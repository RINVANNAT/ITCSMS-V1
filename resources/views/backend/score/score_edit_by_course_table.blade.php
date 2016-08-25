<!-- ****************************
start table render
******************************-->
@if($studentAnnuals->isEmpty())
    <div class="well text-center">No Scores found.</div>
@else

    <template id="vue_score_edit">
        {!! Form::model('', ['route' => ['score.updateMany'], 'method' => 'patch',"id"=>"scoreform"]) !!}
        <table class="scoreinput" style="border:1px black solid">
            <thead>
            <th>no</th>
            <th>Student Name</th>
            <th>Student id</th>
            <th style="display:none;">Abs</th>
            <th> score 10 </th>
            <th> score 30 </th>
            <th> score 60 </th>
            <th style="display:none;"> re-exam </th>
            <th> total </th>
            </thead>
            <tbody>
            <template v-for="(index, studentAnnual) in studentAnnuals">
                <tr>
                    <td>@{{ studentAnnual.no }}</td>
                    <td>@{{ studentAnnual.name }}</td>
                    <td>@{{ studentAnnual.id_card }}</td>
                    <td style="display:none;"> {!! Form::text('abs[]', '@{{  absencesCounts[studentAnnua.id] }}', [ 'v-model'=>"absencesCounts[studentAnnual.id]", 'class' => 'form-score','placeholder'=>'']) !!}</td>
                    {!! Form::hidden('ids[]', '@{{ scores[studentAnnual.id].id }}') !!}
                    {!! Form::hidden('student_annual_ids[]', '@{{ studentAnnual.id }}') !!}
                    <td> {!! Form::text('score10[]', '@{{ scores[studentAnnual.id].score10}}', [ 'v-model'=>"scores[studentAnnual.id].score10", 'id'=>'@{{index}}-1','class' => 'form-score','placeholder'=>'']) !!}</td>
                    <td>{!! Form::text('score30[]', '@{{ scores[studentAnnual.id].score30}}' , ['v-model'=>"scores[studentAnnual.id].score30", 'id'=>'@{{index}}-2','class' => 'form-score','placeholder'=>""]) !!}</td>
                    <td>{!! Form::text('score60[]',  '@{{ scores[studentAnnual.id].score60}}' , ['v-model'=>"scores[studentAnnual.id].score60", 'id'=>'@{{index}}-3', 'class' => 'form-score', 'placeholder'=>""]) !!}</td>
                    <td style="display:none;">{!! Form::text('reexam[]',  '@{{ scores[studentAnnual.id].reexam}}',  ['v-model'=>"scores[studentAnnual.id].reexam", 'class' => 'form-score', 'placeholder'=>""]) !!}</td>
                    <td >@{{ (total(studentAnnual.id)).toFixed(2) }} </td>
                </tr>
            </template>
            {!! Form::hidden('filter', "", ['id' => 'redirectfilter', "value"=>""]) !!}

            </tbody>

        </table>
        <br>
        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </template>




<script type="text/javascript">
    studentAnnuals = {!! json_encode($studentAnnualsFetchResult) !!};
    absencesCounts = {!! json_encode($absencesCounts) !!};
    scores = {!! json_encode($scores) !!};

    var test;
    $(document).ready(function() {
        test = new Vue({
            el: '#vue_score_edit',
            data: {
                studentAnnuals:  studentAnnuals,
                absencesCounts: absencesCounts,
                scores: scores,
                // a computed getter
                total: function ( index ) {
                    // `this` points tscoreso the vm instance
                    var tmp;
                    if(parseFloat(this.scores[index].score60) < parseFloat(this.scores[index].reexam)){
                        tmp = parseFloat(this.scores[index].reexam);
                    }else{
                        tmp = parseFloat(this.scores[index].score60);
                    }
                    if (!!this.scores[index].score10) {
                        tmp = tmp + parseFloat( this.scores[index].score10);
                    }else{
                        this.scores[index].score10  =0;
                    }
                    if (!!this.scores[index].score30) {
                        tmp = tmp + parseFloat( this.scores[index].score30);
                    }else{
                        this.scores[index].score30  =0;
                    }
                    if (!!!this.scores[index].score60) {
                        this.scores[index].score60  =0;
                    }
                    return  tmp;
                },
                validation: function () {
                    var len = this.scores.length;
                    var validated = true;
                    console.log(validated);

                    for ( var i = 0; i < len ; i++){
                        console.log(validated);
                        if ( scores[i].score30 > 30){
                            validated = false;
                            break;
                        }
                        if ( scores[i].score60 > 60){
                            validated = false;
                            console.log(validated);
                            break;
                        }
                    }
                    return validated;
                },
                methods: {
                    updateScore10: function (scoreId) {
                        alert(studentId)
                    },
                    updateScore30: function (studentId) {
                        alert(studentId)
                    },
                    updateScore60: function (studentId) {
                        alert(studentId)
                    },
                    updateScoreReExam: function (studentId) {
                        alert(studentId)
                    }
                },
            }
        });
        $(document).keydown(function(e) {
            var $focused = $(':focus');
            var $focusedId = $focused.attr("id")
            var rowCol = $focusedId.split("-");
            rowCol[0] = parseInt(rowCol[0]);
            rowCol[1] = parseInt(rowCol[1]);
            console.log(rowCol);
            var newId = rowCol;
            switch(e.which) {
                case 37: // lef
                    console.log("left");
                    newId[1] -= 1;
                    $('#'+newId[0]+"-"+newId[1]).focus();
                    break;

                case 38: // up
                    console.log("up");
                    newId[0] -= 1;
                    $('#'+newId[0]+"-"+newId[1]).focus();
                    break;

                case 39: // right

                    newId[1] += 1;
                    $('#'+newId[0]+"-"+newId[1]).focus();
                    break;

                case 40: // down
                    console.log("down");
                    newId[0] += 1;
                    $('#'+newId[0]+"-"+newId[1]).focus();

                    break;

                default: return; // exit this handler for other keys
            }
            e.preventDefault(); // prevent the default action (scroll / move caret)
        });



    });
</script>
@endif
