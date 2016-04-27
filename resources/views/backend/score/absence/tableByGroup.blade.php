@if($studentAnnuals->isEmpty())
    <div class="well text-center">No Absences found.</div>
@else
    <table class="table">
        <thead>
        <th>No</th>
        <th>Student Name</th>
        <th>Student id</th>
        <th> Todal Absence </th>
        <th width="50px">Action</th>
        </thead>
        <tbody>
        @foreach($studentAnnuals as  $key=>$studentAnnual)
            <tr>
                <td>{!! ++$key !!}</td>
                <td>{!! $studentAnnual->student->name_latin !!}</td>
                <td>{!! $studentAnnual->student->id_card !!}</td>
                <td>{!! $absencesCounts[ $studentAnnual->id] !!}</td>
                <!--- todo set url to edit page -->
                <td><a class="linkeditmany" href="">edit</a></td>
            </tr>
        @endforeach
    </table>
@endif