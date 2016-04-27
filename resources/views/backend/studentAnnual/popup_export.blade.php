@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.candidates.title') . ' | ' . trans('labels.backend.candidates.sub_create_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.candidates.title') }}
        <small>{{ trans('labels.backend.candidates.sub_create_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
@stop

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Select fields to export:</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="col-sm-4">
                {!! Form::checkbox('id', "ID",false) !!} Database ID <br/>
                {!! Form::checkbox('id_card', "ID Card",false) !!} ID Card <br/>
                {!! Form::checkbox('mcs_no', "Ministry No",false) !!} Ministry No <br/>
                {!! Form::checkbox('can_id', "Candidate ID",false) !!} Candidate ID <br/>
                {!! Form::checkbox('name_latin', "Name Latin",true) !!} Name Latin <br/>
                {!! Form::checkbox('name_kh', "Name Khmer",true) !!} Name Khmer <br/>
                {!! Form::checkbox('dob', "Date of Birth",true) !!} Date of Birth <br/>
                {!! Form::checkbox('photo', "Photo",false) !!} Photo <br/>
                {!! Form::checkbox('radie', "Radie",false) !!} Radie <br/>
                {!! Form::checkbox('observation', "Observation",false) !!} Observation <br/>
                {!! Form::checkbox('phone', "Phone",false) !!} Phone <br/>
                {!! Form::checkbox('email', "Email",false) !!} Email <br/>
                {!! Form::checkbox('admission_date', "Admission Date",false) !!} Admission Date <br/>
                {!! Form::checkbox('address', "Address",false) !!} Address <br/>
                {!! Form::checkbox('address_current', "Address Current",false) !!} Address Current <br/>
                {!! Form::checkbox('parent_name', "Parent Name",false) !!} Parent Name <br/>
                {!! Form::checkbox('parent_occupation', "Parent Occupation",false) !!} Parent Occupation <br/>
                {!! Form::checkbox('parent_address', "Parent Address",false) !!} Parent Address <br/>
                {!! Form::checkbox('parent_phone', "Parent Phone",false) !!} Parent Phone <br/>
                {!! Form::checkbox('pob', "Place of Birth",false) !!} Place of Birth <br/>
                {!! Form::checkbox('gender_id', "Gender",false) !!} Gender <br/>
                {!! Form::checkbox('high_school_id', "High School",false) !!} High School <br/>
                {!! Form::checkbox('origin_id', "Origin",false) !!} Origin <br/>

            </div>
            <div class="col-sm-4">
                {!! Form::checkbox('group', "Origin",false) !!} Origin <br/>
                {!! Form::checkbox('promotion_id', "Origin",false) !!} Origin <br/>
                {!! Form::checkbox('department_id', "Origin",false) !!} Origin <br/>
                {!! Form::checkbox('academic_year_id', "Origin",false) !!} Origin <br/>
                {!! Form::checkbox('history_id', "Origin",false) !!} Origin <br/>
                {!! Form::checkbox('department_option_id', "Origin",false) !!} Origin <br/>
            </div>
            <div class="col-sm-4">

            </div>
        </div><!-- /.box-body -->
    </div><!--box-->


@stop