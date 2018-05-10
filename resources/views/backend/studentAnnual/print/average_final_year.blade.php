<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="X2IQMvdghRfpLlHKugb0MK9lDoeaeSpbxQpH2Oq9"/>

    <title>
        ITC-SMS | Certificate of Foundation Year Course
    </title>

    <!-- Meta -->
    <meta name="description" content="Printing Attestation">
    <meta name="author" content="Department Information and Communication Engineering">

    <!-- Styles -->
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('css/student_transcript.css') }}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('css/backend/prints/prints.css') }}">

    <style>
        tr {
            page-break-inside: avoid !important;
        }

        td {
            page-break-inside: avoid !important;
        }
        table tr td {
            font-size: 11px;
        }
        .border-thin {
            border: 0.5px solid black !important;
        }
        .border-top {
            border-top: 1px solid black !important;
        }

        .border-left {
            border-left: 1px solid black !important;
        }

        .border-right {
            border-right: 1px solid black !important;
        }
        .border-bottom {
            border-bottom: 1px solid black !important;
        }
        .page {

            /*width: 297mm;*/
            /*height: 210mm;*/
            margin: 0 auto;
            /*margin-top: 10mm;*/
            /*border: 1px #D3D3D3 solid;*/
            padding: -0.3mm -0.3mm 0mm 0.3mm !important;
            /*border-radius: 5px;*/
            /*background: white;*/
            /*box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);*/
            position: relative;
            font-family: "Times New Roman" !important;
        }
        .table > thead > tr > th, .table > thead > tr > td, .table > tbody > tr > th, .table > tbody > tr > td, .table > tfoot > tr > th, .table > tfoot > tr > td {
            padding-top: 0px;
            padding-bottom: 0px;
        }
        .cell-sm {
            padding: 1px !important;
        }
    </style>

</head>
<body>
<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <span>Institut de Technologie du Cambodge</span><br/>
                <span>Department: Genie Chimique et Alimentaire</span><br/>
                <span><strong>Classe: T2-GCA</strong></span>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <p align="center" style="line-height: normal"><strong>Moyenne fin d'etude</strong></p>
                <p align="center" style="line-height: normal">Annee Scolaire(16-17)</p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <table class="table">
                    <tr>
                        <td class="no-border"></td>
                        <td class="no-border"></td>
                        <td class="no-border"></td>
                        <td class="no-border"></td>
                        <td class="border-thin" align="center" colspan="3">1<sup>ere</sup> annee</td>
                        <td class="border-thin" align="center" colspan="3">2<sup>eme</sup> annee</td>
                        <td class="border-thin" align="center" colspan="3">Moy. de Sortie</td>
                        <td class="border-thin border-bottom" align="center" colspan="2" rowspan="2">Mention <br/> de Sortie</td>
                        <td class="border-thin border-bottom" align="center" rowspan="2">Observation</td>
                    </tr>
                    <tr>
                        <td class="border-thin border-bottom" align="center">N<sup>o</sup></td>
                        <td class="border-thin border-bottom" align="center">ID</td>
                        <td class="border-thin border-bottom" align="center"><strong>Noms et Prenoms</strong></td>
                        <td class="border-thin border-bottom" align="center"><strong>Sexe</strong></td>
                        <td class="border-thin border-bottom" align="center" colspan="2">Moy.(M1)</td>
                        <td class="border-thin border-bottom" align="center">GPA</td>
                        <td class="border-thin border-bottom" align="center" colspan="2">Moy.(M2)</td>
                        <td class="border-thin border-bottom" align="center">GPA</td>
                        <td class="border-thin border-bottom" align="center" colspan="2">(M1+M2)/2</td>
                        <td class="border-thin border-bottom" align="center">GPA</td>
                    </tr>
                    @php
                        $num = 50;
                    @endphp
                    @for($i=1;$i<=$num;$i++)
                        <tr>
                            <td class="border-thin border-left border-right" align="center">{{$i}}</td>
                            <td class="border-thin">e20150956</td>
                            <td class="border-thin">LUN SOCHEAT</td>
                            <td class="border-thin" align="center">F</td>
                            @if($i==1)
                                <td class="cell-sm" rowspan="{{$num}}"></td>
                                <td class="border-thin" align="center"><strong>81.73</strong></td>
                                <td class="border-thin" align="center"><strong>81.73</strong></td>
                                <td class="cell-sm" rowspan="{{$num}}"></td>
                                <td class="border-thin" align="center"><strong>79.03</strong></td>
                                <td class="border-thin" align="center"><strong>79.03</strong></td>
                                <td class="cell-sm" rowspan="{{$num}}"></td>
                                <td class="border-thin" align="center"><strong>80.38</strong></td>
                                <td class="border-thin" align="center"><strong>80.38</strong></td>
                                <td class="cell-sm" rowspan="{{$num}}"></td>
                                <td class="border-thin">Tres Bien</td>
                            @else
                                <td class="border-thin" align="center"><strong>81.73</strong></td>
                                <td class="border-thin" align="center"><strong>81.73</strong></td>
                                <td class="border-thin" align="center"><strong>79.03</strong></td>
                                <td class="border-thin" align="center"><strong>79.03</strong></td>
                                <td class="border-thin" align="center"><strong>80.38</strong></td>
                                <td class="border-thin" align="center"><strong>80.38</strong></td>
                                <td class="border-thin">Tres Bien</td>
                            @endif
                            <td class="border-thin border-left border-right"></td>
                        </tr>
                    @endfor
                    <tr>
                        <td class="border-top"></td>
                        <td class="border-top"></td>
                        <td class="border-top"></td>
                        <td class="border-top" align="center">Min</td>
                        <td class="border-top" align="center" colspan="2">50.81</td>
                        <td class="border-top" align="center" colspan="2">53.56</td>
                        <td class="border-top" align="center" colspan="2">25.41</td>
                        <td class="border-top" colspan="2"></td>
                        <td class="border-top"></td>
                    </tr>
                    <tr>
                        <td class="no-border"></td>
                        <td class="no-border"></td>
                        <td class="no-border"></td>
                        <td class="no-border" align="center">Max</td>
                        <td class="no-border" align="center" colspan="2">84.19</td>
                        <td class="no-border" align="center" colspan="2">79.96</td>
                        <td class="no-border" align="center" colspan="2">80.38</td>
                        <td class="no-border" colspan="2"></td>
                        <td class="no-border"></td>
                    </tr>

                </table>
            </div>
        </div>


    </div>
</div>
</body>
</html>