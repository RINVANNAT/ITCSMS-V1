<div class="form-group">
    {!! Form::label('name', trans('labels.backend.scholarships.fields.name_kh'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('name_kh', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.scholarships.fields.name_en'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('name_en', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.scholarships.fields.name_fr'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('name_fr', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.scholarships.fields.code'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('code', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.scholarships.fields.isDroppedUponFail'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::checkbox('isDroppedUponFail', 'True') !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.scholarships.fields.active'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::checkbox('active', 'True', isset($scholarship)?$scholarship->active:true) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.scholarships.fields.duration'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::select('duration',['1 year'=>'1 Year','2 years'=>'2 Years','3 years'=>'3 Years','4 years'=>'4 Years','5 years'=>'5 Years'], null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.scholarships.fields.founder'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('founder', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.scholarships.fields.date_start_end'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        <input type="text" name="date_start_end" class="form-control pull-right" id="date_start_end" value="{!! isset($scholarship)?$scholarship->start->format('d/m/Y')." - ".$scholarship->stop->format('d/m/Y'):"" !!}">
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.scholarships.fields.description'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->