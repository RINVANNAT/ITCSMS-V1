<?php namespace App\Http\Controllers\Backend\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Configuration\HighSchool\ImportHighSchoolRequest;
use App\Http\Requests\Backend\Configuration\StudentBac2\CreateStudentBac2Request;
use App\Http\Requests\Backend\Configuration\StudentBac2\DeleteStudentBac2Request;
use App\Http\Requests\Backend\Configuration\StudentBac2\EditStudentBac2Request;
use App\Http\Requests\Backend\Configuration\StudentBac2\ImportStudentBac2Request;
use App\Http\Requests\Backend\Configuration\StudentBac2\RequestImportStudentBac2Request;
use App\Http\Requests\Backend\Configuration\StudentBac2\StoreStudentBac2Request;
use App\Http\Requests\Backend\Configuration\StudentBac2\UpdateStudentBac2Request;
use App\Http\Requests\Request;
use App\Models\AcademicYear;
use App\Models\Origin;
use App\Repositories\Backend\StudentBac2\StudentBac2RepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class StudentBac2Controller extends Controller
{

    /**
     * @var StudentBac2RepositoryContract
     */
    protected $studentBac2s;

    /**
     * @param StudentBac2RepositoryContract $studentBac2Repo
     */
    public function __construct(
        StudentBac2RepositoryContract $studentBac2Repo
    )
    {
        $this->studentBac2s = $studentBac2Repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.configuration.studentBac2.index');
    }
    public function popup_index()
    {
        $exam_id = $_GET['exam_id'];
        $last_year = AcademicYear::orderBy('id','desc')->first();
        $academicYears = AcademicYear::orderBy('id','desc')->where('id','!=',$last_year->id)->lists('name_kh','id');
        $origins = Origin::lists('name_kh','id');
        return view('backend.configuration.studentBac2.popup_index',compact('exam_id','academicYears','origins'));
    }


    /**
     * Show the form for creating a new resource.
     * @param CreateStudentBac2Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateStudentBac2Request $request)
    {
        return view('backend.configuration.studentBac2.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreStudentBac2Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentBac2Request $request)
    {
        $this->studentBac2s->create($request->all());
        return redirect()->route('admin.configuration.studentBac2s.index')->withFlashSuccess(trans('alerts.backend.generals.created'));
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
     * @param EditStudentBac2Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditStudentBac2Request $request, $id)
    {

        $studentBac2 = $this->studentBac2s->findOrThrowException($id);

        return view('backend.configuration.studentBac2.edit',compact('studentBac2'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateStudentBac2Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentBac2Request $request, $id)
    {
        $this->studentBac2s->update($id, $request->all());
        return redirect()->route('admin.configuration.studentBac2s.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteStudentBac2Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteStudentBac2Request $request, $id)
    {
        if($request->ajax()){
            $this->studentBac2s->destroy($id);
        } else {
            return redirect()->route('admin.configuration.studentBac2s.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
        }
    }

    public function data()
    {
        $exam_id = Input::get('exam_id');
        $studentBac2s = DB::table('studentBac2s')
            ->leftJoin('genders','studentBac2s.gender_id','=','genders.id')
            ->leftJoin('highSchools','studentBac2s.highschool_id','=','highSchools.id')
            ->leftJoin('gdeGrades','studentBac2s.grade','=','gdeGrades.id')
            ->leftJoin('origins','studentBac2s.province_id','=','origins.id')
            ->select(['studentBac2s.id','studentBac2s.bac_year','origins.name_kh as origin','studentBac2s.name_kh','studentBac2s.status','studentBac2s.highschool_id','genders.name_kh as gender_name_kh','highSchools.name_kh as highSchool_name_kh','dob','percentile','gdeGrades.name_en as gdeGrade_name_en']);

        $datatables =  app('datatables')->of($studentBac2s)
            ->editColumn('dob', function($studentBac2){
                $date = Carbon::createFromFormat('Y-m-d h:i:s',$studentBac2->dob);
                return $date->format('d/m/Y');
            })
            ->addColumn('action', function ($studentBac2) {
                return  '<a href="'.route('admin.configuration.studentBac2s.edit',$studentBac2->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.studentBac2s.destroy', $studentBac2->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->addColumn('export', function ($studentBac2) use ($exam_id) {
                return  '<a href="'.route('admin.candidates.create').'?exam_id='.$exam_id.'&studentBac2_id='.$studentBac2->id.'" class="btn btn-xs btn-primary export"><i class="fa fa-mail-forward" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.export').'"></i> </a>';
            });

        if ($origin = $datatables->request->get('origin')) {
            $datatables->where('studentBac2s.province_id', '=', $origin);
        }
        if ($academic_year = $datatables->request->get('academic_year')) {
            $datatables->where('studentBac2s.bac_year', '=', $academic_year);
        }

        return $datatables->make(true);
    }

    public function request_import(RequestImportStudentBac2Request $request){

        return view('backend.configuration.studentBac2.import');

    }

    public function import(ImportStudentBac2Request $request){
        $now = Carbon::now()->format('Y_m_d_H');

        // try to move uploaded file to a temporary location
        if($request->file('import')!= null){
            $import = $now. '.' .$request->file('import')->getClientOriginalExtension();

            $request->file('import')->move(
                base_path() . '/public/assets/uploaded_file/temp/', $import
            );

            $storage_path = base_path() . '/public/assets/uploaded_file/temp/'.$import;

            // and then read that data and store to database
            //Excel::load($storage_path, function($reader) {
            //    dd($reader->first());
            //});


            DB::beginTransaction();

            try{
                Excel::filter('chunk')->load($storage_path)->chunk(10000, function($results){
                    //dd($results->first());
                    // Loop through all rows
                    $results->each(function($row) {

                        $studentBac2 = $this->studentBac2Repository->create($row->toArray());
                    });
                });

            } catch(Exception $e){
                DB::rollback();
            }
            DB::commit();

            return redirect(route('studentBac2s.index'));
        }
    }

    // Candiate From Moeys
    public function get_candidate_from_moeys(){
        return view('backend.configuration.candidatesFromMoeys.index');
    }

    public function candidates_from_moyes_data(\Illuminate\Http\Request $request){

        $candidatesFromMoeys = DB::table('candidatesFromMoeys')
            ->join('studentBac2s','studentBac2s.can_id','=','candidatesFromMoeys.can_id')
            ->join('genders','studentBac2s.gender_id','=','genders.id')
            ->leftJoin('highSchools','studentBac2s.highschool_id','=','highSchools.id')
            ->join('gdeGrades','studentBac2s.grade','=','gdeGrades.id')
            ->join('origins','studentBac2s.province_id','=','origins.id')
            ->select(['candidatesFromMoeys.id','candidatesFromMoeys.bac_year','candidatesFromMoeys.can_id','origins.name_kh as origin','studentBac2s.name_kh','studentBac2s.status','studentBac2s.highschool_id','genders.name_kh as gender_name_kh','highSchools.name_kh as highSchool_name_kh','dob','percentile','gdeGrades.name_en as gdeGrade_name_en']);

        $datatables =  app('datatables')->of($candidatesFromMoeys)
            ->editColumn('dob', function($studentBac2){
                $date = Carbon::createFromFormat('Y-m-d h:i:s',$studentBac2->dob);
                return $date->format('d/m/Y');
            });

        return $datatables->make(true);
    }

}
