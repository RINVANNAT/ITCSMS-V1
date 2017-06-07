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
    {!! Html::style('plugins/iCheck/flat/blue.css') !!}
@stop

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Select fields to export:</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            {!! Form::open(['route' => 'admin.student.export', 'class' => 'form-horizontal', 'id'=>'form_fields']) !!}
            <div class="form-group col-sm-12 box-body with-border text-muted well well-sm no-shadow" style="padding: 20px;">
                <div class="col-sm-4">
                    <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i> Select All</button>
                </div>

                <div class="col-sm-8">
                    {!! Form::checkbox('include_header', 'include_header', false) !!} Include Header <br/>
                    {!! Form::checkbox('raw_data', 'raw_data', false) !!} Raw Data
                    <input type="hidden" name="student_ids" id="student_ids" value="{{$student_ids==null?"":$student_ids}}" />
                    <input type="hidden" name="filter_academic_year" id="filter_academic_year" value="{{$academic_year==null?"":$academic_year}}" />
                    <input type="hidden" name="filter_degree" id="filter_degree" value="{{$degree==null?"":$degree}}" />
                    <input type="hidden" name="filter_grade" id="filter_grade" value="{{$grade==null?"":$grade}}" />
                    <input type="hidden" name="filter_department" id="filter_department" value="{{$department==null?"":$department}}" />
                    <input type="hidden" name="filter_gender" id="filter_gender" value="{{$gender==null?"":$gender}}" />
                    <input type="hidden" name="filter_option" id="filter_option" value="{{$option==null?"":$option}}" />
                    <input type="hidden" name="filter_origin" id="filter_origin" value="{{$origin==null?"":$origin}}" />
                    <input type="hidden" name="filter_semester" id="filter_semester" value="{{$semester==null?"":$semester}}" />
                    <input type="hidden" name="filter_group" id="filter_group" value="{{$group==null?"":$group}}" />
                    <input type="hidden" name="filter_redouble" id="filter_redouble" value="{{$redouble==null?"":$redouble}}" />
                    <input type="hidden" name="filter_radie" id="filter_radie" value="{{$radie==null?"":$radie}}" />
                </div>
            </div>

            <div class="col-sm-4 student_fields">

                <h3 class="box-title"><i class="fa fa-text-width"></i> General Information</h3>
                <blockquote>
                    {!! Form::checkbox('id', 'id', false) !!} {{trans('indexs.id')}} <br/>
                    {!! Form::checkbox('id_card', "id_card",true) !!} {{trans('indexs.id_card')}} <br/>
                    {!! Form::checkbox('name_latin', "name_latin",true) !!} {{trans('indexs.name_latin')}} <br/>
                    {!! Form::checkbox('name_kh', "name_kh",true) !!} {{trans('indexs.name_kh')}} <br/>
                    {!! Form::checkbox('dob', "dob",true) !!} {{trans('indexs.dob')}} <br/>
                    {!! Form::checkbox('gender_id', "gender_id",true) !!} {{trans('indexs.gender_id')}} <br/>
                    {!! Form::checkbox('origin_id', "origin_id",false) !!} {{trans('indexs.origin_id')}} <br/>
                    {!! Form::checkbox('pob', "pob",false) !!} {{trans('indexs.pob')}} <br/>
                </blockquote>

            </div>
            <div class="col-sm-4 student_fields">
                    <h3 class="box-title"><i class="fa fa-text-width"></i> Academic Information</h3>
                    <blockquote>
                        {!! Form::checkbox('class', "class",true) !!} {{trans('indexs.class')}} <br/>
                        {!! Form::checkbox('group', "group",false) !!} {{trans('indexs.group')}} <br/>
                        {!! Form::checkbox('promotion_id', "promotion_id",false) !!} {{trans('indexs.promotion_id')}} <br/>
                        {!! Form::checkbox('academic_year_id', "academic_year_id",false) !!} {{trans('indexs.academic_year_id')}} <br/>
                        {!! Form::checkbox('history_id', "history_id",false) !!} {{trans('indexs.history_id')}} <br/>
                        {!! Form::checkbox('department_option_id', "department_option_id",true) !!} {{trans('indexs.department_option_id')}} <br/>
                        {!! Form::checkbox('radie', "radie",false) !!} {{trans('indexs.radie')}} <br/>
                        {!! Form::checkbox('observation', "observation",false) !!} {{trans('indexs.observation')}} <br/>
                    </blockquote>

            </div>
            <div class="col-sm-4 student_fields">
                <h3 class="box-title">Other Information</h3>
                <blockquote>
                    {!! Form::checkbox('mcs_no', "mcs_no",false) !!} {{trans('indexs.mcs_no')}} <br/>
                    {!! Form::checkbox('can_id', "can_id",false) !!} {{trans('indexs.can_id')}} <br/>
                    {!! Form::checkbox('high_school_id', "high_school_id",false) !!} {{trans('indexs.high_school_id')}} <br/>
                    {!! Form::checkbox('photo', "photo",false) !!} {{trans('indexs.photo')}} <br/>
                    {!! Form::checkbox('phone', "phone",false) !!} {{trans('indexs.phone')}} <br/>
                    {!! Form::checkbox('email', "email",false) !!} {{trans('indexs.email')}} <br/>
                    {!! Form::checkbox('admission_date', "admission_date",false) !!} {{trans('indexs.admission_date')}} <br/>
                    {!! Form::checkbox('address', "address",false) !!} {{trans('indexs.address')}} <br/>
                    {!! Form::checkbox('address_current', "address_current",false) !!} {{trans('indexs.address_current')}} <br/>
                    {!! Form::checkbox('parent_name', "parent_name",false) !!} {{trans('indexs.parent_name')}} <br/>
                    {!! Form::checkbox('parent_occupation', "parent_occupation",false) !!} {{trans('indexs.parent_occupation')}} <br/>
                    {!! Form::checkbox('parent_address', "parent_address",false) !!} {{trans('indexs.parent_address')}} <br/>
                    {!! Form::checkbox('parent_phone', "parent_phone",false) !!} {{trans('indexs.parent_phone')}} <br/>
                </blockquote>

            </div>

            <div class="col-sm-12">
                <div class="col-sm-4 pull-right">
                    <button class="btn btn-default" type="button" id="btn_cancel" style="margin-right: 5px;">Cancel</button>
                    <button class="btn btn-danger" type="submit">Export</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div><!-- /.box-body -->
    </div><!--box-->


@stop
@section('after-scripts-end')
    {!! Html::script('plugins/iCheck/icheck.min.js') !!}
    <script>
        //Enable iCheck plugin for checkboxes
        //iCheck for checkbox and radio inputs
        $('.student_fields input[type="checkbox"]').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-blue'
        });

        //Enable check and uncheck all functionality
        $(".checkbox-toggle").click(function () {
            var clicks = $(this).data('clicks');
            if (clicks) {
                //Uncheck all checkboxes
                $(".student_fields input[type='checkbox']").iCheck("uncheck");
                $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
            } else {
                //Check all checkboxes
                $(".student_fields input[type='checkbox']").iCheck("check");
                $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
            }
            $(this).data("clicks", !clicks);
        });

        $('#btn_cancel').click(function(){
           window.close();
        });
    </script>
@stop