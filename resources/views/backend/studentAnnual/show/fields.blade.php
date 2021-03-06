
<div>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#general_info" aria-controls="generals" role="tab" data-toggle="tab">
                {{ trans('labels.backend.students.tabs.general_information') }}
            </a>
        </li>
        @foreach($student->studentAnnuals as $studentAnnual)
        <li role="presentation">
            <a href="#academic_{{$studentAnnual->academic_year->id}}" aria-controls="new_academic_year" role="tab" data-toggle="tab">
                {{ $studentAnnual->academic_year->name_kh }}
            </a>
        </li>
        @endforeach
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="general_info" style="padding-top:20px">
            <h3 style="font-size: 20px;"><i class="fa fa-info-circle"></i> {{trans('labels.backend.students.basic_info')}}</h3>
            <hr/>
            <div class="row no-margin">
                <div class="col-lg-3">
                    <center>
                        <img style="width: 6cm;padding: 3px;border: 3px solid #d2d6de;"
                             class="profile-user-img img-responsive"
                             src="{{config('app.smis_server')}}/img/profiles/{{$student->photo}}" alt="User profile picture"/>

                    </center>

                </div>
                <div class="col-lg-6">
                    <div class="row no-margin">
                        <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.id_card')}}</span>
                        <span class="show_value col-sm-8">{{$student->id_card}} &nbsp;</span>
                    </div>
                    <div class="row no-margin">
                        <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.name_kh')}}</span>
                        <span class="show_value col-sm-8">{{$student->name_kh}} &nbsp;</span>
                    </div>
                    <div class="row no-margin">
                        <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.name_latin')}}</span>
                        <span class="show_value col-sm-8">{{$student->name_latin}} &nbsp;</span>
                    </div>
                    <div class="row no-margin">
                        <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.gender_id')}}</span>
                        <span class="show_value col-sm-8">{{$student->gender->name_kh}} &nbsp;</span>
                    </div>
                    <div class="row no-margin">
                        <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.dob')}}</span>
                        <span class="show_value col-sm-8">
                            <?php
                                $dob = \Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $student->dob);
                            ?>
                            {{$dob->format('d/m/Y')}} &nbsp;
                        </span>
                    </div>
                    <div class="row no-margin">
                        <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.pob')}}</span>
                        <span class="show_value col-sm-8">{{$student->pob}} &nbsp;</span>
                    </div>

                    <div class="row no-margin">
                        <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.radie')}}</span>
                        <span class="show_value col-sm-8">{{$student->radie}} &nbsp;</span>
                    </div>
                    <div class="row no-margin">
                        <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.observation')}}</span>
                        <span class="show_value col-sm-8">{{$student->observation}} &nbsp;</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th colspan="4">{{trans('labels.backend.students.fields.redouble_id')}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @if(empty($student->redoubles->toArray()))
                            <tr>
                                <td colspan="2" style="text-align: center">
                                    <h4>Empty</h4>
                                </td>
                            </tr>
                        @else
                            <?php $a = 1 ?>
                            @foreach($student->redoubles as $redouble)
                                <tr style="color:#15c">
                                    <td>{{$a}}</td>
                                    <td>{{$redouble->name_en}}</td>
                                </tr>
                                <?php $a++ ?>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
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
                    <span class="show_label col-sm-2">{{trans('labels.backend.students.fields.origin_id')}}</span>
                    <span class="show_value col-sm-10">{{$student->origin->name_kh}} &nbsp;</span>

                    <span class="show_label col-sm-2">{{trans('labels.backend.students.fields.phone')}}</span>
                    <span class="show_value col-sm-10">{{$student->phone}} &nbsp;</span>

                    <span class="show_label col-sm-2">{{trans('labels.backend.students.fields.email')}}</span>
                    <span class="show_value col-sm-10">{{$student->email}} &nbsp;</span>

                    <span class="show_label col-sm-2">{{trans('labels.backend.students.fields.current_address')}}</span>
                    <span class="show_value col-sm-10">{{$student->address_current}} &nbsp;</span>

                    <span class="show_label col-sm-2">{{trans('labels.backend.students.fields.permanent_address')}}</span>
                    <span class="show_value col-sm-10">{{$student->address}} &nbsp;</span>

                </div>
                <div role="tabpanel" class="tab-pane" id="high_school_info" style="padding-top:20px">
                    <span class="show_label col-sm-2">{{trans('labels.backend.students.fields.parent_name')}}</span>
                    <span class="show_value col-sm-10">{{$student->parent_name}} &nbsp;</span>

                    <span class="show_label col-sm-2">{{trans('labels.backend.students.fields.parent_occupation')}}</span>
                    <span class="show_value col-sm-10">{{$student->parent_occupation}} &nbsp;</span>

                    <span class="show_label col-sm-2">{{trans('labels.backend.students.fields.parent_address')}}</span>
                    <span class="show_value col-sm-10">{{$student->parent_address}} &nbsp;</span>

                    <span class="show_label col-sm-2">{{trans('labels.backend.students.fields.parent_phone')}}</span>
                    <span class="show_value col-sm-10">{{$student->parent_phone}} &nbsp;</span>

                </div>
                <div role="tabpanel" class="tab-pane" id="award_info" style="padding-top:20px">
                    <span class="show_label col-sm-2">{{trans('labels.backend.students.fields.highschool_id')}}</span>
                    <span class="show_value col-sm-10">{{$student->high_school_id}} &nbsp;</span>

                    <span class="show_label col-sm-2">{{trans('labels.backend.students.fields.mcs_no')}}</span>
                    <span class="show_value col-sm-10">{{$student->mcs_no}} &nbsp;</span>

                    <span class="show_label col-sm-2">{{trans('labels.backend.students.fields.can_id')}}</span>
                    <span class="show_value col-sm-10">{{$student->can_id}} &nbsp;</span>

                </div>
            </div>
        </div>

        <?php
            $studentAnnuals = $student->studentAnnuals->sortByDesc('academic_year_id');
        ?>

        @foreach($studentAnnuals as $key => $studentAnnual)
        <div role="tabpanel" class="tab-pane" id="academic_{{$studentAnnual->academic_year->id}}" style="padding-top:20px">
            <div class="col-sm-6">
                <div class="row no-margin no-padding">
                    <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.academic_year_id')}}</span>
                    <span class="show_value col-sm-8">{{$studentAnnual->academic_year->name_kh}} &nbsp;</span>
                </div>
                <div class="row no-margin no-padding">
                    <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.promotion_id')}}</span>
                    <span class="show_value col-sm-8">{{$studentAnnual->promotion->name}} &nbsp;</span>
                </div>
                <div class="row no-margin no-padding">
                    <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.degree_id')}}</span>
                    <span class="show_value col-sm-8">{{$studentAnnual->degree->name_kh}} &nbsp;</span>
                </div>
                <div class="row no-margin no-padding">
                    <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.grade_id')}}</span>
                    <span class="show_value col-sm-8">{{$studentAnnual->grade->name_kh}} &nbsp;</span>
                </div>

                <div class="row no-margin no-padding">
                    <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.department_id')}}</span>
                    <span class="show_value col-sm-8">{{$studentAnnual->department->name_kh}} &nbsp;</span>
                </div>

                <div class="row no-margin no-padding">
                    <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.department_option_id')}}</span>
                    <span class="show_value col-sm-8">{{isset($studentAnnual->department_option)?$studentAnnual->department_option->name_kh:""}} &nbsp;</span>
                </div>

                <div class="row no-margin no-padding">
                    <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.group')}}</span>
                    <span class="show_value col-sm-8">{{$studentAnnual->group}} &nbsp;</span>
                </div>

                <div class="row no-margin no-padding">
                    <span class="show_label col-sm-4">{{trans('labels.backend.students.fields.history_id')}}</span>
                    <span class="show_value col-sm-8">{{$studentAnnual->history_id}} &nbsp;</span>
                </div>

            </div>
            <?php $a = 0; ?>
            <div class="col-sm-6">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th colspan="4">{{trans('labels.backend.students.fields.scholarship_id')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(empty($studentAnnual->scholarships->toArray()))
                        <tr>
                            <td colspan="4" style="text-align: center">
                                <h4>Empty</h4>
                            </td>
                        </tr>
                    @else
                        @foreach($studentAnnual->scholarships as $scholarship)
                        <tr style="color:#15c">
                            <td>{{$a}}</td>
                            <td>{{$scholarship->code}}</td>
                            <td>{{$scholarship->founder}}</td>
                            <td>{{$scholarship->duration}}</td>
                        </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>

        </div>
        <?php $a++; ?>
        @endforeach
    </div>
</div>
