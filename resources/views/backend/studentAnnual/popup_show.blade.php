@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.students.title') . ' | ' . "General Information")

@section('page-header')
    <h1>
        {{ trans('labels.backend.candidates.title') }}
        <small>{{ trans('labels.backend.candidates.sub_create_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
    <style>
        .profile-user-img {
            margin: 0 auto;
            width: 100px;
            padding: 3px;
            border: 3px solid #d2d6de;
        }
    </style>
@stop

@section('content')

    <section class="content">

        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive" src="{{config('app.smis_server')}}/img/profiles/{{$student->photo}}" alt="User profile picture">

                        <h3 class="profile-username text-center">{{$student->name_kh}}</h3>

                        <p class="text-muted text-center">{{$student->id_card}}</p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>{{trans('labels.backend.students.fields.name_latin')}}</b> <a class="pull-right" style="font-size: smaller">{{$student->name_latin}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{trans('labels.backend.students.fields.gender_id')}}</b> <a class="pull-right">{{$student->gender->name_kh}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{trans('labels.backend.students.fields.dob')}}</b>
                                <a class="pull-right">
                                    <?php
                                    $dob = \Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $student->dob);
                                    ?>
                                    {{$dob->format('d/m/Y')}}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>{{trans('labels.backend.students.fields.origin_id')}}</b>
                                <a class="pull-right">
                                    {{$student->origin->name_kh}}
                                </a>
                            </li>
                        </ul>

                        <a href="#" class="btn btn-primary btn-block"><b>Export</b></a>
                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans('labels.backend.students.tabs.contact_information') }}</h3>
                    </div>
                    <div class="box-body">
                        <strong><i class="fa fa-phone margin-r-5"></i> {{trans('labels.backend.students.fields.phone')}}</strong>

                        <p class="text-muted">
                            {{$student->phone}}
                        </p>

                        <hr>
                        <strong><i class="fa fa-envelope margin-r-5"></i> {{trans('labels.backend.students.fields.email')}}</strong>

                        <p class="text-muted">
                            {{$student->email}}
                        </p>

                        <hr>

                        <strong><i class="fa fa-map-marker margin-r-5"></i> {{trans('labels.backend.students.fields.current_address')}}</strong>

                        <p class="text-muted">{{$student->address_current}}</p>

                        <hr>

                        <strong><i class="fa fa-map margin-r-5"></i> {{trans('labels.backend.students.fields.permanent_address')}}</strong>

                        <p>{{$student->address}}</p>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#general_info" data-toggle="tab" aria-expanded="true">
                                {{ trans('labels.backend.students.tabs.general_information') }}
                            </a>
                        </li>
                        @foreach($student->studentAnnuals as $studentAnnual)
                            <li class="">
                                <a href="#academic_{{$studentAnnual->academic_year->id}}" data-toggle="tab" aria-expanded="false">{{ $studentAnnual->academic_year->name_kh }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="general_info">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="box box-info">

                                        <div class="box-body">
                                            <strong><i class="fa fa-book margin-r-5"></i> {{trans('labels.backend.students.fields.parent_name')}}</strong>

                                            <p class="text-muted">
                                                {{$student->parent_name==""?"NA":$student->parent_name}}
                                            </p>

                                            <hr>

                                            <strong><i class="fa fa-pencil margin-r-5"></i> {{trans('labels.backend.students.fields.parent_occupation')}}</strong>

                                            <p class="text-muted">{{$student->parent_occupation==""?"NA":$student->parent_occupation}}</p>

                                            <hr>

                                            <strong><i class="fa fa-map-marker margin-r-5"></i> {{trans('labels.backend.students.fields.parent_address')}}</strong>

                                            <p class="text-muted">{{$student->parent_address==""?"NA":$student->parent_address}}</p>

                                            <hr>

                                            <strong><i class="fa fa-phone-square margin-r-5"></i> {{trans('labels.backend.students.fields.parent_phone')}}</strong>

                                            <p>{{$student->parent_phone==""?"NA":$student->parent_phone}}</p>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="box box-info">
                                        <div class="box-body">
                                            <strong>{{trans('labels.backend.students.fields.redouble_id')}}</strong>

                                            <p>
                                                @foreach($student->redoubles as $redouble)
                                                    <span class="label label-danger">{{$redouble->name_en}}</span>
                                                @endforeach
                                            </p>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h3 style="font-size: 20px;"><i class="fa fa-forumbee"></i> {{ trans('labels.backend.students.tabs.high_school_information') }}</h3>
                            <hr/>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <span class="show_label col-sm-3">{{trans('labels.backend.students.fields.highschool_id')}}</span>
                                        <span class="show_value col-sm-9">{{$student->high_school_id}} &nbsp;</span>
                                    </div>
                                    <div class="row">
                                        <span class="show_label col-sm-3">{{trans('labels.backend.students.fields.mcs_no')}}</span>
                                        <span class="show_value col-sm-9">{{$student->mcs_no}} &nbsp;</span>
                                    </div>
                                    <div class="row">
                                        <span class="show_label col-sm-3">{{trans('labels.backend.students.fields.can_id')}}</span>
                                        <span class="show_value col-sm-9">{{$student->can_id}} &nbsp;</span>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <?php
                        $studentAnnuals = $student->studentAnnuals->sortByDesc('academic_year_id');
                        ?>

                        @foreach($studentAnnuals as $key => $studentAnnual)
                            <div class="tab-pane" id="academic_{{$studentAnnual->academic_year->id}}">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h3 style="font-size: 20px;"><i class="fa fa-info-circle"></i> General Information</h3>
                                        <hr/>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row no-margin no-padding">
                                            <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.academic_year_id')}}</span>
                                            <span class="show_value col-sm-8">{{$studentAnnual->academic_year->name_kh}} &nbsp;</span>
                                        </div>
                                        <div class="row no-margin no-padding">
                                            <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.promotion_id')}}</span>
                                            <span class="show_value col-sm-8">{{$studentAnnual->promotion->name}} &nbsp;</span>
                                        </div>
                                        <div class="row no-margin no-padding">
                                            <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.degree_id')}}</span>
                                            <span class="show_value col-sm-8">{{$studentAnnual->degree->name_kh}} &nbsp;</span>
                                        </div>
                                        <div class="row no-margin no-padding">
                                            <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.grade_id')}}</span>
                                            <span class="show_value col-sm-8">{{$studentAnnual->grade->name_kh}} &nbsp;</span>
                                        </div>

                                        <div class="row no-margin no-padding">
                                            <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.department_id')}}</span>
                                            <span class="show_value col-sm-8">{{$studentAnnual->department->name_kh}} &nbsp;</span>
                                        </div>

                                        <div class="row no-margin no-padding">
                                            <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.department_option_id')}}</span>
                                            <span class="show_value col-sm-8">{{isset($studentAnnual->department_option)?$studentAnnual->department_option->name_kh:""}} &nbsp;</span>
                                        </div>

                                        <div class="row no-margin no-padding">
                                            <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.group')}}</span>
                                            <span class="show_value col-sm-8">{{$studentAnnual->group}} &nbsp;</span>
                                        </div>

                                        <div class="row no-margin no-padding">
                                            <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.history_id')}}</span>
                                            <span class="show_value col-sm-8">{{$studentAnnual->history_id}} &nbsp;</span>
                                        </div>

                                    </div>

                                    <?php $a = 0; ?>
                                    <div class="col-sm-6">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th colspan="4">{{trans('labels.backend.students.fields.scholarship_id')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(empty($studentAnnual->scholarships->toArray()))
                                                <tr>
                                                    <td colspan="4" style="text-align: center">
                                                        <h4>Empty</h4>
                                                    </td>
                                                </tr>
                                            @else
                                                @foreach($studentAnnual->scholarships as $scholarship)
                                                    <tr style="color:#15c">
                                                        <td>{{$a}}</td>
                                                        <td>{{$scholarship->code}}</td>
                                                        <td>{{$scholarship->founder}}</td>
                                                        <td>{{$scholarship->duration}}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-sm-12">
                                        <h3 style="font-size: 20px;"><i class="fa fa-info-circle"></i> Course / Score</h3>
                                        <hr/>
                                    </div>
                                    <div class="col-sm-12">
                                        <table class="table table-striped">
                                            <tbody>
                                            <tr>
                                                <th colspan="4">Semester 1</th>
                                                <th style="width: 60px"><button data-semester="1" data-student_id="{{$studentAnnual->id}}" class="btn btn-xs print_transcript_semester_1">Print Transcript</button></th>
                                            </tr>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th>Course Title</th>
                                                <th>Credit</th>
                                                <th>#Absence</th>
                                                <th style="width: 60px">Score</th>
                                            </tr>

                                            <?php $index = 1; ?>
                                            @foreach($scores[$studentAnnual->id] as $key => $score)
                                                @if(is_numeric($key) && $score['semester'] == 1)
                                                    <tr>
                                                        <td>{{$index}}.</td>
                                                        <td>{{isset($score['name_en'])?$score['name_en']:""}}</td>
                                                        <td>{{$score['credit']}}</td>
                                                        <td>{{$score['absence']}}</td>
                                                        <td>
                                                            <?php

                                                                if($score['score']==null){
                                                                    $s = 0;
                                                                }  else {
                                                                    $s = $score['score'];
                                                                }

                                                                if ($s < 30){
                                                                    $score_color = "style=color:red";
                                                                } else if($s<50){
                                                                    $score_color = "style=color:orange";
                                                                } else {
                                                                    $score_color = "style=color:black";
                                                                }

                                                            ?>
                                                            <span {{$score_color}}>{{$score['score']==null?0:$score['score']}}</span>
                                                        </td>
                                                    </tr>
                                                    <?php $index++;?>
                                                @endif
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                            </div>
                            <?php $a++; ?>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

@stop
@section('after-scripts-end')
    <script>
        var avatar = "{{url('img/profiles/avatar.png')}}";
        $(document).ready(function(){
            $('.profiles-user-img').error(function(){
                $(this).attr('src', avatar);
            });

            $(".print_transcript_semester_1").on("click",function(){
                var url = "{{ route('admin.student.print_transcript') }}";

                PopupCenterDual(
                        url
                        + '?student_annual_id='+$(this).data('student_id')
                        + '&semester='+$(this).data('semester'),
                        'Print | Transcript','900','900');
            });
        });
    </script>
@stop

