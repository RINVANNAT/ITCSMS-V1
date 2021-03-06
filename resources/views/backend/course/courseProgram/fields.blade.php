<div class="row no-margin">
    <div class="form-group">
        {!! Form::label('name_kh', trans('labels.backend.coursePrograms.fields.name_kh'), ['class' => 'col-lg-3 control-label required']) !!}
        <div class="col-lg-5">
            {!! Form::text('name_kh', null, ['class' => 'form-control','required'=>'required']) !!}
        </div>
    </div>
</div>
<div class="row no-margin">
    <div class="form-group">
        {!! Form::label('name_en', trans('labels.backend.coursePrograms.fields.name_en'), ['class' => 'col-lg-3 control-label required']) !!}
        <div class="col-lg-5">
            {!! Form::text('name_en', null, ['class' => 'form-control','required'=>'required']) !!}
        </div>
    </div>
</div>
<div class="row no-margin">
    <div class="form-group">
        {!! Form::label('name_fr', trans('labels.backend.coursePrograms.fields.name_fr'), ['class' => 'col-lg-3 control-label required']) !!}
        <div class="col-lg-5">
            {!! Form::text('name_fr', null, ['class' => 'form-control','required'=>'required']) !!}
        </div>
    </div>
</div>
<div class="row no-margin">
    <div class="form-group">
        {!! Form::label('code', trans('labels.backend.coursePrograms.fields.code'), ['class' => 'col-lg-3 control-label']) !!}
        <div class="col-lg-3">
            {!! Form::text('code', null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>
<div class="row no-margin">
    <div class="form-group">
        {!! Form::label('time_course', trans('labels.backend.coursePrograms.fields.time_course'), ['class' => 'col-lg-3 control-label required']) !!}
        <div class="col-lg-9">
            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <div class="col-lg-7">
                            {!! Form::number('time_course', null, ['class' => 'form-control','required'=>'required']) !!}
                        </div>

                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        {!! Form::label('time_td', trans('labels.backend.coursePrograms.fields.time_td'), ['class' => 'col-lg-3 control-label required']) !!}
                        <div class="col-lg-7">
                            {!! Form::number('time_td', null, ['class' => 'form-control','required'=>'required']) !!}
                        </div>

                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        {!! Form::label('time_tp', trans('labels.backend.coursePrograms.fields.time_tp'), ['class' => 'col-lg-3 control-label required']) !!}
                        <div class="col-lg-7">
                            {!! Form::number('time_tp', null, ['class' => 'form-control','required'=>'required']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row no-margin">
    <div class="form-group">

        {!! Form::label('credit', trans('labels.backend.coursePrograms.fields.credit'), ['class' => 'col-lg-3 control-label required']) !!}
        <div class="col-lg-3">
            {!! Form::text('credit', null, ['class' => 'form-control','required'=>'required']) !!}
        </div>


        {!! Form::label('count_credit', "Creditability For Transcript", ['class' => 'col-lg-3 control-label required']) !!}
        <div class="col-lg-2">
            @if(isset($courseProgram))
                @if($courseProgram->is_counted_creditability)
                    <input type="checkbox" name="is_counted_creditability" id="count_credit" class="boolean_input" value="{{\App\Models\Enum\ScoreEnum::is_counted_creditability}}" checked>
                @else
                    <input type="checkbox" name="is_counted_creditability" id="count_credit" class="boolean_input" value="{{\App\Models\Enum\ScoreEnum::is_counted_creditability}}" >
                @endif
            @else
                <input type="checkbox" name="is_counted_creditability" id="count_credit" class="boolean_input" value="{{\App\Models\Enum\ScoreEnum::is_counted_creditability}}" checked>
            @endif

        </div>
    </div>
</div>


<div class="form-group">
    {!! Form::label('degree', trans('labels.backend.coursePrograms.fields.degree'), ['class' => 'col-lg-3 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('degree_id', $degrees, null, ['class' => 'form-control','required'=>'required']) }}
    </div>
</div>

<div class="form-group">
    {!! Form::label('grades', trans('labels.backend.coursePrograms.fields.grade'), ['class' => 'col-lg-3 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('grade_id', $grades, null, ['class' => 'form-control','required'=>'required']) }}
    </div>
</div>

<div class="form-group">
    {!! Form::label('department_id', trans('labels.backend.coursePrograms.fields.department'), ['class' => 'col-lg-3 control-label required']) !!}
    <div class="col-lg-3">
        {{ Form::select('department_id', $departments, null, ['class' => 'form-control','required'=>'required']) }}
    </div>
    {!! Form::label('department_option_id', trans('labels.backend.coursePrograms.fields.department_option'), ['class' => 'col-lg-1 control-label']) !!}
    <div class="col-lg-3">
        {{--{{ Form::select('department_option_id', $options, null, ['class' => 'form-control']) }}--}}
        <select class="form-control" id="department_option_id" name="department_option_id">
            @if(isset($courseProgram))
                <option value=""></option>
                @foreach($options as $option)
                    <?php
                        $selected = "";
                        if($option->id == $courseProgram->department_option_id){
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
    {!! Form::label('semester', trans('labels.backend.coursePrograms.fields.semester'), ['class' => 'col-lg-3 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('semester_id', $semesters, null, ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    {!! Form::label('active', trans('labels.backend.coursePrograms.fields.active'), ['class' => 'col-lg-3 control-label required']) !!}
    <div class="col-lg-7">
        @if(isset($courseProgram))
            @if($courseProgram->active)
                <input type="checkbox" name="active" id="active" class="boolean_input" checked>
            @else
                <input type="checkbox" name="active" id="active" class="boolean_input" >
            @endif
        @else
            <input type="checkbox" name="active" id="active" class="boolean_input" checked>
        @endif
    </div>
</div>

<hr/>

<div class="form-group">
    {!! Form::label('responsible_department_id', trans('labels.backend.coursePrograms.fields.responsible_department_id'), ['class' => 'col-lg-3 control-label']) !!}
    <div class="col-lg-3">
        {{ Form::select('responsible_department_id', $other_departments, null, ['class' => 'form-control','placeholder' => "Department"]) }}
    </div>
</div>


