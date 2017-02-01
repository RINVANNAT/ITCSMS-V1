@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.coursePrograms.title') . ' | ' . trans('labels.backend.coursePrograms.sub_create_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.coursePrograms.title') }}
        <small>{{ trans('labels.backend.coursePrograms.sub_create_title') }}</small>
    </h1>
@endsection


@section('content')
{!! Form::open(['route' => 'admin.course.course_program.store',
                'class' => 'form-horizontal',
                'role' => 'form',
                 'method' => 'post',
                 'id' => 'create-role']) !!}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.coursePrograms.sub_create_title') }}</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            @include('backend.course.courseProgram.fields')
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="{!! route('admin.course.course_program.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
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
                        hourcourse:0,
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