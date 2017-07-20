<div class="modal fade " id="modal-default">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">

                    {{$courseAnnual->name_en.'-S'.$courseAnnual->semester_id. ' |~ '.(($courseAnnual->department_id == config('access.departments.sa'))?'SA' :' SF').'-'.(($courseAnnual->degree_id == config('access.degrees.degree_engineer'))?'I':'T').$courseAnnual->grade_id}}

                </h4>
            </div>
            <div class="modal-body">

                {!! Form::open(['route' => ['course_annual.competency_score.import', $courseAnnual->id],'id' => 'import_course_annual_score', 'role'=>'form','files' => true])!!}
                <div class="box box-success no-padding">

                    <div class="box-body ">

                        <div class="col-sm-12 no-padding">

                            <div class="col-sm-2  space_">

                                <select name="filter_academic_year" id="filter_academic_year" class="form-control">
                                    @foreach($academicYears as $key=>$year)
                                        <option value="{{$key}}"> {{$year}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-1  space_">

                                <select name="filter_dept" id="filter_dept" class="form-control">
                                    @foreach($departments as $key=>$departmentName)
                                        <option value="{{$key}}"> {{$departmentName}}</option>
                                    @endforeach
                                </select>

                            </div>


                            <div class="col-sm-2  space_">

                                <select name="dept_option" id="filter_dept_option" class="left-margin form-control col-sm-2">
                                    <option value="">Option</option>
                                    @foreach($departmentOptions as $option)
                                        <option value="{{$option->id}}"
                                                class="dept_option department_{{$option->department_id}}">{{$option->code}}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-sm-2  space_">

                                <select name="degree" id="filter_degree" class="left-margin form-control  col-sm-2">
                                    @foreach($degrees as $key=>$degreeName)
                                        <option value="{{$key}}"> {{$degreeName}}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-sm-2 space_">

                                <select name="semester" id="filter_semester"  class="left-margin form-control col-sm-2">
                                    @foreach($semesters as $key=>$semester)
                                        <option value="{{$key}}"> {{$semester}}</option>
                                    @endforeach
                                    <option value=""> Semesters</option>
                                </select>

                            </div>

                            <div class="col-sm-2  space_">

                                <select name="grade" id="filter_grade" class="left-margin form-control col-sm-2" style="margin-bottom: 5px">
                                    @foreach($grades as $key=>$gradeName)

                                        @if($department_id = \App\Models\Enum\ScoreEnum::Dept_TC)

                                            @if($key == \App\Models\Enum\ScoreEnum::Year_1)

                                                <option id="{{$key}}" value="{{$key}}" selected> {{$gradeName}}</option>
                                            @else
                                                <option id="{{$key}}" value="{{$key}}"> {{$gradeName}}</option>
                                            @endif

                                        @else
                                            @if($key == \App\Models\Enum\ScoreEnum::Year_3)

                                                <option id="{{$key}}" value="{{$key}}" selected> {{$gradeName}}</option>
                                            @else
                                                <option id=" {{$key}}" value="{{$key}}"> {{$gradeName}}</option>
                                            @endif

                                        @endif

                                    @endforeach
                                </select>

                            </div>

                            <div class="col-sm-1  space_">

                                <select name="group" id="filter_group" class="left-margin form-control " style="margin-bottom: 5px">
                                    <option value="sdf">G1</option>
                                    <option value="sdf">G2</option>
                                </select>

                            </div>

                        </div>

                        <div class="col-sm-12 blog_course">

                            @include('backend.course.courseAnnual.includes.partials.blog_course')
                        </div>

                    </div><!-- /.box-body -->
                </div><!--box-->

                <div class="box box-success">
                    <div class="box-body">
                        <div class="pull-left">
                            {{--<button class="btn btn-danger btn-xs" id="cancel_import">cancel</button>--}}
                        </div>

                        <div class="pull-right">
                            <input type="submit" class="btn btn-success btn-xs" id = "publish_score" value="{{ 'Publish' }}"/>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.box-body -->
                </div><!--box-->
                {!! Form::close() !!}

            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>