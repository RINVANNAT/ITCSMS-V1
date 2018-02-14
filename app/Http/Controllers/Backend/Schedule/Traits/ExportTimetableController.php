<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Models\Group;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableSlot;
use App\Models\Schedule\Timetable\Week;
use App\Repositories\Backend\Schedule\Timetable\EloquentTimetableSlotRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class ExportTimetableController
 * @package App\Http\Controllers\Backend\Schedule\Traits
 */
trait ExportTimetableController
{
    /**
     * @var EloquentTimetableSlotRepository
     */
    protected $exportTimetableSlotRepository;

    /**
     * ExportTimetableController constructor.
     * @param EloquentTimetableSlotRepository $exportTimetableSlotRepository
     */
    public function __construct(EloquentTimetableSlotRepository $exportTimetableSlotRepository)
    {
        $this->exportTimetableSlotRepository = $exportTimetableSlotRepository;
    }

    /**
     * @param EloquentTimetableSlotRepository $exportTimetableSlotRepository
     */
    public function setTimetableSlotRepository($exportTimetableSlotRepository)
    {
        $this->exportTimetableSlotRepository = $exportTimetableSlotRepository;
    }

    /**
     * Get template export file.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function export_timetable($id)
    {
        // find timetable
        $timetable = Timetable::find($id);
        // find all timetable slot by academic year and department id.
        // in order to get weeks.
        $allWeeks = Timetable::where([
            ['academic_year_id', $timetable->academic_year_id],
            ['department_id', $timetable->department_id],
            ['semester_id', $timetable->semester_id]
        ])
            ->select('week_id')
            ->groupBy('week_id')
            ->get();

        $allGroups = Timetable::where([
            ['academic_year_id', $timetable->academic_year_id],
            ['department_id', $timetable->department_id],
            ['semester_id', $timetable->semester_id]
        ])
            ->select('group_id')
            ->groupBy('group_id')
            ->get();

        $weeks = array();
        $groups = array();

        if (count($allWeeks) > 0) {
            foreach ($allWeeks as $week) {
                array_push($weeks, Week::find($week->week_id));

            }
        }

        if (count($allGroups) > 0) {
            foreach ($allGroups as $group) {
                if ($group->group_id != null) {
                    array_push($groups, Group::find($group->group_id));
                }
            }
        }

        usort($weeks, function ($a, $b) {
            return $a->id - $b->id;
        });

        usort($groups, function ($a, $b) {
            if (is_numeric($a->code)) {
                return $a->code - $b->code;
            } else {
                return strcmp($a->code, $b->code);
            }
        });

        return view('backend.schedule.timetables.popup-export', compact('weeks', 'groups', 'timetable'));
    }

    /**
     * Export timetable as excel format.
     * a
     * @return mixed
     */
    public function export_file()
    {
        // dd(request()->all());
        $timetable_id = request('timetable');
        $timetable = Timetable::find($timetable_id);
        $department = $timetable->department->code;
        $groups = request('groups');
        $weeks = request('weeks');
        // dd($timetable->academicYear);

        if ($timetable instanceof Timetable) {
            Excel::create('TIMETABLE-' . $department, function ($excel) use ($timetable, $groups, $weeks) {
                $excel->setTitle('week1');
                // generate sheet excel file.
                if (count($groups) > 0) {
                    if (count($weeks) > 0) {
                        foreach ($groups as $group) {
                            foreach ($weeks as $week) {
                                $findTimetable = $this->find_timetable_export_timetable_file($timetable, $group, $week);
                                if ($findTimetable instanceof Timetable) {
                                    if(!has_half_hour($findTimetable)){
                                        $this->generate_sheet($excel, $findTimetable, $group, $week);
                                    }else{
                                        $this->halfHourTemplate($excel, $findTimetable, null, $week);
                                    }
                                } else {
                                    continue;
                                }
                            }
                        }
                    }
                } else {
                    if (count($weeks) > 0) {
                        foreach ($weeks as $week) {
                            $findTimetable = $this->find_timetable_export_timetable_file($timetable, null, $week);
                            if ($findTimetable instanceof Timetable) {
                                if(!has_half_hour($findTimetable)){
                                    $this->generate_sheet($excel, $findTimetable, null, $week);
                                }else{
                                    $this->halfHourTemplate($excel, $findTimetable, null, $week);
                                }
                            } else {
                                continue;
                            }
                        }
                    }
                }

            })->export('xlsx');
            return Response::json(['status' => true]);
        }
        return Response::json(['status' => false]);
    }

    /**
     * Generate timetable sheet.
     *
     * @param $excel
     * @param Timetable $findTimetable
     * @param null $group
     * @param $week
     * @return mixed
     */
    public function generate_sheet($excel, Timetable $findTimetable, $group = null, $week)
    {
        $filename = ($group == null ? '' : 'Group(' . Group::find($group)->code . ')-') . Week::find($week)->name_en;
        return $excel->sheet($filename, function ($sheet) use ($findTimetable, $group, $week) {
            // prepare header data
            $academicYear = $findTimetable->academicYear->name_latin;
            $department = $findTimetable->department->code;
            $degree = $findTimetable->degree->code;
            $grade = $findTimetable->grade->code;
            $display_group = $group == null ? '' : '(' . Group::find($group)->code . ')';
            $semester = $findTimetable->semester->id == 1 ? 'I' : 'II';
            $week = Week::find($week);
            $timetableSlots = $findTimetable->timetableSlots;
            $timetableSlotsLanguages = new Collection();
            // find student annual ids
            $student_annual_ids = $this->exportTimetableSlotRepository->find_student_annual_ids($findTimetable);
            // have many student annual ids
            if (count($student_annual_ids) > 0) {
                // init array of dept language
                $department_languages = array(12, 13); // (english, french)
                foreach ($department_languages as $department_language) {
                    $groups = $this->exportTimetableSlotRepository->get_group_student_annual_form_language($department_language, $student_annual_ids, $findTimetable);
                    $timetables = $this->exportTimetableSlotRepository->get_timetables_form_language_by_student_annual($groups[0], $findTimetable, $department_language);
                    $timetableSlotsLang = $this->exportTimetableSlotRepository->get_timetable_slot_language_dept($timetables, $groups[0]);
                    $this->exportTimetableSlotRepository->set_timetable_slot_language($timetableSlotsLanguages, $timetableSlotsLang[1], $timetableSlotsLang[0]);
                }
            }

            // insert logo
            $logo = new \PHPExcel_Worksheet_Drawing();
            $logo->setPath(public_path('img/timetable/logo-print.jpg'));
            $logo->setWidth(80);
            $logo->setHeight(60);
            $logo->setOffsetX(25);
            $logo->setCoordinates('A1');
            $logo->setWorksheet($sheet);

            $sheet->setOrientation('landscape');
            $sheet->setFontFamily('Arial Narrow');

            $sheet->mergeCells('C1:I1');
            $sheet->mergeCells('C2:I2');

            $sheet->cells('C1:I2', function ($cells) {
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFontSize(12);
                $cells->setFontWeight('bold');

            });

            // header sheet
            $sheet->row(1, array('', '', 'EMPLOI DU TEMPS ' . $academicYear, '', '', '', '', '', '', 'Semestre - ' . $semester, '', '', ''));
            $sheet->row(2, array('', '', 'Groupe: ' . $department . '-' . $degree . $grade . ($group == null ? '' : $display_group), '', '', '', '', '', '', 'Semaines ' . $week->id, '', '', ''));

            // header table
            $sheet->row(5, array('Horaire', 'Lundi', '', 'Mardi', '', 'Mercredi', '', 'Jeudi', '', 'Vendredi', '', 'Samedi', ''));
            $sheet->setHeight(5, 20);

            // set width header
            $sheet->setWidth(array(
                'A' => 20,
                'B' => 15,
                'C' => 15,
                'D' => 15,
                'E' => 15,
                'F' => 15,
                'G' => 15,
                'H' => 15,
                'I' => 15,
                'J' => 15,
                'K' => 15,
                'L' => 15,
                'M' => 15
            ));

            // semester header
            $sheet->mergeCells('J1:L1');
            $sheet->mergeCells('J2:L2');
            $sheet->cells('J1:L2', function ($cells) {
                $cells->setFontSize(12);
            });

            // merge header
            $sheet->mergeCells('B5:C5');
            $sheet->mergeCells('D5:E5');
            $sheet->mergeCells('F5:G5');
            $sheet->mergeCells('H5:I5');
            $sheet->mergeCells('J5:K5');
            $sheet->mergeCells('L5:M5');

            $sheet->setBorder('A5:M5', 'thin');
            $sheet->cells('A5:M5', function ($cells) {
                $cells->setFontSize(12);
                $cells->setFontWeight('bold');
                $cells->setAlignment('center');
                $cells->setValignment('center');
            });

            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];

            if ($findTimetable->degree->id != 2) {

                $sheet->row(6, array('7h00-7h55'));
                $sheet->mergeCells('A6:A9');

                $sheet->row(10, array('8h00-8h55'));
                $sheet->mergeCells('A10:A13');

                $sheet->row(14, array('9h10-10h05'));
                $sheet->mergeCells('A14:A17');

                $sheet->row(18, array('10h10-11h05'));
                $sheet->mergeCells('A18:A21');

                // 11h-1h
                $sheet->mergeCells('A22:M22');

                $sheet->row(23, array('13h00-13h55'));
                $sheet->mergeCells('A23:A26');

                $sheet->row(27, array('14h00-14h55'));
                $sheet->mergeCells('A27:A30');

                $sheet->row(31, array('15h10-16h05'));
                $sheet->mergeCells('A31:A34');

                $sheet->row(35, array('16h10-17h05'));
                $sheet->mergeCells('A35:A38');


                $sheet->setBorder('A5:A38', 'thin');
                $sheet->cells('A5:A38', function ($cells) {
                    $cells->setFontSize(12);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });
                $countRows = 6;
                // $countColumns = 1;
                for ($timesRow = 0; $timesRow < 9; $timesRow++) {
                    // timesRow = 0 => row = timesRow + countRows = 6;
                    // we start from 6 to [...] row.
                    $countColumns = 1;
                    if ($timesRow == 4) {
                        $sheet->row(22, function ($row) {
                            // call cell manipulation methods
                            $row->setBackground('#f1f1f1');
                        });
                        $countRows = 23;
                        continue;
                    }
                    for ($columnDay = 2; $columnDay <= 7; $columnDay++) {
                        $sheet->cells($columns[$countColumns] . $countRows . ':' . $columns[$countColumns + 1] . ($countRows + 3), function ($cells) {
                            // Set all borders (top, right, bottom, left)
                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });
                        // timetable slot of dept
                        foreach ($timetableSlots as $timetableSlot) {
                            $start = (new Carbon($timetableSlot->start))->hour;
                            $end = (new Carbon($timetableSlot->end))->hour;
                            $day = (new Carbon($timetableSlot->start))->day;

                            if ($columnDay == $day) {
                                if ($timesRow == 0) {
                                    if ($start == 7 && $end >= 8) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 1) {
                                    if ($start <= 8 && $end >= 9) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 2) {
                                    if ($start <= 9 && $end >= 10) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 3) {
                                    if ($start <= 10 && $end >= 11) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 5) {
                                    if ($start == 13 && $end >= 14) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 6) {
                                    if ($start <= 14 && $end >= 15) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 7) {
                                    if ($start <= 15 && $end >= 16) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 8) {
                                    if ($start <= 16 && $end >= 17) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else {
                                    continue;
                                }

                            }
                        }
                        $countColumns += 2;
                    }
                    $countRows += 4;
                }

                // export language course
                if (count($student_annual_ids) > 0 && count($timetableSlotsLanguages)) {

                    for ($timesRow = 7; $timesRow <= 15; $timesRow++) {
                        $countColumns = 1;

                        for ($columnDay = 2; $columnDay <= 7; $columnDay++) {

                            foreach ($timetableSlotsLanguages as $timetableSlotsLanguage) {

                                if ($timesRow == 7) {
                                    $countRows = 6;
                                } elseif ($timesRow == 8) {
                                    $countRows = 10;
                                } elseif ($timesRow == 9) {
                                    $countRows = 14;
                                } elseif ($timesRow == 13) {
                                    $countRows = 23;
                                } elseif ($timesRow == 14) {
                                    $countRows = 27;
                                } elseif ($timesRow == 15) {
                                    $countRows = 31;
                                } else {
                                    continue;
                                }

                                $startDay = (new Carbon($timetableSlotsLanguage['start']))->day;
                                $startHour = (new Carbon($timetableSlotsLanguage['start']))->hour;

                                if (($startDay == $columnDay) && ($startHour == $timesRow)) {

                                    $sheet->mergeCells($columns[$countColumns] . $countRows . ':' . $columns[$countColumns + 1] . $countRows);
                                    $sheet->cell($columns[$countColumns] . $countRows, function ($cell) use ($timetableSlotsLanguage) {
                                        $cell->setValue($timetableSlotsLanguage['course_name']);
                                        $cell->setAlignment('center');
                                        $cell->setFontWeight('bold');
                                    });

                                    for ($i = $countRows + 1; $i <= $countRows + 7; $i++) {
                                        $sheet->mergeCells($columns[$countColumns] . $i . ':' . $columns[$countColumns + 1] . $i);
                                    }

                                    $i = $countRows + 1;

                                    for ($k = 0; $k < count($timetableSlotsLanguage['slotsForLanguage']); $k += 2) {
                                        $sheet->cell($columns[$countColumns] . $i, function ($cell) use ($timetableSlotsLanguage, $k) {
                                            // Set all borders (top, right, bottom, left)
                                            $cell->setBorder('none', 'none', 'none', 'none');
                                            $cell->setFontWeight('bold');
                                            $a = '';
                                            $b = '';
                                            if (isset($timetableSlotsLanguage['slotsForLanguage'][$k])) {
                                                $a = 'Gr.' . $timetableSlotsLanguage['slotsForLanguage'][$k]['group'] . ':' . $timetableSlotsLanguage['slotsForLanguage'][$k]['room'] . '-' . $timetableSlotsLanguage['slotsForLanguage'][$k]['building'];
                                            }
                                            if (isset($timetableSlotsLanguage['slotsForLanguage'][$k + 1])) {
                                                $b = 'Gr.' . $timetableSlotsLanguage['slotsForLanguage'][$k + 1]['group'] . ':' . $timetableSlotsLanguage['slotsForLanguage'][$k + 1]['room'] . '-' . $timetableSlotsLanguage['slotsForLanguage'][$k + 1]['building'];
                                            }
                                            if ($a != '' && $b != '') {
                                                $cell->setValue($a . ', ' . $b);
                                            } else if ($a != '' && $b == '') {
                                                $cell->setValue($a);
                                            }
                                            $cell->setAlignment('center');
                                        });
                                        $i++;
                                    }
                                }
                            }
                            $countColumns += 2;
                        }
                    }
                }

                // border bottom cells
                // Set all borders (top, right, bottom, left)
                $sheet->cells('A38:M38', function ($cells) {
                    $cells->setFontSize(10);
                });

                $sheet->cells('M6:M38', function ($cells) {
                    $cells->setFontSize(10);
                });

            } else {
                $sheet->row(6, array('17h30-18h05'));
                $sheet->mergeCells('A6:A9');

                $sheet->row(10, array('18h10-10h05'));
                $sheet->mergeCells('A10:A13');

                $sheet->setBorder('A5:A13', 'thin');
                $sheet->cells('A5:A13', function ($cells) {
                    $cells->setFontSize(12);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });
                $countRows = 6;
                // $countColumns = 1;
                for ($timesRow = 0; $timesRow < 2; $timesRow++) {
                    // timesRow = 0 => row = timesRow + countRows = 6;
                    // we start from 6 to [...] row.
                    $countColumns = 1;
                    for ($columnDay = 2; $columnDay <= 7; $columnDay++) {
                        $sheet->cells($columns[$countColumns] . $countRows . ':' . $columns[$countColumns + 1] . ($countRows + 3), function ($cells) {
                            // Set all borders (top, right, bottom, left)
                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });
                        // timetable slot of dept
                        foreach ($timetableSlots as $timetableSlot) {
                            $start = (new Carbon($timetableSlot->start))->hour;
                            $end = (new Carbon($timetableSlot->end))->hour;
                            $day = (new Carbon($timetableSlot->start))->day;

                            if ($columnDay == $day) {
                                if ($timesRow == 0) {
                                    if ($start == 17 && $end >= 18) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 1) {
                                    if ($start <= 18 && $end >= 19) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else {
                                    continue;
                                }

                            }
                        }
                        $countColumns += 2;
                    }
                    $countRows += 4;
                }

                // export language course
                if (count($student_annual_ids) > 0 && count($timetableSlotsLanguages)) {

                    for ($timesRow = 7; $timesRow <= 9; $timesRow++) {
                        $countColumns = 1;

                        for ($columnDay = 2; $columnDay <= 7; $columnDay++) {

                            foreach ($timetableSlotsLanguages as $timetableSlotsLanguage) {

                                if ($timesRow == 7) {
                                    $countRows = 6;
                                } elseif ($timesRow == 8) {
                                    $countRows = 10;
                                } elseif ($timesRow == 9) {
                                    $countRows = 14;
                                } else {
                                    continue;
                                }

                                $startDay = (new Carbon($timetableSlotsLanguage['start']))->day;
                                $startHour = (new Carbon($timetableSlotsLanguage['start']))->hour;

                                if (($startDay == $columnDay) && ($startHour == $timesRow)) {

                                    $sheet->mergeCells($columns[$countColumns] . $countRows . ':' . $columns[$countColumns + 1] . $countRows);
                                    $sheet->cell($columns[$countColumns] . $countRows, function ($cell) use ($timetableSlotsLanguage) {
                                        $cell->setValue($timetableSlotsLanguage['course_name']);
                                        $cell->setAlignment('center');
                                        $cell->setFontWeight('bold');
                                    });

                                    for ($i = $countRows + 1; $i <= $countRows + 7; $i++) {
                                        $sheet->mergeCells($columns[$countColumns] . $i . ':' . $columns[$countColumns + 1] . $i);
                                    }

                                    $i = $countRows + 1;

                                    for ($k = 0; $k < count($timetableSlotsLanguage['slotsForLanguage']); $k += 2) {
                                        $sheet->cell($columns[$countColumns] . $i, function ($cell) use ($timetableSlotsLanguage, $k) {
                                            // Set all borders (top, right, bottom, left)
                                            $cell->setBorder('none', 'none', 'none', 'none');
                                            $cell->setFontWeight('bold');
                                            $a = '';
                                            $b = '';
                                            if (isset($timetableSlotsLanguage['slotsForLanguage'][$k])) {
                                                $a = 'Gr.' . $timetableSlotsLanguage['slotsForLanguage'][$k]['group'] . ':' . $timetableSlotsLanguage['slotsForLanguage'][$k]['room'] . '-' . $timetableSlotsLanguage['slotsForLanguage'][$k]['building'];
                                            }
                                            if (isset($timetableSlotsLanguage['slotsForLanguage'][$k + 1])) {
                                                $b = 'Gr.' . $timetableSlotsLanguage['slotsForLanguage'][$k + 1]['group'] . ':' . $timetableSlotsLanguage['slotsForLanguage'][$k + 1]['room'] . '-' . $timetableSlotsLanguage['slotsForLanguage'][$k + 1]['building'];
                                            }
                                            if ($a != '' && $b != '') {
                                                $cell->setValue($a . ', ' . $b);
                                            } else if ($a != '' && $b == '') {
                                                $cell->setValue($a);
                                            }
                                            $cell->setAlignment('center');
                                        });
                                        $i++;
                                    }
                                }
                            }
                            $countColumns += 2;
                        }
                    }
                }

                // border bottom cells
                // Set all borders (top, right, bottom, left)
                $sheet->cells('A38:M38', function ($cells) {
                    $cells->setFontSize(10);
                });

                $sheet->cells('M6:M38', function ($cells) {
                    $cells->setFontSize(10);
                });
            }
        });
    }

    /**
     * Find timetable by reference to another timetable and group, week.
     *
     * @param Timetable $timetable
     * @param null $group
     * @param $week
     * @return mixed
     */
    public function find_timetable_export_timetable_file(Timetable $timetable, $group = null, $week)
    {
        return Timetable::where([
            ['academic_year_id', $timetable->academic_year_id],
            ['department_id', $timetable->department_id],
            ['degree_id', $timetable->degree_id],
            ['option_id', $timetable->option_id],
            ['grade_id', $timetable->grade_id],
            ['semester_id', $timetable->semester_id],
            ['group_id', $group],
            ['week_id', $week]
        ])->first();
    }

    /**
     * Append data into each row.
     *
     * @param $sheet
     * @param $columns
     * @param $countColumns
     * @param $countRows
     * @param TimetableSlot $timetableSlot
     */
    public function append_data($sheet, $columns, $countColumns, $countRows, TimetableSlot $timetableSlot)
    {
        //dd($timetableSlot->room->building);
        //$sheet->setBorder($columns[$countColumns+1] . $countRows . ':' . $columns[$countColumns + 1] . ($countRows + 3), 'thin');

        $sheet->mergeCells($columns[$countColumns] . $countRows . ':' . $columns[$countColumns + 1] . $countRows);
        $sheet->cell($columns[$countColumns] . $countRows, function ($cell) use ($timetableSlot) {
            $cell->setValue($timetableSlot->type);
            // Set all borders (top, right, bottom, left)
            $cell->setBorder('thin', 'thin', 'none', 'thin');
            $cell->setAlignment('right');
        });

        $sheet->mergeCells($columns[$countColumns] . ($countRows + 1) . ':' . $columns[$countColumns + 1] . ($countRows + 1));
        $sheet->cell($columns[$countColumns] . ($countRows + 1), function ($cell) use ($timetableSlot) {
            $cell->setValue(str_limit($timetableSlot->course_name, 30));
            $cell->setFontWeight('bold');
            // Set all borders (top, right, bottom, left)
            $cell->setBorder('none', 'thin', 'none', 'thin');
            $cell->setAlignment('center');
        });

        $sheet->mergeCells($columns[$countColumns] . ($countRows + 2) . ':' . $columns[$countColumns + 1] . ($countRows + 2));
        $sheet->cell($columns[$countColumns] . ($countRows + 2), function ($cell) use ($timetableSlot) {
            $teacher_name = ($timetableSlot->lecturer_id == null ? 'No Lecturer' : $timetableSlot->employee->name_latin);
            $cell->setValue($teacher_name);
            // Set all borders (top, right, bottom, left)
            $cell->setBorder('none', 'thin', 'none', 'thin');
            $cell->setAlignment('center');
        });

        $sheet->mergeCells($columns[$countColumns] . ($countRows + 3) . ':' . $columns[$countColumns + 1] . ($countRows + 3));
        $sheet->cell($columns[$countColumns] . ($countRows + 3), function ($cell) use ($timetableSlot) {
            if ($timetableSlot->room !== null) {
                $cell->setValue(($timetableSlot->room !== null ? $timetableSlot->room->building->code . '-' . $timetableSlot->room->name : 'NULL'));
            } else {
                /*$cell->setBackground('#dd4b39');*/
                $cell->setFontColor('#dd4b39');
                $cell->setValue('NO ROOM');

            }
            $cell->setAlignment('right');
            // Set all borders (top, right, bottom, left)
            $cell->setBorder('none', 'thin', 'thin', 'thin');
        });
    }

    /**
     * @param $excel
     * @param Timetable $findTimetable
     * @param null $group
     * @param $week
     * @return mixed
     */
    public function halfHourTemplate($excel, Timetable $findTimetable, $group = null, $week){
        $filename = ($group == null ? '' : 'Group(' . Group::find($group)->code . ')-') . Week::find($week)->name_en;
        return $excel->sheet($filename, function ($sheet) use ($findTimetable, $group, $week) {
            // prepare header data
            $academicYear = $findTimetable->academicYear->name_latin;
            $department = $findTimetable->department->code;
            $degree = $findTimetable->degree->code;
            $grade = $findTimetable->grade->code;
            $display_group = $group == null ? '' : '(' . Group::find($group)->code . ')';
            $semester = $findTimetable->semester->id == 1 ? 'I' : 'II';
            $week = Week::find($week);
            $timetableSlots = $findTimetable->timetableSlots;
            $timetableSlotsLanguages = new Collection();
            // find student annual ids
            $student_annual_ids = $this->exportTimetableSlotRepository->find_student_annual_ids($findTimetable);
            // have many student annual ids
            if (count($student_annual_ids) > 0) {
                // init array of dept language
                $department_languages = array(12, 13); // (english, french)
                foreach ($department_languages as $department_language) {
                    $groups = $this->exportTimetableSlotRepository->find_group_student_annual_form_language($department_language, $student_annual_ids, $findTimetable);
                    $timetables = $this->exportTimetableSlotRepository->get_timetables_form_language_by_student_annual($groups[0], $findTimetable, $department_language);
                    $timetableSlotsLang = $this->exportTimetableSlotRepository->get_timetable_slot_language_dept($timetables, $groups[0]);
                    $this->exportTimetableSlotRepository->set_timetable_slot_language($timetableSlotsLanguages, $timetableSlotsLang[1], $timetableSlotsLang[0]);
                }
            }

            // insert logo
            $logo = new \PHPExcel_Worksheet_Drawing();
            $logo->setPath(public_path('img/timetable/logo-print.jpg'));
            $logo->setWidth(80);
            $logo->setHeight(60);
            $logo->setOffsetX(25);
            $logo->setCoordinates('A1');
            $logo->setWorksheet($sheet);

            $sheet->setOrientation('landscape');
            $sheet->setFontFamily('Arial Narrow');

            $sheet->mergeCells('C1:I1');
            $sheet->mergeCells('C2:I2');

            $sheet->cells('C1:I2', function ($cells) {
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setFontSize(12);
                $cells->setFontWeight('bold');

            });

            // header sheet
            $sheet->row(1, array('', '', 'EMPLOI DU TEMPS ' . $academicYear, '', '', '', '', '', '', 'Semestre - ' . $semester, '', '', ''));
            $sheet->row(2, array('', '', 'Groupe: ' . $department . '-' . $degree . $grade . ($group == null ? '' : $display_group), '', '', '', '', '', '', 'Semaines ' . $week->id, '', '', ''));

            // header table
            $sheet->row(5, array('Horaire', 'Lundi', '', 'Mardi', '', 'Mercredi', '', 'Jeudi', '', 'Vendredi', '', 'Samedi', ''));
            $sheet->setHeight(5, 20);

            // set width header
            $sheet->setWidth(array(
                'A' => 20,
                'B' => 15,
                'C' => 15,
                'D' => 15,
                'E' => 15,
                'F' => 15,
                'G' => 15,
                'H' => 15,
                'I' => 15,
                'J' => 15,
                'K' => 15,
                'L' => 15,
                'M' => 15
            ));

            // semester header
            $sheet->mergeCells('J1:L1');
            $sheet->mergeCells('J2:L2');
            $sheet->cells('J1:L2', function ($cells) {
                $cells->setFontSize(12);
            });

            // merge header
            $sheet->mergeCells('B5:C5');
            $sheet->mergeCells('D5:E5');
            $sheet->mergeCells('F5:G5');
            $sheet->mergeCells('H5:I5');
            $sheet->mergeCells('J5:K5');
            $sheet->mergeCells('L5:M5');

            $sheet->setBorder('A5:M5', 'thin');
            $sheet->cells('A5:M5', function ($cells) {
                $cells->setFontSize(12);
                $cells->setFontWeight('bold');
                $cells->setAlignment('center');
                $cells->setValignment('center');
            });

            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];

            if ($findTimetable->degree->id != 2) { // associate

                $sheet->row(6, array('7h00-7h30'));
                $sheet->mergeCells('A6:A9');

                $sheet->row(10, array('7h30-8h00'));
                $sheet->mergeCells('A10:A13');

                $sheet->row(14, array('8h00-8h30'));
                $sheet->mergeCells('A14:A17');

                $sheet->row(18, array('8h30-9h00'));
                $sheet->mergeCells('A18:A21');

                $sheet->row(22, array('9h00-9h30'));
                $sheet->mergeCells('A22:A25');

                $sheet->row(26, array('9h30-10h00'));
                $sheet->mergeCells('A26:A29');

                $sheet->row(30, array('10h00-10h30'));
                $sheet->mergeCells('A30:A33');

                $sheet->row(34, array('10h30-11h00'));
                $sheet->mergeCells('A34:A37');

                $sheet->mergeCells('A38:M38');

                $sheet->row(39, array('13h00-13h30'));
                $sheet->mergeCells('A39:A42');

                $sheet->row(43, array('13h30-14h00'));
                $sheet->mergeCells('A43:A46');

                $sheet->row(47, array('14h00-14h30'));
                $sheet->mergeCells('A47:A50');

                $sheet->row(51, array('14h30-15h00'));
                $sheet->mergeCells('A51:A54');

                $sheet->row(55, array('15h00-15h30'));
                $sheet->mergeCells('A55:A58');

                $sheet->row(59, array('15h30-16h00'));
                $sheet->mergeCells('A59:A62');

                $sheet->row(63, array('16h00-16h30'));
                $sheet->mergeCells('A63:A66');

                $sheet->row(67, array('16h30-17h00'));
                $sheet->mergeCells('A67:A70');


                $sheet->setBorder('A5:A70', 'thin');
                $sheet->cells('A5:A70', function ($cells) {
                    $cells->setFontSize(12);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });

                $countRows = 6;
                // $countColumns = 1;
                for ($timesRow = 0; $timesRow < 17; $timesRow++) {
                    // timesRow = 0 => row = timesRow + countRows = 6;
                    // we start from 6 to [...] row.
                    $countColumns = 1;
                    if ($timesRow == 8) {
                        $sheet->row(38, function ($row) {
                            // call cell manipulation methods
                            $row->setBackground('#f1f1f1');
                        });
                        $countRows = 39;
                        continue;
                    }
                    for ($columnDay = 2; $columnDay <= 7; $columnDay++) {
                        $sheet->cells($columns[$countColumns] . $countRows . ':' . $columns[$countColumns + 1] . ($countRows + 3), function ($cells) {
                            // Set all borders (top, right, bottom, left)
                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });
                        // timetable slot of dept
                        foreach ($timetableSlots as $timetableSlot) {
                            $start = $timetableSlot->start;
                            $end = $timetableSlot->end;
                            $day = (new Carbon($timetableSlot->start))->day;

                            if ($columnDay == $day) {
                                if ($timesRow == 0) {
                                    if (get_date_str($start) == '7') {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 1) {
                                    if (
                                        (get_date_str($start) == '7:30')
                                        ||
                                        (get_date_str($start) == '7' && (get_date_str($end) == '8' || get_date_str($end) == '8:30' || get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                        ) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 2) {
                                    if (
                                        (get_date_str($start) == '8')
                                        ||
                                        (
                                            (get_date_str($start) == '7' && (get_date_str($end) == '8:30' || get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '7:30' && (get_date_str($end) == '8:30' || get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                        )
                                        ) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 3) {
                                    if (
                                        (get_date_str($start) == '8:30')
                                        ||
                                        (
                                            (get_date_str($start) == '7' && (get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '7:30' && (get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8' && (get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                        )
                                        ) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                }
                                else if ($timesRow == 4) {
                                    if (
                                        (get_date_str($start) == '9')
                                        ||
                                        (
                                            (get_date_str($start) == '7' && (get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '7:30' && (get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8' && (get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8:30' && (get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                        )
                                        ) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                }else if ($timesRow == 5) {
                                    if (
                                        (get_date_str($start) == '9:30')
                                        ||
                                        (
                                            (get_date_str($start) == '7' && (get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '7:30' && (get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8' && (get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8:30' && (get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '9' && (get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                        )
                                        ) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 6) {
                                    if (
                                        (get_date_str($start) == '10')
                                        ||
                                        (
                                            (get_date_str($start) == '7' && (get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '7:30' && (get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8' && (get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8:30' && (get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '9' && (get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '9:30' && (get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                        )
                                        ) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 7) {
                                    if (
                                        (get_date_str($start) == '10:30')
                                        ||
                                        (
                                            (get_date_str($start) == '7' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '7:30' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '8' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '8:30' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '9' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '9:30' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '10' && get_date_str($end) == '11')
                                        )
                                        ) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 9) {
                                    if (get_date_str($start) == '13') {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 10) {
                                    if (
                                        (get_date_str($start) == '13:30')
                                        ||
                                        (get_date_str($start) == '13' && (get_date_str($end) == '14' || get_date_str($end) == '14:30' || get_date_str($end) == '15' || get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                    ) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 11) {
                                    if (
                                        (get_date_str($start) == '14')
                                        ||
                                        (
                                            (get_date_str($start) == '13' && (get_date_str($end) == '14:30' || get_date_str($end) == '15' || get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '13:30' && (get_date_str($end) == '14:30' || get_date_str($end) == '15' || get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                        )
                                    ) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 12) {
                                    if (
                                        (get_date_str($start) == '14:30')
                                        ||
                                        (
                                            (get_date_str($start) == '13' && (get_date_str($end) == '15' || get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '13:30' && (get_date_str($end) == '15' || get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14' && (get_date_str($end) == '15' || get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                        )
                                    ) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                }
                                else if ($timesRow == 13) {
                                    if (
                                        (get_date_str($start) == '15')
                                        ||
                                        (
                                            (get_date_str($start) == '13' && (get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '13:30' && (get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14' && (get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14:30' && (get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                        )
                                    ) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                }else if ($timesRow == 14) {
                                    if (
                                        (get_date_str($start) == '15:30')
                                        ||
                                        (
                                            (get_date_str($start) == '13' && (get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '13:30' && (get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14' && (get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14:30' && (get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '15' && (get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                        )
                                    ) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 15) {
                                    if (
                                        (get_date_str($start) == '16')
                                        ||
                                        (
                                            (get_date_str($start) == '13' && (get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '13:30' && (get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14' && (get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14:30' && (get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '15' && (get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '15:30' && (get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                        )
                                    ) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 16) {
                                    if (
                                        (get_date_str($start) == '16:30')
                                        ||
                                        (
                                            (get_date_str($start) == '13' && get_date_str($end) == '17')
                                            ||
                                            (get_date_str($start) == '13:30' && get_date_str($end) == '17')
                                            ||
                                            (get_date_str($start) == '14' && get_date_str($end) == '17')
                                            ||
                                            (get_date_str($start) == '14:30' && get_date_str($end) == '17')
                                            ||
                                            (get_date_str($start) == '15' && get_date_str($end) == '17')
                                            ||
                                            (get_date_str($start) == '15:30' && get_date_str($end) == '17')
                                            ||
                                            (get_date_str($start) == '16' && get_date_str($end) == '17')
                                        )
                                    ) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                }
                                else {
                                    continue;
                                }

                            }
                        }
                        $countColumns += 2;
                    }
                    $countRows += 4;
                }

                // export language course
                if (count($student_annual_ids) > 0 && count($timetableSlotsLanguages) > 0) {

                    for ($timesRow = 7; $timesRow <= 15; $timesRow++) {
                        $countColumns = 1;

                        for ($columnDay = 2; $columnDay <= 7; $columnDay++) {

                            foreach ($timetableSlotsLanguages as $timetableSlotsLanguage) {

                                if ($timesRow == 7) {
                                    $countRows = 6;
                                } elseif ($timesRow == 8) {
                                    $countRows = 10;
                                } elseif ($timesRow == 9) {
                                    $countRows = 14;
                                } elseif ($timesRow == 10) {
                                    $countRows = 18;
                                } elseif ($timesRow == 11) {
                                    $countRows = 22;
                                } elseif ($timesRow == 12) {
                                    $countRows = 26;
                                } elseif ($timesRow == 13) {
                                    $countRows = 30;
                                } elseif ($timesRow == 14) {
                                    $countRows = 34;
                                } elseif ($timesRow == 16) {
                                    $countRows = 39;
                                } elseif ($timesRow == 17) {
                                    $countRows = 43;
                                } elseif ($timesRow == 18) {
                                    $countRows = 47;
                                } elseif ($timesRow == 19) {
                                    $countRows = 51;
                                } elseif ($timesRow == 20) {
                                    $countRows = 55;
                                } elseif ($timesRow == 21) {
                                    $countRows = 59;
                                } elseif ($timesRow == 22) {
                                    $countRows = 63;
                                } elseif ($timesRow == 23) {
                                    $countRows = 64;
                                } else {
                                    continue;
                                }

                                $startDay = (new Carbon($timetableSlotsLanguage['start']))->day;
                                $startHour = (new Carbon($timetableSlotsLanguage['start']))->hour;

                                if (($startDay == $columnDay) && ($startHour == $timesRow)) {

                                    $sheet->mergeCells($columns[$countColumns] . $countRows . ':' . $columns[$countColumns + 1] . $countRows);
                                    $sheet->cell($columns[$countColumns] . $countRows, function ($cell) use ($timetableSlotsLanguage) {
                                        $cell->setValue($timetableSlotsLanguage['course_name']);
                                        $cell->setAlignment('center');
                                        $cell->setFontWeight('bold');
                                    });

                                    for ($i = $countRows + 1; $i <= $countRows + 7; $i++) {
                                        $sheet->mergeCells($columns[$countColumns] . $i . ':' . $columns[$countColumns + 1] . $i);
                                    }

                                    $i = $countRows + 1;

                                    for ($k = 0; $k < count($timetableSlotsLanguage['slotsForLanguage']); $k += 2) {
                                        $sheet->cell($columns[$countColumns] . $i, function ($cell) use ($timetableSlotsLanguage, $k) {
                                            // Set all borders (top, right, bottom, left)
                                            $cell->setBorder('none', 'none', 'none', 'none');
                                            $cell->setFontWeight('bold');
                                            $a = '';
                                            $b = '';
                                            if (isset($timetableSlotsLanguage['slotsForLanguage'][$k])) {
                                                $a = 'Gr.' . $timetableSlotsLanguage['slotsForLanguage'][$k]['group'] . ':' . $timetableSlotsLanguage['slotsForLanguage'][$k]['room'] . '-' . $timetableSlotsLanguage['slotsForLanguage'][$k]['building'];
                                            }
                                            if (isset($timetableSlotsLanguage['slotsForLanguage'][$k + 1])) {
                                                $b = 'Gr.' . $timetableSlotsLanguage['slotsForLanguage'][$k + 1]['group'] . ':' . $timetableSlotsLanguage['slotsForLanguage'][$k + 1]['room'] . '-' . $timetableSlotsLanguage['slotsForLanguage'][$k + 1]['building'];
                                            }
                                            if ($a != '' && $b != '') {
                                                $cell->setValue($a . ', ' . $b);
                                            } else if ($a != '' && $b == '') {
                                                $cell->setValue($a);
                                            }
                                            $cell->setAlignment('center');
                                        });
                                        $i++;
                                    }
                                }
                            }
                            $countColumns += 2;
                        }
                    }
                }
            }
            else {
                $sheet->row(6, array('17h30-18h05'));
                $sheet->mergeCells('A6:A9');

                $sheet->row(10, array('18h10-10h05'));
                $sheet->mergeCells('A10:A13');

                $sheet->setBorder('A5:A13', 'thin');
                $sheet->cells('A5:A13', function ($cells) {
                    $cells->setFontSize(12);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });
                $countRows = 6;
                // $countColumns = 1;
                for ($timesRow = 0; $timesRow < 2; $timesRow++) {
                    // timesRow = 0 => row = timesRow + countRows = 6;
                    // we start from 6 to [...] row.
                    $countColumns = 1;
                    for ($columnDay = 2; $columnDay <= 7; $columnDay++) {
                        $sheet->cells($columns[$countColumns] . $countRows . ':' . $columns[$countColumns + 1] . ($countRows + 3), function ($cells) {
                            // Set all borders (top, right, bottom, left)
                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });
                        // timetable slot of dept
                        foreach ($timetableSlots as $timetableSlot) {
                            $start = (new Carbon($timetableSlot->start))->hour;
                            $end = (new Carbon($timetableSlot->end))->hour;
                            $day = (new Carbon($timetableSlot->start))->day;

                            if ($columnDay == $day) {
                                if ($timesRow == 0) {
                                    if ($start == 17 && $end >= 18) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else if ($timesRow == 1) {
                                    if ($start <= 18 && $end >= 19) {
                                        $this->append_data($sheet, $columns, $countColumns, $countRows, $timetableSlot);
                                        break;
                                    }
                                } else {
                                    continue;
                                }

                            }
                        }
                        $countColumns += 2;
                    }
                    $countRows += 4;
                }

                // export language course
                if (count($student_annual_ids) > 0 && count($timetableSlotsLanguages)) {

                    for ($timesRow = 7; $timesRow <= 9; $timesRow++) {
                        $countColumns = 1;

                        for ($columnDay = 2; $columnDay <= 7; $columnDay++) {

                            foreach ($timetableSlotsLanguages as $timetableSlotsLanguage) {

                                if ($timesRow == 7) {
                                    $countRows = 6;
                                } elseif ($timesRow == 8) {
                                    $countRows = 10;
                                } elseif ($timesRow == 9) {
                                    $countRows = 14;
                                } else {
                                    continue;
                                }

                                $startDay = (new Carbon($timetableSlotsLanguage['start']))->day;
                                $startHour = (new Carbon($timetableSlotsLanguage['start']))->hour;

                                if (($startDay == $columnDay) && ($startHour == $timesRow)) {

                                    $sheet->mergeCells($columns[$countColumns] . $countRows . ':' . $columns[$countColumns + 1] . $countRows);
                                    $sheet->cell($columns[$countColumns] . $countRows, function ($cell) use ($timetableSlotsLanguage) {
                                        $cell->setValue($timetableSlotsLanguage['course_name']);
                                        $cell->setAlignment('center');
                                        $cell->setFontWeight('bold');
                                    });

                                    for ($i = $countRows + 1; $i <= $countRows + 7; $i++) {
                                        $sheet->mergeCells($columns[$countColumns] . $i . ':' . $columns[$countColumns + 1] . $i);
                                    }

                                    $i = $countRows + 1;

                                    for ($k = 0; $k < count($timetableSlotsLanguage['slotsForLanguage']); $k += 2) {
                                        $sheet->cell($columns[$countColumns] . $i, function ($cell) use ($timetableSlotsLanguage, $k) {
                                            // Set all borders (top, right, bottom, left)
                                            $cell->setBorder('none', 'none', 'none', 'none');
                                            $cell->setFontWeight('bold');
                                            $a = '';
                                            $b = '';
                                            if (isset($timetableSlotsLanguage['slotsForLanguage'][$k])) {
                                                $a = 'Gr.' . $timetableSlotsLanguage['slotsForLanguage'][$k]['group'] . ':' . $timetableSlotsLanguage['slotsForLanguage'][$k]['room'] . '-' . $timetableSlotsLanguage['slotsForLanguage'][$k]['building'];
                                            }
                                            if (isset($timetableSlotsLanguage['slotsForLanguage'][$k + 1])) {
                                                $b = 'Gr.' . $timetableSlotsLanguage['slotsForLanguage'][$k + 1]['group'] . ':' . $timetableSlotsLanguage['slotsForLanguage'][$k + 1]['room'] . '-' . $timetableSlotsLanguage['slotsForLanguage'][$k + 1]['building'];
                                            }
                                            if ($a != '' && $b != '') {
                                                $cell->setValue($a . ', ' . $b);
                                            } else if ($a != '' && $b == '') {
                                                $cell->setValue($a);
                                            }
                                            $cell->setAlignment('center');
                                        });
                                        $i++;
                                    }
                                }
                            }
                            $countColumns += 2;
                        }
                    }
                }

                // border bottom cells
                // Set all borders (top, right, bottom, left)
                $sheet->cells('A38:M38', function ($cells) {
                    $cells->setFontSize(10);
                });

                $sheet->cells('M6:M38', function ($cells) {
                    $cells->setFontSize(10);
                });
            }
        });
    }
}