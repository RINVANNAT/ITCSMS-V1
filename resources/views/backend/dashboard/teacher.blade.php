<li>
    <i class="fa fa-book bg-blue"></i>

    <div class="timeline-item">
        {{--<span class="time"><i class="fa fa-clock-o"></i> 12:05</span>--}}

        <h3 class="timeline-header"><a href="#">Score management :</a> input score to your courses</h3>

        <div class="timeline-body">
            <table class="table table-bordered" style="background-color: white !important;">
                <tbody>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Courses</th>
                    <th>Class</th>
                    <th style="width: 40px"></th>
                </tr>

                @if($courses == null || empty($courses))
                    <tr>
                        <td colspan="4">Empty</td>
                    </tr>
                @else
                    <?php $index = 1; ?>
                    @foreach($courses as $course)
                    <tr>
                        <td>{{$index}}.</td>
                        <td>{{$course['name_en']}}</td>
                        <td>
                            {{$course['class']}}
                            {{--@foreach($course["groups"] as $group)--}}
                                {{--<span>{{$group["group"]}}</span>--}}
                            {{--@endforeach--}}
                        </td>
                        <td>
                            <a href="{{route('admin.course.form_input_score_course_annual',$course['id'])}}" class="btn btn-xs btn-info input_score_course">
                                <i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.'input score'.'"></i> Score
                            </a>
                        </td>
                    </tr>
                    <?php $index++; ?>
                    @endforeach
                @endif

                </tbody>
            </table>
        </div>
        <div class="timeline-footer">
            <a class="btn btn-primary btn-xs" href="{!! url('admin/course/course_annual') !!}">See more</a>
        </div>
    </div>
</li>