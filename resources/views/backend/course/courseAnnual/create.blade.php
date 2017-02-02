@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.courseAnnuals.title') . ' | ' . trans('labels.backend.courseAnnuals.sub_create_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.courseAnnuals.title') }}
        <small>{{ trans('labels.backend.courseAnnuals.sub_create_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('css/backend/plugin/jstree/themes/default/style.min.css') !!}
@stop

@section('content')
    {!! Form::open(['route' => 'admin.course.course_annual.store', 'class' => 'form-horizontal create_course_annual', 'role' => 'form', 'method' => 'post', 'id' => 'create-role']) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.courseAnnuals.sub_create_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                @include('backend.course.courseAnnual.fields')

                <div class="form-group">
                    {!! Form::label('student_group', "Student Group", ['class' => 'col-lg-2 control-label required label_student_group']) !!}
                    <div class="col-lg-2">
                        <div id="jstree_group">

                        </div>

                    </div>
                </div>



            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.course.course_annual.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                </div>

                <div class="pull-right">
                    <input type="submit" id="submit_form" class="btn btn-success btn-xs" value="{{ trans('buttons.general.crud.create') }}" />
                    {{--<button class="btn btn-xs btn-success" id="btn_generate_group">Get Group</button>--}}
                </div>
                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->
    {!! Form::close() !!}
@stop

@section('after-scripts-end')
    {{--{!! Html::script('js/backend/plugin/jstree/jstree.min.js') !!}--}}

    {!! Html::style('plugins/jstree/themes/default/style.min.css') !!}
    {!! Html::script('plugins/jstree/jstree.min.js') !!}

    {!! Html::script('js/backend/access/roles/script.js') !!}
    {!! Html::script('js/backend/course/courseAnnual/course_annual.js') !!}



    <script>


        $(document).ready(function() {
            $('.label_student_group').hide();
            $('#jstree_group').hide();



            $('form.create_course_annual').on('submit', function(e) {

                var credit = $('input#credit').val();

                if(credit != '') {
                    if($.isNumeric(credit)) {
                        return true;
                    } else {

                        notify('error', 'Not a numeric!')
                        e.preventDefault();

                    }
                } else {
                    notify('error', 'Field credit is required!')
                    e.preventDefault();
                }
//                e.preventDefault();
//                var form_url = $(this).attr('action');
//                var baseData = {
//                    group_selected: ($('#jstree_group').is(':visible'))?JSON.stringify($('#jstree_group').jstree("get_selected")):''
//                };
//
//                $.ajax({
//                    type: 'POST',
//                    url: form_url+'?'+$('form.create_course_annual').serialize(),
//                    data: baseData,
//                    dataType: 'JSON',
//                    success: function(resultData) {
//                        console.log(resultData);
//
//                    }
//                });
            })

            $('#other_dept').on('click', function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'GET',
                    url: '{{route('course_annual.get_other_dept')}}',
                    data: {department_id: 'request'},
                    dataType: "html",
                    success: function(resultData) {
                        if($('select[name=other_department_id]').is(':visible')) {

                            $(this).html(resultData);
                        } else {
                            $('.other_department').append(resultData);
                        }

                    }
                });
            })


            $(document).on('change', 'select[name=other_department_id]', function() {
                var route = '{{route('course_annual.get_other_lecturer')}}';

                $.ajax({
                    type: 'GET',
                    url: route,
                    data: {department_id: $(this).val()},
                    dataType: "html",
                    success: function(resultData) {
                        $('#lecturer_lists').html(resultData);
                    }
                });
            })

            $('#btn_generate_group').on('click', function (e) {
                e.preventDefault();
//                $('#jstree_group').show();
//                $('.label_student_group').show();

                var url_lv1 = '', url_lv2 = '';
                var iconUrl1 = "{{url('plugins/jstree/img/department.png')}}";
                var iconUrl2 = "{{url('plugins/jstree/img/course_pic.png')}}";
                var baseData = {
                    department_id: $('select[name=department_id]').val(),
                    academic_year_id: $('select[name=academic_year_id]').val(),
                    semester_id: $('select[name=semester_id]').val(),
                    degree_id: $('select[name=degree_id]').val(),
                    grade_id: $('select[name=grade_id]').val(),
                    department_option_id: ($('select[name=department_option_id]').is(':visible'))?$('select[name=department_option_id]').val():''
                };

//                console.log(baseData);
                initree_group($('#jstree_group'), '{{route('admin.course.get_department')}}', '{{route('course_annual.student_group')}}', iconUrl1, iconUrl2, baseData);
            });


            function initree_group( object, url_lv1, url_lv2, iconUrl1, iconUrl2 , baseData) {

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

                                return node.id === '#' ? url_lv1+'?tree_side=course_annual'+'&department_id='+baseData.department_id+'&academic_year_id='+baseData.academic_year_id+'&grade_id='+baseData.grade_id+'&degree_id='+baseData.degree_id+'&department_option_id='+baseData.department_option_id + '&semester_id='+baseData.semester_id: url_lv2+'?academic_year_id='+baseData.academic_year_id+'&grade_id='+baseData.grade_id+'&degree_id='+baseData.degree_id+'&department_option_id='+baseData.department_option_id+ '&semester_id='+baseData.semester_id;
                            },
                            'data' : function (node) {

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
                            "icon" : iconUrl2,
                            "valid_children" : []
                        }
                    },
                    "plugins" : [
                        'checkbox', "contextmenu", "search", "state","types", "sort"
                    ]
                }).on('open_node.jstree', function (e, data) {
//                        var folderId = data.node.original.id;
//                        var moduleId = data.node.original.moduleId;
                });
            }
        })
    </script>
@stop