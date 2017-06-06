$(document).ready(function () {
    $(document).on('click', '#print-timetable', function (event) {
        event.preventDefault();
        var left = ($(window).width() / 2) - (500 / 2),
            top = ($(window).height() / 2) - (400 / 2),
            popup = window.open($(this).attr("href"), "popup", "width=500, height=400, top=" + top + ", left=" + left);
    });

    // clone window
    $(document).on('click', '#clone-window-print', function (e) {
        e.preventDefault();
        window.close();
    });

    $(document).on('click', '#print', function (event) {
        event.preventDefault();
        // console.log($('#form_print_timetable').serializeArray());
        $.ajax({
            type: 'GET',
            url: '/admin/schedule/timetables/template-print',
            data: $('#form_print_timetable').serializeArray(),
            success:function () {
                
            },
            error: function () {
                
            },
            complete: function () {
                
            }
        });
        window.close();
        var left = ($(window).width() / 2) - (980 / 2),
            top = ($(window).height() / 2) - (600 / 2),
            popupWindow = window.open($(this).attr("href"), "popupWindow", "width=980, height=600, top=" + top + ", left=" + left);
    });
});