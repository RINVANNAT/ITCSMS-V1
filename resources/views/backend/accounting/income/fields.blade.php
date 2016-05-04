{!! Form::hidden('payslip_client_id', null,['id'=>'payment_payslip_client_id']) !!}
{!! Form::hidden('client_type', null,['id'=>'form_client_type']) !!}
{!! Form::hidden('client_id', null,['id'=>'client_id']) !!}
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.incomes.fields.number'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-2">
        {!! Form::text('number', $number, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->
<div class="form-group">
    {!! Form::label('name', trans('labels.backend.incomes.fields.amount_dollar'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::text('amount_dollar', null, ['class' => 'form-control','id'=>'amount_dollar']) !!}
    </div>
    <span>$</span>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.incomes.fields.amount_riel'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::text('amount_riel', null, ['class' => 'form-control','id'=>'amount_riel']) !!}
    </div>
    <span>៛</span>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.incomes.fields.amount_kh'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::text('amount_kh', null, ['class' => 'form-control','id'=>'amount_kh']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.incomes.fields.client_name'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::select('client_name',[],null,['id'=>'client_name','class'=>"select_client form-control"]) !!}
    </div>
    {!! Form::label('name', trans('labels.backend.incomes.fields.department'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-4">
        {!! Form::text('department',null,['id'=>'department','class'=>"form-control"]) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.incomes.fields.account_id'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::select('account_id', $accounts, current(array_keys($accounts)), array('class'=>'form-control')) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.incomes.fields.income_type'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::select('income_type_id', $incomeTypes, null, array('class'=>'form-control')) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.incomes.fields.description'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::textarea('description', null, array('class'=>'form-control','rows'=>2)) !!}
    </div>
</div><!--form control-->
