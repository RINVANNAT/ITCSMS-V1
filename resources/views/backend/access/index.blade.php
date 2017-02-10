@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.access.users.management'))

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
    <style>

    </style>
@stop

@section('page-header')
    <h1>
        {{ trans('labels.backend.access.users.management') }}
        <small>{{ trans('labels.backend.access.users.active') }}</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.access.users.active') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.access.includes.partials.header-buttons')
            </div>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="user-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.access.users.table.id') }}</th>
                        <th>{{ trans('labels.backend.access.users.table.name') }}</th>
                        <th>{{ trans('labels.backend.access.users.table.email') }}</th>
                        <th>{{ trans('labels.backend.access.users.table.confirmed') }}</th>
                        <th>{{ trans('labels.backend.access.users.table.roles') }}</th>
                        <th>{{ trans('labels.backend.access.users.table.other_permissions') }}</th>
                        <th class="visible-lg">{{ trans('labels.backend.access.users.table.created') }}</th>
                        <th class="visible-lg">{{ trans('labels.backend.access.users.table.last_updated') }}</th>
                        <th>{{ trans('labels.general.actions') }}</th>
                    </tr>
                    </thead>
                    {{--<tbody>--}}
                        {{--@foreach ($users as $user)--}}
                            {{--<tr>--}}
                                {{--<td>{!! $user->id !!}</td>--}}
                                {{--<td>{!! $user->name !!}</td>--}}
                                {{--<td>{!! link_to("mailto:".$user->email, $user->email) !!}</td>--}}
                                {{--<td>{!! $user->confirmed_label !!}</td>--}}
                                {{--<td>--}}
                                    {{--@if ($user->roles()->count() > 0)--}}
                                        {{--@foreach ($user->roles as $role)--}}
                                            {{--{!! $role->name !!}<br/>--}}
                                        {{--@endforeach--}}
                                    {{--@else--}}
                                        {{--{{ trans('labels.general.none') }}--}}
                                    {{--@endif--}}
                                {{--</td>--}}
                                {{--<td>--}}
                                    {{--@if ($user->permissions()->count() > 0)--}}
                                        {{--@foreach ($user->permissions as $perm)--}}
                                            {{--{!! $perm->display_name !!}<br/>--}}
                                        {{--@endforeach--}}
                                    {{--@else--}}
                                        {{--{{ trans('labels.general.none') }}--}}
                                    {{--@endif--}}
                                {{--</td>--}}
                                {{--<td class="visible-lg">{!! $user->created_at->diffForHumans() !!}</td>--}}
                                {{--<td class="visible-lg">{!! $user->updated_at->diffForHumans() !!}</td>--}}
                                {{--<td>{!! $user->action_buttons !!}</td>--}}
                            {{--</tr>--}}
                        {{--@endforeach--}}
                    {{--</tbody>--}}
                </table>
            </div>

            {{--<div class="pull-left">--}}
                {{--{!! $users->total() !!} {{ trans_choice('labels.backend.access.users.table.total', $users->total()) }}--}}
            {{--</div>--}}

            {{--<div class="pull-right">--}}
                {{--{!! $users->render() !!}--}}
            {{--</div>--}}

            {{--<div class="clearfix"></div>--}}
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    <script>
        $(function() {

            $('#user-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: {
                    url:'{!! route('admin.access.users.data') !!}',
                    method:'POST'
                },
                columns: [
                    { data: 'id', name: 'id'},
                    { data: 'name', name: 'name'},
                    { data: 'email', name: 'email'},
                    { data: 'confirmed', name: 'confirmed',searchable:false},
                    { data: 'roles', name: 'roles',orderable:false,searchable:false},
                    { data: 'other_permissions', name: 'other_permissions',orderable:false,searchable:false},
                    { data: 'created_at', name: 'created_at', orderable:false,searchable:false,},
                    { data: 'updated_at', name: 'updated_at', orderable:false,searchable:false},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });
        });
    </script>
@stop
