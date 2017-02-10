@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.employees.title') . ' | ' . trans('labels.backend.employees.sub_edit_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.employees.title') }}
        <small>{{ trans('labels.backend.employees.sub_edit_title') }}</small>
    </h1>
@endsection


@section('after-styles-end')
    {!! Html::style('plugins/datetimepicker/bootstrap-datetimepicker.min.css') !!}
    {!! Html::style('plugins/select2/select2.min.css') !!}
@endsection

@section('content')
    {!! Form::model($employee, ['route' => ['admin.employees.update', $employee->id],'class' => 'form-horizontal', 'role'=>'form', 'method' => 'patch']) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.employees.sub_edit_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                @include ("backend.employee.fields")
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.employees.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
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
    {!! Html::script('plugins/moment/moment.min.js') !!}
    {!! Html::script('plugins/datetimepicker/bootstrap-datetimepicker.min.js') !!}
    {!! HTML::script('plugins/select2/select2.full.min.js') !!}

    <script>
        var $search_url = "{{route('admin.access.users.search')}}";
        var base_url = '{{url('img/profiles/')}}';
        var selected_user_id = {{$employee->user->id}};
        var selected_user = "{{$employee->user->name}}";

        $(function(){
            $('#birthdate').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        });

        $(document).ready(function(){
            var user_search_box = $(".select_user").select2({
                placeholder: 'Enter name ...',
                allowClear: true,
                tags: true,
                createTag: function (params) {
                    return {
                        id: params.term,
                        name: params.term,
                        group: 'customer',
                        newOption: true
                    }
                },
                ajax: {
                    url: $search_url,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term || '', // search term
                            page: params.page || 1
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 3,
                templateResult: formatRepoUser, // omitted for brevity, see the source of this page
                templateSelection: formatRepoSelectionUser, // omitted for brevity, see the source of this page
                initSelection : function (element, callback) {
                    var data = {id: selected_user_id, name: selected_user};
                    callback(data);
                }
            });

        })
    </script>
@stop
