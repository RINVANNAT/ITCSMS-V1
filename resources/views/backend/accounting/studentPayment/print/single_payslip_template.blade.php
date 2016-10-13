<table style="width:100%; height:70mm;border: 1px solid #000000;text-align: left;vertical-align: middle;" rules="none"
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

    <tr style="font-family: metal">
        <td colspan="3">
            <div class="col-md-6 col-xs-6"> នាមត្រកូល និង នាមខ្លួន
                <strong>
                    @if(isset($income->payslipClient->student))
                        {{$income->payslipClient->student->student->name_kh}}
                    @else
                        {{$income->payslipClient->candidate->name_kh}}
                    @endif
                </strong>
            </div>
            <div class="col-md-6 col-xs-6"> អក្សរឡាតាំង
                <strong>
                    @if(isset($income->payslipClient->student))
                        {{strtoupper($income->payslipClient->student->student->name_latin)}}
                    @else
                        {{strtoupper($income->payslipClient->candidate->name_latin)}}
                    @endif
                </strong>
            </div>
        </td>
    </tr>
    <tr style="font-family: metal">
        <td colspan="3">
            <div class="col-md-6 col-xs-6" align="right">ភេទ
                <strong style="padding-right: 30px;">
                    @if(isset($income->payslipClient->student))
                        {{strtoupper($income->payslipClient->student->student->gender->name_kh)}}
                    @else
                        {{strtoupper($income->payslipClient->candidate->gender->name_kh)}}
                    @endif
                </strong>
            </div>
            <div class="col-md-6 col-xs-6" style="padding-left: 0px;">ថ្ងែខែឆ្នាំកំណើត
                <strong class="dob">
                    <?php
                        if(isset($income->payslipClient->student)){
                            $dob =  \Carbon\Carbon::createFromFormat('Y-m-d h:i:s',$income->payslipClient->student->student->dob);
                        } else {
                            $dob =  \Carbon\Carbon::createFromFormat('Y-m-d h:i:s',$income->payslipClient->candidate->dob);
                        }
                    ?>
                    {{$dob->format('d/m/Y')}}
                </strong>
            </div>
        </td>
    </tr>
    <tr style="font-family: metal">
        <td colspan="3">
            <span class="col-md-3 col-xs-3">ចំនួនទឺកប្រាក់</span>
            <b class="col-md-3 col-xs-3">
                @if($income->amount_riel == '' || $income->amount_riel == null)
                    {{$income->amount_dollar}} $
                @else
                    {{$income->amount_riel}} ៛
                @endif

            </b>
            <span class="col-md-3 col-xs-3">ជាអក្សរ</span>
            <b class="col-md-3 col-xs-3 amount_kh">

            </b>
        </td>
    </tr>
    <tr style="font-family: metal">
        <td colspan="3">
            <span class="col-md-3 col-xs-3">នៅជំពាក់</span>
            <span class="col-md-3 col-xs-3">
                <b>{{$debt}}</b>
            </span>
        </td>
    </tr>
    <tr style="font-family: metal">
        <td colspan="3">
            <div class="col-md-12 col-xs-12">
                ជានិស្សិតដេប៉ាតឺម៉ង់
                <strong class="department_name">
                    @if(isset($income->payslipClient->student))
                        {{$income->payslipClient->student->department->name_kh}}
                    @else
                        @if(isset($income->payslipClient->candidate->department))
                            {{$income->payslipClient->candidate->department->name_kh}}
                        @endif
                    @endif

                </strong>
                <strong>
                    @if(isset($income->payslipClient->student))
                        {{$income->payslipClient->student->grade->name_kh}}
                    @else
                        {{$income->payslipClient->candidate->grade->name_kh}}
                    @endif

                </strong>
            </div>
        </td>
    </tr>
    <tr style="font-family: metal">
        <td colspan="3">
            <div class="col-md-12 col-xs-12">
                ថ្នាក់រៀន
                <strong>
                    {{$candidate->degree_name}}
                </strong> ជំនាន់/វគ្គ
                <strong id="candidate_promotion">
                    @if(isset($income->payslipClient->student))
                        {{$income->payslipClient->student->promotion->name}}
                    @else
                        {{$income->payslipClient->candidate->promotion->name}}
                    @endif
                </strong> បង់លើកទី
                <strong class="sequence">
                    {{$income->sequence}}
                </strong> ឆ្នាំសិក្សា
                <strong id="candidate_academic_year">
                    @if(isset($income->payslipClient->student))
                        {{$income->payslipClient->student->academic_year->name_kh}}
                    @else
                        {{$income->payslipClient->candidate->academic_year->name_kh}}
                    @endif
                </strong>
            </div>
        </td>
    </tr>
    <tr style="font-family: metal;">
        <td colspan="3" align="right">
            <div class="col-md-4 col-xs-4"></div>
            <div class="col-md-8 col-xs-8" align="center" style="padding-top: 15px">
                រាជធានីភ្នំពេញ, <strong class="current_date">​ </strong><br/>
                អ្នកទទួលប្រាក់
            </div>
        </td>
    </tr>
    <tr style="font-family: kh-bokor">
        <td colspan="3" align="left">
            <div class="col-md-12 col-xs-12" style="padding-top: 10px">
                <span style="text-decoration: underline">សំគាល់: </span>
                និស្សិតដែលបង់ថ្លៃសិក្សារួចហើយមិនអាចដកប្រាក់វិញបានទេ ។
            </div>
        </td>
    </tr>

</table>