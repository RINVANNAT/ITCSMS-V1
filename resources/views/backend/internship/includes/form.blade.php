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
                       tabindex="1"
                       autofocus
                       value="{{ isset($internship) ? $internship->person : old('person') }}"
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
                <select class="companies form-control" tabindex="3" name="company">
                    <option selected disabled></option>
                    @foreach($companies as $company)
                        @if(isset($internship))
                            @if($internship->internship_company->id == $company->id)
                                <option value="{{ $company }}" selected>{{ $company->text }}</option>
                            @else
                                <option value="{{ $company }}">{{ $company->text }}</option>
                            @endif
                        @else
                            <option value="{{ $company }}">{{ $company->text }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label required">Address</label>
            <div class="col-md-9">
                <textarea class="form-control"
                          id="address"
                          tabindex="5"
                          name="address"
                          rows="4">{{ isset($internship) ? $internship->address : old('address') }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label">Phone</label>
            <div class="col-md-9">
                <input type="text"
                       name="phone"
                       tabindex="6"
                       id="phone"
                       value="{{ isset($internship) ? $internship->phone : old('phone') }}"
                       class="form-control"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label">H/P</label>
            <div class="col-md-9">
                <input type="text"
                       id="hot_line"
                       tabindex="6"
                       name="hot_line"
                       value="{{ isset($internship) ? $internship->hot_line : old('hot_line') }}"
                       class="form-control"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label">E-Mail Address</label>
            <div class="col-md-9">
                <input type="text"
                       tabindex="7"
                       name="e_mail_address"
                       id="e_mail_address"
                       value="{{ isset($internship) ? $internship->e_mail_address : old('e_mail_address') }}"
                       class="form-control"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label">Web</label>
            <div class="col-md-9">
                <input type="text"
                       name="web"
                       tabindex="8"
                       id="web"
                       value="{{ isset($internship) ? $internship->web : old('web') }}"
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
                               id="title"
                               tabindex="2"
                               value="{{ isset($internship) ? $internship->title : old('title') }}"
                               name="title"
                               class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label required">Training Field</label>
                    <div class="col-md-9">
                        <input type="text"
                               name="training_field"
                               tabindex="4"
                               id="training_field"
                               value="{{ isset($internship) ? $internship->training_field : old('training_field') }}"
                               class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label required">Start</label>
                    <div class="col-md-9">
                        <input class="form-control"
                               id="start"
                               tabindex="9"
                               value="{{ isset($internship) ? $internship->start : old('start') }}"
                               name="start"
                               type="text">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label required">End</label>
                    <div class="col-md-9">
                        <input class="form-control"
                               id="end"
                               tabindex="10"
                               value="{{ isset($internship) ? $internship->end : old('end') }}"
                               name="end"
                               type="text">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label required">Issue Date</label>
                    <div class="col-md-9">
                        <input class="form-control"
                               tabindex="11"
                               id="issue_date"
                               value="{{ isset($internship) ? $internship->issue_date : old('issue_date') }}"
                               name="issue_date"
                               type="text">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label required">Academic Year</label>
                    <div class="col-md-9">
                        <select class="form-control"
                                tabindex="12"
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
                        <select class="form-control" tabindex="13" name="students[]" id="students" multiple></select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>