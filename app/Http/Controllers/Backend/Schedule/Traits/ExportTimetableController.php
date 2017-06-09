<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Models\Group;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\Week;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class ExportTimetableController
 * @package App\Http\Controllers\Backend\Schedule\Traits
 */
trait ExportTimetableController
{
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

    public function export_file()
    {
        Excel::create('timetable-', function ($excel) {
            $excel->setTitle('week1');
            foreach (request('weeks') as $item) {
                $excel->sheet(Week::find($item)->name_en, function ($sheet) use ($item) {
                    $sheet->setOrientation('landscape');

                    $sheet->mergeCells('C1:I1');
                    $sheet->mergeCells('C2:I2');
                    $sheet->cells('C1:I2', function ($cells) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                        $cells->setFontSize(14);
                        $cells->setFontWeight('bold');

                    });

                    // header sheet
                    $sheet->row(1, array('', '', 'EMPLOI DU TEMPS 2016 - 2017', '', '', '', '', '', '', 'Semester I', '', '', ''));
                    $sheet->row(2, array('', '', 'Groupe: I1 (1) -TC', '', '', '', '', '', '', 'Week ' . $item, '', '', ''));

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

                    $sheet->row(6, array('07h00 - 08h00'));
                    $sheet->mergeCells('A6:A9');

                    $sheet->row(10, array('08h00 - 09h00'));
                    $sheet->mergeCells('A10:A13');

                    $sheet->row(14, array('09h00 - 10h00'));
                    $sheet->mergeCells('A14:A17');

                    $sheet->row(18, array('10h00 - 11h00'));
                    $sheet->mergeCells('A18:A21');

                    // 11h - 1h
                    $sheet->mergeCells('A22:M22');

                    $sheet->row(23, array('13h00 - 14h00'));
                    $sheet->mergeCells('A23:A26');

                    $sheet->row(27, array('14h00 - 15h00'));
                    $sheet->mergeCells('A27:A30');

                    $sheet->row(31, array('15h00 - 16h00'));
                    $sheet->mergeCells('A31:A34');

                    $sheet->row(35, array('16h00 - 17h00'));
                    $sheet->mergeCells('A35:A38');


                    $sheet->setBorder('A5:A38', 'thin');
                    $sheet->cells('A5:A38', function ($cells) {
                        $cells->setFontSize(12);
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                    });

                    // border each cells for morning
                    for ($i = 6; $i <= 18; $i = $i + 4) {
                        $sheet->cells('B' . $i . ':C' . ($i + 3) . '', function ($cells) {
                            $cells->setBorder('none', 'thin', 'thin', 'none');
                        });
                    }
                    for ($i = 6; $i <= 18; $i = $i + 4) {
                        $sheet->cells('D' . $i . ':E' . ($i + 3) . '', function ($cells) {
                            $cells->setBorder('none', 'thin', 'thin', 'none');
                        });
                    }
                    for ($i = 6; $i <= 18; $i = $i + 4) {
                        $sheet->cells('F' . $i . ':G' . ($i + 3) . '', function ($cells) {
                            $cells->setBorder('none', 'thin', 'thin', 'none');
                        });
                    }
                    for ($i = 6; $i <= 18; $i = $i + 4) {
                        $sheet->cells('H' . $i . ':I' . ($i + 3) . '', function ($cells) {
                            $cells->setBorder('none', 'thin', 'thin', 'none');
                        });
                    }
                    for ($i = 6; $i <= 18; $i = $i + 4) {
                        $sheet->cells('J' . $i . ':K' . ($i + 3) . '', function ($cells) {
                            $cells->setBorder('none', 'thin', 'thin', 'none');
                        });
                    }
                    for ($i = 6; $i <= 18; $i = $i + 4) {
                        $sheet->cells('L' . $i . ':M' . ($i + 3) . '', function ($cells) {
                            $cells->setBorder('none', 'thin', 'thin', 'none');
                        });
                    }

                    // border each cells for evening
                    for ($i = 23; $i <= 35; $i = $i + 4) {
                        $sheet->cells('B' . $i . ':C' . ($i + 3) . '', function ($cells) {
                            $cells->setBorder('none', 'thin', 'thin', 'none');
                        });
                    }
                    for ($i = 23; $i <= 35; $i = $i + 4) {
                        $sheet->cells('D' . $i . ':E' . ($i + 3) . '', function ($cells) {
                            $cells->setBorder('none', 'thin', 'thin', 'none');
                        });
                    }
                    for ($i = 23; $i <= 35; $i = $i + 4) {
                        $sheet->cells('F' . $i . ':G' . ($i + 3) . '', function ($cells) {
                            $cells->setBorder('none', 'thin', 'thin', 'none');
                        });
                    }
                    for ($i = 23; $i <= 35; $i = $i + 4) {
                        $sheet->cells('H' . $i . ':I' . ($i + 3) . '', function ($cells) {
                            $cells->setBorder('none', 'thin', 'thin', 'none');
                        });
                    }
                    for ($i = 23; $i <= 35; $i = $i + 4) {
                        $sheet->cells('J' . $i . ':K' . ($i + 3) . '', function ($cells) {
                            $cells->setBorder('none', 'thin', 'thin', 'none');
                        });
                    }
                    for ($i = 23; $i <= 35; $i = $i + 4) {
                        $sheet->cells('L' . $i . ':M' . ($i + 3) . '', function ($cells) {
                            $cells->setBorder('none', 'thin', 'thin', 'none');
                        });
                    }


                    // border bottom cells
                    // Set all borders (top, right, bottom, left)
                    $sheet->cells('A38:M38', function ($cells) {
                        $cells->setBorder('none', 'none', 'thin', 'none');
                    });

                    $sheet->cells('M6:M38', function ($cells) {
                        $cells->setBorder('none', 'thin', 'thin', 'none');
                    });
                });
            }
        })->export('xls');
    }
}