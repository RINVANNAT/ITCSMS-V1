
@extends('backend.layouts.printing_portrait_a4')
@section('title')
    ITC-SMS | Candidate Score Error
@stop
@section('after-styles-end')
    <style>

        .left{
            text-align: left;
        }

        table th, table td {
            text-align: center;
            padding-top: 3px !important;
            padding-bottom: 3px !important;
        }
    </style>
@stop
@section('content')

        <div class="page">
            <h2>Error Input Score Form</h2>


            <table class="table" width="100%">
                <tr>
                    <th>Room Code</th>
                    <th>Order In Room</th>
                    <th>Correct</th>
                    <th>Wrong</th>
                    <th>No Answer</th>
                </tr>
                <tr>
                    @foreach($object as $key => $orders)
                        <td rowspan="{{count($orders)}}"> {{$key}}</td>
                      
                        @foreach($orders as $order)
                            <tr>
                                <td> {{$order}} </td>
                                <td><input type="text"></td>
                                <td><input type="text"></td>
                                <td><input type="text"></td>
                            </tr>

                        @endforeach
                    @endforeach
                </tr>
            </table>




            <div class="footer">
                <hr/>
                <span>Concours d'entree ITC 2016</span>
                <span class="pull-right">Page</span>
            </div>
        </div>

@endsection

@section('scripts')
    <script>

    </script>
@stop
