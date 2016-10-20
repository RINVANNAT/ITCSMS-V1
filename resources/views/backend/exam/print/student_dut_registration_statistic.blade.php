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
        <center><h2>Student DUT Registration Statistic</h2></center>

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
                <th>TSG_A</th>
                <th>F</th>
                <th>TSG_B</th>
                <th>F</th>
                <th>TSG_C</th>
                <th>F</th>
                <th>TSG_D</th>
                <th>F</th>
                <th>TSG_E</th>
                <th>F</th>
                <th >M</th>
                <th >F</th>
            </tr>
            </thead>
            <tbody>

            {{--loop order by the department code:name abreviation --}}
            @foreach($allDepts as $dept)

                <tr>
                    <td>  {{$dept->name_abr}}</td>

                    @if(isset($candidates[$dept->name_abr]))

                        <?php $candidateDept = $candidates[$dept->name_abr]; $total_by_dept = 0; $total_F=0; ?>
                                <!-- here loop from grade A to E from 34 to 38-->
                        @foreach($arrayGrades as $key => $value)

                            @if(isset($candidateDept[$key])) <!--check here if the key of grade is existe--!>

                                <?php $cand = $candidateDept[$key]; $total_each_grade = 0;?>

                                <!--  this to find total student by each grade in each department-->
                                @foreach($cand as $val)
                                    <?php $total_each_grade = $total_each_grade + count($val);?>
                                @endforeach
                                <?php $total_by_dept = $total_by_dept + $total_each_grade;?>

                                <td> {{$total_each_grade}}</td>

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

                        <td>{{ $total_by_dept }}</td>
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
                    <td>{{$total['M'] + $total['F']}}</td>
                    <td>{{$total['F']}}</td>
                    <?php

                    $total_dept_grade_F= $total_dept_grade_F + $total['F'];
                    $total_dept_grade_M= $total_dept_grade_M + $total['M'];

                    ?>
                @endforeach

                <td style="color: darkred">

                    {{$total_dept_grade_M + $total_dept_grade_F}}
                </td>
                <td style="color: darkred">
                    {{$total_dept_grade_F}}
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
