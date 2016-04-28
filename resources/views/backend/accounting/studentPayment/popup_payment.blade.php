@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.candidates.title') . ' | ' . trans('labels.backend.candidates.sub_create_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.candidates.title') }}
        <small>{{ trans('labels.backend.candidates.sub_create_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
@stop

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.students.sub_detail_title') }}</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="row">
                <div class="col-xs-12">
                    {!! Form::open(['route' => 'admin.accounting.incomes.store','id'=>'payslip_income_form']) !!}
                    {!! Form::hidden('candidate_id', null,['id'=>'payment_candidate_id']) !!}
                    {!! Form::hidden('student_annual_id', null,['id'=>'payment_student_annual_id']) !!}
                    {!! Form::hidden('payslip_client_id', null,['id'=>'payment_payslip_client_id']) !!}
                    {!! Form::hidden('degree_id', null,['id'=>'client_degree_id']) !!}
                    <table width="100%" id="table_modal_payment">
                        <tr>
                            <td align="left" width="20%">
                                <img src="{{url('/img/ITC_Logo.png')}}" width="150px"/>
                            </td>
                            <td align="middle">
                                <h3 style="font-family: khmerosmoulpali">វិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា</h3>
                                <h4>Institut de Technologie du Cambobodge</h4>
                                <h5 style="font-family: tactieng;font-size: 60px;">3</h5>

                                <h3 style="font-family: bayon">បង្កាន់ដៃទទួលប្រាក់ </h3>
                            </td>
                            <td align="left" width="20%" valign="4bottom">
                                លេខ
                            </td>
                        </tr>
                        <tr style="font-family: metal">
                            <td colspan="3">
                                <div class="col-md-6"> នាមត្រកូល និង នាមខ្លួន <strong id="client_name_kh"> <!-- Auto generate --> </strong></div><div class="col-md-6">  អក្សរឡាតាំង <strong id="client_name_latin"><!-- Auto generate --></strong></div></td>
                        </tr>
                        <tr style="font-family: metal">
                            <td colspan="3">
                                <div class="col-md-6" align="right">ភេទ <strong style="padding-right: 30px;" id="client_gender"><!-- Auto generate --></strong></div>
                                <div class="col-md-6" style="padding-left: 0px;">ថ្ងែខែឆ្នាំកំណើត <strong id="client_birthdate"><!-- Auto generate --></strong></div>
                            </td>
                        </tr>
                        <tr style="font-family: metal">
                            <td colspan="3">
                                <div class="form-group col-md-6">
                                    {!! Form::label('amount_riel', "ចំនួនទឺកប្រាក់ជាប្រាក់រៀល  ",['style'=>'margin-right:10px;']) !!}
                                    {!! Form::text('amount_riel', null, ['id'=>'income_riel', 'style'=>'width:40%']) !!}
                                </div>
                                <div class="form-group col-md-6" style="padding-left: 0px;">
                                    {!! Form::label('amount_riel_kh',"ជាអក្សរ") !!}
                                    {!! Form::text('amount_riel_kh',null,['id'=>'income_riel_kh','style'=>'width:80%']) !!}
                                </div>
                            </td>
                        </tr>
                        <tr style="font-family: metal">
                            <td colspan="3">
                                <div class="form-group col-md-6">
                                    {!! Form::label('amount_dollar', "ចំនួនទឺកប្រាក់ជាប្រាក់ដុល្លា",['style'=>'margin-right:10px;']) !!}
                                    {!! Form::text('amount_dollar', null, ['id'=>'income_dollar','style'=>'width:40%']) !!}
                                </div>
                                <div class="form-group col-md-6" style="padding-left: 0px;">
                                    {!! Form::label('amount_dollar_kh',"ជាអក្សរ") !!}
                                    {!! Form::text('amount_dollar_kh',null,['id'=>'income_dollar_kh','style'=>'width:80%']) !!}
                                </div>
                            </td>
                        </tr>

                        <tr style="font-family: metal">
                            <td colspan="3">
                                <div class="col-md-12">
                                    ជានិស្សិតដេប៉ាតឺម៉ង់ <strong id="client_department"> ថ្នាក់មូលដ្ធាន</strong>​ ឆ្នាំទី <strong id="client_grade"></strong>
                                </div>
                            </td>
                        </tr>
                        <tr style="font-family: metal">
                            <td colspan="3">
                                <div class="col-md-12">
                                    ថ្នាក់រៀន <strong id="client_degree">Ingenieur</strong> ជំនាន់/វគ្គ <strong id="client_promotion"></strong> បង់លើកទី <strong id="client_payment_sequence"></strong> ឆ្នាំសិក្សា <strong id="client_academic_year"></strong>
                                </div>
                            </td>
                        </tr>
                        <tr style="font-family: metal;padding-top: 15px;">
                            <td colspan="3">
                                <div class="form-group col-md-12">
                                    {!! Form::label('descriptin', "កំណត់សំគាល់  ",['style'=>'margin-right:10px;']) !!}
                                    {!! Form::textarea('description', null, ['id'=>'description', 'rows'=>2,'class'=>'col-md-12']) !!}
                                </div>
                            </td>
                        </tr>

                        <tr style="font-family: metal;">
                            <td colspan="3" align="right">
                                <div class="col-md-6"></div>
                                <div class="col-md-6" align="center" style="padding-top: 15px">
                                    រាជធានីភ្នំពេញ, <strong id="client_current_date">​<!-- Auto generate --></strong><br/>
                                    អ្នកទទួលប្រាក់
                                </div>
                            </td>
                        </tr>
                        <tr style="font-family: kh-bokor">
                            <td colspan="3" align="left">
                                <div class="col-md-12" style="padding-top: 60px">
                                    <span style="text-decoration: underline">សំគាល់: </span> និស្សិតដែលបង់ថ្លៃសិក្សារួចហើយមិនអាចដក់ប្រាក់វិញបានទេ ។
                                </div>
                            </td>
                        </tr>

                    </table>
                    {!! Form::close() !!}

                </div>


            </div>
        </div><!-- /.box-body -->
    </div><!--box-->


@stop

