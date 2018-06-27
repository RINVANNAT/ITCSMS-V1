@extends ('backend.layouts.popup_master')

@section ('title', 'Course Annual' . ' | ' . 'Total Score Annually')
@section('after-styles-end')
    {!! Html::style(elixir('css/handsontable.full.min.css')) !!}
    {!! Html::style('plugins/select2/select2.min.css') !!}
    <style>
        .handsontable thead tr:first-child {
            height: 80px !important;
            vertical-align: middle !important;
        }
        .handsontable thead tr:nth-child(2) {
            height: 50px !important;
            vertical-align: middle !important;
        }
        .handsontable th {
            white-space: normal !important;
        }

        .handsontable td {
            color: #000 !important;
        }
        .handsontable td .htAutocompleteArrow:hover {
            color: #777 !important;
        }
        .handsontable td.area .htAutocompleteArrow {

            color: #d3d3d3 !important;
        }
        .top {
            margin-top: 5px;
            color: #0A0A0A;
        }
        .top a {
            color: black;
        }
        table tr th, table tr td {
            border: 1px solid #000000 !important;
        }
    </style>
@endsection

@section('content')

    <div class="box box-success">

        <div class="box-header with-border" style="margin-bottom: 0px">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pull-right btn-right">
                            <select name="student_class" class="form-control filter" id="filter_class"></select>
                            <a target="_blank" href="{{route('admin.student.print_average_final_year', ['type'=>'print'])}}">
                                <button class="btn btn-primary btn-average-final-year btn-sm" data-toggle="tooltip" style="margin-left: 5px" data-placement="left"  title="Print Average Final Year" id="print_average_final_year"><i class="fa fa-print"></i></button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- /.box-header -->
        @if (session('status'))
            <div class=" message alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @if (session('warning'))
            <div class=" message alert alert-danger">
                {{ session('warning') }}
            </div>
        @endif
        <div class="box-body panel">
            <div class="page">
                <div class="container">

                    <div class="row">
                        <div class="col-xs-12">
                            <p align="center" style="line-height: normal"><strong>Moyenne fin d'etude</strong></p>
                            <p align="center" style="line-height: normal">Annee Scolaire({{$academic_year->name_latin}})</p>
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
                                    <td class="border-thin" align="center" colspan="2">1<sup>ere</sup> annee</td>
                                    <td class="border-thin" align="center" colspan="2">2<sup>eme</sup> annee</td>
                                    <td class="border-thin" align="center" colspan="2">Moy. de Sortie</td>
                                    <td class="border-thin border-bottom" align="center" rowspan="2">Mention <br/> de Sortie</td>
                                    <td class="border-thin border-bottom" align="center" rowspan="2">Observation</td>
                                </tr>
                                <tr>
                                    <td class="border-thin border-bottom" align="center">N<sup>o</sup></td>
                                    <td class="border-thin border-bottom" align="center">ID</td>
                                    <td class="border-thin border-bottom" align="center"><strong>Noms et Prenoms</strong></td>
                                    <td class="border-thin border-bottom" align="center"><strong>Sexe</strong></td>
                                    <td class="border-thin border-bottom" align="center">Moy.(M1)</td>
                                    <td class="border-thin border-bottom" align="center">GPA</td>
                                    <td class="border-thin border-bottom" align="center">Moy.(M2)</td>
                                    <td class="border-thin border-bottom" align="center">GPA</td>
                                    <td class="border-thin border-bottom" align="center">(M1+M2)/2</td>
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
                                        <td class="border-thin" align="center"><strong>81.73</strong></td>
                                        <td class="border-thin" align="center"><strong>3.5</strong></td>
                                        <td class="border-thin" align="center"><strong>79.03</strong></td>
                                        <td class="border-thin" align="center"><strong>3.0</strong></td>
                                        <td class="border-thin" align="center"><strong>80.38</strong></td>
                                        <td class="border-thin" align="center"><strong>3.5</strong></td>
                                        <td class="border-thin">Tres Bien</td>
                                        <td class="border-thin border-left border-right"></td>
                                    </tr>
                                @endfor
                                <tr>
                                    <td class="border-top"></td>
                                    <td class="border-top"></td>
                                    <td class="border-top"></td>
                                    <td class="border-top" align="center">Min</td>
                                    <td class="border-top" align="center">50.81</td>
                                    <td class="border-top" align="center">50.81</td>
                                    <td class="border-top" align="center">53.56</td>
                                    <td class="border-top" align="center">53.56</td>
                                    <td class="border-top" align="center">25.41</td>
                                    <td class="border-top" align="center">25.41</td>
                                    <td class="border-top"></td>
                                    <td class="border-top"></td>
                                </tr>
                                <tr>
                                    <td class="no-border"></td>
                                    <td class="no-border"></td>
                                    <td class="no-border"></td>
                                    <td class="no-border" align="center">Min</td>
                                    <td class="no-border" align="center">50.81</td>
                                    <td class="no-border" align="center">50.81</td>
                                    <td class="no-border" align="center">53.56</td>
                                    <td class="no-border" align="center">53.56</td>
                                    <td class="no-border" align="center">25.41</td>
                                    <td class="no-border" align="center">25.41</td>
                                    <td class="no-border"></td>
                                    <td class="no-border"></td>
                                </tr>

                            </table>
                        </div>
                    </div>


                </div>
            </div>

        </div>

    </div>
@stop

@section('after-scripts-end')
    {!! HTML::script(elixir('js/handsontable.full.min.js')) !!}
    {!! Html::script('plugins/jpopup/jpopup.js') !!}
    {!! Html::script('js/backend/course/courseAnnual/all_score.js') !!}
    {!! Html::script('plugins/select2/select2.full.min.js') !!}
    {{--myscript--}}

    <script>

        $(function () {
            $.ajax({
                type: 'POST',
                url: '{{route('admin.filter.get_filter_by_class_final_year')}}',
                data: {'academic_year_id': 2018},
                dataType: "json",
                success: function (response) {
                    if (response.status == "success") {
                        $('#filter_class').select2({
                            data: response.data,
                            placeholder: "Select a class",
                        });
                        try {
                            callback();
                        } catch (exception) {

                        }
                    } else {
                        notify("error", "info", "Something went wrong! Cannot filtering value");
                    }
                }
            })
        })

    </script>


@stop