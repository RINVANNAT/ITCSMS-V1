<table class="table">
    <thead>
    <th>Score Attendance</th>
			<th>Score Practice</th>
			<th>Score Exam</th>
    <th width="50px">Action</th>
    </thead>
    <tbody>
    @foreach($scores as $score)
        <tr>
            <td>{!! $score->score10 !!}</td>
			<td>{!! $score->score30 !!}</td>
			<td>{!! $score->score60 !!}</td>
            <td>
                <a href="{!! route('scores.edit', [$score->id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>
                <a href="{!! route('scores.delete', [$score->id]) !!}" onclick="return confirm('Are you sure wants to delete this Score?')"><i class="glyphicon glyphicon-remove"></i></a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
