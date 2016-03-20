<div class="form-group">
    {!! Form::label('name', trans('labels.backend.error.reporting.fields.title'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-3">
        {!! Form::text('title', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.error.reporting.fields.description'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group">
    {!! Form::label('name', trans('labels.backend.error.reporting.fields.image'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! Form::file('image', null, ['class' => 'form-control']) !!}
    </div>
</div><!--form control-->

<div class="form-group" id="preview">
    <div class="col-lg-2"></div>
    @if(isset($reporting->image))
        <img style="padding: 15px;" src="{{url('img/reporting/'.$reporting->image)}}"/>
    @endif
</div>

