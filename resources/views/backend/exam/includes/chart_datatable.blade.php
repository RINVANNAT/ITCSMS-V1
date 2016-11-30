@if(isset($allStudents['TC']))

    <?php $students =  $allStudents['TC'];?>


    <table id="datatable" style="display: none">
        <thead>
        <tr>
            <th></th>
            <th>{{ trans('labels.backend.exams.chart.engineer_statistic.total_student') }}</th>
            <th>{{ trans('labels.backend.exams.chart.engineer_statistic.total_female') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($arrayGrades as $grade)
            <tr>
                <th>{{ trans('labels.backend.exams.chart.engineer_statistic.grade') }} {{$grade}}</th>

                @if($students[$grade])
                    <td>{{ (isset($students[$grade]['M'])?count($students[$grade]['M']):0) + (isset($students[$grade]['F'])?count($students[$grade]['F']):0)}}</td>
                    <td>{{ (isset($students[$grade]['F'])?count($students[$grade]['F']):0)}}</td>
                @else

                    <td>0</td>
                    <td>0</td>

                @endif

            </tr>
        @endforeach

        </tbody>
    </table>

@else


    <table id="datatable" style="display: none">
        <thead>
        <tr>
            <th></th>
            <th>{{ trans('labels.backend.exams.chart.engineer_statistic.total_student') }}</th>
            <th>{{ trans('labels.backend.exams.chart.engineer_statistic.total_female') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($arrayGrades as $grade)
            <tr>
                <th>{{ trans('labels.backend.exams.chart.engineer_statistic.grade') }} {{$grade}}</th>
                    <td>0</td>
                    <td>0</td>
            </tr>
        @endforeach

        </tbody>
    </table>

@endif

@if($allCandidates)

    <?php $pass = $allCandidates['Pass']; $reserve = $allCandidates['Reserve']?>

    <table id="datatable_candidate_resutl" style="display: none">
        <thead>
        <tr>
            <th></th>
            <th>{{ trans('labels.backend.exams.chart.engineer_statistic.total_pass') }}</th>
            <th>{{ trans('labels.backend.exams.chart.engineer_statistic.pass_female') }}</th>
            <th>{{ trans('labels.backend.exams.chart.engineer_statistic.total_reserve') }}</th>
            <th>{{ trans('labels.backend.exams.chart.engineer_statistic.reserve_female') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($arrayGrades as $grade)

            <tr>
                <th>{{ trans('labels.backend.exams.chart.engineer_statistic.grade') }} {{$grade}}</th>

                @if(isset($pass[$grade]))

                    <td>{{ (isset($pass[$grade]['M'])?count($pass[$grade]['M']):0) + (isset($pass[$grade]['F'])?count($pass[$grade]['F']):0)}}</td>
                    <td>{{ isset($pass[$grade]['F'])?count($pass[$grade]['F']):0 }}</td>

                @else

                    <td>0</td>
                    <td>0</td>

                @endif

                @if(isset($reserve[$grade]))
                        <td>{{ (isset($reserve[$grade]['M'])?count($reserve[$grade]['M']):0) + (isset($reserve[$grade]['F'])?count($reserve[$grade]['F']):0)}}</td>
                        <td>{{ isset($reserve[$grade]['F'])?count($reserve[$grade]['F']):0 }}</td>
                @else

                    <td>0</td>
                    <td>0</td>
                @endif

            </tr>
        @endforeach

        </tbody>
    </table>

@endif


