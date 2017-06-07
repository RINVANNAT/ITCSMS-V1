<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Models\Group;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\Week;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Files\ExcelFile;

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
        Excel::create('timetable-', function ($excel){
            $excel->setTitle('week1');
            foreach (request('weeks') as $item){
                $excel->sheet(Week::find($item)->name_en, function ($sheet){
                    $sheet->setOrientation('landscape');
                    $sheet->setWidth(array(
                        'A' => 20,
                        'B' => 20,
                        'C' => 20,
                        'D' => 20,
                        'E' => 20,
                        'F' => 20,
                        'G' => 20
                    ));




                    $sheet->mergeCells('C1:E1');
                    $sheet->mergeCells('C2:E2');
                    $sheet->cells('C1:E1', function ($cells){
                        $cells->setAlignment('center');
                        $cells->setValignment('middle');
                    });
                    $sheet->cells('C2:E2', function ($cells){
                        $cells->setAlignment('center');
                        $cells->setValignment('middle');
                    });


                    $sheet->row(1, array('','','EMPLOI DU TEMPS', '', '', '', 'Semester I'));
                    $sheet->row(2, array('', '', 'Groupe: I1 (1) -TC', '', '', '', 'Week 1'));
                    $sheet->row(5, array('Horaire', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'));

                    $sheet->row(6, array('07h00 - 08h00'));
                    $sheet->mergeCells('A6:A9', function ($cells){
                        $cells->setAlignment('center');
                        $cells->setValignment('middle');
                    });



                    $sheet->row(10, array('08h00 - 09h00'));
                    $sheet->mergeCells('A10:A13', function ($cells){
                        $cells->setAlignment('center');
                        $cells->setValignment('middle');
                    });

                    $sheet->row(14, array('08h00 - 09h00'));
                    $sheet->mergeCells('A14:A17', function ($cells){
                        $cells->setAlignment('center');
                        $cells->setValignment('middle');
                    });

                    $sheet->row(14, array('08h00 - 09h00'));
                    $sheet->mergeCells('A14:A17', function ($cells){
                        $cells->setAlignment('center');
                        $cells->setValignment('middle');
                    });

                    $sheet->row(18, array('08h00 - 09h00'));
                    $sheet->mergeCells('A18:A21', function ($cells){
                        $cells->setAlignment('center');
                        $cells->setValignment('middle');
                    });


                    $sheet->cells('A5:A16', function ($cells){
                        $cells->setAlignment('center');
                    });

                    $sheet->cells('A5:G5', function ($cells){
                        $cells->setAlignment('center');
                        $cells->setValignment('middle');
                    });
                });
            }
        })->export('xls');
    }
}