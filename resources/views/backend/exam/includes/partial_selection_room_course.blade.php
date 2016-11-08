
    <label class="col-sm-4 text_font"> {{ trans('labels.backend.exams.score.select_room') }} </label>
    <div class="col-sm-2 no-padding text_font">
        {!! Form::select('room',$rooms, null, array('class'=>'form-control area','id'=>'room_id_input_score')) !!}
    </div>


