<div class="row no-margin">
    <div class="col-xs-12">

        {!! Form::hidden('payslip_client_id', null,['id'=>'payment_payslip_client_id']) !!}
        {!! Form::hidden('client_type', null,['id'=>'form_client_type']) !!}
        {!! Form::hidden('client_id', null,['id'=>'client_id']) !!}
        <table width="100%" id="table_modal_payment">
            <tr>
                <td align="middle" valign="middle" width="20%" style="padding:10px;">
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
                <td align="middle" width="20%" valign="top" style="padding:10px;">
                    គំរូលេខ ៣/​ ស.ប <br/>
                    លេខ : <strong id="client_name_latin">142<!-- Auto generate --></strong> <br/>
                </td>
            </tr>
            <tr>
                <td align="middle">
                    <div class="form-group col-md-12" style="padding-left: 0px;">

                    </div>

                </td>
                <td align="middle">
                    <h5 id="current_date" style="font-family: bayon">ថ្ងៃទី ១២ ខែ ០២ ឆ្នាំ ២០១៦ </h5>
                </td>
                <td align="middle" valign="middle">
                    <div class="form-group col-md-12" style="padding-left: 0px;">
                        <div class="col-md-12" style="padding-right: 0px;padding-left: 0px;">
                            {!! Form::label('amount_riel','៛',array("style"=>"font-size:20px;width:20%;")) !!}
                            {!! Form::text('amount_riel',null,['id'=>'amount_riel','style'=>'width:70%']) !!}
                        </div>
                        <div class="col-md-12" style="padding-right: 0px;padding-left: 0px;">
                            {!! Form::label('amount_dollar','$',array("style"=>"font-size:20px;width:20%;")) !!}
                            {!! Form::text('amount_dollar',null,['id'=>'amount_dollar','style'=>'width:70%']) !!}
                        </div>


                    </div>
                </td>
            </tr>
            <tr style="font-family: metal">
                <td colspan="3">

                    <div class="col-md-2 outcome_label">បើកអោយលោក</div>
                    <div class="col-md-4">
                        {!! Form::select('client_name',[],null,['id'=>'client_name','class'=>"select_client form-control"]) !!}
                    </div>
                    <div class="col-md-2 outcome_label"> អង្គភាព </div>
                    <div class="col-md-4 input-group input-group-sm" style="padding-left: 15px;">
                        {!! Form::text('department',null,['id'=>'department','style'=>'width:80%','class'=>"form-control"]) !!}
                    </div>
                </td>
            </tr>
            <tr style="font-family: metal">
                <td colspan="3">
                    <div class="col-md-2 outcome_label">
                        ចំនួនប្រាក់ (ជាអក្សរ)
                    </div>
                    <div class="col-md-10 input-group input-group-sm" style="padding-left: 15px;">
                        {!! Form::text('amount_kh',null,['id'=>'amount_kh','class'=>"form-control", 'style'=>'width:80%;']) !!}
                    </div>
                </td>
            </tr>
            <tr style="font-family: metal">
                <td colspan="3">
                    <div class="col-md-2 outcome_label">
                        សំរាប់ចំណាយក្នុងខ្ទង
                    </div>
                    <div class="col-md-4 input-group input-group-sm" style="padding-left: 15px;float: left;">
                        {!! Form::text('description',null,['id'=>'description','class'=>"form-control"]) !!}
                        ់</div>
                    <div class="col-md-2 outcome_label">
                        ប្រភេទចំណាយ
                    </div>
                    <div class="col-md-4 input-group input-group-sm" style="padding-left: 15px;">
                        {!! Form::select('outcome_type_id', $outcomeTypes, null, array('class'=>'form-control','style'=>'width:80%')) !!}
                        ់</div>
                </td>
            </tr>
            <tr style="font-family: metal">
                <td colspan="3">
                    <div class="col-md-2 outcome_label">
                        ចុះក្នុងគណនី
                    </div>
                    <div class="col-md-4 input-group input-group-sm" style="padding-left: 15px; float: left;">
                        {!! Form::select('account_id', $accounts, null, array('class'=>'form-control','style'=>'width:80%')) !!}
                    </div>
                    <div class="col-md-5 outcome_label">
                        លេខ <strong id="client_name_latin">...................<!-- Auto generate --></strong></div></td>
                </td>
            </tr>
            <tr style="font-family: metal">
                <td colspan="3">
                    <div class="col-md-2 outcome_label">
                        ភ្ជាប់ជាមួយ
                    </div>
                    <div class="col-md-4 input-group input-group-sm" style="padding-left: 15px;float: left;">
                        {!! Form::text('attachment_title',null,['id'=>'attachment_title','class'=>"form-control","style"=>"margin-bottom:10px;"]) !!}
                        <div id="files">
                            <input type="file" name="import[]" multiple="multiple"/>
                        </div>
                    </div>
                    <div class="col-md-5 outcome_label">
                        សក្ខីប័ត្រដើម។
                    </div>

                </td>
            </tr>

            <tr style="font-family: metal;">
                <td colspan="3" align="right">
                    <div class="col-md-6"></div>
                    <div class="col-md-6" align="center" style="padding-top: 15px">
                        បានទទួល,  ថ្ងៃទី ០១ ខែ ០២ ឆ្នាំ ២០១៦<strong id="client_current_date">​<!-- Auto generate --></strong><br/>
                    </div>
                </td>
            </tr>
            <tr style="font-family: kh-bokor">
                <td colspan="3" align="left" style="padding-bottom: 60px;">
                    <div class="col-md-3" style="padding-top: 60px">
                        ប្រធានអង្គភាព
                    </div>
                    <div class="col-md-3" style="padding-top: 60px">
                        ប្រធានគណនេយ្យ
                    </div>
                    <div class="col-md-3" style="padding-top: 60px">
                        គណនេយ្យទូទាត់
                    </div>
                    <div class="col-md-3" style="padding-top: 60px">
                        អ្នកទទួលប្រាក់
                    </div>
                </td>
            </tr>

        </table>
        <div class="col-md-12" style="padding-top: 10px;">
            អន្តរក្រសួង ក្រសួងហិរញ្ញវត្ថុ និងក្រសួងផែនការប្រកាសផ្សាយតាមសេចក្តីសំរេចលេខ២១២៧ ចុះថ្ងៃទី ១៦ ខែ១១ ឆ្នាំ១៩៨៤
        </div>


    </div>


</div>

