var popup;
$(document).ready(function () {
    $(document).on('click', '#print-timetable', function (event) {
        event.preventDefault();
        var left = ($(window).width() / 2) - (500 / 2),
            top = ($(window).height() / 2) - (400 / 2),
            popup = window.open($(this).attr("href"), "popup", "width=500, height=400, top=" + top + ", left=" + left);
    });

    $(document).on('click', '#export-timetable', function (event) {
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
});