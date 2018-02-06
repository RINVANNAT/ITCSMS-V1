// export course session into slots table.
$(document).on('click', '.btn_export_course_program', function (event) {
    event.preventDefault();
    toggleLoading(true);
    axios.post('/admin/schedule/timetables/export_course_program', {
        academic_year_id: $('select[name=academicYear]').val(),
        department_id: $('select[name=department]').val(),
        degree_id: $('select[name=degree]').val(),
        option_id: $('select[name=option]').val(),
        grade_id: $('select[name=grade]').val(),
        group_id: $('select[name=group]').val(),
        semester_id: $('select[name=semester]').val(),
    }).then( function (response) {
        if( response.data.status ) {
            get_course_programs();
            notify('info', 'Slots was exported', 'Export Courses');
        }
        toggleLoading(false);
    }).catch(function (error) {
        console.info(error);
        notify('error', 'Slots was not exported', 'Export Courses');
    });
});

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
                    group_item += '<option value="' + val.id + '">' + val.code + '</option>';
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
            get_course_programs();
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
            // get_groups();
        }
    })
}
/** Get course sessions. **/
function get_course_programs() {
    toggleLoading(true);
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/timetables/get_course_programs',
        data: $('#options-filter').serialize(),
        success: function (response) {
            if (response.status === true && response.data.length>0) {
                var course_session_item = '';
                $.each(response.data, function (key, val) {
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
                $('.courses.todo-list').html("<li class='course-item'>NO COURSES, PLEASE EXPORT COURSE !</li>");
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
                        + '<span class="info-box-text text-muted">' + (val.desk === null ? 'N/A' : val.desk) + ' Desk</span>'
                        + '<span class="info-box-text text-muted">' + (val.chair === null ? 'N/A' : val.chair) + ' Chair</span>'
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


function get_employees(query=null) {
    axios.post('/admin/schedule/timetables/get_employees', {
        query: query
    }).then( response => {
        if(response.data.data.length>0) {
            console.log(23);
            let employee_template = '';
            response.data.data.forEach( (employee) => {
                employee_template += `
                    <li class="select2-results__option"
                        role="treeitem"
                        aria-selected="false">
                        <div class="select2-result-repository clearfix">
                            <div class="select2-result-repository__avatar">
                                <img src="https://smis.itc.app/img/profiles/avatar.png">
                            </div>
                            <div class="select2-result-repository__meta">
                                <div class="select2-result-repository__title">`+(employee.id_card == null ? 'No ID Card' : employee.id_card)+` | `+employee.employee_name_kh+`</div>
                                <div class="select2-result-repository__description">`+employee.employee_name_latin+`</div>
                                <div class="select2-result-repository__statistics">
                                    <div class="select2-result-repository__forks">
                                        <i class="fa fa-bank"></i> `+employee.department_code+`
                                    </div>
                                    <div class="select2-result-repository__stargazers">
                                        <i class="fa fa-venus-mars"></i> `+employee.gender_code+`
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                `;
            });
            $('#employee-viewer').html(employee_template);
        }else{
            $('#employee-viewer').html('<h1>NO EMPLOYEES</h1>');
        }
    })
}