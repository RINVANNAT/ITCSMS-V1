@extends('backend.layouts.master')

@section('after-styles-end')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"/>
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker-bs3.css') }}"/>
    <link rel="stylesheet" href="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}"/>
    <style>
        fieldset {
            border: 1px solid #ddd !important;
            margin: 0;
            xmin-width: 0;
            padding: 10px;
            position: relative;
            border-radius: 4px;
            background-color: #fff;
            padding-left: 10px !important;
            margin-bottom: 20px;
        }

        legend {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 0px;
            width: 35%;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px 5px 5px 10px;
            background-color: #f5f5f5;
        }

        .ck-editor__editable {
            border: 1px solid #bfbfbf;
            height: 250px;
        }
        .select2-container--bootstrap
        .select2-results__option--highlighted[aria-selected] {
            background-color: #dddddd !important;
            color: #0A0A0A;
        }
        .company-item-name h3 {
            font-size: 18px;
            font-weight: 900;
            color: #0A0A0A;
        }
    </style>
@stop

@section('page-header')
    <h1>
        {!! app_name() !!}
        <small>Internship</small>
    </h1>
@endsection

@section('content')
    <form class="form-horizontal" action="{{ route('internship.store') }}" method="POST">
        {{ csrf_field() }}
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Internship</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                        @include('backend.internship.includes.form')
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{{ route('internship.index') }}" class="btn btn-danger btn-xs">Cancel</a>
                </div>
                <div class="pull-right">
                    <a class="btn btn-primary btn-xs"
                       href="{{ route('internship.create') }}">
                        <i class="fa fa-plus-circle"></i>
                        New
                    </a>
                    <input type="submit" id="submit_form" class="btn btn-info btn-xs" value="Update">
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </form>
@endsection

@section('after-scripts-end')
    <script type="text/javascript" src="{{ asset('node_modules/inputmask/dist/min/jquery.inputmask.bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/internship.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
    <script>
        $(function () {
	        axios.post('{{ route('internship.get-students') }}', {
		        id: '{{ $internship->id }}'
	        }).then (response => {
		        response.data.forEach((item) => {
			        var newOption = new Option(item.text, item.id, true, true);
			        // Append it to the select
			        $('#students').append(newOption).trigger('change');
                })
	        })
            var newOption = new Option(
                '{{ $internship->internship_company->name }}',
                '{{ $internship->internship_company->id }}',
                true,
                true
            );
            $('.companies').append(newOption).trigger('change');
        })
    </script>
@stop
