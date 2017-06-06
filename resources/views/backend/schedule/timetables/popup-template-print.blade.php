@extends ('backend.layouts.popup_master')

@section ('title', 'Template Print Timetable | Timetable Management')

@section('after-styles-end')

    {!! Html::style('plugins/iCheck/all.css') !!}
    {!! Html::style('plugins/toastr/toastr.min.css') !!}
    {!! Html::style('css/backend/schedule/timetable.css') !!}

    <style type="text/css">
        body {
            line-height: 0.8 !important;
        }

        .content-wrapper {
            background-image: none !important;
            background-color: #fff;
        }
        .row{
            page-break-after: always;
        }

        table.timetable {
            width: 100%;
        }

        table.timetable tr td {
            margin: 0px !important;
            border: 1px solid #c7c7c7;
            border-collapse: collapse;
        }

        table.timetable td {
            /*padding-top: 2px !important;
            padding-bottom: 2px !important;
            padding-left: 10px !important;
            padding-right: 10px !important;*/
        }

        table.timetable th {
            padding: 10px !important;
        }

        table.timetable p, table.timetable tr td {
            font-size: 12px !important;
            margin: 12px !important;
            padding: 0 !important;
            line-height: 0.4 !important;
        }

        th {
            height: 50px !important;
        }

        img.image-logo {
            margin: 0px;
            padding: 0px !important;
            width: 50px;
            height: 50px;
            text-align: left !important;
            float: left;
        }
        @media print {
            .row {page-break-after: always !important;}
        }
    </style>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <table class="timetable">
                <thead>
                <tr style="border: none !important; margin-bottom: 200px !important;">
                    <th rowspan="3" style="text-align: center;border: none !important;">
                        <img src="{{ asset('img/timetable/logo-print.jpg') }}" class="image-logo"/>
                    </th>
                    <th colspan="5" rowspan="2" style="border: none !important;text-align: center;line-height: 1.5;">
                        EMPLOI DU TEMPS 2016-2017<br/>Groupe: I3-GIC
                    </th>
                    <th rowspan="2" style="line-height: 1.5;border: none !important;">Semestre - II<br/>Semaines 1 à 8
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr align="center" style="height: 30px !important;">
                    <td style="font-weight: bold;">Horaire</td>
                    <td style="font-weight: bold;">Lundi</td>
                    <td style="font-weight: bold;">Mardi</td>
                    <td style="font-weight: bold;">Mercredi</td>
                    <td style="font-weight: bold;">Jeudi</td>
                    <td style="font-weight: bold;">Vendredi</td>
                    <td style="font-weight: bold;">Samedi</td>
                </tr>
                @for($i=0; $i<4; $i++)
                    <tr>
                        <td align="center" valign="middle">7:00 - 8:00</td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                    </tr>
                @endfor

                <tr style="height: 30px !important;">
                    <td colspan="7"></td>
                </tr>

                @for($i=0; $i<4; $i++)
                    <tr>
                        <td align="center" valign="middle">7:00 - 8:00</td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>
    </div sty>
    <div class="row">
        <div class="col-md-12">
            <table class="timetable">
                <thead>
                <tr style="border: none !important; margin-bottom: 200px !important;">
                    <th rowspan="3" style="text-align: center;border: none !important;">
                        <img src="{{ asset('img/timetable/logo-print.jpg') }}" class="image-logo"/>
                    </th>
                    <th colspan="5" rowspan="2" style="border: none !important;text-align: center;line-height: 1.5;">
                        EMPLOI DU TEMPS 2016-2017<br/>Groupe: I3-GIC
                    </th>
                    <th rowspan="2" style="line-height: 1.5;border: none !important;">Semestre - II<br/>Semaines 1 à 8
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr align="center" style="height: 30px !important;">
                    <td style="font-weight: bold;">Horaire</td>
                    <td style="font-weight: bold;">Lundi</td>
                    <td style="font-weight: bold;">Mardi</td>
                    <td style="font-weight: bold;">Mercredi</td>
                    <td style="font-weight: bold;">Jeudi</td>
                    <td style="font-weight: bold;">Vendredi</td>
                    <td style="font-weight: bold;">Samedi</td>
                </tr>
                @for($i=0; $i<4; $i++)
                    <tr>
                        <td align="center" valign="middle">7:00 - 8:00</td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                    </tr>
                @endfor

                <tr style="height: 30px !important;">
                    <td colspan="7"></td>
                </tr>

                @for($i=0; $i<4; $i++)
                    <tr>
                        <td align="center" valign="middle">7:00 - 8:00</td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                        <td>
                            <p style="text-align: right;">Course</p>
                            <p style="text-align: center; font-weight: bold;">Algorithm</p>
                            <p style="text-align: center;">CHUN Thavorac</p>
                            <p style="text-align: right">404-F</p>
                        </td>
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>
    </div>

@stop

@section('after-scripts-end')
    {!! Html::script('plugins/iCheck/icheck.js') !!}
    {!! Html::script('js/backend/schedule/clone-timetable.js') !!}
    {!! Html::script('js/backend/schedule/timetable-print.js') !!}
@stop

