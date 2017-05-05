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
});