<?php namespace App\Http\Controllers\Backend\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DegreeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.configuration.degree.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function data(Request $request)
    {
        //$student = Student::join('studentAnnuals', 'studentAnnuals.student_id', '=', 'students.id')
        //	->select(['students.id_card','students.name_kh','students.name_latin','studentAnnuals.grade_id']);

        //$studentAnnuals = StudentAnnual::with(['student','grade'])->select(['students.id_card','students.name_kh','students.name_latin','grades.name_kh']);

        $degrees = DB::table('degrees')
            ->select(['id','code','name_kh','name_en','name_fr']);

        $datatables =  app('datatables')->of($degrees);


        return $datatables
            ->editColumn('code', '{!! str_limit($code, 60) !!}')
            ->editColumn('name_kh', '{!! str_limit($name_kh, 60) !!}')
            ->editColumn('name_en', '{!! str_limit($name_en, 60) !!}')
            ->editColumn('name_fr', '{!! str_limit($name_fr, 60) !!}')
            ->addColumn('action', function ($degree) {
                return '<a href="#edit-'.$degree->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> '. trans('buttons.general.crud.edit').'</a>';
            })
            ->make(true);
    }

}
