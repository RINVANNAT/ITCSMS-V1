<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}"/>

    <title>
        ITC-SMS | Print Result Department Distribution
    </title>

    <!-- Meta -->
    <meta name="description" content="Print Result Department Distribution">
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
            font-family: KhBaphnomLimonR2;
            src: url("{{ asset('fonts/KhBaphnomLimonR2.ttf') }}");
        }
        table {
            width: 100%;
            font-family: "Times New Roman" !important;
        }
        tr th {
            text-align: center;
            padding: 6px;
            font-family: KhBaphnomLimonR2 !important;
        }
        tr td, tr th {
            border: 0.5px solid #000000 !important;
            padding-left: 5px;
            padding-right: 5px;
        }
        table, tr, td, th { page-break-inside: avoid; }
        .title, .sub-title {
            font-family: KhBaphnomLimonR2 !important;
        }
        .title {
            padding-bottom: 15px;
            padding-top: 15px;
        }
    </style>

</head>
<body>
<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h3 class="title text-center text-bold">បំនែងចែកដេប៉ាតឺម៉ង់ថ្នាក់ឆ្នាំទី  {{ $grade->id == 1 ? '២' : '៣' }}</h3>
                <h4 class="sub-title text-center text-bold">ឆ្នាំសិក្សា {{ $academicYear->name_kh }}</h4>
            </div>
            <div class="col-xs-12">
                <table>
                    <tbody>
                        <tr style="background: #dddddd !important;">
                            <th width="20px;">ល.រ</th>
                            <th width="35px;">អត្តលេខ</th>
                            <th>គោត្តនាម និង នាមខ្លួន</th>
                            <th>ភេទ</th>
                            <th>ដេប៉ាតឺម៉ង់</th>
                            <th>សេចក្តីផ្សេងៗ</th>
                        </tr>
                        @foreach($result as $key => $item)
                            @php
                                $deptOption = get_department_option_code($item->department_option_id)
                            @endphp
                            <tr>
                                <td class="text-center">{{ $key+1 }}</td>
                                <td>{{ $item->id_card }}</td>
                                <td>{{ $item->name_latin }}</td>
                                <td class="text-center">{{ $item->sex }}</td>
                                <td class="text-center">{{ $item->dept_code }}{{ $deptOption }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
