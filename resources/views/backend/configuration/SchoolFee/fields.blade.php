<h3 style="font-size: 20px;"><i class="fa fa-info-circle"></i> {{trans('labels.backend.schoolFees.index_tabs.school_fee')}}</h3>
<hr/>
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.schoolFees.fields.degree_id'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-4">
        {!! Form::select('degree_id',$degrees, null, ['class' => 'form-control', 'required'=> 'required']) !!}
    </div>
    {!! Form::label('name', trans('labels.backend.schoolFees.fields.scholarship_id'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::select('scholarship_id',$scholarships, null, ['class' => 'form-control','placeholder'=>'']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.schoolFees.fields.promotion_id'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-4">
        {!! Form::select('promotion_id',$promotions, null, ['class' => 'form-control', 'required'=> 'required']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.schoolFees.fields.to_pay'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::text('to_pay', null, ['class' => 'form-control']) !!}
    </div>
    <div class="col-lg-1">
        {!! Form::select('to_pay_currency',['$'=>'$', '៛' => '៛'], null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.schoolFees.fields.department_id'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
    @foreach($departments as $key => $department)
        @if(isset($schoolFee))
            <input type="checkbox" value="{{$key}}" name="departments[]" {{in_array($key,$schoolFee->departments->lists('id')->toArray())?'checked="checked"':''}} id="department-{{$key}}" /> {{$department}} <br/>
        @else
            <input type="checkbox" value="{{$key}}" name="departments[]" checked="checked" id="department-{{$key}}" /> {{$department}} <br/>
        @endif
    @endforeach
    </div>

    {!! Form::label('name', trans('labels.backend.schoolFees.fields.grade_id'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
    @foreach($grades as $key => $grade)
        @if(isset($schoolFee))
            <input type="checkbox" value="{{$key}}" name="grades[]" {{in_array($key,$schoolFee->grades->lists('id')->toArray())?'checked="checked"':''}} id="grade-{{$key}}" /> {{$grade}} <br/>
        @else
            <input type="checkbox" value="{{$key}}" name="grades[]" checked="true" id="grade-{{$key}}" /> {{$grade}} <br/>
        @endif
    @endforeach
    </div>
</div>

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.schoolFees.fields.description'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->