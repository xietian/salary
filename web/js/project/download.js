/**
 * Created by admin on 2017/11/5.
 */
$(function () {
    $("#export").click(function (event) {
        event.preventDefault();
        var url = $(this).attr('href');
        var params = {};
        $('.filters .form-control').each(function () {
            params[$(this).attr('name')] = $(this).val();
        });
        var paramsStr = $.param(params);
        window.open(url + "?" + paramsStr)
    });
});