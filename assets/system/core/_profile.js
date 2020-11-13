var _dgMyCrime = _dgMyExtra = _dgMyEconomy = _dgMyFileFolder = _dgMyAnatomy = _dgMyDoctorView = '';
var _profilePath = window.location.pathname;
_profilePath = _profilePath.split('/');
_profilePath = _profilePath['2'];

$(document).ready(function () {
    if (_getUrlModule() == 'sprofile') {
        if (_profilePath == 'forensics') {
            _initMyNifsCrime({searchQuery: {}});
            _initMyNifsExtra({searchQuery: {}});
            _initMyNifsEconomy({searchQuery: {}});
            _initMyNifsFileFolder({searchQuery: {}});
            _initMyNifsAnatomy({searchQuery: {}});
            _initMyNifsDoctorView({searchQuery: {}});
        } else {
            _initProfile({path: _profilePath});
        }

    }
});

function _initProfile(param) {
    $.ajax({
        type: 'post',
        url: _profileModRootPath + 'initProfile',
        data: {path: param.path, flash: param.flash},
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_profileWindowId).html(data);
        }
    }).done(function () {
        $('.select2').select2();
        $('.radio, .checkbox').uniform();
        
        $.unblockUI();
    });
}
function _editFormUser(param) {
    if (!$(_userDialogId).length) {
        $('<div id="' + _userDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _profileModRootPath + 'edit',
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
                                    url: _profileModRootPath + 'update',
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
                                        _initProfile({page: 0});
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

        $(".fancybox").fancybox();
        $('.radio, .checkbox').uniform();
        $('.select2').select2();
        $('#change-password').editable({
            type: 'json',
            url: _profileModRootPath + 'resetPassword',
            title: 'Нууц үг шинээр үүсгэх',
            params: function (params) {  //params already contain `name`, `value` and `pk`
                var data = {};
                data['userId'] = params.pk;
                data[params.name] = params.value;
                return data;
            },
            success: function (data) {

                new PNotify({
                    text: 'Нууц үг солигдлоо',
                    addclass: 'bg-success'
                });

//                if (data.status == 'success') {
//                    new PNotify({
//                        text: data.message,
//                        addclass: 'bg-success'
//                    });
//                } else {
//                    new PNotify({
//                        text: data.message,
//                        addclass: 'bg-danger'
//                    });
//                }

            },
            error: function () {
                new PNotify({
                    text: 'Алдаа гарлаа',
                    addclass: 'bg-danger'
                });
            }
        });
    });

}
function _updateUserData(param) {
    var _form = $(_profileFormMainId);
    $(_form).validate({
        errorPlacement: function () {
        }});
    if ($(_form).valid()) {
        $.ajax({
            type: 'post',
            url: _profileModRootPath + 'updateUserData',
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
                _initProfile({path: _profilePath, flash: {status: data.status, message: data.message}});
                $.unblockUI();
            }
        });
    }
}
function _updatePhoto(param) {
    var _form = $(_profileFormMainId);

    if (_form.find('input[name="pic"]').val() != '') {
        $.ajax({
            type: 'post',
            url: _profileModRootPath + 'updatePhoto',
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
                _initProfile({path: _profilePath, flash: {status: data.status, message: data.message}});
                $.unblockUI();
            }
        });
    } else {
        if (!$(_dialogAlertDialogId).length) {
            $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $(_dialogAlertDialogId).empty().html(_dialogAlertNoChoosePhotoMessage);
        $(_dialogAlertDialogId).dialog({
            show: {
                effect: "fade",
                duration: 500
            },
            hide: {
                effect: "fade",
                duration: 1000
            },
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
            }
        });
        $(_dialogAlertDialogId).dialog('open');
    }
}
function _updatePassword(param) {
    var _form = $(_profileFormMainId);
    $(_form).validate({
        errorPlacement: function () {
        }});
    if ($(_form).valid()) {
        $.ajax({
            url: _userModRootPath + 'changePassword',
            type: 'POST',
            dataType: 'json',
            data: _form.serialize(),
            beforeSend: function () {
                $.blockUI({message: null});
            },
            success: function (data) {
                _initProfile({path: _profilePath, flash: {status: data.status, message: data.message}});
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        });
    }
}
function _initForensics(param) {

//        var _height = $(_rootContainerId).height() - 30;
//        var _width = $(_rootContainerId).width() - 30;
//
//        $(_rootContainerId).html('<div class="page-container"><div class="page-content"><div id="window-profile"><table id="dgForensics" style="width:100%;"></table></div></div></div>');
//        var _param = [];
//        if (param.searchQuery.length > 0) {
//            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
//        } else {
//            _param.push({moduleMenuId: _MODULE_MENU_ID});
//        }
//
//        _dgForensics = $('#dgForensics').datagrid({
//            url: _profileModRootPath + 'forensicLists',
//            method: 'get',
//            queryParams: _param[0],
//            title: 'Миний шинжилгээний жагсаалт',
//            iconCls: 'icon-save',
//            pageList: [10, 20, 50, 100, 110, 120, 150, 200],
//            pageSize: 100,
//            fitColumns: true,
//            rownumbers: false,
//            toolbar: [{
//                    text: 'Шинэ (F2)',
//                    iconCls: 'dg-icon-add',
//                    disabled: false,
//                    handler: function () {
//                        _addFormNifsCrime({elem: this});
//                    }
//                }, {
//                    text: 'Засах (F3)',
//                    iconCls: 'dg-icon-edit',
//                    disabled: false,
//                    handler: function () {
//                        var _row = _dgForensics.datagrid('getSelected');
//                        if (_row != null) {
//                            _editFormNifsCrime({elem: this, id: _row.id, createdUserId: _row.created_user_id});
//                        } else {
//                            if (!$(_dialogAlertDialogId).length) {
//                                $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
//                            }
//                            $(_dialogAlertDialogId).empty().html(_dialogAlertNoSelectedRowMessage);
//                            $(_dialogAlertDialogId).dialog({
//                                cache: false,
//                                resizable: false,
//                                bgiframe: false,
//                                autoOpen: false,
//                                title: _dialogAlertTitle,
//                                width: _dialogAlertWidth,
//                                height: "auto",
//                                modal: true,
//                                close: function () {
//                                    $(_dialogAlertDialogId).dialog('close').empty();
//                                },
//                                buttons: [
//                                    {text: _dialogAlertBtnClose, class: 'btn btn-primary', click: function () {
//                                            $(_dialogAlertDialogId).dialog('close').empty();
//                                        }}
//
//                                ]
//                            });
//                            $(_dialogAlertDialogId).dialog('open');
//                        }
//                    }
//                }, {
//                    text: 'Хаалт (F4)',
//                    iconCls: 'dg-icon-lock-1',
//                    disabled: false,
//                    handler: function () {
//                        var _row = _dgForensics.datagrid('getSelected');
//                        if (_row != null) {
//                            _closeNifsCrime({elem: this, id: _row.id});
//                        } else {
//                            if (!$(_dialogAlertDialogId).length) {
//                                $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
//                            }
//                            $(_dialogAlertDialogId).empty().html(_dialogAlertNoSelectedRowMessage);
//                            $(_dialogAlertDialogId).dialog({
//                                cache: false,
//                                resizable: false,
//                                bgiframe: false,
//                                autoOpen: false,
//                                title: _dialogAlertTitle,
//                                width: _dialogAlertWidth,
//                                height: "auto",
//                                modal: true,
//                                close: function () {
//                                    $(_dialogAlertDialogId).dialog('close').empty();
//                                },
//                                buttons: [
//                                    {text: _dialogAlertBtnClose, class: 'btn btn-primary', click: function () {
//                                            $(_dialogAlertDialogId).dialog('close').empty();
//                                        }}
//
//                                ]
//                            });
//                            $(_dialogAlertDialogId).dialog('open');
//                        }
//                    }
//                }, {
//                    text: 'Устгах (F6)',
//                    iconCls: 'dg-icon-remove',
//                    handler: function () {
//                        var _row = _dgForensics.datagrid('getSelected');
//                        if (_row != null) {
//                            _removeNifsCrime({elem: this, id: _row.id, createdUserId: _row.created_user_id, modId: _row.mod_id});
//                        } else {
//                            if (!$(_dialogAlertDialogId).length) {
//                                $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
//                            }
//                            $(_dialogAlertDialogId).empty().html(_dialogAlertNoSelectedRowMessage);
//                            $(_dialogAlertDialogId).dialog({
//                                cache: false,
//                                resizable: false,
//                                bgiframe: false,
//                                autoOpen: false,
//                                title: _dialogAlertTitle,
//                                width: _dialogAlertWidth,
//                                height: "auto",
//                                modal: true,
//                                close: function () {
//                                    $(_dialogAlertDialogId).dialog('close').empty();
//                                },
//                                buttons: [
//                                    {text: _dialogAlertBtnClose, class: 'btn btn-primary', click: function () {
//                                            $(_dialogAlertDialogId).dialog('close').empty();
//                                        }}
//
//                                ]
//                            });
//                            $(_dialogAlertDialogId).dialog('open');
//                        }
//
//                    }
//                }, '-', {
//                    text: 'Экспорт (F9)',
//                    iconCls: 'dg-icon-export',
//                    handler: function () {
//                        _exportNifsCrime({elem: this});
//                    }
//                }, '-', {
//                    text: 'Дэлгэрэнгүй хайлт (F10)',
//                    iconCls: 'dg-icon-search',
//                    handler: function () {
//                        _advensedSearchNifsCrime({elem: this});
//                    }
//                }],
//            width: _width,
//            height: _height,
//            singleSelect: true,
//            pagination: true,
//            loadMsg: 'Боловсруулж байна...',
//            columns: [[
//                    {field: 'create_number', title: '#',
//                        styler: function (value, row, index) {
//                            return row.row_status;
//
//                        }},
//                    {field: 'is_mixx', title: ' ', align: 'center', width: 20},
//                    {field: 'in_out_date', title: 'Бүртгэл', width: 96},
//                    {field: 'resolution', title: 'Тогтоол', width: 150},
//                    {field: 'crime_value', title: 'Хэргийн утга', width: 200},
//                    {field: 'object', title: 'Объект', width: 150},
//                    {field: 'question', title: 'Асуулт', width: 100},
//                    {field: 'expert', title: 'Шинжээч', width: 150,
//                        styler: function (value, row, index) {
//                            return row.expert_status;
//
//                        }},
//                    {field: 'weight', title: 'Ач', align: 'center', width: 20},
//                    {field: 'report', title: 'Дүгнэлт', width: 150},
//                    {field: 'description', title: 'Тайлбар', align: 'center'}
//                ]],
//            onHeaderContextMenu: function (e, field) {
//                e.preventDefault();
//            },
//            onRowContextMenu: function (e, index, row) {
//                e.preventDefault();
//            },
//            onBeforeLoad: function (e, index, row) {
//                $('.panel-tool').html('fdsafdsafdsa');
//                
//                $('.pagination-info').prepend('<span class="pr-2 _definition"><span class="_definition-space"><span class="_definition-box"><span class="fa fa-users"></span> </span> - Бүрэлдэхүүнтэй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#2196F3;"></span> - Шинжээч томилоогүй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#4CAF50;"></span> - Хугацаа хэтэрч хаагдсан</span><span class="_definition-space"><span class="_definition-box" style="background-color:#F44336;"></span> - Хугацаа хэтэрсэн хаагдаагүй</span></span>');
//            },
//            onLoadSuccess: function (data) {
//                $('.datagrid-toolbar').find('tr').append(data.search);
//            }, 
//            onDblClickRow: function () {
//                var _row = _dgForensics.datagrid('getSelected');
//                _editFormNifsCrime({elem: this, id: _row.id, createdUserId: _row.created_user_id});
//            }
//        });

}
function _initMyNifsCrime(param) {

    var _height = $(_rootContainerId).height() - 150;
    var _width = $(_rootContainerId).width() - 88;

    $('#my-forensics-crime').html('<table id="dgMyCrime" style="width:100%;"></table>');
    var _param = [];
    if (param.searchQuery.length > 0) {
        _param.push($.parseParams(param.searchQuery + '&moduleMenuId=5&expertId=' + _peopleId));
    } else {
        _param.push({moduleMenuId: 5, expertId: _peopleId});
    }
    _dgMyCrime = $('#dgMyCrime').datagrid({
        url: _profileModRootPath + 'nifsCrimeLists',
        method: 'get',
        queryParams: _param[0],
        title: 'Миний шинжилгээний жагсаалт',
        iconCls: 'icon-save',
        pageList: [10, 20, 50, 100, 110, 120, 150, 200],
        pageSize: 100,
        fitColumns: true,
        rownumbers: false,
        width: _width,
        height: _height,
        singleSelect: true,
        pagination: true,
        loadMsg: 'Боловсруулж байна...',
        toolbar: [{
                text: 'Дэлгэрэнгүй хайлт',
                iconCls: 'dg-icon-search',
                handler: function () {
                    _advensedSearchMyNifsCrime({elem: this});
                }
            }],
        columns: [[
                {field: 'create_number', title: '#',
                    styler: function (value, row, index) {
                        return row.row_status;

                    }},
                {field: 'is_mixx', title: ' ', align: 'center', width: 20},
                {field: 'in_out_date', title: 'Бүртгэл', width: 96},
                {field: 'resolution', title: 'Тогтоол', width: 150},
                {field: 'crime_value', title: 'Хэргийн утга', width: 200},
                {field: 'object', title: 'Объект', width: 150},
                {field: 'question', title: 'Асуулт', width: 100},
                {field: 'expert', title: 'Шинжээч', width: 150,
                    styler: function (value, row, index) {
                        return row.expert_status;

                    }},
                {field: 'weight', title: 'Ач', align: 'center', width: 20},
                {field: 'report', title: 'Дүгнэлт', width: 150},
                {field: 'description', title: 'Тайлбар', align: 'center'}
            ]],
        onHeaderContextMenu: function (e, field) {
            e.preventDefault();
        },
        onRowContextMenu: function (e, index, row) {
            e.preventDefault();
        },
        onLoadSuccess: function (data) {
            if (!$('#my-forensics-crime').find('._search-result-td').length) {
                $('#my-forensics-crime').find('.datagrid-toolbar').find('tr').append(data.search);
            } else {
                $('#my-forensics-crime').find('._search-result-td').remove();
                $('#my-forensics-crime').find('.datagrid-toolbar').find('tr').append(data.search);
            }
            $('#my-forensics-crime').find('.pagination-info').prepend('<span class="pr-2 _definition"><span class="_definition-space"><span class="_definition-box"><span class="fa fa-users"></span> </span> - Бүрэлдэхүүнтэй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#2196F3;"></span> - Шинжээч томилоогүй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#4CAF50;"></span> - Хугацаа хэтэрч хаагдсан</span><span class="_definition-space"><span class="_definition-box" style="background-color:#F44336;"></span> - Хугацаа хэтэрсэн хаагдаагүй</span></span>');
        }
    });

}
function _advensedSearchMyNifsCrime(param) {

    if (!$(_nifsCrimeDialogId).length) {
        $('<div id="' + _nifsCrimeDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsCrimeModRootPath + 'searchForm',
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
            $(_nifsCrimeDialogId).html(data.html);
            $(_nifsCrimeDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsCrimeDialogId).dialog('close').empty();
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsCrimeDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            _initMyCrime({page: 0, searchQuery: $(_nifsCrimeDialogId).find(_nifsCrimeFormMainId + '-search').serialize()});
                            $(_nifsCrimeDialogId).empty().dialog('close');
                        }}

                ]
            });
            $(_nifsCrimeDialogId).dialog('open');
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

        var _protocolFrom = $("#protocolInDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _protocolTo.datepicker("option", "minDate", _getDate(this));
        });
        var _protocolTo = $("#protocolOutDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _protocolFrom.datepicker("option", "maxDate", _getDate(this));
        });

        var _closeDateFrom = $("#closeInDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _closeDateTo.datepicker("option", "minDate", _getDate(this));
        });
        var _closeDateTo = $("#closeOutDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _closeDateFrom.datepicker("option", "maxDate", _getDate(this));
        });

        $('#latentPrintDepartmentId').on('change', function () {
            $.ajax({
                type: 'post',
                url: _hrPeopleModRootPath + 'controlHrPeopleDepartmentPeopleListDropdown',
                data: {name: 'latentPrintExpertId', departmentId: $(this).val(), selectedId: 0},
                dataType: 'json',
                beforeSend: function () {
                    $.blockUI({message: ''});
                },
                success: function (data) {
                    $('#nifs-latent-print-expert').html(data);
                }
            }).done(function () {
                $('.select2').select2();
                $.unblockUI();
            });
        });

        _createNumber();

        $('input[type="text"]').keypress(function () {
            if (event.keyCode == 13) {
                _initNifsCrime({page: 0, searchQuery: $(_nifsCrimeDialogId).find(_nifsCrimeFormMainId + '-search').serialize()});
                $(_nifsCrimeDialogId).empty().dialog('close');
            }
        });

    });

}
function _initMyNifsExtra(param) {
    var _height = $(_rootContainerId).height() - 150;
    var _width = $(_rootContainerId).width() - 108;

    $('#my-forensics-extra').html('<table id="dgMyExtra" style="width:100%;"></table>');
    var _param = [];
    if (param.searchQuery.length > 0) {
        _param.push($.parseParams(param.searchQuery + '&moduleMenuId=22&expertId=' + _peopleId));
    } else {
        _param.push({moduleMenuId: 22, expertId: _peopleId});
    }

    _dgMyExtra = $('#dgMyExtra').datagrid({
        url: _profileModRootPath + 'nifsExtraLists',
        method: 'get',
        queryParams: _param[0],
        title: 'Тусгай шинжилгээ',
        iconCls: 'icon-save',
        pageList: [10, 20, 50, 100, 110, 120, 150, 200],
        pageSize: 100,
        fitColumns: true,
        rownumbers: false,
        toolbar: [{
                text: 'Дэлгэрэнгүй хайлт',
                iconCls: 'dg-icon-search',
                handler: function () {
                    _advensedSearchMyNifsExtra({elem: this});
                }
            }],
        width: _width,
        height: _height,
        singleSelect: true,
        pagination: true,
        loadMsg: 'Боловсруулж байна...',
        columns: [[
                {field: 'create_number', title: '#',
                    styler: function (value, row, index) {
                        return row.row_status;

                    }},
                {field: 'is_mixx', title: ' ', align: 'center', width: 20},
                {field: 'in_out_date', title: 'Бүртгэл', width: 96},
                {field: 'partner_agent_date', title: 'Томилсон байгууллага, огноо', width: 150},
                {field: 'who_is', title: 'Хэн болох', width: 200},
                {field: 'pre_create_number_value', title: 'Хэргийн утга', width: 150},
                {field: 'object', title: 'Объект', width: 150},
                {field: 'question', title: 'Асуулт', width: 100},
                {field: 'expert_type', title: 'Шинжээч', width: 150,
                    styler: function (value, row, index) {
                        return row.expert_status;

                    }},
                {field: 'weight', title: 'Ач', align: 'center', width: 20},
                {field: 'report', title: 'Дүгнэлт', width: 150},
                {field: 'description', title: 'Тайлбар', align: 'center'}
            ]],
        onHeaderContextMenu: function (e, field) {
            e.preventDefault();
        },
        onRowContextMenu: function (e, index, row) {
            e.preventDefault();
        },
        onLoadSuccess: function (data) {
            if (!$('#my-forensics-extra').find('._search-result-td').length) {
                $('#my-forensics-extra').find('.datagrid-toolbar').find('tr').append(data.search);
            } else {
                $('#my-forensics-extra').find('._search-result-td').remove();
                $('#my-forensics-extra').find('.datagrid-toolbar').find('tr').append(data.search);
            }
            $('#my-forensics-extra').find('.pagination-info').prepend('<span class="pr-2 _definition"><span class="_definition-space"><span class="_definition-box"><span class="fa fa-users"></span> </span> - Бүрэлдэхүүнтэй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#2196F3;"></span> - Шинжээч томилоогүй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#4CAF50;"></span> - Хугацаа хэтэрч хаагдсан</span><span class="_definition-space"><span class="_definition-box" style="background-color:#F44336;"></span> - Хугацаа хэтэрсэн хаагдаагүй</span></span>');
        }
    });


}
function _advensedSearchMyNifsExtra(param) {
    var _permission = _checkModulePermission({data: _globalPermission, role: 'read', moduleMenudId: _MODULE_MENU_ID, createdUserId: _uIdCurrent});
    if (_permission) {
        var _dialogId = 'advencedSearchDialog';
        if (!$('#' + _dialogId).length) {
            $('<div id="' + _dialogId + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _nifsExtraModRootPath + 'searchForm',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsExtraModId},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $('#' + _dialogId).html(data.html);
                $('#' + _dialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $('#' + _dialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $('#' + _dialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                _initNifsExtra({modId: _nifsExtraModId, page: 0, searchQuery: $('#' + _dialogId).find('form').serialize()});
                                $('#' + _dialogId).empty().dialog('close');
                            }}
                    ]
                });
                $('#' + _dialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

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

            var _protocolFrom = $("#protocolInDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _protocolTo.datepicker("option", "minDate", _getDate(this));
            });
            var _protocolTo = $("#protocolOutDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _protocolFrom.datepicker("option", "maxDate", _getDate(this));
            });

            var _closeDateFrom = $("#closeInDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _closeDateTo.datepicker("option", "minDate", _getDate(this));
            });
            var _closeDateTo = $("#closeOutDate").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            }).on("change", function () {
                _closeDateFrom.datepicker("option", "maxDate", _getDate(this));
            });

            _age();
            _createNumber();

            $('input[type="text"]').keypress(function () {
                if (event.keyCode == 13) {
                    _initNifsExtra({page: 0, searchQuery: $('#' + _dialogId).find('form').serialize()});
                    $('#' + _dialogId).empty().dialog('close');
                }
            });

        });
    } else {
        _pageDeny();
    }
}
function _initMyNifsEconomy(param) {
    var _height = $(_rootContainerId).height() - 150;
    var _width = $(_rootContainerId).width() - 108;

    $('#my-forensics-economy').html('<table id="dgMyEconomy" style="width:100%;"></table>');
    var _param = [];
    if (param.searchQuery.length > 0) {
        _param.push($.parseParams(param.searchQuery + '&moduleMenuId=23&expertId=' + _peopleId));
    } else {
        _param.push({moduleMenuId: 23, expertId: _peopleId});
    }

    _dgMyEconomy = $('#dgMyEconomy').datagrid({
        url: _profileModRootPath + 'nifsEconomyLists',
        method: 'get',
        queryParams: _param[0],
        title: 'Эдийн засгийн шинжилгээ',
        iconCls: 'icon-save',
        pageList: [10, 20, 50, 100, 110, 120, 150, 200],
        pageSize: 100,
        fitColumns: true,
        rownumbers: false,
        toolbar: [{
                text: 'Дэлгэрэнгүй хайлт',
                iconCls: 'dg-icon-search',
                handler: function () {
                    _advensedSearchMyNifsEconomy({elem: this});
                }
            }],
        width: _width,
        height: _height,
        singleSelect: true,
        pagination: true,
        loadMsg: 'Боловсруулж байна...',
        columns: [[
                {field: 'create_number', title: '#',
                    styler: function (value, row, index) {
                        return row.row_status;

                    }},
                {field: 'is_mixx', title: ' ', align: 'center', width: 20},
                {field: 'in_out_date', title: 'Бүртгэл', width: 96},
                {field: 'partner_agent_date', title: 'Томилсон байгууллага, огноо', width: 150},
                {field: 'protocol_value', title: 'Хэргийн утга', width: 200},
                {field: 'object', title: 'Объект', width: 150},
                {field: 'question', title: 'Асуулт', width: 100},
                {field: 'expert', title: 'Шинжээч', width: 150,
                    styler: function (value, row, index) {
                        return row.expert_status;

                    }},
                {field: 'weight', title: 'Ач', align: 'center', width: 20},
                {field: 'report', title: 'Дүгнэлт', width: 150},
                {field: 'description', title: 'Тайлбар', align: 'center'}
            ]],
        onHeaderContextMenu: function (e, field) {
            e.preventDefault();
        },
        onRowContextMenu: function (e, index, row) {
            e.preventDefault();
        },
        onLoadSuccess: function (data) {
            if (!$('#my-forensics-economy').find('._search-result-td').length) {
                $('#my-forensics-economy').find('.datagrid-toolbar').find('tr').append(data.search);
            } else {
                $('#my-forensics-economy').find('._search-result-td').remove();
                $('#my-forensics-economy').find('.datagrid-toolbar').find('tr').append(data.search);
            }
            $('#my-forensics-economy').find('.pagination-info').prepend('<span class="pr-2 _definition"><span class="_definition-space"><span class="_definition-box"><span class="fa fa-users"></span> </span> - Бүрэлдэхүүнтэй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#2196F3;"></span> - Шинжээч томилоогүй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#4CAF50;"></span> - Хугацаа хэтэрч хаагдсан</span><span class="_definition-space"><span class="_definition-box" style="background-color:#F44336;"></span> - Хугацаа хэтэрсэн хаагдаагүй</span></span>');
        }
    });

}
function _advensedSearchMyNifsEconomy(param) {

    var _dialogId = 'nifsEconomyAdvencedSearchDialog';
    if (!$('#' + _dialogId).length) {
        $('<div id="' + _dialogId + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsEconomyModRootPath + 'searchForm',
        type: 'POST',
        dataType: 'json',
        data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsEconomyModId},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $('#' + _dialogId).html(data.html);
            $('#' + _dialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: "auto",
                modal: true,
                close: function () {
                    $('#' + _dialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $('#' + _dialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {

                            _initMyNifsEconomy({page: 0, searchQuery: $('#' + _dialogId).find('form').serialize()});
                            $('#' + _dialogId).empty().dialog('close');
                        }}
                ]
            });
            $('#' + _dialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {
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

        var _protocolFrom = $("#protocolInDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _protocolTo.datepicker("option", "minDate", _getDate(this));
        });
        var _protocolTo = $("#protocolOutDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _protocolFrom.datepicker("option", "maxDate", _getDate(this));
        });

        var _closeDateFrom = $("#closeInDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _closeDateTo.datepicker("option", "minDate", _getDate(this));
        });
        var _closeDateTo = $("#closeOutDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _closeDateFrom.datepicker("option", "maxDate", _getDate(this));
        });

        _createNumber();

        $('input[type="text"]').keypress(function () {
            if (event.keyCode == 13) {
                _initNifsEconomy({page: 0, searchQuery: $('#' + _dialogId).find('form').serialize()});
                $('#' + _dialogId).empty().dialog('close');
            }
        });

    });

}
function _initMyNifsFileFolder(param) {
    var _height = $(_rootContainerId).height() - 150;
    var _width = $(_rootContainerId).width() - 108;

    $('#my-forensics-file-folder').html('<table id="dgMyFileFolder" style="width:100%;"></table>');
    var _param = [];
    if (param.searchQuery.length > 0) {
        _param.push($.parseParams(param.searchQuery + '&moduleMenuId=14&expertId=' + _peopleId));
    } else {
        _param.push({moduleMenuId: 14, expertId: _peopleId});
    }

    _dgMyFileFolder = $('#dgMyFileFolder').datagrid({
        url: _profileModRootPath + 'nifsFileFolderLists',
        method: 'get',
        queryParams: _param[0],
        title: 'Хавтаст хэргийн бүртгэл',
        iconCls: 'icon-save',
        pageList: [10, 20, 50, 100, 110, 120, 150, 200],
        pageSize: 100,
        fitColumns: true,
        rownumbers: false,
        toolbar: [{
                text: 'Дэлгэрэнгүй хайлт',
                iconCls: 'dg-icon-search',
                handler: function () {
                    _advensedSearchMyNifsFileFolder({elem: this});
                }
            }],
        width: _width,
        height: _height,
        singleSelect: true,
        pagination: true,
        loadMsg: 'Боловсруулж байна...',
        columns: [[
                {field: 'create_number', title: '#',
                    styler: function (value, row, index) {
                        return row.row_status;

                    }},
                {field: 'is_mixx', title: ' ', align: 'center', width: 20},
                {field: 'in_out_date', title: 'Бүртгэл', width: 96},
                {field: 'full_name', title: 'Шинжлүүлэгч', width: 150},
                {field: 'partner', title: 'Тогтоол ТБ', width: 200},
                {field: 'protocol', title: 'Хэргийн дугаар', width: 150},
                {field: 'object', title: 'Объект', width: 150},
                {field: 'pre', title: 'Өмнөх', width: 150},
                {field: 'expert', title: 'Шинжээч/төрөл', width: 150,
                    styler: function (value, row, index) {
                        return row.expert_status;

                    }},
                {field: 'report', title: 'Дүгнэлт', width: 150},
                {field: 'description', title: 'Тайлбар', align: 'center'}
            ]],
        onHeaderContextMenu: function (e, field) {
            e.preventDefault();
        },
        onRowContextMenu: function (e, index, row) {
            e.preventDefault();
        },
        onLoadSuccess: function (data) {
            if (!$('#my-forensics-file-folder').find('._search-result-td').length) {
                $('#my-forensics-file-folder').find('.datagrid-toolbar').find('tr').append(data.search);
            } else {
                $('#my-forensics-file-folder').find('._search-result-td').remove();
                $('#my-forensics-file-folder').find('.datagrid-toolbar').find('tr').append(data.search);
            }
            $('#my-forensics-file-folder').find('.pagination-info').prepend('<span class="pr-2 _definition"><span class="_definition-space"><span class="_definition-box"><span class="fa fa-users"></span> </span> - Бүрэлдэхүүнтэй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#2196F3;"></span> - Шинжээч томилоогүй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#4CAF50;"></span> - Хугацаа хэтэрч хаагдсан</span><span class="_definition-space"><span class="_definition-box" style="background-color:#F44336;"></span> - Хугацаа хэтэрсэн хаагдаагүй</span></span>');
        }
    });

    $('#my-forensics-file-folder').find('.pagination-load').trigger('click');

}
function _advensedSearchMyNifsFileFolder(param) {
    if (!$(_dialogAlertDialogId).length) {
        $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsFileFolderModRootPath + 'searchForm',
        type: 'POST',
        dataType: 'json',
        data: {moduleMenuId: _MODULE_MENU_ID, modId: _nifsFileFolderModId},
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
                            _initMyNifsFileFolder({modId: _nifsFileFolderModId, page: 0, searchQuery: $(_dialogAlertDialogId).find('form').serialize()});
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

        var _protocolFrom = $("#protocolInDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _protocolTo.datepicker("option", "minDate", _getDate(this));
        });
        var _protocolTo = $("#protocolOutDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _protocolFrom.datepicker("option", "maxDate", _getDate(this));
        });

        var _closeDateFrom = $("#closeInDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _closeDateTo.datepicker("option", "minDate", _getDate(this));
        });
        var _closeDateTo = $("#closeOutDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _closeDateFrom.datepicker("option", "maxDate", _getDate(this));
        });

        $('select[name="questionId"]').on('change', function () {

            if ($(this).val() == 38) {

                $('#initFileFolderControlQuestionHtml').html(
                        '<div class="col-md-6">' +
                        '<div class="form-group" style="margin-bottom: 0;">' +
                        '<label for="Нас" required="required" class="control-label text-left" defined="">Нас</label>' +
                        '<div class="clearfix"></div>' +
                        '<input type="text" name="age1" value="" id="age1" placeholder="0" maxlength="3" class="form-control init-control-age" style="width: 50px; float:left; margin-right:20px; text-align:right;">' +
                        '<input type="text" name="age2" value="" id="age2" placeholder="100" maxlength="3" class="form-control init-control-age" style="width: 50px; float:left; text-align:right; margin-right:20px;">' +
                        '<div class="clearfix"></div>' +
                        '<span class="help-block"><i class="icon-help"></i> 5 &lt;= Нас &lt;= 60, 10&lt;= Нас, Нас &lt;= 80 эвсэл сонго .</span>' +
                        '</div>' +
                        '</div>');
                _age();

            } else {
                $('#initFileFolderControlQuestionHtml').html('<input type="hidden" name="age" id="age" value="0">');
            }

        });

        $('input[type="text"]').keypress(function () {
            if (event.keyCode == 13) {
                _initNifsFileFolder({page: 0, searchQuery: $(_dialogAlertDialogId).find(_nifsFileFolderFormMainId + '-search').serialize()});
                $(_dialogAlertDialogId).empty().dialog('close');
            }
        });




    });

}
function _initMyNifsAnatomy(param) {
    var _height = $(_rootContainerId).height() - 150;
    var _width = $(_rootContainerId).width() - 108;

    $('#my-forensics-anatomy').html('<table id="dgMyAnatomy" style="width:100%;"></table>');
    var _param = [];
    if (param.searchQuery.length > 0) {
        _param.push($.parseParams(param.searchQuery + '&moduleMenuId=15&expertId=' + _peopleId));
    } else {
        _param.push({moduleMenuId: 15, expertId: _peopleId});
    }

    _dgMyAnatomy = $('#dgMyAnatomy').datagrid({
        url: _profileModRootPath + 'nifsAnatomyLists',
        method: 'get',
        queryParams: _param[0],
        title: 'Задлан шинжилгээ',
        iconCls: 'icon-save',
        pageList: [10, 20, 50, 100, 110, 120, 150, 200],
        pageSize: 100,
        fitColumns: true,
        rownumbers: false,
        toolbar: [{
                text: 'Дэлгэрэнгүй хайлт (F10)',
                iconCls: 'dg-icon-search',
                handler: function () {
                    _advensedSearchNifsAnatomy({elem: this});
                }
            }],
        width: _width,
        height: _height,
        singleSelect: true,
        pagination: true,
        loadMsg: 'Боловсруулж байна...',
        columns: [[
                {field: 'create_number', title: '#',
                    styler: function (value, row, index) {
                        return row.row_status;

                    }},
                {field: 'is_mixx', title: ' ', align: 'center', width: 20},
                {field: 'in_out_date', title: 'Бүртгэл', width: 70},
                {field: 'be_date', title: 'Үзлэг', width: 70},
                {field: 'full_name', title: 'Шинжлүүлэгч', width: 150},
                {field: 'is_work', title: 'Ажил', width: 50},
                {field: 'partner', title: 'Тогтоол ИГ', width: 100},
                {field: 'short_value', title: 'БХТ', width: 100},
                {field: 'expert', title: 'Эмч', width: 150,
                    styler: function (value, row, index) {
                        return row.expert_status;

                    }},
                {field: 'is_where', title: 'Хаана', width: 100},
                {field: 'diagnosis', title: 'Онош', width: 150},
                {field: 'send_document', title: 'ИБ', width: 30, align: 'center'}
            ]],
        onHeaderContextMenu: function (e, field) {
            e.preventDefault();
        },
        onRowContextMenu: function (e, index, row) {
            e.preventDefault();
        },
        onLoadSuccess: function (data) {

            if (!$('#my-forensics-anatomy').find('._search-result-td').length) {
                $('#my-forensics-anatomy').find('.datagrid-toolbar').find('tr').append(data.search);
            } else {
                $('#my-forensics-anatomy').find('._search-result-td').remove();
                $('#my-forensics-anatomy').find('.datagrid-toolbar').find('tr').append(data.search);
            }

            $('#my-forensics-anatomy').find('.pagination-info').prepend('<span class="pr-2 _definition"><span class="_definition-space"><span class="_definition-box"><span class="fa fa-users"></span> </span> - Бүрэлдэхүүнтэй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#2196F3;"></span> - Шинжээч томилоогүй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#4CAF50;"></span> - Хугацаа хэтэрч хаагдсан</span><span class="_definition-space"><span class="_definition-box" style="background-color:#F44336;"></span> - Хугацаа хэтэрсэн хаагдаагүй</span></span>');
        }
    });

}
function _advensedSearchMyNifsAnatomy(param) {
    if (!$(_nifsAnatomyDialogId).length) {
        $('<div id="' + _nifsAnatomyDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsAnatomyModRootPath + 'searchForm',
        type: 'POST',
        dataType: 'json',
        data: $(_rootContainerId).find('#form-nifs-anatomy-init').serialize() + '&moduleMenuId=' + _MODULE_MENU_ID,
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_nifsAnatomyDialogId).html(data.html);
            $(_nifsAnatomyDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsAnatomyDialogId).dialog('close').empty();
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsAnatomyDialogId).dialog('close').empty();
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            _initMyNifsAnatomy({page: 0, searchQuery: $(_nifsAnatomyDialogId).find('form').serialize()});
                            $(_nifsAnatomyDialogId).dialog('close').empty();
                        }}

                ]
            });
            $(_nifsAnatomyDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {
        $('.select2').select2();
        $('.radio, .checkbox').uniform();

        $("#crimeDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        });

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

        var _protocolFrom = $("#protocolInDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _protocolTo.datepicker("option", "minDate", _getDate(this));
        });
        var _protocolTo = $("#protocolOutDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _protocolFrom.datepicker("option", "maxDate", _getDate(this));
        });

        var _closeDateFrom = $("#closeInDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _closeDateTo.datepicker("option", "minDate", _getDate(this));
        });
        var _closeDateTo = $("#closeOutDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _closeDateFrom.datepicker("option", "maxDate", _getDate(this));
        });

        _createNumber();
        _age();

        $('input[type="text"]').keypress(function () {
            if (event.keyCode == 13) {
                _initNifsAnatomy({page: 0, searchQuery: $(_nifsAnatomyDialogId).find(_nifsAnatomyFormMainId + '-search').serialize()});
                $(_nifsAnatomyDialogId).empty().dialog('close');
            }
        });
    });
}
function _initMyNifsDoctorView(param) {
    var _height = $(_rootContainerId).height() - 150;
    var _width = $(_rootContainerId).width() - 108;

    $('#my-forensics-doctor-view').html('<table id="dgMyDoctorView" style="width:100%;"></table>');
    var _param = [];
    if (param.searchQuery.length > 0) {
        _param.push($.parseParams(param.searchQuery + '&moduleMenuId=15&expertId=' + _peopleId));
    } else {
        _param.push({moduleMenuId: 15, expertId: _peopleId});
    }

    _dgMyDoctorView = $('#dgMyDoctorView').datagrid({
        url: _profileModRootPath + 'nifsDoctorViewLists',
        method: 'get',
        queryParams: _param[0],
        title: 'Үзлэгийн бүртгэл',
        iconCls: 'icon-save',
        pageList: [10, 20, 50, 100, 110, 120, 150, 200],
        pageSize: 100,
        fitColumns: true,
        rownumbers: false,
        toolbar: [{
                text: 'Дэлгэрэнгүй хайлт (F10)',
                iconCls: 'dg-icon-search',
                handler: function () {
                    _advensedSearchMyNifsDoctorView({elem: this});
                }
            }],
        width: _width,
        height: _height,
        singleSelect: true,
        pagination: true,
        loadMsg: 'Боловсруулж байна...',
        columns: [[
                {field: 'create_number', title: '#',
                    styler: function (value, row, index) {
                        return row.row_status;

                    }},
                {field: 'in_out_date', title: 'Бүртгэл', width: 100},
                {field: 'full_name', title: 'Овог, нэр, РД', width: 200},
                {field: 'is_work', title: 'Ажил', width: 70},
                {field: 'partner', title: 'Тогтоол ИГ', width: 150},
                {field: 'short_value', title: 'БХТ', width: 100},
                {field: 'expert', title: 'Эмч', width: 80,
                    styler: function (value, row, index) {
                        return row.expert_status;
                    }},
                {field: 'is_where', title: 'Хаана', align: 'center', width: 70},
                {field: 'close_type', title: 'Гэмтэл', width: 70},
                {field: 'description', title: 'Тайлбар', width: 100},
                {field: 'send_document', title: 'ИБ', width: 30, align: 'center'}
            ]],
        onHeaderContextMenu: function (e, field) {
            e.preventDefault();
        },
        onRowContextMenu: function (e, index, row) {
            e.preventDefault();

        },
        onBeforeLoad: function (e, index, row) {

        },
        onLoadSuccess: function (data) {
            if (!$('#my-forensics-doctor-view').find('._search-result-td').length) {
                $('#my-forensics-doctor-view').find('.datagrid-toolbar').find('tr').append(data.search);
            } else {
                $('#my-forensics-doctor-view').find('._search-result-td').remove();
                $('#my-forensics-doctor-view').find('.datagrid-toolbar').find('tr').append(data.search);
            }
            $('#my-forensics-doctor-view').find('.pagination-info').prepend('<span class="pr-2 _definition"><span class="_definition-space"><span class="_definition-space"><span class="_definition-box" style="background-color:#2196F3;"></span> - Шинжээч томилоогүй</span><span class="_definition-space"><span class="_definition-box" style="background-color:#4CAF50;"></span> - Хугацаа хэтэрч хаагдсан</span><span class="_definition-space"><span class="_definition-box" style="background-color:#F44336;"></span> - Хугацаа хэтэрсэн хаагдаагүй</span></span>');
        }
    });
}
function _advensedSearchMyNifsDoctorView(param) {
    if (!$(_nifsDoctorViewDialogId).length) {
        $('<div id="' + _nifsDoctorViewDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _nifsDoctorViewModRootPath + 'searchForm',
        type: 'POST',
        dataType: 'json',
        data: $(_rootContainerId).find('form#form-nifs-doctor-view-init').serialize() + '&moduleMenuId=' + _MODULE_MENU_ID,
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_nifsDoctorViewDialogId).html(data.html);
            $(_nifsDoctorViewDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: "auto",
                modal: true,
                close: function () {
                    $(_nifsDoctorViewDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_nifsDoctorViewDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            _initMyNifsDoctorView({modId: _nifsDoctorViewModId, page: 0, searchQuery: $(_nifsDoctorViewDialogId).find('form').serialize()});
                            $(_nifsDoctorViewDialogId).empty().dialog('close');
                        }}

                ]
            });
            $(_nifsDoctorViewDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {
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

        var _fromOut = $("#protocolInDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _toOut.datepicker("option", "minDate", _getDate(this));
        });
        var _toOut = $("#protocolOutDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _fromOut.datepicker("option", "maxDate", _getDate(this));
        });

        var _closeDateFrom = $("#closeInDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _closeDateTo.datepicker("option", "minDate", _getDate(this));
        });
        var _closeDateTo = $("#closeOutDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _closeDateFrom.datepicker("option", "maxDate", _getDate(this));
        });

        var _crimeInDate = $("#crimeInDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _crimeOutDate.datepicker("option", "minDate", _getDate(this));
        });
        var _crimeOutDate = $("#crimeOutDate").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        }).on("change", function () {
            _crimeInDate.datepicker("option", "maxDate", _getDate(this));
        });

        $('#shortValueId').on('change', function () {

            var _this = $(this);
            var _html = '';

            if (_this.val() == 9 || _this.val() == 3) {

                _html += '<div class="col-md-6">';
                _html += '<div class="form-group">';
                _html += '<label for="Эр бэлгийн эс" required="required" class="control-label" defined="1">Эр бэлгийн эс</label>';

                $.ajax({
                    url: _nifsDoctorViewModRootPath + 'controlNifsIsSpermDropdown',
                    dataType: 'json',
                    async: false,
                    success: function (data) {
                        _html += data;
                    }
                });

                _html += '</div>';
                _html += '</div>';

                $('#initDoctorViewControlIsSpermHtml').html(_html);

                _html = '<div class="col-md-6">';
                _html += '<div class="form-group">';
                _html += '<label for="Оролцогч" required="required" class="control-label" defined="1">Оролцогч</label>';

                _html += '<select class="select2" name="isCrimeShip">';
                _html += '<option value="0" selected="selected"> - Бүгд - </option>';
                _html += '<option value="1"> Хохирогч </option>';
                _html += '<option value="2"> Холбогдогч </option>';
                _html += '</select>';

                _html += '</div>';
                _html += '</div>';

                $('#initDoctorViewControlIsCrimeShipHtml').html(_html);

                $('.select2').select2();

            } else {

                $('#initDoctorViewControlIsSpermHtml').html('<input type="hidden" name="isSperm" value="0">');
                $('#initDoctorViewControlIsCrimeShipHtml').html('<input type="hidden" name="isCrimeShip" value="0">');

            }

        });

        $('input[type="text"]').keypress(function () {

            if (event.keyCode == 13) {
                _initNifsDoctorView({page: 0, searchQuery: $(_nifsDoctorViewDialogId).find(_nifsDoctorViewFormMainId + '-search').serialize()});
                $(_nifsDoctorViewDialogId).empty().dialog('close');
            }

        });
    });
}
