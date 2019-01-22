@extends('backend.layouts.printing_portrait_a4_transcript')
@section('title')
    ITC-SMS |
@stop
@section("after-styles-end")
    <style>
        @font-face {
            font-family: khmer_m1;
            src: url("{{url('assets/fonts/Khmer M1.volt.ttf')}}");
        }

        @font-face {
            font-family: khmer_s1;
            src: url("{{url('assets/fonts/Khmer s1.volt.ttf')}}");
        }
        .sign-title {
            margin-bottom: 10px;
            margin-top: 10px;
        }
        .title-size {
            font-size: 16px;
            font-family: times_new_roman_normal !important;
        }
        .title {
            font-family: khmer_m1 !important;
        }
        th {
            background-color: #a8f1e7eb;
        }
        td {
            padding : 2px !important;
        }
        td, th {
            border: 1px solid black !important;
        }
        .center {
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <?php $group='A'; ?>
    @foreach($data as $page)
        <div class="page">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <span class="title-size">Institut de Technologie du Cambodge</span>
                        </div>
                        <div class="col-md-12">
                            <span class="title-size" style="font-family: times_new_roman_normal !important;">Departement: {{$department->name_fr}}</span>
                        </div>
                    </div>
                    <div class="sign-title" align="center">
                        <span class="title">សម្រង់វត្តមាននិស្សិតប្រលងបញ្ចប់ឆមាសទី {{to_khmer_number($semester)}}</span>
                    </div>

                    <div class="sign-content">
                        <div class="row">
                            @if($by_group)
                                <span style="font-weight: bold; font-size: 16px; margin-left: 15px;">Classe: {{$page[0]["class"]}} - {{$page[0]["group"]}} (Nb. {{count($page)}})</span>
                            @else
                                <span style="font-weight: bold; font-size: 16px; margin-left: 15px;">Classe: {{$page[0]["class"]}} - {{$group}} (Nb. {{count($page)}})</span>
                            @endif
                            <span style="margin-right: 15px;font-size: 16px;float: right">Année Scolaire {{$academic_year->name_latin}}</span>
                        </div>
                        <div class="row" style="line-height: 30px;">
                            <div class="col-md-12 col-lg-12">
                                <span class="underline">
                                    <span class="no_underline" style="font-family: khmer_s1 !important;">មុខវិជ្ជា: </span> (M .............) .....................................................................................................................................
                                </span>
                            </div>
                            <div class="col-md-6 col-lg-6 col-xs-6">
                                <span style="font-family: khmer_s1 !important;">ថ្ងៃទី  ខែ ឆ្នាំ</span> .........................................................
                            </div>
                            <div class="col-md-6 col-lg-6 col-xs-6">
                                <span style="font-family: khmer_s1 !important;">បន្ទប់លេខ</span> ...............................................................
                            </div>
                        </div>
                        <div class="row" style="line-height: 30px;">
                            <div class="col-md-6 col-lg-6 col-xs-6">
                                <span style="font-family: khmer_s1 !important;">រយះពេល</span> ...............................................................
                            </div>
                            <div class="col-md-3 col-lg-3 col-xs-3">
                                <span style="font-family: khmer_s1 !important;">ចាប់ពីម៉ោង</span> ................
                            </div>
                            <div class="col-md-3 col-lg-3 col-xs-3">
                                <span style="font-family: khmer_s1 !important;">ដល់ម៉ោង</span> ................
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <tr>
                                <th class="center">No</th>
                                <th>ID</th>
                                <th>Prénoms et Noms</th>
                                <th class="center">Sexe</th>
                                <th>Signature</th>
                                <th>Observations</th>
                            </tr>
                            <?php $index = 1; ?>
                            @foreach($page as $student)
                            <tr>
                                <td class="center">{{$index}}</td>
                                <td>{{$student["id_card"]}}</td>
                                <td>{{strtoupper($student["name_latin"])}}</td>
                                <td class="center">{{$student["gender"]}}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php $index++; ?>
                            @endforeach
                        </table>
                        <div class="col-xs-8"></div>
                        <div class="col-xs-4" align="center">
                            <div class="row">
                                Phnom Penh, le .................... {{$academic_year->id}}
                            </div>
                            <div class="row">
                                Le Directeur Adjoint
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php $group++ ?>
    @endforeach
@endsection

@section('scripts')
    <script>

    </script>
@endsection