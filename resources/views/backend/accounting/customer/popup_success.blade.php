@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.customers.title') . ' | ' . trans('labels.backend.customers.sub_create_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.customers.title') }}
        <small>{{ trans('labels.backend.customers.sub_create_title') }}</small>
    </h1>
@endsection

@section('content')
    <div class="alert alert-info">
        <h4><i class="icon fa fa-info"></i> Operation is successful!</h4>
        New customer has been inserted into our database. You may continue your work!
    </div>

    <a onclick="self.close();" href="javascript:void(0);" class="btn btn-success">Close</a>
@stop