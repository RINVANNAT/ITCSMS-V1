@extends('backend.layouts.printing_portrait_a4')
@section('title')
    ITC-SMS | Correction Sheet
@stop

@section('after-styles-end')
    <style>
        .sticker{
            padding: 5mm;
        }
        .border{
            border: 2px solid black;
        }

        h2{
            font-weight: 900;
        }
        .left{
            text-align: left;
        }
        .right{
            text-align: right;
        }
        .center{
            text-align: center;
        }
        table th, table td {
            text-align: center;
            padding-top: 3px !important;
            padding-bottom: 3px !important;
        }

        .table-bordered > thead > tr > th,
        .table-bordered > tbody > tr > th,
        .table-bordered > tfoot > tr > th,
        .table-bordered > thead > tr > td,
        .table-bordered > tbody > tr > td,
        .table-bordered > tfoot > tr > td {
            border:2px solid #000000 !important;
        }

        @media print{
            .table-bordered > thead > tr > th,
            .table-bordered > tbody > tr > th,
            .table-bordered > tfoot > tr > th,
            .table-bordered > thead > tr > td,
            .table-bordered > tbody > tr > td,
            .table-bordered > tfoot > tr > td {
                border:2px solid #000000 !important;
            }
        }
    </style>
@endsection
@section('content')
    <?php
    $page_number = 1;
    $total_page = count($rooms);
    $corrections = [1,2];
    ?>
    @foreach($corrections as $correction)
        @foreach($courses as $course)
            @foreach($rooms as $room)
                <div class="page">
                    <div class="row no-margin">
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <h3 class="left">SALLE:</h3>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <h3 class="center">Feuille de notes</h3>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <h3 class="right">Correction</h3>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <h1 style="font-weight: 900; font-size: 3em;padding: 0px;margin-top: 0px;margin-bottom: 0px;" class="left">{{$room['roomcode']}}</h1>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <h1 class="center" style="margin-top: 5px;margin-bottom: 0px;">{{$course->name_kh}}</h1>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">

                            <div style="border:3px black solid;width:2.5cm; float:right">
                                <h1 class="center" style="font-weight: 900;padding: 0px; margin-top: 0px;margin-bottom: 0px;">{{$correction}}</h1>
                            </div>
                        </div>
                    </div>

                    <div class="pull-right">
                        Nom du correcteur: .................................
                    </div>

                    <table class="table table-bordered" width="100%" style="table-layout: fixed;height: 100%">
                        <tr>
                            <th>Numero</th>
                            <th>Bon</th>
                            <th>Faux</th>
                            <th>Sans reponse</th>
                        </tr>
                        <?php
                        $index = 1;
                        ?>
                        @foreach($room['candidates'] as $candidate)
                            <tr>
                                <td width="25%">{{str_pad($index, 2, '0', STR_PAD_LEFT)}}</td>
                                <td width="25%"></td>
                                <td width="25%"></td>
                                <td width="25%"></td>
                                <?php $index++; ?>
                            </tr>
                        @endforeach
                    </table>
                    <div class="footer">

                        <span>Attention: Nombre des étudiants peut être 26 ou 27 (Année {{ $academic_year->id - 1 }})</span>

                    </div>
                </div>
                <?php $page_number++; ?>
            @endforeach
        @endforeach
    @endforeach

@endsection

@section('scripts')
    <script>

    </script>
@stop
