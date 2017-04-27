$(document).ready(function () {
    // Filter courses sessions.
    $('#filter-courses-sessions').on('change', function (e) {

        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: '/admin/schedule/timetables/filter-courses-sessions    ',
            data: $('#filter-courses-sessions').serialize(),
            success: function (response) {
                console.log(response);
            },
            error: function () {
                swal(
                    'Oops...',
                    'Something went wrong!',
                    'error'
                );
            }
        });

    });

    // Call function dragging courses sessions.
    drag_course_session();

    // Timetable sections.
    $('#timetable').fullCalendar({

        defaultView: 'timetable',
        defaultDate: '2017-01-01',
        header: false,
        footer: false,
        views: {
            timetable: {
                type: 'agendaWeek',
                setHeight: '100px'
            }
        },
        allDaySlot: false,
        hiddenDays: [0],
        height: 650,
        fixedWeekCount: false,
        minTime: '07:00:00',
        maxTime: '20:00:00',
        slotLabelFormat: 'h:mm a',
        columnFormat: 'dddd',
        events: [
            {
                id: 1,
                title: 'Event Conflict',
                start: '2017-01-02 07:00:00',
                end: '2017-01-02 10:00:00',
                teacherName: 'CHUN Thavorac',
                typeCourseSession: 'TP',
                times: 23,
                allDay: false,
                status: true
            },
            {
                id: 2,
                title: 'Computer Architecture',
                start: '2017-01-03 07:00:00',
                end: '2017-01-03 09:00:00',
                teacherName: 'CHUN Thavorac',
                typeCourseSession: 'TP',
                times: 23,
                allDay: false,
                status: false
            }
        ],
        editable: true,
        droppable: true,
        dragRevertDuration: 10,
        drop: function () {
            $(this).addClass('course-selected');

            setTimeout(function () {
                $(this).removeClass('course-selected');
            }, 100);
        },
        eventDragStart: function (event, jsEvent, ui, view) {
            var room = '';
            room += '<div class="room-item ui-draggable ui-draggable-handle">';
            room += '<i class="fa fa-refresh"></i> Loading...';
            room += '</div>';
            $('.rooms').html(room);
        },
        eventDragStop: function (event, jsEvent, ui, view) {
            // Trigger when stop drag the event.
            if (isEventOverDiv(jsEvent.clientX, jsEvent.clientY)) {
                $('#timetable').fullCalendar('removeEvents', event._id);
                var course = '';
                course += '<li class="course-item drag-course-back">'
                    + '<span class="handle ui-sortable-handle">'
                    + '<i class="fa fa-ellipsis-v"></i> '
                    + '<i class="fa fa-ellipsis-v"></i>'
                    + '</span>'
                    + '<span class="text course-name">' + event.title + '</span><br>'
                    + '<span style="margin-left: 28px;" class="teacher-name">' + event.teacherName + '</span><br/>'
                    + '<span style="margin-left: 28px;" class="course-type">' + event.typeCourseSession + '</span> :'
                    + '<span class="times">' + event.times + '</span> H'
                    + '</li>';

                $('.courses').prepend(course);

                setTimeout(function () {
                    $('.courses').find('.drag-course-back').removeClass('drag-course-back');
                }, 300);

                drag_course_session();
            }
        },
        eventClick: function (calEvent, jsEvent, view) {
            // Trigger when click the event.
        },
        eventDrop: function (event, delta, revertFunc) {
            // Trigger where move and drop the event on full calendar.
        },
        eventRender: function (event, element, view) {
            var object = '<a class="fc-time-grid-event fc-v-event fc-event fc-start fc-end course-item  fc-draggable fc-resizable" style="top: 65px; bottom: -153px; z-index: 1; left: 0%; right: 0%;">' +

                '<div class="fc-content">' +
                '<div class="container-room">' +
                '<div class="side-course">' +
                '<div class="fc-title conflict" data-toggle="tooltip" data-placement="right" title="Tooltip on top">' + event.title + '</div>' +
                '<p class="text-primary conflict">Mr. YOU Vandy</p> ' +
                '<p class="text-primary conflict">Course</p> ' +
                '</div>' +
                '<div class="side-room">' +
                '</label> ' +
                '</div> ' +
                '<div class="clearfix"></div> ' +
                '</div>' +
                '</div>' +
                '<div class="fc-bgd"></div>' +
                '<div class="fc-resizer fc-end-resizer"></div>' +
                '</a>';

            return $(object);
        },
        eventAfterAllRender: function (view) {

        },
        eventOverlap: function (stillEvent, movingEvent) {
            return stillEvent.allDay && movingEvent.allDay;
        },
        select: function (start, end, jsEvent, view) {
            alert(start);
        }

    });

    $('.room-item').removeAttr('style');
    $('.course-item').removeAttr('style');

    var isEventOverDiv = function (x, y) {

        var courses = $('.courses');
        var offset = courses.offset();
        offset.right = courses.width() + offset.left;
        offset.bottom = courses.height() + offset.top;

        /** Compare*/
        return x >= offset.left
            && y >= offset.top
            && x <= offset.right
            && y <= offset.bottom;
    };

    // Clicking to remove the room from course.
    $(document).on('click', '.remove-room', function (e) {
        $(this).addClass('rf-room');
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

        var suggest_room = '';
        suggest_room += '<label class="label label-danger remove-room">' +
            $(this).text() +
            ' <i class="fa fa-trash"></i> ';
        $('.course-selected').parent().children().eq(1).html(suggest_room);
        $(this).remove();

    });

    // Reload courses.
    $('#timetable').fullCalendar('rerenderEvents');

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
            title: $(this).find('.course-name').text(),
            stick: true,
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
