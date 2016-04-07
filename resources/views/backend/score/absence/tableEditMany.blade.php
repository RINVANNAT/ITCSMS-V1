@if($studentAnnuals->isEmpty())
    <div class="well text-center">No Absences found.</div>
@else
    {!! Form::open( array("url"=>"absences/updateMany","method"=>"PATCH"))!!}
    <table class="table">
        <thead>
        <th>No</th>
        <th>Student Name</th>
        <th>Student id</th>
        <th> Todal Absence </th>

        </thead>
        <tbody>
        @foreach($studentAnnuals as  $key => $studentAnnual)
            <tr>
                <td>{!! ++$key !!}</td>

                <td>{!! $studentAnnual->student->name_latin !!}</td>
                <td>{!! $studentAnnual->student->id_card !!}</td>
                <td>
                    {!!  Form::hidden('stuids[]', $studentAnnual->id) !!}
                    {!!  Form::hidden('stuname[]', $studentAnnual->student->name_latin) !!}
                    {!!  Form::text('absencecount[]',$absencesCounts[ $studentAnnual->id], ['class' => 'form-control']) !!}
                </td>


            </tr>
        @endforeach
        {!!  Form::hidden('fillter', true, ['id' => 'fillterhidden']) !!}
        {!!  Form::hidden('fillterdata', true, ['id' => 'fillterdatahidden']) !!}
    </table>

    </div>
    <div class="form-group col-sm-12">
        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endif