@extends('backend.layouts.master')

@section('after-styles-end')
    {!! Html::style('plugins/iCheck/square/red.css') !!}
    {!! Html::style('plugins/sweetalert2/dist/sweetalert2.css') !!}
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}

    <style>
        .margin-bottom-15 {
            margin-bottom: 15px;
        }
    </style>
@stop

@section('page-header')
    <h1>
        {!! app_name() !!}
        <small>Fill Missing Score</small>
    </h1>
@endsection

@section('content')
    <form class="form-horizontal"
          method="POST"
          action="{{ route('distribution-department.generate') }}">
        {{ csrf_field() }}
        <input type="hidden" :value="academicYearSelected" name="academic_year_id"/>
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Fill score</h3>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-12 margin-bottom-15">
                        <div class="pull-right">
                            <input type="text" class="form-control"/>
                        </div>
                        <div class="form-inline">
                            <template v-if="academicYears !== null">
                                <select class="form-control input-sm"
                                        @change="getStudentWhoHaveNoScore"
                                        v-model="academicYearSelected">
                                    <option :value="academicYear.id"
                                            :key="key"
                                            v-for="(academicYear, key) in academicYears">
                                        @{{ academicYear.name_latin }}
                                    </option>
                                </select>
                            </template>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered table-condensed table-striped">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>ID Card</th>
                                <th>Name Khmer</th>
                                <th>Name Latin</th>
                                <th>Score 1</th>
                                <th>Score 2</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-if="students.length > 0">
                                <tr v-for="(student, key) in students">
                                    <td>@{{ key+1 }}</td>
                                    <td>@{{ student.student_id_card }}</td>
                                    <td>@{{ student.student_name_kh }}</td>
                                    <td>@{{ student.student_name_latin }}</td>
                                    <td><input type="number"
                                               v-model="student.score_1"
                                               class="form-control"></td>
                                    <td><input type="number"
                                               v-model="student.score_2"
                                               class="form-control"></td>
                                    <td>
                                        <button class="btn btn-primary btn-xs"
                                                @click="saveStudentWhoHaveNoScore">Save
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="8">
                                        <p class="text-center">No Data.</p>
                                    </td>
                                </tr>
                            </template>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('distribution-department.index') !!}"
                       class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                </div>

                <div class="pull-right">
                    <button class="btn btn-primary btn-xs"
                            @click="saveStudentWhoHaveNoScore">Save
                    </button>
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
            data() {
                return {
                    academicYears: null,
                    academicYearSelected: null,
                    students: []
                }
            },
            methods: {
                getAcademicYear() {
                    axios.post('/admin/distribution-department/get-academic-year')
                        .then((response) => {
                            this.academicYears = response.data.data
                            this.academicYearSelected = response.data.data[0].id
                            this.getStudentWhoHaveNoScore()
                        })
                },
                getStudentWhoHaveNoScore() {
                    toggleLoading(true)
                    axios.post('/admin/distribution-department/get-student-who-have-no-score', {
                        academic_year_id: this.academicYearSelected
                    }).then((response) => {
                        this.students = response.data.data
                        toggleLoading(false)
                    })
                },
                saveStudentWhoHaveNoScore() {
                    toggleLoading(true)
                    axios.post('/admin/distribution-department/save-student-who-have-no-score', {
                        students: this.students
                    }).then((response) => {
                        if (response.data.code === 1) {
                            this.getStudentWhoHaveNoScore()
                        }
                        toggleLoading(false)
                    })
                }
            },
            mounted() {
                this.getAcademicYear()
            }
        })
    </script>
@stop
