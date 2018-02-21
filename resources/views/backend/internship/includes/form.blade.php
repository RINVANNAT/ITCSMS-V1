@if(isset($internship))
    <input type="hidden" name="id" value="{{ $internship->id }}"/>
@endif
<div class="row">
    <div class="col-md-12">
        <fieldset>
            <legend>Header</legend>
            <div class="form-group">
                <label class="col-md-3 control-label">No.</label>
                <div class="col-md-1">
                    <input type="text"
                           readonly
                           value="{{ $number }}"
                           name="number"
                           class="form-control">
                </div>

                <label class="col-md-1 control-label">Ref. No</label>
                <div class="col-md-3">
                    <input type="text"
                           name="ref_number"
                           value="{{ isset($internship) ? $internship->ref_number : null }}"
                           class="form-control">
                </div>

                <label class="col-md-1 control-label">Date</label>
                <div class="col-md-3">
                    <input class="form-control"
                           id="date"
                           value="{{ isset($internship) ? $internship->date : null }}"
                           name="date"
                           type="text">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label required">Title</label>
                <div class="col-md-9">
                    <input type="text"
                           name="internship_title"
                           value="{{ isset($internship) ? $internship->internship_title : null }}"
                           required
                           class="form-control"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label required">Subject</label>
                <div class="col-md-9">
                    <input type="text"
                           name="subject"
                           value="{{ isset($internship) ? $internship->subject : null }}"
                           required
                           class="form-control"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label required">Internship Period</label>
                <div class="col-md-9">
                    <input type="text"
                           name="period"
                           value="{{ isset($internship) ? (new \Carbon\Carbon($internship->start_date))->format("m/d/Y"). ' - ' .(new \Carbon\Carbon($internship->end_date))->format("m/d/Y") : null }}"
                           id="period"
                           class="form-control"/>
                </div>
            </div>
        </fieldset>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <fieldset>
            <legend>Company Info</legend>

            <div class="form-group">
                <label class="col-md-4 control-label required">Contact Name</label>
                <div class="col-md-8">
                    <input type="text"
                           name="contact_name"
                           value="{{ isset($internship) ? $internship->contact_name : null }}"
                           class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label required">Contact Detail</label>
                <div class="col-md-8">
                    <textarea name="contact_detail"
                              id="contact_detail"
                              class="form-control"
                              value="{{ isset($internship) ? $internship->contact_detail : null }}"
                              rows="18"></textarea>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="col-md-6">
        <fieldset>
            <legend>Student Info</legend>

            <div class="form-group">
                <label class="col-md-4 control-label required">Academic Year</label>
                <div class="col-md-8">
                    <select class="form-control"
                            name="academic_year"
                            id="academic_year">
                        @foreach($academic_years as $academic_year)
                            @if(isset($pre_academic_year))
                                @if($pre_academic_year->id == $academic_year->id)
                                    <option value="{{ $academic_year->id }}" selected>{{ $academic_year->name_latin }}</option>
                                @else
                                    <option value="{{ $academic_year->id }}">{{ $academic_year->name_latin }}</option>
                                @endif
                            @else
                                <option value="{{ $academic_year->id }}">{{ $academic_year->name_latin }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label required">Students</label>
                <div class="col-md-8">
                    <select class="form-control" name="students[]" id="students"></select>
                </div>
            </div>
        </fieldset>
    </div>
</div>