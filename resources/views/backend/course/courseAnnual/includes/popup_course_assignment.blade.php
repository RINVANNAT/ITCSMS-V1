@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.score.request_score_form'))

@section('content')
    <style>
        .text_font{
            font-size: 16pt;
            color: #4d2926;
            font-style: italic;
            text-align: center;
        }
        .area{
            font-size: 26px;
            border-radius: 0;
            background: transparent;
            width: 180px;
            text-indent: 10px;
        }

        .testing {

        }
        .form-edit {
            width: 70px;
            height: 20px;
        }

    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery/jquery-2.1.4.min.js')}}"><\/script>')</script>


        <div class="box box-success">
            <div class="box-header with-border text_font">
                <strong class="box-title"> <span class="text_font">{{ trans('labels.backend.courseAnnual.course_assignment') }}</span></strong>
                <strong class="box-title"> <span class="text_font">{{ $academicYear->name_latin }}</span></strong>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                {{--here what i need to write --}}

                <div class="row">
                    <div class="col-sm-12">

                        <div class="col-sm-6">
                           <div class="col-sm-12 " style="border: 2px solid green">

                               <span class="text_font"><i class="fa fa-book" aria-hidden="true"> {{ trans('labels.backend.courseAnnual.title') }} </i> </span>

                               @permission('course-assignment')
                               <button class="btn btn-xs btn-primary pull-right" style="margin-top: 5px; margin-bottom: 5px; margin-right: -8px" id="btn_assign_course">Assign Course</button>
                               @endauth
                              <div id="annual_course">

                              </div>
                           </div>
                        </div>

                        <div class="col-sm-6 no-padding">
                            <div class="col-sm-12 " style="border: 2px solid green">
                                <span class="text_font"> <i class="fa fa-user" aria-hidden="true"> {{ trans('labels.backend.courseAnnual.lecturer') }} </i></span>
                                @permission('course-assignment')
                                <button class="btn btn-xs btn-danger pull-right" style="margin-top: 5px; margin-bottom: 5px; margin-right: -8px" id="btn_remove_course">Remove Course</button>
                                @endauth
                                <div id="annual_teacher">

                                </div>
                            </div>



                        </div>

                    </div>
                </div>


            </div>
        </div>

    <div class="box box-success">
        <div class="box-body">
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="loading">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
@stop
@section('after-scripts-end')
   {{--here where i need to write the js script --}}
   {!! Html::style('plugins/jstree/themes/default/style.min.css') !!}
   {!! Html::script('plugins/jstree/jstree.min.js') !!}
   <script>


       var iconUrl1 = "{{url('plugins/jstree/img/department.png')}}";
       var iconUrl2 = "{{url('plugins/jstree/img/teacher.png')}}";
       var iconUrl3 = "{{url('plugins/jstree/img/course_pic.png')}}";

       var department_id = '{{$departmentId}}';
       var grade_id = '{{$gradeId}}';
       var degree_id = '{{$degreeId}}';
       var academic_year_id = '{{$academicYear->id}}';



       initree_course($('#annual_course'), '{{route('admin.course.get_department')}}', '{{route('admin.course.get_course_by_department')}}', iconUrl1, iconUrl3);


       initree_teacher($('#annual_teacher'), '{{route('admin.course.get_department')}}', '{{route('admin.course.get_teacher_by_department')}}','{{route('admin.course.get_course_by_teacher')}}', iconUrl1, iconUrl2, iconUrl3 );

       var id_teacher= [];

       function initree_teacher( object, url_lv1, url_lv2, url_lv3, iconUrl1, iconUrl2, iconUrl3) {

           object.jstree({
               "core" : {
                   "animation":0,
                   "check_callback" : true,
                   'force_text' : true,
                   "themes" : {
                       "variant" : "large",
                       "stripes" : true
                   },
                   "data":{
                       'url' : function (node) {

                           if(node.id == '#'){
                               return url_lv1+'?tree_side=teacher_annual'+'&department_id='+department_id+'&academic_year_id='+academic_year_id+'&grade_id='+grade_id+'&degree_id='+degree_id;
                           } else {

                               var node_id = node.id.split('_');
                               if(node_id[2] == 'teacher'){
                                   return url_lv3+'?academic_year_id='+academic_year_id+'&grade_id='+grade_id+'&degree_id='+degree_id;
                               } else {
                                   return url_lv2+'?academic_year_id='+academic_year_id+'&grade_id='+grade_id+'&degree_id='+degree_id;
                               }
                           }
                           //return node.id === '#' ? url_lv1 : url_lv2;
                       },
                       'data' : function (node) {
                           return { 'id' : node.id };
                       }
                   }
               },

               "checkbox" : {
//                   "keep_selected_style" : false,
                   three_state : false, // to avoid that fact that checking a node also check others
                   tie_selection : true

               },
               "types" : {
                   "#" : { "max_depth" : 3, "valid_children" : ["department","teacher", "course"] },
                   "department" : {
                       "icon" : iconUrl1,
                       "valid_children" : ["teacher"],

                   },
                   "teacher" :{
                       "multiple" : false,
                       "icon" : iconUrl2,
                       "valid_children" : ["course"],
                       "HTML" : true
                   },
                   "course" :{
                       "icon" : iconUrl3,
                       "valid_children" : [],
                       "multiple" : true

                   }
               },
               "plugins" : [
                   'checkbox', "contextmenu", "search", "state","types","sort"
               ]
           }).bind('select_node.jstree', function (e, data) {

               var explode = data.node.id.split('_');

               if (explode.length == 4) {
                   $('#annual_teacher').jstree(true).settings.core.multiple = false;
                   $('#annual_teacher').jstree(true).redraw(true);
                   timeTdTdCourseTeacherAnnual(data.node.id);

               } else {

                   $('#annual_teacher').jstree(true).settings.core.multiple = true;
                   $('#annual_teacher').jstree(true).redraw(true);

               }


           }).bind('deselect_node.jstree', function(e, data) {
               var explode = data.node.id.split('_');
               if(explode.length == 4) {

                   $('.li_time_teaching').remove();
               }
           });

       }

       function initree_course( object, url_lv1, url_lv4, iconUrl1, iconUrl3 ) {

           object.jstree({

               "core" : {
                   "animation":0,
                   "check_callback" : true,
                   'force_text' : true,
                   "themes" : {
                       "variant" : "large",
                       "stripes" : true
                   },
                   "data":{
                       'url' : function (node) {

                           return node.id === '#' ? url_lv1+'?tree_side=course_annual'+'&department_id='+department_id+'&academic_year_id='+academic_year_id+'&grade_id='+grade_id+'&degree_id='+degree_id : url_lv4+'?academic_year_id='+academic_year_id+'&grade_id='+grade_id+'&degree_id='+degree_id;


                       },
                       'data' : function (node) {
//                           alert(object.jstree("is_loaded"));

                           return {
                               'id' : node.id,
                               'class' : node.class
                           };
                       },
                   }
               },
               "checkbox" : {
                   "keep_selected_style" : false
               },
               "types" : {
                   "#" : { "max_depth" : 3, "valid_children" : ["department","course"] },
                   "department" : {
                       "icon" : iconUrl1,
                       "valid_children" : ["course"]
                   },
                   "course" :{
                       "icon" : iconUrl3,
                       "valid_children" : []
                   }
               },
               "plugins" : [
                   'checkbox', "contextmenu", "search", "state","types", "sort"
               ]
           }).on('open_node.jstree', function (e, data) {
               var folderId = data.node.original.id;
               var moduleId = data.node.original.moduleId;
               console.log(folderId);
               initdiv(data.node);
           });
       }


       function initdiv(object) {
           $(".department_course").each(function() {
               var tp = ($(this).attr('tp'));
               var td = ($(this).attr('td'));
               var course = ($(this).attr('course'));
               var total = parseInt(tp) + parseInt(td) + parseInt(course);
               var li_id = $(this).attr('id');
               var text = $(this).attr('course_name');

//               $(this).children('a').find('i').addClass('fa fa-book');


               $(this).append('<div class="col-sm-2 pull-right">'+
                                   '<button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> Actions <span class="caret"></span></button>'+
                                   '<ul class="dropdown-menu" role="menu">'+
                                    '<li>' + ' <a href="{{route('admin.course.form_edit_course_annual')}}" class="edit_course" li_id = '+li_id+'> <i class="fa fa-edit"> Edit </i></a> '+ '</li>'+
                                    '<li>' + ' <a href="{{route('admin.course.add_course_annual')}}" class="add_course" li_id = '+li_id+'> <i class="fa fa-plus"> Add </i></a>'+ '</li>'+
                                    '<li>' + ' <a href="{{route('admin.course.delete_course_annual')}}" class="delete_course" li_id = '+li_id+'> <i class="fa fa-trash"> Delete </i></a> '+ '</li>'+
                                   '</ul>'+
                               '<div>');

               $(this).append('<br/>'+'<label   class="label label-xs label-default time btn_tp" style="margin-left: 40px"> TP ='+tp+'</label>');

               $(this).append('<label  class="label label-xs label-default time btn_td"> TD ='+td+'</label>');

               $(this).append('<label class="label label-xs label-default time btn_course"> Course ='+course+'</label>');

               $(this).append('<label class="label label-xs label-default time"> Total ='+total+'</label>');

           });
       }

       function timeTdTdCourseTeacherAnnual(id) {

           var tp = ( $("#"+id).attr('time_tp'));
           var td = ( $("#"+id).attr('time_td'));
           var course = ( $("#"+id).attr('time_course'));

            $("#"+id).append('<li class="li_time_teaching">'+
                            '<label class="label label-xs label-info time" style="margin-left: 40px"> TP ='+tp+'</label>' +
                            '<label class="label label-xs label-info time"> TD ='+td+'</label>'+
                            '<label class="label label-xs label-info time"> Course ='+course+'</label>'
                        +'</li>');

       }

       $('#btn_cancel_request_input').on('click', function() {
           window.close();
       });

       function ajaxRequest(method, baseUrl, baseData){

           $.ajax({
               type: method,
               url: baseUrl,
               data: baseData,
               dataType: "json",
               success: function(resultData) {

                if(resultData.status == true) {

                  notify('success', 'info', resultData.message); 

                  $('#annual_course').jstree("refresh");
                  $('#annual_teacher').jstree("refresh");



                } else {
                    notify('error', 'info', resultData.message);
                }
               }
           });
       }

       $('#btn_remove_course').on('click', function() {

           var baseData= {
               course_selected: JSON.stringify($('#annual_teacher').jstree("get_selected"))
           }
           var baseUrl = '{{route('admin.course.remove_course_from_teacher')}}';

           if( baseData.course_selected != '[]') {

                swal({
                        title: "Confirm",
                        text: "Remove Selected Courses",
                        type: "info",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes",
                        closeOnConfirm: true
                    }, function(confirmed) {
                        if (confirmed) {
                             ajaxRequest('DELETE', baseUrl, baseData);
                        }
                    });
           }

       });

       $('#btn_assign_course').on('click', function() {

           var baseData= {
               teacher_id: $('#annual_teacher').jstree("get_selected"),
               course_id: $('#annual_course').jstree("get_selected")
           };

           if(baseData.teacher_id.length > 0) {

               if(baseData.course_id.length > 0) {

                   var baseUrl = '{{route('admin.course.assign_course_teacher')}}';

                   swal({
                       title: "Confirm",
                       text: "Assign Selected Courses!",
                       type: "info",
                       showCancelButton: true,
                       confirmButtonColor: "#DD6B55",
                       confirmButtonText: "Yes",
                       closeOnConfirm: true
                   }, function(confirmed) {
                       if (confirmed) {

                           ajaxRequest('POST', baseUrl, baseData);
                       }
                   });

               } else {
                   notify('error', 'info', 'Please Select Course!!');
               }

           } else {

               notify('error', 'info', 'Please Select Teacher!!');
           }
       });


       $(document).on('click','.edit_course',function(e){
           e.preventDefault();
          var id =  $(this).attr('li_id');
           var url = $(this).attr('href');

           edit_course_window = PopupCenterDual(url+'?dept_course_id='+id,'Update Candidate','500','600');


       }).on('click','.add_course',function(e){
           e.preventDefault();
           var id =  $(this).attr('li_id');
           var url = $(this).attr('href');

           var baseData = {
               dept_course_id : id
           }

           swal({
               title: "Confirm",
               text: "You want to duplicate course!!",
               type: "info",
               showCancelButton: true,
               confirmButtonColor: "#DD6B55",
               confirmButtonText: "Yes",
               closeOnConfirm: true
           }, function(confirmed) {
               if (confirmed) {
                   $.ajax({
                       type: 'POST',
                       url: url,
                       data: baseData,
                       dataType: "json",
                       success: function(resultData) {
                           if(resultData.status == true) {
                               notify('success', 'info', resultData.message);
                               $('#annual_course').jstree("refresh");

                           } else {
                               notify('error', 'info', resultData.message);
                           }
                       }
                   });
               }
           });

       }).on('click','.delete_course',function(e){
           e.preventDefault();
           var id =  $(this).attr('li_id');
           var url = $(this).attr('href');
           var baseData = {
               dept_course_id : id
           }


           swal({
               title: "Confirm",
               text: "You want to delete this course!!",
               type: "info",
               showCancelButton: true,
               confirmButtonColor: "#DD6B55",
               confirmButtonText: "Yes",
               closeOnConfirm: true
           }, function(confirmed) {
               if (confirmed) {
                   $.ajax({
                       type: 'DELETE',
                       url: url,
                       data: baseData,
                       dataType: "json",
                       success: function(resultData) {
                           if(resultData.status == true) {
                               notify('success', 'info', resultData.message);
                               $('#annual_course').jstree("refresh");

                           } else {
                               notify('error', 'info', resultData.message);
                           }
                       }
                   });
               }
           });


       });

   </script>
@stop