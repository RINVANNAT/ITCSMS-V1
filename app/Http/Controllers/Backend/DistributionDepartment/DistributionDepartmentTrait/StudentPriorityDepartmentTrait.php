<?php

namespace App\Http\Controllers\Backend\DistributionDepartment\DistributionDepartmentTrait;

use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\DistributionDepartment;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

trait StudentPriorityDepartmentTrait
{
    public function getStudentPriorityDepartment($academic_year_id)
    {
        try {
            $amountDepartmentChosen = DistributionDepartment::where([
                'academic_year_id' => $academic_year_id
            ])
                ->select('student_annual_id', DB::raw('COUNT(priority) as priority'))
                ->groupBy('student_annual_id')
                ->first();

            if ($amountDepartmentChosen instanceof DistributionDepartment) {
                $amountDepartmentChosen = $amountDepartmentChosen->priority;
            }

            $studentDistributeDepartments = DistributionDepartment::where([
                'distribution_departments.academic_year_id' => $academic_year_id
            ])
                ->join('studentAnnuals', 'studentAnnuals.id', '=', 'distribution_departments.student_annual_id')
                ->join('students', 'students.id', '=', 'studentAnnuals.student_id')
                ->select([
                    'distribution_departments.student_annual_id',
                    'distribution_departments.score_1',
                    'distribution_departments.score_2',
                    'students.name_latin'
                ])
                ->distinct('distribution_departments.student_annual_id')
                ->orderBy('students.name_latin', 'asc')
                ->get();

            if (count($studentDistributeDepartments) > 0) {
                return Excel::create('Student Priority Department ' . $academic_year_id, function ($excel) use ($academic_year_id, $studentDistributeDepartments, $amountDepartmentChosen) {
                    $excel->setTitle('Student Priority Department ' . $academic_year_id);

                    $this->getStudentPriorityDepartmentData($excel, $amountDepartmentChosen, $studentDistributeDepartments);
                })->export('xlsx');
            }
            return redirect()->back()->withFlashInfo('No data are found! Verify your academic already has student!');
        } catch (\Exception $exception) {
            return redirect()->back()->withFlashDanger($exception->getMessage());
        }
    }

    public function getStudentPriorityDepartmentData($excel, $amountDepartmentChosen, $students)
    {
        $excel->sheet('Student Priority Department ', function ($sheet) use ($amountDepartmentChosen, $students) {
            // header
            $sheet->mergeCells('A1:E1');
            $sheet->cell('A1', function ($cell) {
                $cell->setValue('Institute of Technology of Cambodia');
            });

            $sheet->setAutoSize(true);

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
            for ($x = 'G'; $x != 'IW'; $x++) {
                if ($mergChoosPriorityCell == $amountDepartmentChosen) {
                    $sheet->mergeCells('G6:' . $x . '6');
                    break;
                }
                $mergChoosPriorityCell += 1;
            }

            $priority = 1;
            for ($x = 'G'; $x != 'IW'; $x++) {
                if ($priority <= $amountDepartmentChosen) {
                    $sheet->cell($x . '7', $priority);
                    $priority += 1;
                } else {
                    break;
                }
            }

            $number = 1;
            $row = 8;

            foreach ($students as $index => $value) {
                $sheet->cell('A' . $row, $number);
                $sheet->cell('B' . $row, $value->studentAnnual->student->id_card);
                $sheet->cell('C' . $row, strtoupper($value->studentAnnual->student->name_latin));
                $sheet->cell('D' . $row, $value->student->gender->code);
                $sheet->cell('E' . $row, $value->score_1);
                $sheet->cell('F' . $row, $value->score_2);
                $distributionDepartments = DistributionDepartment::where('student_annual_id', $value->student_annual_id)
                    ->orderBy('priority', 'asc')
                    ->get()->toArray();
                $alphabet = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
                $selectAlphabet = 6;
                for ($i = 0; $i < $amountDepartmentChosen; $i++) {
                    $department = Department::find($distributionDepartments[$i]['department_id'])->code;
                    $department_option = '';
                    if (!is_null($distributionDepartments[$i]['department_option_id'])) {
                        $tmp = DepartmentOption::find($distributionDepartments[$i]['department_option_id']);
                        $department_option = $tmp->code;
                    }
                    $sheet->cell($alphabet[$selectAlphabet] . $row, $department . $department_option);
                    $selectAlphabet++;
                }
                $number += 1;
                $row += 1;
            }

            $sheet->setBorder('A6:' . $alphabet[$selectAlphabet - 1] . ($row - 1), 'thin');
            $sheet->cells('A6:' . $alphabet[$selectAlphabet - 1] . '7', function ($cells) {
                $cells->setValignment('center');
                $cells->setAlignment('center');
                $cells->setFont(array(
                    'family' => 'Calibri',
                    'size' => '12',
                    'bold' => true
                ));
            });
        });

    }
}