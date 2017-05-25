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
        var year = $('#calendar').fullCalendar('getDate').format('YYYY');
        $.ajax({
            type: 'GET',
            url: '/admin/schedule/events/repeat/' + year,
            success: function () {
                //sweetAlert('Hello World !');
            }
        });
        renderingEventsOnSideLeft(year);
    });

    // [NEXT EVENT _CLICK] on full calendar

    $('.fc-next-button.fc-button.fc-state-default.fc-corner-right').click(function () {
        var year = $('#calendar').fullCalendar('getDate').format('YYYY');
        $.ajax({
            type: 'GET',
            url: '/admin/schedule/events/repeat/' + year,
            success: function () {
                //sweetAlert('Hello World !');
            }
        });
        renderingEventsOnSideLeft(year);
    });

    // [TODAY _CLICK]
    $('.fc-today-button.fc-button.fc-state-default.fc-corner-left.fc-corner-right').click(function () {
        renderingEventsOnSideLeft($('#calendar').fullCalendar('getDate').format('YYYY'));
    });


    // [TYPE_EVENT]
    $('input[name="dailyYear"]').click(function () {
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

var renderingEventsOnSideLeft = function (year) {
    $.ajax({
        type: 'GET',
        url: '/admin/schedule/find_events_by_year/' + year,
        success: function (response) {
            if (response.status == true) {
                var event = '';
                $.each(response.events, function (key, val) {
                    if (val.public == true) {
                        event += '<div class="external-event bg-red ui-draggable ui-draggable-handle" data-bg="bg-red" event-id="' + val.id + '">' + val.title + '</div>';
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

                $('#calendar').fullCalendar('renderEvent', event, true);
                $("#calendar").fullCalendar('refresh');

                toastr["success"]("The event is added.", "Successfully");
                renderingEventsOnSideLeft($('#calendar').fullCalendar('getDate').format('YYYY'));
                calendar();
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