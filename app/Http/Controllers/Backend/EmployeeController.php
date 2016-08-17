<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Employee\CreateEmployeeRequest;
use App\Http\Requests\Backend\Employee\DeleteEmployeeRequest;
use App\Http\Requests\Backend\Employee\EditEmployeeRequest;
use App\Http\Requests\Backend\Employee\StoreEmployeeRequest;
use App\Http\Requests\Backend\Employee\UpdateEmployeeRequest;
use App\Models\Access\User\User;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Gender;
use App\Repositories\Backend\Employee\EmployeeRepositoryContract;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Flash;
use App\Utils\ArrayUtils;

class EmployeeController extends Controller
{
    /**
     * @var EmployeeRepositoryContract
     */
    protected $employees;
    protected $roles;

    /**
     * @param EmployeeRepositoryContract $employeeRepo
     * @param RoleRepositoryContract $roleRepo
     */
    public function __construct(
        EmployeeRepositoryContract $employeeRepo,
        RoleRepositoryContract $roleRepo
    )
    {
        $this->employees = $employeeRepo;
        $this->roles = $roleRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.employee.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::lists('name_kh','id')->toArray();
        $users = User::lists('name','id')->toArray();
        $genders = Gender::lists('name_en','id')->toArray();
        $roles = $this->roles->getAllRoles('sort', 'asc', true);
        return view('backend.employee.create',compact('departments','users','genders','roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreEmployeeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeRequest $request)
    {
        $this->employees->create($request->all());

        return redirect()->route('admin.employees.index')->withFlashSuccess(trans('alerts.backend.generals.created'));
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
     * @param EditEmployeeRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditEmployeeRequest $request, $id)
    {
        $employee = $this->employees->findOrThrowException($id);

        $departments = Department::lists('name_kh','id')->toArray();
        $users = User::lists('name','id')->toArray();
        $genders = Gender::lists('name_en','id')->toArray();
        $roles = $this->roles->getAllRoles('sort', 'asc', true);

        
        //$selected_role_ids = $employee->roles()->lists('role_id')->toArray();
        //dd($selected_role_ids);
        

        return view('backend.employee.edit',compact('employee','departments','users','genders','roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateEmployeeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeRequest $request, $id)
    {
        $this->employees->update($id, $request->all());
        return redirect()->route('admin.employees.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteEmployeeRequest $request, $id)
    {
            $this->employees->destroy($id);
        if($request->ajax()){
            return json_encode(array("success"=>true));
        } else {
            return redirect()->route('admin.employees.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
        }
    }

    public function data()
    {

        $employees = Employee::with('roles')->select(['id','name_kh','name_latin','email','phone','department_id']);

        //$employees = Employee::select(array('employees.id', 'employees.name_kh','employees.name_latin','employees.email','employees.phone','employees.department_id',DB::raw("'roles'")));
        $datatables =  app('datatables')->of($employees);


        return $datatables
            ->editColumn('name_kh', '{!! str_limit($name_kh, 60) !!}')
            ->editColumn('name_latin', '{!! str_limit($name_latin, 60) !!}')
            ->editColumn('email', '{!! $email !!}')
            ->editColumn('phone', '{!! $phone !!}')
            ->editColumn('roles', function ($employee){
                $role_view = "";
                foreach($employee->roles()->lists('name') as $role){
                    $role_view .= "<span>".$role."</span><br/>";
                }

                return $role_view;
            })
            ->editColumn('department_id', '{!! $department_id !!}')
            ->addColumn('action', function ($employee) {
                return  '<a href="'.route('admin.employees.edit',$employee->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.employees.destroy', $employee->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

    public function request_import()
    {
        return view('backend.employee.import_config');
    }


    public function import(CreateEmployeeRequest $request)
    {
        $now = Carbon::now()->format('Y_m_d_H');

        $messageSuccess = "";
        if ($request->file('import') != null) {
            $import = $now . '.' . $request->file('import')->getClientOriginalExtension();
            $request->file('import')->move(
                base_path() . '/public/assets/uploaded_file/temp/', $import
            );

            $storage_path = base_path() . '/public/assets/uploaded_file/temp/' . $import;
            $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
            $fileType = finfo_file($finfo, $storage_path) . "\n";
            finfo_close($finfo);
            $fileContext = array(
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                "application/vnd.ms-excel",
                "text/plain",
            );

            if (in_array($fileType, $fileContext)) {
                Flash::error('file type is ' . $fileType . ' Only excel or csv that import:' . $fileType . " key:");
                return redirect()->back();
            }
            $GLOBALS['countRow'] = 0;
            $GLOBALS['countRowEmployee'] = 0;
            $GLOBALS['employees'] = array();


            /**
             * 1. Import Employee
             */
            DB::beginTransaction();
            try {
                Excel::filter('chunk')->load($storage_path)->chunk(1000, function ($results) {
                    $aRow = array_keys($results->first()->toArray());
                    $results->each(function ($row) {

//                        check data format
//                        flash message error with row
//                        else go.
                        $employeeData = array();
                        $employeeData["name_kh"] = $row["name_kh"];
                        $employeeData["name_latin"] = $row["name_latin"];
                        $employeeData["email"] = $row["email"];
                        $employeeData["phone"] = $row["phone"];
                        $employeeData["active"] = true;
                        $employeeData["gender_id"] = $row["gender_id"];
                        $employeeData["birthdate"] = $row["birthdate"];;
                        $employeeData["assignees_roles"] = array("3");
                        $employeeData["department_id"] = $row["department_id"];
                        $employeeData["created_at"] = Carbon::now()->format('Y_m_d_H');
                        $employeeData["create_uid"] = auth()->id();
                        array_push($GLOBALS['employees'], $employeeData);
                    });
                });
            } catch (Exception $e) {
//                return redirect()->back();
            }


            $countNewEmployeeImport = 0 ;


            foreach ($GLOBALS['employees'] as $employee){
                $employee["name_kh"];
            }


            try {
                $uniqueEmployees = ArrayUtils::unique_multidim_array($GLOBALS['employees'], "name_latin");
                foreach ($uniqueEmployees as $employee){
                    $employeeDataBase = Employee::where('name_latin', $employee['name_latin'])->first();
                    if($employeeDataBase == null) {
                        if ($employee['name_latin']==null){
                            Flash::error("name of lecture can not be empty");
                            return redirect()->back();
                        }
                        Employee::create($employee);
                        $countNewEmployeeImport = $countNewEmployeeImport + 1;
                    }
                }
                $messageSuccess = "Number of Employee that had been import: ".$countNewEmployeeImport."<br>";
            } catch (Exception $e) {
                DB::rollback();
                Flash::error('Employee Error: Data is not correct format ');
            }
            DB::commit();
            Flash::success('Import Successfully ' . $GLOBALS['countRow'] . ' rows effected');
            return redirect()->route('admin.employees.index');
        }else{
            Flash::error("Please select import file");
        }
    }
}
