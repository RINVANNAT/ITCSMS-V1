<div class="page">
    <div class="row">
        <div class="col-md-12" style="padding-bottom: 0.4cm;margin-top: -12px;">
            <span>Ref. N<sup>o</sup> .................. ITC.BE</span>
            <span class="pull-right">Phnom Penh, {{ \Carbon\Carbon::parse($internship->issue_date)->format('F d, Y') }}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <p>
                To: {{ $internship->person }} <br/>
                {{ $internship->company }} <br/>
                {{ $internship->address }} <br/>
                @if($internship->phone != "") Phone: {{ $internship->phone }} <br/> @endif
                @if($internship->hot_line != "") H/P: {{ $internship->hot_line }} <br/> @endif
                @if($internship->e_mail_address != "") E-Mail Address: {{ $internship->e_mail_address }} <br/> @endif
                @if($internship->web != "") Web: {{ $internship->web }} @endif
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <br/><p><strong>Subject: {{ $internship->title }}</strong></p><br/>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <p class="text-justify">
                Dear {{ $internship->person }}, <br/>
                The {{ $internship->department->name_en }} of Institute of Technology of Cambodia (ITC) is searching for a company that can accept {{ count($internship->internship_student_annuals) }} of our {{ strtolower($internship->grade->name_en) }} Engineering students to do internship, so those students can get work experience from your company and this work experience will contribute towards the completion of their studies.
            </p>
        </div>
    </div>

    <div class="row">
        <div class="student-label">
            <p>The names of students are:</p>
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

    <div class="row text-justify">
        <div class="col-md-12">
            <p>
                The internship period will take place from {{ (new \Carbon\Carbon($internship->start_date))->format('F d') }} to {{ (new \Carbon\Carbon($internship->end_date))->format('F d, Y') }} and would need to be scheduled by both your company and ITC.
            </p>

            <p>
                In this case, we would like to ask for your cooperation with our institute and let our students do this internship in your company in the field of {{ strtoupper($internship->training_field) }}. After that, he will write a thesis and present it to a jury of the Institute for an evaluation. This evaluation will bring account with others for the final exam.
            </p>

            <p>
                If you agree with this formula, I will send you a contract to sign. Please consider this cooperation to help our students finish their studies in {{ $internship->department->name_en }} Department of ITC.
            </p>

            <p>
                We are looking forward to hearing from your acceptance.
            </p>

            @if(!empty($internship->person))
                <p>Yours Sincerely,</p>
            @else
                <p>Yours Faithfully,</p>
            @endif
        </div>

        <div class="col-md-12" style="margin-top: 87px;">
            <p>Deputy Director,</p>
            <p style="margin-top:-10px;">Institute of Technology of Cambodia</p>
        </div>
    </div>
</div>