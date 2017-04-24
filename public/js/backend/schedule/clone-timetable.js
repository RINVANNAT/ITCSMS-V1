$(document).ready(function () {
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
    });
});