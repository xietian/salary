$(function () {
    var w = $('#warn');
    $("#channel_name").autocomplete({
        source: function (request, response) {
            var channel_type_id = $("#channel_type_id").val();
            var province_code = $("#province_code").val();
            var city_code = $("#city_code").val();
            $.ajax({
                url: "/wx/register/searchstorebyname",
                dataType: "json",
                data: {
                    city_code: city_code,
                    province_code: province_code,
                    channel_type_id: channel_type_id,
                    store_name: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            label: item.name,
                            id: item.id
                        }
                    }));
                }
            });
        },
        minLength: 1,
        select: function (event, ui) {
            // $("#channel_name").val(ui.item.label);
            $("#channel_id").val(ui.item.id);
        }
    });
    $(".submit_btn").click(function (evt) {
        var p = $('#province_code option:selected').val();
        var c = $('#city_code option:selected').val();
        var ch = $('#channel_name').val();
        var i = $.trim($('#invite_code').val());
        var n = $.trim($('#name').val());
        var t = $.trim($('#tel').val());
        var v = $.trim($('#verify_code').val());
        if(p==''){
            w.html('请选择所在省份');
            return false;
        }
        if(c==''){
            w.html('请选择所在城市');
            return false;
        }
        if(ch==''){
            w.html('请输入药店名称');
            return false;
        }
        if(i==''){
            w.html('请输入邀请码');
            return false;
        }
        if(n==''){
            w.html('请输入姓名');
            return false;
        }
        if(t==''){
            w.html('请输入手机号');
            return false;
        }
        if(!testIphone(t)){
            w.html('请输入正确的手机号');
            return false;
        }
        if(v==''){
            w.html('请获取邀请码');
            return false;
        }
        var loadi2=layer.load(1);
        evt.preventDefault();
        w.html('');
        $.ajax({
            url: "/wx/register/store",
            type: "POST",
            dataType: "json",
            data: $("form").serialize(),//channel_id:$("#channel_id").val(),name:$("#name").val(),tel:+$("#tel").val(),verify_code:$("#verify_code").val()
            success: function (res) {
                layer.close(loadi2);
                if (res.code == 0) {
                    alert('注册成功');
                    window.location = "/wx/users/tips"
                } else {
                    // alert(res.msg);
                    layer.msg('<div style="text-align:cente;">'+res.msg+'</div>', {time:1000});
                    return false;
                }
            }
        });
    });
    w.html('');
});
