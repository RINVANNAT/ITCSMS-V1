<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="X2IQMvdghRfpLlHKugb0MK9lDoeaeSpbxQpH2Oq9"/>

    <title>
        ITC-SMS | Print Album's Photo
    </title>

    <!-- Meta -->
    <meta name="description" content="Printing Attestation">
    <meta name="author" content="Department Information and Communication Engineering">
    {!! Html::style(elixir('css/backend.css')) !!}
    <style>
        @font-face {
            font-family: khmer_os_muol;
            src: url("{{ asset('assets/fonts/KhmerOS_muollight.ttf') }}");

        }
        .avatar .crop {
            width: 1.2in;
            height: 1.55in;
            display: block;
            border: 0.5px solid #dddddd !important;
            overflow: hidden;
            margin-left: auto;
            margin-right: auto;
            position: relative;
        }
        .photo {
            position: absolute;
            width: 1.2in;
            height: 1.55in;
            top: 0;
            left: 0;
        }
        .avatar img {
            width: 100%;
        }
        .col-xs-2 {
            height: 200px;
        }
        .title {
            font-family: "khmer_os_muol";
            font-size: 18px;
            text-align: center;
            line-height: 40px;
            margin-bottom: 30px;
        }
        .page {
            page-break-inside: avoid !important;
        }


    </style>
</head>
<body>
    <div class="page">
        <div class="row">
            <div class="col-xs-12">
                <div class="title">
                    និស្សិត{{ $studentAnnuals_front[0]['degree'] }}({{ $studentAnnuals_front[0]['degree_code'] }}{{$studentAnnuals_front[0]['grade_id']}}{{$group !== '' ? '-'.$group : ''}}-{{$studentAnnuals_front[0]['department']}}) ជំនាន់ទី {{to_khmer_number($studentAnnuals_front[0]['promotion_id'])}}
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($studentAnnuals_front as $student)
                <div class="col-xs-2" style="page-break-inside: avoid">
                    <div class="avatar">
                        <div class="crop" style="text-align: center">
                            <p>{{ $student['id_card'] }}</p>
                            <p>{{ $student['gender'] }}</p>
                            <p>{{ $student['degree_code'] }}{{ $student['grade_id'] }}{{ $student['department'] }}</p>
                            <p>{{ $student['phone'] }}</p>
                            <div class="photo">
                                <img src="{{$smis_server->value}}/img/profiles/{{$student->photo}}"
                                     style="width: 1.2in; height: 1.55in; border: 0.2px solid #dddddd;"
                                     onerror="this.src='/img/100x148-ffffff7f.png'"/>
                            </div>
                        </div>
                    </div>
                    <div style="text-align: center">
                        @if(strlen($student->name_latin) < 25)
                            <span class="name_latin">{{strtoupper($student->name_latin)}}</span>
                        @else
                            <span class="name_latin" style="font-size: 13px !important;">{{strtoupper($student->name_latin)}}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</body>

{!! Html::script('js/vendor/bootstrap/bootstrap.min.js') !!}