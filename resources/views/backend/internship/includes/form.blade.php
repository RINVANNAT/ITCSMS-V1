@if(isset($internship))
    <input type="hidden" name="id" value="{{ $internship->id }}"/>
@endif
<div class="row">

    <div class="col-md-6">
        @if(isset($internship))
            <div class="form-group">
                <label class="col-md-3 control-label">Ref. Number</label>
                <div class="col-md-9">
                    <input class="form-control"
                           value="{{ $internship->id }}"
                           readonly
                           name="person"
                           type="text">
                </div>
            </div>
        @endif

        <div class="form-group">
            <label class="col-md-3 control-label required">Person</label>
            <div class="col-md-9">
                <input class="form-control"
                       value="{{ isset($internship) ? $internship->person : null }}"
                       name="person"
                       type="text">
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-offset-3 col-md-9">
                <label>
                    <input type="checkbox"
                           name="is_name"
                           @if(isset($internship))
                               @if($internship->is_name == true)
                                   checked
                               @endif
                           @else
                            checked
                           @endif
                           value="{{ isset($internship) ? $internship->is_name : 1 }}"/> Is person's name?
                </label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label required">Company</label>
            <div class="col-md-9">
                <input type="text"
                       name="company"
                       value="{{ isset($internship) ? $internship->company : null }}"
                       class="form-control"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label required">Address</label>
            <div class="col-md-9">
                <textarea class="form-control"
                          name="address"
                          rows="4">{{ isset($internship) ? $internship->address : null }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label">Phone</label>
            <div class="col-md-9">
                <input type="text"
                       name="phone"
                       value="{{ isset($internship) ? $internship->phone : null }}"
                       class="form-control"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label">H/P</label>
            <div class="col-md-9">
                <input type="text"
                       name="hot_line"
                       value="{{ isset($internship) ? $internship->hot_line : null }}"
                       class="form-control"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label">E-Mail Address</label>
            <div class="col-md-9">
                <input type="text"
                       name="e_mail_address"
                       value="{{ isset($internship) ? $internship->e_mail_address : null }}"
                       class="form-control"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label">Web</label>
            <div class="col-md-9">
                <input type="text"
                       name="web"
                       value="{{ isset($internship) ? $internship->web : null }}"
                       class="form-control"/>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-md-3 control-label">Title</label>
                    <div class="col-md-9">
                        <input type="text"
                               value="{{ isset($internship) ? $internship->title : null }}"
                               name="title"
                               class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label required">Training Field</label>
                    <div class="col-md-9">
                        <input type="text"
                               name="training_field"
                               value="{{ isset($internship) ? $internship->training_field : null }}"
                               class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label required">Start</label>
                    <div class="col-md-9">
                        <input class="form-control"
                               id="start"
                               value="{{ isset($internship) ? $internship->start : null }}"
                               name="start"
                               type="text">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label required">End</label>
                    <div class="col-md-9">
                        <input class="form-control"
                               id="end"
                               value="{{ isset($internship) ? $internship->end : null }}"
                               name="end"
                               type="text">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label required">Issue Date</label>
                    <div class="col-md-9">
                        <input class="form-control"
                               id="issue_date"
                               value="{{ isset($internship) ? $internship->issue_date : null }}"
                               name="issue_date"
                               type="text">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label required">Academic Year</label>
                    <div class="col-md-9">
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
                    <label class="col-md-3 control-label required">Students</label>
                    <div class="col-md-9">
                        <select class="form-control" name="students[]" id="students"></select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>