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
                        <th>Updating</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td width="1cm">1</td>
                        <td width="20cm"> Khmer</td>
                        <td><input type="text" name="name_kh" class="form-control inputs_val" value="{{$course->name_kh}}"></td>

                    </tr>
                    <tr>
                        <td>2</td>
                        <td> English</td>
                        <td><input type="text" name="name_en" class="form-control inputs_val" value="{{$course->name_en}}"></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td> France</td>
                        <td><input type="text" name="name_fr" class="form-control inputs_val" value="{{$course->name_fr}}"></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Time Course</td>
                        <td>
                            {!! Form::text('time_course', ($course->time_course != null)?$course->time_course:0, ['class' => 'form-control number_only inputs_val','required'=>'required']) !!}
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Time TD</td>
                        <td>
                            {!! Form::text('time_td', ($course->time_td != null)?$course->time_td:0, ['class' => 'form-control number_only inputs_val','required'=>'required']) !!}
                        </td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Time TP</td>
                        <td>
                            {!! Form::text('time_tp', ($course->time_tp != null)?$course->time_tp:0, ['class' => 'form-control number_only inputs_val','required'=>'required']) !!}
                        </td>
                    </tr>

                    <tr>
                        <td>7</td>
                        <td> Semester </td>

                        <td>
                            <select name="semester_id" id="select_semester_id" class="enlarge-selection">
                                @foreach($allSemesters as $semester)
                                    @if($course->semester_id == $semester->id)
                                        <option value="{{$semester->id}}" selected>{{$semester->name_en}}</option>
                                    @else
                                        <option value="{{$semester->id}}">{{$semester->name_en}}</option>

                                    @endif

                                @endforeach

                            </select>
                        </td>
                    </tr>


                    <tr>
                        <td>8</td>
                        <td> Group </td>

                        <td>
                            {!! Form::select('group',$allGroups,$course->group, array('class'=>'form-control','id'=>'group', 'placeholder' => 'Group')) !!}
                        </td>
                    </tr>
                    </tbody>
                </table>
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
                        window.opener.location.reload();
                        window.setTimeout(function(){
                          window.close();
                        }, 3000);
                    } else {
                        notify('error', 'info', result.message);
                    }

                }
            });
        }


        $('#btn_update_course').on('click', function(e) {
            e.preventDefault();
            var data = $('form.form_edit_course_annual').serialize();

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


    </script>


@stop