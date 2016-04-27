@if($studentAnnuals->isEmpty())
    <div class="well text-center">No Students found.</div>
@else
    <table class="tablescore" id="tablescore">
        <thead >
        <th ></th>
        <th ></th>
        <th width="500px"></th>
        <th ></th>
        <th>Abs Total</th>
        @foreach($courseAnnuals as $courseAnnual)
            <th class="coursetitle" colspan="2"> <span class="coursetitlebg"> {!! $courseAnnual->course->name_en !!} </span></th>
        @endforeach
        <th>
        </th>
        <th>
        </th>

        <th>
        </th>

        <th width="50px"></th>
        </thead>



        <thead>
        <th>No</th>
        <th>Student id</th>
        <th>Student Name</th>
        <th>Sexe</th>
        <th>Abs Total</th>

        @foreach($courseAnnuals as $courseAnnual)
            <th id="{!! $courseAnnual->course->name_en !!}">Abs </th>
            <th id="{!! $courseAnnual->course->name_en !!}"> {!! $courseAnnual->course->credit !!} </th>
        @endforeach
        <th>
          Classement
        </th>
        <th> ranking </th>

        <th>
            Observation
        </th>
        <th width="50px">Action</th>
        </thead>
        <tbody>

        @foreach($studentAnnuals as $key=>$studentAnnual)
            <tr>
                <td>{!! ++$key!!}</td>

                <td>{!! $studentAnnual->student->id_card !!}</td>
                <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" >{!! $studentAnnual->student->name_latin !!}</td>
                <td>{!! $studentAnnual->student->gender->name_en !!}</td>
                <td>{!! $absencesCounts["totalabs"][$studentAnnual->id] !!}  </td>
                @foreach($courseAnnuals as $courseAnnual)
                    <td>{!! $absencesCounts[$studentAnnual->id][$courseAnnual->id] !!} </td>
                    <td>{!! $scoresDataViews[$studentAnnual->id][$courseAnnual->id]["scoreTotalinCourse"] !!}</td>
                @endforeach
                <td>
                    {!! $scoresDataViews[$studentAnnual->id]["moyenne"]!!}
                </td>
                <td>
                    {!! $scoresDataViews[$studentAnnual->id]["ranking"]!!}
                </td>
                <td>
                    <div class="eval-popup" stuid="{!! $studentAnnual->id !!}">   {!! $evalStatus[$studentAnnual->id]["name"]!!}  </div>
                </td>

                <td>
                    <a href="{!! route('scores.edit', [$scoresindex[$studentAnnual->id][$courseAnnual->id]->id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>
                    <a href="{!! route('scores.delete', [$scoresindex[$studentAnnual->id][$courseAnnual->id]->id]) !!}" onclick="return confirm('Are you sure wants to delete this Score?')"><i class="glyphicon glyphicon-remove"></i></a>
                </td>

            </tr>
        @endforeach

        </tbody>
    </table>
@endif

