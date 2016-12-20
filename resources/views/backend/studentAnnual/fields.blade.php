<div>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#general_info" aria-controls="generals" role="tab" data-toggle="tab">
                {{ trans('labels.backend.students.tabs.general_information') }}
            </a>
        </li>
        <li role="presentation">
            <a href="#new_academic_info" aria-controls="new_academic_year" role="tab" data-toggle="tab">
                {{ trans('labels.backend.students.tabs.new_academic_information') }} <i style="color: red">*</i>
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="general_info" style="padding-top:20px">
            <h3 style="font-size: 20px;"><i class="fa fa-info-circle"></i> {{trans('labels.backend.students.basic_info')}}</h3>
            <hr/>
            <div class="row no-margin">
                <div class="col-lg-3">
                    <center>
                        <img style="width: 4cm;padding: 3px;border: 3px solid #d2d6de;"
                             class="profile-user-img img-responsive"
                             src="{{(isset($studentAnnual) && !empty($studentAnnual->student->photo))?$smis_server->value."/img/profiles/".$studentAnnual->student->photo:url('img/profiles/avatar.png')}}" alt="User profile picture">
                             {{--src="{{(isset($studentAnnual) && !empty($studentAnnual->student->photo))?url('img/profiles/'.$studentAnnual->student->photo):url('img/profiles/avatar.png')}}" alt="User profile picture"> --}}
                        </img>
                        <div style="width: 5cm;">
                            {!! Form::file('photo', null,['id'=>'photo']) !!}
                        </div>
                    </center>
                </div>
                <div class="col-lg-9">
                    {!! Form::label('id_card',trans('labels.backend.students.fields.id_card'),['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-md-10" style="padding-bottom: 10px;">
                        {!! Form::text('id_card', isset($studentAnnual)?$studentAnnual->student->id_card:null, array('class'=>'form-control','placeholder'=>'ID Card (Auto generate)', 'disabled'=>'disabled','id'=>'id_card')) !!}
                        @if(!isset($studentAnnual))
                        {!! Form::checkbox('enable_id_card','true') !!}
                        <span>Enable ID Card</span>
                        @endif
                    </div>

                    {!! Form::label('name', trans('labels.backend.students.fields.name_kh'), ['class' => 'col-lg-2 control-label required']) !!}
                    <div class="col-lg-10" style="padding-bottom: 10px;">
                        {!! Form::text('name_kh', isset($studentAnnual)?$studentAnnual->student->name_kh:null, ['class' => 'form-control','required'=>'required']) !!}
                    </div>

                    {!! Form::label('name', trans('labels.backend.students.fields.name_latin'), ['class' => 'col-lg-2 control-label required']) !!}
                    <div class="col-lg-10" style="padding-bottom: 10px;">
                        {!! Form::text('name_latin', isset($studentAnnual)?$studentAnnual->student->name_latin:null, ['class' => 'form-control','required'=>'required']) !!}
                    </div>
                    {!! Form::label('name', trans('labels.backend.students.fields.gender_id'), ['class' => 'col-lg-2 control-label required']) !!}
                    <div class="col-lg-10" style="padding-bottom: 10px;">
                        {!! Form::select('gender_id',$genders, isset($studentAnnual)?$studentAnnual->student->gender_id:null, ['class' => 'form-control','required'=>'required']) !!}
                    </div>
                    {!! Form::label('name', trans('labels.backend.students.fields.dob'), ['class' => 'col-lg-2 control-label required']) !!}
                    <div class="col-lg-10" style="padding-bottom: 10px;">
                        {!! Form::text('dob', isset($studentAnnual)?$studentAnnual->student->dob->format('d/m/Y'):null, ['class' => 'form-control','required'=>'required']) !!}
                    </div>
                    {!! Form::label('name', trans('labels.backend.students.fields.pob'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10" style="padding-bottom: 10px;">
                        {!! Form::select('pob',$origins, isset($studentAnnual->student->pob)?$studentAnnual->student->pob:null, ['class' => 'form-control']) !!}
                    </div>
                    {!! Form::label('name', trans('labels.backend.students.fields.radie'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10" style="padding-bottom: 10px;">
                        {!! Form::checkbox('radie', "radie",isset($studentAnnual)?$studentAnnual->student->radie:false) !!}
                    </div>
                    {!! Form::label('name', trans('labels.backend.students.fields.observation'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10" style="padding-bottom: 10px;">
                        {!! Form::textarea('observation', isset($studentAnnual)?$studentAnnual->student->observation:null, ['class' => 'form-control']) !!}
                    </div>
                </div>



            </div><!--form control-->


            <h3 style="font-size: 20px;"><i class="fa fa-forumbee"></i> {{trans('labels.backend.students.more_info')}}</h3>
            <hr/>

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#parent_info" aria-controls="generals" role="tab" data-toggle="tab">
                        {{ trans('labels.backend.students.tabs.contact_information') }}
                    </a>
                </li>
                <li role="presentation">
                    <a href="#high_school_info" aria-controls="new_academic_year" role="tab" data-toggle="tab">
                        {{ trans('labels.backend.students.tabs.parent_information') }}
                    </a>
                </li>
                <li role="presentation">
                    <a href="#award_info" aria-controls="new_academic_year" role="tab" data-toggle="tab">
                        {{ trans('labels.backend.students.tabs.high_school_information') }}
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="parent_info" style="padding-top:20px">
                    {!! Form::label('name', trans('labels.backend.students.fields.origin_id'), ['class' => 'col-lg-2 control-label required']) !!}
                    <div class="col-lg-10" style="padding-bottom: 10px;">
                        {!! Form::select('origin_id', $origins,isset($studentAnnual)?$studentAnnual->student->origin_id:null, array('class'=>'form-control', 'required'=>'required')) !!}
                    </div>
                    {!! Form::label('name', trans('labels.backend.students.fields.phone'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10" style="padding-bottom: 10px;">
                        {!! Form::text('phone',isset($studentAnnual)?$studentAnnual->student->phone:null, array('class'=>'form-control')) !!}
                    </div>
                    {!! Form::label('name', trans('labels.backend.students.fields.email'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10" style="padding-bottom: 10px;">
                        {!! Form::text('email',isset($studentAnnual)?$studentAnnual->student->email:null, array('class'=>'form-control')) !!}
                    </div>
                    {!! Form::label('name', trans('labels.backend.students.fields.current_address'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10" style="padding-bottom: 10px;">
                        {!! Form::text('address_current',isset($studentAnnual)?$studentAnnual->student->address_current:null, array('class'=>'form-control')) !!}
                    </div>
                    {!! Form::label('name', trans('labels.backend.students.fields.permanent_address'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10" style="padding-bottom: 10px;">
                        {!! Form::text('address',isset($studentAnnual)?$studentAnnual->student->address:null, array('class'=>'form-control')) !!}
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="high_school_info" style="padding-top:20px">
                    {!! Form::label('name', trans('labels.backend.students.fields.parent_name'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10" style="padding-bottom: 10px;">
                        {!! Form::text('parent_name',isset($studentAnnual)?$studentAnnual->student->parent_name:null, array('class'=>'form-control')) !!}
                    </div>
                    {!! Form::label('name', trans('labels.backend.students.fields.parent_occupation'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10" style="padding-bottom: 10px;">
                        {!! Form::text('parent_occupation',isset($studentAnnual)?$studentAnnual->student->parent_occupation:null, array('class'=>'form-control')) !!}
                    </div>
                    {!! Form::label('name', trans('labels.backend.students.fields.parent_address'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10" style="padding-bottom: 10px;">
                        {!! Form::text('parent_address',isset($studentAnnual)?$studentAnnual->student->parent_address:null, array('class'=>'form-control')) !!}
                    </div>
                    {!! Form::label('name', trans('labels.backend.students.fields.parent_phone'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10" style="padding-bottom: 10px;">
                        {!! Form::text('parent_phone',isset($studentAnnual)?$studentAnnual->student->parent_phone:null, array('class'=>'form-control')) !!}
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="award_info" style="padding-top:20px">
                    {!! Form::label('name', trans('labels.backend.students.fields.highschool_id'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10" style="padding-bottom: 10px;">
                        {!! Form::select('high_school_id',$highSchools,isset($studentAnnual->student->high_school_id)?$studentAnnual->student->high_school_id:null, array('class'=>'form-control')) !!}
                    </div>
                    {!! Form::label('name', trans('labels.backend.students.fields.mcs_no'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10" style="padding-bottom: 10px;">
                        {!! Form::text('mcs_no',isset($studentAnnual)?$studentAnnual->student->mcs_no:null, array('class'=>'form-control')) !!}
                    </div>
                    {!! Form::label('name', trans('labels.backend.students.fields.can_id'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10" style="padding-bottom: 10px;">
                        {!! Form::text('can_id',isset($studentAnnual)?$studentAnnual->student->can_id:null, array('class'=>'form-control')) !!}
                    </div>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="new_academic_info" style="padding-top:20px">
            {!! Form::label('name', trans('labels.backend.students.fields.academic_year_id'), ['class' => 'col-lg-2 control-label required']) !!}
            <div class="col-lg-10" style="padding-bottom: 10px;">
                {!! Form::select('academic_year_id',$academic_years,null, array('class'=>'form-control','required'=>'required')) !!}
            </div>
            {!! Form::label('name', trans('labels.backend.students.fields.promotion_id'), ['class' => 'col-lg-2 control-label required']) !!}
            <div class="col-lg-10" style="padding-bottom: 10px;">
                {!! Form::select('promotion_id',$promotions,null, array('class'=>'form-control','required'=>'required')) !!}
            </div>
            {!! Form::label('name', trans('labels.backend.students.fields.degree_id'), ['class' => 'col-lg-2 control-label required']) !!}
            <div class="col-lg-10" style="padding-bottom: 10px;">
                {!! Form::select('degree_id',$degrees,null, array('class'=>'form-control','required'=>'required')) !!}
            </div>
            {!! Form::label('name', trans('labels.backend.students.fields.grade_id'), ['class' => 'col-lg-2 control-label required']) !!}
            <div class="col-lg-10" style="padding-bottom: 10px;">
                {!! Form::select('grade_id',$grades,null, array('class'=>'form-control','required'=>'required')) !!}
            </div>
            {!! Form::label('name', trans('labels.backend.students.fields.department_id'), ['class' => 'col-lg-2 control-label required']) !!}
            <div class="col-lg-10" style="padding-bottom: 10px;">
                {!! Form::select('department_id',$departments,null, array('class'=>'form-control','required'=>'required')) !!}
            </div>
            {!! Form::label('name', trans('labels.backend.students.fields.department_option_id'), ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-10" style="padding-bottom: 10px;">
                {!! Form::select('department_option_id',$department_options,null, array('class'=>'form-control','placeholder'=>'')) !!}
            </div>
            {!! Form::label('name', trans('labels.backend.students.fields.group'), ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-10" style="padding-bottom: 10px;">
                {!! Form::text('group',null, array('class'=>'form-control')) !!}
            </div>
            {!! Form::label('name', trans('labels.backend.students.fields.history_id'), ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-10" style="padding-bottom: 10px;">
                {!! Form::select('history_id',$histories,null, array('class'=>'form-control','placeholder'=>'')) !!}
            </div>
            {!! Form::label('name', trans('labels.backend.students.fields.redouble_id'), ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-10" style="padding-bottom: 10px;">
                {!! Form::select('redouble_id',$redoubles,null, array('class'=>'form-control','placeholder'=>'')) !!}
            </div>
            {!! Form::label('name', trans('labels.backend.students.fields.scholarship_id'), ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-10" style="padding-bottom: 10px;">
                {!! Form::select('scholarship_ids[]',$scholarships,null, array('class'=>'form-control select2','multiple'=>'multiple','style'=>'width:100%')) !!}
            </div>
        </div>
    </div>
</div>
