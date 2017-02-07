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
        credit = $('#course_id :selected').attr('credit');

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

    $('#credit').val(credit);

}

$('#course_id').on('change', function() {
    setTimeCourseTpTd();
    setNameKhEnFr();
    setSelectedField();
});

$('Document').ready(function() {
    if($('#course_id :selected').val()) {
        setTimeCourseTpTd();
        setNameKhEnFr();
        setSelectedField();
    }
});

