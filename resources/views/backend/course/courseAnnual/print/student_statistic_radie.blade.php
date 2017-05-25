@extends('backend.layouts.printing_landscape_a3')
@section('title')
    ITC-SMS | តារាងស្ថិតិ សិស្សលុបឈ្មោះ
@stop

@section('after-styles-end')
    <style>

        table, td, th, tr {
            border: 1px solid black;
            font-size: 16px;
        }

        .table {
            width: 100%;
            max-width: 80%;
            margin-bottom: 20px;
        }
        table, th {
            text-align: center;
        }



    </style>
@stop
@section('content')

    <div class="page">
        <div class="row">

            <div class="col-md-6 col-sm-offset-2">
                <center>
                    <h2>Statistic of Eliminated Student</h2>
                    <h4>Academic Year: {{$year->name_kh}}</h4>
                </center>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th colspan="4">Non-Eliminated-Student</th>
                    <th colspan="4">Eliminated-Student</th>
                    <th colspan="3"> Total</th>

                </tr>
                <tr>
                    <th colspan="2">Scholarship Awarded</th>
                    <th colspan="2">Non Scholarship Awarded</th>
                    <th colspan="2">Scholarship Awarded</th>
                    <th colspan="2">Non Scholarship Awarded</th>
                    <th colspan="2"> Students </th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    {{--Non Eliminated side--}}
                    <td>Total</td>
                    <td>F</td>
                    <td>Total</td>
                    <td>F</td>

                    {{--Eliminated side--}}
                    <td>Total</td>
                    <td>F</td>
                    <td>Total</td>
                    <td>F</td>

                    {{--total side--}}
                    <td>Total</td>
                    <td> F</td>
                </tr>
                <tr>
                    <td> {{count($scholarshipStudentPass)}}</td>
                    <td>{{count($f_student_pass)}}</td>
                    <td>{{count($studentPass) - count($scholarshipStudentPass)}}</td>
                    <td>{{count($f_student_pass_ids) - count($f_student_pass)}}</td>

                    <td>{{count($scholarshipStudentFail)}}</td>
                    <td>{{count($f_student_fail)}}</td>
                    <td> {{count($studentFail) - count($scholarshipStudentFail)}}</td>
                    <td> {{count($f_student_fail_ids) - count($f_student_fail)}}</td>

                    <td> {{count($studentPass) + count($studentFail)}}</td>
                    <td> {{count($f_student_pass_ids) + count($f_student_fail_ids)}}</td>

                </tr>

            </tbody>

        </table>


    </div>

@endsection

@section('scripts')
    <script>

    </script>
@stop