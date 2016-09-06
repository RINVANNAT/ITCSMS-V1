@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.coursePrograms.title') . ' | ' . trans('labels.backend.coursePrograms.sub_edit_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.coursePrograms.title') }}
        <small>{{ trans('labels.backend.coursePrograms.sub_edit_title') }}</small>
    </h1>
@endsection

@section('content')
    {!! Form::model($courseProgram, ['route' => ['admin.course.course_program.update', $courseProgram->id],'class' => 'form-horizontal', 'role'=>'form', 'method' => 'patch']) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.coursePrograms.sub_edit_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                @include ("backend.course.courseProgram.fields")
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.course.course_program.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                </div>

                <div class="pull-right">
                    <input type="submit" class="btn btn-info btn-xs" value="{{ trans('buttons.general.crud.update') }}" />
                </div>
                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->
    {!! Form::close() !!}
@stop


@section('after-scripts-end')
    {!! Html::script('plugins/moment/moment.min.js') !!}
    {!! Html::script('plugins/datetimepicker/bootstrap-datetimepicker.min.js') !!}
    <script src="{{url('assets/js/vue/vue.min.js')}}">
    </script>


    <script>
        $(function(){

            $(document).ready(function () {
//                $("#creditlabel").
//                #("#credithidhen")
                new Vue({
                    el: '#credittemplate',
                    data: {
                        hourcourse:$("input[name=time_course]").val(),
                        hourtp:0,
                        hourtd:0,


                    },
                    computed: {
                        // a computed getter
                        credit: function () {
                            // `this` points to the vm instance

//                            C/16 + (TD+TP)/ 32
                            return parseInt((parseInt(this.hourcourse)/16) + ((parseInt(this.hourtp) + parseInt(this.hourtd))/32));
                        }
                    }
                });



            });
        });
    </script>
@stop