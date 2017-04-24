<select name="academicYear">
    <option selected disabled>Academic</option>
    @foreach($academicYears as $academicYear)
        <option value="{{ $academicYear->id }}">{{ $academicYear->name_latin }}</option>
    @endforeach
</select>

<select name="degree">
    <option selected disabled>Degree</option>
    @foreach($degrees as $degree)
        <option value="{{ $degree->id }}">{{ $degree->name_en }}</option>
    @endforeach
</select>

<select name="grade">
    <option selected disabled>Grade</option>
    @foreach($grades as $grade)
        <option value="{{ $grade->id }}">{{ $grade->name_en }}</option>
    @endforeach
</select>

<select name="option">
    <option selected disabled>Option</option>
    @foreach($options as $option)
        <option value="{{ $option->id }}">{{ $option->code }}</option>
    @endforeach
</select>

<select name="semester">
    <option selected disabled>Semester</option>
    @foreach($semesters as $semester)
        <option value="{{ $semester->id }}">{{ $semester->name_en }}</option>
    @endforeach
</select>

<select name="weekly">
    <option selected disabled>Weekly</option>
    @foreach($semesters as $semester)
        <option value="{{ $semester->id }}">{{ $semester->name_en }}</option>
    @endforeach
</select>