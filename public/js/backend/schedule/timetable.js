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
    $('.courses .course-item').each(function () {

        // store data so the calendar knows to render an event upon drop
        $(this).data('event', {
            title: $.trim($(this).text()), // use the element's text as the event title
            stick: true // maintain when user navigates (see docs on the renderEvent method)
        });

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true,      // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
        });

    });

    // Dragging rooms.
    $('.rooms .room-item').each(function () {

        // store data so the calendar knows to render an event upon drop
        $(this).data('event', {
            title: $.trim($(this).text()), // use the element's text as the event title
            stick: true // maintain when user navigates (see docs on the renderEvent method)
        });

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true,      // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
        });

    });

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
        events: [
            {
                title: 'Cloud Computing',
                start: '2017-04-24 07:00:00',
                description: 'Course: 4H, Room: F-404, Group: A',
                end: '2017-03-17 09:00:00',
                backgroundColor: '#00a65a',
                borderColor: 'white',
                textColor: 'white'
            }
        ],
        editable: true,
        droppable: true,
        dragRevertDuration: 0,
        eventDragStart: function (event, jsEvent, ui, view) {
            var room = '';
            room += '<div class="room-item ui-draggable ui-draggable-handle">';
            room += '<i class="fa fa-ellipsis-v"></i> ';
            room += '<i class="fa fa-ellipsis-v"></i> Suggest Rooms Here';
            room += '</div>';
            $('.rooms').html(room);
        },
        eventDragStop: function (event, jsEvent, ui, view) {
            if (isEventOverDiv(jsEvent.clientX, jsEvent.clientY)) {
                var courses = '';
                courses += '<li class="course-item">'
                    +'<span class="handle ui-sortable-handle">'
                    +'<i class="fa fa-ellipsis-v"></i>'
                    +'<i class="fa fa-ellipsis-v"></i>'
                    +'</span>'
                    +'<span class="text">'+event.title+'</span><br>'
                    // +'<span style="margin-left: 28px;">Mr. YOU Vanndy</span><br/>'
                    // +'<span style="margin-left: 28px;">Course = 8H</span>'
                    +'</li>';

                $('#calendar').fullCalendar('removeEvents', event._id);
                var el = $('.courses').append(courses);
                el.draggable({
                    zIndex: 999,
                    revert: true,
                    revertDuration: 0
                });
                el.data('event', {title: event.title, id: event.id, stick: true});
            }
        },
        eventClick: function (calEvent, jsEvent, view) {
            $(this).css('backgroundColor', '#00c0ef').css('borderColor', '#fff');
            var room = '';
            room += '<div class="room-item ui-draggable ui-draggable-handle">';
            room += '<i class="fa fa-ellipsis-v"></i> ';
            room += '<i class="fa fa-ellipsis-v"></i> Suggest Rooms Here';
            room += '</div>';
            $('.rooms').html(room);
        },
        eventDrop: function(event, delta, revertFunc) {

            var room = '';
            room += '<div class="room-item ui-draggable ui-draggable-handle">';
            room += '<i class="fa fa-ellipsis-v"></i> ';
            room += '<i class="fa fa-ellipsis-v"></i> Suggest Rooms Here';
            room += '</div>';
            $('.rooms').html(room);

        },
        eventRender: function( event, element, view ) {
            //event.className('hello');
            console.log(event);
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

    /** Clone timetable **/
    /** iCheckbox */
    $('input[type="checkbox"].square').iCheck({
        checkboxClass: 'icheckbox_square-blue'
    });

    /** Form submit clone */
    $('#form-clone-timetable').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/admin/schedule/timetables/clone',
            data: $('#form-clone-timetable').serialize(),
            success: function (response) {
                console.log(response);
                $('#clone-timetable').modal('toggle');
                $('#form-clone-timetable')[0].reset();
                // Reset selected checkbox.
                $('input[type="checkbox"].square').iCheck('update');
                swal(
                    'Success',
                    'You have been cloned timetable.',
                    'success'
                );
            },
            error: function () {
                swal(
                    'Oops...',
                    'Something went wrong!',
                    'error'
                );
            }
        })
    })
});