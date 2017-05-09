$(document).ready(function () {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
});

/*Drag course session into timetable.*/
function drag_course_session() {

    $('.courses .course-item').each(function () {

        // store data so the calendar knows to render an event upon drop
        $(this).data('event', {
            course_session_id: $(this).find('.courses-session-id').text(),
            course_name: $(this).find('.course-name').text(),
            class_name: 'course-item',
            teacher_name: $(this).find('.teacher-name').text(),
            course_type: $(this).find('.course-type').text(),
            times: $(this).find('.times').text()
        });

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true,      // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
        });

    });
}

/** Get rooms. **/
function get_groups() {
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/timetables/get_groups',
        data: $('#options-filter').serialize(),
        success: function (response) {
            if (response.status == true) {
                var group_item = '';
                $.each(response.groups, function (key, val) {
                    group_item += '<option value="' + val.id + '">' + val.name + '</option>';
                });

                $('select[name="group"]').html(group_item);
            }
            else {
                $('select[name="group"]').html('');
            }
        },
        error: function () {

        },
        complete: function () {
            get_weeks($('select[name="semester"] :selected').val());
        }
    })
}

/** Get weeks. **/
function get_weeks(semester_id) {
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/timetables/get_weeks',
        data: {semester_id: semester_id},
        success: function (response) {
            var option = '';
            $.each(response.weeks, function (key, val) {
                option += '<option value="' + val.id + '">' + val.name_en + '</option>';
            });

            $('select[name="weekly"]').html(option);
        },
        error: function () {

        },
        complete: function () {
            get_course_sessions();
            get_timetable();
            get_timetable_slots();
        }
    });
}

/** Get options. **/
function get_options(department_id) {
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/timetables/get_options',
        data: {department_id: department_id},
        success: function (response) {
            var option = '';
            $.each(response.options, function (key, val) {
                option += '<option value="' + val.id + '">' + val.code + '</option>';
            });

            $('select[name="option"]').html(option);
        },
        error: function () {

        },
        complete: function () {
            get_groups();
        }
    });
}

/** Get course sessions. **/
function get_course_sessions() {
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/timetables/get_course_sessions',
        data: $('#options-filter').serialize(),
        success: function (response) {
            if (response.status == true) {
                var course_session_item = '';
                $.each(response.course_sessions, function (key, val) {
                    course_session_item += '<li class="course-item">' +
                        '<span class="handle ui-sortable-handle">' +
                        '<i class="fa fa-ellipsis-v"></i> ' +
                        '<i class="fa fa-ellipsis-v"></i>' +
                        '</span>' +
                        '<span class="text course-name">' + val.course_name + '</span><br>' +
                        '<span style="margin-left: 28px;" class="teacher-name">' + val.teacher_name + '</span><br/>';
                    if (val.tp != 0) {
                        course_session_item += '<span style="margin-left: 28px;" class="course-type">TP</span> : ' +
                            '<span class="times">' + val.tp + '</span> H'
                    }
                    else if (val.td != 0) {
                        course_session_item += '<span style="margin-left: 28px;" class="course-type">TD</span> : ' +
                            '<span class="times">' + val.td + '</span> H'
                    }
                    else {
                        course_session_item += '<span style="margin-left: 28px;" class="course-type">Course</span> : ' +
                            '<span class="times">' + val.tc + '</span> H'
                    }
                    course_session_item += '<span class="text courses-session-id" style="display: none;">' + val.id + '</span><br>' + '</li>';
                });

                $('.courses.todo-list').html(course_session_item);
                drag_course_session()
            }
            else {
                $('.courses.todo-list').html("<li class='course-item'>There are no course sessions created yet.</li>");
            }
        },
        error: function () {

        }
    });
}

/** Search rooms. **/
function search_rooms(query) {
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/timetables/search_rooms',
        data: {query: query},
        success: function (response) {
            if (response.status == true) {
                var room_item = '';
                $.each(response.rooms, function (key, val) {
                    room_item += '<div class="room-item" id="' + val.id + '">'
                        + '<i class="fa fa-building-o"></i> '
                        + '<span>' + val.name + '-' + val.code + '</span>'
                        + '</div> ';
                });

                $('.rooms').html(room_item);
            }
            else {
                var message = '<div class="room-item bg-danger" style="width: 100%; background-color: red; color: #fff;">' +
                    '<i class="fa fa-warning"></i> Room not found!' +
                    '</div>';
                $('.rooms').html(message);
            }
        },
        error: function () {
            swal(
                'Oops...',
                'Something went wrong!',
                'error'
            );
        }
    });
}