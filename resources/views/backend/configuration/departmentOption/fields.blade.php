<div class="form-group">
    {!! Form::label('name', trans('labels.backend.departmentOptions.fields.code'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::text('code', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.departmentOptions.fields.name_kh'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('name_kh', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.departmentOptions.fields.name_en'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('name_en', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.departmentOptions.fields.name_fr'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('name_fr', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.departmentOptions.fields.department_id'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-10">
        {!! Form::select('department_id',$departments, null, ['class' => 'form-control','placeholder'=>'','required'=>'required']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.departmentOptions.fields.degree_id'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-10">
        {!! Form::select('degree_id',$degrees, null, ['class' => 'form-control','required'=>'required']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.departmentOptions.fields.active'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::checkbox('active', 'TRUE', true) !!}
    </div>
</div><!--form control-->