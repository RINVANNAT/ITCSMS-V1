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
                    <th rowspan="2">Departments</th>
                    <th colspan="2">A</th>
                    <th colspan="2">B</th>
                    <th colspan="2">C</th>
                    <th colspan="2">D</th>
                    <th colspan="2">E</th>
                    <th colspan="2">Total</th>

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
                    <th >M</th>
                    <th >F</th>
                </tr>
                </thead>
                <tbody>

                @foreach($allDepts as $dept)

                    <tr>
                        <td>  {{$dept->name_abr}}</td>



                        @if(isset($candidates[$dept->name_abr]))

                            <?php $candidateDept = $candidates[$dept->name_abr]; $total_M = 0; $total_F=0;?>

                            @foreach($arrayGrades as $key => $value)

                                @if(isset($candidateDept[$key]))
                                    <?php $cand = $candidateDept[$key]?>
                                    @if(isset($cand['M']))
                                        <?php $total_M = $total_M +  count($cand['M']);?>
                                        <td>{{count($cand['M'])}}</td>
                                    @else
                                        <td>0</td>
                                    @endif

                                    @if(isset($cand['F']))
                                            <?php $total_F = $total_F +  count($cand['F']);?>
                                        <td>{{count($cand['F'])}}</td>
                                    @else
                                        <td>0</td>
                                    @endif
                                @else
                                    <td>0</td>
                                    <td>0</td>

                                @endif

                            @endforeach

                                <td>{{$total_M}}</td>
                                <td>{{$total_F}}</td>

                        @else
                            @foreach($arrayGrades as $key => $val)
                                <td>0</td>
                                <td>0</td>
                            @endforeach

                                <td>0</td>
                                <td>0</td>
                        @endif

                    </tr>

                @endforeach

                <tr>
                    <td>Total</td>
                    <?php $total_dept_grade_F=0; $total_dept_grade_M = 0;?>
                    @foreach($totalBydept as $total)
                        <td>{{$total['M']}}</td>
                        <td>{{$total['F']}}</td>
                        <?php

                            $total_dept_grade_F= $total_dept_grade_F + $total['F'];
                            $total_dept_grade_M= $total_dept_grade_M + $total['M'];

                        ?>
                    @endforeach

                    <td >

                        {{$total_dept_grade_M}}
                    </td>
                    <td>
                        {{$total_dept_grade_F}}
                    </td>


                </tr>

                <tr>
                    <td> </td>
                    @foreach($totalBydept as $total)
                        <td colspan="2">{{$total['M'] + $total['F'] }}</td>
                    @endforeach
                    <td colspan="2" style="color: darkred">
                        {{$total_dept_grade}}
                    </td>
                </tr>

                </tbody>
            </table>





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
                    <th >M</th>
                    <th >F</th>
                </tr>
                </thead>
                <tbody>

                @foreach($allDepts as $dept)

                    <tr>
                        <td>  {{$dept->name_abr}}</td>



                        @if(isset($candidates[$dept->name_abr]))

                            <?php $candidateDept = $candidates[$dept->name_abr]; $total_M = 0; $total_F=0;?>

                            <?php
                                foreach($candidateDept as $candDept) {
                                    if(isset($candDept['M'])) {
                                        $total_M = $total_M +  count($candDept['M']);
                                    }
                                    if(isset($candDept['F'])) {
                                        $total_F = $total_F +  count($candDept['F']);
                                    }
                                }

                                $totalByDept = $total_M + $total_F;
                            ?>

                            @foreach($arrayGrades as $key => $value)

                                @if(isset($candidateDept[$key]))

                                    <?php $cand = $candidateDept[$key]?>

                                    @if(isset($cand['M']))

                                        <td>{{sprintf('%0.2f', (count($cand['M']) *100)/$totalByDept)}}</td>

                                    @else
                                        <td>0</td>
                                    @endif

                                    @if(isset($cand['F']))

                                        <td>{{ sprintf('%0.2f', (count($cand['F']) *100)/$totalByDept) }}</td>
                                    @else
                                        <td>0</td>
                                    @endif
                                @else
                                    <td>0</td>
                                    <td>0</td>

                                @endif

                            @endforeach

                            <td>{{ sprintf('%0.2f', ($total_M *100)/$totalByDept) }}</td>
                            <td>{{ sprintf('%0.2f', ($total_F *100)/$totalByDept) }}</td>

                        @else
                            @foreach($arrayGrades as $key => $val)
                                <td>0</td>
                                <td>0</td>
                            @endforeach

                            <td>0</td>
                            <td>0</td>
                        @endif

                    </tr>

                @endforeach

                <tr>
                    <td>Total</td>
                    @foreach($totalBydept as $total)
                        <td>{{sprintf('%0.2f', ($total['M'] *100)/$total_dept_grade)}}</td>
                        <td>{{sprintf('%0.2f', ($total['F'] *100)/$total_dept_grade)}}</td>

                    @endforeach

                    <td >


                        {{sprintf('%0.2f', ($total_dept_grade_M *100)/$total_dept_grade)}}
                    </td>
                    <td>
                        {{sprintf('%0.2f', ($total_dept_grade_F *100)/$total_dept_grade)}}
                    </td>
                </tr>

                <tr>
                    <td> </td>
                    @foreach($totalBydept as $total)
                        <td colspan="2">{{sprintf('%0.2f', (($total['M'] + $total['F']) *100)/$total_dept_grade)}}</td>

                    @endforeach

                    <td colspan="2" style="color: darkred">
                        {{100.00}}
                    </td>
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
