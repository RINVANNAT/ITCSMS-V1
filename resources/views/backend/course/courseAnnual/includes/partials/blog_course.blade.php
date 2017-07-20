
@if(isset($courses))
    @foreach($courses as $course)
        <div class="col-sm-4 pull-left">
            @foreach($course as $item)

                <div class="col-sm-12">
                    <label for="{{$item->course_annual_id}}" class="btn btn-xs" style="font-size: 12pt">
                        <input type="radio" class="course_annual_radio" name="course_annual_id" value="{{$item->course_annual_id}}" id="{{$item->course_annual_id}}">
                        {{$item->name_en.'-S'.$item->semester_id}}
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


