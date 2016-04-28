@extends('app')


@section('content')
        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{trans('messages.user_group')}}
        <small>General information about student Absentce</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-lock"></i> {{trans('messages.user_management')}}</a></li>
        <li class="active">{{trans('messages.user_group')}}</li>
    </ol>
</section>
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
                        <a href="{!! route('groups.create') !!}">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> {{trans('messages.add')}}
                            </button>
                        </a>

                        <div class="btn-group">
                            <button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                            <button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                            <button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                            <button id="evaluation" class="btn btn-default btn-sm"> Evaluation </button>
                            <button id="autoeval" class="btn btn-default btn-sm"> AutoEvaluation </button>
                            <button id="hidecolum" class="btn btn-default btn-sm"> Hide </button>

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
                        <div id="groupselectorcontainer">
                        </div>



                        <!-- /.pull-right -->
                    </div>
                    <div id="table-content">

                        @include('backend.score.reexam_table')

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





    <div id="app">
        <ul style="margin-left: 0; padding-left: 0; border: 1px solid grey;list-style-type: none;border: 1px solid grey;">
            <li  class="eval" style="border-top: 1px solid grey; background-color: #f4f4f4; max-width: 100px;" v-for="studentEvalStatus in studentEvalStatuses" evalid="@{{studentEvalStatus.id}}">
                @{{ studentEvalStatus.name }}
            </li>
        </ul>
    </div>


</section><!-- /.content -->




<div class="container">
    @include('common.errors')

</div>
<script src="{{url('assets/js/handlebars/template.js')}}">
</script>
<script src="{{url('assets/js/handlebars/groupsselector.js')}}">
</script>
<script src="{{url('assets/js/handlebars/groupsselectorlong.js')}}">
</script>
<script src="{{url('assets/js/utility/jsutility.js')}}">
</script>


@endsection



@section('css')
    <style>
        .form-score{
            border: solid 1px black;
            width: 10%;
        }

        .tablescore td, th, table{
            border:1px solid black;
            padding-left: 2px;
            padding-right: 2px;
        }
        .tablescore th{
            border:1px solid black;
        }
        .coursetitle {
            height: 60px;
            max-width: 60px;
            overflow: hidden;
            white-space: nowrap;
            -webkit-transform: rotate(90deg);
            -moz-transform: rotate(90deg);
            -ms-transform: rotate(90deg);
            -o-transform: rotate(90deg);
            transform: rotate(90deg);
            color: #000;

        }
        .coursetitle:hover{
            height: 60px;
            max-width: 60px;
            overflow: visible;
            -webkit-transform: rotate(90deg);
            -moz-transform: rotate(90deg);
            -ms-transform: rotate(90deg);
            -o-transform: rotate(90deg);
            transform: rotate(90deg);
            color: #000;
        }



    </style>
@endsection


@section('js')
    <script>
        $("#app").hide();




        paramet = {};
        updatedepence = null;
        $( document ).ready(function() {
            $.get( "{!! route('studentEvalStatusesr.api.v1') !!}", function(studentEvalStatuses){

                new Vue({
                    el: '#app',
                    data: {
                        studentEvalStatuses: studentEvalStatuses
                    }
                })

            });


            function callbackCourseAnnual(data){
                $.extend(paramet, data);
                var url = "{!! route('scores.course') !!}"+"?fillter=true&fillterdata="+JSON.stringify(paramet);
                console.log(url);
                $.get( url , function( html ) {
                    $("#table-content").html(html);

                });
            };

            function callbackSelecGroup(data){
                $.extend(paramet, data);
                var url = "{!! route('scores.reexam') !!}"+"?fillter=true&fillterdata="+JSON.stringify(paramet);
                console.log(url);
                $.get( url , function( html ) {
                    $("#table-content").html(html);

                });
            };

            var url = ["{!! route('degrees.api.v1') !!}","{!! route('grades.api.v1') !!}","{!! route('departments.api.v1') !!}"];
            SMSFILER.config(url,callbackSelecGroup);

            $(document).on("click",".linkeditmany", function(e){
                var url = $(this).attr("href");
                $(this).attr("href",url+"?redirect=1&fillterdata="+JSON.stringify(paramet));
                $("#app").hide();
            });



            $(document).on("mouseenter",".coursetitle", function(e){
                var test = $( this ).children();
                test.css({
                    "background-color": "#3c8dbc",
                    "color": "#FFFFFF",
                    "padding-right": "3px",
                    "padding-left": "3px"
                });
            });
            $(document).on("mouseleave",".coursetitle", function(e){
                var test = $( this ).children();
                test.css({
                    "background-color": "#FFFFFF",
                    "color": "#000000",
                    "padding-right": "0px",
                    "padding-left": "0px"
                });
            });

            stuid = 0;

            $(document).on("click",".eval-popup", function(e){
                stuid = $( this ).attr("stuid");
                $("#app").show();
                console.log("eval-popup");

                var offsetTop =  $(this).offset().top +  $(this).outerHeight() - $( window ).scrollTop(),
                        offsetLeft =   $(this).offset().left;
                dropdownHeight = $(window).height() - offsetTop - 20;
                $("#app").css({
                    'max-height': dropdownHeight,
                    position: 'fixed',
                    top: offsetTop,
                    left: offsetLeft,
                    width: '100px',
                });
                $("#app").css({
                    'max-height': dropdownHeight,
                    'overflow': 'auto',
                    '-webkit-overflow-scrolling': 'touch'
                });


            });




            $(document).on("click",".eval", function(e){
                updatedepence = $( this );

                var evalid = $( this).attr("evalid");
                url = "{!! route('attacheStudentEvalStatuses.api.v1') !!}"
                url += "?stuId="+stuid +"&evalId="+evalid;
                console.log(url);
                $.get(url, function(data){
                    console.log(data);
                    var url = "{!! route('scores.reexam') !!}"+"?fillter=true&fillterdata="+JSON.stringify(paramet);
                    console.log(url);

                    $.get( url , function( html ) {
                        $("#table-content").html(html);

                    });

                });

                $("#app").hide();

            });

            $(document).on("click","#autoeval", function(e){
                //

                $.extend(paramet, {"autoeval":true});
                console.log("in autoeval");
                var url = "{!! route('scores.reexam') !!}"+"?fillter=true&fillterdata="+JSON.stringify(paramet);
                console.log(url);
                $.get( url , function( html ) {
                    $("#table-content").html(html);


                });
            });


            $(document).on("click","#hidecolum", function(e){
                //


                $('#tablescore tr > *:nth-child(6)').toggle();
                $('#tablescore tr > *:nth-child(7)').toggle();

                $('#tablescore tr > *:nth-child(8)').toggle();
                $('#tablescore tr > *:nth-child(9)').toggle();

                $('#tablescore tr > *:nth-child(10)').toggle();
                $('#tablescore tr > *:nth-child(11)').toggle();
                $('#tablescore tr > *:nth-child(12)').toggle();
                $('#tablescore tr > *:nth-child(13)').toggle();

            });






        });















    </script>
@endsection
