<table style="width:100%; height:120mm;border: 1px solid #000000;text-align: left;vertical-align: middle;" rules="none"
       frame="box" class="table_modal_payment">
    <tr>
        <td align="left" width="20%">
            <img src="{{url('/img/ITC_Logo.png')}}" width="150px"/>
        </td>
        <td align="middle">
            <h3 style="font-family: khmerosmoulpali">វិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា</h3>
            <h4>Institut de Technologie du Cambodge</h4>
            <h5 style="font-family: tactieng">3</h5>

            <h3 style="font-family: bayon">បង្កាន់ដៃទទួលប្រាក់</h3>
        </td>
        <td align="left" width="20%" valign="bottom">
            លេខ <b>{{str_pad($income->number, 4, "0", STR_PAD_LEFT)}}</b>
        </td>
    </tr>
    <tr>
        <td colspan="3"><div style="height: 1cm;"></div></td>
    </tr>
    <tr style="font-family: metal">
        <td colspan="3">
            <div class="col-md-6 col-xs-6"> នាមត្រកូល និង នាមខ្លួន
                <strong>
                    {{$income->payslipClient->student->student->name_kh}}
                </strong>
            </div>
            <div class="col-md-6 col-xs-6"> អក្សរឡាតាំង
                <strong>
                    {{$income->payslipClient->student->student->name_latin}}
                </strong>
            </div>
        </td>
    </tr>
    <tr style="font-family: metal">
        <td colspan="3">
            <div class="col-md-6 col-xs-6" align="right">ភេទ
                <strong style="padding-right: 30px;">
                    {{$income->payslipClient->student->student->gender->name_kh}}
                </strong>
            </div>
            <div class="col-md-6 col-xs-6" style="padding-left: 0px;">ថ្ងែខែឆ្នាំកំណើត
                <strong class="dob">
                    <?php
                        $dob =  \Carbon\Carbon::createFromFormat('Y-m-d h:i:s',$income->payslipClient->student->student->dob);
                    ?>
                    {{$dob->format('d/m/Y')}}
                </strong>
            </div>
        </td>
    </tr>
    <tr style="font-family: metal">
        <td colspan="3">
            <span class="col-md-3 col-xs-3">ចំនួនទឺកប្រាក់បានបង់</span>
            <span class="col-md-3 col-xs-3">
                @if($income->amount_riel == '' || $income->amount_riel == null)
                    {{$income->amount_dollar}} $
                @else
                    {{$income->amount_riel}} ៛
                @endif

            </span>
            <span class="col-md-3 col-xs-3">ជាអក្សរ</span>
            <span class="col-md-3 col-xs-3">
                @if($income->amount_dollar_kh == '' || $income->amount_dollar_kh == null)
                    {{$income->amount_dollar_kh}}
                @else
                    {{$income->amount_riel_kh}}
                @endif
            </span>
        </td>
    </tr>
    <tr style="font-family: metal">
        <td colspan="3">
            <span class="col-md-3 col-xs-3">ចំនួនទឺកប្រាក់នៅជំពាក់</span>
            <span class="col-md-3 col-xs-3">
                @if($income->amount_riel == '' || $income->amount_riel == null)
                    {{$income->amount_dollar}} $
                @else
                    {{$income->amount_riel}} ៛
                @endif

            </span>
            <span class="col-md-3 col-xs-3">ជាអក្សរ</span>
            <span class="col-md-3 col-xs-3">
                @if($income->amount_dollar_kh == '' || $income->amount_dollar_kh == null)
                    {{$income->amount_dollar_kh}}
                @else
                    {{$income->amount_riel_kh}}
                @endif
            </span>
        </td>
    </tr>
    <tr style="font-family: metal">
        <td colspan="3">
            <div class="col-md-12 col-xs-12">
                ជានិស្សិតដេប៉ាតឺម៉ង់
                <strong>
                    {{$income->payslipClient->student->department->name_kh}}

                </strong> ឆ្នាំទី
                <strong>
                    {{$income->payslipClient->student->grade->name_kh}}

                </strong>
            </div>
        </td>
    </tr>
    <tr style="font-family: metal">
        <td colspan="3">
            <div class="col-md-12 col-xs-12">
                ថ្នាក់រៀន
                <strong>
                    Ingenieur
                </strong> ជំនាន់/វគ្គ
                <strong id="candidate_promotion">
                    {{$income->payslipClient->student->promotion->name}}
                </strong> បង់លើកទី
                <strong class="sequence">
                    {{$income->sequence}}
                </strong> ឆ្នាំសិក្សា
                <strong id="candidate_academic_year">
                    {{$income->payslipClient->student->academic_year->name_kh}}
                </strong>
            </div>
        </td>
    </tr>
    <tr style="font-family: metal;">
        <td colspan="3" align="right">
            <div class="col-md-6 col-xs-6"></div>
            <div class="col-md-6 col-xs-6" align="center" style="padding-top: 15px">
                រាជធានីភ្នំពេញ, <strong class="current_date">​២៥​ សីហា ២០១៥​ </strong><br/>
                អ្នកទទួលប្រាក់
            </div>
        </td>
    </tr>
    <tr style="font-family: kh-bokor">
        <td colspan="3" align="left">
            <div class="col-md-12 col-xs-12" style="padding-top: 60px">
                <span style="text-decoration: underline">សំគាល់: </span>
                និស្សិតដែលបង់ថ្លៃសិក្សារួចហើយមិនអាចដក់ប្រាក់វិញបានទេ ។
            </div>
        </td>
    </tr>

</table>