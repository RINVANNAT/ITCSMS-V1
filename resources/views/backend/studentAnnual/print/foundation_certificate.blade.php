@extends('backend.layouts.printing_landscape_a4_foundation_certificate')
@section('before')
@section('title')
    ITC-SMS | {{trans('messages.foundation_certificate')}}
@stop
@section('before-styles-end' )
@endsection


@section('content')
    @foreach($students as $student)
    <?php
        $student_pass = true;
        if($transcript_type == "semester1") {
            $last_score = $scores[$student['id']]["final_score_s1"];
        } else {
            $last_score = $scores[$student['id']]["final_score"];
        }

        $gpa = "";
        if($last_score >= 85){
            $gpa = "4.0";
        } else if ($last_score >= 80) {
            $gpa = "3.5";
        } else if ($last_score >= 70) {
            $gpa = "3.0";
        } else if ($last_score >= 65) {
            $gpa = "2.5";
        } else if ($last_score >= 50) {
            $gpa = "2.0";
        } else if ($last_score >= 45) {
            $student_pass = false;
            $gpa = "1.5";
        } else if ($last_score >= 40) {
            $student_pass = false;
            $gpa = "1.0";
        }  else {
            $student_pass = false;
            $gpa = "0.0";
        }
        foreach ($scores[$student['id']] as $key => $score) {
            if(is_numeric($key)) {
                if(intval($score["score"]) < 30) {
                    $student_pass = false;
                }
            }
        }
    ?>
    @if($student_pass)
    <div class="page">
        <div class="row">
            <div class="col-md-6 col-xs-6">
                <div class="border margin-right-5" align="center">
                    <h4 class="h2-title">ព្រឹត្តិបត្រពិន្ទុ</h4>
                    <h3 class="text-bold english_section" style="font-size: 20px">Academic Transcript</h3>
                    <div class="row">
                        <div class="col-md-6 english_section">
                            <span class="left">Name: <b>{{strtoupper($student['name_latin'])}}</b></span>
                        </div>
                        <div class="col-md-6 english_section">
                            <span class="right" style="margin-right: 5mm">ID: {{$student['id_card']}}</span>
                        </div>
                    </div>
                    <table width="100%" style="margin-top: 3mm;">
                        <thead>
                        <tr>
                            <th class="text_left">មុខវិជ្ជាទូទៅ</th>
                            <th class="text_left english_section">General Subjects</th>
                            <th class="text_center english_section">Credits</th>
                            <th class="text_center english_section">Grades</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($scores[$student['id']] as $key => $score)
                            @if(is_numeric($key))
                                @if(strtolower($score['name_en']) != "technical drawing" && strtolower($score['name_en']) != "computer for engineering")
                                    <tr>
                                        <td style="text-align: left;width: 35%">{{isset($score['name_kh'])?$score['name_kh']:""}}</td>
                                        <td class="english_section" style="text-align: left;width: 35%">{{isset($score['name_en'])?$score['name_en']:""}}</td>
                                        <td class="english_section" style="text-align: center;width: 15%">{{ $score["credit"] }}</td>
                                        <?php
                                        $grade = get_grading($score["score"]);
                                        ?>
                                        <td class="col-right english_section" style="text-align:center; width: 15%;">{!! $grade !!}</td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach
                        <tr>
                            <th>មុខវិជ្ជាតម្រង់ទិស</th>
                            <th class="english_section" colspan="3">Oriented Subjects</th>
                        </tr>
                        @foreach($scores[$student['id']] as $key => $score)
                            @if(is_numeric($key))
                                @if(strtolower($score['name_en']) == "technical drawing" || strtolower($score['name_en']) == "computer for engineering" || strtolower($score['name_en']) == "informatic")
                                    <tr>
                                        <td style="text-align: left;width: 35%">{{isset($score['name_kh'])?$score['name_kh']:""}}</td>
                                        <td class="english_section" style="text-align: left;width: 35%">{{isset($score['name_en'])?$score['name_en']:""}}</td>
                                        <td class="english_section" style="text-align: center;width: 15%">{{ $score["credit"] }}</td>
                                        <?php
                                        $grade = get_grading($score["score"]);
                                        ?>
                                        <td class="col-right english_section" style="text-align:center; width: 15%;">{!! $grade !!}</td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                    <div align="left">
                        <div class="gpa">
                            <h4> <b>GPA: {{$gpa}}</b></h4>
                        </div>
                        <div class="transcript-footer">
                            <div class="grading-system">
                                <h5><b>Note:</b></h5>
                                <table style="margin-left: 18px" width="100%">
                                    <tr>
                                        <td>A &nbsp;= 85% -100% = ល្អប្រសើរ</td>
                                        <td>C &nbsp;= 50% - 64% = មធ្យម</td>
                                    </tr>
                                    <tr>
                                        <td>B<sup>+</sup> = 80% - 84% = ល្អណាស់</td>
                                        <td>D &nbsp;= 45% - 49% = ខ្សោយ</td>
                                    </tr>
                                    <tr>
                                        <td>B &nbsp;= 70% - 79% = ល្អ</td>
                                        <td>E &nbsp;= 40% - 44% = ខ្សោយណាស់</td>
                                    </tr>
                                    <tr>
                                        <td>C<sup>+</sup> = 65% - 69% = ល្អបង្គួរ</td>
                                        <td>F &nbsp;= < 40% = ធ្លាក់</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="remark">
                                <span class="smallest_text"><b>*</b> <span>វិញ្ញាបនបត្រនេះមិនចេញជូនជាលើកទី២ទេ។</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-6">
                <div class="border margin-left-5">

                    <div class="row" style="margin-top: 90mm">
                        <div class="col-md-12 margin-top-40">
                            <h3 class="text-center text-bold">{{$student['academic_year_latin']}}</h3>
                        </div>
                    </div>
                    <p class="bottom-footer">លេខ: គ.ទ.ក ០២១៧១៣០០៦ {{to_khmer_number($ranking_data[$student['id_card']]->Rank)}}/{{to_khmer_number(substr($student['academic_year_id'],-2))}}/<span class="text-bold">វ.ប.ក</span></p>
                </div>
            </div>
        </div>
    </div>
    <div class="page">
        <div class="row">
            <div class="col-md-6 col-xs-6">
                <div class="border margin-right-5 khmer_section">
                    <p class="text-center h1_khmer_title"><strong>នាយក</strong></p>
                    <p class="tran-header text-center">បញ្ជាក់ថា​ :</p>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="left">និស្សិតឈ្មោះ: <strong>{{$student['name_kh']}}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="right">ភេទ: {{to_khmer_gender($student['gender'])}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            $dob = \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$student['dob']);
                            $dob_y = $dob->year;
                            $dob_m = $dob->month;
                            $dob_d = $dob->day;
                            ?>
                            <p class="left">ថ្ងៃខែឆ្នាំកំណើត: {{to_khmer_number($dob_d)}} ខែ {{to_khmer_month($dob_m)}} ឆ្នាំ {{to_khmer_number($dob_y)}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="tran-content line-height">
                                បានបញ្ចប់ថ្នាក់ឆ្នាំសិក្សាមូលដ្ឋានដោយជោគជ័យនៅ វិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា
                                ក្នុងឆ្នាំសិក្សា {{$student['academic_year_kh']}}។
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="tran-last-content line-height">
                                វិញ្ញាបនបត្រនេះ ប្រគល់ជូនសាមីជនដើម្បីយកទៅប្រើប្រាស់តាមការដែលអាចប្រើបាន។
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-4">
                            <img class="photo" src="{{$smis_server->value}}/img/profiles/{{$student['photo']}}">
                        </div>
                        <div class="col-md-8 col-xs-8">
                            <div align="center" style="margin-top: 5mm">
                                <p>
                                    <?php
                                    $d = \Carbon\Carbon::createFromFormat("d/m/Y", $issued_date);
                                    $issue_d = $d->day;
                                    $issue_m = $d->month;
                                    $issue_y = $d->year;
                                    ?>
                                    រាជធានីភ្នំពេញ ថ្ងៃទី{{to_khmer_number($issue_d)}} ខែ{{to_khmer_month($issue_m)}} ឆ្នាំ{{to_khmer_number($issue_y)}}
                                </p>
                                <p>
                                    ជ. នាយកវិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា
                                </p>
                                <p>
                                    នាយករង
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-6">
                <div class="border margin-left-5 english_section">
                    <p align="center"><strong>DIRECTOR</strong></p>
                    <p class="tran-header text-center">Certifies that</p>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="left">Name: <strong>{{strtoupper($student['name_latin'])}}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="right">Sex: {{strtolower($student['gender'])=="m"?"Male":"Female"}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="left">Born on {{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$student['dob'])->formatLocalized('%d %B %Y')}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="line-height tran-content">
                                has successfully completed Foundation Year Course at Institute of Technology of Cambodia in
                                academic year {{$student['academic_year_latin']}}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="line-height tran-last-content">
                                This certificate is presented to the bearer with all rights and privileges thereto
                                pertaining.
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div align="center">
                                <p>Issued at Phnom Penh, {{\Carbon\Carbon::createFromFormat("d/m/Y",$issued_date)->formatLocalized('%d %B %Y')}}</p>
                                <p>For Director General</p>
                                <p>Deputy Director</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endforeach
@endsection

@section('scripts')
    <script>

    </script>
@endsection
