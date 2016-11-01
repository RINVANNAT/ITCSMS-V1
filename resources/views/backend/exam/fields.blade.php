<div class="col-md-12">
    <i id="btn_show_exam_info" class="btn btn-primary fa fa-plus">  </i>

    <div id="exam_blog">


        <div class="form-group">
            {!! Form::label('name', trans('labels.backend.exams.fields.name'), ['class' => 'col-lg-2 control-label required']) !!}
            <div class="col-lg-4">
                {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
            </div>
        </div><!--form control-->

        <div class="form-group">
            {!! Form::label('date_start_end', trans('labels.backend.exams.fields.date_start_end'), ['class' => 'col-lg-2 control-label required']) !!}
            <div class="col-lg-10">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="date_start_end" class="date-range form-control pull-right" required id="date_start_end" value="{!! isset($exam)?$exam->date_start->format('d/m/Y')." - ".$exam->date_end->format('d/m/Y'):"" !!}">
                </div>
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('success_registration_date_start_end', trans('labels.backend.exams.fields.success_registration_date_start_end'), ['class' => 'col-lg-2 control-label required']) !!}
            <div class="col-lg-10">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="success_registration_date_start_end" class="date-range form-control pull-right" required id="success_registration_date_start_end" value="{!! isset($exam) && $exam->success_registration_start != null?$exam->success_registration_start->format('d/m/Y')." - ".$exam->success_registration_stop->format('d/m/Y'):"" !!}">
                </div>
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('reserve_registration_date_start_end', trans('labels.backend.exams.fields.reserve_registration_date_start_end'), ['class' => 'col-lg-2 control-label required']) !!}
            <div class="col-lg-10">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="reserve_registration_date_start_end" class="date-range form-control pull-right" required id="reserve_registration_date_start_end" value="{!! isset($exam) && $exam->reserve_registration_start != null?$exam->reserve_registration_start->format('d/m/Y')." - ".$exam->reserve_registration_stop->format('d/m/Y'):"" !!}">
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('type_id', trans('labels.backend.exams.fields.type_id'), ['class' => 'col-lg-2 control-label required']) !!}
            <div class="col-lg-10">
                {!! Form::select('type_id',$examType, null, ['class' => 'form-control', 'required']) !!}
            </div>
        </div><!--form control-->
        <div class="form-group">
            {!! Form::label('academic_year_id', trans('labels.backend.exams.fields.academic_year_id'), ['class' => 'col-lg-2 control-label required']) !!}
            <div class="col-lg-10">
                {!! Form::select('academic_year_id',$academicYear, null, ['class' => 'form-control']) !!}
            </div>
        </div><!--form control-->


        <div class="form-group">
            {!! Form::label('description', trans('labels.backend.exams.fields.description'), ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-10">
                {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
            </div>
        </div><!--form control-->

    </div>
</div>


