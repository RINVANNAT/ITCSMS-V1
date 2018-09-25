<?php

namespace App\Http\Controllers\Backend\DistributionDepartment;

use App\Http\Controllers\Backend\DistributionDepartment\DistributionDepartmentTrait\StudentPriorityDepartmentTrait;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\DistributionDepartment;
use App\Models\DistributionDepartmentResult;
use App\Models\StudentAnnual;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;

class DistributionDepartmentController extends Controller
{
    use StudentPriorityDepartmentTrait;

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
            $studentAnnuals = DistributionDepartment::where([
                'academic_year_id' => $request->academic_year_id
            ])
                ->select('student_annual_id')
                ->distinct('student_annual_id')
                ->pluck('student_annual_id');
            return message_success(count($studentAnnuals));
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function generate(Request $request)
    {
        try {
            $distributionDepartments = DistributionDepartment::where('academic_year_id', $request->academic_year_id)
                ->where(function ($q) {
                    $q->whereNull('distribution_departments.score_1')
                        ->orWhereNull('distribution_departments.score_2');
                })->get();

            if (count($distributionDepartments) > 0) {
                return redirect()->route('distribution-department.get-fill-score-page')->withFlashDanger('Please fill out missing student score first');
            }

            // calculate final score = (score_1 * 2) + (score_2 * 3)
            $distributionDepartments = DistributionDepartment::where('academic_year_id', $request->academic_year_id)
                // ->whereNull('score')
                ->select('student_annual_id', 'score_1', 'score_2')
                ->distinct('student_annual_id')
                ->get();

            if (count($distributionDepartments) > 0) {
                foreach ($distributionDepartments as $distributionDepartment) {
                    $items = DistributionDepartment::where('student_annual_id', $distributionDepartment->student_annual_id)->get();
                    if (count($items) > 0) {
                        $score = number_format((float)((((float)$items[0]->socre_1)) + (((float)$items[0]->score_2) * 2)) / 3, 2);
                        foreach ($items as $item) {
                            $item->score = number_format((float)$score, 2);
                            $item->update();
                        }
                    }
                }
            }

            // find all distribution department results by academic year id.
            $distributionDepartmentResults = DistributionDepartmentResult::where([
                'academic_year_id' => $request->academic_year_id
            ])->get();

            // delete all distribution department
            foreach ($distributionDepartmentResults as $departmentResult) {
                $departmentResult->delete();
            }

            // find student_annual_id in StudentAnnual model with
            $studentAnnualIds = DistributionDepartment::where([
                'academic_year_id' => $request->academic_year_id
            ])
                ->select('student_annual_id')
                ->distinct('student_annual_id')
                ->pluck('student_annual_id');

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
                        // take an student annual
                        $data = DistributionDepartment::where('student_annual_id', $annualDistributionDepartment->student_annual_id)
                            ->select('id', 'student_annual_id', 'score', 'department_id', 'priority', 'department_option_id')
                            ->orderBy('priority', 'asc')
                            ->get()->toArray();

                        $departmentIdSelected = null;
                        $departmentOptionIdSelected = null;
                        $prioritySelected = null;
                        $isBreak = false;
                        $student_annual_id = null;

                        if (count($data) > 0) {
                            for ($i = 0; $i < count($data); $i++) {
                                $prevStudentScore = 0;
                                if ($i > 0) {
                                    $prevStudentScore = (float)$data[$i - 1]['score'];
                                }
                                $score = $data[$i]['score'];
                                $student_annual_id = $data[$i]['student_annual_id'];
                                foreach ($departments as &$department) {
                                    if ($department['total'] > 0) {
                                        // check each student
                                        // there are two ways to set student into department
                                        // in case department is not enough or current and previous student has the same score
                                        if (!is_null($department['option_id'])) {
                                            $departmentOptionIdSelected = $department['option_id'];
                                            if (($data[$i]['department_id'] == $department['department_id']) && ($data[$i]['department_option_id'] == $department['option_id'])) {
                                                if (($department['total'] > 0) || ($data[$i]['score'] == $prevStudentScore)) {
                                                    $department['total']--;
                                                    $departmentIdSelected = $department['department_id'];
                                                    $prioritySelected = $data[$i]['priority'];
                                                    $departmentOptionIdSelected = $department['option_id'];
                                                    $isBreak = true;
                                                    break;
                                                }
                                            }
                                        }
                                        if (($data[$i]['department_id'] == $department['department_id']) && is_null($department['option_id'])) {
                                            if ($department['total'] > 0 || ($data[$i]['score'] == $prevStudentScore)) {
                                                $department['total']--;
                                                $departmentIdSelected = $department['department_id'];
                                                $prioritySelected = $data[$i]['priority'];
                                                $departmentOptionIdSelected = $department['option_id'];
                                                $isBreak = true;
                                                break;
                                            }
                                        }
                                    }
                                }
                                // put student into department
                                if ($isBreak) {
                                    DistributionDepartmentResult::create([
                                        'academic_year_id' => $request->academic_year_id,
                                        'student_annual_id' => $student_annual_id,
                                        'department_id' => $departmentIdSelected,
                                        'department_option_id' => $departmentOptionIdSelected,
                                        'total_score' => $score,
                                        'priority' => $prioritySelected
                                    ]);
                                    break;
                                }
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
            $academic_year_id = null;
            if (isset($request->academic_year_id)) {
                $academic_year_id = $request->academic_year_id;
            } else {
                $tmp = AcademicYear::latest()->first();
                $academic_year_id = $tmp->id;
            }
            $result = DistributionDepartmentResult::with('department', 'departmentOption', 'studentAnnual')
                ->where('academic_year_id', $academic_year_id)
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
            $studentAnnualIds = DistributionDepartmentResult::where([
                'academic_year_id' => $academic_year_id
            ])->pluck('student_annual_id');

            if (count($studentAnnualIds) > 0) {
                return Excel::create('Distribution Department ' . $academic_year_id, function ($excel) use ($academic_year_id, $studentAnnualIds) {
                    $excel->setTitle('Distribution Department ' . $academic_year_id);

                    $departments = Department::with('department_options')
                        ->where('is_specialist', true)
                        ->orderBy('name_en', 'asc')
                        ->get();

                    foreach ($departments as $department) {
                        if (count($department->department_options) > 0) {
                            foreach ($department->department_options as $option) {
                                $result = DistributionDepartmentResult::where([
                                    'distribution_department_results.department_id' => $department->id,
                                    'distribution_department_results.department_option_id' => $option->id,
                                    'distribution_department_results.academic_year_id' => $academic_year_id
                                ])
                                    ->join('studentAnnuals', 'studentAnnuals.id', '=', 'distribution_department_results.student_annual_id')
                                    ->join('departments', 'departments.id', '=', 'distribution_department_results.department_id')
                                    ->join('departmentOptions', 'departmentOptions.id', '=', 'distribution_department_results.department_option_id')
                                    ->join('students', 'students.id', '=', 'studentAnnuals.student_id')
                                    ->join('genders', 'genders.id', '=', 'students.gender_id')
                                    ->select('distribution_department_results.*', 'departments.*', 'studentAnnuals.*', 'students.*', 'genders.code as sex')
                                    ->orderBy('students.name_latin', 'asc')
                                    ->get();
                                if (count($result) > 0) {
                                    $this->getSheet($excel, $department, $option, $result);
                                }
                            }
                        }

                        $result = DistributionDepartmentResult::where([
                            'distribution_department_results.department_id' => $department->id,
                            'distribution_department_results.academic_year_id' => $academic_year_id,
                            'distribution_department_results.department_option_id' => null
                        ])
                            ->join('studentAnnuals', 'studentAnnuals.id', '=', 'distribution_department_results.student_annual_id')
                            ->join('departments', 'departments.id', '=', 'distribution_department_results.department_id')
                            ->join('students', 'students.id', '=', 'studentAnnuals.student_id')
                            ->join('genders', 'genders.id', '=', 'students.gender_id')
                            ->select('distribution_department_results.*', 'departments.*', 'studentAnnuals.*', 'students.*', 'genders.code as sex')
                            ->orderBy('students.name_latin', 'asc')
                            ->get();
                        if (count($result) > 0) {
                            $this->getSheet($excel, $department, null, $result);
                        }
                    }
                })->export('xlsx');
            }
            return redirect()->back()->withFlashInfo('No data are found! Verify your academic already has student!');
        } catch (\Exception $exception) {
            return redirect()->back()->withFlashDanger($exception->getMessage());
        }
    }

    private function getSheet($excel, $department, $departmentOption = null, $data)
    {
        $academicYear = AcademicYear::find($data[0]->academic_year_id);
        $excel->sheet($department->code . ($departmentOption != null ? $departmentOption->code : ''), function ($sheet) use ($data, $department, $departmentOption, $academicYear) {
            // header
            $sheet->mergeCells('A3:C3');
            $sheet->cell('A3', function ($cell) use ($department, $academicYear) {
                $cell->setValue('ឆ្នាំសិក្សា ' . $academicYear->name_kh);
                $cell->setFont(array(
                    'bold' => true,
                    'size' => 13
                ));
            });

            $sheet->mergeCells('D3:E3');
            $sheet->cell('D3', function ($cell) use ($department) {
                $cell->setValue('ដេប៉ាតឺម៉ង់៖ ' . $department->code);
                $cell->setFont(array(
                    'bold' => true,
                    'size' => 13
                ));
            });

            $sheet->mergeCells('A1:E1');
            $sheet->cell('A1', function ($cell) use ($department) {
                $cell->setAlignment('center');
                $cell->setValue('បំនែងចែកដេប៉ាតឺម៉ង់ថ្នាក់ឆ្នាំទី៣');
                $cell->setFont(array(
                    'bold' => true,
                    'size' => 14
                ));
            });

            $sheet->cells('A1:E1', function ($cell) use ($department) {
                $cell->setAlignment('center');
                $cell->setFont(array(
                    'bold' => true
                ));
            });

            $sheet->cell('A5', 'ល.រ');
            $sheet->cell('B5', 'អត្តលេខ');
            $sheet->cell('C5', 'គ្តោនាម');
            $sheet->cell('D5', 'ភេទ');
            $sheet->cell('E5', 'ដេប៉ាតឺម៉ង');

            $row = 6;
            $start = $row - 1;
            $nb = 1;
            foreach ($data as $item) {
                $sheet->cell('A' . $row, $nb);
                $sheet->cell('B' . $row, $item->studentAnnual->student->id_card);
                $sheet->cell('C' . $row, $item->studentAnnual->student->name_latin);
                $sheet->cell('D' . $row, $item->sex);
                $sheet->cell('E' . $row, $department->code . ($departmentOption != null ? $departmentOption->code : ''));
                $row++;
                $nb++;
            }
            $end = $row - 1;
            $sheet->setBorder('A' . $start . ':E' . $end, 'thin');
            $sheet->cells('A6:A' . $end, function ($cells) {
                $cells->setAlignment('center');
            });
            $sheet->cells('D6:E' . $end, function ($cells) {
                $cells->setAlignment('center');
            });
            $sheet->cells('A5:E5', function ($cells) {
                $cells->setBackground('#dddddd');
                $cells->setAlignment('center');
            });
        });
    }

    public function getFillScorePage()
    {
        return view('backend.distributionDepartment.fill-score');
    }

    public function getStudentWhoHaveNoScore(Request $request)
    {
        $this->validate($request, [
            'academic_year_id' => 'required'
        ]);
        try {
            $students = DistributionDepartment::where('distribution_departments.academic_year_id', $request->academic_year_id)
                ->join('studentAnnuals', 'studentAnnuals.id', '=', 'distribution_departments.student_annual_id')
                ->join('students', 'students.id', '=', 'studentAnnuals.student_id')
                ->where(function ($q) {
                    $q->whereNull('distribution_departments.score_1')
                        ->orWhereNull('distribution_departments.score_2');
                })
                ->select([
                    'students.id as student_id',
                    'students.id_card as student_id_card',
                    'students.name_kh as student_name_kh',
                    'students.name_latin as student_name_latin',
                    'distribution_departments.student_annual_id as student_annual_id',
                    'distribution_departments.score_1 as score_1',
                    'distribution_departments.score_2 as score_2',
                ])
                ->distinct('distribution_departments.student_annual_id')
                ->get();
            return message_success($students);
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function saveStudentWhoHaveNoScore(Request $request)
    {
        $this->validate($request, [
            'students' => 'required'
        ]);
        try {
            $students = $request->students;
            foreach ($students as $student) {
                $distributionDepartments = DistributionDepartment::where('student_annual_id', $student['student_annual_id'])->get();
                foreach ($distributionDepartments as $distributionDepartment) {
                    if ($distributionDepartment instanceof DistributionDepartment) {
                        $distributionDepartment->update($student);
                    }
                }
            }
            return message_success($request->students);
        } catch (\Exception $e) {
            return message_error($e->getMessage());
        }
    }

    public function getDepartmentChosen(Request $request)
    {
        $this->validate($request, [
            'academic_year_id' => 'required'
        ]);
        try {
            $studentAnnualId = DistributionDepartment::where('academic_year_id', $request->academic_year_id)
                ->first();
            if ($studentAnnualId instanceof DistributionDepartment) {
                $studentAnnualId = $studentAnnualId->student_annual_id;
                $distributionDepartment = DistributionDepartment::where('student_annual_id', $studentAnnualId)
                    ->get();
                $depts = [];

                foreach ($distributionDepartment as $item) {
                    $deptCode = Department::find($item->department_id);
                    $label = (string)$deptCode->code;
                    $id = (string)$deptCode->id;
                    if (!is_null($item->department_option_id)) {
                        $deptOption = DepartmentOption::find($item->department_option_id);
                        $label .= (string)$deptOption->code;
                        $id .= '_' . (string)$deptOption->id;
                    }
                    $option['label'] = $label;
                    $option['id'] = $id;

                    if (count($depts) == 0) {
                        array_push($depts, $option);
                    } else {
                        $found = true;
                        foreach ($depts as $dept) {
                            if ($option['label'] == $dept['label']) {
                                $found = false;
                                break;
                            }
                        }
                        if ($found) {
                            array_push($depts, $option);
                        }
                    }
                }

                return message_success($depts);
            }
            return message_success([]);
        } catch (\Exception $e) {
            return message_error($e->getMessage());
        }
    }

    public function exportAll(Request $request)
    {
        try {
            $academicYear = AcademicYear::find($request->academic_year_id);
            $academic_year_id = $request->academic_year_id;

            $result = DistributionDepartmentResult::where([
                'distribution_department_results.academic_year_id' => $academic_year_id,
            ])
                ->join('studentAnnuals', 'studentAnnuals.id', '=', 'distribution_department_results.student_annual_id')
                ->join('departments', 'departments.id', '=', 'distribution_department_results.department_id')
                ->join('students', 'students.id', '=', 'studentAnnuals.student_id')
                ->join('genders', 'genders.id', '=', 'students.gender_id')
                ->select('distribution_department_results.*', 'students.*', 'departments.code as dept_code', 'genders.code as sex')
                ->orderBy('students.name_latin', 'asc')
                ->get();
            if (count($result) > 0) {
                return Excel::create('Distribution Department Result ' . $academicYear->name_latin, function ($excel) use ($academic_year_id, $academicYear, $result) {
                    $excel->setTitle('Distribution Department Result ' . $academic_year_id);
                    $this->getSheetAll($excel, $result, $academicYear);
                })->export('xlsx');

            } else {
                return redirect()->back()->withFlashInfo('No data are found! Verify your academic already has student!');
            }
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    private function getSheetAll($excel, $data, AcademicYear $academicYear)
    {
        $excel->sheet('Result', function ($sheet) use ($data, $academicYear) {
            // header
            $sheet->mergeCells('A1:E1');
            $sheet->cell('A1', function ($cell) {
                $cell->setAlignment('center');
                $cell->setValue('បំនែងចែកដេប៉ាតឺម៉ង់ថ្នាក់ឆ្នាំទី៣');
                $cell->setFont(array(
                    'bold' => true,
                    'size' => 14
                ));
            });

            $sheet->mergeCells('A3:E3');
            $sheet->cell('A3', function ($cell) use ($academicYear) {
                $cell->setValue('ឆ្នាំសិក្សា ' . $academicYear->name_kh);
                $cell->setAlignment('center');
                $cell->setFont(array(
                    'bold' => true,
                    'size' => 13
                ));
            });

            $sheet->cell('A5', 'ល.រ');
            $sheet->cell('B5', 'អត្តលេខ');
            $sheet->cell('C5', 'គ្តោនាម');
            $sheet->cell('D5', 'ភេទ');
            $sheet->cell('E5', 'ដេប៉ាតឺម៉ង');

            $row = 6;
            $start = $row - 1;
            $nb = 1;
            foreach ($data as $item) {
                $deptOption = '';
                if (isset($item->department_option_id)) {
                    $deptOption = DepartmentOption::find($item->department_option_id);
                    $deptOption = $deptOption->code;
                }
                $sheet->cell('A' . $row, $nb);
                $sheet->cell('B' . $row, $item->studentAnnual->student->id_card);
                $sheet->cell('C' . $row, $item->studentAnnual->student->name_latin);
                $sheet->cell('D' . $row, $item->sex);
                $sheet->cell('E' . $row, $item->dept_code . $deptOption);
                $row++;
                $nb++;
            }
            $end = $row - 1;
            $sheet->setBorder('A' . $start . ':E' . $end, 'thin');
            $sheet->cells('A6:A' . $end, function ($cells) {
                $cells->setAlignment('center');
            });
            $sheet->cells('D6:E' . $end, function ($cells) {
                $cells->setAlignment('center');
            });
            $sheet->cells('A5:E5', function ($cells) {
                $cells->setBackground('#dddddd');
                $cells->setAlignment('center');
            });
        });
    }

    public function printEachDepartment(Request $request)
    {
        $academicYear = AcademicYear::find($request->academic_year_id);

        $studentAnnualId = DistributionDepartment::where('academic_year_id', $request->academic_year_id)
            ->first();
        $data = [];
        if ($studentAnnualId instanceof DistributionDepartment) {
            $studentAnnualId = $studentAnnualId->student_annual_id;
            $distributionDepartment = DistributionDepartment::where('student_annual_id', $studentAnnualId)
                ->get();
            foreach ($distributionDepartment as $item) {

                $result = DistributionDepartmentResult::where([
                    'distribution_department_results.academic_year_id' => $academicYear->id,
                    'distribution_department_results.department_id' => $item->department_id
                ]);

                if (!is_null($item->department_option_id)) {
                    $result->where('distribution_department_results.department_option_id', $item->department_option_id);
                }

                $result->join('studentAnnuals', 'studentAnnuals.id', '=', 'distribution_department_results.student_annual_id')
                    ->join('departments', 'departments.id', '=', 'distribution_department_results.department_id')
                    ->join('students', 'students.id', '=', 'studentAnnuals.student_id')
                    ->join('genders', 'genders.id', '=', 'students.gender_id')
                    ->select('distribution_department_results.*', 'students.*', 'departments.code as dept_code', 'genders.code as sex')
                    ->orderBy('students.name_latin', 'asc');

                array_push($data, $result->get());
            }
        }

        if (count($data) > 0) {
            return SnappyPdf::loadView('backend.distributionDepartment.print-each-department', compact('academicYear', 'data'))
                ->setOption('encoding', 'utf-8')
                ->stream();
        } else {
            return redirect()->back()->withFlashInfo('No data are found! Verify your academic already has student!');
        }
    }

    public function printAll(Request $request)
    {
        $academicYear = AcademicYear::find($request->academic_year_id);
        $academic_year_id = $request->academic_year_id;

        $result = DistributionDepartmentResult::where([
            'distribution_department_results.academic_year_id' => $academic_year_id,
        ])
            ->join('studentAnnuals', 'studentAnnuals.id', '=', 'distribution_department_results.student_annual_id')
            ->join('departments', 'departments.id', '=', 'distribution_department_results.department_id')
            ->join('students', 'students.id', '=', 'studentAnnuals.student_id')
            ->join('genders', 'genders.id', '=', 'students.gender_id')
            ->select('distribution_department_results.*', 'students.*', 'departments.code as dept_code', 'genders.code as sex')
            ->orderBy('students.name_latin', 'asc')
            ->get();

        return SnappyPdf::loadView('backend.distributionDepartment.print-all', compact('academicYear', 'result'))
            ->setOption('encoding', 'utf-8')
            ->stream();
    }

    public function getImportPage($degree, $academic_year_id)
    {
        return view('backend.distributionDepartment.import', compact('degree', 'academic_year_id'));
    }

    public function importData(Request $request)
    {
        $this->validate($request, [
            'file' => 'required'
        ]);
        try {
            DistributionDepartment::where('academic_year_id', (int) $request->academic_year_id)->delete();
            DistributionDepartmentResult::where('academic_year_id', (int) $request->academic_year_id)->delete();

            $file = $request->file('file');
            Excel::load($file, function ($reader) use ($request) {
                $reader->setHeaderRow(6);
                $rows = $reader->get();
                foreach ($rows as $row) {
                    $studentAnnual = StudentAnnual::join('students', 'students.id', '=', 'studentAnnuals.student_id')
                        ->where('students.id_card', $row['id_card'])
                        ->select('studentAnnuals.*')
                        ->orderBy('studentAnnuals.id', 'desc')
                        ->first();

                    $chosen = [];
                    $priorities = array_except($row->toArray(), ['no', 'id_card', 'name', 'sex', 'score_year_i', 'score_year_ii']);
                    foreach ($priorities as $key => $value) {
                        $item['student_annual_id'] = $studentAnnual->id;
                        $item['academic_year_id'] = (int) $request->academic_year_id;
                        $item['score_1'] = $row['score_year_i'];
                        $item['score_2'] = $row['score_year_ii'];
                        $item['priority'] = $key;

                        $tmp = explode('_', $value);
                        if (count($tmp) > 1) {
                            $item['department_id'] = $tmp[0];
                            $item['department_option_id'] = $tmp[1];
                        } else {
                            $item['department_id'] = $value;
                            $item['department_option_id'] = null;
                        }
                        array_push($chosen, $item);
                    }

                    foreach ($chosen as $item) {
                        DistributionDepartment::create($item);
                    }
                }
            });
            return redirect()->route('distribution-department.index')->withFlashInfo('Imported !');
        } catch (\Exception $exception) {
            return redirect()->back()->withFlashInfo($exception->getMessage());
        }
    }
}
