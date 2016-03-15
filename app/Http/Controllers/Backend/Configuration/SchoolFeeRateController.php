<?php namespace App\Http\Controllers\Backend\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Configuration\SchoolFee\CreateSchoolFeeRequest;
use App\Http\Requests\Backend\Configuration\SchoolFee\DeleteSchoolFeeRequest;
use App\Http\Requests\Backend\Configuration\SchoolFee\EditSchoolFeeRequest;
use App\Http\Requests\Backend\Configuration\SchoolFee\ImportSchoolFeeRequest;
use App\Http\Requests\Backend\Configuration\SchoolFee\RequestImportSchoolFeeRequest;
use App\Http\Requests\Backend\Configuration\SchoolFee\StoreSchoolFeeRequest;
use App\Http\Requests\Backend\Configuration\SchoolFee\UpdateSchoolFeeRequest;
use App\Models\Degree;
use App\Models\Department;
use App\Models\Grade;
use App\Models\Promotion;
use App\Models\Scholarship;
use App\Repositories\Backend\SchoolFee\SchoolFeeRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SchoolFeeRateController extends Controller
{

    /**
     * @var SchoolFeeRepositoryContract
     */
    protected $schoolFees;

    /**
     * @param SchoolFeeRepositoryContract $schoolFeeRepo
     */
    public function __construct(
        SchoolFeeRepositoryContract $schoolFeeRepo
    )
    {
        $this->schoolFees = $schoolFeeRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.configuration.schoolFee.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateSchoolFeeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateSchoolFeeRequest $request)
    {
        $departments = Department::where('parent_id',11)->lists('name_kh','id');
        $grades = Grade::lists('name_kh','id');
        $promotions = Promotion::orderBy('name','desc')->limit(16)->lists('name','id');
        $scholarships = Scholarship::where('active',true)->lists('code','id');
        $degrees = Degree::lists('name_kh','id');
        return view('backend.configuration.schoolFee.create', compact('departments','grades','promotions','scholarships','degrees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateSchoolFeeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create_scholarship_fee(CreateSchoolFeeRequest $request)
    {
        $departments = Department::where('parent_id',11)->lists('name_kh','id');
        $grades = Grade::lists('name_kh','id');
        $promotions = Promotion::orderBy('name','desc')->limit(5)->lists('name','id');
        $scholarships = Scholarship::lists('name_kh','id');
        $degrees = Department::lists('name_kh','id');
        return view('backend.configuration.schoolFee.create_scholarship_fee', compact('departments','grades','promotions','scholarships','degrees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreSchoolFeeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSchoolFeeRequest $request)
    {
        $this->schoolFees->create($request->all());
        return redirect()->route('admin.configuration.schoolFees.index')->withFlashSuccess(trans('alerts.backend.generals.created'));
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
     * @param EditSchoolFeeRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditSchoolFeeRequest $request, $id)
    {

        $schoolFee = $this->schoolFees->findOrThrowException($id);

        $departments = Department::where('parent_id',11)->lists('name_kh','id');
        $grades = Grade::lists('name_kh','id');
        $promotions = Promotion::orderBy('name','desc')->limit(16)->lists('name','id');
        $scholarships = Scholarship::where('active',true)->lists('code','id');
        $degrees = Degree::lists('name_kh','id');

        return view('backend.configuration.schoolFee.edit',compact('schoolFee','departments','grades','promotions','scholarships','degrees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateSchoolFeeRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSchoolFeeRequest $request, $id)
    {
        $this->schoolFees->update($id, $request->all());
        return redirect()->route('admin.configuration.schoolFees.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteSchoolFeeRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteSchoolFeeRequest $request, $id)
    {
        if($request->ajax()){
            $this->schoolFees->destroy($id);
        } else {
            return redirect()->route('admin.configuration.schoolFees.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
        }
    }

    public function data($with_scholarships)
    {
        $schoolFees = DB::table('schoolFeeRates')
            ->leftJoin('degrees', 'schoolFeeRates.degree_id', '=', 'degrees.id')
            ->leftJoin('promotions', 'schoolFeeRates.promotion_id', '=', 'promotions.id')
            ->leftJoin('scholarships', 'schoolFeeRates.scholarship_id', '=', 'scholarships.id');
        if($with_scholarships == "true") {
            $schoolFees = $schoolFees
                ->whereNotNull('scholarship_id');
        } else {
            $schoolFees = $schoolFees
                ->whereNull('scholarship_id');
        }

        $schoolFees = $schoolFees
                ->select(['schoolFeeRates.id','to_pay','to_pay_currency','degrees.name_kh as degree_name_kh','scholarships.code as scholarship_code','promotions.name as promotion_name','academic_year_id']);


        $datatables =  app('datatables')->of($schoolFees);

        return $datatables
            ->editColumn('to_pay', '{!! $to_pay." ".$to_pay_currency !!}')
            ->addColumn('departments', function($schoolFee){

                $related_departments = DB::table('departments')
                    ->join('department_school_fee_rate','departments.id','=','department_school_fee_rate.department_id')
                    ->where('department_school_fee_rate.school_fee_rate_id','=',$schoolFee->id)
                    ->orderBy('name_kh','ASC')
                    ->select('name_kh')->get();
                $list = "";
                foreach($related_departments as $department){
                    $list .= $department->name_kh."<br/>";
                }
                return $list;
            })
            ->addColumn('grades', function($schoolFee){

                $related_grades = DB::table('grades')
                    ->join('grade_school_fee_rate','grades.id','=','grade_school_fee_rate.grade_id')
                    ->where('grade_school_fee_rate.school_fee_rate_id','=',$schoolFee->id)
                    ->orderBy('name_kh','ASC')
                    ->select('name_kh')->get();
                $list = "";
                foreach($related_grades as $grade){
                    $list .= $grade->name_kh."<br/>";
                }
                return $list;
            })
            ->addColumn('action', function ($schoolFee) {
                return  '<a href="'.route('admin.configuration.schoolFees.edit',$schoolFee->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.schoolFees.destroy', $schoolFee->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);

    }

    public function request_import(RequestImportSchoolFeeRequest $request){

        return view('backend.configuration.schoolFee.import');

    }

    public function import(ImportSchoolFeeRequest $request){
        $now = Carbon::now()->format('Y_m_d_H');

        // try to move uploaded file to a temporary location
        if($request->file('import')!= null){
            $import = $now. '.' .$request->file('import')->getClientOriginalExtension();

            $request->file('import')->move(
                base_path() . '/public/assets/uploaded_file/temp/', $import
            );

            $storage_path = base_path() . '/public/assets/uploaded_file/temp/'.$import;

            DB::beginTransaction();

            try{
                Excel::filter('chunk')->load($storage_path)->chunk(10000, function($results){

                    $results->each(function($row) {
                        $data = $row->toArray();
                        $data['departments'] = [1,2,3,4,5,6,7,8];
                        $data['grades'] = [1,2,3,4,5];
                        if($data['degree_id']==2){
                            $data['grades'] = [1,2];
                        }
                        $schoolFee = $this->schoolFees->create($data);
                    });
                });

            } catch(Exception $e){
                DB::rollback();
            }
            DB::commit();

            return redirect(route('admin.configuration.schoolFees.index'));
        }
    }

}
