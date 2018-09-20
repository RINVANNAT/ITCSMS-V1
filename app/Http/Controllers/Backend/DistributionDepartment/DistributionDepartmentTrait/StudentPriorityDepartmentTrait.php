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
                'distribution_departments.academic_year_id' => $academic_year_id
            ])->join('studentAnnuals', 'studentAnnuals.id','=', 'distribution_departments.student_annual_id')
                ->join('students', 'students.id','=', 'studentAnnuals.student_id')
                ->orderBy('students.name_latin')
                ->get();


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

            $mergChoosPriorityCell = 1;
            for($x='G'; $x != 'IW'; $x++)
            {
                if ($mergChoosPriorityCell == $amountDepartmentChosen) {
                    $sheet->mergeCells('G6:'.$x.'6');
                    break;
                }
                $mergChoosPriorityCell += 1;
            }

            $priority = 1;
            for($x='G'; $x != 'IW'; $x++)
            {
                if ($priority <= $amountDepartmentChosen) {
                    $sheet->cell($x.'7', $priority);
                    $priority +=1;
                } else {
                    break;
                }
            }

            $number = 1;
            $row = 8;
            $studentAnnuals = [];
            foreach ($students as $index => $value) {
                if ($index == 0 || $value->student_annual_id != $students[$index-1]->student_annual_id) {
                    $studentAnnuals[$value->student_annual_id] = [];
                    $studentAnnuals[$value->student_annual_id][$value->priority] = [];
                    $studentAnnuals[$value->student_annual_id][$value->priority]['department_id'] = $value->department_id;
                    $studentAnnuals[$value->student_annual_id][$value->priority]['department_option_id'] = $value->department_option_id;
                    $sheet->cell('A'.$row, $number);
                    $sheet->cell('B'.$row, $value->studentAnnual->student->id_card);
                    $sheet->cell('C'.$row, strtoupper($value->studentAnnual->student->name_latin));
                    $sheet->cell('D'.$row, $value->student->gender->code);
                    $sheet->cell('E'.$row, $value->score_1);
                    $sheet->cell('F'.$row, $value->score_2);
                    $number+=1;
                    $row+=1;
                } else if ($value->student_annual_id == $students[$index-1]->student_annual_id) {
                    $studentAnnuals[$value->student_annual_id][$value->priority]['department_id'] = $value->department_id;
                    $studentAnnuals[$value->student_annual_id][$value->priority]['department_option_id'] = $value->department_option_id;
                }
            }


        });

    }
}