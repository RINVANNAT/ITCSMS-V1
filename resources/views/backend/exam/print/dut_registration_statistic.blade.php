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
                        <th rowspan="2">Mesurment</th>
                        <th colspan="2">A</th>
                        <th colspan="2">B</th>
                        <th colspan="2">C</th>
                        <th colspan="2">D</th>
                        <th colspan="2">E</th>
                        <th rowspan="2">Total</th>
                    </tr>
                    <tr>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
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
                    <tr>
                        <td>
                            NUM
                        </td>


                            <?php
                            $A_Male = $candidates[34]['M'];
                            $A_Female = $candidates[34]['F'];
                            $B_Male = $candidates[35]['M'];
                            $B_Female = $candidates[35]['F'];
                            $C_Male = $candidates[36]['M'];
                            $C_Female = $candidates[36]['F'];
                            $D_Male = $candidates[37]['M'];
                            $D_Female = $candidates[37]['F'];
                            $E_Male = $candidates[38]['M'];
                            $E_Female = $candidates[38]['F'];

                            ?>
                                <td>{{count($A_Male)}}</td>
                                <td>{{count($A_Female)}}</td>
                                <td>{{count($B_Male)}}</td>
                                <td>{{count($B_Female)}}</td>
                                <td>{{count($C_Male)}}</td>
                                <td>{{count($C_Female)}}</td>
                                <td>{{count($D_Male)}}</td>
                                <td>{{count($D_Female)}}</td>
                                <td>{{count($E_Male)}}</td>
                                <td>{{count($E_Female)}}</td>

                                <td> {{$total}}</td>
                    </tr>
                <tr>
                    <td>
                        %
                    </td>

                    <?php $totalPercentage=0;?>

                    @foreach($candidates as $candidate)
                        <td>{{sprintf('%0.2f', (count($candidate['M']) / $total)* 100) }}</td>
                        <td>{{sprintf('%0.2f', (count($candidate['F']) / $total)* 100) }}</td>

                        <?php $totalPercentage = $totalPercentage + (count($candidate['M']) * 100)/$total + (count($candidate['F']) * 100)/$total;?>


                    @endforeach

                    <td>{{sprintf('%0.2f', $totalPercentage) }}</td>
                    </tr>

                </tbody>
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
