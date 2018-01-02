@extends('backend.layouts.printing_landscape_a4_foundation_certificate')
@section('before')
@section('title')
    ITC-SMS | {{trans('messages.foundation_certificate')}}
@stop

@section('before-styles-end' )
    <style>

        /*Register new font-face*/
        @font-face {
            font-family: khmer_m1;
            src: url("{{ asset('assets/fonts/khmer M1.volt.ttf') }}");
        }

        @font-face {
            font-family: khmer_m2;
            src: url("{{ asset('assets/fonts/khmer M2.volt.ttf') }}");
        }

        @font-face {
            font-family: khmer_niroth;
            src: url("{{ asset('assets/fonts/KhmerOSniroth.ttf') }}");
        }

        .right {
            float: right;
        }
        .left {
            float: left;
        }
        .text_right {
            text-align: right;
        }
        .text_left {
            text-align: left;
        }
        .text_center {
            text-align: center;
        }
        .smallest_text {
            font-size: 11pt;
        }
        /*.border {
            border: 2px solid black;
            height: 190mm;
            padding: 5mm;
        }*/

        .border {
            border: 2px solid black;
            height: 190mm;
            padding: 2px;
        }

        .sub-border {
            border: 1px solid black;
            height: 188mm;
            padding: 5mm;
        }

        img {
            width: 100px;
            height: auto;
            position: absolute;
            top: 30px;
            left: 90px;
        }

        .main-title {
            font-family: khmer_m1;
        }
        .simple-title {
            font-family: khmer_m2;
        }
        .branch_title {
            font-family: khmer_niroth;
        }
        .margin-top-40 {
            margin-top: 40px;
        }
        .bottom-footer {
            position: absolute;
            left: 60px;
            bottom: 10px;
        }

        /*slide2*/
        .tran-content{
            margin-top: 30px;
        }
        .tran-last-content{
            margin-top: 50px;
        }
        .tran-header {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .line-height{
            line-height: 1.8;
        }
        .photo {
            border-bottom: 1px solid;
            border-top: 1px solid;
            border-left: 1px solid;
            width: 150px;
            height: 180px;
            border-right: 1px solid;
            margin-top: 15px;
        }

        .margin-left-5 {
            margin-left: 5mm;
        }

        .margin-right-5 {
            margin-right: 5mm;
        }
    </style>
@endsection

@section('content')
    @foreach($students as $student)
    <div class="page">
        <div class="row">
            <div class="col-md-6">
                <div class="border margin-right-5" align="center">
                    <div class="sub-border">
                        <h4 class="main-title">ព្រឹត្តិបត្រពិន្ទុ</h4>
                        <h3 class="text-bold">Academic Transcript</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <span class="left">Name: {{strtoupper($student['name_latin'])}}</span>
                            </div>
                            <div class="col-md-6">
                                <span class="right">ID: {{$student['id_card']}}</span>
                            </div>
                        </div>
                        <table width="100%">
                            <thead>
                            <tr>
                                <th class="text_left">មុខវិជ្ជាទូទៅ</th>
                                <th class="text_left">General Subjects</th>
                                <th class="text_center">Credits</th>
                                <th class="text_center">Grades</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1 ?>
                            @foreach($scores[$student['id']] as $key => $score)
                                @if(is_numeric($key))
                                    <tr>
                                        <td style="text-align: left;width: 35%">{{isset($score['name_kh'])?$score['name_kh']:""}}</td>
                                        <td style="text-align: left;width: 35%">{{isset($score['name_en'])?$score['name_en']:""}}</td>
                                        <td style="text-align: center;width: 15%">{{ $score["credit"] }}</td>
                                        <?php
                                        $grade = "";
                                        if($score["score"] >= 85){
                                            $grade = "A";
                                        } else if ($score["score"] >= 80) {
                                            $grade = "B<sup>+</sup>";
                                        } else if ($score["score"] >= 70) {
                                            $grade = "B";
                                        } else if ($score["score"] >= 65) {
                                            $grade = "C<sup>+</sup>";
                                        } else if ($score["score"] >= 50) {
                                            $grade = "C";
                                        } else if ($score["score"] >= 45) {
                                            $grade = "D";
                                        } else if ($score["score"] >= 40) {
                                            $grade = "E";
                                        }  else {
                                            $grade = "F";
                                        }

                                        ?>
                                        <td class="col-right" style="text-align:center; width: 15%;">{!! $grade !!}</td>
                                    </tr>
                                    <?php $i++ ?>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                        <div align="left">
                            <div class="gpa">
                                <?php
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
                                    $gpa = "1.5";
                                } else if ($last_score >= 40) {
                                    $gpa = "1.0";
                                }  else {

                                    $gpa = "0.0";
                                }
                                ?>
                                <h4> <b>GPA: {{$gpa}}</b></h4>
                            </div>
                            <div class="transcript-footer">
                                <div class="grading-system">
                                    <h5><b>Note:</b></h5>
                                    <table style="margin-left: 18px" width="100%">
                                        <tr>
                                            <td>A = 85% -100% = ល្អប្រសើរ</td>
                                            <td>C = 50% - 64% = មធ្យម</td>
                                        </tr>
                                        <tr>
                                            <td>B<sup>+</sup> = 80% - 84% = ល្អណាស់</td>
                                            <td>D = 45% - 49% = ខ្សោយ</td>
                                        </tr>
                                        <tr>
                                            <td>B = 70% - 79% = ល្អ</td>
                                            <td>E = 40% - 44% = ខ្សោយណាស់</td>
                                        </tr>
                                        <tr>
                                            <td>C<sup>+</sup> = 65% - 69% = ល្អបង្គួរ</td>
                                            <td>F = < 40% = ធ្លាក់</td>
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
            </div>
            <div class="col-md-6">
                <div class="border margin-left-5">
                    <div class="sub-border">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="header-right">
                                    <div class="pull-right">
                                        <h4 class="text-center simple-title">ព្រះរាជាណាចក្រកម្ពុជា</h4>
                                        <h4 class="text-center">KINGDOM OF CAMBODIA</h4>
                                        <h4 class="text-center simple-title">ជាតិ សាសនា​ ព្រះមហាក្សត្រ</h4>
                                        <h4 class="text-center">Nation Religion King</h4>
                                        <p class="text-center" style="font-family: tactieng;font-size: 40pt;">7</p>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="header-left">
                                    <div class="pull-left">
                                        <p class="text-center"><img src="{{ asset('img/ITC_Logo.png') }}"/></p>
                                        <h4 class="text-center branch_title">វិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា</h4>
                                        <h4 class="text-center">Institute of Technology of Cambodia</h4>​
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 margin-top-40">
                                <h3 class="text-center main-title">វិញ្ញាបនបត្រថ្នាក់ឆ្នាំសិក្សាមូលដ្ឋាន</h3>
                                <h3 class="text-center text-bold">Certificate of Foundation Year Course</h3>
                                <h3 class="text-center text-bold">2015 - 2016</h3>
                            </div>
                        </div>

                        <p class="bottom-footer">លេខ: គ.ទ.ក ០២១៧១៣០០៦ ៧២៤/១៦/<span class="text-bold">វ.ប.ក</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page">
        <div class="row">
            <div class="col-md-6">
                <div class="border margin-right-5">
                    <div class="sub-border">
                        <h5 class="text-center main-title"><strong>នាយក</strong></h5>
                        <h4 class="tran-header text-center">បញ្ជាក់ថា​ :</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="left">និស្សិតឈ្មោះ: <strong>រិទ្ធិ បញ្ញា</strong></h4>
                            </div>
                            <div class="col-md-6">
                                <h4 class="right">ភេទ: ប្រុស</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="left">ថ្ងៃខែឆ្នាំកំណើត: ១១ ខែសីហា ១៩៩៨</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="tran-content line-height">
                                    បានបញ្ចប់ថ្នាក់ឆ្នាំសិក្សាមូលដ្ឋានដោយជោគជ័យនៅ វិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា
                                    ក្នុងឆ្នាំសិក្សា ២០១៥_២០១៦។
                                </h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="tran-last-content line-height">
                                    វិញ្ញាបនបត្រនេះ ប្រគល់ជូនសាមីជនដើម្បីយកទៅប្រើប្រាស់តាមការដែលអាចប្រើបាន។
                                </h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="photo"></div>
                            </div>
                            <div class="col-md-8">
                                <div align="center">
                                    <h4>
                                        រាជធានីភ្នំពេញ ថ្ងៃទី១៥ ខែកញ្ញា ឆ្នាំ២០១៦
                                    </h4>
                                    <h4>
                                        ជ. នាយកវិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា
                                    </h4>
                                    <h4>
                                        នាយករង
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border margin-left-5">
                    <div class="sub-border">
                        <h4 align="center"><strong>DIRECTOR</strong></h4>
                        <h4 class="tran-header text-center">Certifies that</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="left">Name: <strong>ANG MALYNET</strong></h4>
                            </div>
                            <div class="col-md-6">
                                <h4 class="right">Sex: Male</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="left">Born: on 11 August 1998</h4>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="line-height tran-content">
                                    has successfully completed Foundation Year Course at Institute of Technology of Cambodia in
                                    academic year 2015-2016
                                </h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="line-height tran-last-content">
                                    This certificate is presented to ther bearer with all rights and privileges thereto
                                    pertaining.
                                </h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div align="center">
                                    <h4>Issued at Phnom Penh, 15 September 2016</h4>
                                    <h4>For Director General</h4>
                                    <h4>Deputy Director</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endforeach
@endsection

@section('scripts')
    <script>

    </script>
@endsection
