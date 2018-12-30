/**
 * Created by andy on 2018/11/22.
 */

var uploader = new AliyunUpload.Vod({
    // 文件上传失败
    'onUploadFailed': function (uploadInfo, code, message) {
        log("onUploadFailed: file:" + uploadInfo.file.name + ",code:" + code + ", message:" + message);
        alert("文件上传失败")
    },
    // 文件上传完成
    'onUploadSucceed': function (uploadInfo) {
        log("onUploadSucceed: " + uploadInfo.file.name + ", endpoint:" + uploadInfo.endpoint + ", bucket:" + uploadInfo.bucket + ", object:" + uploadInfo.object);
        // $("#w0").trigger('submit');
        // $("#w0").trigger('submit');
        // alert("上传成功");
        // clearList();
        $("#w0").submit();
        // $("#uploadBtn").prop('disabled', true);
        // $("#submit").prop('disabled',false);
    },
    // 文件上传进度
    'onUploadProgress': function (uploadInfo, totalSize, loadedPercent) {
        log("onUploadProgress:file:" + uploadInfo.file.name + ", fileSize:" + totalSize + ", percent:" + (loadedPercent * 100.00).toFixed(2) + "%");
    },
    // STS临时账号会过期，过期时触发函数
    'onUploadTokenExpired': function (uploadInfo) {
        log("onUploadTokenExpired");
        if (isVodMode()) {
            // 实现时，根据uploadInfo.videoId从新获取UploadAuth
            //实际环境中调用点播的刷新上传凭证接口，获取凭证
            //https://help.aliyun.com/document_detail/55408.html?spm=a2c4g.11186623.6.630.BoYYcY
            //获取上传凭证后，调用setUploadAuthAndAddress
            // uploader.resumeUploadWithAuth(uploadAuth);
        }
    },
    onUploadCanceled: function (uploadInfo) {
        log("onUploadCanceled:file:" + uploadInfo.file.name);
    },
    // 开始上传
    'onUploadstarted': function (uploadInfo) {
        if (isVodMode()) {
            if (!uploadInfo.videoId)//这个文件没有上传异常
            {

                var name = $("#resources-name").val();
                if ($.trim(name) == "") {
                    alert("请设置资源名称");
                    return false;
                }
                $.post("/resources/auth", {"fileName": uploadInfo.file.name, "title": name}, function (res) {
                    if (res.code == 200) {
                        $("#uploadAuth").val(res.data.UploadAuth);

                        $("#resources-third_resource_id").val(res.data.VideoId);
                        // alert( $("#resources-third_resource_id").val());
                        //实际环境中调用调用点播的获取上传凭证接口
                        //https://help.aliyun.com/document_detail/55407.html?spm=a2c4g.11186623.6.629.CH7i3X
                        //获取上传凭证后，调用setUploadAuthAndAddress
                        uploader.setUploadAuthAndAddress(uploadInfo, res.data.UploadAuth, res.data.UploadAddress, res.data.VideoId);
                    } else {
                        alert(res.msg);
                    }

                }, 'json');

            }
            else//如果videoId有值，根据videoId刷新上传凭证
            {
                var name = $("#resources-name").val();
                if ($.trim(name) == "") {
                    alert("请设置资源名称");
                    return false;
                }
                $.post("/resources/auth", {"fileName": uploadInfo.file.name, "title": name}, function (res) {
                    // console.log(res)
                    //mock 上传凭证 实际产品中需要通过接口获取
                    //实际环境中调用点播的刷新上传凭证接口，获取凭证
                    //https://help.aliyun.com/document_detail/55408.html?spm=a2c4g.11186623.6.630.BoYYcY
                    //获取上传凭证后，调用setUploadAuthAndAddress
                    if (res.code == 200) {
                        $("#uploadAuth").val(res.data.UploadAuth);
                        $("#resources-third_resource_id").val(uploadInfo.videoId);
                        uploader.setUploadAuthAndAddress(uploadInfo, res.data.UploadAuth, res.data.UploadAddress);
                    } else {
                        alert(res.msg);
                    }
                }, 'json');

            }
        }
    }
    ,
    'onUploadEnd': function (uploadInfo) {
        log("onUploadEnd: uploaded all the files");
    }
});

var getCheckpoint = function () {
    var list = uploader.listFiles();
    for (var i = 0; i < list.length; i++) {
        var value = uploader.getCheckpoint(list[i].file);
        if (value) {
            log(list[i].file.name + ' checkpoint: videoId=' + value.videoId + ' loaded=' + value.loaded + ' state=' + value.state);
        }
        else {
            log(list[i].file.name + ' no checkpoint.');
        }
    }
};

// 点播上传。每次上传都是独立的鉴权，所以初始化时，不需要设置鉴权
// 临时账号过期时，在onUploadTokenExpired事件中，用resumeWithToken更新临时账号，上传会续传。
var selectFile = function (event) {

    var userData = '{"Vod":{"StorageLocation":"","UserData":{"IsShowWaterMark":"false","Priority":"7"}}}';
    for (var i = 0; i < event.target.files.length; i++) {
        log("add file: " + event.target.files[i].name);
        uploader.addFile(event.target.files[i], null, null, null, userData);
    }
    $("#uploadBtn").prop('disabled', false);
};

document.getElementById("resources-files")
    .addEventListener('change', selectFile);

var textarea = document.getElementById("textarea");

function start() {
    log("start upload.");
    var name = $("#resources-name").val();
    if ($.trim(name) == "") {
        alert("请设置资源名称");
        return false;
    }
    var list = uploader.listFiles();
    if (list.length > 0) {
        uploader.startUpload();
        return true;
    } else {
        $("#w0").submit();
    }

}

function stop() {
    log("stop upload.");
    uploader.stopUpload();
}

function resumeWithToken() {
    log("resume upload with token.");
    var uploadAuth = document.getElementById("uploadAuth").value;
    uploader.resumeUploadWithAuth(uploadAuth);

}

function clearInputFile() {
    var ie = (navigator.appVersion.indexOf("MSIE") != -1);
    if (ie) {
        var file = document.getElementById("resources-files");
        var file2 = file.cloneNode(false);
        file2.addEventListener('change', selectFile);
        file.parentNode.replaceChild(file2, file);
    }
    else {
        document.getElementById("resources-files").value = '';
    }

}

function clearList() {
    log("clean upload list.");
    uploader.cleanList();
}

function getList() {
    log("get upload list.");
    var list = uploader.listFiles();
    for (var i = 0; i < list.length; i++) {
        log("file:" + list[i].file.name + ", status:" + list[i].state + ", endpoint:" + list[i].endpoint + ", bucket:" + list[i].bucket + ", object:" + list[i].object);
    }
}

function deleteFile() {
    if (document.getElementById("deleteIndex").value) {
        var index = document.getElementById("deleteIndex").value;
        log("delete file index:" + index);
        uploader.deleteFile(index);
    }
}

function cancelFile() {
    if (document.getElementById("cancelIndex").value) {
        var index = document.getElementById("cancelIndex").value
        log("cancel file index:" + index);
        uploader.cancelFile(index);
    }
}

function resumeFile() {
    if (document.getElementById("resumeIndex").value) {
        var index = document.getElementById("resumeIndex").value
        log("resume file index:" + index);
        uploader.resumeFile(index);
    }
}

function clearLog() {
    textarea.options.length = 0;
}

function log(value) {
    if (!value) {
        return;
    }

    var len = textarea.options.length;
    if (len > 0 && textarea.options[len - 1].value.substring(0, 40) == value.substring(0, 40)) {
        //textarea.remove(len-1);
    } else if (len > 25) {
        textarea.remove(0);
    }

    var option = document.createElement("option");
    option.value = value, option.innerHTML = value;
    textarea.appendChild(option);
}

function isVodMode() {
    return true;
}