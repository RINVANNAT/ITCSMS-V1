@extends ('backend.layouts.popup_master')

@section ('title', 'Student Redouble Lists' . ' | ' . 'Redouble Courses')

@section('content')

    <div class="box box-success">

        <style>

            div.vertical{
                position: relative;
                height: 130px;
                margin-left: 0;
                writing-mode: tb-rl;
                filter: flipv fliph;
                text-align: center;
            }

            th.vertical {
                padding-bottom: 10px;
                vertical-align: bottom;
            }

            th, td.center {
                text-align: center;
            }


            #student_resit_subject tr:hover {
                background-color: #e8f2eb;
            }

        </style>
        <div class="box-header with-border">
            <h3 class="box-title">Student Supplementary Lists: {{$academicYear->name_latin}}</h3>

        </div>
        <!-- /.box-header -->
        <div class="box-body panel">

            <ul class="nav nav-tabs">
                <li class="active tablink"><a href="#" id="student" >Student Supplementary Lists</a></li>
                <li class="tablink"><a href="#" id="subject" > Course Supplementary List </a></li>
            </ul>

        </div>

        <div id="student_resit_list" class="tabcontent">

            @if($status)
                <div class="table-responsive">
                    {!! Form::open(['route' => 'course_annual.export_student_re_exam', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'student_re_exam_form']) !!}
                    @include('backend.course.courseAnnual.includes.student_resit_list')
                    {!! Form::close() !!}
                </div>
            @else

                <div id="blog_message">
                    <div class="alert alert-error">
                        <h4><i class="icon fa fa-info"></i> Rattrapage </h4>
                        <p> {{$message}}</p>
                    </div>
                </div>

            @endif



        </div>

        <div id="subject_resit_list" class="tabcontent" style="display: none">

            @if($status)
                <div class="table-responsive">
                    {!! Form::open(['route' => 'course_annual.export_supplementary_subject', 'name' => 'resit-form' , 'class' => 'form-horizontal ',  'role' => 'form', 'method' => 'post', 'id' => 'supplementary_subject_lists']) !!}

                    <div id="resit_subject">
                        @include('backend.course.courseAnnual.includes.resit_subject_lists')
                    </div>
                    {!! Form::close() !!}
                </div>
            @endif

        </div>

    </div>


    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="#" id="cancel_table" class="btn btn-default btn-xs">{{ trans('buttons.general.cancel') }}</a>
            </div>

            <div class="pull-right">
                {{--<input type="button" id="btn_update_course" class="btn btn-danger btn-xs" value="Save and Export" />--}}
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
    {!! Html::script('plugins/moment/moment.min.js') !!}
    {!! Html::script('plugins/daterangepicker/daterangepicker.js') !!}

    <script>

        var current_resit_subjects= [];
        $(document).ready(function() {
            current_resit_subjects = getCurrentCheckBoxVal();
        })

        $('#cancel_table').on('click', function() {
            window.close();
        });

        function calculateScore(object) {

            refreshContent(object);

            var student_id = object.attr('student_id');
           if(object.is(':checked')) {
               var selected_score = object.attr('score');
               if(parseFloat(selected_score) > parseFloat('{{\App\Models\Enum\ScoreEnum::Pass_Moyenne}}')) {
                   swal({
                       title: "Attention",
                       text: "Sorry you cannot select score which is upper or equal 50",
                       type: "warning",
                       showCancelButton: false,
                       confirmButtonColor: "#DD6B55",
                       confirmButtonText: "OK",
                       closeOnConfirm: true
                   }, function(confirmed) {

                       if (confirmed) {
                           object.prop('checked', false);
                       } else {
                           object.prop('checked', false);
                       }
                   });

               } else {

                   /*$('.'+student_id).serializeArray()*/

                   var total_score = 0;
                   var total_credit=0;

                   $('.'+student_id).each(function() {
                       var score = $(this).attr('score');
                       var credit = $(this).attr('credit');
                       total_credit = parseFloat(total_credit) + parseFloat(credit);

                       if($(this).is(':checked')) {
                           total_score = parseFloat(total_score) + (parseFloat('{{\App\Models\Enum\ScoreEnum::Pass_Moyenne}}')* parseFloat(credit));
                       } else {
                           total_score = parseFloat(total_score) + (parseFloat(score) * parseFloat(credit));
                       }
                   });

                   var moyenne = parseFloat(total_score)/parseFloat(total_credit)

                   $('label#'+student_id).html(parseFloat(moyenne.toFixed(2)))
               }
           } else {

               var total_score = 0;
               var total_credit=0;
               var course_program_id = object.val();

               $('.'+student_id).each(function() {
                   var score = $(this).attr('score');
                   var credit = $(this).attr('credit');
                   total_credit = parseFloat(total_credit) + parseFloat(credit);

                   if($(this).is(':checked')) {
                       total_score = parseFloat(total_score) + (parseFloat('{{\App\Models\Enum\ScoreEnum::Pass_Moyenne}}')* parseFloat(credit));
                   } else {
                       total_score = parseFloat(total_score) + (parseFloat(score) * parseFloat(credit));
                   }
               });

               var moyenne = parseFloat(total_score)/parseFloat(total_credit)

               $('label#'+student_id).html(parseFloat(moyenne.toFixed(2)))

           }
        }

        function getCurrentCheckBoxVal() {

            var resit_subjects = [];
            $('input.input_value').each(function() {
                if(this.checked) {
                    resit_subjects.push($(this).val())
                }
            })

            return resit_subjects;

        }

        function afterChangeCheckBox() {
            var checkbox_chanes = [];
            $('input.input_value').each(function() {
                if(this.checked) {
                    checkbox_chanes.push($(this).val())
                }
            })

            return checkbox_chanes;
        }


        $('#student').on('click', function(e) {
            openTab($(this), 'student_resit_list');
        });
        $('#schedule').on('click', function(e) {
            openTab($(this), 'resit_schedule');
        })
        $('#subject').on('click', function(e) {
            openTab($(this), 'subject_resit_list');
        })

        function openTab(object, tab) {

            var i, tabcontent, tablinks;

            tabcontent = $('.tabcontent');
            $.each(tabcontent, function() {
                $(this).hide();
            });

            $.each($('.tablink'), function() {
                $(this).removeClass('active');
            });

            var clickedTab = $('#'+tab);
            clickedTab.show();
            object.parent('li').addClass('active')
        }

        $('form#student_re_exam_form').on('submit',function(e) {

            var change = afterChangeCheckBox();
            var status = true;
            if(change.length !== current_resit_subjects.length) {
                status = false;
            } else {
                $.each(change, function(key, value) {

                    if(current_resit_subject[key] != value ) {
                        status = false;
                    }
                })
            }
            status = true;
            if(status) {
                return true;
            } else {

                swal({
                    title: "Attention",
                    text: "Please Save Change Before Export",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    closeOnConfirm: true
                }, function(confirmed) {

                    if (confirmed) {

                    }
                });
                return false;
            }

        });


        $('form#supplementary_subject_lists').on('submit', function (e) {

            var status = true;
            $('input.date_start_end').each(function (key, value) {
                if ($(this).val() == null || $(this).val() == '') {
                    notify('error', 'Please Input all Date Time!', 'Attention!');
                    e.preventDefault();
                    status = false;
                    return false
                }
            });

            if (status) {
                $('input.room').each(function (key, value) {
                    if ($(this).val() == null || $(this).val() == '') {
                        notify('error', 'Please Complete All Room Field!', 'Attention!');
                        e.preventDefault()
                        return false
                    }
                });
            }


        })

        nonResitSubject();
        function nonResitSubject() {
            $('.count_resit').each(function(key, value) {

//                console.log($(this).text())
                if($.trim($(this).text()) == "-") {
                   $(this).parent('tr').addClass('danger');
                    $(this).parent('tr').find('input').each(function() {
                        $(this).prop('disabled', true);
//                        $(this).removeAttr('required');
                    })
                }
            })
        }

        function refreshContent(object) {

            var studentName = object.attr('student_name');
            var studentAnnualId = object.attr('student_annual_id');
            var courseName = object.attr('course_name');
            var course_annual_id = object.val();

            var div_to_append = function(student_name, student_annual_id, course_annual_id, color) {

                var div =  '<label for="name" class="label" style="width: 100%; font-size: 10pt; color: '+color+'">' + student_name +
                        '<input type="hidden" class="student_annual_id" name="student_annual_id[]" value="'+student_annual_id+'">'+
                        '<input type="hidden" name="course['+course_annual_id+'][]" value="'+student_annual_id+'">'+
                        '</label>'

                return div + '<br>';
            }

            if(object.is(':checked')) {

                var selected_score = object.attr('score');
                if(parseFloat(selected_score) < parseFloat('{{\App\Models\Enum\ScoreEnum::Pass_Moyenne}}')) {

                    var status = true;
                    var count_tr = 0;
                    $('form#supplementary_subject_lists').find('tr').each(function (key, tr) {

                       if($(tr).attr('course_annual_name') == courseName) {
                           status = false;
                           /*---note the course annual id that we pass to the function div_to_append*/
                           $(tr).find('td.td_student_name').append(div_to_append(studentName, studentAnnualId, $(tr).attr('course_annual_id'), '#00a157'));
                           var current = $(tr).find('td.count_resit').text();
                           $(tr).find('td.count_resit').html('<label for="count_resit" class="label label-success" style="font-size: 14pt"> '+(parseInt(current) +1)+'</label>');

                       }
                        count_tr++;
                    });

                    if(status === true) {

                        var new_tr_to_append = function(course_annual_id, course_annual_name, student_annual_name, student_annual_id, count_row, number_student, row_color, color) {

                            var new_tr = '<tr course_annual_id="1676" course_annual_name="'+course_annual_name+'" class="'+row_color+'">' +
                                        '<input type="hidden" name="course_annual_id[]" value="'+course_annual_id+'">' +
                                        '<td>'+count_row+'</td>' +
                                        '<td>'+course_annual_name+'</td>' +
                                        '<td class="td_student_name" style="text-align:center; vertical-align:middle;" >' +
                                            '<label for="name" class="label" style="width: 100%; font-size: 10pt; color: '+color+'">' + student_annual_name +
                                            '<input type="hidden" class="student_annual_id" name="student_annual_id[]" value="'+student_annual_id+'">' +
                                            '<input type="hidden" name="course['+course_annual_id+'][]" value="'+student_annual_id+'">' +
                                            '</label>' +
                                            '<br>' +
                                        '</td>' +
                                        '<td style="text-align:center; vertical-align:middle;">' +
                                            '<div class="input-group">' +
                                                '<div class="input-group-addon">' +
                                                '<i class="fa fa-calendar"></i>' +
                                                '</div>' +
                                                '<input type="text" name="date_start_end['+course_annual_id+']" class="form-control pull-right date_start_end">' +
                                            '</div>' +
                                        '</td>' +
                                        '<td style="text-align:center; vertical-align:middle;">' +
                                            '<input type="text" class="form-control" name="room['+course_annual_id+']">' +
                                        '</td>' +
                                        '<td class="count_resit" style="text-align:center; vertical-align:middle;">' +
                                            '<label for="count_resit" class="label label-success" style="font-size: 14pt"> '+number_student+'</label>' +
                                        '</td>' +
                                    '</tr>';

                            return new_tr;
                        }

                        $('form#supplementary_subject_lists').find('table').find('tbody').append(
                                new_tr_to_append(course_annual_id, courseName, studentName, studentAnnualId, (count_tr), 1, '', '#0A0A0A')
                        )
                    }
                }
            } else {

                $('form#supplementary_subject_lists').find('tr').each(function (key, tr) {

                    var current = $(tr).find('td.count_resit').text();
                    if($(tr).attr('course_annual_name') == courseName) {

                        var course_id = $(tr).attr('course_annual_id');
                        var new_label_div = '';

                        if($(tr).find('td.td_student_name').find('label').length > 1) {

                            $(tr).find('td.td_student_name').children('label').find('input.student_annual_id').each(function (index, input) {

                                if($(input).val() != studentAnnualId ) {
                                    var name = $(input).parent('label').text().trim();
                                    var id = $(input).val();
                                    new_label_div += div_to_append(name, id, course_id, '#0A0A0A');
                                }
                            });
                            $(tr).find('td.td_student_name').html(new_label_div)
                            $(tr).find('td.count_resit').html('<label for="count_resit" class="label label-success" style="font-size: 14pt"> '+(parseInt(current) - 1)+'</label>')
                        } else {
                            $(tr).remove();
                        }
                    }
                })
            }
            init_date_picker();
        }

        init_date_picker();
        function init_date_picker()
        {
            var dateToday = new Date();
            $("input.date_start_end").daterangepicker({
                timePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 30,
                format: 'DD/MM/YYYY H:mm',
                minDate: dateToday,
                locale: {
                    format: 'MM/DD/YYYY H:mm'
                }
            });
        }

    </script>


@stop