@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.scholarships.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.scholarships.title') }}
        <small>{{ trans('labels.backend.scholarships.sub_index_title') }}</small>
    </h1>

@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 style="font-size: 20px;"><i class="fa fa-info-circle"></i> {{trans('labels.backend.scholarships.general_information')}}
            </h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div>
                @include('backend.scholarship.includes.show_general_fields')
            </div>

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
        <div class="box-header with-border">
            <h3 style="font-size: 20px;"><i class="fa fa-forumbee"></i> {{trans('labels.backend.scholarships.more_information')}}
            </h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div>
                @include('backend.scholarship.includes.show_more_fields')
            </div>

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    <script>
        $(function() {
            $('#scholarships-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: '{!! route('admin.scholarship.data') !!}',
                columns: [
                    { data: 'name_kh', name: 'name_kh'},
                    { data: 'name_en', name: 'name_en'},
                    { data: 'name_fr', name: 'name_fr'},
                    { data: 'code', name: 'code'},
                    { data: 'founder', name: 'founder'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });

            enableDeleteRecord($('#scholarships-table'));
        });
    </script>
@stop
