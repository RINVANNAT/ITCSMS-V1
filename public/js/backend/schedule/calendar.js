$(document).ready(function () {
    // Setup toastr plugin
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

    // Render full calendar
    calendar();
    renderingEventsOnSideLeft($('#calendar').fullCalendar('getDate').format('YYYY'));


    // Add more events.
    $('#form-create-event').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: '/admin/schedule/calendars/events/store',
            data: $(this).serialize(),
            success: function (response) {
                if (response.status == true) {
                    swal(
                        $('input[name="title"]').val(),
                        'The event was created successfully.',
                        'success'
                    );
                    $('#form-create-event')[0].reset();
                    calendar();
                    $('#modal-add-event').modal('toggle');
                    renderingEventsOnSideLeft($('#calendar').fullCalendar('getDate').format('YYYY'));
                }

            },
            error: function (data) {
                var errors = "";
                $.each(data.responseJSON, function (key, val) {
                    errors += val + '</br>';
                });
                swal(
                    'Oops... ' + status,
                    errors,
                    'error'
                )
            }
        });

    });

    // prev event on full calendar

    $('.fc-prev-button.fc-button.fc-state-default.fc-corner-left').click(function () {
        renderingEventsOnSideLeft($('#calendar').fullCalendar('getDate').format('YYYY'));
    });

    // [NEXT EVENT _CLICK] on full calendar

    $('.fc-next-button.fc-button.fc-state-default.fc-corner-right').click(function () {
        renderingEventsOnSideLeft($('#calendar').fullCalendar('getDate').format('YYYY'));
    });

    // [TODAY _CLICK]
    $('.fc-today-button.fc-button.fc-state-default.fc-corner-left.fc-corner-right').click(function () {
        renderingEventsOnSideLeft($('#calendar').fullCalendar('getDate').format('YYYY'));
    });


    // [TYPE_EVENT]
    $('input[name="fix"]').click(function () {
        var startEndDateInput = '<div class="form-group extra-input">' +
            '<label for="start" class="control-label col-md-2">Start Date</label>' +
            '<div class="col-md-10">' +
            '<div class="input-group">' +
            '<input type="datetime" class="form-control" name="start" id="start"/>' +
            '<span class="input-group-addon">' +
            '<span class="glyphicon glyphicon-calendar"></span>' +
            '</span>' +
            '</div>' +
            '</div>' +
            '</div>' +

            '<div class="form-group extra-input">' +
            '<label for="end" class="control-label col-md-2">End Date</label>' +
            '<div class="col-md-10">' +
            '<div class="input-group">' +
            '<input type="datetime" class="form-control" name="end" id="end"/>' +
            '<span class="input-group-addon">' +
            '<span class="glyphicon glyphicon-calendar"></span>' +
            '</span>' +
            '</div>' +
            '</div>' +
            '</div>';

        if ($(this).prop("checked") == true) {
            $('#form-create-event').children().eq(0).append(startEndDateInput);
            $('#start').datetimepicker({format: 'YYYY-MM-DD'});
            $('#end').datetimepicker({format: 'YYYY-MM-DD'});
        }
        else if ($(this).prop("checked") == false) {
            $('#form-create-event').find('.extra-input').remove();
        }
    });


    /**
     * Select Event Type.
     * @author mab
     */
    if ($('#public').val() == "true") {
        $('#departments').remove();
    }
    else {
        selectInputDepartment();
    }

    $(document).on('change', '#public', function () {
        if ($('#public').val() == "true") {
            $('#departments').remove();
        }
        else {
            selectInputDepartment();
        }
    });
});

var calendar = function () {
    /* initialize the external events
     -----------------------------------------------------------------*/
    function ini_events(ele) {
        ele.each(function () {

            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
                title: $.trim($(this).text()), // use the element's text as the event title
                id: $(this).attr('event-id'),
                className: $(this).attr('data-bg')
            };

            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject);

            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 1070,
                revert: true, // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });

        });
    }

    ini_events($('#external-events div.external-event'));

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date();
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        buttonText: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            day: 'Day'
        },
        //Random default events
        events: '/admin/schedule/events',
        columnFormat: 'dddd',
        drop: function (date) { // this function is called when something is dropped
            var originalEventObject = $(this).data('eventObject');

            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);

            // assign it the date that was reported
            var tempDate = new Date(date);  //clone date
            copiedEventObject.start = tempDate;
            copiedEventObject.end = new Date(tempDate.setHours(tempDate.getHours() + 2));
            copiedEventObject.allDay = true;

            addEvent(copiedEventObject);
            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
            // renderingEventsOnSideLeft($('#calendar').fullCalendar('getDate').format('YYYY'));

        },
        editable: true,
        droppable: true, // this allows things to be dropped onto the calendar !!!
        eventResize: function (event, delta, revertFunc) {
            // Get current end date.
            var end = event.end.format();
            // Call resize event function.
            resizeEvent(event.id, event.start.format(), end);

        },
        eventDrop: function (event, delta, revertFunc) {
            moveEvent(event);
        },
        eventClick: function (event) {
            removeEvent(event);
        },
        eventMouseover: function (calEvent, jsEvent) {
            var tooltip = '<div class="tooltipevent">' + calEvent.title + '</div>';
            var $tooltip = $(tooltip).appendTo('body');

            $(this).mouseover(function (e) {
                $(this).css('z-index', 10000);
                $tooltip.fadeIn('500');
                $tooltip.fadeTo('10', 1.9);
            }).mousemove(function (e) {
                $tooltip.css('top', e.pageY + 10);
                $tooltip.css('left', e.pageX + 20);
            });
        },
        eventMouseout: function (calEvent, jsEvent) {
            $(this).css('z-index', 8);
            $('.tooltipevent').remove();
        },
        eventRender: function (event, element) {
            if (event.public == true) {
                element.addClass('bg-red');
            }
            else {
                element.addClass('bg-green');
            }
        }
    });
};

var renderingEventsOnSideLeft = function (year) {
    $.ajax({
        type: 'GET',
        url: '/admin/schedule/find_events_by_year/' + year,
        success: function (response) {
            if (response.status == true) {
                var event = '';
                $.each(response.events, function (key, val) {
                    if (val.public == true) {
                        event += '<div class="external-event bg-red ui-draggable ui-draggable-handle" data-bg="bg-aqua" event-id="' + val.id + '">' + val.title + '</div>';
                    }
                    else {
                        event += '<div class="external-event bg-green ui-draggable ui-draggable-handle" data-bg="bg-green" event-id="' + val.id + '">' + val.title + '</div>';
                    }
                });

                $('#external-events').html(event);
                calendar();
            }
        },
        error: function (response) {
            sweetAlert(response.errors);
        }
    });
};

var addEvent = function (event) {
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/calendars/fullcalendar/drag',
        contentType: "application/json",
        dataType: "json",
        cache: false,
        data: JSON.stringify({
            event_id: event.id,
            title: event.title,
            start: event.start,
            end: event.end
        }),
        success: function (response) {
            if (response.status == true) {
                var newEvent = new Object();

                newEvent.title = response.title;
                newEvent.id = response.id;
                newEvent.allDay = true;
                newEvent.start = response.start;
                newEvent.end = response.end;
                newEvent.className = 'bg-green';
                if (response.public == true) {
                    newEvent.className = 'bg-red';
                }
                $('#calendar').fullCalendar('renderEvent', newEvent, false);
                $("#calendar").fullCalendar('refresh');
                calendar();
                toastr["success"]("The event is added.", "Successfully");
                renderingEventsOnSideLeft($('#calendar').fullCalendar('getDate').format('YYYY'));
            } else {
                toastr["info"]("The event is already added.", "Already existed!");
            }
        },
        error: function () {
            toastr["error"]("The event does not add.", "error");
        }
    });
};

var moveEvent = function (event) {
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/calendars/fullcalendar/move',
        data: {
            id: event.id,
            start: event.start.format(),
            end: event.start.format()
        },
        success: function (response) {
            if (response.status == true) {
                toastr["info"]("The event already is moved.", "Successfully");
            } else {
                toastr["error"]("The event does not move.", "Error");
            }
        }
    });
};

var removeEvent = function (event) {
    swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then(function () {
        $.ajax({
            type: 'POST',
            url: '/admin/schedule/calendars/fullcalendar/delete',
            data: {
                id: event.id
            },
            success: function (response) {
                console.log(response)
                if (response.status == true) {
                    $('#calendar').fullCalendar('removeEvents', event.id);
                    renderingEventsOnSideLeft($('#calendar').fullCalendar('getDate').format('YYYY'));
                    swal(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    );
                    calendar();
                }
            },
            error: function (error, jqXHR, exception) {
                sweetAlert("Oops...", "Something went wrong!", "error");
            }
        });

    });
};

var resizeEvent = function (id, start, end) {
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/calendars/fullcalendar/resize',
        data: {
            id: id,
            start: start,
            end: end
        },
        success: function (response) {
            if (response.status == true) {
                toastr["info"]("The event is already updated.", "Successfully");
                calendar();
            } else {
                toastr["warning"]("The event does not resize.", "Warning");
            }
        }
    })
};

var selectInputDepartment = function () {
    $.ajax({
        type: 'GET',
        url: '/admin/schedule/departments',
        success: function (response) {
            var departments = '<div class="form-group" id="departments"><label class="control-label col-md-2">Department</label><div class="col-md-10">';
            $.each(response.data, function (key, val) {
                departments += '<input type="checkbox" value="' + val.id + '" name="departments[]"> ' + val.code + ' ';
            });
            departments += '</div></div>';
            $('#public').parent().parent().after(departments);
        },
        error: function () {
            swal(
                'Oops...',
                'Something went wrong while get all departments!',
                'error'
            );
        }
    });
};