<?php

namespace App\Http\Controllers\Backend\Internship;

use App\Models\AcademicYear;
use App\Models\Employee;
use App\Models\Student;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

/**
 * Class InternshipController
 * @package App\Http\Controllers\Backend\Internship
 */
class InternshipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.internship.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $academic_years = AcademicYear::latest()->get();
        return view('backend.internship.create')->with([
            'academic_years' => $academic_years
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request)
    {
        dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return void
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return void
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $academic_year_id = Input::get('academic_year_id');
            $page = Input::get('page');
            $resultCount = 25;
            $offset = ($page - 1) * $resultCount;

            $students = Student::join('genders', 'genders.id', '=', 'students.gender_id')
                ->join('studentAnnuals', 'studentAnnuals.student_id', '=', 'students.id')
                ->join('departments', 'departments.id', '=', 'studentAnnuals.department_id')
                ->where('studentAnnuals.academic_year_id', $academic_year_id)
                ->where('students.name_latin', 'ilike', "%" . Input::get("term") . "%")
                ->orWhere('students.name_kh', 'ilike', "%" . Input::get("term") . "%")
                ->select([
                    'studentAnnuals.id as id',
                    'students.id_card',
                    'students.name_kh as text',
                    'students.name_latin',
                    'genders.code as gender',
                    'departments.code as department'
                ]);

            $client = $students
                ->orderBy('name_latin')
                ->skip($offset)
                ->take($resultCount)
                ->get();

            $count = Count($students->get());
            $endCount = $offset + $resultCount;
            $morePages = $count > $endCount;

            $results = array(
                'results' => $client,
                'pagination' => array(
                    "more" => $morePages
                )
            );
            return response()->json($results);
        }
    }
}