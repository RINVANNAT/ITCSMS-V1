@extends ('backend.layouts.popup_master')

@section ('title', 'Course Annual' . ' | ' . 'Edit Course Annual')

@section('content')

    <div class="box box-success">

        <style>
            .enlarge-text{
                font-size: 36px;
            }
            .enlarge-number{
                font-size: 22px;
            }

            .enlarge-selection{
                font-size: 14px;
                border-radius: 0;
                background: transparent;
                width: 150px;
                text-indent: 10px;
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
            <h3 class="box-title">Course Annual Edition</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body panel">

            {!! Form::open(['route' => ['admin.course.edit_course_annual',$course->id], 'class' => 'form-horizontal form_edit_course_annual', 'role' => 'form', 'method' => 'put']) !!}

                <table class="table table-hover" id="dev-table">
                    <thead>
                    <tr style="background-color: #0a6aa1; color: #00ee00">
                        <th>Order</th>
                        <th>Name</th>
                        <th>Input Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>Time Course</td>
                        <td>
                            {!! Form::text('time_course', ($course->time_course != null)?$course->time_course:0, ['class' => 'form-control number_only inputs_val','required'=>'required']) !!}
                        </td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Time TD</td>
                        <td>
                            {!! Form::text('time_td', ($course->time_td != null)?$course->time_td:0, ['class' => 'form-control number_only inputs_val','required'=>'required']) !!}
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Time TP</td>
                        <td>
                            {!! Form::text('time_tp', ($course->time_tp != null)?$course->time_tp:0, ['class' => 'form-control number_only inputs_val','required'=>'required']) !!}
                        </td>
                    </tr>
                    
                    </tbody>
                </table>

            @if(isset($studentGroups))

                <div class="col-md-12 no-padding" style=" text-align: left">
                    <label style="font-size: 12pt" for="student" class="btn btn-primary btn-xs">
                        <input type="checkbox" name="student" id="student" value="all">
                        All Group
                    </label>

                </div>


                <div class="col-md-12">
                    @foreach($studentGroups as $group)
                        @if(count($selected_groups) > 0))

                            @if(in_array($group->group_code, $selected_groups[$course->id]))
                                <label for="{{$group->group_id}}" style="font-size: 12pt" class="col-md-2 btn btn-xs">
                                    <input type="checkbox" class="student_group" checked name="group[]" id="{{$group->group_id}}" value="{{$group->group_id}}">
                                    {{$group->group_code}}
                                </label>
                            @else
                                <label for="{{$group->group_id}}" style="font-size: 12pt" class=" col-md-2 btn  btn-xs">
                                    <input type="checkbox"  class="student_group" name="group[]" id="{{$group->group_id}}" value="{{$group->group_id}}">
                                    {{$group->group_code}}
                                </label>

                            @endif
                        @else

                            <label for="{{$group->group_id}}" style="font-size: 12pt" class=" col-md-2 btn btn-xs">
                                <input type="checkbox" name="group[]" class="student_group" id="{{$group->group_id}}" value="{{$group->group_id}}">
                                {{$group->group_code}}
                            </label>
                        @endif



                    @endforeach
                </div>

            @endif

            {!! Form::close() !!}
        </div>

    </div>

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="#" id="cancel_edit" class="btn btn-default btn-xs">{{ trans('buttons.general.cancel') }}</a>
            </div>

            <div class="pull-right">
                <input type="button" id="btn_update_course" class="btn btn-danger btn-xs" value="OK" />
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

                    if(result.status== true) {
                        notify('success', 'info', result.message);
                        window.opener.refresh_course_tree();

//                        window.parent.$("#annual_course");

                        window.setTimeout(function(){
                          window.close();
                        }, 2000);
                    } else {
                        notify('error', 'info', result.message);
                    }

                }
            });
        }


        $('#btn_update_course').on('click', function(e) {
            e.preventDefault();
            var data = $('form.form_edit_course_annual').serialize();

            var credit = $('input[name=course_annual_credit]').val();

            if($.isNumeric(credit)) {
                swal({
                    title: "Confirm",
                    text: "Save Edition??",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes,Save it",
                    closeOnConfirm: true
                }, function(confirmed) {
                    if (confirmed) {
                        ajaxRequest('PUT', $('form.form_edit_course_annual').attr('action'), data);
                    }
                });

            } else {
                notify('error', 'Field Credit is not a valid value')
            }

        })

        $('#cancel_edit').on('click', function() {
            window.close();
        })


        $(".number_only").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });


        $('.inputs_val').keydown(function (e) {
            if (e.which === 13) {
                var index = $('.inputs_val').index(this) + 1;
                $('.inputs_val').eq(index).focus().select();
            }
        });


        $('input[name=student]').on('change', function() {
            if($(this).is(':checked')) {
                $('.student_group').prop('checked', true);
            } else {
                $('.student_group').prop('checked', false);
            }
        })


    </script>


@stop