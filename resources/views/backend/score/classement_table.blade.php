@if($studentAnnuals->isEmpty())
    no student found
@else
<div  id="vue_table_score">
    <table>
        <thead >
            <th ></th>
            <th class="coursetitle">
            </th>
            <th></th>
            <th ></th>
            <th></th>
            <th class="coursetitle" colspan="2" v-for="(index, courseAnnual) in courseAnnuals" >
                <span id="course_title" class="coursetitlebg short_column" toggleremove="1" cid='@{{courseAnnual.id}}' toggle="0" > @{{ courseAnnual.name }}  </span>
            </th>
            <th class="coursetitle">
                <span id="ranking" toggle="0" class="coursetitlebg short_column" toggleremove="1"> Classement  </span>
            </th>
            <th class="coursetitle">
                <span id="ranking"  toggle="0" class="coursetitlebg short_column" toggleremove="1"> ranking  </span>
            </th>
            <th class="coursetitle">
                 <span class="coursetitlebg"> Observation  </span>
            </th>
        </thead>

        <thead>
            <th>No</th>
            <th><span id="student_id" toggle="0">ID</span> </th>
            <th><span id="student_name" toggle="0" style="word-wrap: break-word; max-width: 50px;">Student Name </span> </th>
            <th>Sex</th>
            <th><span id="abs_total" toggle="0">Abs Total</span> </th>
            <template v-for="courseAnnual in courseAnnuals" >
                <th>
                    @{{ courseAnnual.course.credit }}
                </th>
                <th>
                    Abs
                </th>
            </template>
            <th> </th>
            <th>  </th>
            <th> </th>
        </thead>
        <tbody>
            <template v-for="(index, student)  in studentAnnuals ">
                <tr>
                    <td>@{{ student.no }}</td>
                    <td>@{{ student.id_card }} </td>
                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">@{{ student.name_kh }}</td>
                    <td>@{{ student.gender.code  }}</td>
                    <td> @{{ absencesCounts["totalabs"][student.id]}}</td>
                    <template v-for="courseAnnual in courseAnnuals" >
                        <td>
                            @{{ absencesCounts[student.id][courseAnnual.id] }}
                        </td>
                        <td  class="coursescore" highlight='@{{ scoresDataViews[student.id][courseAnnual.id]["highlight"]}}' >
                            @{{ scoresDataViews[student.id][courseAnnual.id]["scoreTotalinCourse"]   }}
                        </td>
                    </template>
                    <td class="classementhighlight" highlight='@{{  scoresDataViews[student.id][courseAnnual.id]["moyennehighlight"] }}' >
                        @{{ (scoresDataViews[student.id]["moyenne"]).toFixed(2); }}
                    </td>
                    <td>
                        @{{ scoresDataViews[student.id]["ranking"] }}
                    </td>
                    <td>
                        <div style="white-space: nowrap;" class="eval-popup" studentId='@{{ student.id }}' evalId='@{{ evalStatus[student.id]["id"] }}' >   @{{ evalStatus[student.id]["name"] }}   </div>
                    </td>
                </tr>
            </template>

        </tbody>
    </table>

</div>
<script>
    courseAnnules = {!! $courseAnnuals->toJson() !!};
    studentAnnuals = {!! json_encode($studentAnnuals) !!};
    scoresDataViews = {!!  json_encode($scoresDataViews) !!};
    absencesCounts = {!! json_encode($absencesCounts) !!};
    evalStatus={!!json_encode($evalStatus)  !!};



    function  renderScoreTable(courseAnnuals, studentAnnuals, scoresDataViews, absencesCounts, evalStatus){

    }

    $( document ).ready(function() {
        new Vue({
            el: '#vue_table_score',
            data: {
                courseAnnuals: courseAnnules,
                studentAnnuals:studentAnnuals,
                scoresDataViews:scoresDataViews,
                absencesCounts:absencesCounts,
                evalStatus:evalStatus
            }
        });
    });

    $(document).on("click","#testshort", function(e){
        //courseAnnules.splice(1, 1);

        studentAnnuals.sort(function(a, b){
            if(a.name_kh < b.name_kh) return -1;
            if(a.name_kh > b.name_kh) return 1;
            return 0;
        })
    });


    $(document).on("click","#student_name", function(e){
        //courseAnnules.splice(1, 1);
        var ordering = $(this).attr("toggle");

        //asd
        if (ordering == "0"){
            studentAnnuals.sort(function(a, b){
                if(a.name_kh > b.name_kh) return -1;
                if(a.name_kh < b.name_kh) return 1;
                return 0;
            });
            $(this).attr("toggle", "1");
        }else{
            //des
            studentAnnuals.sort(function(a, b){
                if(a.name_kh < b.name_kh) return -1;
                if(a.name_kh > b.name_kh) return 1;
                return 0;
            });

            $(this).attr("toggle", "0");
        }
    });
    $(document).on("click","#student_id", function(e){
        //courseAnnules.splice(1, 1);
        var ordering = $(this).attr("toggle");

        //asd
        if (ordering == "0"){
            studentAnnuals.sort(function(a, b){
                if(a.id_card < b.id_card) return -1;
                if(a.id_card > b.id_card) return 1;
                return 0;
            });
            $(this).attr("toggle", "1");
        }else{
            //des
            studentAnnuals.sort(function(a, b){
                if(a.id_card > b.id_card) return -1;
                if(a.id_card < b.id_card) return 1;
                return 0;
            })
            $(this).attr("toggle", "0");
        }
    });


    $(document).on("click","#abs_total", function(e){
        //courseAnnules.splice(1, 1);
        var ordering = $(this).attr("toggle");



        if (ordering == "0"){
            studentAnnuals.sort(function(a, b){
                if(absencesCounts["totalabs"][a.id]  < absencesCounts["totalabs"][b.id] ) return -1;
                if(absencesCounts["totalabs"][a.id]  > absencesCounts["totalabs"][b.id]  ) return 1;
                return 0;
            });
            $(this).attr("toggle", "1");
        }else{
            //des
            studentAnnuals.sort(function(a, b){
                if(absencesCounts["totalabs"][a.id]  < absencesCounts["totalabs"][b.id] ) return 1;
                if(absencesCounts["totalabs"][a.id]  > absencesCounts["totalabs"][b.id]  ) return -1;
                return 0;
            })
            $(this).attr("toggle", "0");
        }
    });


    $(document).on("click","#course_title", function(e){
        //courseAnnules.splice(1, 1);
        var ordering = $(this).attr("toggle");
        var courseId = $(this).attr("cid");



        if (ordering == "0"){
            studentAnnuals.sort(function(a, b){
                if(scoresDataViews[a.id][courseId]["scoreTotalinCourse"]  < scoresDataViews[b.id][courseId]["scoreTotalinCourse"] ) return -1;
                if(scoresDataViews[a.id][courseId]["scoreTotalinCourse"]  > scoresDataViews[b.id][courseId]["scoreTotalinCourse"]  ) return 1;
                return 0;
            });
            $(this).attr("toggle", "1");
        }else{
            //des
            studentAnnuals.sort(function(a, b){
                if(scoresDataViews[a.id][courseId]["scoreTotalinCourse"]  < scoresDataViews[b.id][courseId]["scoreTotalinCourse"] ) return 1;
                if(scoresDataViews[a.id][courseId]["scoreTotalinCourse"]  > scoresDataViews[b.id][courseId]["scoreTotalinCourse"]  ) return -1;
                return 0;
            })
            $(this).attr("toggle", "0");
        }
    });

    $(document).on("click","#ranking", function(e){
        //courseAnnules.splice(1, 1);
        var ordering = $(this).attr("toggle");
        var courseId = $(this).attr("cid");

        $(this).attr("toggleremove","0");
        $(".short_column").attr("toggleremove","1");



        if (ordering == "0"){
            studentAnnuals.sort(function(a, b){
                if(scoresDataViews[a.id]["ranking"]  < scoresDataViews[b.id]["ranking"] ) return -1;
                if(scoresDataViews[a.id]["ranking"]  > scoresDataViews[b.id]["ranking"]  ) return 1;
                return 0;
            });
            $(this).attr("toggle", "1");
        }else{
            //des
            studentAnnuals.sort(function(a, b){
                if(scoresDataViews[a.id]["ranking"]  < scoresDataViews[b.id]["ranking"] ) return 1;
                if(scoresDataViews[a.id]["ranking"]  > scoresDataViews[b.id]["ranking"]  ) return -1;
                return 0;
            })
            $(this).attr("toggle", "0");
        }
    });



</script>
@endif

