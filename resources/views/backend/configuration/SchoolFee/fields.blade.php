<h3 style="font-size: 20px;"><i class="fa fa-info-circle"></i> {{trans('labels.backend.schoolFees.index_tabs.school_fee')}}</h3>
<hr/>
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.schoolFees.fields.degree_id'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-4">
        {!! Form::select('degree_id',$degrees, null, ['class' => 'form-control', 'required'=> 'required']) !!}
    </div>
    {!! Form::label('name', trans('labels.backend.schoolFees.fields.grade_id'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::select('grade_id',$grades, null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.schoolFees.fields.promotion_id'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-4">
        {!! Form::select('promotion_id',$promotions, null, ['class' => 'form-control', 'required'=> 'required']) !!}
    </div>
    {!! Form::label('name', trans('labels.backend.schoolFees.fields.department_id'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::select('department_id',$departments, null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.schoolFees.fields.to_pay'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::text('to_pay', null, ['class' => 'form-control']) !!}
    </div>
    <div class="col-lg-1">
        {!! Form::select('to_pay_currency',['dollar'=>'$', 'riel' => '៛'], null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->
<br/>
<h3 style="font-size: 20px;"><i class="fa fa-info-circle"></i> {{trans('labels.backend.schoolFees.index_tabs.scholarship_fee')}}</h3>
<hr/>

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.schoolFees.fields.scholarship_id'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::select('scholarship_id',$scholarships, null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.schoolFees.fields.budget'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::text('budget', null, ['class' => 'form-control']) !!}
    </div>
    <div class="col-lg-1">
        {!! Form::select('budget_currency',['$'=>'$', '៛' => '៛'], null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.schoolFees.fields.description'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->