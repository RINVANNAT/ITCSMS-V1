<ul class="todo-list ui-sortable">
    @foreach($course_annuals as $course_annual)
    <li>
        <span class="handle ui-sortable-handle">
            <i class="fa fa-ellipsis-v"></i>
            <i class="fa fa-ellipsis-v"></i>
        </span>

        <span class="text">{{$course_annual->name}}</span>
        <small class="label label-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
        <div class="tools">
            <i class="fa fa-edit"></i>
            <i class="fa fa-trash-o"></i>
        </div>
    </li>
    @endforeach

</ul>