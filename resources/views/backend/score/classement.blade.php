@extends ('backend.layouts.master')


@section('page-header')
    <h1>
        {{ trans('labels.backend.score.title') }}
        <small>{{ trans('labels.backend.score.sub_input_title') }}</small>
    </h1>

@stop






@section('content')
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
                            <button id="autoeval" class="btn btn-default btn-sm"> Generate AutoEvaluation </button>
                            <button id="testshort" class="btn btn-default btn-sm">testshort</button>
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
                        <br>
                        <div id="selectacademic">
                        </div>
                        <div id="selectsemester">
                        </div>

                        <div id="groupselectorcontainer">
                        </div>
                    </div>
                    <div id="table-content">
                        @include('backend.score.classement_table')
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    <div class="pull-right" style="padding-right: 15px;">
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
    <scritp  type="text/x-template"  id="app">
        <i id="closeeval" class="glyphicon glyphicon-remove"></i>
        <ul id="uleval">
            <li  class="eval" v-for="studentEvalStatus in studentEvalStatuses" evalid="@{{studentEvalStatus.id}}">
                @{{ studentEvalStatus.name }}
            </li>
        </ul>

    </scritp>
</section><!-- /.content -->
@endsection

@section('after-styles-end')
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

        #vue_table_score td, th, table{
            border:1px solid black;
            padding-left: 2px;
            padding-right: 2px;
        }
        #vue_table_score table{
            padding-left: 2px;
            padding-right: 2px;
        }
        #vue_table_score th{
            border:1px solid black;
        }
        td {
            padding:0px;
            margin:0px;
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

        }
        td.coursescore[highlight="red"]{
            background-color:#ff4646;
        }
        td.coursescore[highlight="yellow"]{
            background-color:#ffe700;
        }
        td.classementhighlight[highlight="red"]{
            background-color:#ff4688;
        }
        td.classementhighlight[highlight="yellow"]{
            background-color:#ffe722;
        }

        #closeeval{
            float: right;
            padding:5px;
        }
        #app{

            border:1px solid #3c8dbc;
            padding: 0px;
            margin: 0px;
            box-shadow: -10px 14px 35px 15px rgba(0,0,0,0.57);
        }
        .eval{
            border-top: 3px solid #3c8dbc;
            max-width: 100px;
            padding: 5px;
            background-color: white;


        }
        .eval:hover{

            background-color: #3c8dbc;
            color:white;


        }
        #uleval{
            list-style-type: none;
            padding: 0px;
            margin: 0px;


        }

        .short_column[toggleremove="0"]{
            background-color: red;
        }

        .short_column[toggleremove="1"]{

        }



    </style>
@stop

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
        $("#app").hide();
        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i=0; i<ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1);
                if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
            }
            return "";
        }
        paramet = {};
        updatedepence = null;

        //--------------------------------------
        // Set Slide Bar Navagation Hide | Show
        //-------------------------------------
        var cookieValue = document.cookie.replace(/(?:(?:^|.*;\s*)sidebar-collapse\s*\=\s*([^;]*).*$)|^.*$/, "$1");
        if (cookieValue=="0"){
            $("body").removeClass("sidebar-collapse");
        }else{
            $("body").addClass("sidebar-collapse");
        }
        //--------------------------------------



        $( document ).ready(function() {


//            tofix move to 5.2
            {{--$.get( "{!! route('studentEvalStatusesr.api.v1') !!}", function(studentEvalStatuses){--}}
                {{--console.log("type of returne status");--}}
                {{--console.log(typeof studentEvalStatuses);--}}
                {{--new Vue({--}}
                    {{--el: '#app',--}}
                    {{--data: {--}}
                        {{--studentEvalStatuses: studentEvalStatuses--}}
                    {{--}--}}
                {{--})--}}
            {{--});--}}

            function callbackCourseAnnual(data){
                $.extend(paramet, data);
                var count = 0;
                for (k in paramet) if (paramet.hasOwnProperty(k)) count++;
                console.log(count);
                if(count == 4){
                    toggleLoading(true);
                    var url = "{!! route('score.ranking') !!}"+"?fillter=true&fillterdata="+JSON.stringify(paramet);
                    $.get( url , function( html ) {
                        $("#table-content").html(html);
                        toggleLoading(false);

                    });
                }
            };
            function callbackCourseAnnual2(data){
                console.log("in callback semester");
                $.extend(paramet, data);
                var count = 0;
                for (k in paramet) if (paramet.hasOwnProperty(k)) count++;
                console.log(count);
                if(count == 5){
                    toggleLoading(true);
                    var url = "{!! route('score.ranking') !!}"+"?fillter=true&fillterdata="+JSON.stringify(paramet);
                    $.get( url , function( html ) {
                        $("#table-content").html(html);
                        toggleLoading(false);
                    });
                }

            };

            function callbackSelecGroup(data){
                $.extend(paramet, data);
                var url = "{!! route('score.ranking') !!}"+"?fillter=true&fillterdata="+JSON.stringify(paramet);
                toggleLoading(true);
                $.get( url , function( html ) {
                    $("#table-content").html(html);
                    toggleLoading(false);
                });
            };


            var url = ["{!! route('degrees.api.v1') !!}","{!! route('grades.api.v1') !!}","{!! route('departments.api.v1') !!}"];
            SMSFILER.config(url,callbackSelecGroup);

            {{--$(document).on("click","#evaluation",function(e){--}}
                {{--var url = "{!! route('scoreeval.api.v1') !!}"+"?filter="+JSON.stringify(paramet);--}}
                {{--toggleLoading(true);--}}
                {{--console.log(url);--}}
                {{--$.get( url , function( html ) {--}}

                    {{--toggleLoading(false);--}}
                    {{--alert("finish");--}}
                {{--});--}}

            {{--});--}}

            var urlsemester = ["{!! route('semesters.api.v1') !!}"];
            console.log(urlsemester);
            var urlacademic = ["{!! route('academicYears.api.v1') !!}"];
            function callbackSemester(data){
                console.log("in Semester callback");
                $.extend(paramet, data);


                var url = "{!! route('score.ranking') !!}"+"?fillter=true&fillterdata="+JSON.stringify(paramet);


                var count = 0;
                $.each(paramet, function(key,value){

                    count = count +1;
                });
                if(count > 3){
                    toggleLoading(true);

                    console.log("in if"+count);
                    $.get( url , function( html ) {
                        $("#table-content").html(html);
                        toggleLoading(false);
                    });
                }
                console.log("count2:"+count);
            };

            var filter1 = new SMSFILERLONGo(urlsemester,callbackSemester,"#selectsemester");

            function callbackAcademic(data){
                console.log("in Academic callback");
                $.extend(paramet, data);


                var url = "{!! route('score.ranking') !!}"+"?fillter=true&fillterdata="+JSON.stringify(paramet);

                console.log(typeof(paramet));
                var count = 0;
                $.each(paramet, function(key,value){
                    console.log("count:"+count);
                    count = count +1;
                });
                if(count > 3){
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

            stuId = 0;
            $(document).on("click",".eval-popup", function(e){
                stuId = $( this ).attr("studentId");

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
                alert("hello");
                updatedepence = $( this );

                var evalId = $( this).attr("evalid");




{{--                url = "{!! route('attacheStudentEvalStatuses.api.v1') !!}"--}}
                url += "?stuId="+stuId +"&evalId="+evalId;
                console.log(url);
                toggleLoading(true);
                $.get(url, function(data){
                    console.log(data);
                    var url = "{!! route('score.ranking') !!}"+"?fillter=true&fillterdata="+JSON.stringify(paramet);
                    console.log(url);

                    $.get( url , function( html ) {
                        $("#table-content").html(html);
                        toggleLoading(false);

                    });

                });

                $("#app").hide();

            });

            //--------------------------------------
            // Evaluation
            //-------------------------------------
            $(document).on("click","#autoeval", function(e){
                $.extend(paramet, {"autoeval":true});
                console.log("in autoeval");
                var url = "{!! route('score.ranking') !!}"+"?fillter=true&fillterdata="+JSON.stringify(paramet);
                console.log(url);

                $.get( url , function( html ) {
                    $("#table-content").html(html);


                });
            });
            $(document).on("click","#closeeval", function(e){
                $("#app").hide();
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

            //--------------------------------------
            // Set Slide Bar Navagation Hide | Show
            //-------------------------------------
            $(document).on("click",".sidebar-toggle",function(e){
                var cookieValue = document.cookie.replace(/(?:(?:^|.*;\s*)sidebar-collapse\s*\=\s*([^;]*).*$)|^.*$/, "$1");
                if (cookieValue=="0"){
                    document.cookie="sidebar-collapse=1";
                }else{
                    document.cookie="sidebar-collapse=0";
                }
            });
            //--------------------------------------

            //--------------------------------------
            // Vue js table
            //-------------------------------------
        });
    </script>

@endsection
