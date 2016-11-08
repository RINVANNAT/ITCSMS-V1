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
                <td>{{ count($students[$grade]['M']) + count($students[$grade]['F'])}}</td>
                <td>{{count($students[$grade]['F'])}}</td>
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
                <td>{{ count($pass[$grade]['M']) + count($pass[$grade]['F'])}}</td>
                <td>{{count($pass[$grade]['F'])}}</td>

                <td>{{ count($reserve[$grade]['M']) + count($reserve[$grade]['F'])}}</td>
                <td>{{count($reserve[$grade]['F'])}}</td>
            </tr>
        @endforeach

        </tbody>
    </table>

@endif


