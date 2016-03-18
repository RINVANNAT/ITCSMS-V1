<div class="form-group">
    {!! Form::label('name', trans('labels.backend.customers.fields.name'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.customers.fields.phone'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::text('phone', null, ['class' => 'form-control']) !!}
    </div>

    {!! Form::label('name', trans('labels.backend.customers.fields.email'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::text('email', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.customers.fields.company'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::text('company', null, ['class' => 'form-control']) !!}
    </div>
    {!! Form::label('name', trans('labels.backend.customers.fields.identity_number'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::text('identity_number', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.customers.fields.active'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::checkbox('active', 'True',true) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.customers.fields.address'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::textarea('address', null, ['class' => 'form-control','rows' => 2]) !!}
    </div>
</div><!--form control-->