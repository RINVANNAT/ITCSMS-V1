@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . 'Generate Candidates Result')

@section('content')

    <div class="box box-success">

        <style>
            .enlarge-text{
                font-size: 36px;
            }
            .enlarge-number{
                font-size: 22px;
            }


            .modal {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.8);
                z-index: 1;
            }
        </style>
        <div class="box-header with-border">
            <h3 class="box-title">Get Result Score</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

            {!! Form::open(['route' => ['admin.course.edit_course_annual',$course->id], 'class' => 'form-horizontal calculation_score', 'role' => 'form', 'method' => 'post']) !!}
                <div class="row">
                    <div class="col-md-12 no-padding">
                        <div class="col-md-3">
                            <label for="name_kh" class="label label-default enlarge-number"> Name Khmer </label>
                            {!! Form::text("name_kh", $course->name_kh, ['class' => 'enlarge-number form-control']) !!}
                        </div>

                        <div class="col-md-3">

                        </div>
                    </div>
                </div>



            {!! Form::close() !!}

        </div>

    </div>

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="#" id="cancel_edit" class="btn btn-default btn-xs">{{ trans('buttons.general.cancel') }}</a>
            </div>

            <div class="pull-right">
                <input type="button" id="btn_get_factor_ok" class="btn btn-danger btn-xs" value="OK" />
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')

    {{--myscript--}}

    <script>

        function ajaxRequest(method, baseUrl, baseData){

            $.ajax({
                type: method,
                url: baseUrl,
                data: baseData,
                success: function(result) {
                    console.log(result);

                    if(result.status) {

                        $('#btn_result_score_candidate').hide();
                        notify('success', 'Result Success!');
                        window.close();

                    } else {
                        $('.modal').hide();
                        notify("error","info", "There are not enough candidates!!!");
                    }

                }
            });
        }


        $('#btn_get_factor_ok').on('click', function(e) {
            e.preventDefault();

        })

        $('#cancel_edit').on('click', function() {
            window.close();
        })


    </script>


@stop