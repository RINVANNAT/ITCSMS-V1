@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.customers.title') . ' | ' . trans('labels.backend.customers.sub_edit_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.customers.title') }}
        <small>{{ trans('labels.backend.customers.sub_edit_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/select2/select2.min.css') !!}
@stop

@section('content')
    {!! Form::model($customer, ['route' => ['admin.accounting.customers.update', $customer->id],'class' => 'form-horizontal', 'role'=>'form', 'method' => 'patch']) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.customers.sub_edit_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                @include ("backend.accounting.customer.fields")
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.accounting.customers.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                </div>

                <div class="pull-right">
                    <input type="submit" class="btn btn-info btn-xs" value="{{ trans('buttons.general.crud.update') }}" />
                </div>
                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->
    {!! Form::close() !!}
@stop


@section('after-scripts-end')
    {!! HTML::script('plugins/select2/select2.full.min.js') !!}
    <script>
        $(function () {
            $(".select2").select2();
        });
    </script>
@stop
