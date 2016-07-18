@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.candidates.title') . ' | ' . trans('labels.backend.candidates.sub_create_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.candidates.title') }}
        <small>{{ trans('labels.backend.candidates.sub_create_title') }}</small>
    </h1>
@endsection

@section('content')
    {!! Form::open(['route' => 'admin.candidate.popup_store', 'id'=> 'candidate-form', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post']) !!}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.candidates.sub_create_title') }}</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            @include('backend.candidate.fields')
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="#" id="btn-cancel" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
            </div>

            <div class="pull-right">
                <input id="btn-submit" type="submit" class="btn btn-success btn-xs" value="{{ trans('buttons.general.crud.create') }}" />
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
    {!! Form::close() !!}
@stop

@section('after-scripts-end')
    <script>

        function save_candidate(){
            var candidate_form = $("#candidate-form");
            $.ajax({
                type: 'POST',
                url: candidate_form.attr('action'),
                data: candidate_form.serialize(),
                dataType: "json",
                success: function(resultData) {
                    console.log(resultData);
                }
            });
        }

        $('.input').keypress(function (e) {
            if (e.which == 13) {
                save_candidate();
                return false;    //<---- Add this line
            }
        });

        $("#btn-submit").on("click", function(e){

            e.preventDefault();
            save_candidate();
        });

        $("#btn-cancel").on("click",function(){
           window.close();
        });


        function return_back(){
            try {
                window.opener.opener.refresh_candidate_list();
                window.close();
            } catch (err) {
                alert(err.description || err) //or console.log or however you debug
            }

            self.close();
        }
    </script>
@stop