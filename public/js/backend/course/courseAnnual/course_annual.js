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

$('#course_id').on('change', function() {
    setTimeCourseTpTd();
    setNameKhEnFr();
});

$('Document').ready(function() {
    if($('#course_id :selected').val()) {
        setTimeCourseTpTd();
        setNameKhEnFr();
    }
});

