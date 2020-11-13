$(document).ready(function () {
    if (_getUrlModule() == 'shrContact') {
        _initHrContact({page: 0, searchQuery: {}});
    }
});
$(document).bind('keydown', 'f2', function () {
    _addFormHrAds({elem: this});
});
$(document).bind('keydown', 'f3', function () {
    _advensedSearchAds({elem: this});
});

function _initHrContact(param) {
    $.ajax({
        type: 'get',
        url: _hrContactModRootPath + 'lists',
        data: param.searchQuery,
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_rootContainerId).html(data);
        }
    }).done(function () {
        $('input[name="hrContactFname"]').keypress(function () {
            if (event.keyCode == 13) {
                _initHrContactData({keyword: $(this).val()});
                $('input[name="hrContactFname"]').val('');
            }
        });

        $('button[name="hrContactButton"]').on('click', function () {

            _initHrContactData({keyword: $('input[name="hrContactFname"]').val()});
            $('input[name="hrContactFname"]').val('');

        });

        $.unblockUI();
    });
}
function _initHrContactData(param) {
    $.ajax({
        type: 'get',
        url: _hrContactModRootPath + 'listsData',
        data: {keyword: param.keyword},
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_hrContactWindowId).html(data);
            $.unblockUI();
        }
    });
}