
@if(isset($courses))
    @foreach($courses as $course)
        <div class="col-sm-4">
            @foreach($course as $item)

                <div class="col-sm-12">
                    <label for="{{$item->course_annual_id}}" class="btn btn-xs">
                        <input type="radio" class="course_annual_radio" name="course_annual_id" value="{{$item->course_annual_id}}" id="{{$item->course_annual_id}}">
                        {{$item->name_en}}
                    </label>
                </div>
            @endforeach
        </div>
    @endforeach
@else

    <div class="col-sm-12 alert alert-warning">
        <h4>
            <i class="fa fa-info-circle"></i>
            No Selected Courses
        </h4>

        <p> Please Filter to select the responsible course </p>

    </div>
@endif


