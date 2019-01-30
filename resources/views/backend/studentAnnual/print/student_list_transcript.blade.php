<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="X2IQMvdghRfpLlHKugb0MK9lDoeaeSpbxQpH2Oq9"/>

    <title>
        ITC-SMS | Student List
    </title>

    <!-- Meta -->
    <meta name="description" content="Printing Attestation">
    <meta name="author" content="Department Information and Communication Engineering">

    <!-- Styles -->
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('css/student_transcript.css') }}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('css/backend/prints/prints.css') }}">

    <style>
        @font-face {
            font-family: times_new_roman_normal;
            src: url("{{ asset('fonts/TIMES.TTF') }}");
        }

        @font-face {
            font-family: times_new_roman_normal_bold;
            src: url("{{ asset('fonts/Times_New_Roman_Bold.ttf') }}");
        }

        @font-face {
            font-family: khmer_m2;
            src: url("{{ url('assets/fonts/khmer M2.volt.ttf') }}");
        }

        @font-face {
            font-family: khmer_s1;
            src: url("{{ url('assets/fonts/khmer s1.volt.ttf') }}");
        }

        .page {
            margin: 0 auto;
            padding: 7mm 7mm 8mm 7mm;
            position: relative;
            font-family: "Times New Roman" !important;
        }
        table, tr td, tr th {
            border: 0.5px solid #000000 !important;
        }
        table, tr, td, th { page-break-inside: avoid; }

        .title {
            font-family: khmer_m2;
            font-size: 18px;
            text-align: center;
        }

        thead {
            display: table-header-group;
            background-color: #fffacd;
            border: 0.5px solid #000000 !important;
        }
        tbody {
            display: table-row-group;
            border: 0.5px solid #000000 !important;
        }

    </style>

</head>
<body>
<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 title">
                <span>បញ្ជីនិស្សិតវិស្វករឆ្នាំទី{{to_khmer_number($studentAnnuals[0]['grade_id'])}}
                    @if ($studentAnnuals[0]['grade_id'] == 1)
                        ទទួលវិញ្ញាបនបត្របញ្ចប់ថ្នាក់ឆ្នាំមូលដ្ឋានដោយជោគជ័យ
                    @endif
                    ឆ្នាំសិក្សា {{to_khmer_number(sprintf("%04d",$studentAnnuals[0]['academic_year'] -1))}}-{{to_khmer_number(sprintf("%04d",$studentAnnuals[0]['academic_year']))}}</span>
            </div>
        </div>
        <div class="row" style="margin-top: 5px; font-family: khmer_s1;">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ល.រ</th>
                        <th>អត្តលេខ</th>
                        <th style="width: 120px">ឈ្មោះខ្មែរ</th>
                        <th>ឈ្មោះជាភាសាឡាតាំង</th>
                        <th>ភេទ</th>
                        <th style="width: 135px">ថ្ងៃខែឆ្នាំកំណើត</th>
                        <th>ហត្ថលេខា</th>
                    </tr>
                </thead>
                @php
                    $index = 1;
                    $girl = 0
                @endphp
                <tbody>
                    @foreach($studentAnnuals as $studentAnnual)
                        <tr style="font-size: 12px">
                            <td align="center">{{$index++}}</td>
                            <td>{{$studentAnnual['id_card']}}</td>
                            <td>{{$studentAnnual['name_kh']}}</td>
                            <td>{{$studentAnnual['name_latin']}}</td>
                            <td>
                                @if(strtolower($studentAnnual['gender']) == 'm')
                                    ប្រុស
                                @else
                                    @php
                                        $girl++
                                    @endphp
                                    ស្រី
                                @endif
                            </td>
                            <td>{{to_khmer_number(sprintf("%02d", (new \Carbon\Carbon($studentAnnual['dob']))->day))}} ខែ{{to_khmer_month((new \Carbon\Carbon($studentAnnual['dob']))->month)}} ឆ្នាំ{{to_khmer_number(sprintf("%04d", (new \Carbon\Carbon($studentAnnual['dob']))->year))}}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row" style="font-size: 18px">
            <strong>បញ្ចប់បញ្ជីត្រឹម {{$index-1}}នាក់ ក្នុងនោះមានស្រីចំនួន {{$girl}}នាក់</strong>
        </div>
    </div>
</div>
</body>
{!! Html::script('plugins/jquery.min.js') !!}
<script>
    $(function () {
        var name = $('.name');

        var numWords = name.text().split("").length;
        console.log(numWords)

        if (numWords >= 14) {
            // name.css("font-size", "8px");
        }
    })
</script>
</html>
