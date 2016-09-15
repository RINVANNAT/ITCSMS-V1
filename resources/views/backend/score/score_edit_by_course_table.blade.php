<!-- ****************************
start table render
******************************-->
<br/>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        @if(isset($courseAnnual))
        <!-- Modal content-->
        <div class="modal-content">
            <div class="box box-success">
                <div class="box-body">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="box-title">{{ trans('labels.backend.courseAnnuals.sub_edit_title') }}</h3>
            </div>
                {!! Form::model($courseAnnual, ['route' => ['admin.course.course_annual.update_score_per', $courseAnnual->id],'class' => 'form-horizontal', "id"=>"editForm", 'role'=>'form', 'method' => 'patch']) !!}
                        <br/>
                        <div id="course_message" style="display: none; text-align: center;">
                            <spna style="color:red"> The addition of the 3 must be equal to 100 % </spna>
                            <br/>
                        </div>
                        <div class="form-group">
                            {!! Form::label('', "Absence score percentage", ['class' => 'col-lg-5 control-label required']) !!}
                            <div class="col-lg-4">
                                {{ Form::text('score_percentage_column_1',  null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('departments', "TP score percentage", ['class' => 'col-lg-5 control-label required']) !!}
                            <div class="col-lg-4">
                                {{ Form::text('score_percentage_column_2',  null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('departments', "Final score percentage", ['class' => 'col-lg-5 control-label required']) !!}
                            <div class="col-lg-4">
                                {{ Form::text('score_percentage_column_3',  null, ['class' => 'form-control']) }}
                            </div>
                        </div>
            <div class="modal-footer">

                    <br/>
                    <div class="pull-left">
                        <button class="btn btn-info close hidden" type="button" id="close_course_edit"  data-dismiss="modal"> Close </button>
                    </div>
                        <div class="pull-right">

                            <input type="submit" class="btn btn-info" value="{{ trans('buttons.general.crud.update') }}"/>

                        </div>

            </div>

                    {!! Form::close() !!}


                </div><!-- /.box-body -->
            </div><!--box-->
        </div>
    @endif



</div></div>

@if($studentAnnuals->isEmpty())
    <div class="well text-center">No Scores found. </div>
@else
    <template id="vue_score_edit">

        {!! Form::model('', ['route' => ['score.updateMany'], 'method' => 'patch',"id"=>"scoreform"]) !!}
        @if($course_annual_fetch["isScoreRuleChange"])

            <script type="javascript">
                console.log("test");
                console.log(test.$data.course_annual)
            </script>

            <table class="scoreinput" style="border:1px black solid">
                <thead>
                <th> No </th>
                <th> Student Name </th>
                <th> ID </th>
                <th style="display:none;">Abs</th>
                <th> Abs </th>

                <th class="scoreper" v-show="course_annual.score_percentage_column_1> 0"> <a href="" data-toggle="modal" data-target="#myModal">Score @{{ course_annual.score_percentage_column_1 }} </a> </th>
                <th class="scoreper" v-show="course_annual.score_percentage_column_2> 0"> <a href="" data-toggle="modal" data-target="#myModal"> Score @{{ course_annual.score_percentage_column_2 }} </a></th>
                <th class="scoreper" v-show="course_annual.score_percentage_column_3> 0"> <a href="" data-toggle="modal" data-target="#myModal">Score @{{ course_annual.score_percentage_column_3 }} </a></th>
                <th style="display:none;"> re-exam </th>
                <th> total </th>
                </thead>
                <tbody>
                <template v-for="(index, studentAnnual) in studentAnnuals">
                    <tr>
                        <td>@{{ studentAnnual.no }}</td>
                        <td>@{{ studentAnnual.name }}</td>
                        <td>@{{ studentAnnual.id_card }}</td>
                        <td> {!! Form::text('abs[]', '@{{  absencesCounts[studentAnnua.id] }}', [ 'v-model'=>"absencesCounts[studentAnnual.id]", 'class' => 'form-score','id'=>'@{{index}}-0','placeholder'=>'']) !!}</td>
                        {!! Form::hidden('ids[]', '@{{ scores[studentAnnual.id].id }}') !!}
                        {!! Form::hidden('student_annual_ids[]', '@{{ studentAnnual.id }}') !!}
                        <td v-show="course_annual.score_percentage_column_1> 0"> {!! Form::text('score10[]', '@{{ scores[studentAnnual.id].score10}}', [ 'v-model'=>"scores[studentAnnual.id].score10", 'id'=>'@{{index}}-1',
                        'class' => "form-score @{{ inRow1Validation(studentAnnual.id)}}",'placeholder'=>'']) !!}   </td>
                        <td v-show="course_annual.score_percentage_column_2> 0">{!! Form::text('score30[]', '@{{ scores[studentAnnual.id].score30}}' , ['v-model'=>"scores[studentAnnual.id].score30", 'id'=>'@{{index}}-2','class' => 'form-score @{{ inRow2Validation(studentAnnual.id)}}'        ,'placeholder'=>""]) !!}    </td>
                        <td v-show="course_annual.score_percentage_column_3> 0">{!! Form::text('score60[]',  '@{{ scores[studentAnnual.id].score60}}' , ['v-model'=>"scores[studentAnnual.id].score60", 'id'=>'@{{index}}-3', 'class' => 'form-score @{{ inRow3Validation(studentAnnual.id)}}'      ,'placeholder'=>""]) !!} </td>
                        <td style="display:none;">{!! Form::text('reexam[]',  '@{{ scores[studentAnnual.id].reexam}}',  ['v-model'=>"scores[studentAnnual.id].reexam", 'class' => 'form-score', 'placeholder'=>""]) !!}</td>
                        <td class="@{{ totalValidation(studentAnnual.id)}}" >@{{(total(studentAnnual.id)).toFixed(2) }} </td>
                    </tr>
                </template>
                {!! Form::hidden('filter', "", ['id' => 'redirectfilter', "value"=>""]) !!}

                </tbody>

            </table>
        @else
            <table class="scoreinput" style="border:1px black solid">
                <thead>
                <th> No </th>
                <th> Student Name </th>
                <th> ID </th>
                <th style="display:none;">Abs</th>
                <th> Abs </th>
                <th class="scoreper"> <a href="" data-toggle="modal" data-target="#myModal">Score 10 </a></th>
                <th class="scoreper"> <a href="" data-toggle="modal" data-target="#myModal">Score 30 </a> </th>
                <th class="scoreper"> <a href="" data-toggle="modal" data-target="#myModal">Score 60 </a></th>
                <th style="display:none;"> re-exam </th>
                <th> total </th>
                </thead>
                <tbody>
                <template v-for="(index, studentAnnual) in studentAnnuals">
                    <tr>
                        <td>@{{ studentAnnual.no }}</td>
                        <td>@{{ studentAnnual.name }}</td>
                        <td>@{{ studentAnnual.id_card }}</td>
                        <td> {!! Form::text('abs[]', '@{{  absencesCounts[studentAnnua.id] }}', [ 'v-model'=>"absencesCounts[studentAnnual.id]", 'class' => 'form-score','id'=>'@{{index}}-0','placeholder'=>'']) !!}</td>
                        {!! Form::hidden('ids[]', '@{{ scores[studentAnnual.id].id }}') !!}
                        {!! Form::hidden('student_annual_ids[]', '@{{ studentAnnual.id }}') !!}
                        <td> {!! Form::text('score10[]', '@{{ scores[studentAnnual.id].score10}}', [ 'v-model'=>"scores[studentAnnual.id].score10", 'id'=>'@{{index}}-1', 'class' => "form-score @{{ inRow1Validation(studentAnnual.id)}}",'placeholder'=>'']) !!}</td>

                        <td>{!! Form::text('score30[]', '@{{ scores[studentAnnual.id].score30}}' , ['v-model'=>"scores[studentAnnual.id].score30", 'id'=>'@{{index}}-2','class' => 'form-score @{{ inRow2Validation(studentAnnual.id)}}','placeholder'=>""]) !!}</td>

                        <td>{!! Form::text('score60[]',  '@{{ scores[studentAnnual.id].score60}}' , ['v-model'=>"scores[studentAnnual.id].score60", 'id'=>'@{{index}}-3', 'class' => 'form-score @{{ inRow3Validation(studentAnnual.id)}}', 'placeholder'=>""]) !!}</td>
                        <td style="display:none;">{!! Form::text('reexam[]',  '@{{ scores[studentAnnual.id].reexam}}',  ['v-model'=>"scores[studentAnnual.id].reexam", 'class' => 'form-score', 'placeholder'=>""]) !!}</td>
                        <td class="@{{ totalValidation(studentAnnual.id)}}" >@{{(total(studentAnnual.id)).toFixed(2) }} </td>
                    </tr>
                </template>
                {!! Form::hidden('filter', "", ['id' => 'redirectfilter', "value"=>""]) !!}

                </tbody>

            </table>

        @endif


        <br>
        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </template>



<script type="text/javascript">
    studentAnnuals = {!! json_encode($studentAnnualsFetchResult) !!};
    absencesCounts = {!! json_encode($absencesCounts) !!};
    scores = {!! json_encode($scores) !!};
    course_annual = {!! json_encode($course_annual_fetch) !!};

    var test;
    $(document).ready(function() {
        test = new Vue({
            el: '#vue_score_edit',
            data: {
                studentAnnuals:  studentAnnuals,
                absencesCounts: absencesCounts,
                course_annual: course_annual,
                scores: scores,
                // a computed getter
                total: function ( index ) {
                    // `this` points tscoreso the vm instance
                    var tmp;
                    var total = parseFloat(this.scores[index].score10) + parseFloat(this.scores[index].score30) + parseFloat(this.scores[index].score60)

                    if( total< parseFloat(this.scores[index].reexam)){
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
                        this.scores[index].score60  = 0;
                    }
                    return  tmp;
                },
                inRow1Validation: function ( index ) {
                    // `this` points tscoreso the vm instance
                    var tmp = parseFloat( parseFloat( this.scores[index].score10));
                    if (tmp <= this.course_annual.score_percentage_column_1){
                        return "total_validate";
                    }else if (tmp > this.course_annual.score_percentage_column_1){
                        return "total_not_validate";
                    }else {
                        return "total_not_validate";
                    }
                },
                inRow2Validation: function ( index ) {
                    // `this` points tscoreso the vm instance
                    var tmp = parseFloat( parseFloat( this.scores[index].score30));
                    if (tmp <= this.course_annual.score_percentage_column_2){
                        return "total_validate";
                    }else if (tmp > this.course_annual.score_percentage_column_2){
                        return "total_not_validate";
                    }else {
                        return "total_not_validate";
                    }
                },
                inRow3Validation: function ( index ) {
                    // `this` points tscoreso the vm instance
                    var tmp = parseFloat( parseFloat( this.scores[index].score60));
                    if (tmp <= this.course_annual.score_percentage_column_3){
                        return "total_validate";
                    }else if (tmp > this.course_annual.score_percentage_column_3){
                        return "total_not_validate";
                    }else {
                        return "total_not_validate";
                    }
                },


                totalValidation: function ( index ) {
                    // `this` points tscoreso the vm instance
                    var tmp = parseFloat(this.scores[index].score60)+ parseFloat( this.scores[index].score10) +parseFloat( this.scores[index].score30);

                    console.log(tmp);
                    if (tmp <= 100){
                        return "total_validate";
                    }else if (tmp > 100){
                        return "total_not_validate";
                    }else {
                        return "total_not_validate";
                    }
                },

                totalValidationAll: function () {
                    var len = this.studentAnnuals.length;

                    console.log(this.studentAnnuals);
                    var validated = true;
                    for ( var i = 0; i < len ; i++){
                        var tmp = parseFloat(!!!this.scores[this.studentAnnuals[i].id].score60?0:this.scores[this.studentAnnuals[i].id].score60)
                                + parseFloat( !!!this.scores[this.studentAnnuals[i].id].score10?0:this.scores[this.studentAnnuals[i].id].score10)
                                +parseFloat( !!!this.scores[this.studentAnnuals[i].id].score30?0:this.scores[this.studentAnnuals[i].id].score30);
                        console.log(tmp);
                        if (tmp > 100){
                            return  false;
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





        $(document).on("contextmenu", ".scoreper", function (e) {
            if( e.button == 2 ) {
                alert('Right mouse button!');
                return false;
            }
            return true;
        });

        var delay = (function(){
            var timer = 0;
            return function(callback, ms){
                clearTimeout (timer);
                timer = setTimeout(callback, ms);
            };
        })();


        $(document).keyup(function(e) {
            var selfe = e;
            if ($("input.form-score").is(":focus")){
                selfe.preventDefault();
                var $focused = $(':focus');
                var $focusedId = $focused.attr("id")
                var rowCol = $focusedId.split("-");
                rowCol[0] = parseInt(rowCol[0]);
                rowCol[1] = parseInt(rowCol[1]);
                var newId = rowCol;
                switch(selfe.which) {
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
            };

            $("input.form-score").focusin(function () {
                var value = $(this).val();
                if (value == "0"){
                    $(this).val("");
                }
                if (value == 0){
                    $(this).val("");
                }
            });
            $("input.form-score").focusout(function () {
                var value = $(this).val();
                if (value == ""){
                    $(this).val(0);
                }
            });

            // prevent the default action (scroll / move caret)
        });

        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();

                if ($("input.form-score").is(":focus")){
                    var $focused = $(':focus');
                    var $focusedId = $focused.attr("id")
                    var rowCol = $focusedId.split("-");
                    rowCol[0] = parseInt(rowCol[0]);
                    rowCol[1] = parseInt(rowCol[1]);
                    var newId = rowCol;
                    if (newId[1] == 3){
                        newId[1]=0;
                        newId[0]+=1;

                    }else{
                        newId[1]+=1;
                    }
                    $('#'+newId[0]+"-"+newId[1]).focus();
                }
                return false;
            }

        });


        $('#scoreform').submit(function (e) {
            e.preventDefault();
            var validation = test.totalValidationAll();
            if (validation == false ){
                message = new Vue({
                    el: '#flashMessage',
                    template: '<div class="alert alert-error"> Score is not valide! </div>',
                    data: {
                        messages: []
                    },
                });
                $('html,body').animate({
                    scrollTop: $("#flashMessage").offset().top - 70
                });
            }
            return validation;
        });


        button = $(document).find("button#close_course_edit");
        console.log("button");
        console.log(button);

        function validateInputScorePersentage() {
            var result = false;

            var per10 = $("input[name=score_percentage_column_1]").val();
            var per30 = $("input[name=score_percentage_column_2]").val();
            var per60 = $("input[name=score_percentage_column_3]").val();

            console.log("val");
            console.log(per10)
            console.log(per30)
            console.log(per60)

            if (parseInt(per10) + parseInt(per30) + parseInt(per60) == 100){
                result = true;
            }

            // message
            if (result){
                $("#course_message").hide();
            }else{
                $("#course_message").show();
            }

            return result;
        }

        $(document).on('submit','form#editForm',function(e){
            e.preventDefault();

            var form = $("#editForm");
            $.ajaxSetup({
                headers: {
                    'X-XSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            var validation = validateInputScorePersentage();
            console.log(validation);

            if (validation == true){
                $.ajax({
                            url: form.attr("action"),
                            type:"POST",
                            data: form.serialize(),
                            dataType: "json",
                            success: function (json) {
//                            toastr.success(json.message, "Notifications");
                                button.click();
                                reloadThisPage();
                            },
                            error: function (jqXhr, json, errorThrown) {
                                var errors = jqXhr.responseJSON;
                                var errorsHtml = '';
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value[0] + '</li>';
                                });
//                            toastr.error(errorsHtml, "Error " + jqXhr.status + ': ' + errorThrown);
                            }
                        })
                        .done(function (response) {
                            console.log("ajax sent");
                        })
                        .fail(function (jqXHR, json) {
                            //
                        });
            }else{

            }
            return false;
        });
    });
</script>
@endif
