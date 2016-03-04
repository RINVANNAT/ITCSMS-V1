<div class="form-group">
    {!! Form::label('name', trans('labels.backend.incomeTypes.fields.name'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.incomeTypes.fields.active'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::checkbox('active', 'TRUE', true) !!}
    </div>
</div><!--form control-->


<div class="form-group">
    {!! Form::label('name', trans('labels.backend.incomeTypes.fields.description'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->