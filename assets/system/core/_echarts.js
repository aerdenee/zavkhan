
function _initEchart(param) {
    console.log(param);
    var _this = $(param.elem).find('#' + param.initId + 'Block');
    console.log(_this);
    $.ajax({
        type: 'post',
        url: param.path,
        data: $(_rootContainerId).find(param.formId).serialize(),
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {

            var tempInit = echarts.init(document.getElementById(param.initId));
            tempInit.clear();
            tempInit.setOption(data);

            window.onresize = function () {
                setTimeout(function () {
                    tempInit.resize();
                }, 200);
            }

        }
    }).done(function () {
        $.unblockUI();
    });
}

