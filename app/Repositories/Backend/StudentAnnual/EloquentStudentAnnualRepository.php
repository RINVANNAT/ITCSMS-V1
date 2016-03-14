<?php

namespace App\Repositories\Backend\StudentAnnual;


use App\Exceptions\GeneralException;
use App\Http\Requests\Backend\Student\StoreStudentRequest;
use App\Http\Requests\Backend\Student\UpdateStudentRequest;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\StudentAnnual;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class EloquentStudentAnnualRepository
 * @package App\Repositories\Backend\StudentAnnual
 */
class EloquentStudentAnnualRepository implements StudentAnnualRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(StudentAnnual::find($id))) {
            return StudentAnnual::with(['student','scholarships'])->find($id);
            //return StudentAnnual::with(['student','scholarships','student.gender','department','grade','degree','department_option','academic_year'])->find($id);
        }

        throw new GeneralException(trans('exceptions.backend.general.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getStudentAnnualsPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return StudentAnnual::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllStudentAnnuals($order_by = 'sort', $sort = 'asc')
    {
        return StudentAnnual::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  StoreStudentRequest $request
     * @throws GeneralException
     * @return bool
     */
    public function create(StoreStudentRequest $request)
    {

        $input = $request->all();
        $last_academic_year = AcademicYear::orderBy('id','desc')->first();

        // First create general information in table students first
        $student = new Student();


        /* ------------------ work with ID Card ------------------- */
        if(!isset($input['id_card']) || $input['id_card'] == null){ // If id card is not passed along, generate a new one

            $last_student = StudentAnnual::leftJoin('students','studentAnnuals.student_id','=','students.id')
                ->where('academic_year_id',$last_academic_year->id)
                ->where('studentAnnuals.grade_id',1)
                ->orderBy('students.id_card','desc')->first();

            if($last_student != null) {
                $next_id = (int)substr($last_student->id_card, 5)+1;
            } else {
                $next_id = 1;
            }

            $student->id_card = 'e'.$last_academic_year->id.str_pad($next_id, 4, '0', STR_PAD_LEFT);
        }

        if (Student::where('id_card', $student->id_card)->first()) {
            throw new GeneralException(trans('exceptions.backend.students.already_exists'));
        }

        /* ------------------ work with photo ----------------*/

        if($request->file('photo')!= null){
            $imageName = $student->id_card . '.' .$request->file('photo')->getClientOriginalExtension();
            $student->photo = $imageName;
            $request->file('photo')->move(
                base_path() . '/public/img/profiles/', $imageName
            );
        } else {
            $student->photo = 'user.png';
        }

        if($input['mcs_no'] != "" || $input['mcs_no'] != null){
            $student->mcs_no = $input['mcs_no'];
        }
        if($input['can_id'] != "" || $input['can_id'] != null){
            $student->can_id = $input['can_id'];
        }

        $student->name_latin = $input['name_latin'];
        $student->name_kh = $input['name_kh'];
        $student->dob = $input['dob'];
        $student->radie = isset($input['radie'])?true:false;
        $student->observation = $input['observation'];
        $student->phone = $input['phone'];
        $student->email = $input['email'];
        $student->admission_date = Carbon::now();
        $student->address = $input['address'];
        $student->address_current = $input['address_current'];
        $student->parent_name = $input['parent_name'];
        $student->parent_occupation = $input['parent_occupation'];
        $student->parent_address = $input['parent_address'];
        $student->parent_phone = $input['parent_phone'];
        $student->active = isset($input['active'])?true:false;
        $student->pob = $input['pob'];
        $student->redouble_id = null;
        if(isset($input['redouble_id'])){
            if($input['redouble_id']!= ""){
                $student->redouble_id = $input['redouble_id'];
            }
        }

        $student->gender_id = $input['gender_id'];

        $student->high_school_id = null;
        if(isset($input['high_school_id'])){
            if($input['high_school_id'] != ""){
                $student->high_school_id = $input['high_school_id'];
            }
        }
        $student->origin_id = $input['origin_id'];

        $student->created_at = Carbon::now();
        $student->create_uid = auth()->id();


        DB::beginTransaction();
        if($student->save()){
            // If save successful, create student annual
            $studentAnnual = new StudentAnnual();

            $studentAnnual->student_id = $student->id;
            $studentAnnual->academic_year_id = $last_academic_year->id;
            $studentAnnual->group = $input['group'];
            $studentAnnual->active = isset($input['active'])?true:false;
            $studentAnnual->promotion_id = $input['promotion_id'];
            $studentAnnual->department_id = $input['department_id'];
            $studentAnnual->degree_id = $input['degree_id'];
            $studentAnnual->created_at = Carbon::now();
            $studentAnnual->create_uid = auth()->id();
            $studentAnnual->grade_id = $input['grade_id'];

            $studentAnnual->department_option_id = null;
            if(isset($input['department_option_id'])){
                if($input['department_option_id'] != ""){
                    $studentAnnual->department_option_id = $input['department_option_id'];
                }
            }

            $studentAnnual->history_id = null;
            if(isset($input['history_id'])){
                if($input['history_id'] != ""){
                    $studentAnnual->history_id = $input['history_id'];
                }
            }

            if ($studentAnnual->save()) {
                if(isset($input['scholarship_ids'])){
                    $studentAnnual->scholarships()->sync($input['scholarship_ids']);
                }
                DB::commit();
                return true;
            }
            DB::rollback();
            throw new GeneralException(trans('exceptions.backend.general.create_error'));
        } else {
            DB::rollback();
            throw new GeneralException(trans('exceptions.backend.general.create_error'));
        }

    }

    /**
     * @param  $id
     * @param  UpdateStudentRequest $request
     * @throws GeneralException
     * @return bool
     */
    public function update($id, UpdateStudentRequest $request)
    {
        $studentAnnual = $this->findOrThrowException($id);

        $input = $request->all();
        $last_academic_year = AcademicYear::orderBy('id','desc')->first();

        // First create general information in table students first
        $student = Student::find($studentAnnual->id);


        /* ------------------ work with photo ----------------*/

        if($request->file('photo')!= null){
            $imageName = $student->id_card . '.' .$request->file('photo')->getClientOriginalExtension();
            $student->photo = $imageName;
            $request->file('photo')->move(
                base_path() . '/public/img/profiles/', $imageName
            );
        } else {
            $student->photo = 'user.png';
        }

        if($input['mcs_no'] != "" || $input['mcs_no'] != null){
            $student->mcs_no = $input['mcs_no'];
        }
        if($input['can_id'] != "" || $input['can_id'] != null){
            $student->can_id = $input['can_id'];
        }

        $student->name_latin = $input['name_latin'];
        $student->name_kh = $input['name_kh'];
        $student->dob = $input['dob'];
        $student->radie = isset($input['radie'])?true:false;
        $student->observation = $input['observation'];
        $student->phone = $input['phone'];
        $student->email = $input['email'];
        $student->admission_date = Carbon::now();
        $student->address = $input['address'];
        $student->address_current = $input['address_current'];
        $student->parent_name = $input['parent_name'];
        $student->parent_occupation = $input['parent_occupation'];
        $student->parent_address = $input['parent_address'];
        $student->parent_phone = $input['parent_phone'];
        $student->active = isset($input['active'])?true:false;
        $student->pob = $input['pob'];
        $student->redouble_id = null;
        if(isset($input['redouble_id'])){
            if($input['redouble_id']!= ""){
                $student->redouble_id = $input['redouble_id'];
            }
        }

        $student->gender_id = $input['gender_id'];

        $student->high_school_id = null;
        if(isset($input['high_school_id'])){
            if($input['high_school_id'] != ""){
                $student->high_school_id = $input['high_school_id'];
            }
        }
        $student->origin_id = $input['origin_id'];

        $student->created_at = Carbon::now();
        $student->create_uid = auth()->id();


        DB::beginTransaction();
        if($student->save()){

            $studentAnnual->student_id = $student->id;
            $studentAnnual->academic_year_id = $last_academic_year->id;
            $studentAnnual->group = $input['group'];
            $studentAnnual->active = isset($input['active'])?true:false;
            $studentAnnual->promotion_id = $input['promotion_id'];
            $studentAnnual->department_id = $input['department_id'];
            $studentAnnual->degree_id = $input['degree_id'];
            $studentAnnual->created_at = Carbon::now();
            $studentAnnual->create_uid = auth()->id();
            $studentAnnual->grade_id = $input['grade_id'];

            $studentAnnual->department_option_id = null;
            if(isset($input['department_option_id'])){
                if($input['department_option_id'] != ""){
                    $studentAnnual->department_option_id = $input['department_option_id'];
                }
            }

            $studentAnnual->history_id = null;
            if(isset($input['history_id'])){
                if($input['history_id'] != ""){
                    $studentAnnual->history_id = $input['history_id'];
                }
            }

            if ($studentAnnual->save()) {
                if(isset($input['scholarship_ids'])){
                    $studentAnnual->scholarships()->sync($input['scholarship_ids']);
                }
                DB::commit();
                return true;
            }
            DB::rollback();
            throw new GeneralException(trans('exceptions.backend.general.update_error'));
        } else {
            DB::rollback();
            throw new GeneralException(trans('exceptions.backend.general.update_error'));
        }
    }

    /**
     * @param  $id
     * @throws GeneralException
     * @return bool
     */
    public function destroy($id)
    {

        $model = $this->findOrThrowException($id);

        if ($model->delete()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }
}
