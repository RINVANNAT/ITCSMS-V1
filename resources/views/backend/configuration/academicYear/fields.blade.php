<div class="form-group">
    {!! Form::label('name', trans('labels.backend.academicYears.fields.code'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-3">
        {!! Form::text('id', null, ['class' => 'form-control', 'id'=>'code', 'required'=>'required']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.academicYears.fields.name_kh'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('name_kh', null, ['class' => 'form-control', 'required'=>'required']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.academicYears.fields.name_latin'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('name_latin', null, ['class' => 'form-control', 'required'=>'required']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.academicYears.fields.date_start_end'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input type="text" name="date_start_end" class="form-control pull-right" id="date_start_end" value="{!! isset($academicYear)?$academicYear->date_start->format('d/m/Y')." - ".$academicYear->date_end->format('d/m/Y'):"" !!}">
        </div>
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.academicYears.fields.description'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->