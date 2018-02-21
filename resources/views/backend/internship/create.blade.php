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
                <h3 class="box-title">Create Internship</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">No.</label>
                                    <div class="col-md-1">
                                        <input type="text"
                                               readonly
                                               name="no"
                                               class="form-control">
                                    </div>

                                    <label class="col-md-1 control-label">Ref. No</label>
                                    <div class="col-md-3">
                                        <input type="text"
                                               name="ref_no"
                                               class="form-control">
                                    </div>

                                    <label class="col-md-1 control-label">Date</label>
                                    <div class="col-md-3">
                                        <input class="form-control"
                                               id="date"
                                               name="date"
                                               type="text">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label required">Subject</label>
                                    <div class="col-md-9">
                                        <input type="text"
                                               name="subject"
                                               required
                                               class="form-control"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label required">Internship Period</label>
                                    <div class="col-md-9">
                                        <input type="text"
                                               name="period"
                                               id="period"
                                               class="form-control"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>Company Info</legend>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label required">Contact Name</label>
                                        <div class="col-md-8">
                                            <input type="text" name="contact_name" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label required">Contact Detail</label>
                                        <div class="col-md-8">
                                            <textarea name="contact_detail"
                                                      id="contact_detail"
                                                      class="form-control"
                                                      rows="18"></textarea>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-md-6">
                                <fieldset>
                                    <legend>Student Info</legend>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label required">Academic Year</label>
                                        <div class="col-md-8">
                                            <select class="form-control"
                                                    name="academic_year"
                                                    id="academic_year">
                                                @foreach($academic_years as $academic_year)
                                                    <option value="{{ $academic_year->id }}">{{ $academic_year->name_latin }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label required">Students</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="students[]" id="students"></select>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
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
                    <input type="submit" id="submit_form" class="btn btn-success btn-xs" value="Create">
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
    <script src="https://cdn.ckeditor.com/ckeditor5/1.0.0-alpha.2/classic/ckeditor.js"></script>
    <script>
        var $search_url = "{{route('internship.search')}}";
        var base_url = '{{url('img/profiles/')}}';
        var get_group_url = "{{route('course_annual.get_group_filtering')}}";

        $(function () {

            $('#academic_year').select2({
                theme: "bootstrap"
            });

            $('#date').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            $('#period').daterangepicker();

            ClassicEditor.create( document.querySelector( '#contact_detail' ) )
                .catch( error => {
                    console.error( error );
                } );

            // search student
            $("#students").select2({
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
            });
        })
    </script>
@stop