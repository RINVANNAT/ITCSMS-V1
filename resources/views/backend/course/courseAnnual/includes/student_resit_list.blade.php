<style>
  .border_btn {
      margin-bottom: 5px;
      margin-left: 5px;
  }
</style>

<input type="hidden" name="academic_year_id" value="{{$academicYear->id}}">
<table class="table table-bordered">
    <thead>
    <tr>
        <th>No</th>
        <th>ID-Card</th>
        <th>Name</th>
        <?php $totalCredit=0?>
        @foreach($coursePrograms as $program)
            <?php $course = $program->courseAnnual()->first()?>
            <?php
            $totalCredit = $totalCredit + $course->credit;
            ?>
            <th><div class="vertical">{{$program->name_en}}<br>{{$course->credit}}  </div> </th>

        @endforeach
        <th>Moyenne <br>{{$totalCredit}} </th>
    </tr>
    </thead>
    <tbody>

    <?php $index = 1;?>

    @foreach($students as $student)

        <?php $totalScore =0; $approximation_score=0;?>

        <input type="hidden" value="{{$student->id_card}}" name="student_id_card[]">
        <tr>
            <td>{{$index}}</td>
            <td>{{$student->id_card}}</td>
            <td >{{$student->name_latin}}</td>
            <?php $count_label=1;?>
            @foreach($coursePrograms as $program)
                <?php $count_label++;?>

                <?php
                $courseAnnual = $program->courseAnnual()->first();//---because on course program has many course annual and in every course annual have the same proper of the course so we can use only the first course annual(name, credit)

                $subjectRattrapages = $studentRattrapages[$student->id_card];
                if(isset($subjectRattrapages['fail'])) {
                    $courseAnnualFailId = array_intersect($studentRattrapages[$student->id_card]['fail'], $courseAnnualByProgram[$program->id]);
                } else {
                    $courseAnnualFailId=[];
                }
                ?>
                @if(count($courseAnnualFailId)> 0)

                    <?php

                    //---this array have only one element of exact course annual of the student who must take the resit
                    foreach($courseAnnualFailId as $courseAnnualId) {// loop only one time
                        $studentScore = isset($averages[$courseAnnualId])?$averages[$courseAnnualId][$student->student_annual_id]:null;
                    }
                    //---we need to find the course annual credit not course program credit
                    $totalScore = $totalScore + ( (($studentScore != null)?$studentScore->average:0) * $courseAnnual->credit);
                    $approximation_score = $approximation_score + (\App\Models\Enum\ScoreEnum::Pass_Moyenne * $courseAnnual->credit);
                    ?>
                    <td>
                        {{--set checked--}}
                        <label for="{{$student->id_card}}_{{$count_label}}"><input id="{{$student->id_card}}_{{$count_label}}" credit="{{$courseAnnual->credit}}" student_id="{{$student->id_card}}" class="{{$student->id_card}} input_value" type="checkbox" onchange="calculateScore($(this))" name="{{$student->id_card}}[]" value="{{$courseAnnualId}}" checked score="{{($studentScore!=null)?$studentScore->average:0}}"> {{number_format((float)(($studentScore!=null)?$studentScore->average:0), 2, '.', '')}}</label>

                    </td>
                @else

                    @if(isset($studentRattrapages[$student->id_card]['pass']))
                        <?php


                        $courseAnnualPassId = array_intersect($studentRattrapages[$student->id_card]['pass'], $courseAnnualByProgram[$program->id]);
                        //---this array have only one element of exact course annual
                        foreach($courseAnnualPassId as $courseAnnualId) {
                            $studentScore = isset($averages[$courseAnnualId])?$averages[$courseAnnualId][$student->student_annual_id]:null;
                            //-----course annual credit not course program credit
                            $totalScore = $totalScore + ( (($studentScore !=null)?$studentScore->average:0) * $courseAnnual->credit);

                            $approximation_score = $approximation_score + ( (($studentScore !=null)?$studentScore->average:0) * $courseAnnual->credit);
                        }
                        ?>

                        @if(count($courseAnnualPassId) >0)

                                <td width="">
                                    <label for="{{$student->id_card}}_{{$count_label}}"><input id="{{$student->id_card}}_{{$count_label}}" type="checkbox" credit="{{$courseAnnual->credit}}" student_id="{{$student->id_card}}" class="{{$student->id_card}} input_value" onchange="calculateScore($(this))" name="{{$student->id_card}}[]" value="{{$courseAnnualId}}" score="{{($studentScore!=null)?$studentScore->average:0}}"> {{number_format((float)(($studentScore!=null)?$studentScore->average:0), 2, '.', '')}}</label>

                                </td>
                        @endif

                    @endif

                @endif


            @endforeach
            <?php

            $moyenne = number_format((float)($totalScore/(($totalCredit >0)?$totalCredit:1)), 2, '.', '');
            $approximation_moyenne = number_format((float)($approximation_score/(($totalCredit >0)?$totalCredit:1)), 2, '.', '');
            ?>
            <td><label for="moyenne" class=" label label-warning">{{$moyenne}} </label> <label id="{{$student->id_card}}" for="approximation_score" class="label label-success">  {{$approximation_moyenne}}</label></td>
        </tr>

        <?php $index++;?>

    @endforeach


    </tbody>
</table>

<div>
    <button type="submit" class="btn btn-info border_btn btn_export_submit"> Export Lists </button>
    <button type="button" class="btn btn-warning save_change border_btn"> Save Change </button>
</div>

