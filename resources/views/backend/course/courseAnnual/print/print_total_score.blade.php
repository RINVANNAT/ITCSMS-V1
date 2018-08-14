@extends('backend.layouts.printing_landscape_a3')
@section('title')
    ITC-SMS | សំរង់ពិន្ទុប្រចាំឆមាស
@stop

@section('after-styles-end')
    <style>
        table, td, th, tr {
            border: 1px solid black;
            font-size: 10px;
        }

        div.vertical{
            position: relative;
            height: 130px;
            margin-left: 0;
            writing-mode: tb-rl;
            filter: flipv fliph;
            text-align: center;
        }

        th.vertical {
            padding-bottom: 10px;
            vertical-align: bottom;
        }

        th, td.center {
            text-align: center;
        }

        td {
            padding: 3px !important;
        }

        .page {
            padding: 5mm !important;
        }
        .Abs, .Total {
            width: 7mm;
        }
        .score, .rank, .rattrapage,.S_1, .S_2  {
            width: 10mm;
        }
        .redouble{
            width: 15mm;
        }
        /*.observation {*/
            /*width: 25mm;*/
        /*}*/
        .remark {
            width: 15mm;
        }

    </style>
@stop
@section('content')

        <?php
            $count = count($data->nestedHeaders[0]) - 3;

            $first_chunk = array_slice($data->data, 0, 27);
            $next_chunk = array_slice($data->data,27);
            $all_chunk = $data->data;
        ?>
        <div class="page">
            <div class="row">
                <div class="col-md-3">
                    Institut de Technologie du Cambodge <br/>
                    Département: {{$department->name_fr}} <br/>
                    <?php
                      if($dept_option != null){
                          $option = $dept_option->code;
                      } else {
                          $option = "";
                      }
                    ?>
                    <b>Classe: {{$degree->code.$grade->code}} - {{$department->code.$option}}</b>
                </div>
                <div class="col-md-6">
                    <center>
                        <h2>RELEVE DES NOTES DE CONTROLE</h2>
                        <h5>Année Scolaire {{$academic_year->name_latin}}</h5>
                    </center>
                </div>
                <div class="col-md-3">

                </div>
            </div>

            <table style="width:400mm;margin-left: auto; margin-right: auto;">
                <tbody style="width: 400mm !important;">
                    <tr>
                        <?php
                        $index = 0;
                        ?>
                        @foreach($data->nestedHeaders[0] as $header)
                            <?php
                            if($index >2){
                                $vertical = "vertical";
                            } else {
                                $vertical = "";
                            }
                            ?>
                            @if(isset($header->label))
                                <th class="{{$vertical}}" colspan="{{$header->colspan}}">
                                    <div class="{{$vertical}}">{{$header->label}}</div>
                                </th>
                            @else
                                <th class="{{$vertical}}">
                                    <div class="{{$vertical}}">
                                        {{$header}}
                                    </div>
                                </th>
                            @endif
                            <?php $index++; ?>
                        @endforeach
                    </tr>
                    <tr>
                        <?php
                        $index = 0;
                        ?>
                        @foreach($data->nestedHeaders[1] as $header)
                            <?php
                            if($index >3){
                                $width = "";
                                if(isset($header->label)){
                                    if($header->label == "Abs"){
                                        $custom_class = "Abs";
                                    } else if(is_numeric($header->label)){
                                        $custom_class = "score";
                                    } else {
                                        $custom_class = $header->label;
                                    }
                                } else {
                                    if(is_numeric($header)){
                                        $custom_class = "score";
                                    } else {
                                        $custom_class = $header;
                                    }
                                }
                            } else {
                                $custom_class = "";
                                if($index == 0 || $index == 3){
                                    $width = "style='width:10mm'";
                                } else if($index == 1){
                                    $width = "style='width:20mm'";
                                } else {
                                    $width = "style='width:40mm !important'";
                                }
                            }

                            ?>
                            @if(isset($header->label))
                                <th {!! $width !!} class="{{$custom_class}}" colspan="{{$header->colspan}}">
                                    {{$header->label}}
                                </th>
                            @else
                                <th {!! $width !!} class="{{$custom_class}}">
                                    @if(is_numeric($header))
                                        {{$header}}
                                    @endif
                                </th>
                            @endif
                            <?php $index++; ?>
                        @endforeach
                    </tr>

                    @foreach(collect($all_chunk)->reverse()->splice(5)->reverse() as &$element)
                        <?php $element = collect($element)->reverse()->splice(1)->reverse() ?>
                        <tr>
                            @foreach($element as $key => $value)
                                @if($key == "student_name")
                                    <td class="left">{{$value}}</td>
                                @elseif($key != "_empty_")
                                    <td class="center">{{$value}}</td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                    {{--{{ dd($all_chunk[113]) }}--}}
                    @for($i = count(collect($all_chunk)->reverse()->splice(5)->reverse()); $i < count($all_chunk); $i++)
                        <tr>
                            @foreach($all_chunk[$i] as $key => $value)
                                @if($key == "student_name")
                                    <td class="left">{{$value}}</td>
                                @elseif($key != "_empty_")
                                    <td class="center">{{$value}}</td>
                                @endif
                            @endforeach
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
@endsection

@section('scripts')
    <script>

    </script>
@stop