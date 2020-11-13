var _dgHrAds = '';
var _getHrAdsUrlModule = _getUrlModule();
var _permissionHrAds = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getHrAdsUrlModule == 'shrAds') {
        _initHrAds({page: 0, searchQuery: {}});
    }
});

$(document).bind('keydown', 'f2', function () {
    if (_getHrAdsUrlModule == 'shrAds') {
        _addFormHrAds({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getHrAdsUrlModule == 'shrAds') {
        var _row = _dgHrAds.datagrid('getSelected');
        if (_row != null) {
            _editFormHrAds({elem: this, id: _row.id, createdUserId: _row.created_user_id});
        } else {
            if (!$(_dialogAlertDialogId).length) {
                $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
            }
            $(_dialogAlertDialogId).empty().html(_dialogAlertNoSelectedRowMessage);
            $(_dialogAlertDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: _dialogAlertTitle,
                width: _dialogAlertWidth,
                height: "auto",
                modal: true,
                close: function () {
                    $(_dialogAlertDialogId).dialog('close').empty();
                },
                buttons: [
                    {text: _dialogAlertBtnClose, class: 'btn btn-primary', click: function () {
                            $(_dialogAlertDialogId).dialog('close').empty();
                        }}

                ]
            });
            $(_dialogAlertDialogId).dialog('open');
        }
    }

});
$(document).bind('keydown', 'f6', function () {
    if (_getHrAdsUrlModule == 'shrAds') {
        var _row = _dgHrAds.datagrid('getSelected');
        if (_row != null) {
            _deleteHrAds({elem: this, id: _row.id, createdUserId: _row.created_user_id, modId: _row.mod_id});
        } else {
            if (!$(_dialogAlertDialogId).length) {
                $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
            }
            $(_dialogAlertDialogId).empty().html(_dialogAlertNoSelectedRowMessage);
            $(_dialogAlertDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: _dialogAlertTitle,
                width: _dialogAlertWidth,
                height: "auto",
                modal: true,
                close: function () {
                    $(_dialogAlertDialogId).dialog('close').empty();
                },
                buttons: [
                    {text: _dialogAlertBtnClose, class: 'btn btn-primary', click: function () {
                            $(_dialogAlertDialogId).dialog('close').empty();
                        }}

                ]
            });
            $(_dialogAlertDialogId).dialog('open');
        }
    }

});
$(document).bind('keydown', 'f10', function () {
    if (_getHrAdsUrlModule == 'shrAds') {
        _advensedSearchAds({elem: this});
    }
});
function _initHrAds(param) {
    if (_permissionHrAds.isModule) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;

        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-hr-ads"><table id="dgHrAds" style="width:100%;"></table></div></div></div>');
        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgHrAds = $('#dgHrAds').datagrid({
            url: _hrAdsModRootPath + 'lists',
            method: 'get',
            queryParams: _param[0],
            title: 'Мэдээ, мэдээлэл',
            iconCls: 'icon-save',
            pageList: [10, 20, 50, 100, 110, 120, 150, 200],
            pageSize: 100,
            fitColumns: true,
            rownumbers: false,
            toolbar: [{
                    text: 'Шинэ (F2)',
                    iconCls: 'dg-icon-add',
                    handler: function () {
                        _addFormHrAds({elem: this});
                    }
                }, {
                    text: 'Засах (F3)',
                    iconCls: 'dg-icon-edit',
                    disabled: false,
                    handler: function () {
                        var _row = _dgHrAds.datagrid('getSelected');
                        if (_row != null) {
                            _editFormHrAds({elem: this, id: _row.id, createdUserId: _row.created_user_id});
                        } else {
                            if (!$(_dialogAlertDialogId).length) {
                                $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
                            }
                            $(_dialogAlertDialogId).empty().html(_dialogAlertNoSelectedRowMessage);
                            $(_dialogAlertDialogId).dialog({
                                cache: false,
                                resizable: false,
                                bgiframe: false,
                                autoOpen: false,
                                title: _dialogAlertTitle,
                                width: _dialogAlertWidth,
                                height: "auto",
                                modal: true,
                                close: function () {
                                    $(_dialogAlertDialogId).dialog('close').empty();
                                },
                                buttons: [
                                    {text: _dialogAlertBtnClose, class: 'btn btn-primary', click: function () {
                                            $(_dialogAlertDialogId).dialog('close').empty();
                                        }}

                                ]
                            });
                            $(_dialogAlertDialogId).dialog('open');
                        }
                    }
                }, {
                    text: 'Устгах (F6)',
                    iconCls: 'dg-icon-remove',
                    handler: function () {
                        var _row = _dgHrAds.datagrid('getSelected');
                        if (_row != null) {
                            _deleteHrAds({elem: this, id: _row.id, createdUserId: _row.created_user_id, modId: _row.mod_id});
                        } else {
                            if (!$(_dialogAlertDialogId).length) {
                                $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
                            }
                            $(_dialogAlertDialogId).empty().html(_dialogAlertNoSelectedRowMessage);
                            $(_dialogAlertDialogId).dialog({
                                cache: false,
                                resizable: false,
                                bgiframe: false,
                                autoOpen: false,
                                title: _dialogAlertTitle,
                                width: _dialogAlertWidth,
                                height: "auto",
                                modal: true,
                                close: function () {
                                    $(_dialogAlertDialogId).dialog('close').empty();
                                },
                                buttons: [
                                    {text: _dialogAlertBtnClose, class: 'btn btn-primary', click: function () {
                                            $(_dialogAlertDialogId).dialog('close').empty();
                                        }}

                                ]
                            });
                            $(_dialogAlertDialogId).dialog('open');
                        }

                    }
                }, '-', {
                    text: 'Илгээх (F11)',
                    iconCls: 'dg-icon-send-message',
                    handler: function () {
                        var _row = _dgHrAds.datagrid('getSelected');
                        if (_row != null) {
                            _sendNotificationHrAds({elem: this, id: _row.id, createdUserId: _row.created_user_id});
                        } else {
                            if (!$(_dialogAlertDialogId).length) {
                                $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
                            }
                            $(_dialogAlertDialogId).empty().html(_dialogAlertNoSelectedRowMessage);
                            $(_dialogAlertDialogId).dialog({
                                cache: false,
                                resizable: false,
                                bgiframe: false,
                                autoOpen: false,
                                title: _dialogAlertTitle,
                                width: _dialogAlertWidth,
                                height: "auto",
                                modal: true,
                                close: function () {
                                    $(_dialogAlertDialogId).dialog('close').empty();
                                },
                                buttons: [
                                    {text: _dialogAlertBtnClose, class: 'btn btn-primary', click: function () {
                                            $(_dialogAlertDialogId).dialog('close').empty();
                                        }}

                                ]
                            });
                            $(_dialogAlertDialogId).dialog('open');
                        }

                    }
                }, '-', {
                    text: 'Дэлгэрэнгүй хайлт (F10)',
                    iconCls: 'dg-icon-search',
                    handler: function () {
                        _advensedSearchAds({elem: this});
                    }
                }],
            width: _width,
            height: _height,
            singleSelect: true,
            pagination: true,
            loadMsg: 'Боловсруулж байна...',
            columns: [[
                    {field: 'id', title: '#'},
                    {field: 'pic', title: 'Зураг', width: 150},
                    {field: 'title', title: 'Гарчиг', width: 550},
                    {field: 'department_date', title: 'Тайлбар', width: 350},
                    {field: 'send_count', title: 'Илгээсэн', width: 60, align: 'center'},
                    {field: 'is_active', title: 'Төлөв', width: 60, align: 'center'}
                ]],
            onHeaderContextMenu: function (e, field) {
                e.preventDefault();
            },
            onRowContextMenu: function (e, index, row) {
                e.preventDefault();
            },
            onLoadSuccess: function (data) {
                $('.datagrid-toolbar').find('tr').append(data.search);
                $('.pagination-info').prepend('<span class="pr-2 _definition"><span class="_definition-space"><span class="_definition-box"><span class="fa fa-users"></span> </span> - Бүрэлдэхүүнтэй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#2196F3;"></span> - Шинжээч томилоогүй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#4CAF50;"></span> - Хугацаа хэтэрч хаагдсан</span><span class="_definition-space"><span class="_definition-box" style="background-color:#F44336;"></span> - Хугацаа хэтэрсэн хаагдаагүй</span></span>');
            }, onDblClickRow: function () {
                var _row = _dgHrAds.datagrid('getSelected');
                _editFormHrAds({elem: this, id: _row.id, createdUserId: _row.created_user_id});
            }
        });
    } else {
        _pageDeny();
    }
}
function _deleteHrAds(param) {
    if ((_permissionHrAds.our.delete && param.userId == _uIdCurrent) || (_permissionHrAds.your.delete && param.userId != _uIdCurrent)) {
        if (!$(_dialogAlertDialogId).length) {
            $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $(_dialogAlertDialogId).empty().html(_dialogAlertDeleteMessage);
        $(_dialogAlertDialogId).dialog({
            cache: false,
            resizable: false,
            bgiframe: false,
            autoOpen: false,
            title: _dialogAlertTitle,
            width: _dialogAlertWidth,
            height: "auto",
            modal: true,
            close: function () {
                $(_dialogAlertDialogId).empty().dialog('close');
            },
            buttons: [
                {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                        $(_dialogAlertDialogId).empty().dialog('close');
                    }},
                {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                        $.ajax({
                            type: 'post',
                            url: _hrAdsModRootPath + 'delete',
                            dataType: "json",
                            data: {id: param.id},
                            beforeSend: function () {
                                $.blockUI({
                                    message: _jqueryBlockUiMessage,
                                    overlayCSS: _jqueryBlockUiOverlayCSS,
                                    css: _jqueryBlockUiMessageCSS
                                });
                            },
                            success: function (data) {
                                _PNotify({status: data.status, message: data.message});
                                _initHrAds({page: 0});
                                $.unblockUI();
                            }
                        });
                        $(_dialogAlertDialogId).empty().dialog('close');
                    }}
            ]
        });
        $(_dialogAlertDialogId).dialog('open');
    } else {
        _pageDeny();
    }
}
function _advensedSearchAds(param) {
    if (_permissionHrAds.isModule) {
        if (!$(_dialogAlertDialogId).length) {
            $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _hrAdsModRootPath + 'searchForm',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_dialogAlertDialogId).html(data.html);
                $(_dialogAlertDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_dialogAlertDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_dialogAlertDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                _initHrAds({page: 0, searchQuery: $(_dialogAlertDialogId).find('form').serialize()});
                                $(_dialogAlertDialogId).empty().dialog('close');
                                $.unblockUI();
                            }}
                    ]
                });
                $(_dialogAlertDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.select2').select2();
            $('.radio, .checkbox').uniform({radioClass: 'choice'});

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

            $('input[type="text"]').keypress(function () {
                if (event.keyCode == 13) {
                    _initHrAds({page: 0, searchQuery: $(_dialogAlertDialogId).find('form').serialize()});
                    $(_dialogAlertDialogId).empty().dialog('close');
                }
            });
        });
    } else {
        _pageDeny();
    }
}
function _addFormHrAds(param) {
    if (_permissionHrAds.our.create) {
        if (!$(_hrAdsDialogId).length) {
            $('<div id="' + _hrAdsDialogId.replace('#', '') + '"></div>').appendTo('body');
        }

        $.ajax({
            url: _hrAdsModRootPath + 'add',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_hrAdsDialogId).empty().html(data.html);

                $(_hrAdsDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_hrAdsDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_hrAdsDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                $('textarea[name="introText"]').val(CKEDITOR.instances.introText.getData());

                                var _form = $(_hrAdsDialogId).find('form');
                                $(_form).validate({
                                    errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _hrAdsModRootPath + 'insert',
                                        data: $(_form).serialize(),
                                        dataType: 'json',
                                        beforeSend: function () {
                                            $.blockUI({
                                                message: _jqueryBlockUiMessage,
                                                overlayCSS: _jqueryBlockUiOverlayCSS,
                                                css: _jqueryBlockUiMessageCSS
                                            });
                                        },
                                        success: function (data) {
                                            _PNotify({status: data.status, message: data.message});
                                            _sendNotification({title: $(_form).find('#title').val(), message: $(_form).find('#fullText').val(), type: 'all'});
                                            _initHrAds({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_hrAdsDialogId).empty().dialog('close');
                                    });
                                }
                            }}
                    ]
                });
                $(_hrAdsDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

            $('.select2').select2({
                tags: true
            });
            $('.radio, .checkbox').uniform();
            $('.init-date').pickadate({
                labelMonthNext: _globalDatePickerNextMonth,
                labelMonthPrev: _globalDatePickerPrevMonth,
                labelMonthSelect: _globalDatePickerChooseMonth,
                labelYearSelect: _globalDatePickerChooseYear,
                selectMonths: true,
                selectYears: true,
                monthsFull: _globalDatePickerListMonth,
                weekdaysShort: _globalDatePickerListWeekDayShort,
                today: _globalDatePickerChooseToday,
                clear: _globalDatePickerChooseClear,
                close: _globalDatePickerChooseClose,
                formatSubmit: 'yyyy-mm-dd',
                format: 'yyyy-mm-dd'
            });

            $('.pickatime-limits').pickatime({
                min: [7, 30],
                max: [14, 0],
                formatSubmit: 'HH:i',
                hiddenName: true
            });
            CKEDITOR.replace('introText');

            $.getScript('/assets/system/core/_contentMedia.js');
            $.getScript('/assets/system/core/_contentComment.js');

        });
    } else {
        _pageDeny();
    }

}
function _editFormHrAds(param) {
    if ((_permissionHrAds.our.update && param.userId == _uIdCurrent) || (_permissionHrAds.your.update && param.userId != _uIdCurrent)) {
        if (!$(_hrAdsDialogId).length) {
            $('<div id="' + _hrAdsDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _hrAdsModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_hrAdsDialogId).html(data.html);

                $(_hrAdsDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_hrAdsDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_hrAdsDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                $('textarea[name="introText"]').val(CKEDITOR.instances.introText.getData());

                                var _form = $(_hrAdsDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _hrAdsModRootPath + 'update',
                                        data: $(_form).serialize(),
                                        dataType: 'json',
                                        beforeSend: function () {
                                            $.blockUI({
                                                message: _jqueryBlockUiMessage,
                                                overlayCSS: _jqueryBlockUiOverlayCSS,
                                                css: _jqueryBlockUiMessageCSS
                                            });
                                        },
                                        success: function (data) {
                                            _PNotify({status: data.status, message: data.message});
                                            _initHrAds({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_hrAdsDialogId).empty().dialog('close');
                                    });
                                }
                            }}

                    ]
                });
                $(_hrAdsDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

            $('.select2').select2({
                tags: true
            });
            $('.radio, .checkbox').uniform();
            $('.init-date').pickadate({
                labelMonthNext: _globalDatePickerNextMonth,
                labelMonthPrev: _globalDatePickerPrevMonth,
                labelMonthSelect: _globalDatePickerChooseMonth,
                labelYearSelect: _globalDatePickerChooseYear,
                selectMonths: true,
                selectYears: true,
                monthsFull: _globalDatePickerListMonth,
                weekdaysShort: _globalDatePickerListWeekDayShort,
                today: _globalDatePickerChooseToday,
                clear: _globalDatePickerChooseClear,
                close: _globalDatePickerChooseClose,
                formatSubmit: 'yyyy-mm-dd',
                format: 'yyyy-mm-dd'
            });

            $('.pickatime-limits').pickatime({
                min: [7, 30],
                max: [14, 0],
                formatSubmit: 'HH:i',
                hiddenName: true
            });
            CKEDITOR.replace('introText');

            _initContentMedia({page: 0, searchQuery: {}});
            _initContentComment();

        });
    } else {
        _pageDeny();
    }

}
function _sendNotificationHrAds(param) { /*Click send button*/
    if ((_permissionHrAds.our.update && param.userId == _uIdCurrent) || (_permissionHrAds.your.update && param.userId != _uIdCurrent)) {
        var json = null;
        var _phoneToken = null;

        $.ajax({
            type: 'post',
            url: _hrAdsModRootPath + 'getData',
            dataType: "json",
            data: {id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $.ajax({
                    type: 'post',
                    url: _hrAdsModRootPath + 'getPhoneToken',
                    dataType: "json",
                    data: {adsId: param.id},
                    async: false,
                    success: function (rdata) {
                        _phoneToken = rdata;
                    }
                });
                json = {
                    registration_ids: _phoneToken,
                    prioryty: 'normal',
                    "data": {
                        title: data.title,
                        message: data.intro_text,
                        count: 1,
                        sound: 'default',
                        notId: 1,
                        content_available: '0',
                        path: '0',
                        params: {id: param.id}}};

                $.ajax({
                    headers: {
                        'Authorization': 'key=AAAAXzRBAIE:APA91bEvnetGnSdVKPtRJH3DS2jpAdlSVrgUI8lBOvVO4Z2eXinSYTeB_JfJZWexAiKuLI3q80SmCTzRNy60qpDZY1o3ZwqxdiUGNxjjs9b0RX73i5GEru24YevfmWI3KkijjEWwxMHT',
                        'Content-Type': 'application/json'
                    },
                    type: "post",
                    url: "https://fcm.googleapis.com/fcm/send",
                    data: JSON.stringify(json),
                    dataType: "json",
                    async: false,
                    success: function (response) {

                        _sendNotificationCounter({id: param.id});
                        _initHrAds({page: 0, searchQuery: {}});
                        _PNotify({status: 'success', message: 'Амжилттай илгээгдлээ'});
                        return response;
                    },
                    error: function (err) {
                        return err;
                    }
                });

                $.unblockUI();
            }
        });
    } else {
        _pageDeny();
    }
}
function _sendNotificationCounter(params) {

    $.ajax({
        type: 'post',
        url: _hrAdsModRootPath + 'sendNotificationCounter',
        dataType: "json",
        data: {selectedId: params.id},
        success: function (data) {
            _initHrAds({page: 0, searchQuery: {}});
        }
    });

}
function sendDemo() {

    json = {
        registration_ids: ['fZ-usdPoalo:APA91bEF1MFnP41QiLvRfKFt_frHW0VYjG_Bmy0_s1RCYF_iD_TmkymTS-pfE_LbT0EAnMB5S47wfxjFBD9pvWah4w3sCaMhlUqsTBkaMcZ_I8KO3auMsjph3deiqYzyn3Q3RxOh9FwY', 'dXS5rUsQDyA:APA91bE0LtqV9hSmko4MVi61Y5zadNpVRdTAFKNDbEdzOfsEQTiXRhiB3j23I59sTATEe9rAmufd8U1SuSWcoIydWvU9myTYKvradAfldVqVQti-D_xeoSOeJ-cl1NQG7BKnlekyGZqb'],
        prioryty: 'normal',
        "data": {
            title: 'test title',
            message: 'test message',
            sound: 'default',
            notId: 1,
            content_available: '0',
            path: '0',
            params: {id: 1}}};

    $.ajax({
        headers: {
            'Authorization': 'key=AAAAXzRBAIE:APA91bEvnetGnSdVKPtRJH3DS2jpAdlSVrgUI8lBOvVO4Z2eXinSYTeB_JfJZWexAiKuLI3q80SmCTzRNy60qpDZY1o3ZwqxdiUGNxjjs9b0RX73i5GEru24YevfmWI3KkijjEWwxMHT',
            'Content-Type': 'application/json'
        },
        type: "post",
        url: "https://fcm.googleapis.com/fcm/send",
        data: JSON.stringify(json),
        dataType: "json",
        async: false,
        success: function (response) {

            console.log(response);
            return response;
        },
        error: function (err) {
            return err;
        }
    });
}