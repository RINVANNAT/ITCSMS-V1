/** Get rooms. **/
function get_groups() {
    toggleLoading(true);
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/timetables/get_groups',
        data: $('#options-filter').serialize(),
        success: function (response) {
            if (response.status === true) {
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
            swal(
                'Oops...',
                'Something went wrong!',
                'error'
            );
        },
        complete: function () {
            get_weeks($('select[name="semester"] :selected').val());
            toggleLoading(false);
        }
    })
}

/** Get weeks. **/
function get_weeks(semester_id) {
    toggleLoading(true);
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
            swal(
                'Oops...',
                'Something went wrong!',
                'error'
            );
        },
        complete: function () {
            get_course_sessions();
            get_timetable();
            get_timetable_slots();
            toggleLoading(false);
        }
    });
}

/** Get options. **/
function get_options(department_id) {
    toggleLoading(true);
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
            swal(
                'Get Options',
                'Something went wrong!',
                'error'
            );
        },
        complete: function () {
            get_grades(department_id);
            toggleLoading(false);
        }
    });
}


function get_grades(department_id) {
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/timetables/get_grades',
        data: {department_id: department_id},
        success: function (response) {
            if (response.status === true) {
                var grades = '';
                $.each(response.grades, function (key, val) {
                    grades += '<option value="' + val.id + '">' + val.name_en + '</option>';
                });
                $('select[name="grade"]').html(grades);
            }
        },
        error: function () {
            swal(
                'Get Grades',
                'Something went wrong!',
                'error'
            );
        },
        complete: function () {
            get_groups();
        }
    })
}
/** Get course sessions. **/
function get_course_sessions() {
    toggleLoading(true);
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/timetables/get_course_sessions',
        data: $('#options-filter').serialize(),
        success: function (response) {
            if (response.status === true) {
                var course_session_item = '';
                $.each(response.course_sessions, function (key, val) {
                    if (val.teacher_name === null) {
                        course_session_item += '<li class="course-item disabled">';
                    }
                    else {
                        course_session_item += '<li class="course-item">';
                    }
                    course_session_item += '<span class="handle ui-sortable-handle">' +
                        '<i class="fa fa-ellipsis-v"></i> ' +
                        '<i class="fa fa-ellipsis-v"></i>' +
                        '</span>' +
                        '<span class="text course-name">' + val.course_name + '</span><br>';
                    if (val.teacher_name === null) {
                        course_session_item += '<span style="margin-left: 28px;" class="teacher-name bg-danger badge">Unsigned</span><br/>';
                    } else {
                        course_session_item += '<span style="margin-left: 28px;" class="teacher-name">' + val.teacher_name + '</span><br/>';
                    }
                    if (val.tp !== 0) {
                        course_session_item += '<span style="margin-left: 28px;" class="course-type">TP</span> : ' +
                            '<span class="times">' + val.remaining + '</span> H'
                    }
                    else if (val.td !== 0) {
                        course_session_item += '<span style="margin-left: 28px;" class="course-type">TD</span> : ' +
                            '<span class="times">' + val.remaining + '</span> H'
                    }
                    else {
                        course_session_item += '<span style="margin-left: 28px;" class="course-type">Course</span> : ' +
                            '<span class="times">' + val.remaining + '</span> H'
                    }
                    course_session_item += '<span class="text courses-session-id" style="display: none;">' + val.course_session_id + '</span><span class="text slot-id" style="display: none;">' + val.id + '</span><br>' + '</li>';
                });

                $('.courses.todo-list').html(course_session_item);
                drag_course_session()
            }
            else {
                $('.courses.todo-list').html("<li class='course-item'>There are no course sessions created yet.</li>");
            }
        },
        error: function () {
            swal(
                'Oops...',
                'Something went wrong!',
                'error'
            );
        },
        complete: function () {
            toggleLoading(false);
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
            if (response.status === true) {
                var room_item = '';
                $.each(response.rooms, function (key, val) {
                    room_item += '<div class="info-box">'
                        + '<span class="info-box-icon bg-aqua">'
                        + '<span>' + val.code + '-' + val.name + '</span>'
                        + '</span>'
                        + '<div class="info-box-content">'
                        + '<span class="info-box-number">' + val.room_type + '</span>'
                        + '<span class="info-box-text text-muted">' + (val.desk === null ? 0 : val.desk) + ' Desk</span>'
                        + '<span class="info-box-text text-muted">' + (val.chair === null ? 0 : val.chair) + ' Chair</span>'
                        + '</div>'
                        + '</div>';
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

/** set background color slot not allow */
function set_background_color_slot_not_allow() {
    $('.view-timetable').find('[data-time="11:00:00"]').addClass('slot-not-allow');
    $('.view-timetable').find('[data-time="11:30:00"]').addClass('slot-not-allow');
    $('.view-timetable').find('[data-time="12:00:00"]').addClass('slot-not-allow');
    $('.view-timetable').find('[data-time="12:30:00"]').addClass('slot-not-allow');
    $('.view-timetable').find('[data-time="17:00:00"]').addClass('slot-not-allow');
}
