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

    // Dragging courses sessions.
    drag_course_session();

    // Dragging rooms.
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
        eventDragStart: function (event, jsEvent, ui, view) {
            var room = '';
            room += '<div class="room-item ui-draggable ui-draggable-handle">';
            room += '<i class="fa fa-refresh"></i> Loading...';
            room += '</div>';
            $('.rooms').html(room);
        },
        eventDragStop: function (event, jsEvent, ui, view) {
            // Implementation event back.
        },
        eventClick: function (calEvent, jsEvent, view) {
            $('body').find('.course-selected').removeClass('course-selected');
            $(this).addClass('course-selected');
            var room = '';
            room += '<div class="room-item">';
            room += '<i class="fa fa-ellipsis-v"></i> ';
            room += '<i class="fa fa-ellipsis-v"></i> F-404';
            room += '</div>';
            $('.rooms').html(room);
            drag_room();
        },
        eventDrop: function (event, delta, revertFunc) {
            // Implementations.
        },
        eventRender: function (event, element, view) {
            element.addClass('course');
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
    }
});

var drag_room = function () {
    $('.rooms .room-item').each(function () {

        $(this).data('event', {
            title: $.trim($(this).text()),
            stick: true
        });

        $(this).draggable({
            zIndex: 999,
            revert: true,
            revertDuration: 0
        });

    });
};

var drag_course_session = function () {
    $('.courses .course-item').each(function () {

        $(this).data('event', {
            title: $.trim($(this).text()),
            stick: true
        });

        $(this).draggable({
            zIndex: 999,
            revert: true,
            revertDuration: 0
        });

    });
};

var rooms = function (nb_rooms) {
    var room = '';
    room += '<div class="room-item">'
         +'<i class="fa fa-ellipsis-v"></i>'
         +'<i class="fa fa-ellipsis-v"></i> F-306</div>';
    for(var i=0; i<nb_rooms; i++)
    {
        room += room;
    }
    $('.rooms').append(room);
};