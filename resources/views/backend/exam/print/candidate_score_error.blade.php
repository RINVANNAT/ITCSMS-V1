@extends('backend.layouts.printing_portrait_a4')
@section('title')
    ITC-SMS | Candidate Score Error
@stop
@section('after-styles-end')
    <style>

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
@stop
@section('content')

    <?php
    $page_number = 1;
    $chunks = array_chunk($errors,28);
    $total_page = count($chunks);

    ?>

    @foreach($chunks as $page)
        <?php
        $result = [];
        foreach($page as $item){
            $result[explode("_",$item->candidateProperties)[0]][explode("_",$item->candidateProperties)[1]]=$item->scoreErrors;
        }
        ?>

        <div class="page">
            <div class="col-sm-12 no-padding">
                <div class="col-sm-6">
                    <h4>Error Score: {{$courseName}}</h4>
                </div>
                <div class="col-sm-6">
                    <h4 style="float: right">Corrector Name: ............................</h4>
                </div>
            </div>



                <table class="table table-bordered" >
                    <thead>
                        <tr>
                            <th>Room</th>
                            <th width="16%">Order</th>
                            <th width="16%">Sequence</th>
                            <th width="16%">Correct</th>
                            <th width="16%">Wrong</th>
                            <th width="16%">No Answer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($result as $key => $value)
                            <?php $row = 1; ?>
                            @foreach($value as $order_key => $order)
                                @if($row == 1)
                                    <tr class="tab" style="border-bottom: 1px solid black">
                                        <td rowspan="{{count($value)}}" style="vertical-align: middle"> {{$key}}</td>
                                        <td> {{$order_key}} </td>
                                        <td>{{count($order)+1}}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @else
                                    <tr class="tab" style="border-bottom: 1px solid black">
                                        <td> {{$order_key}} </td>
                                        <td>{{count($order)+1}}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endif

                                <?php $row++ ?>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>

            <div class="footer">
                <hr/>
                <span>Concours d'entree ITC 2016</span>
                <span class="pull-right">Page {{$page_number}} sur {{$total_page}}</span>

            </div>
        </div>

        <?php $page_number++; ?>

    @endforeach

@endsection

@section('scripts')
    <script>

    </script>
@stop
