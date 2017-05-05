$(document).ready(function () {
    // search rooms.
    $(document).on('keyup', 'input[name="search_room_query"]', function () {
        search_rooms($(this).val());
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
        get_rooms();

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

/*Drag course session into timetable.*/
var drag_course_session = function () {

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
};
/** List all rooms. **/
var get_rooms = function () {
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/timetables/get_rooms',
        success: function (response) {
            if (response.status == true) {
                var room_item = '';
                $.each(response.rooms, function (key, val) {
                    room_item += '<div class="room-item" id="room' + val.id + '">'
                        + '<i class="fa fa-building-o"></i> '
                        + val.name + '-' + val.code
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
        }
    })

};
/** Get rooms. **/
var get_groups = function () {
    setTimeout(function () {
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

            }
        })
    }, 200);
};
/** Get weeks. **/
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
/** Get options. **/
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
/** Get course sessions. **/
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
    }, 300);
};
/** Search rooms. **/
var search_rooms = function (query) {
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/timetables/search_rooms',
        data: {query: query},
        success: function (response) {
            if (response.status == true) {
                var room_item = '';
                $.each(response.rooms, function (key, val) {
                    room_item += '<div class="room-item" id="room' + val.id + '">'
                        + '<i class="fa fa-building-o"></i> '
                        + val.name + '-' + val.code
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
};