@extends('backend.layouts.printing_landscape_a4')
@section('title')
    ITC-SMS | Certificate
@stop

@section('after-styles-end')
    <style>
        .title{
            font-family: "Calibri Light";
        }

        .p1 {
            font-size: 14pt;
        }
        .p2 {
            font-size: 13pt;
        }

        .attestation_title {
            font-size: 24pt;
            font-family: "Calibri Light (Headings)";
            color: #0F6AB4;
            font-weight: bold;
        }

        .panel_top_8{
            margin-top: 5%;
        }
        .red_col {
            color: red;
        }
        .set_border_ {
            border: 1px solid black !important;
        }

        table tr {
            border: 1px solid black !important;
        }
        table thead th  {
            border: 1px solid black !important;
        }
        table tr td {
            border: 1px solid black !important;
            font-size: 9pt;
            font-family: "Calibri Light";
        }


    </style>
@stop
@section('content')

    <div class="page">
        <div class="row text-center title">
            <p class="p1">
                Ministère de l’Éducation, de la Jeunesse et des Sports
                <p class="p2">
                Institut de Technologie du Cambodge
                    <p class="p2">
                    Section de Français
                    </p>
                </p>
            </p>
        </div>

        <div class="row text-center attestation_title">
           <span>
               ATTESTATION de NIVEAU de LANGUE FRANÇAISE
           </span>
        </div>

        <div class="row text-center" style="font-family: 'Calibri Light'; font-size: 12pt; margin-top: 20px">
            <span>
                Le Directeur de l’Institut de Technologie du Cambodge atteste que 
            </span>
        </div>

        <div class="row text-center panel_top_8" style="font-family: 'Calibri Light'; font-size: 12pt; margin-top: 20px; font-weight: bold">
            <span>
               Mlle NGOV KHEANG, née le 06 juin 1996, ID : e2016234
            </span>
        </div>

        <div class="row" style="margin-top: 3%">

            <div class="col-md-5 no-padding text-center">

                 <span>
                    Bulletin de notes
                </span>
                <table class="table">
                    <thead>
                    <tr class="set_border">
                        <th colspan="2" rowspan="2">Compétences évaluées comblées</th>
                        <th>Note</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr class="set_border">
                        <td rowspan="2" style="text-align:center; vertical-align:middle;" > ÉCRIT </td>
                        <td>Compréhension</td>
                        <td > <span class="red_col">23</span> /25</td>
                    </tr>
                    <tr class="set_border">

                        <td >Production</td>
                        <td><span class="red_col">20</span> /25</td>
                    </tr>

                    <tr class="set_border">
                        <td rowspan="2" style="text-align:center; vertical-align:middle;"  > ORAL </td>
                        <td>Compréhension</td>
                        <td > <span class="red_col">19</span>  /25</td>
                    </tr>
                    <tr class="set_border">

                        <td >Production</td>
                        <td><span class="red_col">22</span> /25</td>
                    </tr>

                    <tr style="border-bottom: 0px !important; border-left: none !important; border-right: none !important;">

                        <td style="border-bottom: 0px !important; border-left: none !important; border-right: none !important;"> </td>

                        <td style="border-bottom: 0px !important; border-left: none !important; border-right: none !important;">
                            Note Finale
                        </td>

                        <td style="border-bottom: 0px !important; border-left: none !important; border-right: none !important;">
                            <span class="red_col"> 84</span> /100
                        </td>

                    </tr>

                    </tbody>
                </table>

                <span class="red_col">
                    Admise
                </span>


                <div class="row" style="margin-top: 5%">

                    <p style="font-size: 9pt; font-family: 'Calibri Light (Headings)'; text-align: left">
                        *Note minimale requise par épreuve : 05/25
                    <p style="font-size: 9pt; font-family: 'Calibri Light (Headings)'; text-align: left" >
                        *Seuil de réussite pour obtenir le niveau : 50/100
                    </p>
                    </p>

                    <p style="font-size: 6pt; font-family: 'Calibri Light (Headings)'; text-align: left">
                        Cette originale attestation est délivrée en un seul exemplaire. Aucun double ne pourra être refait.
                    </p>

                    <p style="font-size: 6pt; font-family: 'Calibri Light (Headings)'; text-align: left">
                        Cette attestation est accompagnée par le bulletin de notes de l’étudiant (e) intéressé(e).
                    </p>
                </div>



            </div>
            <div class="col-md-6 pull-right no-padding" >
                <p style="font-family: 'Calibri Light'; font-size: 12pt">
                    a comblé avec succès un niveau de langue française
                    <span style="font-size: 21pt; color: #0a6aa1"> A1 </span>
                    du Cadre européen commun de référence pour les langues (CECRL) à la session de l’examen de niveau à l’Institut, survenue du 19 au 23 juin 2017.
                </p>

                <p style="font-family: 'Calibri Light'; font-size: 12pt; font-style: italic">
                    Pour valoir ce que de droit.
                </p>

                <div class="pull-right" style="margin-top: 10%">
                    <p class="text-center title" style="font-size: 12pt">
                        Phnom Penh, ITC, le 26 juin 2017
                        <p style="margin-top: -10px !important;">
                        Le Directeur de l’Institut de Technologie du Cambodge,
                        </p>
                    </p>
                </div>

            </div>

        </div>
    </div>

@endsection

@section('scripts')
    <script>

    </script>
@stop