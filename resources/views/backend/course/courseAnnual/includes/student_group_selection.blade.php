{{--{!! Form::select('group',$groups,null, array('class'=>'form-control','id'=>'filter_student_group','placeholder'=>'All Group')) !!}--}}

<select name="student_group" id="filter_student_group" style="margin-left: 5px">
    <option value=""> All Groups</option>
    @foreach($groups as $group)
    <option value="{{$group}}">{{$group}}</option>
    @endforeach
</select>