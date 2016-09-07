
@extends('backend.layouts.printing_portrait_a4')
@section('title')
    ITC-SMS | Candidate Score Error
@stop
@section('after-styles-end')
    <style>
      
        table, th {
            border: 1px solid black;
            text-align: center;
        }
        td{
            border-left: 1px solid black;
        }
        input{
            width:100px;
        }
    </style>
@stop
@section('content')

    <?php   $page_number = 1;
    $total_page = count($arraySplitPages);

    ?>

    @foreach($arraySplitPages as $page)

        <div class="page">
            <div class="col-sm-12 no-padding">
                <div class="col-sm-6">
                    <h4>Error Score: {{$courseName}}</h4>
                </div>
                <div class="col-sm-6">
                    <h4 style="float: right">Corrector Name: ............................</h4>
                </div>
            </div>



                <table class="table tab" >
                    <thead>
                        <tr>
                            <th>Room</th>
                            <th>Order</th>
                            <th>Correct</th>
                            <th>Wrong</th>
                            <th>No Answer</th>
                        </tr>
                    </thead>

                        @foreach($page as $key => $orders)
                        <tr class="tab" style="border-bottom: 1px solid black">
                            <td rowspan="{{count($orders) +1 }}" > {{$key}}</td>

                        </tr>
                            @foreach($orders as $order)
                                <tr class="tab">
                                    <td> {{$order}} </td>
                                    <td><input type="text"></td>
                                    <td><input type="text"></td>
                                    <td><input type="text"></td>

                                </tr>

                            @endforeach

                        @endforeach

                    <tbody>

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
