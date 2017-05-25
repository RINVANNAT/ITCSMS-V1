<select name="group_name" id="group_name" class="" required>
    <option value="" disabled selected>Groups</option>
    @foreach($groups as $group)
        <option value="{{$group->id}}" > {{$group->code}}</option>
    @endforeach
</select>