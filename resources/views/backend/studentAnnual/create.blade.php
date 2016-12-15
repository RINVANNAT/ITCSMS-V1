@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.students.title') . ' | ' . trans('labels.backend.students.sub_create_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.students.title') }}
        <small>{{ trans('labels.backend.students.sub_create_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/daterangepicker/daterangepicker-bs3.css') !!}
    {!! Html::style('plugins/select2/select2.min.css') !!}
@endsection

@section('content')
    {!! Form::open(['route' => 'admin.studentAnnuals.store','id'=>'student_form', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true]) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.students.sub_create_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                @include('backend.studentAnnual.fields')
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.studentAnnuals.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                </div>

                <div class="pull-right">
                    <input type="submit" class="btn btn-success btn-xs" value="{{ trans('buttons.general.crud.create') }}" />
                </div>
                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->
    {!! Form::close() !!}
@stop

@section('after-scripts-end')
    {!! Html::script('plugins/moment/moment.min.js') !!}
    {!! Html::script('plugins/daterangepicker/daterangepicker.js') !!}
    {!! HTML::script('plugins/select2/select2.full.min.js') !!}
    <script>
        $(function(){
            var upload_photo_url = "{{config('app.smis_server')."/upload_photo"}}";
            $(".select2").select2();
            $('#date_start_end').daterangepicker({
                format: 'DD/MM/YYYY',
            });
            $('input[name="enable_id_card"]').change(function (event) {
                if($(this).is(":checked")){
                    $('#id_card').prop("disabled",false);
                } else {
                    $('#id_card').prop("disabled",true);
                    $('#id_card').val(null);
                }
            });
            $('input[type=file]').change(function (event) {
                //console.log($(this).mozFullPath);

                $('.profile-user-img').attr('src',URL.createObjectURL(event.target.files[0]));
            });

            $('#student_form').on('submit',function(){
                $.ajax({
                    url : upload_photo_url,
                    type : 'POST',
                    data : $('#student_form').serialize,
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,  // tell jQuery not to set contentType
                    success : function(data) {
                        console.log(data);
                        alert(data);
                    }
                });
            });
        });
    </script>
@stop