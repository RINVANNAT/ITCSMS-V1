var getBaseData = function(){
    var data = {
        academic_year_id: $('select#filter_academic_year :selected').val(),
        department_id: $('select#filter_dept :selected').val(),
        dept_option_id: $('select#filter_dept_option :selected').val(),
        degree_id: $('select#filter_degree :selected').val(),
        semester_id: $('select#filter_semester :selected').val(),
        grade_id: $('select#filter_grade :selected').val(),
    };
    return data;
};


$(document).on('change', 'select#filter_academic_year', function (e) {
    getCourseAnnuals();

});

$(document).on('change', 'select#filter_dept', function (e) {
    getCourseAnnuals();
});

$(document).on('change', 'select#filter_dept_option', function (e) {

    getCourseAnnuals();
});

$(document).on('change', 'select#filter_degree', function (e) {

    getCourseAnnuals();
});

$(document).on('change', 'select#filter_semester', function (e) {

    getCourseAnnuals();
});

$(document).on('change', 'select#filter_grade', function (e) {
    getCourseAnnuals();
});

function getCourseAnnuals ()
{

    $.ajax({
        method:"GET",
        url: '/admin/course/directed-course-annual',
        data:getBaseData(),
        dataType:'HTML',
        success: function (result) {
            $('div.blog_course').html(result)
        },
        error: function (response) {

            notify('error', 'SomeThing Went Wrong!')

        }
    });
}

$(document).on( 'change','input.course_annual_radio', function (e) {

    if($(this).is(':checked')) {

        $.ajax({
            method:'POST',
            url: '/admin/course/course-annual/validate-responsible-course',
            data:{selected_course_annual_id: $(this).val(), course_annual_id: $('select[name=available_course] :selected').val()},
            success:function (result) {

                if(result.status) {

                    showNotify('success' , result.message, 'Notification')
                    $('#publish_score').show();


                } else {

                    showNotify('warning' , result.message, 'Attention')
                    $('#publish_score').hide();
                }
                
            },
            error: function (response) {
                
            }
        })
    }
});


function filterGroup () {


    $.ajax({
        method:'POST',
        url: '/admin/course/course-annual/validate-responsible-course',
        data:{selected_course_annual_id: $(this).val(), course_annual_id: $('select[name=available_course] :selected').val()},
        success:function (result) {

            if(result.status) {

                showNotify('success' , result.message, 'Notification')



            } else {

                showNotify('warning' , result.message, 'Attention')
            }

        },
        error: function (response) {

        }
    })
}

function showNotify(type, message, title){
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    toastr[type](message, title);
}
