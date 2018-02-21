@extends('backend.layouts.master')

@section('after-styles-end')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}"/>
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
                                <fieldset>
                                    <legend>Header</legend>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Ref. No</label>
                                        <div class="col-md-3">
                                            <input type="text"
                                                   name="ref_no"
                                                   class="form-control">
                                        </div>

                                        <label class="col-md-3 control-label">Date</label>
                                        <div class="col-md-3">
                                            <input class="form-control"
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
                                            <input type="text" name="subject" class="form-control"/>
                                        </div>
                                    </div>
                                </fieldset>
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
                                            <textarea name="contact_detail" class="form-control" rows="8"></textarea>
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
                                            <select class="form-control" name="academic_year" id="academic_year">
                                                @foreach($academic_years as $academic_year)
                                                    <option value="{{ $academic_year->id }}">{{ $academic_year->name_latin }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label required">Chose Students</label>
                                        <div class="col-md-8">
                                            <textarea name="contact_detail" class="form-control" rows="8"></textarea>
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
    <script>
        $(function () {
            $('#academic_year').select2();
        })
    </script>
@stop