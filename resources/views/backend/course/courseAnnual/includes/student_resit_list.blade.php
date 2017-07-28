<style>
  .border_btn {
      margin-bottom: 5px;
      margin-left: 5px;
  }

</style>

<input type="hidden" name="academic_year_id" value="{{$academicYear->id}}">
<table class="table table-bordered" id="student_resit_subject">
    <thead>
    <tr>
        <th>No</th>
        <th>ID-Card</th>
        <th><div style="width: 150px;">Name</div></th>
        <?php $totalCredit=0;  $courseProgramBaseIds = [];?>
        @foreach($courseAnnuals as $courseAnnual)

            @if(count($courseProgramBaseIds) > 0)
                @if(!in_array($courseAnnual->course_id, $courseProgramBaseIds))

                    @if($courseAnnual->is_counted_creditability)

                        <?php $courseProgramBaseIds[] = $courseAnnual->course_id; $totalCredit += $courseAnnual->course_annual_credit;?>
                        <th><div class="vertical" style="width: 70px;">{{$courseAnnual->name_en}}<br>{{$courseAnnual->course_annual_credit}}  </div> </th>
                    @endif
                @endif
            @else
                @if($courseAnnual->is_counted_creditability)
                    <?php $courseProgramBaseIds[] = $courseAnnual->course_id;$totalCredit += $courseAnnual->course_annual_credit;?>
                    <th><div class="vertical" style="width: 70px;">{{$courseAnnual->name_en}}<br>{{$courseAnnual->course_annual_credit}}  </div> </th>
                @endif

            @endif
        @endforeach
        <th> <div style="width: 170px"> Moyenne </div> <br>{{$totalCredit}} </th>
    </tr>
    </thead>

    <tbody>

    <?php $index = 1;?>

    @foreach($studentRattrapages as $studentRattrapage)

        <?php $student = $studentRattrapage['student'];?>
        <?php $totalScore =0; $approximation_score=0;?>

        <tr>
            <td>{{$index}}</td>
            <td>{{$student->id_card}}</td>
            <td>{{$student->name_kh}}</td>
            <?php $count_label=1; $course_program_base_ids = []; $failSubjects = collect($studentRattrapage['fail'])->keyBy('course_annual_id')->toArray(); $passSubjects = isset($studentRattrapage['pass'])?collect($studentRattrapage['pass'])->keyBy('course_annual_id')->toArray(): null;?>

            @foreach($courseAnnuals as $courseAnnual)
                <?php $count_label++;?>

                    @if(count($course_program_base_ids) > 0)

                        @if(!in_array($courseAnnual->course_id, $course_program_base_ids))

                            @if($courseAnnual->is_counted_creditability)

                                @if(isset($failSubjects[$courseAnnual->course_annual_id]))
                                    <?php $fail =  $failSubjects[$courseAnnual->course_annual_id];?>

                                        <td>
                                            {{--set checked--}}
                                            <label for="{{$student->id_card}}_{{$count_label}}" style=" font-size: 12pt;" class="btn btn-xs btn-default">
                                                <input student_annual_id = "{{$student->student_annual_id}}" student_name="{{$student->name_kh}}" id="{{$student->id_card}}_{{$count_label}}" credit="{{$courseAnnual->course_annual_credit}}" student_id="{{$student->id_card}}" class="{{$student->id_card}} input_value" type="checkbox" onchange="calculateScore($(this))" name="{{$student->id_card}}[]" value="{{$courseAnnual->course_annual_id}}" course_name="{{$courseAnnual->name_en}}" checked score="{{$fail['score']}}"> {{number_format((float)($fail['score']), 2, '.', '')}}
                                            </label>

                                        </td>

                                    <?php
                                            /*-----score fail---*/
                                        $totalScore += ( $fail['score'] * $courseAnnual->course_annual_credit);
                                        $approximation_score += (\App\Models\Enum\ScoreEnum::Pass_Moyenne * $courseAnnual->course_annual_credit);
                                    ?>

                                @endif

                                @if(isset($passSubjects[$courseAnnual->course_annual_id]))


                                        <?php $pass =  $passSubjects[$courseAnnual->course_annual_id];?>

                                        <td>
                                            {{--set checked--}}
                                            <label for="{{$student->id_card}}_{{$count_label}}" student_annual_id = "{{$student->student_annual_id}}" student_name="{{$student->name_kh}}" style=" font-size: 12pt;" class="btn btn-xs btn-default">
                                                <input id="{{$student->id_card}}_{{$count_label}}" credit="{{$courseAnnual->course_annual_credit}}" student_id="{{$student->id_card}}" class="{{$student->id_card}} input_value" type="checkbox" onchange="calculateScore($(this))" name="{{$student->id_card}}[]" value="{{$courseAnnual->course_annual_id}}" course_name="{{$courseAnnual->name_en}}" score="{{$pass['score']}}"> {{number_format((float)($pass['score']), 2, '.', '')}}
                                            </label>

                                        </td>

                                            <?php
                                                /*--score pass---*/
                                                $totalScore += ( $pass['score'] * $courseAnnual->course_annual_credit);
                                                $approximation_score += ($pass['score'] * $courseAnnual->course_annual_credit);
                                            ?>
                                @endif
                            @endif

                        @else

                            @if($courseAnnual->is_counted_creditability)

                                @if(isset($failSubjects[$courseAnnual->course_annual_id]))

                                    <?php $fail =  $failSubjects[$courseAnnual->course_annual_id];?>

                                    <td>
                                        {{--set checked--}}
                                        <label for="{{$student->id_card}}_{{$count_label}}" style=" font-size: 12pt;" class="btn btn-xs btn-default">
                                            <input student_annual_id = "{{$student->student_annual_id}}" student_name="{{$student->name_kh}}" id="{{$student->id_card}}_{{$count_label}}" credit="{{$courseAnnual->course_annual_credit}}" student_id="{{$student->id_card}}" class="{{$student->id_card}} input_value" type="checkbox" onchange="calculateScore($(this))" name="{{$student->id_card}}[]" value="{{$courseAnnual->course_annual_id}}" course_name="{{$courseAnnual->name_en}}" checked score="{{$fail['score']}}"> {{number_format((float)($fail['score']), 2, '.', '')}}
                                        </label>

                                    </td>

                                        <?php
                                            /*-----score fail---*/
                                            $totalScore += ( $fail['score'] * $courseAnnual->course_annual_credit);
                                            $approximation_score += (\App\Models\Enum\ScoreEnum::Pass_Moyenne * $courseAnnual->course_annual_credit);
                                        ?>

                                @endif

                                @if(isset($passSubjects[$courseAnnual->course_annual_id]))

                                    <?php $pass =  $passSubjects[$courseAnnual->course_annual_id];?>

                                    <td>
                                        {{--set checked--}}
                                        <label for="{{$student->id_card}}_{{$count_label}}" style=" font-size: 12pt;" class="btn btn-xs btn-default">
                                            <input student_annual_id = "{{$student->student_annual_id}}" student_name="{{$student->name_kh}}" id="{{$student->id_card}}_{{$count_label}}" credit="{{$courseAnnual->course_annual_credit}}" student_id="{{$student->id_card}}" class="{{$student->id_card}} input_value" type="checkbox" onchange="calculateScore($(this))" name="{{$student->id_card}}[]" value="{{$courseAnnual->course_annual_id}}" course_name="{{$courseAnnual->name_en}}" score="{{$pass['score']}}"> {{number_format((float)($pass['score']), 2, '.', '')}}
                                        </label>

                                    </td>

                                        <?php
                                            /*--score pass---*/
                                            $totalScore += ( $pass['score'] * $courseAnnual->course_annual_credit);
                                            $approximation_score += ($pass['score'] * $courseAnnual->course_annual_credit);
                                        ?>

                                @endif
                            @endif


                        @endif
                    @else
                        @if($courseAnnual->is_counted_creditability)

                            @if(isset($failSubjects[$courseAnnual->course_annual_id]))
                                <?php $fail =  $failSubjects[$courseAnnual->course_annual_id];?>

                                <td>
                                    {{--set checked--}}
                                    <label for="{{$student->id_card}}_{{$count_label}}" style=" font-size: 12pt;" class="btn btn-xs btn-default">
                                        <input student_annual_id = "{{$student->student_annual_id}}" student_name="{{$student->name_kh}}" id="{{$student->id_card}}_{{$count_label}}" credit="{{$courseAnnual->course_annual_credit}}" student_id="{{$student->id_card}}" class="{{$student->id_card}} input_value" type="checkbox" onchange="calculateScore($(this))" name="{{$student->id_card}}[]" value="{{$courseAnnual->course_annual_id}}" course_name="{{$courseAnnual->name_en}}" checked score="{{$fail['score']}}"> {{number_format((float)($fail['score']), 2, '.', '')}}
                                    </label>

                                </td>

                                    <?php
                                        /*-----score fail---*/
                                        $totalScore += ( $fail['score'] * $courseAnnual->course_annual_credit);
                                        $approximation_score += (\App\Models\Enum\ScoreEnum::Pass_Moyenne * $courseAnnual->course_annual_credit);
                                    ?>

                            @endif

                            @if(isset($passSubjects[$courseAnnual->course_annual_id]))

                                <?php $pass =  $passSubjects[$courseAnnual->course_annual_id];?>

                                <td>
                                    {{--set checked--}}
                                    <label for="{{$student->id_card}}_{{$count_label}}" style=" font-size: 12pt;" class="btn btn-xs btn-default">
                                        <input student_annual_id = "{{$student->student_annual_id}}" student_name="{{$student->name_kh}}" id="{{$student->id_card}}_{{$count_label}}" credit="{{$courseAnnual->course_annual_credit}}" student_id="{{$student->id_card}}" class="{{$student->id_card}} input_value" type="checkbox" onchange="calculateScore($(this))" name="{{$student->id_card}}[]" value="{{$courseAnnual->course_annual_id}}" course_name="{{$courseAnnual->name_en}}" score="{{$pass['score']}}"> {{number_format((float)($pass['score']), 2, '.', '')}}
                                    </label>

                                </td>

                                    <?php
                                        /*--score pass---*/
                                        $totalScore += ( $pass['score'] * $courseAnnual->course_annual_credit);
                                        $approximation_score += ($pass['score'] * $courseAnnual->course_annual_credit);
                                    ?>

                            @endif
                        @endif

                    @endif

            @endforeach


            <?php

                $moyenne = number_format((float)($totalScore/(($totalCredit > 0)?$totalCredit:1)), 2, '.', '');
                $approximation_moyenne = number_format((float)($approximation_score/(($totalCredit > 0)?$totalCredit:1)), 2, '.', '');
            ?>
                <td style="text-align:center; vertical-align:middle;">
                    <label for="moyenne" class=" label label-warning"  style="font-size: 12pt;"  >{{$moyenne}} </label>
                    <label id="{{$student->id_card}}" for="approximation_score" class="label label-success" style="font-size: 12pt; margin-left: 5px">  {{$approximation_moyenne}}</label>
                </td>

        </tr>

        <?php $index++;?>

    @endforeach

    </tbody>

</table>

<div>
    <button type="submit" class="btn btn-info border_btn btn_export_submit"> Export Lists </button>
    {{--<button type="button" class="btn btn-warning save_change border_btn"> Save Change </button>--}}
</div>

