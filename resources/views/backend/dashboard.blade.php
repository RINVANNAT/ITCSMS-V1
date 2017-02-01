@extends('backend.layouts.master')

@section('after-styles-end')
    <style>
        .timeline-inverse>li>.timeline-item {
            background: #f0f0f0 !important;
            border: 1px solid #ddd !important;
            -webkit-box-shadow: none;
            box-shadow: none !important;
        }


    </style>
@stop

@section('page-header')
    <h1>
        {!! app_name() !!}
        <small>{{ trans('strings.backend.dashboard.title') }}</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('strings.backend.dashboard.welcome') }} {!! access()->user()->name !!}!</h3>
            <div class="box-tools pull-right">
                {{--<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>--}}
                {{--<div class="btn-group">--}}
                    {{--<button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">--}}
                        {{--Export data <span class="caret"></span>--}}
                    {{--</button>--}}
                    {{--<ul class="dropdown-menu" role="menu">--}}
                        {{--<li><a href="#" id="export_student_list">Export current student list</a></li>--}}
                        {{--<li><a href="#" id="export_student_list_custom">Export custom student list</a></li>--}}

                    {{--</ul>--}}
                {{--</div>--}}
            </div>
        </div>
        <div class="box-body">

            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> Welcome to ITC-School Management Information System.</h4>
                <p>
                    This application is under construction with partial release. Please report the problems or your demanding to our developers by using this <a href="{{route('admin.reporting.index')}}">REPORTING SYSTEM</a>.
                    We appreciate your contributions and we hope to run this system in full scale very soon.
                </p>
                <p>
                    - Developer Team
                </p>
            </div>

            <?php
                $teacher = false;
                foreach($user->roles as $role){
                    if($role->name == "Teacher"){
                        $teacher = true;
                        break;
                    }
                }
            ?>

            <div class="row">
                <div class="col-md-12">
                    <ul class="timeline timeline-inverse">

                        @if($teacher)
                            @include('backend.dashboard.teacher')
                        @endif

                        <li>
                            <i class="fa fa-user bg-purple"></i>

                            <div class="timeline-item">
                                {{--<span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>--}}

                                <h3 class="timeline-header"><a href="#">User information</a> view/update your information</h3>

                                <div class="timeline-body">
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <div role="tabpanel">

                                                <!-- Nav tabs -->
                                                <ul class="nav nav-tabs" role="tablist">
                                                    <li role="presentation" class="active">
                                                        <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">{{ trans('navs.frontend.user.my_information') }}</a>
                                                    </li>
                                                </ul>

                                                <div class="tab-content">

                                                    <div role="tabpanel" class="tab-pane active" id="profile">
                                                        <table class="table table-striped table-hover table-bordered dashboard-table">
                                                            <tr>
                                                                <th>{{ trans('labels.frontend.user.profile.avatar') }}</th>
                                                                <td><img src="{!! $user->picture !!}" class="user-profile-image" /></td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('labels.frontend.user.profile.name') }}</th>
                                                                <td>{!! $user->name !!}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('labels.frontend.user.profile.email') }}</th>
                                                                <td>{!! $user->email !!}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('labels.frontend.user.profile.created_at') }}</th>
                                                                <td>{!! $user->created_at !!} ({!! $user->created_at->diffForHumans() !!})</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('labels.frontend.user.profile.last_updated') }}</th>
                                                                <td>{!! $user->updated_at !!} ({!! $user->updated_at->diffForHumans() !!})</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('labels.general.actions') }}</th>
                                                                <td>
                                                                    <a href="{!! route('frontend.user.profile.edit') !!}" class="btn btn-primary btn-xs">{{ trans('labels.frontend.user.profile.edit_information') }}</a>

                                                                    @if ($user->canChangePassword())
                                                                        <a href="{!! route('auth.password.change') !!}" class="btn btn-warning btn-xs">{{ trans('navs.frontend.user.change_password') }}</a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div><!--tab panel profile-->

                                                </div><!--tab content-->

                                            </div><!--tab panel-->

                                        </div><!--panel body-->

                                    </div><!-- panel -->
                                </div>
                            </div>
                        </li>

                        {{--<li>--}}
                        {{--<i class="fa fa-camera bg-purple"></i>--}}

                        {{--<div class="timeline-item">--}}
                        {{--<span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>--}}

                        {{--<h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>--}}

                        {{--<div class="timeline-body">--}}
                        {{--<img src="http://placehold.it/150x100" alt="..." class="margin">--}}
                        {{--<img src="http://placehold.it/150x100" alt="..." class="margin">--}}
                        {{--<img src="http://placehold.it/150x100" alt="..." class="margin">--}}
                        {{--<img src="http://placehold.it/150x100" alt="..." class="margin">--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</li>--}}
                        <li>
                            <i class="fa fa-clock-o bg-gray"></i>
                        </li>
                    </ul>
                </div>
            </div>

        </div><!-- /.box-body -->
    </div><!--box box-success-->
@endsection

@section('after-scripts-end')
    <script>
    </script>
@stop