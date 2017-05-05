<button class="btn btn-sm btn_add_course_session" style="margin-bottom: 10px;">Add Course Session</button>
<div class="add_session_wrapper box-body with-border text-muted well well-sm no-shadow" style="padding: 0 10px 0 10px; display: none">
    <form class="form_add_session" style="padding: 0 10px 0 10px;">
        <div class="row">
            <div class="col-md-2" style="padding-left:3px;padding-right: 3px;">
                <div class="form-group">
                    <label for="session_time_course">Course</label>
                    <input type="number" name="time_course" min="0" max="{{$course_annual->time_course}}" value="{{$course_annual->time_course}}" class="form-control" id="session_time_course">
                    <input type="hidden" name="course_annual_id" value="{{$course_annual->id}}" />
                </div>
            </div>
            <div class="col-md-2" style="padding-left:3px;padding-right: 3px;">
                <div class="form-group">
                    <label for="session_time_td">TD</label>
                    <input type="number" name="time_td" min="0" max="{{$course_annual->time_td}}" value="{{$course_annual->time_td}}" class="form-control" id="session_time_td">
                </div>
            </div>
            <div class="col-md-2" style="padding-left:3px;padding-right: 3px;">
                <div class="form-group">
                    <label for="session_time_tp">TP</label>
                    <input type="number" name="time_tp" min="0" max="{{$course_annual->time_tp}}" value="{{$course_annual->time_tp}}" class="form-control" id="session_time_tp">
                </div>
            </div>
            <div class="col-md-6" style="padding-left:3px;padding-right: 3px;">
                <div class="form-group">
                    <label for="time_tp">Lecturer</label>
                    {!! Form::select('employee',[],null,['id'=>'employee','class'=>"select_employee form-control",'style'=>'width:100%;']) !!}
                    {{ Form::hidden('employee_id', null, ['class' => 'form-control', 'id'=>'session_lecturer']) }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8" style="padding-left:3px;padding-right: 3px;">
                <div class="form-group">
                    <label for="groups">Groups</label>
                    @if(isset($groups))

                        @foreach($groups as $group)
                            <?php $index =0;?>

                            @if($group != null)

                                <?php $status =true;?>

                                    <label for="group"> <input type="checkbox" name="groups[]" class="each-check-box" value="{{$group->group_id}}"> {{$group->group_code}}</label>

                            @endif

                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-xs pull-right btn_cancel_course_session" style="margin: 5px;">Cancel</button>
                <button type="button" class="btn btn-xs btn-danger pull-right btn_save_course_session" style="margin: 5px;">Save</button>
            </div>
        </div>
    </form>
</div>
@if(empty($course_sessions))
    <div class="course_session_message col-sm-12 box-body with-border text-muted well well-sm no-shadow" style="padding: 20px; min-height: 50px;">
        <center><h4>Related course session is empty. You may click button Add to create course sessions.</h4></center>
    </div>
@else
    <ul class="todo-list ui-sortable">
        @foreach($course_sessions as $course_session)
        <li>
            <span class="handle ui-sortable-handle">
                <i class="fa fa-ellipsis-v"></i>
                <i class="fa fa-ellipsis-v"></i>
            </span>

            <span class="text">{{$course_session->name}}</span>
            <br/>
            <span style="margin-left: 28px;">(C={{$course_session->time_course}} | TD={{$course_session->time_td}} | TP={{$course_session->time_tp}})</span>
            <small class="label label-danger"><i class="fa fa-user"></i> {{$course_session->employee == ""? "NA":$course_session->employee}}</small>
            <div class="tools">
                {{--<i class="fa fa-edit btn_edit_course_session"--}}
                   {{--data-id="{{$course_session->id}}"--}}
                   {{--data-time_course="{{$course_session->time_course}}"--}}
                   {{--data-time_tp="{{$course_session->time_tp}}"--}}
                   {{--data-time_td="{{$course_session->time_td}}"--}}
                   {{--data-lecturer_id="{{$course_session->lecturer_id}}"--}}
                   {{--data-lecturer="{{$course_session->employee}}"--}}
                   {{--data-groups="{{$course_session->groups}}"--}}
                {{--></i>--}}
                <i class="fa fa-trash-o btn_delete_course_session" data-url="{!! route('admin.course.course_session.destroy',$course_session->id) !!}"></i>
            </div>
            <br/>
            <span style="margin-left: 28px;">Groups:
                @if(isset($selectedGroups[$course_session->id]))
                    @foreach($selectedGroups[$course_session->id] as $group)
                        {{$group->code}}
                    @endforeach
                @endif

            </span>
        </li>
        @endforeach

    </ul>
@endif