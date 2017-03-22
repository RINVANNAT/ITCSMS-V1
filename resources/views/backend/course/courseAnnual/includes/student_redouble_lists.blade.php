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
            {!! Form::open(['route' => 'course_annual.export_student_re_exam', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'student_re_exam_form']) !!}
            @include('backend.course.courseAnnual.includes.student_resit_list')
            {!! Form::close() !!}
        </div>

        <div id="subject_resit_list" class="tabcontent" style="display: none">

            {!! Form::open(['route' => 'course_annual.export_supplementary_subject', 'name' => 'resit-form' , 'class' => 'form-horizontal ',  'role' => 'form', 'method' => 'post', 'id' => 'supplementary_subject_lists']) !!}

            @include('backend.course.courseAnnual.includes.resit_subject_lists')

            {!! Form::close() !!}

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

    {{--myscript--}}

    <script>




        var current_resit_subjects= [];
        $(document).ready(function() {
            current_resit_subjects = getCurrentCheckBoxVal();
        })

        $('#cancel_table').on('click', function() {
            window.close();
        });

        function calculateScore(object) {
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


        $('button.save_change').on('click',function() {
            var url = '{{route('save_student_resit_exam')}}'
            var data = $('form#student_re_exam_form').serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data:data ,
                dataType: "json",
                success:function(result) {
                    if(result.status) {
                        notify('success', result.message, 'Info');
                        current_resit_subjects= getCurrentCheckBoxVal();
                        location.reload();
                    }

                }
            })

        });

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

        nonResitSubject();
        function nonResitSubject() {
            $('.count_resit').each(function(key, value) {

//                console.log($(this).text())
                if($.trim($(this).text()) == "-") {
                   $(this).parent('tr').css('display', 'none')
                    /*$(this).parent('tr').find('input').each(function() {
                        $(this).prop('disabled', true);
                        $(this).removeAttr('required');
                    })*/
                }
            })
        }
    </script>


@stop