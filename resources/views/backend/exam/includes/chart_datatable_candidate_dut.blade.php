
@if($status == 'candidate_dut_registration')


    <?php $arrayGrades = ['A' => '34','B' => '35', 'C' => '36', 'D' => '37', 'E' => '38']; ?>

    <table id="datatable_candidate_dut_registration" style="display: none">
        <thead>
        <tr>
            <th></th>
            <th>{{ trans('labels.backend.exams.chart.engineer_statistic.total_student') }}</th>
            <th>{{ trans('labels.backend.exams.chart.engineer_statistic.total_female') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($arrayGrades as $key => $grade)
            <tr>
                <th> {{ trans('labels.backend.exams.chart.engineer_statistic.grade') }} {{$key}}</th>
                <td>{{ count($candidateDuts[$grade]['M']) + count($candidateDuts[$grade]['F'])}}</td>
                <td>{{count($candidateDuts[$grade]['F'])}}</td>
            </tr>
        @endforeach

        </tbody>
    </table>

@endif



