<div class="form-group">
    {!! Form::label('name', trans('labels.backend.incomes.fields.code'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('code', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.incomes.fields.name_kh'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('name_kh', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.incomes.fields.name_en'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('name_en', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.incomes.fields.name_fr'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('name_fr', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.incomes.fields.school'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::select('school_id', $schools, current(array_keys($schools)), array('class'=>'form-control','placeholder'=>trans('labels.general.none'))) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.incomes.fields.departments'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        <select class="select2" name="departments[]" multiple="multiple" style="width: 100%">
            @if(isset($selected_departments))
                @foreach ($departments as $key => $value)
                    <option value="{!! $key !!}" {!! in_array($key,$selected_departments)?'selected="selected"':"" !!}>{!! $value !!}</option>
                @endforeach
            @else
                @foreach ($departments as $key => $value)
                    <option value="{!! $key !!}">{!! $value !!}</option>
                @endforeach
            @endif
        </select>
    </div>
</div><!--form control-->


<div class="form-group">
    {!! Form::label('name', trans('labels.backend.incomes.fields.description'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->