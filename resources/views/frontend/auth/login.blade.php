@extends('frontend.layouts.master')

@section('after-styles-end')
    <style>
        .supporter {
            margin-top: 90px;
            width: 100%;
            height: 20vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .supporter-body {
            /*display: inline;*/
        }
        .supporter-image > img{
            width: 320px;
        }
    </style>
@endsection

@section('content')

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading">{{ trans('labels.frontend.auth.login_box_title') }}</div>

                <div class="panel-body">

                    {!! Form::open(['url' => 'login', 'class' => 'form-horizontal']) !!}

                    <div class="form-group">
                        {!! Form::label('email', trans('validation.attributes.frontend.email'), ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::input('email', 'email', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.frontend.email')]) !!}
                        </div><!--col-md-6-->
                    </div><!--form-group-->

                    <div class="form-group">
                        {!! Form::label('password', trans('validation.attributes.frontend.password'), ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::input('password', 'password', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.frontend.password')]) !!}
                        </div><!--col-md-6-->
                    </div><!--form-group-->

                    {{--<div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('remember') !!} {{ trans('labels.frontend.auth.remember_me') }}
                                </label>
                            </div>
                        </div><!--col-md-6-->
                    </div><!--form-group-->--}}

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            {!! Form::submit(trans('labels.frontend.auth.login_button'), ['class' => 'btn btn-primary', 'style' => 'margin-right:15px']) !!}

                            {!! link_to('password/reset', trans('labels.frontend.passwords.forgot_password')) !!}
                        </div><!--col-md-6-->
                    </div><!--form-group-->

                    {!! Form::close() !!}

                    <div class="row text-center">
                        {!! $socialite_links !!}
                    </div>
                </div><!-- panel body -->

            </div><!-- panel -->

        </div><!-- col-md-8 -->

        <div class="col-md-8 col-md-offset-2">
            <div class="supporter">
                <div class="supporter-body">
                    <div class="supporter-image">
                        <img src="/img/undraw_calling_kpbp.svg"/>
                    </div>
                    <div class="supporter-tel text-center">
                        <a href="tel:077542245">
                            <h3>Tel: 077542245</h3>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- row -->

@endsection
