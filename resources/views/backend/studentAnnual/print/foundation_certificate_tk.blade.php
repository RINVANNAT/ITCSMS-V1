@extends('backend.layouts.printing_landscape_a4_foundation_certificate')

@section('title', 'ITC-SMS | ' . trans('messages.foundation_certificate'))

@section('after-styles-end')
    <style>
        .transcript-footer {
            position: absolute;
            bottom: 18px;
        }
        .sub-content-margin-top-en {
            /*default: 50px;*/
            margin-top: 15px !important;
        }
        .sub-content-margin-top-kh {
            /*default: 50px;*/
            margin-top: 30px !important;
        }
        .oriented {
            height: 30px;
        }
    </style>
@endsection

@section('content')
    @foreach($students as $student)
        <?php
        $gpa = "";
        $student_pass = true;
        if ($transcript_type == "semester1") {
            $last_score = $scores[$student['id']]["final_score_s1"];
            $gpa = get_gpa($last_score, $passedScoreI);
        } else {
            $last_score = $scores[$student['id']]["final_score"];
            $gpa = get_gpa($last_score, $passedScoreII);
        }

        foreach ($scores[$student['id']] as $key => $score) {
            if (is_numeric($key)) {
                if (intval($score["score"]) < 30 && intval($score["resit"]) < 30) {
                    $student_pass = false;
                }
            }
        }
        ?>
        @if($student_pass && ($is_front == 'true'))
            <div class="page">
                <div class="row">
                    <div class="col-md-6 col-xs-6">
                        <div class="border margin-right-5" align="center" style="padding: 10mm 10mm 10mm 3mm">
                            <h4 class="h2_khmer_title">ព្រឹត្តិបត្រពិន្ទុ</h4>
                            <h3 class="text-bold english_section" style="font-size: 20px">Academic Transcript</h3>
                            <div class="row">
                                <div class="col-md-8 english_section">
                                    <span class="left">Name: <b>{{strtoupper($student['name_latin'])}}</b></span>
                                </div>
                                <div class="col-md-4 english_section">
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
                                        @if((strtolower($score['name_en']) != "technical drawing") && (strtolower($score['name_en']) != "computer for engineering") && (strtolower($score['name_en']) != "informatic") && (strtolower($score['name_fr']) != "informatique"))
                                            <tr>
                                                <td style="text-align: left;width: 35%">{{isset($score['name_kh'])?$score['name_kh']:""}}</td>
                                                <td class="english_section"
                                                    style="text-align: left;width: 35%">{{isset($score['name_en'])?$score['name_en']:""}}</td>
                                                <td class="english_section"
                                                    style="text-align: center;width: 15%">{{ $score["credit"] }}</td>
                                                <?php
                                                if ($transcript_type == "semester1") {
                                                    $grade = get_grading($score["score"], $passedScoreI);
                                                } else {
                                                    $grade = get_grading($score["score"], $passedScoreII);
                                                }
                                                ?>
                                                <td class="col-right english_section"
                                                    style="text-align:center; width: 15%;">{!! $grade !!}</td>
                                            </tr>
                                        @endif
                                    @endif
                                @endforeach
                                <tr class="oriented" valign="bottom">
                                    <th>មុខវិជ្ជាតម្រង់ទិស</th>
                                    <th class="english_section" colspan="3">Oriented Subjects</th>
                                </tr>
                                @foreach($scores[$student['id']] as $key => $score)
                                    @if(is_numeric($key))
                                        @if(strtolower($score['name_en']) == "technical drawing" || strtolower($score['name_en']) == "computer for engineering" || strtolower($score['name_en']) == "informatic" || strtolower($score['name_fr']) == "informatique")
                                            <tr valign="bottom">
                                                <td style="text-align: left;width: 35%">{{isset($score['name_kh'])?$score['name_kh']:""}}</td>
                                                <td class="english_section"
                                                    style="text-align: left;width: 35%">{{isset($score['name_en'])?$score['name_en']:""}}</td>
                                                <td class="english_section"
                                                    style="text-align: center;width: 15%">{{ $score["credit"] }}</td>
                                                <?php
                                                if ($transcript_type == "semester1") {
                                                    $grade = get_grading($score["score"], $passedScoreI);
                                                } else {
                                                    $grade = get_grading($score["score"], $passedScoreII);
                                                }
                                                ?>
                                                <td class="col-right english_section"
                                                    style="text-align:center; width: 15%;">{!! $grade !!}</td>
                                            </tr>
                                        @endif
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                            <div align="left">
                                <div class="gpa">
                                    <h4><b>GPA: {{$gpa}}</b></h4>
                                </div>
                                <div class="transcript-footer">
                                    <div class="grading-system">
                                        <h5><b>Note:</b></h5>
                                        <table style="margin-left: 18px" width="100%">
                                            <tr>
                                                <td class="english_section">A &nbsp;= 85% -100% = <span
                                                            class="khmer_section">ល្អប្រសើរ</span></td>
                                                <td class="english_section">C &nbsp;= 50% - 64% = <span
                                                            class="khmer_section">មធ្យម</span></td>
                                            </tr>
                                            <tr>
                                                <td class="english_section">B<sup>+</sup> = 80% - 84% = <span
                                                            class="khmer_section">ល្អណាស់</span></td>
                                                <td class="english_section">D &nbsp;= 45% - 49% = <span
                                                            class="khmer_section">ខ្សោយ</span></td>
                                            </tr>
                                            <tr>
                                                <td class="english_section">B &nbsp;= 70% - 79% = <span
                                                            class="khmer_section">ល្អ</span></td>
                                                <td class="english_section">E &nbsp;= 40% - 44% = <span
                                                            class="khmer_section">ខ្សោយណាស់</span></td>
                                            </tr>
                                            <tr>
                                                <td class="english_section">C<sup>+</sup> = 65% - 69% = <span
                                                            class="khmer_section">ល្អបង្គួរ</span></td>
                                                <td class="english_section">F &nbsp;= < 40% = <span
                                                            class="khmer_section">ធ្លាក់</span></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="remark" style="margin-top: 7px">
                                        <span class="smallest_text"><b>*</b> <span>វិញ្ញាបនបត្រនេះមិនចេញជូនជាលើកទី២ទេ។</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-6">
                        <div class="border margin-left-5">

                            <div class="row" style="margin-top: 85mm">
                                <div class="col-md-12 margin-top-40">
                                    <h3 class="text-center text-bold">{{$student['academic_year_latin']}}</h3>
                                </div>
                            </div>
                            <?php
                            $now = \Carbon\Carbon::now();
                            ?>
                            <p class="bottom-footer">លេខ: គ.ទ.ក
                                ០២១៧១៣០០៦/ {{to_khmer_number($ranking_data[$student['id_card']]->Rank)}}
                                /{{to_khmer_number(substr($now->year,-2))}}/<span class="text-bold">វ.ប.ក</span></p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
    @foreach($students->reverse() as $student)
        <?php
        $gpa = "";
        $student_pass = true;
        if ($transcript_type == "semester1") {
            $last_score = $scores[$student['id']]["final_score_s1"];
            $gpa = get_gpa($last_score, $passedScoreI);
        } else {
            $last_score = $scores[$student['id']]["final_score"];
            $gpa = get_gpa($last_score, $passedScoreII);
        }

        foreach ($scores[$student['id']] as $key => $score) {
            if (is_numeric($key)) {
                if (intval($score["score"]) < 30 && intval($score["resit"]) < 30) {
                    $student_pass = false;
                }
            }
        }
        ?>

        @if($student_pass && ($is_back == 'true'))
            <div class="page">
                <div class="row">
                    <div class="col-md-6 col-xs-6">
                        <div class="khmer_section" style="padding: 10mm 10mm 10mm 3mm; font-family: khmer_s1 !important">
                            <p class="text-center h2_khmer_title"><strong>នាយក</strong></p>
                            <p class="tran-header text-center">បញ្ជាក់ថា​ ៖</p>
                            <div class="row" style="margin-top: 10mm">
                                <div class="col-md-6">
                                    <p class="left">និស្សិតឈ្មោះ៖ <strong>{{$student['name_kh']}}</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="right" style="margin-right: 9px">ភេទ៖ {{to_khmer_gender($student['gender'])}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    $dob = \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $student['dob']);
                                    $dob_y = $dob->year;
                                    $dob_m = $dob->month;
                                    $dob_d = $dob->day;
                                    ?>
                                    <p class="left">
                                        ថ្ងៃខែឆ្នាំកំណើត៖ {{to_khmer_number($dob_d)}} {{to_khmer_month($dob_m)}} {{to_khmer_number($dob_y)}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="tran-content line-height">
                                        បានបញ្ចប់ថ្នាក់ឆ្នាំសិក្សាមូលដ្ឋានដោយជោគជ័យនៅ វិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា (សាខាត្បូងឃ្មុំ)
                                        ក្នុងឆ្នាំសិក្សា {{$student['academic_year_kh']}}។
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="line-height sub-content-margin-top-kh" style="margin-top: 45px !important;">
                                        វិញ្ញាបនបត្រនេះ ប្រគល់ជូនសាមីជនដើម្បីយកទៅប្រើប្រាស់តាមការដែលអាចប្រើបាន។
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-xs-4">
                                    @if($photo == 'true')
                                        <img class="photo"
                                             src="{{$smis_server->value}}/img/profiles/{{$student['photo']}}">
                                    @else
                                        <div class="photo"></div>
                                    @endif
                                </div>
                                <div class="col-md-8 col-xs-8">
                                    <div align="center" style="margin-top: 7mm">
                                        <p>
                                            <?php
                                            $d = \Carbon\Carbon::createFromFormat("d/m/Y", $issued_date);
                                            $issue_d = $d->day;
                                            $issue_m = $d->month;
                                            $issue_y = $d->year;
                                            ?>
                                            រាជធានីភ្នំពេញ ថ្ងៃទី{{to_khmer_number($issue_d)}}
                                            ខែ{{to_khmer_month($issue_m)}} ឆ្នាំ{{to_khmer_number($issue_y)}}
                                        </p>
                                        <p style="font-family: 'khmer_m2' !important;">
                                            ជ.នាយកវិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា
                                        </p>
                                        <p style="font-family: 'khmer_m2' !important;">
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
                            <div class="row" style="margin-top: 10mm">
                                <div class="col-md-8">
                                    <p class="left">Name: <strong>{{strtoupper($student['name_latin'])}}</strong></p>
                                </div>
                                <div class="col-md-4">
                                    <p class="right">Sex: {{strtolower($student['gender'])=="m"?"Male":"Female"}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="left" style="margin-top: -2mm">Born
                                        on {{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$student['dob'])->formatLocalized('%d %B %Y')}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="line-height" style="margin-top: 25px; line-height: 1.5 !important;">
                                        has successfully completed Foundation Year Course at Institute of Technology of
                                        Cambodia (Tbong Khmum Branch) in
                                        academic year {{$student['academic_year_latin']}}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="line-height tran-last-content" style="margin-top: 19px; line-height: 1.5 !important;">
                                        This certificate is presented to the bearer with all rights and privileges
                                        thereto
                                        pertaining.
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div align="center">
                                        <div>Issued at Phnom
                                            Penh, {{\Carbon\Carbon::createFromFormat("d/m/Y",$issued_date)->formatLocalized('%d %B %Y')}}</div>
                                        <div style="margin-top: 1mm">For Director General of ITC</div>
                                        <div style="margin-top: 1mm">Deputy Director</div>
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
