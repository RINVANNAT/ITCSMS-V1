function setSelectedRow() {

    // var current_rows = $(document).find(".current_row");
    // if (current_rows != null) {
    //     current_rows.removeClass("current_row");
    // }
    // var row = $(".current").closest("tr").find('td')[0];
    // var num_tr = $(row).text();
    // $(".current").closest("tr").addClass("current_row");
    // $('div.ht_master').find('table').find('tbody').find('tr:eq('+(num_tr-1)+')').addClass('current_row')

}

var colorRenderer = function (instance, td, row, col, prop, value, cellProperties) {

    Handsontable.renderers.TextRenderer.apply(this, arguments);
    if (jQuery.isNumeric(value)) {
        if (value < parseInt(50)) {/*'{{\App\Models\Enum\ScoreEnum::Pass_Moyenne}}'*/
            if (prop != 'number') {
                if (prop != 'Rank' && prop != 'Rattrapage') {
                    var check = prop.split('_');
                    if (check[0] != 'Abs') {

                        if (prop != 'total') {
                            var colSemester = prop.split('_');
                            if (colSemester[0] != 'S') {

                                if (value < parseInt(30)) {/*{{\App\Models\Enum\ScoreEnum::Under_30}}*/

                                    if (value <= parseInt(10)) {/*'{{\App\Models\Enum\ScoreEnum::Score_10}}'*/
                                        td.style.backgroundColor = '#A41C00';
                                    } else {
                                        td.style.backgroundColor = '#F76A4D';
                                    }

                                } else {
                                    td.style.backgroundColor = '#D2B500';
                                }

                            }
                        }
                    }

                }
            }

        }
        var check = prop.split('_');

        if (check[0] == 'Abs') {
            td.style.backgroundColor = '#E6E6E8'
        }
    }
};


    $('#filter_academic_year').on('change', function () {
        //filter_table();
    })
    $('#filter_grade').on('change', function () {
        //filter_table();
    });
    $('#filter_semester').on('change', function () {
        //filter_table();
    })
    $('#filter_degree').on('change', function () {
        //filter_table();
    });

    $('#filter_group').on('change', function () {
        //filter_table();
    });

    var getBaseData = function() {

        var BaseData = {
            department_id: $('#filter_dept :selected').val(),
            degree_id: $('#filter_degree :selected').val(),
            grade_id: $('#filter_grade :selected').val(),
            academic_year_id: $('#filter_academic_year :selected').val(),
            semester_id: $('#filter_semester :selected').val(),
            dept_option_id: $('#filter_dept_option :selected').val(),
            group_name: $('#filter_group :selected').val()

        }

        return BaseData;
    };

    var getSelectedText = function() {

        var objectText = {
            department: $('#filter_dept :selected').text(),
            degree: $('#filter_degree :selected').val(),
            grade: $('#filter_grade :selected').val(),
            academic_year: $('#filter_academic_year :selected').text(),
            semester_id: $('#filter_semester :selected').val(),
            dept_option: $('#filter_dept_option :selected').text(),
            group_name: $('#filter_group :selected').val()

        }

    return objectText;
};

    if ($('.message').is(':visible')) {
        setTimeout(function () {
            $(".message").fadeOut("slow");
        }, 3000);
    }

    $('#print_total_radie').on('click', function (e) {
        e.preventDefault();
        var remote_url = $(this).attr('href');
        var academic_year_id = $('select[name=academic_year] :selected').val()
        var semester_id = $('select[name=semester] :selected').val();
        if (academic_year_id) {
            window.open(remote_url + '?academic_year_id=' + academic_year_id + '&semester_id=' + semester_id, '_blank')
        } else {
            notify('info', 'Please select a year!')
        }
    });


/*-----Assign rattrapage on frontend -----(number of subject that student should re-exam)---*/


function getStudentReExam(subjects, pass_moyenn, approximate_moyenne) {

    var total_credit = 0;
    if ('fail' in subjects) {//=== isset() in php

        if ('pass' in subjects) {

            var validate_score = 0;

            //console.log(subjects);

            $.each(subjects['fail'], function (f_key, f_val) {

                //console.log(validate_score +'=='+ validate_score +'++'+ parseFloat('{{\App\Models\Enum\ScoreEnum::Pass_Moyenne}}') +'*'+ f_val['credit']);
                total_credit  += parseFloat(f_val['credit']);
                validate_score +=  parseFloat(pass_moyenn) * f_val['credit']; /*{{\App\Models\Enum\ScoreEnum::Pass_Moyenne}}*/
                //console.log(validate_score);
            });

            $.each(subjects['pass'], function (p_key, p_val) {
                //console.log(validate_score +'=='+ validate_score +'++'+ parseFloat(p_val['score']) +'*'+ p_val['credit']);
                total_credit += parseFloat(p_val['credit']);
                validate_score  += (parseFloat(p_val['score']) * p_val['credit']);
                //console.log(validate_score);
            });

            var approximation_moyenne = parseFloat((parseFloat(validate_score) / parseFloat(total_credit)));

            //console.log(approximation_moyenne +'=='+ (parseFloat(validate_score) +'/'+ parseFloat(total_credit)));

            if (approximation_moyenne < parseFloat(approximate_moyenne)) {//----55 /*'{{\App\Models\Enum\ScoreEnum::Aproximation_Moyenne}}'*/

                if (subjects['pass'].length > 0) {

                    var find_min = findMinScore(subjects['pass']);

                    if (find_min['element']['score'] < parseFloat(pass_moyenn)) {//--count couse score for only less than 50

                        subjects['fail'].push(find_min['element'])
                        delete subjects['pass'][find_min['index']];

                        var tmp_subject_pass = [];

                        $.each(subjects['pass'], function (key, obj_subject) {
                            if (!$.isEmptyObject(obj_subject)) {
                                tmp_subject_pass.push(obj_subject);
                            }
                        });
                        subjects['pass'] = tmp_subject_pass;

                        return getStudentReExam(subjects, pass_moyenn, approximate_moyenne); //---recuring this function again
                    } else {

                        //---if approximation  moyenne is bigger than 50 allow him
                        if (approximation_moyenne > parseFloat(pass_moyenn)) {
                            return subjects;
                        } else {
                            return subjects;
                        }
                    }
                } else {

                    return subjects;
                }
            } else {
                return subjects;
            }
        } else {
            return subjects; //---student fail all subject
        }
    } else {

        /*--check if the moyenne of student is under 50 and all subject are bigger than 30*/

        var approximation_moyenne = calculate_moyenne(subjects);

        if (approximation_moyenne < parseFloat(pass_moyenn)) {

            subjects['fail'] = [];
            if (subjects['pass'].length > 0) {

                var find_min = findMinScore(subjects['pass']);

                if (find_min['element']['score'] < parseFloat(pass_moyenn)) {

                    subjects['fail'].push(find_min['element'])
                    delete subjects['pass'][find_min['index']];
                    var tmp_subject_pass = [];
                    $.each(subjects['pass'], function (key, obj_subject) {
                        if (!$.isEmptyObject(obj_subject)) {
                            tmp_subject_pass.push(obj_subject);
                        }
                    });
                    subjects['pass'] = tmp_subject_pass;
                    return getStudentReExam(subjects, pass_moyenn, approximate_moyenne); //---recuring this function again

                } else {

                    return subjects;
                }
            } else {
                return subjects;
            }

        } else {

            return subjects;
        }
    }
}


function calculate_moyenne(subjects) {

    var credit = 0;
    var score = 0;
    if ('fail' in subjects) {
        if ('pass' in subjects) {

            $.each(subjects['fail'], function (f_index, f_value) {
                credit = credit + parseFloat(f_value['credit']);
                score = score + (parseFloat(f_value['score']) * parseFloat(f_value['credit']));
            });

            $.each(subjects['pass'], function (p_index, p_value) {
                credit = credit + parseFloat(p_value['credit']);
                score = score + (parseFloat(p_value['score']) * parseFloat(p_value['credit']));
            });

            return parseFloat(parseFloat(score) / parseFloat(credit));

        } else {

            $.each(subjects['fail'], function (f_index, f_value) {
                credit = credit + parseFloat(f_value['credit']);
                score = score + (parseFloat(f_value['score']) * parseFloat(f_value['credit']));
            });

            return parseFloat(parseFloat(score) / parseFloat(credit));
        }

    } else {

        $.each(subjects['pass'], function (index, value) {
            credit = credit + parseFloat(value['credit']);
            score = score + (parseFloat(value['score']) * parseFloat(value['credit']));
        });

        return parseFloat(parseFloat(score) / parseFloat(credit));
    }
}



function findMinScore(subjects_pass) {

    var min = subjects_pass[0]['score'];
    var credit = subjects_pass[0]['credit'];
    var course_annual_id = subjects_pass[0]['course_annual_id'];
    var index = 0;

    for (var int = 1; int < subjects_pass.length; int++) {
        if (min > subjects_pass[int]['score']) {

            index = int;
            min = subjects_pass[int]['score'];
            credit = subjects_pass[int]['credit'];
            course_annual_id = subjects_pass[int]['course_annual_id'];
        }
    }
    return {
        element: {score: min, credit: credit, course_annual_id: course_annual_id},
        index: index
    }
}


