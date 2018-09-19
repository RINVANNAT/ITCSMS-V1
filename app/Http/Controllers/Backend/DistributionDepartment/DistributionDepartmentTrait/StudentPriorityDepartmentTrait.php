<?php

namespace App\Http\Controllers\Backend\DistributionDepartment\DistributionDepartmentTrait;

use App\Models\DistributionDepartment;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

trait StudentPriorityDepartmentTrait
{
    public function getStudentPriorityDepartment($academic_year_id){
        try {
            $amountDepartmentChosen = DistributionDepartment::where([
                'academic_year_id' => $academic_year_id
            ])
                ->select('student_annual_id', DB::raw('COUNT(priority) as priority'))
                ->groupBy('student_annual_id')
                ->first()->priority;

            $studentDistributeDepartments = DistributionDepartment::where([
                'academic_year_id' => $academic_year_id
            ])->get();


            if (count($studentDistributeDepartments) > 0) {
                return Excel::create('Student Priority Department ' . $academic_year_id, function ($excel) use ($academic_year_id, $studentDistributeDepartments, $amountDepartmentChosen) {
                    $excel->setTitle('Student Priority Department ' . $academic_year_id);

                    $this->getStudentPriorityDepartmentData($excel, $amountDepartmentChosen, $studentDistributeDepartments);
                })->export('xlsx');
            }
            return redirect()->back()->withFlashInfo('No data are found!');
        } catch (\Exception $exception) {
            return redirect()->back()->withFlashDanger($exception->getMessage());
        }
    }

    public function getStudentPriorityDepartmentData($excel, $amountDepartmentChosen, $students) {

        $excel->sheet('Student Priority Department ', function ($sheet) use ($amountDepartmentChosen, $students) {
            // header
            $sheet->mergeCells('A1:E1');
            $sheet->cell('A1', function ($cell) {
                $cell->setValue('Institute of Technology of Cambodia');
            });

            $sheet->mergeCells('A6:A7');
            $sheet->mergeCells('B6:B7');
            $sheet->mergeCells('C6:C7');
            $sheet->mergeCells('D6:D7');
            $sheet->mergeCells('E6:E7');
            $sheet->mergeCells('F6:F7');

            $sheet->cell('A6', 'No');
            $sheet->cell('B6', 'ID Card');
            $sheet->cell('C6', 'Name Latin');
            $sheet->cell('D6', 'Sex');
            $sheet->cell('E6', 'Score Year 1');
            $sheet->cell('F6', 'Score Year 2');
            $sheet->cell('G6', 'Chosen Priority');


        });

    }
}