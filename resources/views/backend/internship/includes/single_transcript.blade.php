<div class="page">
    <div class="row">
        <div class="tran-header"></div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <p>Ref No ........... ITC.BE</p>
        </div>
        <div class="col-md-6">
            <p class="pull-right">Phnom Penh, {{ (new \Carbon\Carbon($internship->date))->toFormattedDateString() }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 contact_detail">
            {!! $internship->contact_detail !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <br/><p><strong>Subject: {{ $internship->subject }}</strong></p><br/>
        </div>
    </div>

    <div class="align-justify">
        <div class="row">
            <div class="col-md-12">
                <p>
                    Dear Sir, <br/>
                    The {{ $internship->department->name_en }} of Institute of Technology of Cambodia (ITC) is searching for a company that can accept one of our {{ $internship->grade->name_en }} year Engineering students to do internship, so this student can get work experience from your company and this work experience will contribute towards the completion of his study.
                </p>
            </div>
        </div>

        <div class="row">
            <div class="student-label">
                <p>The name of student is:</p>
            </div>
            <div class="student-list">
                @foreach($internship->internship_student_annuals as $internship_student_annual)
                    <p>
                        <strong>
                            @if($internship_student_annual->gender->code == 'M')
                                Mr.
                            @else
                                Miss.
                            @endif
                            {{ strtoupper($internship_student_annual->student->name_latin) }}
                        </strong>
                    </p>
                @endforeach
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <p>
                    The internship period will take place from {{ (new \Carbon\Carbon($internship->start_date))->toFormattedDateString() }} to {{ (new \Carbon\Carbon($internship->end_date))->toFormattedDateString() }} and would need to be scheduled by both your company and ITC.
                </p>

                <p>
                    In this case, we would like to ask for your cooperation with our institute and let our student do this internship in your company in the field of {{ strtoupper($internship->internship_title) }}. After that,
                    @foreach($internship->internship_student_annuals as $internship_student_annual)
                        {{ ($internship_student_annual->gender->code == 'M') ? 'he' : 'she' }}
                    @endforeach
                    will write a thesis and present it to a jury of the Institute for an evaluation. This evaluation will bring account with others for the final exam.
                </p>

                <p>
                    If you agree with this formula, I will send you a contract to sign. Please consider this cooperation to help our student finish his study in {{ $internship->department->name_en }} Department of ITC.
                </p>

                <p>
                    We are looking forward to hearing from your acceptance.
                </p>

                @if(!empty($internship->contact_name))
                    <p>Yours Sincerely,</p>
                @else
                    <p>Yours Faithfully,</p>
                @endif
            </div>

            <div class="col-md-12" style="margin-top: 50px;">
                <p>Deputy Director,</p>
                <p>Institute Of Technology of Cambodia</p>
            </div>
        </div>
    </div>
</div>