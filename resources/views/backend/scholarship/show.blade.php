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
            $('#school_fee_and_award_table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: '{!! route('admin.configuration.schoolFee.data',["true",$scholarship->id]) !!}',
                columns: [
                    { data: 'group', name: 'group', orderable:false, searchable:false},
                    { data: 'promotion_name', name: 'promotion_name', orderable:false, searchable:false},
                    { data: 'to_pay', name: 'to_pay', orderable:false, searchable:false},
                    { data: 'budget', name: 'budget', orderable:false, searchable:false},
                ]
            });
            $('#scholarship_holder_table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: '{!! route('admin.student.data',$scholarship->id) !!}',
                columns: [
                    { data: 'id_card', name: 'students.id_card',orderable:false,searchable:false },
                    { data: 'name_kh', name: 'students.name_kh',orderable:false,searchable:false },
                    { data: 'name_latin', name: 'students.name_latin',orderable:false, searchable:false},
                    { data: 'dob', name: 'students.dob',orderable:false, searchable:false},
                    { data: 'class' , name: 'class',orderable:false, searchable:false},
                ]
            });

            enableDeleteRecord($('#scholarships-table'));
        });
    </script>
@stop
