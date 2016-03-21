<table  style="width:100%; border: 1px solid #000000;">
    <tr>
        <td align="middle" valign="middle" style="width:30%;padding:10px;">
            អង្គភាព<br/><br/>
                    <span><b>
                            វិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា
                        </b>
                    </span>

        </td>
        <td align="middle">
            <h3 style="font-family: khmerosmoulpali">ប័ណ្ណចំណាយ</h3>
            <h5 style="font-family: tactieng;font-size: 40px; margin-bottom: 0px;margin-top: 0px;">3</h5>


        </td>
        <td align="middle" valign="top" style="width:30%;padding:10px;">
            គំរូលេខ ៣/​ ស.ប <br/>
            លេខ : <strong id="client_name_latin">{{str_pad($outcome->number, 4, '0', STR_PAD_LEFT)}}</strong> <br/>
        </td>
    </tr>
    <tr>
        <td align="middle">
            <div class="form-group col-md-12 col-sm-12" style="padding-left: 0px;">

            </div>

        </td>
        <td align="middle">
            <h5 class="current_date" style="font-family: bayon">ថ្ងៃទី ១២ ខែ ០២ ឆ្នាំ ២០១៦ </h5>
        </td>
        <td align="middle" valign="middle">
            <div class="form-group col-md-12 col-sm-12" style="padding-left: 0px;">
                @if($outcome->amount_dollar == null)
                <div class="col-md-12" style="padding-right: 0px;padding-left: 0px;">
                    {!! Form::label('amount_riel','៛',array("style"=>"font-size:20px;width:20%;")) !!}
                    {!! Form::text('amount_riel',$outcome->amount_riel,['id'=>'amount_riel','style'=>'width:70%', 'disabled'=>'disabled']) !!}
                </div>
                @else
                <div class="col-md-12" style="padding-right: 0px;padding-left: 0px;">
                    {!! Form::label('amount_dollar','$',array("style"=>"font-size:20px;width:20%;")) !!}
                    {!! Form::text('amount_dollar',$outcome->amount_dollar,['id'=>'amount_dollar','style'=>'width:70%', 'disabled'=>'disabled']) !!}
                </div>
                @endif


            </div>
        </td>
    </tr>
    <tr style="font-family: metal">
        <td colspan="3">

            <div class="col-md-3 col-sm-3 col-xs-3 outcome_label">បើកអោយលោក</div>
            <div class="col-md-3 col-sm-3 col-xs-3">
                <strong>
                    @if($outcome->payslipClient->employee == null)
                        @if($outcome->payslipClient->customer == null)
                            {{$outcome->payslipClient->student->student->name_kh}}
                        @else
                            {{$outcome->payslipClient->customer->name}}
                        @endif
                    @else
                        {{$outcome->payslipClient->employee->name_kh}}
                    @endif

                </strong>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-2 outcome_label"> អង្គភាព </div>
            <div class="col-md-4 col-sm-2 col-xs-2 input-group input-group-sm" style="padding-left: 15px;">
                <strong>
                    @if($outcome->payslipClient->employee == null)
                        @if($outcome->payslipClient->customer == null)
                            {{$outcome->payslipClient->student->department->name_kh}}
                        @else
                            {{$outcome->payslipClient->customer->company}}
                        @endif
                    @else
                        {{$outcome->payslipClient->employee->department->name_kh}}
                    @endif

                </strong>
            </div>
        </td>
    </tr>
    <tr style="font-family: metal">
        <td colspan="3">
            <div class="col-md-4 col-sm-2 col-xs-4 outcome_label">
                ចំនួនប្រាក់ (ជាអក្សរ)
            </div>
            <div class="col-md-8 col-sm-8 col-xs-8 c input-group input-group-sm" style="padding-left: 15px;">
                <strong>{{$outcome->amount_kh}}</strong>
            </div>
        </td>
    </tr>
    <tr style="font-family: metal">
        <td colspan="3">
            <div class="col-md-3 col-sm-3 col-xs-3 outcome_label">
                សំរាប់ចំណាយក្នុងខ្ទង
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3 input-group input-group-sm" style="padding-left: 15px;float: left;">
                <strong>
                    {{$outcome->description}}
                </strong>
                ់</div>
            <div class="col-md-3 col-sm-3 col-xs-3 outcome_label">
                ប្រភេទចំណាយ
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3 input-group input-group-sm" style="padding-left: 15px;">
                <strong>{{$outcome->outcomeType->code ." | ".$outcome->outcomeType->name}}</strong>
            ់</div>
        </td>
    </tr>
    <tr style="font-family: metal">
        <td colspan="3">
            <div class="col-md-3 col-sm-3 col-xs-3 outcome_label">
                ចុះក្នុងគណនី
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3 input-group input-group-sm" style="padding-left: 15px; float: left;">
                <strong>
                    {{$outcome->account->name}}
                </strong>
            </div>
            <div class="col-md-5 col-sm-5 col-xs-5 outcome_label">
                លេខ <strong id="client_name_latin">...................<!-- Auto generate --></strong></div></td>
        </td>
    </tr>
    <tr style="font-family: metal">
        <td colspan="3">
            <div class="col-md-2 col-sm-2 col-xs-2 outcome_label">
                ភ្ជាប់ជាមួយ
            </div>
            <div class="col-md-4 col-sm-4 col-xs-4 input-group input-group-sm" style="padding-left: 15px;float: left;">
                <strong>
                    {{$outcome->attachment_name}}
                </strong>
            </div>
            <div class="col-md-5 col-sm-5 col-xs-5 outcome_label">
                សក្ខីប័ត្រដើម។
            </div>

        </td>
    </tr>

    <tr style="font-family: metal;">
        <td colspan="3" align="right">
            <div class="col-md-6 col-sm-6 col-xs-6"></div>
            <div class="col-md-6 col-sm-6 col-xs-6" align="center" style="padding-top: 15px">
                បានទទួល,  <span class="current_date"></span><strong id="client_current_date">​<strong>{{$outcome->pay_date->format('d/m/Y')}}</strong></strong><br/>
            </div>
        </td>
    </tr>
    <tr style="font-family: kh-bokor">
        <td colspan="3" align="left" style="padding-bottom: 60px;">
            <div class="col-md-3 col-sm-3 col-xs-3" style="padding-top: 60px">
                ប្រធានអង្គភាព
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3" style="padding-top: 60px">
                ប្រធានគណនេយ្យ
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3" style="padding-top: 60px">
                គណនេយ្យទូទាត់
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3" style="padding-top: 60px">
                អ្នកទទួលប្រាក់
            </div>
        </td>
    </tr>

</table>