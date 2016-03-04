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
use App\Repositories\Backend\StudentBac2\StudentBac2RepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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

    /**
     * Show the form for creating a new resource.
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
     * @param  \Illuminate\Http\Request  $request
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
     * @param  \Illuminate\Http\Request  $request
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
        $studentBac2s = DB::table('studentBac2s')
            ->select(['id','name_kh','gender_id','dob']);

        $datatables =  app('datatables')->of($studentBac2s);


        return $datatables
            ->editColumn('name_kh', '{!! str_limit($name_kh, 60) !!}')
            ->editColumn('dob', '{!! $dob !!}')
            ->editColumn('gender_id', '{!! $gender_id !!}')
            ->addColumn('action', function ($studentBac2) {
                return  '<a href="'.route('admin.configuration.studentBac2s.edit',$studentBac2->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.studentBac2s.destroy', $studentBac2->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
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

}
