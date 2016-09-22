@extends('backend.layouts.printing_landscape_a4')
@section('title')
    ITC-SMS | Registration Statistic
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

        table tfoot{
            background-color: #9FAFD1;
        }

    </style>
@stop
@section('content')
    <?php
    $page_number = 1;
    $total_page = 1;
    ?>
        <div class="page">
            <center><h2>Registration Statistic</h2></center>

            <table class="table table-bordered" width="100%">
                <thead>
                    <tr>
                        <th rowspan="2">Date</th>
                        <th colspan="2">A</th>
                        <th colspan="2">B</th>
                        <th colspan="2">C</th>
                        <th colspan="2">D</th>
                        <th colspan="2">E</th>
                        <th rowspan="2">Total</th>
                    </tr>
                    <tr>
                        <th>Num.</th>
                        <th>%</th>
                        <th>Num.</th>
                        <th>%</th>
                        <th>Num.</th>
                        <th>%</th>
                        <th>Num.</th>
                        <th>%</th>
                        <th>Num.</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                            $a = 0;
                            $b = 0;
                            $c = 0;
                            $d = 0;
                            $e = 0;
                    ?>
                    @foreach($candidates as $date => $grades)

                        <?php
                        $total = 0;
                        $a = $a + count($grades[34]);
                        $b = $b + count($grades[35]);
                        $c = $c + count($grades[36]);
                        $d = $d + count($grades[37]);
                        $e = $e + count($grades[38]);

                        foreach($grades as $value){
                            $total = $total + count($value); // Total per day
                        }
                        ?>
                        <tr>
                            <td>{{$date}}</td>
                            @foreach($grades as $grade)
                                <td>{{count($grade)}}</td>
                                <td>{{sprintf('%0.2f',(count($grade)/$total)*100)}}</td>
                            @endforeach
                            <td>{{$total}}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            Total
                        </td>
                        <td>{{$a}}</td>
                        <td>{{sprintf('%0.2f',($a/($a+$b+$c+$d+$e))*100)}}</td>
                        <td>{{$b}}</td>
                        <td>{{sprintf('%0.2f',($b/($a+$b+$c+$d+$e))*100)}}</td>
                        <td>{{$c}}</td>
                        <td>{{sprintf('%0.2f',($c/($a+$b+$c+$d+$e))*100)}}</td>
                        <td>{{$d}}</td>
                        <td>{{sprintf('%0.2f',($d/($a+$b+$c+$d+$e))*100)}}</td>
                        <td>{{$e}}</td>
                        <td>{{sprintf('%0.2f',($e/($a+$b+$c+$d+$e))*100)}}</td>
                        <td>{{$a+$b+$c+$d+$e}}</td>
                    </tr>
                </tfoot>
            </table>
            <div class="footer">
                <hr/>
                <span>Concours d'entree ITC 2016</span>
                <span class="pull-right">Page {{$page_number}} sur {{$total_page}}</span>
            </div>
        </div>
        <?php $page_number++; ?>

@endsection

@section('scripts')
    <script>

    </script>
@stop
