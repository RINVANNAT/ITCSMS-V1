@extends ('backend.layouts.master')


@section('page-header')
    <h1>
        {{ trans('labels.backend.score.title') }}
        <small>{{ trans('labels.backend.score.sub_input_title') }}</small>
    </h1>

@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
    <style>
        .toolbar {
            float: left;
        }
    </style>
@stop


@section('content')
<!-- Content Header (Page header) -->

<!-- Main content -->
<section class="content">
    @include('flash::message')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <!-- /.box-header -->
                <div class="box-body table-responsive">

                    <div class="mailbox-controls">
                        <!-- Check all button -->
                        <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                        </button>



                        


                        <div class="btn-group">
                            <button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                            <button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                            <button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>

                        </div>
                        <div class="pull-right">
                            <div class="input-group" style="width: 150px;">
                                <!-- search filed -->
                                <div id="searchfield" class="searchfield" contenteditable="true" index1="0">
                                    <div id="searchinput" contenteditable="true" tabindex="1"></div>
                                </div>


                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                    <button id='hideselectgroup'class="btn btn-default btn-sm">Hide Fillter</button>
                                </div>
                            </div>

                        </div>
                        <!-- /.btn-group -->
                        <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                        <div id="selectacademic">
                        </div>

                        <div id="groupselectorcontainer">
                        </div>


                        <!-- /.pull-right -->
                    </div>
                    <div id="table-content">
                        @include('backend.score.score_edit_by_course_table')
                    </div>

                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    <div class="pull-right" style="padding-right: 15px;">

                        <!-- /.btn-group -->
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>


</section><!-- /.content -->




<div class="container">


</div>



@endsection



@section('before-styles-end')
    <style>
        .form-score{
            width: 70px;
        }
        .scoreinput th, td{
            border:1px solid black;
            padding:2px;
        }
        .scoreinput  td{
        }
        .scorecontent {
            padding-left: 8px;
        }

    </style>
@endsection


@section('after-scripts-end')
    <script src="{{url('assets/js/handlebars/template.js')}}">
    </script>
    <script src="{{url('assets/js/handlebars/groupsselector.js')}}">
    </script>
    <script src="{{url('assets/js/handlebars/groupsselectorlong.js')}}">
    </script>
    <script src="{{url('assets/js/utility/jsutility.js')}}">
        console.log("hello from the skype");
    </script>
    <script src="{{url('assets/js/mustache.js')}}">
    </script>
    <script src="{{url('assets/js/vue/vue.min.js')}}">
    </script>

    <script src="{{url('assets/js/vue/vue-resource.min.js')}}">
    </script>






    <script>

        paramet = {};
        $( document ).ready(function() {
            function callbackCourseAnnual(data){
                $.extend(paramet, data);

                var url = "{!! route('score.input') !!}"+"?filter="+JSON.stringify(paramet);

                console.log("url:"+url);
                toggleLoading(true);
                $.get( url , function( html ) {
                    $("#table-content").html(html);
                    toggleLoading(false);
                });
            };
            function callbackSelecGroup(data){
                $.extend(paramet, data);
                var shallowEncoded = $.param( paramet );
                console.log(shallowEncoded);

                var urltmp = "{!! route('api.v1.courseAnnuals') !!}" + "?" + shallowEncoded;


                {{--var url2 = ["{!! route('api.v1.courseAnnuals') !!}"+"?filter="+JSON.stringify(paramet)];--}}
                console.log(url2);
                var url2 = [urltmp,];
                //SMSFILERLONG.config(url2,callbackCourseAnnual);

                var filter2 = new SMSFILERLONGo(url2,callbackCourseAnnual);
            };
            var url = ["{!! route('degrees.api.v1') !!}","{!! route('grades.api.v1') !!}","{!! route('departments.api.v1') !!}"];
            SMSFILER.config(url,callbackSelecGroup);

            $(document).on("click",".linkeditmany", function(e){
                var url = $(this).attr("href");
                $(this).attr("href",url+"?redirect=1&fillterdata="+JSON.stringify(paramet));
            });

            //----------------------
            // Academic Year Filter
            //----------------------
            var urlacademic = ["{!! route('academicYears.api.v1') !!}"];
            function callbackAcademic(data){
                console.log("in Academic callback");
                console.log("testxxx:");
                console.log(data);
                $.extend(paramet, data);


                var url = "{!! route('score.input') !!}"+"?filter="+JSON.stringify(paramet);

                console.log(typeof(paramet));
                var count = 0;
                $.each(paramet, function(key,value){
                    console.log("count:"+count);
                    count = count +1;
                });
                if(count == 4){
                    toggleLoading(true);
                    console.log("in if"+count);
                    $.get( url , function( html ) {
                        $("#table-content").html(html);
                        toggleLoading(false);
                    });
                }
                console.log("count2:"+count);
            };

            var filter2 = new SMSFILERLONGo(urlacademic,callbackAcademic,"#selectacademic");

        });
        $(document).on("submit","#scoreform", function(e){
            var self = this;
            e.preventDefault();
            $("#redirectfilter").attr("value",JSON.stringify(paramet));
            self.submit();


            //$(this).attr("href",url+"?redirect=1&fillterdata="+JSON.stringify(paramet));
        });

        $("#scoreform")

    </script>
@endsection

