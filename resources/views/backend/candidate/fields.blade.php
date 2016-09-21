{!! Form::hidden('exam_id',$exam->id)!!}
{!! Form::hidden('academic_year_id',$exam->academic_year_id)!!}
{!! Form::hidden('studentBac2_id',$studentBac2==null?null:$studentBac2->id)!!}
{!! Form::hidden('degree_id',1) !!}
@if(isset($candidate) && $candidate != null)
    {!! Form::hidden('highschool_id',$candidate->highschool_id, array('id'=>'highschool_id'))!!}
@else
    {!! Form::hidden('highschool_id',isset($studentBac2) ?$studentBac2->highschool_id:null,array('id'=>'highschool_id'))!!}
@endif
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

    <div class="form-group col-sm-6">
        {!! Form::label('register_from',trans('labels.backend.candidates.fields.register_from'),array('class'=>'col-sm-4 control-label required')) !!}
        <div class="col-sm-8">
            @if(isset($candidate) && $candidate != null)
                {!! Form::select('register_from', ['ITC'=>'ITC','Ministry'=>'Ministry'],$candidate->register_from, array('class'=>'form-control input','id'=>'candidate_register_from','required'=>'required')) !!}
            @else
                @if($exam->type_id == 2)
                    {!! Form::select('register_from', ['ITC'=>'ITC'],['ITC'=>'ITC'], array('class'=>'form-control input','id'=>'candidate_register_from','disabled','required'=>'required')) !!}
                @else
                    {!! Form::select('register_from', [""=>"",'ITC'=>'ITC','Ministry'=>'Ministry'],null, array('class'=>'form-control input','id'=>'candidate_register_from','required'=>'required')) !!}
                @endif
            @endif

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

    <div class="form-group col-sm-6 required">
        {!! Form::label('dob',trans('labels.backend.candidates.fields.dob'),array('class'=>'col-sm-4 control-label required')) !!}
        <div class="col-sm-8">
            @if(isset($candidate) && $candidate != null)
                {!! Form::text('dob', $candidate->dob->format("d/m/Y"), array('class'=>'form-control date-form input','placeholder'=>'Birth Date','id'=>'candidate_dob','required'=>'required',isset($studentBac2)?"disabled":"")) !!}
            @else
                {!! Form::text('dob', isset($studentBac2)?$studentBac2->dob:null, array('class'=>'form-control date-form input','placeholder'=>'Birth Date','id'=>'candidate_dob','required'=>'required',isset($studentBac2)?"disabled":"")) !!}
            @endif
        </div>
    </div>
</div>


<h3 style="font-size: 20px;"><i class="fa fa-history"></i> {{trans('labels.backend.candidates.header.study_record')}}
</h3>
<hr style="margin-top:0px;"/>

<div class="row no-margin">
    <div class="row no-margin">
        <div class="form-group col-sm-6 required">
            {!! Form::label('highschool_id',trans('labels.backend.candidates.fields.highschool_id'),array('class'=>'col-sm-4 control-label required')) !!}
            <div class="col-sm-8">

                <select name="highschool_name" class="form-control input" id="candidate_highschool_name" required {{isset($studentBac2)?"disabled":""}}>
                    @if(isset($highschool) && $highschool != null)
                        @foreach($highschool as $key => $value)
                        <option value="{!!$value!!}" selected="selected">{{$key}}</option>
                        @endforeach
                    @endif
                </select>

            </div>
        </div>

        <div class="form-group col-sm-6 required">
            {!! Form::label('province_id',trans('labels.backend.candidates.fields.origin_id'),array('class'=>'col-sm-4 control-label required')) !!}
            <div class="col-sm-8">
                {!! Form::select('province_id',$provinces, isset($studentBac2->province_id)?$studentBac2->province_id:null, array('class'=>'form-control input','placeholder'=>'Origin','rows'=>3,'id'=>'candidate_province_id','required'=>'required',isset($studentBac2)?"disabled":"")) !!}
            </div>
        </div>
    </div>

    <div class="row no-margin">
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
    </div>

    <div class="row no-margin">
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
    </div>
    <div class="row no-margin">
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

</div>

<h3 style="font-size: 20px;"><i class="fa fa-mortar-board"></i> {{trans('labels.backend.candidates.header.academic_information')}}
</h3>
<hr style="margin-top:0px;"/>

@if($exam->type_id == 1)

@elseif($exam->type_id == 2)
    <div class="row no-margin">
        <div class="form-group col-sm-12 required" id="choose_department">
            {!! Form::hidden('degree_id',2) !!}

            <div class="col-sm-12">
                <table id="choose_department_table">
                    <tr>
                        @foreach($departments as $department)
                            <td class="choose_department_cell"><center><b>{!! $department->code !!}</b></center></td>
                        @endforeach
                    </tr>
                    <tr>
                        @if(isset($candidate) && $candidate != null)
                            @foreach($candidate->departments as $department)
                                <td class="choose_department_cell">
                                    <div class="col-md-12 col-sm-12">
                                        {!! Form::text('choice_department['.$department->id.']', $department->pivot->rank, array('class'=>'form-control department_choice input','id'=>$department->code.'_rank','style'=>'padding:0px;border:0;border-bottom: 2px dotted;',"maxlength"=>"1",'required'=>'required')) !!}
                                    </div>
                                </td>
                            @endforeach
                        @else
                            @foreach($departments as $department)
                                <td class="choose_department_cell">
                                    <div class="col-md-12 col-sm-12">
                                        {!! Form::text('choice_department['.$department->id.']', null, array('class'=>'form-control department_choice input','id'=>$department->code.'_rank','style'=>'padding:0px;border:0;border-bottom: 2px dotted;',"maxlength"=>"1",'required'=>'required')) !!}
                                    </div>
                                </td>
                            @endforeach
                        @endif
                    </tr>
                </table>

            </div>
        </div>
    </div>
@endif

<div class="form-group col-sm-6 required">
    {!! Form::label('promotion',trans('labels.backend.candidates.fields.promotion_id'),array('class'=>'col-sm-4 control-label required')) !!}
    <div class="col-sm-8">
        {!! Form::select('promotion_id',$promotions, [key($promotions)=>$promotions[key($promotions)]], array('class'=>'form-control input','id'=>'candidate_promotion_id','placeholder'=>'Promotion', 'Required'=>'required')) !!}
    </div>
</div>
