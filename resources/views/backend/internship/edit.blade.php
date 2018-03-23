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
                    <input type="submit" id="submit_form" class="btn btn-info btn-xs" value="Update">
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </form>
@endsection

@section('after-scripts-end')
    <script type="text/javascript" src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
    <script>
        var $search_url = "{{route('internship.search')}}";
        var base_url = '{{url('img/profiles/')}}';
        var get_group_url = "{{route('course_annual.get_group_filtering')}}";

        $(function () {

            $('#academic_year').select2({
                theme: "bootstrap"
            });

            $('#issue_date, #start, #end').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            $('#period').daterangepicker();

            // search student
            var selectStudents = $("#students").select2({
                placeholder: 'Enter name ...',
                theme: "bootstrap",
                allowClear: false,
                tags: true,
                createTag: function (params) {
                    return {
                        id: params.term,
                        name: params.term,
                        group: 'customer',
                        newOption: true
                    }
                },
                ajax: {
                    url: $search_url,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term || '',
                            academic_year_id: $('#academic_year').val(),
                            page: params.page || 1
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                minimumInputLength: 3,
                templateResult: formatRepoEmployee,
                templateSelection: formatRepoSelectionEmployee,
                multiple: true,
                @if(isset($internship))
                initSelection: ( (element, callback) => {
                    axios.post('{{ route('internship.get-students') }}', {
                        id: '{{ $internship->id }}'
                    }).then (response => {
                        callback(response.data)
                    })
                })
                @endif
            });
        })
    </script>
@stop