@extends('backend.layouts.printing_portrait_a4_transcript')
@section('title')
    ITC-SMS |
@stop
@section("after-styles-end")
    <style>

        .sign-title {
            margin-bottom: 10px;
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
    <?php $group=1; ?>
    @foreach($data as $page)
        <div class="page">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>Institut de Technologie du Cambodge</h5>
                            <h4>Departement {{$page[0]["class"]}}</h4>
                        </div>
                        <div style="position: absolute;top: 0;right: 0; margin-right: 15px;">
                            <?php $now = \Carbon\Carbon::now() ?>
                            <h6 class="pull-right">Updated: {{$now->format("d/m/Y")}}</h6>
                        </div>
                    </div>
                    <div class="sign-title">
                        <h3 class="title" align="center"><strong>សម្រង់វត្តមាននិស្សិតប្រលងបញ្ចប់ឆមាសទី. {{$semester}}</strong></h3>
                    </div>

                    <div class="sign-content">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Classe: {{$page[0]["class"]}} ({{$academic_year->name_latin}})</h3>
                            </div>
                        </div>
                        <div class="row">
                            <span style="margin: 5px 15px 5px; font-size: 20px;">Promotion {{$page[0]["promotion"]}}</span>
                            <span style="margin: 5px 15px 5px; font-size: 20px; float:right;">Group: {{$group}}</span>
                        </div>
                        <table class="table table-bordered">
                            <tr>
                                <th class="center">No</th>
                                <th>ID</th>
                                <th>Non et Prenoms</th>
                                <th class="center">Sexe</th>
                                <th>Provenance</th>
                                <th>Observation</th>
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