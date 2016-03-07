<div>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#school_fee_and_award" aria-controls="generals" role="tab" data-toggle="tab">
                {{ trans('labels.backend.scholarships.school_fee_and_award') }}
            </a>
        </li>
        <li role="presentation">
            <a href="#scholarship_holder" aria-controls="candidates" role="tab" data-toggle="tab">
                {{ trans('labels.backend.scholarships.scholarship_holder') }}
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="school_fee_and_award" style="padding-top:20px">
            @include('backend.scholarship.includes.school_fee_and_award')
        </div>
        <div role="tabpanel" class="tab-pane" id="scholarship_holder" style="padding-top:20px">
            @include('backend.scholarship.includes.scholarship_holder')
        </div>
    </div>
</div>






