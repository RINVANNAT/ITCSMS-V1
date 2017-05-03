$(document).ready(function () {

    /*********************************
     * Load module.
     *
     *********************************/

    get_options($('select[name="department"] :selected').val());
    get_weeks($('select[name="semester"] :selected').val());
    get_course_sessions();
    get_groups();
    drag_course_session();

    /**********************************
     * Option select semester.
     * Department selected show option
     *
     **********************************/
    // get weeks.
    $(document).on('change', 'select[name="semester"]', function () {
        get_weeks($(this).val());
        get_course_sessions();
    });
    // get options.
    $(document).on('change', 'select[name="department"]', function () {
        get_options($(this).val());
        get_groups();
        get_course_sessions();
    });
    // get course session.
    $(document).on('change', 'select[name="academicYear"]', function () {
        get_course_sessions();
    });
    // get group and course session.
    $(document).on('change', 'select[name="option"]', function () {
        get_groups();
        get_course_sessions();
    });
    // get course session.
    $(document).on('change', 'select[name="grade"]', function () {
        get_course_sessions();
    });

    // Call function dragging courses sessions.
    $('.room-item').removeAttr('style');
    $('.course-item').removeAttr('style');

    // Clicking to remove the room from course.
    $(document).on('click', '.remove-room', function () {
        $(this).addClass('rf-room');
        $(this).parent().parent().parent().children().eq(0).children().eq(0).addClass('rf-room-name');
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(function () {
            $('body').find('.rf-room').remove();
            $('body').find('.rf-room-name').empty().removeClass('rf-room-name');
            swal(
                'Removed !',
                'The room have been removed from this course.',
                'success'
            )
        })
    });

    // Click on course item show available room.
    $(document).on('click', '.side-course', function () {

        $('body').find('.course-selected').removeClass('course-selected');
        $(this).addClass('course-selected');
        var room = '';
        for (var i = 0; i < 10; i++) {
            room += '<div class="room-item suggest-room">';
            room += '<i class="fa fa-ellipsis-v"></i> ';
            room += '<i class="fa fa-ellipsis-v"></i> F-' + Math.floor(Math.random() * 201)
            room += '</div> ';
        }
        // Apply with $.ajax({ /** implementation.... */ });
        $('.rooms').html(room);

    });

    // Add room into course
    $(document).on('click', '.suggest-room', function () {
        var btn_delete = '<button class="btn btn-danger btn-xs remove-room"><i class="fa fa-trash"></i></button>';
        $('.course-selected').parent().children().eq(1).children().eq(0).children().text($(this).text());
        $('.course-selected').parent().children().eq(1).children().eq(1).children().eq(1).html(btn_delete);
        $(this).remove();

    });

    // Conflict button action
    $(document).on('click', '#btn-conflict', function (event) {
        event.preventDefault();
        var table = '';
        table += '<table class="table table-bordered room-item">' +
            '<thead>' +
            '<tr>' +
            '<th>No.</th>' +
            '<th>Conflict Name</th>' +
            '<th>Description</th>' +
            '<th>Action</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            '<tr>' +
            '<td>01</td>' +
            '<td>Room</td>' +
            '<td>Please solve conflict</td>' +
            '<td>' +
            '<button class="btn btn-success btn-xs"><i class="fa fa-check"></i> </button> ' +
            '<button class="btn btn-danger btn-xs"><i class="fa fa-times"></i> </button> ' +
            '</td>' +
            '</tr>' +
            '</tbody>' +
            '</table>';
        $('.rooms').html(table);

    });
});

// Drag room into timetable.
var drag_room = function () {
    $('.rooms .room-item').each(function () {

        $(this).data('event', {
            title: $.trim($(this).text()),
            stick: true
        });

        $(this).draggable({
            zIndex: 1000000,
            revert: true,
            revertDuration: 0
        });

    });
};

// Drag course session into timetable.
var drag_course_session = function () {

    $('.courses .course-item').each(function () {
        $(this).data('event', {
            id: $(this).find('.courses-session-id').text(),
            title: $(this).find('.course-name').text(),
            className: 'course-item',
            teacherName: $(this).find('.teacher-name').text(),
            typeCourseSession: $(this).find('.course-type').text(),
            times: $(this).find('.times').text()
        });

        $(this).draggable({
            zIndex: 1000000,
            revert: true,
            revertDuration: 0
        });

    });
};

// List all rooms.
var rooms = function (nb_rooms) {

    var room = '';
    room += '<div class="room-item">'
        + '<i class="fa fa-ellipsis-v"></i>'
        + '<i class="fa fa-ellipsis-v"></i> F-306</div>';
    for (var i = 0; i < nb_rooms; i++) {
        room += room;
    }

    $('.rooms').append(room);

};

var get_groups = function () {
    setTimeout(function () {
        $.ajax({
            type: 'POST',
            url: '/admin/schedule/timetables/get_groups',
            data: $('#options-filter').serialize(),
            success: function (response) {
                if(response.status == true)
                {
                    var group_item = '';
                    $.each(response.groups, function (key, val) {
                        group_item += '<option value="'+val.id+'">'+val.group+'</option>';
                    });

                    $('select[name="group"]').html(group_item);
                }
            },
            error: function () {

            }
        })
    }, 100);
};

var get_weeks = function (semester_id) {
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

        }
    });
};

var get_options = function (department_id) {
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

        }
    });
};

var get_course_sessions = function () {
    setTimeout(function () {
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
                                '<span class="times">' + val.courses + '</span> H'
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
    }, 100);
};