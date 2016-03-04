<div class="form-group">
    {!! Form::label('name', trans('labels.backend.candidates.fields.code'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-3">
        {!! Form::text('code', null, ['class' => 'form-control']) !!}
    </div>
    <div class="col-lg-3">
        <span class="form-information">* This will be used in some shortcut eg. I5GCI</span>
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.candidates.fields.name_kh'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('name_kh', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.candidates.fields.name_en'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('name_en', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.candidates.fields.name_fr'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('name_fr', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->


<div class="form-group">
    {!! Form::label('name', trans('labels.backend.candidates.fields.description'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->