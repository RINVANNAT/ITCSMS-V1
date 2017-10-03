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
    @if($candidates)

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
                <span>Concours d'entree ITC {{isset($academic_year)?($academic_year->id-1):"N/A"}}</span>
                {{--<span class="pull-right">Page {{$page_number}} sur {{$total_page +2}}</span>--}}
            </div>
        </div>
        <?php $page_number++; ?>
    @endif

    @if($allCandidates)
        <div class="page">

            <center><h2>Candidate Result Statistic</h2></center>
            <strong class="text-center"> Numeration </strong>

            <table class="table table-bordered" width="100%">
                <thead>
                <tr>
                    <th rowspan="2">Departments</th>
                    <th colspan="2">A</th>
                    <th colspan="2">B</th>
                    <th colspan="2">C</th>
                    <th colspan="2">D</th>
                    <th colspan="2">E</th>
                    <th colspan="2">Total</th>

                </tr>
                <tr>
                    <th>Total</th>
                    <th>F</th>
                    <th>Total</th>
                    <th>F</th>
                    <th>Total</th>
                    <th>F</th>
                    <th>Total</th>
                    <th>F</th>
                    <th>Total</th>
                    <th>F</th>
                    <th >Total</th>
                    <th >F</th>
                </tr>
                </thead>
                <tbody>

                {{--loop order by the result code:name abreviation --}}
                <?php $total_pass_reserve_by_grade=[]; $toal_pass_reserve_girl_by_grade= []; $totalCandidates = 0; $total_g = 0;?>
                @foreach($allCandidates as $key => $cand)

                    <tr>

                        <td>  {{$key}} </td>

                        <?php
                        $total_pass_reserve = 0;
                        $total_girl = 0;

                        ?>

                        @foreach($arrayGrades as $grade)

                            @if(isset($cand[$grade]))

                                {{--check if there are passed candidates only male or only girl by the bac grade--}}

                                @if(isset($cand[$grade]['F']) && isset($cand[$grade]['M']) )


                                    <?php
                                    $total_pass_reserve = $total_pass_reserve +count( $cand[$grade]['M']) + count($cand[$grade]['F']);

                                    $total_girl = $total_girl + count($cand[$grade]['F']);

                                    $total_pass_reserve_by_grade[$key]['total'][] = count( $cand[$grade]['M']) + count($cand[$grade]['F']);
                                    $total_pass_reserve_by_grade[$key]['F'][] = count($cand[$grade]['F']);
                                    ?>
                                    <td> {{count( $cand[$grade]['M']) + count($cand[$grade]['F'])}}</td>
                                    <td> {{count($cand[$grade]['F'])}}</td>

                                @elseif(isset($cand[$grade]['F']) == false && isset($cand[$grade]['M']) == true )

                                    <?php
                                    $total_pass_reserve = $total_pass_reserve +count( $cand[$grade]['M']);
                                    $total_pass_reserve_by_grade[$key]['total'][] = count( $cand[$grade]['M']) ;
                                    $total_pass_reserve_by_grade[$key]['F'][] = 0;
                                    ?>
                                    <td> {{count( $cand[$grade]['M'])}}</td>
                                    <td> 0</td>


                                @elseif(isset($cand[$grade]['F']) == true && isset($cand[$grade]['M']) == false )

                                    <?php
                                    $total_pass_reserve = $total_pass_reserve +count( $cand[$grade]['F']);
                                    $total_girl = $total_girl + count( $cand[$grade]['F']);
                                    //                                          // to find the total of candidate by each bac_grade and in each column
                                    $total_pass_reserve_by_grade[$key]['total'][] = count( $cand[$grade]['F']) ;
                                    $total_pass_reserve_by_grade[$key]['F'][] = count( $cand[$grade]['F']);
                                    ?>
                                    <td> {{count( $cand[$grade]['F'])}} </td>
                                    <td> {{count( $cand[$grade]['F'])}} </td>

                                @else
                                    <td>0</td>
                                    <td>0</td>

                                @endif


                            @else
                                <td>0</td>
                                <td>0</td>

                            @endif

                        @endforeach

                        <?php $totalCandidates = $totalCandidates + $total_pass_reserve; $total_g = $total_g+ $total_girl;?>

                        <td>{{$total_pass_reserve}} </td>
                        <td>{{$total_girl}} </td>

                    </tr>

                @endforeach

                <tr>
                    <td><strong>Total</strong></td>
                    <?php $index =0; $gIndex=0; ?>
                    @foreach($total_pass_reserve_by_grade['Pass']['total'] as $total)
                        <td>{{  $total + $total_pass_reserve_by_grade['Reserve']['total'][$index] }}</td>
                        <td>{{  $total_pass_reserve_by_grade['Pass']['F'][$index] + $total_pass_reserve_by_grade['Reserve']['F'][$index] }}</td>

                        <?php $index++;?>

                    @endforeach

                    <td style="color: darkred;">
                        {{$totalCandidates}}
                    </td>
                    <td style="color: darkred;">
                        {{$total_g}}
                    </td>
                </tr>
                </tbody>
            </table>

            <strong class="text-center"> Percentage </strong>
            <table class="table table-bordered" width="100%">
                <thead>
                <tr>
                    <th rowspan="2">Departments</th>
                    <th colspan="2">A</th>
                    <th colspan="2">B</th>
                    <th colspan="2">C</th>
                    <th colspan="2">D</th>
                    <th colspan="2">E</th>
                    <th colspan="2">Total</th>

                </tr>
                <tr>
                    <th>Total</th>
                    <th>F</th>
                    <th>Total</th>
                    <th>F</th>
                    <th>Total</th>
                    <th>F</th>
                    <th>Total</th>
                    <th>F</th>
                    <th>Total</th>
                    <th>F</th>
                    <th >Total</th>
                    <th >F</th>
                </tr>
                </thead>
                <tbody>

                {{--loop order by the department code:name abreviation --}}
                <?php $total_pass_reserve_by_grade=[]; $toal_pass_reserve_girl_by_grade= []; $totalCandidates = 0; $total_g = 0;?>
                @foreach($allCandidates as $key => $cand)

                    <tr>
                        <td>  {{$key}} </td>

                        <?php $total_pass_reserve = 0; $total_girl = 0; ?>

                        @foreach($arrayGrades as $grade)

                            @if(isset($cand[$grade]))

                                @if(isset($cand[$grade]['F']) && isset($cand[$grade]['M']))

                                    <?php
                                    $total_pass_reserve = $total_pass_reserve +count( $cand[$grade]['M']) + count($cand[$grade]['F']);
                                    $total_girl = $total_girl + count($cand[$grade]['F']);

                                    $total_pass_reserve_by_grade[$key]['total'][] = count( $cand[$grade]['M']) + count($cand[$grade]['F']);
                                    $total_pass_reserve_by_grade[$key]['F'][] = count($cand[$grade]['F']);
                                    ?>
                                    <td> {{count( $cand[$grade]['M']) + count($cand[$grade]['F'])}}</td>

                                    <td> {{sprintf('%0.2f',(count($cand[$grade]['F'])/(count( $cand[$grade]['M']) + count($cand[$grade]['F'])))*100)}} % </td>

                                @elseif(isset($cand[$grade]['F'])==false && isset($cand[$grade]['M'])== true )

                                    <?php
                                    $total_pass_reserve = $total_pass_reserve + ( $cand[$grade]['M']);

                                    $total_pass_reserve_by_grade[$key]['total'][] = count( $cand[$grade]['M']);
                                    $total_pass_reserve_by_grade[$key]['F'][] = 0;
                                    ?>
                                    <td> {{count( $cand[$grade]['M'])}}</td>
                                    <td> 0</td>

                                @elseif(isset($cand[$grade]['F'])==true && isset($cand[$grade]['M'])== false )

                                    <?php
                                    $total_pass_reserve = $total_pass_reserve + ( $cand[$grade]['F']);
                                    $total_girl = $total_girl + count($cand[$grade]['F']);

                                    $total_pass_reserve_by_grade[$key]['total'][] = count( $cand[$grade]['F']);
                                    $total_pass_reserve_by_grade[$key]['F'][] = count( $cand[$grade]['F']);
                                    ?>
                                    <td> {{count( $cand[$grade]['F'])}}</td>
                                    <td> {{count( $cand[$grade]['F'])}}</td>

                                @else
                                    <td>0</td>
                                    <td>0</td>

                                @endif


                            @else
                                <td>0</td>
                                <td>0</td>

                            @endif

                        @endforeach

                        <?php $totalCandidates = $totalCandidates + $total_pass_reserve; $total_g = $total_g+ $total_girl;?>
                        <td>{{$total_pass_reserve}} </td>
                        <td> {{sprintf('%0.2f',($total_girl/$total_pass_reserve)*100)}} % </td>
                    </tr>

                @endforeach

                <tr>
                    <td><strong>Total</strong></td>
                    <?php $index =0; $gIndex=0; ?>
                    @foreach($total_pass_reserve_by_grade['Pass']['total'] as $total)
                        <td>{{  $total + $total_pass_reserve_by_grade['Reserve']['total'][$index] }}</td>

                        <td> {{sprintf('%0.2f',(($total_pass_reserve_by_grade['Pass']['F'][$index] + $total_pass_reserve_by_grade['Reserve']['F'][$index])/($total + $total_pass_reserve_by_grade['Reserve']['total'][$index]))*100)}} % </td>

                        <?php $index++;?>

                    @endforeach

                    <td style="color: darkred;">
                        {{$totalCandidates}}
                    </td>

                    <td style="color: darkred;">
                        {{sprintf('%0.2f',($total_g/$totalCandidates)*100)}} %
                    </td>

                </tr>

                </tbody>
            </table>



            <div class="footer">
                <hr/>
                <span>Concours d'entree ITC {{isset($academic_year)?($academic_year->id-1):"N/A"}}</span>
                {{--<span class="pull-right">Page {{$page_number}} sur {{$total_page+2}}</span>--}}
            </div>

        </div>

    @endif

    @if($allStudents)

        <div class="page">

            <center><h2>Student Engineer Registration Statistic</h2></center>
            <strong class="text-center"> Numeration </strong>

            <table class="table table-bordered" width="100%">
                <thead>
                <tr>
                    <th rowspan="2">Departments</th>
                    <th colspan="2">A</th>
                    <th colspan="2">B</th>
                    <th colspan="2">C</th>
                    <th colspan="2">D</th>
                    <th colspan="2">E</th>
                    <th colspan="2">Total</th>

                </tr>
                <tr>
                    <th>Total</th>
                    <th>F</th>
                    <th>Total</th>
                    <th>F</th>
                    <th>Total</th>
                    <th>F</th>
                    <th>Total</th>
                    <th>F</th>
                    <th>Total</th>
                    <th>F</th>
                    <th >Total</th>
                    <th >F</th>
                </tr>
                </thead>
                <tbody>

                {{--loop order by the department code:name abreviation --}}
                <?php $total_pass_reserve_by_grade=[]; $toal_pass_reserve_girl_by_grade= []; $totalCandidates = 0; $total_g = 0;?>
                @foreach($allStudents as $key => $cand)

                    <tr>
                        <td>  {{$key}} </td>

                        <?php $total_TC = 0; $total_girl = 0; ?>

                        @foreach($arrayGrades as $grade)

                            @if(isset($cand[$grade]))

                                @if(isset($cand[$grade]['F']) && isset($cand[$grade]['M']))

                                    <?php
                                    $total_TC = $total_TC +count( $cand[$grade]['M']) + count($cand[$grade]['F']);
                                    $total_girl = $total_girl + count($cand[$grade]['F']);
                                    ?>
                                    <td> {{count( $cand[$grade]['M']) + count($cand[$grade]['F'])}}</td>
                                    <td> {{count($cand[$grade]['F'])}}</td>

                                @elseif(isset($cand[$grade]['F'])== false && isset($cand[$grade]['M'])== true)

                                    <?php
                                    $total_TC = $total_TC +count( $cand[$grade]['M']);

                                    ?>
                                    <td> {{count( $cand[$grade]['M'])}}</td>
                                    <td> 0</td>
                                @elseif(isset($cand[$grade]['F'])== true && isset($cand[$grade]['M'])== false)

                                    <?php
                                    $total_TC = $total_TC +count($cand[$grade]['F']);
                                    $total_girl = $total_girl + count($cand[$grade]['F']);
                                    ?>
                                    <td> {{ count($cand[$grade]['F'])}}</td>
                                    <td> {{count($cand[$grade]['F'])}}</td>

                                @else

                                    <td>0</td>
                                    <td>0</td>

                                @endif


                            @else
                                <td>0</td>
                                <td>0</td>

                            @endif

                        @endforeach

                        <?php $totalCandidates = $totalCandidates + $total_TC; $total_g = $total_g+ $total_girl;?>

                        <td>{{$total_TC}} </td>
                        <td>{{$total_girl}} </td>

                    </tr>

                @endforeach

                </tbody>
            </table>






            <strong class="text-center"> Percentage </strong>
            <table class="table table-bordered" width="100%">
                <thead>
                <tr>
                    <th rowspan="2">Departments</th>
                    <th colspan="2">A</th>
                    <th colspan="2">B</th>
                    <th colspan="2">C</th>
                    <th colspan="2">D</th>
                    <th colspan="2">E</th>
                    <th colspan="2">Total</th>

                </tr>
                <tr>
                    <th>Total</th>
                    <th>F</th>
                    <th>Total</th>
                    <th>F</th>
                    <th>Total</th>
                    <th>F</th>
                    <th>Total</th>
                    <th>F</th>
                    <th>Total</th>
                    <th>F</th>
                    <th >Total</th>
                    <th >F</th>
                </tr>
                </thead>
                <tbody>

                {{--loop order by the department code:name abreviation --}}
                <?php $total_pass_reserve_by_grade=[]; $toal_pass_reserve_girl_by_grade= []; $totalCandidates = 0; $total_g = 0;?>
                @foreach($allStudents as $key => $cand)

                    <tr>
                        <td>  {{$key}} </td>

                        <?php $total_TC = 0; $total_girl = 0; ?>

                        @foreach($arrayGrades as $grade)

                            @if(isset($cand[$grade]))

                                @if(isset($cand[$grade]['F']) && isset($cand[$grade]['M']) )

                                    <?php
                                    $total_TC = $total_TC +count( $cand[$grade]['M']) + count($cand[$grade]['F']);
                                    $total_girl = $total_girl + count($cand[$grade]['F']);
                                    ?>
                                    <td> {{count( $cand[$grade]['M']) + count($cand[$grade]['F'])}}</td>

                                    <td> {{sprintf('%0.2f',(count($cand[$grade]['F'])/(count( $cand[$grade]['M']) + count($cand[$grade]['F'])))*100)}} % </td>

                                @elseif(isset($cand[$grade]['F'])== false && isset($cand[$grade]['M'])== true )

                                    <?php
                                    $total_TC = $total_TC +count( $cand[$grade]['M']);

                                    ?>
                                    <td> {{count( $cand[$grade]['M'])}}</td>
                                    <td> 0</td>


                                @elseif(isset($cand[$grade]['F'])== true && isset($cand[$grade]['M'])== false )

                                    <?php
                                    $total_TC = $total_TC + count($cand[$grade]['F']);
                                    $total_girl = $total_girl + count($cand[$grade]['F']);
                                    ?>
                                    <td> {{count($cand[$grade]['F'])}}</td>
                                    <td> {{sprintf('%0.2f',(count($cand[$grade]['F'])/(count($cand[$grade]['F'])))*100)}} % </td>


                                @else

                                    <td>0</td>
                                    <td>0</td>

                                @endif


                            @else
                                <td>0</td>
                                <td>0</td>

                            @endif

                        @endforeach

                        <?php $totalCandidates = $totalCandidates + $total_TC; $total_g = $total_g+ $total_girl;?>

                        <td>{{$total_TC}} </td>

                        <td> {{sprintf('%0.2f',($total_girl/$total_TC)*100)}} % </td>

                    </tr>

                @endforeach

                </tbody>
            </table>

            <div class="footer">
                <hr/>
                <span>Concours d'entree ITC {{isset($academic_year)?($academic_year->id-1):"N/A"}}</span>
                {{--<span class="pull-right">Page {{$page_number+1}} sur {{$total_page+2}}</span>--}}
            </div>

        </div>

    @endif

@endsection

@section('scripts')
    <script>

    </script>
@stop
