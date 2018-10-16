// preparing checkbox weeks and groups.
function checkBoxComponents() {
    $('input[type="checkbox"].square').iCheck({
        checkboxClass: 'icheckbox_square-blue'
    });

    // Checked or Unchecked weeks.
    $('#all-weeks').iCheck('uncheck');
    $('#all-groups').iCheck('uncheck');

    $('#all-weeks').on('ifToggled', function () {
        $('input[data-target="weeks"]:checkbox').iCheck('toggle');
    });

    // Checked or Unchecked groups.
    $('#all-groups').on('ifToggled', function () {
        $('input[data-target="groups"]:checkbox').iCheck('toggle');
    });
}

// rendering form for clone timetable.
function clone_timetable_form() {
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/timetables/clone_timetable_form',
        data: $('#options-filter').serialize(),
        success: function (response) {
            var weeks = '';
            $.each(response.weeks, function (key, val) {
                weeks += '<div class="col-md-3">'
                    + '<label for="' + val.id + '">'
                    + '<input type="checkbox"'
                    + 'data-target="weeks"'
                    + 'name="weeks[]"'
                    + 'value="' + val.id + '"'
                    + 'class="square weeks_value">'
                    + ' Week ' + val.id
                    + '</label>'
                    + '</div>';
            });

            // var groups = '';
            //
            // $.each(response.groups, function (key, val) {
            //     groups += '<div class="col-md-2">'
            //         + '<label for="' + val.id + '">'
            //         + '<input type="checkbox"'
            //         + 'data-target="groups"'
            //         + 'name="groups[]"'
            //         + 'value="' + val.id + '"'
            //         + 'class="square groups_value"> ' + val.code
            //         + '</label>'
            //         + '</div>';
            // });

            $('.render_weeks').html(weeks);
            // $('.render_groups').html(groups);

            $('#academic_year_id').val($('select[name="academicYear"] :selected').val());
            $('#department_id').val($('select[name="department"] :selected').val());
            $('#degree_id').val($('select[name="degree"] :selected').val());
            $('#option_id').val($('select[name="option"] :selected').val());
            $('#grade_id').val($('select[name="grade"] :selected').val());
            $('#semester_id').val($('select[name="semester"] :selected').val());
            $('#group_id').val($('select[name="group"] :selected').val());
            $('#week_id').val($('select[name="weekly"] :selected').val());
            checkBoxComponents();
        }
    })
}
function checkBoxComponentsReset() {
    $('input[type="checkbox"].square').iCheck({
        checkboxClass: 'icheckbox_square-blue'
    });

    // Checked or Unchecked weeks.
    $('.all-weeks').iCheck('uncheck');

    $('.all-weeks').on('ifToggled', function () {
        $('input[data-target="weeks"]:checkbox').iCheck('toggle');
    });
}

function reset_timetable_form() {
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/timetables/clone_timetable_form',
        data: $('#options-filter').serialize(),
        success: function (response) {
            var weeks = '';
            $.each(response.weeks, function (key, val) {
                weeks += '<div class="col-md-3">'
                    + '<label for="' + val.id + '">'
                    + '<input type="checkbox"'
                    + 'data-target="weeks"'
                    + 'name="weeks[]"'
                    + 'value="' + val.id + '"'
                    + 'class="square weeks_value">'
                    + ' Week ' + val.id
                    + '</label>'
                    + '</div>';
            });

            $('.render_weeks').html(weeks);

            $('#academic_year_id').val($('select[name="academicYear"] :selected').val());
            $('#department_id').val($('select[name="department"] :selected').val());
            $('#degree_id').val($('select[name="degree"] :selected').val());
            $('#option_id').val($('select[name="option"] :selected').val());
            $('#grade_id').val($('select[name="grade"] :selected').val());
            $('#semester_id').val($('select[name="semester"] :selected').val());
            $('#group_id').val($('select[name="group"] :selected').val());
            $('#week_id').val($('select[name="weekly"] :selected').val());
            checkBoxComponentsReset();
        }
    })
}


// document ready
$(function () {
    // show checkbox groups and weeks
    setTimeout(function () {
        $('body').find('.fc-axis.fc-widget-header').html('<i class="fa fa-calendar"></i>');
    }, 1000);
    checkBoxComponents();

    // click btn clone timetable to show form.
    $(document).on('click', '.btn_clone_timetable', function () {
        clone_timetable_form();
    });

    // click btn reset timetable to show form.
    $(document).on('click', '.btn-reset-timetable', function () {
        reset_timetable_form();
    });

    // click clone timetable
    $(document).on('click', '.button_clone_timetable', function (event) {
        $('#clone-timetable').modal('show');
        event.preventDefault();
        swal({
            title: 'Are you sure?',
            text: "Timetable slots will remove automatic.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Clone'
        }).then(function () {
            $('#clone-timetable').modal('hide');
            toggleLoading(true);
            $.ajax({
                type: 'POST',
                url: '/admin/schedule/timetables/clone/clone_timetable',
                data: $('#form-clone-timetable').serialize(),
                success: function (response) {
                    if (response.status === true) {
                        notify('info', 'Cloning timetable successfully', 'Clone Timetable');
                        get_course_programs();
                    } else {
                        swal(
                            'Oops...',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function () {
                    $('#clone-timetable').modal('hide');
                    notify('error', 'Something went wrong.', 'Clone timetable');
                },
                complete: function () {
                    toggleLoading(false);
                    get_timetable_slots();
                    get_course_programs();
                }
            });
        });
    });
});