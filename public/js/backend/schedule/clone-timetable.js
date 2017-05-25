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

            var groups = '';

            $.each(response.groups, function (key, val) {
                groups += '<div class="col-md-2">'
                    + '<label for="' + val.id + '">'
                    + '<input type="checkbox"'
                    + 'data-target="groups"'
                    + 'name="groups[]"'
                    + 'value="' + val.id + '"'
                    + 'class="square groups_value"> ' + val.code
                    + '</label>'
                    + '</div>';
            });

            $('.render_weeks').html(weeks);
            $('.render_groups').html(groups);

            $('input[type="checkbox"].square').iCheck({
                checkboxClass: 'icheckbox_square-blue'
            });
            $('#all-weeks').on('ifToggled', function () {
                $('input[data-target="weeks"]:checkbox').iCheck('toggle');
            });

            // Checked or Unchecked groups.
            $('#all-groups').on('ifToggled', function () {
                $('input[data-target="groups"]:checkbox').iCheck('toggle');
            });
        },
        error: function () {
            swal(
                'Form submission is failed',
                'Hello World!',
                'error'
            );
        },
        complete: function () {

        }
    })
}
$(document).ready(function () {
    /** Clone timetable **/
    /** iCheckbox */
    $('input[type="checkbox"].square').iCheck({
        checkboxClass: 'icheckbox_square-blue'
    });

    // Checked or Unchecked weeks.
    $('#all-weeks').on('ifToggled', function () {
        $('input[data-target="weeks"]:checkbox').iCheck('toggle');
    });

    // Checked or Unchecked groups.
    $('#all-groups').on('ifToggled', function () {
        $('input[data-target="groups"]:checkbox').iCheck('toggle');
    });

    $(document).on('click', '.btn_clone_timetable', function () {
        clone_timetable_form();
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
            error: function (response) {
                var message = '';
                $.each(response.responseJSON, function (key, val) {
                    message += val + '<br/>';
                });

                swal(
                    'Form submission is failed',
                    message,
                    'error'
                );
            }
        })
    });

    // click clone timetable

    $(document).on('click', '.button_clone_timetable', function (event) {
        event.preventDefault();
        var i = 0;
        var weeks = [];
        $('.weeks_value:checked').each(function () {
            weeks[i++] = $(this).val();
        });
        i = 0;

        var groups = [];
        $('.groups_value:checked').each(function () {
            groups[i++] = $(this).val();
        });
        i = 0;

        $.ajax({
            type: 'POST',
            url: '/admin/schedule/timetables/clone/clone_timetable',
            data: {
                weeks: weeks,
                groups: groups,
                academic_year_id: $('select[name="academicYear"] :selected').val(),
                department_id: $('select[name="department"] :selected').val(),
                degree_id: $('select[name="degree"] :selected').val(),
                option_id: $('select[name="option"] :selected').val(),
                grade_id: $('select[name="grade"] :selected').val(),
                semester_id: $('select[name="semester"] :selected').val(),
                group_id: $('select[name="group"] :selected').val(),
                week_id: $('select[name="weekly"] :selected').val()
            },
            success: function (response) {
                //console.log(response);
            },
            complete: function () {
                //toggleLoading(false);
            }
        })
    })
});