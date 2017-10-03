@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.candidates.title') . ' | ' . trans('labels.backend.candidates.sub_create_title'))

@section('after-styles-end')
    {!! Html::style('plugins/datetimepicker/bootstrap-datetimepicker.min.css') !!}
    {!! Html::style('plugins/select2/select2.min.css') !!}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.candidates.title') }}
        <small>{{ trans('labels.backend.candidates.sub_create_title') }}</small>
    </h1>
@endsection


@section('content')
    {!! Form::model($candidate, ['route' => ['admin.candidates.update', $candidate->id],'class' => 'form-horizontal', 'id'=> 'candidate-form', 'role'=>'form', 'method' => 'patch']) !!}
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.candidates.sub_create_title') }}</h3>
            <div class="pull-right">
                <a href="#" id="btn-cancel" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                <input id="btn-submit" type="submit" class="btn btn-success btn-xs" value="{{ trans('buttons.general.crud.update') }}" />
            </div>
        </div><!-- /.box-header -->

        <div class="box-body" style="background-color: #ddd !important;">
            @include('backend.candidate.fields')
        </div><!-- /.box-body -->
    </div><!--box-->

    {!! Form::close() !!}
@stop

@section('after-scripts-end')
    <script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\Backend\Candidate\UpdateCandidateRequest') !!}
    @include('backend.candidate.includes.add_update_js')
@stop