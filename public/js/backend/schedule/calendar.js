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

    // Event click on each object events.
    //$(document).on('click', '.external-event', function () {
    //    // swal({
    //    //     title: 'Submit email to run ajax request',
    //    //     input: 'email',
    //    //     showCancelButton: true,
    //    //     confirmButtonText: 'Submit',
    //    //     showLoaderOnConfirm: true,
    //    //     allowOutsideClick: false
    //    // }).then(function (email) {
    //    //     swal({
    //    //         type: 'success',
    //    //         title: 'Ajax request finished!',
    //    //         html: 'Submitted email: ' + email
    //    //     })
    //    // });
    //});

    // Render full calendar
    calendar();
    renderingEventsOnSideLeft($('#calendar').fullCalendar('getDate').format('YYYY'));
    // Render Event on full calendar
    // getAllEvents();

    // Add more events.
    //$('#form-create-event').on('submit', function (e) {
    //    e.preventDefault();
    //
    //    $.ajax({
    //        type: 'POST',
    //        url: '/admin/schedule/calendars',
    //        data: $(this).serialize(),
    //        success: function (response) {
    //            if (response.status == true) {
    //                swal(
    //                    $('input[name="title"]').val(),
    //                    'You have been successfully to create a new event.',
    //                    'success'
    //                );
    //                $('#form-create-event')[0].reset();
    //                calendar();
		//			$('#modal-add-event').modal('toggle');
    //                renderingEventsOnSideLeft($('#calendar').fullCalendar('getDate')._i[0]);
    //            }
    //
    //        },
    //        error: function (data) {
    //            var errors = "";
    //            $.each(data.responseJSON, function (key, val) {
    //                errors += val + '</br>';
    //            });
    //            swal(
    //                'Oops... ' + status,
    //                errors,
    //                'error'
    //            )
    //        }
    //    });
    //
    //
    //});

	// prev event on full calendar

    $('.fc-prev-button.fc-button.fc-state-default.fc-corner-left').click(function(){
		//var moment = $('#calendar').fullCalendar('getDate');
		//sweetAlert(moment.format('Y'));
        //console.log($('#calendar').fullCalendar('getDate').format('YYYY'));
        renderingEventsOnSideLeft($('#calendar').fullCalendar('getDate').format('YYYY'));
    });

    // next event on full calendar

    $('.fc-next-button.fc-button.fc-state-default.fc-corner-right').click(function(){
		//var moment = $('#calendar').fullCalendar('getDate');
		//sweetAlert(moment.format('Y'));
        //$("#calendar").fullCalendar( 'refresh' );
        //console.log($('#calendar').fullCalendar('getDate').format('YYYY'));
        renderingEventsOnSideLeft($('#calendar').fullCalendar('getDate').format('YYYY'));
    });

    // today event click.
    $('.fc-today-button.fc-button.fc-state-default.fc-corner-left.fc-corner-right').click(function(){
        //$("#calendar").fullCalendar( 'refresh' );
        //console.log($('#calendar').fullCalendar('getDate')+"right");
        renderingEventsOnSideLeft($('#calendar').fullCalendar('getDate').format('YYYY'));
    });
});

function calendar() {
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
        events: '/admin/schedule/calendars/events/render',
        columnFormat: 'dddd',
        drop: function (date) { // this function is called when something is dropped
            var originalEventObject = $(this).data('eventObject');
            console.log(originalEventObject);

            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);

            // assign it the date that was reported
            var tempDate = new Date(date);  //clone date
            copiedEventObject.start = tempDate;
            copiedEventObject.end = new Date(tempDate.setHours(tempDate.getHours() + 2));
            copiedEventObject.allDay = true;

            addEvent(copiedEventObject);
            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
            renderingEventsOnSideLeft($('#calendar').fullCalendar('getDate').format('YYYY'));

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
            updateEvent(event);
        },
        eventClick: function (event) {
            removeEvent(event);
        },
        eventMouseover: function(calEvent, jsEvent) {
            var tooltip = '<div class="tooltipevent">' + calEvent.title + '</div>';
            var $tooltip = $(tooltip).appendTo('body');

            $(this).mouseover(function(e) {
                $(this).css('z-index', 10000);
                $tooltip.fadeIn('500');
                $tooltip.fadeTo('10', 1.9);
            }).mousemove(function(e) {
                $tooltip.css('top', e.pageY + 10);
                $tooltip.css('left', e.pageX + 20);
            });
        },
        eventMouseout: function(calEvent, jsEvent) {
            $(this).css('z-index', 8);
            $('.tooltipevent').remove();
        }
    });
}
var renderingEventsOnSideLeft = function(year)
{
    $.ajax({
       type: 'GET',
        url: '/admin/schedule/calendars/event/'+year,
        success:function (response) {
			if (response.status == true) {
				var event = '';
				$.each(response.events, function (key, val) {
					if (val.category_event_id == 1) {
						event += '<div class="external-event bg-aqua ui-draggable ui-draggable-handle" data-bg="bg-aqua" event-id="' + val.id + '">' + val.title + '</div>';
					}
					else if (val.category_event_id == 2) {
						event += '<div class="external-event bg-green ui-draggable ui-draggable-handle" data-bg="bg-green" event-id="' + val.id + '">' + val.title + '</div>';
					}
					else {
						event += '<div class="external-event bg-red ui-draggable ui-draggable-handle" data-bg="bg-red" event-id="' + val.id + '">' + val.title + '</div>';
					}
				});

				$('#external-events').html(event);
				calendar();
			}
        },
        error:function (response) {
            sweetAlert(response.errors);
        }
    });
};

var addEvent = function (event) {
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/calendars/event/drag',
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
                newEvent.id = response.event.id;
                newEvent.allDay = true;
                newEvent.start = response.event.start;
                newEvent.end = response.event.end;
                $("#calendar").fullCalendar( 'refresh' );
                $('#calendar').fullCalendar('renderEvent', newEvent, true);
                toastr["success"]("You have been added the event successfully.");
                renderingEventsOnSideLeft($('#calendar').fullCalendar('getDate').format('YYYY'));
            } else {
                toastr["error"]("The event is already added in this year!");
            }
        }
    });
};
//
//var updateEvent = function (event) {
//    $.ajax({
//        type: 'POST',
//        url: '/admin/schedule/calendars/event/move',
//        data: {
//            id: event.id,
//            start: event.start.format(),
//            end: event.start.format()
//        },
//        success: function (response) {
//            if (response.status == true) {
//                toastr["info"]("You have been updated successfully.", "Information");
//            } else {
//                toastr["error"]("Something went wrong !");
//            }
//        }
//    });
//};
//
//var removeEvent = function (event) {
//    swal({
//        title: 'Are you sure?',
//        text: "You won't be able to revert this!",
//        type: 'warning',
//        showCancelButton: true,
//        confirmButtonColor: '#3085d6',
//        cancelButtonColor: '#d33',
//        confirmButtonText: 'Yes, delete it!'
//    }).then(function () {
//        $.ajax({
//            type: 'POST',
//            url: '/admin/schedule/calendars/event/delete',
//            data: {
//                id: event.id
//            },
//            success: function (response) {
//                if (response.status == true) {
//                    $('#calendar').fullCalendar('removeEvents', response.id);
//                    renderingEventsOnSideLeft($('#calendar').fullCalendar('getDate')._i[0]);
//                }
//            }
//        });
//        swal(
//            'Deleted!',
//            'Your file has been deleted.',
//            'success'
//        )
//    });
//};
//
//var resizeEvent = function (id, start, end) {
//    $.ajax({
//        type: 'POST',
//        url: '/admin/schedule/calendars/event/resize',
//        data: {
//            id: id,
//            start: start,
//            end: end
//        },
//        success: function (response) {
//            if (response.status == true) {
//                toastr["success"]("You have been added the event successfully.", "Have been updated");
//                calendar();
//            } else {
//                toastr["error"]("Error !", "Something went wrong.");
//            }
//        }
//    })
//};
