{!! Form::hidden('exam_id',$exam->id)!!}
{!! Form::hidden('academic_year_id',$exam->academic_year_id)!!}
{!! Form::hidden('studentBac2_id',$studentBac2==null?null:$studentBac2->id)!!}
{!! Form::hidden('degree_id',1) !!}
{!! Form::hidden('highschool_id',isset($studentBac2)?$studentBac2->highschool_id:null,['id'=>'highschool_id']) !!}


<h3 style="font-size: 20px;"><i class="fa fa-user"></i> {{trans('labels.backend.candidates.header.personal_information')}}
</h3>
<hr style="margin-top:0px;"/>
<div class="row no-margin">
    <div class="form-group col-sm-6 required">
        {!! Form::label('register_id', trans('labels.backend.candidates.fields.register_id'),array('class'=>'col-sm-4 control-label required')) !!}
        <div class="col-sm-4">
            {!! Form::text('register_id', null, array('class'=>'form-control input','placeholder'=>'Register ID','id'=>'candidate_register_id','required','autofocus')) !!}
        </div>
    </div>
    <div class="form-group col-sm-6 required">
        {!! Form::label('gender_id',trans('labels.backend.candidates.fields.gender_id'),array('class'=>'col-sm-4 control-label required')) !!}
        <div class="col-sm-8">
            {!! Form::select('gender_id', $genders, isset($studentBac2)?$studentBac2->gender_id:null, array('class'=>'form-control input','placeholder'=>'Gender','id'=>'candidate_gender_id','required'=>'required',isset($studentBac2)?"disabled":"")) !!}
        </div>
    </div>

</div>
<div class="row no-margin">
    <div class="form-group col-sm-6 required">
        {!! Form::label('name_kh', trans('labels.backend.candidates.fields.name_kh'),array('class'=>'col-sm-4 control-label required')) !!}
        <div class="col-sm-8">
            {!! Form::text('name_kh', isset($studentBac2)?$studentBac2->name_kh:null, array('class'=>'form-control input','placeholder'=>'Name Khmer','id'=>'candidate_name_kh','required'=>'required',isset($studentBac2)?"disabled":"")) !!}
        </div>
    </div>

    <div class="form-group col-sm-6 required">
        {!! Form::label('dob',trans('labels.backend.candidates.fields.dob'),array('class'=>'col-sm-4 control-label required')) !!}
        <div class="col-sm-8">
            {!! Form::text('dob', isset($studentBac2)?$studentBac2->dob:null, array('class'=>'form-control date-form input','placeholder'=>'Birth Date','id'=>'candidate_dob','required'=>'required',isset($studentBac2)?"disabled":"")) !!}
        </div>
    </div>

</div>
<div class="row no-margin">

    <div class="form-group col-sm-6 required">
        {!! Form::label('name_latin',trans('labels.backend.candidates.fields.name_latin'),array('class'=>'col-sm-4 control-label required')) !!}
        <div class="col-sm-8">
            {!! Form::text('name_latin', null, array('class'=>'form-control input','placeholder'=>'Name Latin','id'=>'candidate_name_latin','required'=>'required')) !!}
        </div>
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('phone',trans('labels.backend.candidates.fields.phone'),array('class'=>'col-sm-4 control-label')) !!}
        <div class="col-sm-8">
            {!! Form::text('phone', null, array('class'=>'form-control input','placeholder'=>'Phone Number','id'=>'candidate_phone')) !!}
        </div>
    </div>
</div>

<div class="row no-margin">
    <div class="form-group col-sm-6 required">
        {!! Form::label('pob',trans('labels.backend.candidates.fields.pob'),array('class'=>'col-sm-4 control-label required')) !!}
        <div class="col-sm-8">
            {!! Form::select('pob',$provinces, isset($studentBac2->pob)?$studentBac2->pob:null, array('class'=>'form-control input','placeholder'=>'Place of birth','rows'=>2,'id'=>'candidate_pob','required'=>'required',isset($studentBac2)?"disabled":"")) !!}
        </div>
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('register_from',trans('labels.backend.candidates.fields.register_from'),array('class'=>'col-sm-4 control-label required')) !!}
        <div class="col-sm-8">
            {!! Form::select('register_from', ['ITC'=>'ITC','Ministry'=>'Ministry'],isset($studentBac2)?$studentBac2->status:null, array('class'=>'form-control input','id'=>'candidate_register_from','required'=>'required',isset($studentBac2)?"disabled":"")) !!}
        </div>
    </div>
</div>

<div class="row no-margin">


    <!--<div class="form-group col-sm-6">
        {!! Form::label('email',trans('labels.backend.candidates.fields.email'),array('class'=>'col-sm-4 control-label')) !!}
        <div class="col-sm-8">
            {!! Form::text('email', null, array('class'=>'form-control input','placeholder'=>'Email','id'=>'candidate_email')) !!}
        </div>
    </div> -->

</div>

<!--<div class="row no-margin">

    <div class="form-group col-sm-6">
        {!! Form::label('address_current',trans('labels.backend.candidates.fields.address_current'),array('class'=>'col-sm-4 control-label')) !!}
        <div class="col-sm-8">
            {!! Form::textarea('address_current', null, array('class'=>'form-control input','placeholder'=>'Current Address','rows'=>2,'id'=>'candidate_address_current')) !!}
        </div>
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('address',trans('labels.backend.candidates.fields.address'),array('class'=>'col-sm-4 control-label')) !!}
        <div class="col-sm-8">
            {!! Form::textarea('address', null, array('class'=>'form-control input','placeholder'=>'Permanent Address','rows'=>2,'id'=>'candidate_address_permanent')) !!}
        </div>
    </div>

</div> -->


<h3 style="font-size: 20px;"><i class="fa fa-history"></i> {{trans('labels.backend.candidates.header.study_record')}}
</h3>
<hr style="margin-top:0px;"/>

<div class="row no-margin">
    <!--<div class="form-group col-sm-6 required">
        {!! Form::label('mcs_no',trans('labels.backend.candidates.fields.mcs_no'),array('class'=>'col-sm-4 control-label')) !!}
        <div class="col-sm-8">
            {!! Form::text('mcs_no', isset($studentBac2->mcs_no)?$studentBac2->mcs_no:null, array('class'=>'form-control input','placeholder'=>'Ministry ID','id'=>'candidate_mcs_no')) !!}
        </div>
    </div>
    <div class="form-group col-sm-6 required">
        {!! Form::label('can_id',trans('labels.backend.candidates.fields.can_id'),array('class'=>'col-sm-4 control-label')) !!}
        <div class="col-sm-8">
            {!! Form::text('can_id', isset($studentBac2->can_id)?$studentBac2->can_id:null, array('class'=>'form-control input','placeholder'=>'BacII No','id'=>'candidate_can_id')) !!}
        </div>
    </div> -->
    <div class="form-group col-sm-6 required">
        {!! Form::label('highschool_id',trans('labels.backend.candidates.fields.highschool_id'),array('class'=>'col-sm-4 control-label required')) !!}
        <div class="col-sm-8">
            @if(isset($highschool))
                {!! Form::text('highschool_name', $highschool->name_kh, array('class'=>'form-control input',isset($studentBac2)?"disabled":"")) !!}
            @else
                {!! Form::select('highschool_name',[], null, array('class'=>'form-control input','placeholder'=>'High school','id'=>'candidate_highschool_name','required'=>'required')) !!}
            @endif
        </div>
    </div>

    <div class="form-group col-sm-6 required">
        {!! Form::label('province_id',trans('labels.backend.candidates.fields.origin_id'),array('class'=>'col-sm-4 control-label required')) !!}
        <div class="col-sm-8">
            {!! Form::select('province_id',$provinces, isset($studentBac2->province_id)?$studentBac2->province_id:null, array('class'=>'form-control input','placeholder'=>'Origin','rows'=>3,'id'=>'candidate_province_id','required'=>'required',isset($studentBac2)?"disabled":"")) !!}
        </div>
    </div>

    <div class="form-group col-sm-6 required">
        {!! Form::label('bac_percentile',trans('labels.backend.candidates.fields.bac_total_score'),array('class'=>'col-sm-4 control-label required')) !!}
        <div class="col-sm-4">
            {!! Form::text('bac_percentile', isset($studentBac2)?$studentBac2->percentile:null, array('class'=>'form-control input','placeholder'=>'Score','id'=>'candidate_bac_percentile','required'=>'required',isset($studentBac2)?"disabled":"")) !!}
        </div>
    </div>
    <div class="form-group col-sm-6 required">
        {!! Form::label('bac_math_grade',trans('labels.backend.candidates.fields.bac_math_grade'),array('class'=>'col-sm-4 control-label')) !!}
        <div class="col-sm-4">
            {!! Form::select('bac_math_grade',$gdeGrades, isset($studentBac2->bac_math_grade)?$studentBac2->bac_math_grade:null, array('class'=>'form-control input','placeholder'=>'','id'=>'candidate_bac_math_grade',isset($studentBac2)?"disabled":"")) !!}
        </div>
    </div>

    <div class="form-group col-sm-6 required">
        {!! Form::label('bac_total_grade',trans('labels.backend.candidates.fields.bac_total_grade'),array('class'=>'col-sm-4 control-label required')) !!}

        <div class="col-sm-4">
            {!! Form::select('bac_total_grade',$gdeGrades, isset($studentBac2)?$studentBac2->grade:null, array('class'=>'form-control input','placeholder'=>'','id'=>'candidate_bac_total_grade','required'=>'required',isset($studentBac2)?"disabled":"")) !!}
        </div>
    </div>

    <div class="form-group col-sm-6 required">
        {!! Form::label('bac_phys_grade',trans('labels.backend.candidates.fields.bac_phys_grade'),array('class'=>'col-sm-4 control-label')) !!}

        <div class="col-sm-4">
            {!! Form::select('bac_phys_grade',$gdeGrades, isset($studentBac2->bac_phys_grade)?$studentBac2->bac_phys_grade:null, array('class'=>'form-control input','placeholder'=>'','id'=>'candidate_bac_phys_grade',isset($studentBac2)?"disabled":"")) !!}
        </div>
    </div>

    <div class="form-group col-sm-6 required">
        {!! Form::label('bac_year',trans('labels.backend.candidates.fields.bac_year'),array('class'=>'col-sm-4 control-label required')) !!}
        <div class="col-sm-8">
            {!! Form::select('bac_year', $academicYears,isset($studentBac2->bac_year)?$studentBac2->bac_year:null, array('class'=>'form-control input','placeholder'=>'BacII Year','id'=>'candidate_bac_year','required'=>'required',isset($studentBac2)?"disabled":"")) !!}
        </div>
    </div>

    <div class="form-group col-sm-6 required">
        {!! Form::label('bac_chem_grade',trans('labels.backend.candidates.fields.bac_chem_grade'),array('class'=>'col-sm-4 control-label')) !!}
        <div class="col-sm-4">
            {!! Form::select('bac_chem_grade',$gdeGrades, isset($studentBac2->bac_chem_grade)?$studentBac2->bac_chem_grade:null, array('class'=>'form-control input','placeholder'=>'','id'=>'candidate_bac_chem_grade',isset($studentBac2)?"disabled":"")) !!}
        </div>
    </div>

</div>

<h3 style="font-size: 20px;"><i class="fa fa-mortar-board"></i> {{trans('labels.backend.candidates.header.academic_information')}}
</h3>
<hr style="margin-top:0px;"/>

<div class="form-group col-sm-6 required">
    {!! Form::label('promotion',trans('labels.backend.candidates.fields.promotion_id'),array('class'=>'col-sm-4 control-label required')) !!}
    <div class="col-sm-8">
        {!! Form::select('promotion_id',$promotions, $promotions[count($promotions)], array('class'=>'form-control input','id'=>'candidate_promotion_id','placeholder'=>'Promotion', 'Required'=>'required')) !!}
    </div>
</div>
@if($exam->type_id == 1)

        <!--<div class="row no-margin">
        <div class="form-group col-sm-6">

            {!! Form::label('math_c',trans('labels.backend.candidates.fields.math_score'),array('class'=>'col-sm-4 control-label')) !!}
            <div class="col-sm-8" style="padding: 0px;">
                <div class="col-sm-4" style="padding-right: 1px; display: table">
                    <div style="width: 80%;padding-right: 0px;">
                        {!! Form::text('math_c', null, array('class'=>'form-control input','placeholder'=>'C','style'=>'padding-left:5px;padding-right:5px;')) !!}
                    </div>
                    <span style="display:table-cell;padding-left: 0px;padding-right: 0px;float: none;vertical-align: middle;width: 20%">/30</span>
                </div>
                <div class="col-sm-4" style="padding-left: 8px;padding-right: 8px; display: table">
                    <div style="width: 80%;padding-right: 0px;">
                        {!! Form::text('math_w', null, array('class'=>'form-control input','placeholder'=>'W','style'=>'padding-left:5px;padding-right:5px;')) !!}
                    </div>
                    <span style="display:table-cell;padding-left: 0px;padding-right: 0px;float: none;vertical-align: middle;width: 20%">/30</span>
                </div>
                <div class="col-sm-4" style="padding-left: 1px; display: table">
                    <div style="width: 80%;padding-right: 0px;">
                        {!! Form::text('math_na', null, array('class'=>'form-control input','placeholder'=>'NA','style'=>'padding-left:5px;padding-right:5px;')) !!}
                    </div>
                    <span style="display:table-cell;padding-left: 0px;padding-right: 0px;float: none;vertical-align: middle;width: 20%">/30</span>
                </div>


            </div>
        </div>
        <div class="form-group col-sm-6">
            {!! Form::label('phys_chem_c',trans('labels.backend.candidates.fields.phys_chem_score'),array('class'=>'col-sm-4 control-label')) !!}
            <div class="col-sm-8" style="padding: 0px;">
                <div class="col-sm-4" style="padding-right: 1px; display: table">
                    <div style="width: 80%;padding-right: 0px;">
                        {!! Form::text('phys_chem_c', null, array('class'=>'form-control input','placeholder'=>'C','style'=>'padding-left:5px;padding-right:5px;')) !!}
                    </div>
                    <span style="display:table-cell;padding-left: 0px;padding-right: 0px;float: none;vertical-align: middle;width: 20%">/30</span>
                </div>
                <div class="col-sm-4" style="padding-left: 8px;padding-right: 8px; display: table">
                    <div style="width: 80%;padding-right: 0px;">
                        {!! Form::text('phys_chem_w', null, array('class'=>'form-control input','placeholder'=>'W','style'=>'padding-left:5px;padding-right:5px;')) !!}
                    </div>
                    <span style="display:table-cell;padding-left: 0px;padding-right: 0px;float: none;vertical-align: middle;width: 20%">/30</span>
                </div>
                <div class="col-sm-4" style="padding-left: 1px; display: table">
                    <div style="width: 80%;padding-right: 0px;">
                        {!! Form::text('phys_chem_na', null, array('class'=>'form-control input','placeholder'=>'NA','style'=>'padding-left:5px;padding-right:5px;')) !!}
                    </div>
                    <span style="display:table-cell;padding-left: 0px;padding-right: 0px;float: none;vertical-align: middle;width: 20%">/30</span>
                </div>


            </div>
        </div>
        <div class="form-group col-sm-6">
            {!! Form::label('logic_c',trans('labels.backend.candidates.fields.logic_score'),array('class'=>'col-sm-4 control-label')) !!}
            <div class="col-sm-8" style="padding: 0px;">
                <div class="col-sm-4" style="padding-right: 1px; display: table">
                    <div style="width: 80%;padding-right: 0px;">
                        {!! Form::text('logic_c', null, array('class'=>'form-control input','placeholder'=>'C','style'=>'padding-left:5px;padding-right:5px;')) !!}
                    </div>
                    <span style="display:table-cell;padding-left: 0px;padding-right: 0px;float: none;vertical-align: middle;width: 20%">/30</span>
                </div>
                <div class="col-sm-4" style="padding-left: 8px;padding-right: 8px; display: table">
                    <div style="width: 80%;padding-right: 0px;">
                        {!! Form::text('logic_w', null, array('class'=>'form-control input','placeholder'=>'W','style'=>'padding-left:5px;padding-right:5px;')) !!}
                    </div>
                    <span style="display:table-cell;padding-left: 0px;padding-right: 0px;float: none;vertical-align: middle;width: 20%">/30</span>
                </div>
                <div class="col-sm-4" style="padding-left: 1px; display: table">
                    <div style="width: 80%;padding-right: 0px;">
                        {!! Form::text('logic_na', null, array('class'=>'form-control input','placeholder'=>'NA','style'=>'padding-left:5px;padding-right:5px;')) !!}
                    </div>
                    <span style="display:table-cell;padding-left: 0px;padding-right: 0px;float: none;vertical-align: middle;width: 20%">/30</span>
                </div>


            </div>
        </div>

    </div>-->
@elseif($exam->type_id == 2)
    <div class="row no-margin">
        <div class="form-group col-sm-12 required" id="choose_department">
            {!! Form::hidden('degree_id',3) !!}
            {!! Form::label('GCA_rank',trans('labels.backend.candidates.fields.preferred_department'),array('class'=>'col-sm-12 control-label','style'=>'padding-bottom:10px;')) !!}
            <div class="col-sm-12">
                <table id="choose_department_table">
                    <tr>
                        @foreach($departments as $department)
                            <td class="choose_department_cell">{!! $department->name_kh !!}</td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($departments as $department)
                            <td class="choose_department_cell"><b>{!! $department->code !!}</b></td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($departments as $department)
                            <td class="choose_department_cell">
                                <div class="col-md-7 col-sm-7" style="height: 50px;display: table;">
                                    <span style="display: table-cell;vertical-align: middle">{{trans('labels.backend.candidates.priority_number')}}</span>
                                </div>
                                <div class="col-md-5 col-sm-5">
                                    {!! Form::text($department->code.'_rank', null, array('class'=>'form-control department_choice input','id'=>$department->code.'_rank','style'=>'padding:0px;border:0;border-bottom: 2px dotted;',"maxlength"=>"1",'required'=>'required')) !!}
                                </div>
                            </td>
                        @endforeach
                    </tr>
                </table>

            </div>
        </div>
    </div>
@endif