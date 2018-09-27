@extends('backend.layouts.master')

@section('after-styles-end')
    {!! Html::style('plugins/iCheck/square/red.css') !!}
    {!! Html::style('plugins/sweetalert2/dist/sweetalert2.css') !!}
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
@stop

@section('page-header')
    <h1>
        {!! app_name() !!}
        <small>Distribution Department Engineer Level</small>
    </h1>
@endsection

@section('content')
    <form class="form-horizontal"
          method="POST"
          action="{{ route('distribution-department.generate') }}">
        {{ csrf_field() }}
        <input type="hidden" :value="academicYearSelected" name="academic_year_id"/>
        <input type="hidden" value="{{ $grade_id }}" name="grade_id"/>
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><strong>New Distribution Department</strong></h3>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-center"><strong>({{ $academic_year_id }} - {{ $grade_id == 2 ? 'II' : 'I' }}) TOTAL STUDENT ANNUALS: @{{ totalStudentAnnuals }}</strong></h3>
                    </div>
                    <div class="col-md-12"><hr/></div>
                    <div class="col-md-12">
                        <div class="form-group row" v-if="departments !== null">
                            <template v-for="(eachDepartment, keyDept) in departments">
                                <div class="col-md-3"
                                     :key="keyDept"
                                     style="margin-bottom: 5px;">
                                    <label class="col-md-6 control-label">@{{ eachDepartment.label }}</label>
                                    <div class="col-md-6">
                                        <input type="number"
                                               value="0"
                                               :name="eachDepartment.id"
                                               class="form-control"/>
                                    </div>
                                </div>

                                <template v-for="(eachOption, key) in eachDepartment.department_options">
                                    <div class="col-md-3" style="margin-bottom: 5px;">
                                        <label class="col-md-6 control-label">
                                            @{{ eachDepartment.code }}_@{{ eachOption.code }}</label>
                                        <div class="col-md-6">
                                            <input type="number"
                                                   value="0"
                                                   :name="eachDepartment.id + '_' + eachOption.id"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                </template>
                            </template>
                        </div>
                        <div class="row" v-else>
                            <div class="col-md-12">
                                <h3 class="text-center">There are no department</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('distribution-department.index') !!}"
                       class="btn btn-danger btn-sm">{{ trans('buttons.general.cancel') }}</a>
                </div>

                <div class="pull-right">
                    <input type="submit"
                           id="submit_form"
                           class="btn btn-primary btn-sm"
                           value="Generate"/>
                    <a :href="'/admin/distribution-department/' + academicYearSelected + '/export'"
                       class="btn btn-success btn-sm">
                        <i class="fa fa-file-excel-o"></i> Export
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </form>
@endsection

@section('after-scripts-end')
    {!! Html::script('plugins/iCheck/icheck.min.js') !!}
    {!! Html::script('plugins/sweetalert2/dist/sweetalert2.js') !!}
    {!! Html::script('plugins/toastr/toastr.min.js') !!}
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    <script type="text/javascript" src="{{ asset('node_modules/vue/dist/vue.js') }}"></script>
    <script type="text/javascript" src="{{ asset('node_modules/axios/dist/axios.js') }}"></script>

    <script>
        const vm = new Vue({
            el: '.wrapper',
            data () {
                return {
                    inputValues: null,
                    academicYears: null,
                    departments: null,
                    academicYearSelected: null,
                    gradeId: null,
                    totalStudentAnnuals: 0
                }
            },
            computed: {
                enableGenerateButton () {
                    if (this.totalStudentAnnuals === 0) {
                        return true
                    }
                    return false
                }
            },
            methods: {
                getAcademicYear () {
                    axios.post('/admin/distribution-department/get-academic-year')
                        .then((response) => {
                            this.academicYears = response.data.data
                            this.academicYearSelected = response.data.data[0].id

                        })
                },
                getDepartmentChosen () {
                    toggleLoading(true)
                    axios.post('/admin/distribution-department/get-department-chosen', {
                    	academic_year_id: this.academicYearSelected,
                        grade_id: this.grade_id
                    }).then((response) => {
                        this.departments = response.data.data
                        toggleLoading(false)
                    })
                },
                getTotalStudentAnnuals () {
                    toggleLoading(true)
                    axios.post('/admin/distribution-department/get-total-student-annuals', {
                        academic_year_id: this.academicYearSelected,
                        grade_id: this.grade_id
                    }).then((response) => {
                        this.totalStudentAnnuals = response.data.data
                        this.getDepartmentChosen()
                        toggleLoading(false)
                    })
                },
                makeChangeTotalStudentAnnuals (e) {
                    var value = parseFloat(e.target.value)
                    if (value === 'NaN') {
                        return 0
                    }
                    if(value <= this.totalStudentAnnuals) {
                        this.totalStudentAnnuals = this.totalStudentAnnuals - value
                    }
                }
            },
            mounted () {
                this.academicYearSelected = '{{ $academic_year_id }}'
                this.grade_id = '{{ $grade_id }}'
                this.getTotalStudentAnnuals()
            }
        })
    </script>
@stop
