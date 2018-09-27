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
        <small>Import data for Grade {{ $grade_id == 2 ? 'II' : 'I' }}</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Upload Excel File</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <form file="true" method="POST" action="{{ route('distribution-department.import') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" value="{{ $grade_id }}" name="grade_id"/>
                        <input type="hidden" value="{{ $academic_year_id }}" name="academic_year_id"/>
                        <div class="form-group">
                            <label></label>
                            <input type="file" name="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/>
                        </div>
                        <div class="form-group">
                            <hr/>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-xs">
                                <i class="fa fa-database"></i> Store
                            </button>
                            <a href="{{ route('distribution-department.index') }}" class="btn btn-danger btn-xs">
                                <i class="fa fa-times-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
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

    </script>

    <script>
		const vm = new Vue({
			el: '.wrapper',
			data() {
				return {
					academicYears: null,
					academicYearSelected: 2018
				}
			},
			methods: {
				getAcademicYear() {
					axios.post('/admin/distribution-department/get-academic-year')
						.then((response) => {
							this.academicYears = response.data.data
							this.academicYearSelected = response.data.data[0].id
						})
				}
			},
			mounted() {
				this.getAcademicYear()
			}
		})
    </script>
@stop
