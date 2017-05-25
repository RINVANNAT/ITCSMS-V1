<?php
    if(isset($courseAnnual)){
        $disabled = "disabled";
    } else {
        $disabled = "";
    }
?>
<div class="form-group">
    {!! Form::label('course', trans('labels.backend.courseAnnuals.fields.course'), ['class' => 'col-lg-3 control-label required']) !!}
    <div class="col-lg-4">
        <select
                name="course_id"
                id="course_id"
                class="form-control"
                {{$disabled}}
                required
        >
            @foreach($courses as $key => $group)
                <option></option>
                <optgroup label="{{$key}}">
                    @foreach($group as $course)
                        <?php
                            if(isset($courseAnnual) && $course->id == $courseAnnual->course_id){
                                $selected = "selected";
                            } else {
                                $selected = "";
                            }
                        ?>
                        <option {{$selected}} value="{{$course->id}}"
                                time_course="{{$course->time_course}}"
                                time_tp="{{$course->time_tp}}"
                                time_td="{{$course->time_td}}"
                                name_kh="{{$course->name_kh}}"
                                name_en="{{$course->name_en}}"
                                name_fr="{{$course->name_fr}}"
                                credit="{{$course->credit}}"
                                dept="{{$course->department_id}}"
                                grade="{{$course->grade_id}}"
                                degree="{{$course->degree_id}}"
                                dept_option="{{$course->department_option_id}}"
                                semester="{{$course->semester_id}}"
                                responsible_department_id="{{$course->responsible_department_id}}">
                            {{$course->degree_code.$course->grade_id.$course->option."-S".$course->semester_id." | ".$course->name_en}}
                        </option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
    </div>
</div>


<div class="form-group">
    {!! Form::label('course', trans('labels.backend.courseAnnuals.fields.employee'), ['class' => 'col-lg-3 control-label']) !!}
    <div class="col-lg-4">
        {{--{{ Form::select('employee_id', $employees, null, ['class' => 'form-control', 'id'=>'lecturer_lists', 'placeholder' => 'Lecturer', 'required'=> 'required']) }}--}}
        {!! Form::select('employee',[],null,['id'=>'employee','class'=>"select_employee form-control",'style'=>'width:100%;']) !!}
        {{ Form::hidden('employee_id', null, ['class' => 'form-control', 'id'=>'lecturer_lists']) }}
    </div>
</div>

<div class="form-group">
    {!! Form::label('semester', trans('labels.backend.courseAnnuals.fields.academic_year'), ['class' => 'col-lg-3 control-label required']) !!}
    <div class="col-lg-3">
        {{ Form::select('academic_year_id', $academicYears, null, ['class' => 'form-control','id'=>'academic_year_id', 'placeholder' => 'Academic year', 'required' => 'required',$disabled]) }}
    </div>
</div>

<div class="form-group">
    {!! Form::label('semester', trans('labels.backend.courseAnnuals.fields.semester'), ['class' => 'col-lg-3 control-label required']) !!}
    <div class="col-lg-5">
        {{ Form::select('semester_id', $semesters, null, ['class' => 'form-control', 'id' => 'semester_id', 'placeholder' => 'Semester', 'required' => 'required']) }}
    </div>
</div>


<div class="form-group">
    {!! Form::label('degree', trans('labels.backend.courseAnnuals.fields.degree'), ['class' => 'col-lg-3 control-label required']) !!}
    <div class="col-lg-5">
        {{ Form::select('degree_id', $degrees, isset($courseAnnual->courseAnnualClass[0])?$courseAnnual->courseAnnualClass[0]->degree_id:null, ['class' => 'form-control', 'id' => 'degree_id','required'=>'required', 'placeholder' => 'Degree',$disabled]) }}
    </div>
</div>

<div class="form-group">
    {!! Form::label('grades', trans('labels.backend.courseAnnuals.fields.grade'), ['class' => 'col-lg-3 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('grade_id', $grades, isset($courseAnnual->courseAnnualClass[0])?$courseAnnual->courseAnnualClass[0]->grade_id:null, ['class' => 'form-control', 'id' => 'grade_id','required'=>'required', 'placeholder' => 'Grade',$disabled]) }}
    </div>
</div>

<div class="form-group">
    {!! Form::label('departments', trans('labels.backend.courseAnnuals.fields.department'), ['class' => 'col-lg-3 control-label required']) !!}
    <div class="col-lg-3">
        {{ Form::select('department_id', $departments, null, ['class' => 'form-control', 'id' => 'department_id','required'=>'required',$disabled]) }}
    </div>
    {!! Form::label('department_option_id', trans('labels.backend.courseAnnuals.fields.department_option_id'), ['class' => 'col-lg-1 control-label']) !!}
    <div class="col-lg-3">
        {{--{{ Form::select('department_option_id', $options, null, ['class' => 'form-control']) }}--}}
        <select class="form-control" id="department_option_id" name="department_option_id">
            @if(isset($courseAnnual))
                <option value=""></option>
                @foreach($options as $option)
                    <?php
                        $selected = "";
                        if($option->id == $courseAnnual->department_option_id){
                            $selected = "selected";
                        }
                    ?>
                    <option {{$selected}} value="{{$option->id}}" class="department_option department_{{$option->department_id}}" style="display: none">{{$option->code}}</option>
                @endforeach
            @else
                <option value="" selected></option>
                @foreach($options as $option)
                    <option value="{{$option->id}}" class="department_option department_{{$option->department_id}}" style="display: none">{{$option->code}}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>

<div class="form-group">

    <div class="col-lg-3">
        <label for="group" style="float:right; font-size: 12pt" class="btn btn-xs control-label"> <input id="group" type="checkbox" class="check_all_box"> {{ trans('labels.backend.courseAnnuals.fields.group')}}</label>
    </div>

{{--    {!! Form::label('group','',  ['class' => 'col-lg-3 control-label']) !!}--}}
    <div class="col-lg-2 no-padding" id="group_panel">

        @if(isset($groups))

                @foreach($groups as $group)
                    <?php $index =0;?>

                    @if($group != null)

                        <?php $status =true;?>
                        @foreach($courseAnnual->courseAnnualClass as $class)
                            @if(trim($group->group_id) == trim($class->group_id))
                                <?php $status =false;?>
                                <label class="btn btn-xs" style="font-size: 12pt" for="{{$class->group_id}}"> <input id="{{$class->group_id}}" type="checkbox" name="groups[]" class="each_check_box" value="{{$class->group_id}}" checked> {{$group->group_code}}</label>
                            @endif
                        @endforeach
                        @if($status == true)
                            <label style="font-size: 12pt" class="btn btn-xs" for="{{$group->group_id}}"> <input id="{{$group->group_id}}" type="checkbox" name="groups[]" class=" each_check_box" value="{{$group->group_id}}"> {{$group->group_code}}</label>
                        @endif

                    @endif

                @endforeach
        @endif

    </div>

      @if(isset($courseAnnual))
          @if($courseAnnual->is_having_resitted)
            <label for="having_resitted" class="col-lg-2 control-label required"> Allow Resit <input id="having_resitted" type="checkbox" name="is_having_resitted"  value="{{\App\Models\Enum\ScoreEnum::is_having_resitted}}" checked>  </label>
          @else
            <label for="having_resitted" class="col-lg-2 control-label required"> Allow Resit <input id="having_resitted" type="checkbox" name="is_having_resitted"  value="{{\App\Models\Enum\ScoreEnum::is_having_resitted}}">  </label>
          @endif

      @else
        <label for="having_resitted" class="col-lg-2 control-label required"> Allow Resit <input id="having_resitted" type="checkbox" name="is_having_resitted"  value="{{\App\Models\Enum\ScoreEnum::is_having_resitted}}">  </label>
      @endif


</div>

<div class="form-group">
    {!! Form::label('credit', 'Credit', ['class' => 'col-lg-3 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::text('credit',  isset($courseAnnual)?$courseAnnual->credit:null, ['class' => 'form-control' , 'id'=> 'credit', 'required' => 'required']) }}
    </div>
</div>


<div class="form-group">

    {!! Form::label('count_creditability', "Creditability For Transcript", ['class' => 'col-lg-3 control-label required']) !!}
    <div class="col-lg-2">
        @if(isset($courseAnnual))
            @if($courseAnnual->is_counted_creditability)
                <input type="checkbox" name="is_counted_creditability" id="count_credit" class="boolean_input" value="{{\App\Models\Enum\ScoreEnum::is_counted_creditability}}" checked>
            @else
                <input type="checkbox" name="is_counted_creditability" id="count_credit" class="boolean_input" value="{{\App\Models\Enum\ScoreEnum::is_counted_creditability}}" >
            @endif
        @else
            <input type="checkbox" name="is_counted_creditability" id="count_credit" class="boolean_input" value="{{\App\Models\Enum\ScoreEnum::is_counted_creditability}}" checked>
        @endif

    </div>

    {!! Form::label('count_absence', " 10%-Absence", ['class' => 'col-lg-2 control-label required']) !!}

    <div class="col-lg-3">
        @if(isset($courseAnnual))
            @if($courseAnnual->is_counted_absence)
                <input type="checkbox" name="is_counted_absence" id="count_absence" class="boolean_input" value="{{\App\Models\Enum\ScoreEnum::is_counted_absence}}" checked>
            @else
                <input type="checkbox" name="is_counted_absence" id="count_absence" class="boolean_input" value="{{\App\Models\Enum\ScoreEnum::is_counted_absence}}">
            @endif
        @else
            <input type="checkbox" name="is_counted_absence" id="count_absence" class="boolean_input" value="{{\App\Models\Enum\ScoreEnum::is_counted_absence}}" checked>
        @endif

    </div>

</div>

@if(isset($midterm) || isset($final))
    @if(isset($midterm))
        <div class="form-group">
            {!! Form::label('midterm_score', "Midterm Score", ['class' => 'col-lg-3 control-label required']) !!}
            <div class="col-lg-2">
                <select name="midterm_score" id="midterm_score_id" class="form-control">

                    @if($midterm['percentage'] == \App\Models\Enum\ScoreEnum::Midterm_30)
                        <option value="{{\App\Models\Enum\ScoreEnum::Midterm_0}}" > 0% </option>
                        <option value="{{\App\Models\Enum\ScoreEnum::Midterm_30}}" selected > 30% </option>
                        <option value="{{\App\Models\Enum\ScoreEnum::Midterm_40}}"> 40%</option>

                        <input type="hidden" name="midterm_percentage_id" value="{{$midterm['percentage_id']}}">
                        <input type="hidden" name="final_percentage_id" value="{{$final['percentage_id']}}">



                    @elseif($midterm['percentage'] == \App\Models\Enum\ScoreEnum::Midterm_0)

                        <option value="{{\App\Models\Enum\ScoreEnum::Midterm_0}}" selected> 0% </option>
                        <option value="{{\App\Models\Enum\ScoreEnum::Midterm_30}}"> 30% </option>
                        <option value="{{\App\Models\Enum\ScoreEnum::Midterm_40}}"> 40% </option>

                        <input type="hidden" name="midterm_percentage_id" value="{{$midterm['percentage_id']}}">
                        <input type="hidden" name="final_percentage_id" value="{{$final['percentage_id']}}">

                    @else

                        <option value="{{\App\Models\Enum\ScoreEnum::Midterm_0}}"> 0% </option>
                        <option value="{{\App\Models\Enum\ScoreEnum::Midterm_30}}"> 30% </option>
                        <option value="{{\App\Models\Enum\ScoreEnum::Midterm_40}}" selected> 40% </option>

                        <input type="hidden" name="midterm_percentage_id" value="{{$midterm['percentage_id']}}">
                        <input type="hidden" name="final_percentage_id" value="{{$final['percentage_id']}}">

                    @endif
                </select>
            </div>

            {!! Form::label('final_score', "Final Score", ['class' => 'col-lg-2 control-label required']) !!}

            <div class="col-lg-3">
                {{ Form::text('final_score',  null, ['class' => 'form-control' , 'id'=> 'final_score_id', 'required' => 'required', 'readonly']) }}
            </div>
        </div>

    @else

        <div class="form-group">
            {!! Form::label('midterm_score', "Midterm Score", ['class' => 'col-lg-3 control-label required']) !!}
            <div class="col-lg-2">
                <select name="midterm_score" id="midterm_score_id" class="form-control">

                    <option value="{{\App\Models\Enum\ScoreEnum::Midterm_0}}" selected> 0% </option>
                    <option value="{{\App\Models\Enum\ScoreEnum::Midterm_30}}"> 30% </option>
                    <option value="{{\App\Models\Enum\ScoreEnum::Midterm_40}}"> 40% </option>

                    <input type="hidden" name="final_percentage_id" value="{{$final['percentage_id']}}">

                </select>
            </div>

            {!! Form::label('final_score', "Final Score", ['class' => 'col-lg-2 control-label required']) !!}

            <div class="col-lg-3">
                {{ Form::text('final_score',  null, ['class' => 'form-control' , 'id'=> 'final_score_id', 'required' => 'required', 'readonly']) }}
            </div>
        </div>



    @endif


@else
    <div class="form-group">
        {!! Form::label('midterm_score', "Midterm Score", ['class' => 'col-lg-3 control-label required']) !!}
        <div class="col-lg-2">
            <select name="midterm_score" id="midterm_score_id" class="form-control">
                <option value="{{\App\Models\Enum\ScoreEnum::Midterm_0}}">  0% </option>
                <option value="{{\App\Models\Enum\ScoreEnum::Midterm_30}}"> 30% </option>
                <option value="{{\App\Models\Enum\ScoreEnum::Midterm_40}}"> 40%</option>
            </select>
        </div>

        {!! Form::label('final_score', "Final Score", ['class' => 'col-lg-2 control-label required']) !!}

        <div class="col-lg-3">
            {{ Form::text('final_score',  null, ['class' => 'form-control' , 'id'=> 'final_score_id', 'required' => 'required', 'readonly']) }}
        </div>
    </div>
@endif





<div class="form-group">
    {!! Form::label('time_course', "Time Course", ['class' => 'col-lg-3 control-label required']) !!}
    <div class="col-lg-2">
        {{ Form::number('time_course',  null, ['class' => 'form-control', 'id'=>'time_course', 'required' => 'required']) }}
    </div>

    {!! Form::label('name_kh', "Name Khmer", ['class' => 'col-lg-2 control-label']) !!}

    <div class="col-lg-3">
        {{ Form::text('name_kh',  null, ['class' => 'form-control', 'id'=>'name_kh', 'required' => 'required']) }}
    </div>
</div>


<div class="form-group">
    {!! Form::label('time_td', "Time TD", ['class' => 'col-lg-3 control-label required']) !!}
    <div class="col-lg-2">
        {{ Form::number('time_td',  null, ['class' => 'form-control', 'id'=> 'time_td', 'required' => 'required']) }}
    </div>

    {!! Form::label('name_en', "Name English", ['class' => 'col-lg-2 control-label']) !!}

    <div class="col-lg-3">
        {{ Form::text('name_en',  null, ['class' => 'form-control', 'id'=> 'name_en', 'required' => 'required']) }}
    </div>
</div>

<div class="form-group">
    {!! Form::label('time_tp', "Time TP", ['class' => 'col-lg-3 control-label required']) !!}
    <div class="col-lg-2">
        {{ Form::number('time_tp',  null, ['class' => 'form-control', 'id'=>'time_tp', 'required' => 'required']) }}
    </div>

    {!! Form::label('name_fr', "Name France", ['class' => 'col-lg-2 control-label']) !!}

    <div class="col-lg-3">
        {{ Form::text('name_fr',  null, ['class' => 'form-control' , 'id'=> 'name_fr', 'required' => 'required']) }}
    </div>
</div>

<hr/>

<div class="form-group">
    {!! Form::label('responsible_department_id', trans('labels.backend.coursePrograms.fields.responsible_department_id'), ['class' => 'col-lg-3 control-label']) !!}
    <div class="col-lg-3">
        {{ Form::select('responsible_department_id', $other_departments, isset($courseAnnual)?$courseAnnual->responsible_department_id:null, ['class' => 'form-control','placeholder' => "Department"]) }}
    </div>
</div>



