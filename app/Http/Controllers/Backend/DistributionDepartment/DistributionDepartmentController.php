<?php

namespace App\Http\Controllers\Backend\DistributionDepartment;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\DistributionDepartment;
use App\Models\DistributionDepartmentResult;
use App\Models\StudentAnnual;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;

class DistributionDepartmentController extends Controller
{
    public function index()
    {
        return view('backend.distributionDepartment.index');
    }

    public function getGeneratePage()
    {
        return view('backend.distributionDepartment.generate');
    }

    public function getDepartment()
    {
        $departments = Department::with('department_options')
            ->where('is_specialist', true)
            ->orderBy('name_en', 'asc')
            ->get();
        return message_success($departments);
    }

    public function getAcademicYear()
    {
        $academicYears = AcademicYear::latest()->get();
        return message_success($academicYears);
    }

    public function getTotalStudentAnnuals(Request $request)
    {
        $this->validate($request, [
            'academic_year_id' => 'required'
        ]);
        try {
            $studentAnnuals = StudentAnnual::where([
                'academic_year_id' => $request->academic_year_id,
                'degree_id' => 1,
                'grade_id' => 2
            ])->count();
            return message_success($studentAnnuals);
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function generate(Request $request)
    {
        try {
            // find student_annual_id in StudentAnnual model with
            $studentAnnualIds = StudentAnnual::where([
                'academic_year_id' => $request->academic_year_id,
                'degree_id' => 1,
                'grade_id' => 2
            ])->pluck('id');

            if (count($studentAnnualIds) > 0) {
                // find all student annual from DistributionDepartment
                $studentAnnualDistributionDepartments = DistributionDepartment::whereIn('student_annual_id', $studentAnnualIds)
                    ->select('student_annual_id', 'score')
                    ->distinct('student_annual_id')
                    ->orderBy('score', 'desc')
                    ->get();

                // prepare data with department and department option
                $tmpDepts = $request->except(['_token', 'academic_year_id']);
                $departments = [];
                $totalStudentAnnualFormRequest = 0;
                foreach ($tmpDepts as $key => $dept) {
                    if (0 == (int)$dept) {
                        continue;
                    }
                    $tmp = explode('_', $key);
                    if (count($tmp) > 1) {
                        array_push($departments, ['department_id' => (int)$tmp[0], 'option_id' => (int)$tmp[1], 'total' => (int)$dept]);
                    } else {
                        array_push($departments, ['department_id' => (int)$key, 'option_id' => null, 'total' => (int)$dept]);
                    }
                    $totalStudentAnnualFormRequest += (int)$dept;
                }

                if ($totalStudentAnnualFormRequest == count($studentAnnualDistributionDepartments)) {
                    foreach ($studentAnnualDistributionDepartments as $annualDistributionDepartment) {
                        $data = DistributionDepartment::where('student_annual_id', $annualDistributionDepartment->student_annual_id)
                            ->select('id', 'student_annual_id', 'score', 'department_id', 'priority', 'department_option_id')
                            ->orderBy('priority', 'asc')
                            ->get()->toArray();

                        $departmentIdSelected = null;
                        $departmentOptionIdSelected = null;
                        $prioritySelected = null;
                        $isBreak = false;
                        $student_annual_id = null;

                        foreach ($data as $item) {
                            $score = $item['score'];
                            $student_annual_id = $item['student_annual_id'];
                            foreach ($departments as &$department) {
                                if ($department['total'] > 0) {
                                    if (!is_null($department['option_id'])) {
                                        $departmentOptionIdSelected = $department['option_id'];
                                        if (($item['department_id'] == $department['department_id']) && ($item['department_option_id'] == $department['option_id'])) {
                                            if ($department['total'] > 0) {
                                                $department['total']--;
                                                $departmentIdSelected = $department['department_id'];
                                                $prioritySelected = $item['priority'];
                                                $departmentOptionIdSelected = $department['option_id'];
                                                $isBreak = true;
                                                break;
                                            }
                                        }
                                    }
                                    if (($item['department_id'] == $department['department_id']) && is_null($department['option_id'])) {
                                        if ($department['total'] > 0) {
                                            $department['total']--;
                                            $departmentIdSelected = $department['department_id'];
                                            $prioritySelected = $item['priority'];
                                            $departmentOptionIdSelected = $department['option_id'];
                                            $isBreak = true;
                                            break;
                                        }
                                    }
                                }
                            }
                            if ($isBreak) {
                                $result = DistributionDepartmentResult::where('student_annual_id', $student_annual_id)->first();
                                if ($result instanceof DistributionDepartmentResult) {
                                    $result->update([
                                        'student_annual_id' => $student_annual_id,
                                        'department_id' => $departmentIdSelected,
                                        'department_option_id' => $departmentOptionIdSelected,
                                        'total_score' => $score,
                                        'priority' => $prioritySelected
                                    ]);
                                } else {
                                    DistributionDepartmentResult::create([
                                        'student_annual_id' => $student_annual_id,
                                        'department_id' => $departmentIdSelected,
                                        'department_option_id' => $departmentOptionIdSelected,
                                        'total_score' => $score,
                                        'priority' => $prioritySelected
                                    ]);
                                }
                                break;
                            }
                        }
                    }
                    return redirect()->route('distribution-department.index');
                } else {
                    return redirect()->back()->withFlashDanger('The amount student annuals are not match between ' . $totalStudentAnnualFormRequest . ' and ' . count($studentAnnualDistributionDepartments));
                }
            } else {
                return redirect()->back()->withFlashDanger('There are 0 student annuals found');
            }
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function data(Request $request)
    {
        try {
            $academicYearId = AcademicYear::latest()->first();
            $studentAnnualsIds = StudentAnnual::where([
                'academic_year_id' => $academicYearId->id,
                'degree_id' => 1,
                'grade_id' => 2
            ])->pluck('id');

            $result = DistributionDepartmentResult::with('department', 'departmentOption', 'studentAnnual')
                ->whereIn('student_annual_id', $studentAnnualsIds)
                ->select('distribution_department_results.*')
                ->latest();
            return Datatables::of($result)
                ->editColumn('department_option', function ($result) {
                    if (is_null($result->department_option_id)) {
                        return 'N/A';
                    }
                    return $result->departmentOption->code;
                })
                ->make(true);
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function export($academic_year_id)
    {
        try {
            $studentAnnualIds = StudentAnnual::where([
                'academic_year_id' => $academic_year_id,
                'degree_id' => 1,
                'grade_id' => 2
            ])->pluck('id');

            if (count($studentAnnualIds) > 0) {
                return Excel::create('Distribution Department ' . $academic_year_id, function ($excel) use ($academic_year_id, $studentAnnualIds) {
                    $excel->setTitle('Distribution Department ' . $academic_year_id);

                    $departments = Department::with('department_options')
                        ->where('is_specialist', true)
                        ->orderBy('name_en', 'asc')
                        ->get();

                    foreach ($departments as $department) {
                        $result = null;
                        if (count($department->department_options) > 0) {
                            foreach ($department->department_options as $option) {
                                $result = DistributionDepartmentResult::with('studentAnnual', 'department', 'departmentOption')
                                    ->where([
                                        'department_id' => $department->id,
                                        'department_option_id' => $option->id
                                    ])
                                    ->whereIn('student_annual_id', $studentAnnualIds)
                                    ->get();
                                if (count($result) > 0) {
                                    $this->getSheet($excel, $department, $option, $result);
                                }
                            }
                        } else {
                            $result = DistributionDepartmentResult::with('studentAnnual', 'department', 'departmentOption')
                                ->where([
                                    'department_id' => $department->id
                                ])
                                ->whereIn('student_annual_id', $studentAnnualIds)
                                ->get();
                            if (count($result) > 0) {
                                $this->getSheet($excel, $department, null, $result);
                            }
                        }
                    }
                })->export('xlsx');
            }
            return redirect()->back()->withFlashInfo('No data are found!');
        } catch (\Exception $exception) {
            return redirect()->back()->withFlashDanger($exception->getMessage());
        }
    }

    private function getSheet($excel, $department, $departmentOption = null, $data)
    {
        $excel->sheet($department->code . ($departmentOption != null ? $departmentOption->code : ''), function ($sheet) use ($data, $department, $departmentOption) {
            // header
            $sheet->mergeCells('A1:E1');
            $sheet->cell('A1', function ($cell) {
                $cell->setValue('Institute of Technology of Cambodia');
            });

            $sheet->mergeCells('A2:G2');
            $sheet->cell('A2', function ($cell) use ($department) {
                $cell->setValue('Department of ' . $department->name_en);
            });

            $sheet->mergeCells('B4:C4');
            $sheet->cell('B4', function ($cell) use ($department) {
                $cell->setAlignment('center');
                $cell->setValue('Student Listing');
                $cell->setFont(array(
                    'bold' => true
                ));
            });

            $sheet->cells('A6:E6', function ($cell) use ($department) {
                $cell->setAlignment('center');
                $cell->setFont(array(
                    'bold' => true
                ));
            });

            $sheet->cell('A6', 'ID Card');
            $sheet->cell('B6', 'Name Khmer');
            $sheet->cell('C6', 'Name Latin');
            $sheet->cell('D6', 'Score');
            $sheet->cell('E6', 'Priority');

            $row = 7;
            $start = $row - 1;
            foreach ($data as $item) {
                $sheet->cell('A' . $row, $item->studentAnnual->student->id_card);
                $sheet->cell('B' . $row, $item->studentAnnual->student->name_kh);
                $sheet->cell('C' . $row, strtoupper($item->studentAnnual->student->name_latin));
                $sheet->cell('D' . $row, $item->total_score);
                $sheet->cell('E' . $row, $item->priority);
                $row++;
            }
            $end = $row - 1;
            $sheet->setBorder('A' . $start . ':E' . $end, 'thin');
        });
    }
}
