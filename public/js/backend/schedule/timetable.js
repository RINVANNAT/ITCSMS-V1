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
        })
    });

    // Call function dragging courses sessions.
    drag_course_session();

    // Call function dragging rooms.
    drag_room();

    // Timetable sections.
    $('#timetable').fullCalendar({
        defaultView: 'timetable',
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
        events: [],
        editable: true,
        droppable: true,
        dragRevertDuration: 0,
        drag: function () {
          alert('Drag');
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
        },
        eventClick: function (calEvent, jsEvent, view) {
            // Trigger when click the event.
        },
        eventDrop: function (event, delta, revertFunc) {
            // Trigger where move and drop the event on full calendar.
        },
        eventRender: function (event, element, view) {
            console.log(event);
            element.addClass('course');
        },
        eventAfterRender: function( event, element, view ) {
            // Trigger when after render the event.
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

    // Click on course show available room.
    $(document).on('click', '.course-item', function () {
        $('body').find('.course-selected').removeClass('course-selected');
        $(this).addClass('course-selected');
        var room = '';
        room += '<div class="room-item">';
        room += '<i class="fa fa-ellipsis-v"></i> ';
        room += '<i class="fa fa-ellipsis-v"></i> F-404';
        room += '</div>';
        // Apply with $.ajax({ /** implementation.... */ });
        $('.rooms').html(room);
        drag_room();
    })
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