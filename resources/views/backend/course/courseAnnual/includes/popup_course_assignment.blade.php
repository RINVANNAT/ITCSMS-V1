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

    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery/jquery-2.1.4.min.js')}}"><\/script>')</script>


        <div class="box box-success">
            <div class="box-header with-border text_font">
                <strong class="box-title"> <span class="text_font">{{ trans('labels.backend.courseAnnual.course_assignment') }}</span></strong>
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
            <div class="pull-left">
                <a href="#" id="btn_cancel_request_input" class="btn btn-default btn-xs">{{ trans('labels.backend.exams.score.btn_cancel') }}</a>
            </div>

            <div class="pull-right">
                <input type="button" id="btn_ok_request_input_score" class="btn btn-primary btn-xs" value="{{ trans('labels.backend.exams.score.btn_ok') }}" />
            </div>
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
       var iconUrl2 = "{{url('plugins/jstree/img/role.png')}}";
       var iconUrl3 = "{{url('plugins/jstree/img/employee.png')}}";

       initJsTree_StaffSelected($('#annual_course'), '{{route('admin.course.get_department')}}', '{{route('admin.course.get_course_by_department')}}', iconUrl1, iconUrl3);

       initJsTree_StaffRole($('#annual_teacher'), '{{route('admin.course.get_department')}}', '{{route('admin.course.get_teacher_by_department')}}','{{route('admin.course.get_course_by_teacher')}}', iconUrl1, iconUrl2, iconUrl3 );

       function initJsTree_StaffRole( object, url_lv1, url_lv2, url_lv3, iconUrl1, iconUrl2, iconUrl3) {

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
                               return url_lv1;
                           } else {

                               var node_id = node.id.split('_');
                               if(node_id[2] == 'teacher'){
                                   return url_lv3;
                               } else {
                                   return url_lv2;
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
                   "keep_selected_style" : false
               },
               "types" : {
                   "#" : { "max_depth" : 3, "valid_children" : ["department","teacher", "course"] },
                   "department" : {
                       "icon" : iconUrl1,
                       "valid_children" : ["teacher"]
                   },
                   "teacher" :{
                       "icon" : iconUrl2,
                       "valid_children" : ["course"]
                   },
                   "course" :{
                       "icon" : iconUrl3,
                       "valid_children" : []
                   }
               },
               "plugins" : [
                   "wholerow",'checkbox', "contextmenu", "search", "state","types", "html_data"
               ]
           });

       }

       function initJsTree_StaffSelected( object, url_lv1, url_lv4, iconUrl1, iconUrl3 ) {

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
                           return node.id === '#' ? url_lv1 : url_lv4;
                       },
                       'data' : function (node) {
                           return { 'id' : node.id };
                       }
                   }
               },
               "checkbox" : {
                   "keep_selected_style" : false
               },
               "types" : {
                   "#" : { "max_depth" : 3, "valid_children" : ["department","course"] },
                   "deparment" : {
                       "icon" : iconUrl1,
                       "valid_children" : ["course"]
                   },
                   "course" :{
                       "icon" : iconUrl3,
                       "valid_children" : []
                   }
               },
               "plugins" : [
                   "wholerow",'checkbox', "contextmenu", "search", "state","types"
               ]
           });

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
               console.log( baseData);

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

       })


   </script>
@stop