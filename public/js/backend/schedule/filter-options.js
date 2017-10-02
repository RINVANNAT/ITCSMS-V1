/** Get rooms. **/
function get_groups() {
    toggleLoading(true);
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/timetables/get_groups',
        data: $('#options-filter').serialize(),
        success: function (response) {
            if (response.status === true) {
                var group_item = '';
                $.each(response.groups, function (key, val) {
                    group_item += '<option value="' + val.id + '">' + val.name + '</option>';
                });

                $('select[name="group"]').html(group_item);
            }
            else {
                $('select[name="group"]').html('');
            }
        },
        error: function () {
            swal(
                'Oops...',
                'Something went wrong!',
                'error'
            );
        },
        complete: function () {
            get_weeks($('select[name="semester"] :selected').val());
            toggleLoading(false);
        }
    })
}



/** Get options. **/
function get_options(department_id) {
    toggleLoading(true);
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/timetables/get_options',
        data: {department_id: department_id},
        success: function (response) {
            var option = '';
            console.log(response.options);
            $.each(response.options, function (key, val) {
                option += '<option value="' + val.id + '">' + val.code + '</option>';
            });

            $('select[name="option"]').html(option);
        },
        error: function () {
            swal(
                'Get Options',
                'Something went wrong!',
                'error'
            );
        },
        complete: function () {
            get_grades(department_id);
            toggleLoading(false);
        }
    });
}

/** Get grade. */
function get_grades(department_id) {
    $.ajax({
        type: 'POST',
        url: '/admin/schedule/timetables/get_grades',
        data: {department_id: department_id},
        success: function (response) {
            if (response.status === true) {
                var grades = '';
                $.each(response.grades, function (key, val) {
                    grades += '<option value="' + val.id + '">' + val.name_en + '</option>';
                });
                $('select[name="grade"]').html(grades);
            }
        },
        error: function () {
            swal(
                'Get Grades',
                'Something went wrong!',
                'error'
            );
        },
        complete: function () {
            get_groups();
        }
    })
}
