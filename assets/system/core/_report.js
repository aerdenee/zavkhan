$(document).ready(function () {
    if (_getUrlModule() == 'sreport') {
        _initReport({page: 0, searchQuery: {}});
    }
    
});

function _initReport(param) {
    $.ajax({
        type: 'get',
        url: _reportModRootPath + 'home',
        data: param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID + '&per_page=' + param.page,
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_rootContainerId).html(data.html);
        }
    }).done(function () {
        $.unblockUI();
        
            $(_rootContainerId).find('td').on('click', function(){
                console.log('fdsfdsa');
            });
    });
}

function _reportItem(param) {

    $.ajax({
        type: 'get',
        url: _reportModRootPath + 'getReportItem',
        data: {moduleMenuId: _MODULE_MENU_ID, reportMenuId: param.reportMenuId, reportModId: param.reportModId},
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_rootContainerId).html(data.html);
        }
    }).done(function () {
        $('.select2').select2();
        $('.radio, .checkbox').uniform();
        var _from = $("#inDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _to.datepicker("option", "minDate", _getDate(this));
        });
        var _to = $("#outDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _from.datepicker("option", "maxDate", _getDate(this));
        });
        $.unblockUI();
    });
}