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
        {!! Form::select('user_id',$users, null, ['class' => 'form-control','placeholder'=>'']) !!}
    </div>
</div><!--form control-->
@endif

<div class="form-group">
    <label class="col-lg-2 control-label">{{ trans('validation.attributes.backend.access.users.associated_roles') }}</label>
    <div class="col-lg-3">
        @if (count($roles) > 0)
            @foreach($roles as $role)
                @if(isset($employee))
                    <input type="checkbox" value="{{$role->id}}" name="assignees_roles[]" id="role-{{$role->id}}" {{in_array($role->id,$employee->roles()->lists('role_id')->toArray())?'checked="checked"':""}} />
                @else
                    <input type="checkbox" value="{{$role->id}}" name="assignees_roles[]" id="role-{{$role->id}}" />
                @endif

                <label for="role-{{$role->id}}">{!! $role->name !!}</label>
                <br/>
            @endforeach
        @else
            {{ trans('labels.backend.access.users.no_roles') }}
        @endif
    </div>
</div><!--form control-->