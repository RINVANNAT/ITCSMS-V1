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

class StudentBac2OldRecordController extends Controller
{

    /**
     * @var StudentBac2RepositoryContract
     */
    protected $studentBac2sOldRecord;

    /**
     * @param StudentBac2RepositoryContract $studentBac2Repo
     */
    public function __construct(
        StudentBac2RepositoryContract $studentBac2Repo
    )
    {
        $this->studentBac2sOldRecord = $studentBac2Repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.configuration.studentBac2OldRecord.index');
    }


    public function data()
    {
        $exam_id = Input::get('exam_id');

        $studentBac2sOldRecord = DB::table('student_bac2s_old_record')
            ->leftJoin('genders','student_bac2s_old_record.gender_id','=','genders.id')
            ->leftJoin('highSchools','student_bac2s_old_record.highschool_id','=','highSchools.id')
            ->leftJoin('gdeGrades as total_grade','student_bac2s_old_record.grade','=','total_grade.id')
            ->leftJoin('gdeGrades as math_grade','student_bac2s_old_record.bac_math_grade','=','math_grade.id')
            ->leftJoin('gdeGrades as phys_grade','student_bac2s_old_record.bac_phys_grade','=','phys_grade.id')
            ->leftJoin('gdeGrades as chem_grade','student_bac2s_old_record.bac_chem_grade','=','chem_grade.id')
            ->leftJoin('origins','student_bac2s_old_record.province_id','=','origins.id')
            ->select([
                'student_bac2s_old_record.id',
                'student_bac2s_old_record.bac_year',
                'origins.name_kh as origin',
                'student_bac2s_old_record.name_kh',
                'student_bac2s_old_record.status',
                'student_bac2s_old_record.highschool_id',
                'genders.name_kh as gender_name_kh',
                'highSchools.name_kh as highSchool_name_kh',
                'dob','percentile',
                'math_grade.name_en as math_grade_name',
                'phys_grade.name_en as phys_grade_name',
                'chem_grade.name_en as chem_grade_name',
                'total_grade.name_en as total_grade_name',
            ]);

        $datatables =  app('datatables')->of($studentBac2sOldRecord)
            ->editColumn('dob', function($studentBac2){
                $date = Carbon::createFromFormat('Y-m-d h:i:s',$studentBac2->dob);
                return $date->format('d/m/Y');
            })
            ->addColumn('action', function ($studentBac2) {
                return  '<a href="'.route('admin.configuration.studentBac2OldRecords.edit',$studentBac2->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.studentBac2OldRecords.destroy', $studentBac2->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->addColumn('export', function ($studentBac2) use ($exam_id) {
                return  '<a href="'.route('admin.candidates.create').'?exam_id='.$exam_id.'&studentBac2_id='.$studentBac2->id.'" class="btn btn-xs btn-primary export"><i class="fa fa-mail-forward" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.export').'"></i> </a>';
            });

        if ($origin = $datatables->request->get('origin')) {
            $datatables->where('student_bac2s_old_record.province_id', '=', $origin);
        }
        if ($academic_year = $datatables->request->get('academic_year')) {
            $datatables->where('student_bac2s_old_record.bac_year', '=', $academic_year);
        }

        return $datatables->make(true);
    }

}
