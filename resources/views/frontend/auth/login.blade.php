@extends('frontend.layouts.master')

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
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-bullhorn"></i> System Supporters</div>
                <div class="panel-body">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th>Line.</th>
                            {{--<th>Name</th>--}}
                            <th>Telephone</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Supporter 01</td>
                            {{--<td>Mr. KIM YOUNENG</td>--}}
                            <td><a href="tel:010 73 76 40">010 73 76 40</a></td>
                        </tr>
                        <tr>
                            <td>Supporter 02</td>
                            {{--<td>Miss. EUT CHANLEAKENA</td>--}}
                            <td><a href="tel:010 85 51 99">010 85 51 99</a></td>
                        </tr>
                        <tr>
                            <td>Supporter 03</td>
                            {{--<td>Mr. CHUN THAVORAC</td>--}}
                            <td><a href="tel:096 60 00 880">096 60 00 880</a></td>
                        </tr>
                        <tr>
                            <td>Supporter 04</td>
                            {{--<td>Mr. HEL Mab</td>--}}
                            <td><a href="tel:096 24 49 031">096 24 49 031</a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div><!-- row -->

@endsection