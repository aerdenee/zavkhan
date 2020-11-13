var _dgUser = '';
var _getUserUrlModule = _getUrlModule();
var _permissionUser = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getUserUrlModule == 'suser') {
        _initUser({page: 0, searchQuery: {}});
    }

});

$(document).bind('keydown', 'f2', function () {
    if (_getUserUrlModule == 'suser') {
        _addFormUser({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getUserUrlModule == 'suser') {
        var _row = _dgUser.datagrid('getSelected');
        if (_row != null) {
            _editFormUser({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getUserUrlModule == 'suser') {
        var _row = _dgUser.datagrid('getSelected');
        if (_row != null) {
            _deleteUser({elem: this, id: _row.id, createdUserId: _row.created_user_id, modId: _row.mod_id});
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
    if (_getUserUrlModule == 'suser') {
        _advensedSearchUser({elem: this});
    }
});

function _initUser(param) {
    if (_permissionUser.isModule) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;

        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-user"><table id="dgUser" style="width:100%;"></table></div></div></div>');
        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgUser = $('#dgUser').datagrid({
            url: _userModRootPath + 'lists',
            method: 'get',
            queryParams: _param[0],
            title: 'Хэрэглэгчийн бүртгэл',
            iconCls: 'icon-save',
            pageList: [10, 20, 50, 100, 110, 120, 150, 200],
            pageSize: 100,
            fitColumns: true,
            rownumbers: false,
            toolbar: [{
                    text: 'Шинэ (F2)',
                    iconCls: 'dg-icon-add',
                    disabled: false,
                    handler: function () {
                        _addFormUser({elem: this});
                    }
                }, {
                    text: 'Засах (F3)',
                    iconCls: 'dg-icon-edit',
                    disabled: false,
                    handler: function () {
                        var _row = _dgUser.datagrid('getSelected');
                        if (_row != null) {
                            _editFormUser({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        var _row = _dgUser.datagrid('getSelected');
                        if (_row != null) {
                            _deleteUser({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        _advensedSearchUser({elem: this});
                    }
                }],
            width: _width,
            height: _height,
            singleSelect: true,
            pagination: true,
            loadMsg: 'Боловсруулж байна...',
            columns: [[
                    {field: 'id', title: '#'},
                    {field: 'pic', title: 'Зураг', width: 80, align: 'center'},
                    {field: 'full_name', title: 'Овог, нэр', width: 550},
                    {field: 'phone', title: 'Утас', width: 100},
                    {field: 'email', title: 'Мэйл', width: 200},
                    {field: 'token', title: 'Төхөөрөмж', width: 60, align: 'center'},
                    {field: 'is_active', title: 'Төлөв', width: 60, align: 'center'}
                ]],
            onHeaderContextMenu: function (e, field) {
                e.preventDefault();
            },
            onRowContextMenu: function (e, index, row) {
                e.preventDefault();

            },
            onLoadSuccess: function (data) {
                if (!$('._search-result-inner').length) {
                    $(_rootContainerId).find('.datagrid').prepend(data.search);
                } else {
                    $('._search-result-inner').remove();
                    $(_rootContainerId).find('.datagrid').prepend(data.search);
                }
            }, onDblClickRow: function () {
                var _row = _dgUser.datagrid('getSelected');
                _editFormUser({elem: this, id: _row.id, createdUserId: _row.created_user_id});
            }
        });
    } else {
        _pageDeny();
    }
}
function _deleteUser(param) {
    if ((_permissionUser.our.delete && param.createdUserId == _uIdCurrent) || (_permissionUser.your.delete && param.createdUserId != _uIdCurrent)) {
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
                            url: _userModRootPath + 'delete',
                            dataType: "json",
                            data: {id: param.id},
                            success: function (data) {
                                _PNotify({status: data.status, message: data.message});
                                _initUser({page: 0, searchQuery: $(_userFormMainId + '-init').serialize()});
                            }
                        });
                        $(_dialogAlertDialogId).dialog('close');
                    }}

            ]
        });
        $(_dialogAlertDialogId).dialog('open');
    } else {
        _pageDeny();
    }
}
function _advensedSearchUser(param) {
    if (_permissionUser.isModule) {
        if (!$(_dialogAlertDialogId).length) {
            $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _userModRootPath + 'searchForm',
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
                        {text: data.btn_yes, class: 'btn btn-primary', click: function () {
                                _initUser({page: 0, searchQuery: $(_dialogAlertDialogId).find('form').serialize()});
                                $(_dialogAlertDialogId).empty().dialog('close');
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
            $('input[type="text"]').keypress(function () {
                if (event.keyCode == 13) {
                    _initUser({page: 0, searchQuery: $(_dialogAlertDialogId).find('form').serialize()});
                    $(_dialogAlertDialogId).empty().dialog('close');
                }
            });
        });
    } else {
        _pageDeny();
    }
}
function _addFormUser(param) {

    if (_permissionUser.our.create) {
        if (!$(_userDialogId).length) {
            $('<div id="' + _userDialogId.replace('#', '') + '"></div>').appendTo('body');
        }

        $.ajax({
            url: _userModRootPath + 'add',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID},
            beforeSend: function () {
                $.blockUI({message: null});
            },
            success: function (data) {

                $(_userDialogId).empty().html(data.html);
                $(_userDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_userDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_userDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_userDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});

                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _userModRootPath + 'insert',
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
                                            _initUser({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_userDialogId).empty().dialog('close');
                                    });
                                }
                            }}
                    ]
                });
                $(_userDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

            $('.select2').select2();
            $('.radio, .checkbox').uniform();

            var _department = $(_userDialogId).find('#userDepartmentId');

            _department.on('change', function () {
                _controlHrPeopleDropdown({name: 'peopleId', departmentId: $(this).val(), selectedId: 0});
            });

        });
    } else {
        _pageDeny();
    }
}
function _controlHrPeopleDropdown(param) {


    $.ajax({
        url: _hrPeopleModRootPath + 'controlHrPeopleListDropdown',
        type: 'POST',
        dataType: 'json',
        data: {name: param.name, departmentId: param.departmentId, selectedId: param.selectedId},
        beforeSend: function () {
            $.blockUI({message: null});
        },
        success: function (data) {
            $('#hr-people-department-people-list-dropdown-html').html('<div class="form-group row"><label for="Ажилтан" required="required" class="control-label col-md-3 text-right" defined="1">Ажилтан: </label><div class="col-md-9">' + data + '</div></div>');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function () {
        $('.select2').select2();
        $('#peopleId').on('change', function () {
            _setUserInformation({selectedId: $(this).val()});
        });

    });


}
function _setUserInformation(param) {
    $.ajax({
        url: _hrPeopleModRootPath + 'getData',
        type: 'POST',
        dataType: 'json',
        data: {selectedId: param.selectedId},
        beforeSend: function () {
            $.blockUI({message: null});
        },
        success: function (data) {
            $('#lname').val(data.lname);
            $('#fname').val(data.fname);
            $('#email').val(data.email);
            $('#phone').val(data.phone);
            $('#user').val(data.email);
            $('input[name="oldPic"]').val(data.pic);//өмнө үүссэн зургийг ашиглаж байгаа тул хуучин талбарт утга оноож байгаа

            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function () {
        $('.select2').select2();
    });
}
function _editFormUser(param) {
    if ((_permissionUser.our.update && param.createdUserId == _uIdCurrent) || (_permissionUser.your.update && param.createdUserId != _uIdCurrent)) {
        if (!$(_userDialogId).length) {
            $('<div id="' + _userDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _userModRootPath + 'edit',
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
                $(_userDialogId).empty().html(data.html);
                $(_userDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_userDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_userDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                                var _form = $(_userDialogId).find('form');
                                $(_form).validate({
                                    errorPlacement: function () {
                                    }});
                                if ($(_form).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: _userModRootPath + 'update',
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
                                            $(_userDialogId).empty().dialog('close');
                                            _initUser({page: 0, searchQuery: {}});
                                            $.unblockUI();
                                        }
                                    }).done(function () {
                                        $(_userDialogId).empty().dialog('close');
                                    });
                                }
                            }}
                    ]
                });
                $(_userDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

            $('.select2').select2();
            $('.radio, .checkbox').uniform();

        });
    } else {
        _pageDeny();
    }
}
function _readFormUser(param) {

    if (!$(_userDialogId).length) {
        $('<div id="' + _userDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _userModRootPath + 'read',
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
            $(_userDialogId).empty().html(data.html);
            $(_userDialogId).dialog({
                cache: false,
                resizable: true,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: data.width,
                height: "auto",
                modal: true,
                close: function () {
                    $(_userDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-primary active legitRipple', click: function () {

                            $(_userDialogId).empty().dialog('close');
                        }}
                ]
            });
            $(_userDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {

        $('.radio, .checkbox').uniform({radioClass: 'choice'});
        $('.select2').select2();

    });

}
function _setUserPassword(param) {
    var _dialogAlertDialogId = '#userPasswordResetDialog';
    if (!$(_dialogAlertDialogId).length) {
        $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _userModRootPath + 'formSetUserPassword',
        type: 'POST',
        dataType: 'json',
        data: {userId: param.userId},
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
                width: 450,
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
                            var _form = $(_dialogAlertDialogId).find('form');

                            $.ajax({
                                url: _userModRootPath + 'setPassword',
                                type: 'POST',
                                dataType: 'json',
                                data: {userId: param.userId, newPassword: _form.find('input[name="newPassword"]').val(), confirmPassword: _form.find('input[name="confirmPassword"]').val()},
                                beforeSend: function () {
                                    $.blockUI({message: null});
                                },
                                success: function (data) {

                                    if (data.status == 'success') {
                                        $(_dialogAlertDialogId).empty().dialog('close');
                                    }
                                    _PNotify({status: data.status, message: data.message});
                                    $.unblockUI();
                                },
                                error: function () {
                                    $.unblockUI();
                                }
                            });

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
        $('input[type="text"]').keypress(function () {
            if (event.keyCode == 13) {
                _initUser({page: 0, searchQuery: $(_dialogAlertDialogId).find('form').serialize()});
                $(_dialogAlertDialogId).empty().dialog('close');
            }
        });
    });
}


