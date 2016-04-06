@extends ('backend.layouts.master')


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
                            <button id=btn-cademicyear class="btn btn-default btn-sm" toggle="off"> Academic year </button>

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
                        <div id="selectsemester">
                        </div>

                        <div id="groupselectorcontainer">
                        </div>



                        <!-- /.pull-right -->
                    </div>
                    <div id="table11">
                        @include('backend.score.absence.tableByGroup')
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


    <script>
        paramet = {"academic_year_id":2015};
        console.log("hello from the skype");
        $( document ).ready(function() {

            function callbackCourseAnnual(data){
                $.extend(paramet,data);

                var url = "{!! route('absences.indexByGroup') !!}"+"?filter="+JSON.stringify(paramet);
                console.log(url);
                $.get( url , function( data2 ) {

                    $("#table11").html(data2);

                });
            };

            function callbackSelecGroup(data){
                $.extend(paramet, data);
                {{--var urltmp = "{!! route('courseAnnuals') !!}"+"?filter="+JSON.stringify(paramet);--}}
                var urltmp = "{!! route('api.v1.courseAnnuals') !!}"+"?filter="+JSON.stringify(paramet);
                var url2 = [urltmp,];

                SMSFILERLONG.config(url2,callbackCourseAnnual);
            };

            var url = ["{!! route('degrees.api.v1') !!}", "{!! route('grades.api.v1') !!}","{!! route('departments.api.v1') !!}"];
            SMSFILER.config(url,callbackSelecGroup);

            $(document).on("click",".linkeditmany", function(e){
                var url = $(this).attr("href");

                $(this).attr("href",url+"?redirect=1&filter="+JSON.stringify(paramet));
            });

            var urlsemester = ["{!! route('semesters.api.v1') !!}"];
            var urlacademic = ["{!! route('academicYears.api.v1') !!}", ];
            var filter1 = new SMSFILERLONGo(urlsemester,callbackCourseAnnual,"#selectsemester");

            var filter2 = new SMSFILERLONGo(urlacademic,callbackCourseAnnual,"#selectacademic");

        });
        function callbackAcademicYear(data){
            alert("in callback")
        }

        // override result.
        $(document).on("click","#btn-cademicyear",function(e){
            var toggle = $(this).attr("toggle");
            if (toggle == "off"){
                $(this).attr("toggle", "on");
            }else if(toggle == "on"){
                $(this).attr("toggle", "off");
            }

            var urltmp = "http://localhost:8000/api/v1/academicYears";
            var url2 = [urltmp,];
            SMSFILERLONG.config(url2,callbackAcademicYear,"#groupselectorcontainer");

        });
    </script>
@endsection