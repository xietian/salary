/**
 * Created by andy on 2018/12/29.
 */
$(function () {
    $(".in").on("change", function () {
        var item_id = $(this).attr("item_id");
        var user_id = $(this).attr("user_id");
        var val = $(this).val();
        var date = $("#check_issue_date").val();
        var data = {
            'item_id': item_id,
            'user_id': user_id,
            'val': val,
            'date': date,
        };
        $.ajax({
            type: "post",
            url: "/salary/set",
            data: data,
            dataType: "json",
            success: function (data) {
                if (data.code != 200) {
                    alert(data.msg)
                }
            }
        });
    });

    $(".in_base").on("change", function () {
        var item_id = $(this).attr("item_id");
        var user_id = $(this).attr("user_id");
        var val = $(this).val();
        var data = {
            'item_id': item_id,
            'user_id': user_id,
            'val': val,
        };
        $.ajax({
            type: "post",
            url: "/salary/bset",
            data: data,
            dataType: "json",
            success: function (data) {
                if (data.code != 200) {
                    alert(data.msg)
                }
            }
        });
    });

});