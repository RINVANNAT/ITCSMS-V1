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
    protected $timetableSlotRepository;

    /**
     * ExportTimetableController constructor.
     * @param EloquentTimetableSlotRepository $timetableSlotRepository
     */
    public function __construct(EloquentTimetableSlotRepository $timetableSlotRepository)
    {
        $this->timetableSlotRepository = $timetableSlotRepository;
    }

    /**
     * @param EloquentTimetableSlotRepository $timetableSlotRepository
     */
    public function setTimetableSlotRepository($timetableSlotRepository)
    {
        $this->timetableSlotRepository = $timetableSlotRepository;
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
                                    $this->generate_sheet($excel, $findTimetable, $group, $week);
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
                                $this->generate_sheet($excel, $findTimetable, null, $week);
                            } else {
                                continue;
                            }
                        }
                    }
                }

            })->export('xls');
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
            $student_annual_ids = $this->timetableSlotRepository->find_student_annual_ids($findTimetable);
            // have many student annual ids
            if (count($student_annual_ids) > 0) {
                // init array of dept language
                $department_languages = array(12, 13); // (english, french)

                foreach ($department_languages as $department_language) {
                    $groups = $this->timetableSlotRepository->find_group_student_annual_form_language($department_language, $student_annual_ids, $findTimetable);
                    $timetables = $this->timetableSlotRepository->get_timetables_form_language_by_student_annual($groups[0], $findTimetable, $department_language);
                    $timetableSlotsLang = $this->timetableSlotRepository->get_timetable_slot_language_dept($timetables, $groups[0]);
                    $this->timetableSlotRepository->set_timetable_slot_language($timetableSlotsLanguages, $timetableSlotsLang[1], $timetableSlotsLang[0]);
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

            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];

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
                    // timetable slot language
                    /*if(count($student_annual_ids)>0){
                        foreach ($timetableSlotsLanguages as $timetableSlotsLanguage) {
                            $start = (new Carbon($timetableSlotsLanguage['start']))->hour;
                            $end = (new Carbon($timetableSlotsLanguage['end']))->hour;
                            $day = (new Carbon($timetableSlotsLanguage['start']))->day;

                            if ($columnDay == $day) {
                                if ($timesRow == 0) {
                                    if ($start == 7 && $end >= 8) {
                                        dd($timetableSlotsLanguage['room']);
                                        break;
                                    }
                                } else if ($timesRow == 1) {
                                    if ($start <= 8 && $end >= 9) {
                                        dd($timetableSlotsLanguage['group']);
                                        break;
                                    }
                                } else if ($timesRow == 2) {
                                    if ($start <= 9 && $end >= 10) {
                                        dd($timetableSlotsLanguage['building']);
                                        break;
                                    }
                                } else if ($timesRow == 3) {
                                    if ($start <= 10 && $end >= 11) {
                                        dd($timetableSlotsLanguage['room']);
                                        break;
                                    }
                                } else if ($timesRow == 5) {
                                    if ($start == 13 && $end >= 14) {
                                        dd($timetableSlotsLanguage['room']);
                                        break;
                                    }
                                } else if ($timesRow == 6) {
                                    if ($start <= 14 && $end >= 15) {
                                        dd($timetableSlotsLanguage['room']);
                                        break;
                                    }
                                } else if ($timesRow == 7) {
                                    if ($start <= 15 && $end >= 16) {
                                        dd($timetableSlotsLanguage['room']);
                                        break;
                                    }
                                } else if ($timesRow == 8) {
                                    if ($start <= 16 && $end >= 17) {
                                        dd($timetableSlotsLanguage['room']);
                                        break;
                                    }
                                } else {
                                    continue;
                                }

                            }
                        }
                    }*/
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

            // border bottom cells
            // Set all borders (top, right, bottom, left)
            $sheet->cells('A38:M38', function ($cells) {
                $cells->setFontSize(10);
            });

            $sheet->cells('M6:M38', function ($cells) {
                $cells->setFontSize(10);
            });
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
            $cell->setValue($timetableSlot->teacher_name);
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
}