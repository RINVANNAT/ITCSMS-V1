<div class="form-group">
    {!! Form::label('name', trans('labels.backend.employees.fields.name_kh'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('name_kh', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.employees.fields.name_latin'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('name_latin', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.employees.fields.email'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('email', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.employees.fields.phone'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('phone', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.employees.fields.birthdate'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('birthdate', null, ['class' => 'form-control','id'=>'birthdate']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.employees.fields.address'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('address', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.employees.fields.active'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::checkbox('active', 'true',true) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.employees.fields.gender_id'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::select('gender_id',$genders, null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.employees.fields.department_id'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::select('department_id',$departments, null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

@if (access()->hasRole('Administrator'))
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.employees.fields.user_id'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        <select name="user_select2" class="form-control select_user" id="select_user"></select>
        {{ Form::hidden('user_id', null, ['class' => 'form-control', 'id'=>'user_id']) }}
    </div>
</div>
@endif

<div class="form-group">
    <label class="col-lg-2 control-label">{{ trans('validation.attributes.backend.access.users.associated_roles') }}</label>
    <div class="col-lg-10">
        <div class="row">
        @if (count($positions) > 0)
            @foreach($positions as $position)
                <div class="col-md-4">
                @if(isset($employee))
                    <input type="checkbox" value="{{$position->id}}" name="assignees_roles[]" id="role-{{$position->id}}"
                            {{in_array($position->id,$employee->positions()->lists('position_id')->toArray())?'checked="checked"':""}} />
                @else
                    <input type="checkbox" value="{{$position->id}}" name="assignees_roles[]" id="role-{{$position->id}}" />
                @endif

                <label for="role-{{$position->id}}">{!! $position->title !!}</label>
                </div>
            @endforeach
        @else
            {{ trans('labels.backend.access.users.no_roles') }}
        @endif
        </div>

    </div>
</div>