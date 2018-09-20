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
        <small>Distribution Department Engineer Level</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Distribution Department</h3>
            <div class="box-tools pull-right">
                <a class="btn btn-primary btn-sm"
                   href="{{ route('distribution-department.get-generate-page') }}">
                    <i class="fa fa-refresh"></i> Generate</a>
                <div class="btn-group">
                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Actions <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a :href="'/admin/distribution-department/' + academicYearSelected + '/get-student-priority-department'">
                                <i class="fa fa-download"></i> Export Student Priority Department
                            </a>
                        </li>
                        <li>
                            <a :href="'/admin/distribution-department/' + academicYearSelected + '/export'">
                                <i class="fa fa-download"></i> Export Result General
                            </a>
                        </li>
                        <li>
                            <a :href="'/admin/distribution-department/' + academicYearSelected + '/export'">
                                <i class="fa fa-download"></i> Export Result Each Department
                            </a>
                        </li>
                        <li>
                            <a href="javascript::void(0)">
                                <i class="fa fa-database"></i> Import data
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12 margin-bottom-15">
                    <form class="form-inline">
                        <template v-if="academicYears !== null">
                            <select class="form-control input-sm"
                                    id="academicYear"
                                    v-model="academicYearSelected">
                                <option :value="academicYear.id"
                                        :key="key"
                                        v-for="(academicYear, key) in academicYears">
                                    @{{ academicYear.name_latin }}
                                </option>
                            </select>
                        </template>
                    </form>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dt-responsive nowrap"
                               id="distribution-department">
                            <thead>
                            <tr>
                                <th>ID Card</th>
                                <th>Name Khmer</th>
                                <th>Name Latin</th>
                                <th>Department</th>
                                <th>Department Option</th>
                                <th>Score</th>
                                <th>Priority</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

	    $(function () {
		    let oTable = $('#distribution-department').DataTable({
			    processing: true,
			    serverSide: true,
			    ajax: {
				    url: '{{ route('distribution-department.data') }}',
				    type: 'GET',
				    data: function (d) {
                        d.academic_year_id = $('#academicYear').val()
				    }
			    },
			    columns: [
				    {data: 'student_annual.student.id_card', name: 'student_annual.student.id_card'},
				    {data: 'student.name_kh', name: 'student.name_kh'},
				    {data: 'student.full_name_latin', name: 'student.full_name_latin'},
				    {data: 'department.name_en', name: 'department.name_en'},
				    {data: 'department_option', name: 'department_option'},
				    {data: 'total_score', name: 'total_score'},
				    {data: 'priority', name: 'priority', orderable: false, searchable: false}
			    ],
			    order: [[5, 'desc']]
		    })

		    $(document).on('change', '#academicYear', function (e) {
	            oTable.draw()
	            e.preventDefault()
            })
	    })
    </script>

    <script>
	    const vm = new Vue({
		    el: '.wrapper',
		    data () {
			    return {
				    academicYears: null,
				    academicYearSelected: 2018
			    }
		    },
		    methods: {
			    getAcademicYear () {
				    axios.post('/admin/distribution-department/get-academic-year')
					    .then((response) => {
						    this.academicYears = response.data.data
						    this.academicYearSelected = response.data.data[0].id
					    })
			    }
		    },
		    mounted () {
			    this.getAcademicYear()
		    }
	    })
    </script>
@stop
