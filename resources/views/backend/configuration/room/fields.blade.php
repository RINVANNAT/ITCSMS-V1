<div class="form-group">
    {!! Form::label('name', trans('labels.backend.rooms.fields.name'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
    {!! Form::label('name', trans('labels.backend.rooms.fields.nb_desk'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-1">
        {!! Form::text('nb_desk', null, ['class' => 'form-control']) !!}
    </div>
    {!! Form::label('name', trans('labels.backend.rooms.fields.size'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-1">
        {!! Form::text('size', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.rooms.fields.room_type_id'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::select('room_type_id',$room_types, null, ['class' => 'form-control']) !!}
    </div>
    {!! Form::label('name', trans('labels.backend.rooms.fields.nb_chair'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-1">
        {!! Form::text('nb_chair', null, ['class' => 'form-control']) !!}
    </div>
    {!! Form::label('name', trans('labels.backend.rooms.fields.nb_chair_exam'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-1">
        {!! Form::text('nb_chair_exam', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.rooms.fields.building_id'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::select('building_id',$buildings, null, ['class' => 'form-control']) !!}
    </div>
    {!! Form::label('name', trans('labels.backend.rooms.fields.department_id'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::select('department_id',$departments, null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.rooms.fields.active'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::checkbox('active', 'TRUE', true) !!}
    </div>

    {!! Form::label('name', trans('labels.backend.rooms.fields.public'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::checkbox('is_public_room') !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.rooms.fields.exam'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::checkbox('is_exam_room') !!}
    </div>

</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.rooms.fields.description'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->