<div class="form-group">
    {!! Form::label('name', trans('labels.backend.buildings.fields.name'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('code', trans('labels.backend.buildings.fields.code'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('code', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('description', trans('labels.backend.buildings.fields.description'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('description', null, ['class' => 'form-control']) !!}
    </div>
</div>