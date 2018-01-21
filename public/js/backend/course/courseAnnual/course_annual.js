


function load_group(method){

    var course_program_id = $('#course_id :selected').val();
    var department_id = $("#department_id").val();
    var academic_year_id = $("#academic_year_id").val();
    var degree_id = $("#degree_id").val();
    var grade_id = $("#grade_id").val();
    var department_option_id = $("#department_option_id").val();
    var semester_id =  $("#semester_id").val();

    // Load group only department, academic year, degree and grade are filled.
    if(semester_id != "" && semester_id != null) {
        if(department_id != "" && department_id != null  && academic_year_id != "" && degree_id != "" && grade_id != ""){
            $.ajax({
                url : get_group_url,
                type: 'GET',
                data: {
                    department_id:$("#department_id").val(),
                    academic_year_id:$("#academic_year_id").val(),
                    degree_id:$("#degree_id").val(),
                    grade_id:$("#grade_id").val(),
                    department_option_id:$("#department_option_id").val(),
                    course_program_id: course_program_id,
                    semester_id: semester_id,
                    _method: method
                },
                success : function(data){

                    if(data != null){

                        $('.check_all_box').prop('checked', true);
                        var option_text = "";

                        $.each(data.group_code, function(key, value){

                            option_text = option_text +

                                ' <label style="font-size: 12pt" for="'+value+'" class="btn btn-xs"><input checked  style="font-size: 18pt" type="checkbox" id="'+value+'" class="each_check_box" name="groups[]" value="'+data.group_id[value]+'"> '+value+'</label>'

                        })
                        $("#group_panel").html(option_text);
                    }
                }
            })
        }
    }
}

function setTimeCourseTpTd() {

    var course = $('#course_id :selected').attr('time_course'),
        td = $('#course_id :selected').attr('time_td'),
        tp = $('#course_id :selected').attr('time_tp');
    $('#time_course').val(course);
    $('#time_td').val(td);
    $('#time_tp').val(tp);
}

function setNameKhEnFr() {

    var name_kh = $('#course_id :selected').attr('name_kh'),
        name_en = $('#course_id :selected').attr('name_en'),
        name_fr = $('#course_id :selected').attr('name_fr');
    $('#name_kh').val(name_kh);
    $('#name_en').val(name_en);
    $('#name_fr').val(name_fr);

}

function setSelectedField() {

    var grade = $('#course_id :selected').attr('grade'),
        department = $('#course_id :selected').attr('dept'),
        degree  = $('#course_id :selected').attr('degree'),
        dept_option = $('#course_id :selected').attr('dept_option'),
        semester = $('#course_id :selected').attr('semester'),
        credit = $('#course_id :selected').attr('credit'),
        responsible_department_id = $('#course_id :selected').attr('responsible_department_id');

    $('select[name=grade_id] option').each(function() {
        if($(this).val() == grade) {
            $(this).prop('selected', true);
        }
    })

    $('select[name=department_id] option').each(function() {
        if($(this).val() == department) {
            $(this).prop('selected', true);
        }
    })

    $('select[name=degree_id] option').each(function() {
        if($(this).val() == degree) {
            $(this).prop('selected', true);
        }
    })

    $('select[name=department_option_id] option').each(function() {
        if($(this).val() == dept_option) {
            $(this).prop('selected', true);
        }
    })

    $('select[name=semester_id] option').each(function() {
        if($(this).val() == semester) {
            $(this).prop('selected', true);
        }
    })

    $('select[name=responsible_department_id] option').each(function() {
        if($(this).val() == responsible_department_id) {
            $(this).prop('selected', true);
        }
    })

    $('#credit').val(credit);
}



$(".check_all_box").change(function() {

    if(this.checked) {
        $('.each_check_box').prop('checked', true);
    } else {
        $('.each_check_box').prop('checked', false);
    }
});


function loadReferenceCourse(route, token, selected_course_annual_id, depts)
{
    var baseData ;

    if($('select#responsible_department_id :selected').val()) {

        var responsible_department_id = $('select#responsible_department_id :selected').val();

        if(responsible_department_id == depts.sa || responsible_department_id == depts.sf) {


            if(selected_course_annual_id != null && selected_course_annual_id!= '') {
                baseData = {department_id : $('select#responsible_department_id :selected').val(), _token:token, degree_id: $('select#degree_id :selected').val(), grade_id:$('select#grade_id :selected').val(), course_annual_id: selected_course_annual_id}
            } else {
                baseData = {department_id : $('select#responsible_department_id :selected').val(), _token:token, degree_id: $('select#degree_id :selected').val(), grade_id:$('select#grade_id :selected').val()}
            }
            $.ajax({
                method: 'POST',
                url: route,
                data:baseData ,
                dataType: 'JSON',
                success:function (result) {

                    $('#reference_course_id').select2({
                        data: result.data,
                        allowClear: true,
                        placeholder: " Select Program"
                    });
                },
                error: function(error) {

                    notify('error', 'Something went wrong!')
                }
            })

            $( ".block_course_reference" ).show();
        } else {


            $( ".block_course_reference" ).hide();

        }


    }


    $(document).on('change', 'select#responsible_department_id', function() {

        var responsible_department_id = $('select#responsible_department_id :selected').val();

        if(responsible_department_id == depts.sa || responsible_department_id == depts.sf) {

            if(selected_course_annual_id != null && selected_course_annual_id!= '') {
                baseData = {
                    department_id : $(this).val(),
                    _token:token,
                    degree_id: $('select#degree_id :selected').val(),
                    grade_id:$('select#grade_id :selected').val(),
                    course_annual_id: selected_course_annual_id
                }
            } else {

                baseData = {department_id : $(this).val(), _token:token, degree_id: $('select#degree_id :selected').val(), grade_id:$('select#grade_id :selected').val()}
            }
            $.ajax({
                method: 'POST',
                url: route,
                data:baseData ,
                dataType: 'JSON',
                success:function (result) {
                    $('#reference_course_id').html('').select2({
                        placeholder: " Select Program",
                        data: result.data,
                        allowClear: true
                    });
                },
                error: function() {
                    notify('error', 'Something went wrong!')
                }
            });
            $( ".block_course_reference" ).show();
        } else {

            $( ".block_course_reference" ).hide();
        }

    });

}






