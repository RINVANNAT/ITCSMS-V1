@if(isset($allStudents['TC']))

    <?php $students =  $allStudents['TC'];?>


    <table id="datatable" style="display: none">
        <thead>
        <tr>
            <th></th>
            <th>Total</th>
            <th>Female</th>
        </tr>
        </thead>
        <tbody>
        @foreach($arrayGrades as $grade)
            <tr>
                <th>{{$grade}}</th>
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
            <th>Total_Pass</th>
            <th>Pass_Female</th>
            <th>Total_Reserve</th>
            <th>Reserve_Female</th>
        </tr>
        </thead>
        <tbody>
        @foreach($arrayGrades as $grade)
            <tr>
                <th>{{$grade}}</th>
                <td>{{ count($pass[$grade]['M']) + count($pass[$grade]['F'])}}</td>
                <td>{{count($pass[$grade]['F'])}}</td>

                <td>{{ count($reserve[$grade]['M']) + count($reserve[$grade]['F'])}}</td>
                <td>{{count($reserve[$grade]['F'])}}</td>
            </tr>
        @endforeach

        </tbody>
    </table>

@endif


