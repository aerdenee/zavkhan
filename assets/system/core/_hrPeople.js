var _dgHrPeople = '';
var _getHrPeopleUrlModule = _getUrlModule();
var _permissionHrPeople = _checkModulePermission({data: _globalPermission, moduleMenudId: _MODULE_MENU_ID});

$(document).ready(function () {
    if (_getHrPeopleUrlModule == 'shrPeople') {
        _initHrPeople({page: 0, searchQuery: {}});
    }
});
$(document).bind('keydown', 'f2', function () {
    if (_getHrPeopleUrlModule == 'shrPeople') {
        _addFormHrPeople({elem: this});
    }
});
$(document).bind('keydown', 'f3', function () {
    if (_getHrPeopleUrlModule == 'shrPeople') {
        var _row = _dgHrPeople.datagrid('getSelected');
        if (_row != null) {
            _editFormHrPeople({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
    if (_getHrPeopleUrlModule == 'shrPeople') {
        _advensedSearchHrPeople({elem: this});
    }
});

function _initHrPeople(param) {
    if (_permissionHrPeople.isModule) {
        var _height = $(_rootContainerId).height() - 30;
        var _width = $(_rootContainerId).width() - 30;

        $(_rootContainerId).html('<style>.datagrid-body td[field="pic"] div img {width:80px;}</style><div class="page-container"><div class="page-content"><div id="window-hr-people"><table id="dgHrPeople" style="width:100%;"></table></div></div></div>');

        var _param = [];
        if (param.searchQuery.length > 0) {
            _param.push($.parseParams(param.searchQuery + '&moduleMenuId=' + _MODULE_MENU_ID));
        } else {
            _param.push({moduleMenuId: _MODULE_MENU_ID});
        }

        _dgHrPeople = $('#dgHrPeople').datagrid({
            url: _hrPeopleModRootPath + 'lists',
            method: 'get',
            queryParams: _param[0],
            title: 'Хүний нөөцийн бүртгэл',
            iconCls: 'icon-save',
            pageList: [10, 20, 50, 100, 110, 120, 150, 200],
            pageSize: 100,
            fitColumns: true,
            rownumbers: false,
            toolbar: [{
                    text: 'Шинэ (F2)',
                    iconCls: 'dg-icon-add',
                    handler: function () {
                        _addFormHrPeople({elem: this});
                    }
                }, {
                    text: 'Засах (F3)',
                    iconCls: 'dg-icon-edit',
                    disabled: false,
                    handler: function () {
                        var _row = _dgHrPeople.datagrid('getSelected');
                        if (_row != null) {
                            _editFormHrPeople({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        var _row = _dgHrPeople.datagrid('getSelected');
                        if (_row != null) {
                            _removeHrPeople({elem: this, id: _row.id, createdUserId: _row.created_user_id});
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
                        _advensedSearchHrPeople({elem: this});
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
                    {field: 'full_name_position', title: 'Овог, нэр, албан тушаал', width: 550},
                    {field: 'contact', title: 'Хаяг, утас', width: 200},
                    {field: 'status', title: 'Төлөв', width: 60, align: 'center'}
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
            },
            onDblClickRow: function () {
                var _row = _dgHrPeople.datagrid('getSelected');
                _editFormHrPeople({elem: this, id: _row.id, createdUserId: _row.created_user_id});
            }
        });
    } else {
        _pageDeny();
    }
}
function _removeHrPeople(param) {
    if ((_permissionHrPeople.our.delete && param.createdUserId == _uIdCurrent) || (_permissionHrPeople.your.delete && param.createdUserId != _uIdCurrent)) {
        if (!$(_hrPeopleDialogId).length) {
            $('<div id="' + _hrPeopleDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $(_hrPeopleDialogId).empty().html(_dialogAlertDeleteMessage);
        $(_hrPeopleDialogId).dialog({
            cache: false,
            resizable: false,
            bgiframe: false,
            autoOpen: false,
            title: _dialogAlertTitle,
            width: _dialogAlertWidth,
            height: "auto",
            modal: true,
            close: function () {
                $(_hrPeopleDialogId).dialog('close').empty();
            },
            buttons: [
                {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                        $(_hrPeopleDialogId).dialog('close').empty();
                    }},
                {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                        $.ajax({
                            type: 'post',
                            url: _hrPeopleModRootPath + 'delete',
                            dataType: "json",
                            data: {id: param.id},
                            success: function (data) {
                                _PNotify({status: data.status, message: data.message});
                                _initHrPeople({page: 0, searchQuery: $(_rootContainerId).find(_hrPeopleFormMainId + '-init').serialize()});
                                $.unblockUI();
                            }
                        });
                        $(_hrPeopleDialogId).dialog('close').empty();
                    }}

            ]
        });
        $(_hrPeopleDialogId).dialog('open');
    } else {
        _pageDeny();
    }
}
function _advensedSearchHrPeople(param) {
    if (_permissionHrPeople.isModule) {
        if (!$(_hrPeopleDialogId).length) {
            $('<div id="' + _hrPeopleDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _hrPeopleModRootPath + 'searchForm',
            type: 'POST',
            dataType: 'json',
            data: $(_rootContainerId).find('#form-hr-people-init').serialize(),
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_hrPeopleDialogId).html(data.html);
                $(_hrPeopleDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: 800,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(_hrPeopleDialogId).dialog('close');
                    },
                    buttons: [
                        {text: data.btn_no, class: 'btn btn-default', click: function () {
                                $(_hrPeopleDialogId).empty().dialog('close');
                            }},
                        {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                                _initHrPeople({page: 0, searchQuery: $(_hrPeopleDialogId).find('form').serialize()});
                                $(_hrPeopleDialogId).empty().dialog('close');
                            }}

                    ]
                });
                $(_hrPeopleDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {
            $('.select2').select2();
            $('.radio, .checkbox').uniform();

            $('#liveCityId', _hrPeopleDialogId).on('change', function () {
                _initControlAddressHtml({parentId: $(this).val(), name: 'liveSoumId', initHtml: '_init-live-soum-html', rootContainer: _hrPeopleDialogId});
                _initControlAddressHtml({parentId: -1, name: 'liveStreetId', initHtml: '_init-live-street-html', readonly: true, rootContainer: _hrPeopleDialogId});
            });

            _initDate({loadName: '.init-date'});

            $('input[type="text"]').keypress(function () {
                if (event.keyCode == 13) {
                    _initHrPeople({page: 0, searchQuery: $(_hrPeopleDialogId).find('form').serialize()});
                    $(_hrPeopleDialogId).empty().dialog('close');
                }
            });
        });
    } else {
        _pageDeny();
    }
}

function _addFormHrPeople(param) {
    if (_permissionHrPeople.our.create) {
        $.ajax({
            url: _hrPeopleModRootPath + 'add',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _hrPeopleModId},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_rootContainerId).html(data.html);
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

            $('.select2').select2();
            $('.radio, .checkbox').uniform();
            $('.pickatime-limits').pickatime({
                min: [7, 30],
                max: [14, 0],
                formatSubmit: 'HH:i',
                hiddenName: true
            });

            $('#birthCityId', _rootContainerId).on('change', function () {
                _initControlAddressHtml({parentId: $(this).val(), name: 'birthSoumId', initHtml: '_init-birth-soum-html', rootContainer: _rootContainerId});
                _initControlAddressHtml({parentId: -1, name: 'birthStreetId', initHtml: '_init-birth-street-html', readonly: true, rootContainer: _rootContainerId});
            });

            $('#liveCityId', _rootContainerId).on('change', function () {
                _initControlAddressHtml({parentId: $(this).val(), name: 'liveSoumId', initHtml: '_init-live-soum-html', rootContainer: _rootContainerId});
                _initControlAddressHtml({parentId: -1, name: 'liveStreetId', initHtml: '_init-live-street-html', readonly: true, rootContainer: _rootContainerId});
            });

            /*
             * Tab 1
             * */
            _listsHrPeopleFamilyMember({peopleId: 0});
            _listsHrPeopleRelationMember({peopleId: 0});
            _listsHrPeopleWork({peopleId: 0});

            /*
             * Tab 2
             * */
            _listsHrPeopleEducation({peopleId: 0});
            _listsHrPeopleEducationDoctor({peopleId: 0});

            /*
             * Tab 3
             * */
            _listsHrPeopleCourse({peopleId: 0});
            _listsHrPeoplePositionRank({peopleId: 0});
            _listsHrPeopleEducationRank({peopleId: 0});
            _listsHrPeopleLanguage({peopleId: 0});

            /*
             * Tab 4
             * */
            _listsHrPeopleAward({peopleId: 0});

            /*
             * Tab 5
             * */
            _listsHrPeopleReport({peopleId: 0});

            /*
             * Tab 6
             * */
            _listsHrPeopleConviction({peopleId: 0});

//        

        });
    } else {
        _pageDeny();
    }
}
function _editFormHrPeople(param) {
    if ((_permissionHrPeople.our.update && param.createdUserId == _uIdCurrent) || (_permissionHrPeople.your.update && param.createdUserId != _uIdCurrent)) {
        $.ajax({
            url: _hrPeopleModRootPath + 'edit',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, modId: _hrPeopleModId, id: param.id},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_rootContainerId).html(data.html);
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

            $('#birthCityId', _rootContainerId).on('change', function () {
                _initControlAddressHtml({parentId: $(this).val(), name: 'birthSoumId', initHtml: '_init-birth-soum-html', rootContainer: _rootContainerId});
                _initControlAddressHtml({parentId: -1, name: 'birthStreetId', initHtml: '_init-birth-street-html', readonly: true, rootContainer: _rootContainerId});
            });

            $('#liveCityId', _rootContainerId).on('change', function () {
                _initControlAddressHtml({parentId: $(this).val(), name: 'liveSoumId', initHtml: '_init-live-soum-html', rootContainer: _rootContainerId});
                _initControlAddressHtml({parentId: -1, name: 'liveStreetId', initHtml: '_init-live-street-html', readonly: true, rootContainer: _rootContainerId});
            });

            /*
             * Tab 1
             * */
            _listsHrPeopleFamilyMember({peopleId: param.id});
            _listsHrPeopleRelationMember({peopleId: param.id});
            _listsHrPeopleWork({peopleId: param.id});

            /*
             * Tab 2
             * */
            _listsHrPeopleEducation({peopleId: param.id});
            _listsHrPeopleEducationDoctor({peopleId: param.id});

            /*
             * Tab 3
             * */
            _listsHrPeopleCourse({peopleId: param.id});
            _listsHrPeoplePositionRank({peopleId: param.id});
            _listsHrPeopleEducationRank({peopleId: param.id});
            _listsHrPeopleLanguage({peopleId: param.id});

            /*
             * Tab 4
             * */
            _listsHrPeopleAward({peopleId: param.id});

            /*
             * Tab 5
             * */
            _listsHrPeopleReport({peopleId: param.id});

            /*
             * Tab 6
             * */
            _listsHrPeopleConviction({peopleId: param.id});


            $('.select2').select2();
            $('.radio, .checkbox').uniform();
            // Display date dropdowns
//        $(".init-date").datepicker({
//            changeMonth: true,
//            changeYear: true,
//            numberOfMonths: 1,
//            dateFormat: _dateFormat
//        });

//        $('.init-date').pickadate({
//            labelMonthNext: _globalDatePickerNextMonth,
//            labelMonthPrev: _globalDatePickerPrevMonth,
//            labelMonthSelect: _globalDatePickerChooseMonth,
//            labelYearSelect: _globalDatePickerChooseYear,
//            selectMonths: true,
//            selectYears: true,
//            monthsFull: _globalDatePickerListMonth,
//            weekdaysShort: _globalDatePickerListWeekDayShort,
//            today: _globalDatePickerChooseToday,
//            clear: _globalDatePickerChooseClear,
//            close: _globalDatePickerChooseClose,
//            formatSubmit: 'yyyy-mm-dd',
//            format: 'yyyy-mm-dd',
//            container: '.my-external-container'
//        });
//
//        $('.pickatime-limits').pickatime({
//            min: [7, 30],
//            max: [14, 0],
//            formatSubmit: 'HH:i',
//            hiddenName: true
//        });
            $(".init-date").datepicker({
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            });
        });
    } else {
        _pageDeny();
    }
}
function _initControlAddressHtml(param) {
    $.ajax({
        type: 'post',
        url: _addressModRootPath + 'controlAddressDropDown',
        data: {parentId: param.parentId, selectedId: 0, name: param.name, disabled: param.disabled, readonly: param.readonly, required: param.required},
        dataType: 'json',
        success: function (data) {
            $('.' + param.initHtml).html(data);
        }
    }).done(function () {
        $.unblockUI();
        $('.select2').select2();
        $('#birthSoumId', param.rootContainer).on('change', function () {
            _initControlAddressHtml({parentId: $(this).val(), name: 'birthStreetId', initHtml: '_init-birth-street-html', rootContainer: param.rootContainer});
            $('input[name="birthAddress"]', param.rootContainer).attr('readonly', false);
        });
        $('#liveSoumId', param.rootContainer).on('change', function () {
            _initControlAddressHtml({parentId: $(this).val(), name: 'liveStreetId', initHtml: '_init-live-street-html', rootContainer: param.rootContainer});
            $('input[name="liveAddress"]', param.rootContainer).attr('readonly', false);
        });
    });
}
function _listsHrPeopleFamilyMember(param) {
    $.ajax({
        type: 'post',
        url: _hrPeopleModRootPath + 'listsHrPeopleFamilyMember',
        data: {peopleId: param.peopleId},
        dataType: 'json',
        success: function (data) {
            $('#init-hr-family-member').html(data);
        }
    }).done(function () {
        $('.select2').select2();
        $.unblockUI();
    });
}
function _removeTableFormRow(param) {

    var _this = $(param.elem);
    var _dialogId = '#dialog-table-form-row';

    if (!$(_dialogId).length) {
        $('<div id="' + _dialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $(_dialogId).empty().html(_dialogAlertDeleteMessage);
    $(_dialogId).dialog({
        cache: false,
        resizable: false,
        bgiframe: false,
        autoOpen: false,
        title: _dialogAlertTitle,
        width: _dialogAlertWidth,
        height: "auto",
        modal: true,
        close: function () {
            $(_dialogId).dialog('close').remove();
        },
        buttons: [
            {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                    $(_hrPeopleDialogId).dialog('close').empty();
                }},
            {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {
                    _this.parents('tr').remove()
                    $(_dialogId).dialog('close').empty();
                }}

        ]
    });
    $(_dialogId).dialog('open');
}
function _addHrPeopleFamilyMember(param) {

    var _string = _relation = _birthYear = '';
    var _dataNumber = parseInt($(_rootContainerId).find('.lists-hr-people-family-member tr').last().attr('data-number')) + 1;

    $.ajax({
        type: 'post',
        url: _hrPeopleModRootPath + 'controlBirthYearDropdown',
        data: {name: 'familyBirthYear[]', selectedId: 0},
        dataType: 'json',
        async: false,
        success: function (data) {
            _birthYear = data;
        }
    });

    $.ajax({
        type: 'post',
        url: 'shrPeopleRelation/controlHrPeopleRelationDropdown',
        data: {name: 'familyRelationId[]', selectedId: 0},
        dataType: 'json',
        async: false,
        success: function (data) {
            _relation = data;
        }
    });

    _string += '<tr data-number="' + _dataNumber + '">';
    _string += '<td class="_control"><input type="hidden" name="familyNumber[]" value="number' + _dataNumber + '">' + _relation + '</td>';
    _string += '<td class="_control"><input type="text" name="familyFLName[]" class="_control_input"></td>';
    _string += '<td class="_control">' + _birthYear + '</td>';
    _string += '<td class="_control"><input type="text" name="familyBirthAddress[]" class="_control_input"></td>';
    _string += '<td class="_control"><input type="text" name="familyLiveWork[]" class="_control_input"></td>';

    _string += '<td class="text-center">';
    _string += '<ul class="icons-list">';
    _string += '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
    _string += '</ul>';
    _string += '</td>';
    _string += '</tr>';
    $(_rootContainerId).find('.lists-hr-people-family-member').append(_string);
    $('.select2').select2();
}
function _listsHrPeopleRelationMember(param) {
    $.ajax({
        type: 'post',
        url: _hrPeopleModRootPath + 'listsHrPeopleRelationMember',
        data: {peopleId: param.peopleId},
        dataType: 'json',
        success: function (data) {
            $('#init-hr-relation-member').html(data);
        }
    }).done(function () {
        $('.select2').select2();
        $.unblockUI();
    });
}
function _addHrPeopleRelationMember(param) {
    var _string = _relation = _birthYear = '';
    var _dataNumber = parseInt($(_rootContainerId).find('.lists-hr-people-relation-member tr').last().attr('data-number')) + 1;
    $.ajax({
        type: 'post',
        url: _hrPeopleModRootPath + 'controlBirthYearDropdown',
        data: {name: 'relationBirthYear[]', selectedId: 0},
        dataType: 'json',
        async: false,
        success: function (data) {
            _birthYear = data;
        }
    });

    $.ajax({
        type: 'post',
        url: 'shrPeopleRelation/controlHrPeopleRelationDropdown',
        data: {name: 'relationRelationId[]', selectedId: 0},
        dataType: 'json',
        async: false,
        success: function (data) {
            _relation = data;
        }
    });

    _string += '<tr data-number="' + _dataNumber + '">';
    _string += '<td class="_control"><input type="hidden" name="relationNumber[]" value="number' + _dataNumber + '">' + _relation + '</td>';
    _string += '<td class="_control"><input type="text" name="relationFLName[]" class="_control_input"></td>';
    _string += '<td class="_control">' + _birthYear + '</td>';
    _string += '<td class="_control"><input type="text" name="relationBirthAddress[]" class="_control_input"></td>';
    _string += '<td class="_control"><input type="text" name="relationLiveWork[]" class="_control_input"></td>';

    _string += '<td class="text-center">';
    _string += '<ul class="icons-list">';
    _string += '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
    _string += '</ul>';
    _string += '</td>';
    _string += '</tr>';
    $(_rootContainerId).find('.lists-hr-people-relation-member').append(_string);
    $('.select2').select2();
}
function _listsHrPeopleEducation(param) {
    $.ajax({
        type: 'post',
        url: _hrPeopleModRootPath + 'listsHrPeopleEducation',
        data: {peopleId: param.peopleId},
        dataType: 'json',
        success: function (data) {
            $('#init-hr-people-education').html(data);
        }
    }).done(function () {
        $('.select2').select2();
        $('.radio, .checkbox').uniform();
        $(".init-date").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        });
        $.unblockUI();
        $.unblockUI();
    });
}
function _addHrPeopleEducation(param) {
    var _string = _educationRank = '';
    var _dataNumber = parseInt($(_rootContainerId).find('.lists-hr-people-education tr').last().attr('data-number')) + 1;
    $.ajax({
        type: 'post',
        url: _hrPeopleEducationRankMasterDataModRootPath + 'controlHrPeopleEducationRankMasterDataDropDown',
        data: {name: 'educationRankId[]', selectedId: 0},
        dataType: 'json',
        async: false,
        success: function (data) {
            _educationRank = data;
        }
    });
    _string += '<tr data-number="' + _dataNumber + '">';
    _string += '<td class="_control"><input type="hidden" name="educationAutoNumber[]" value="number' + _dataNumber + '"><input type="text" name="educationSchoolName[]" class="_control_input"></td>';
    _string += '<td class="_control"><input type="text" name="educationInDate[]" class="_control_input init-date"></td>';
    _string += '<td class="_control"><input type="text" name="educationOutDate[]" class="_control_input init-date"></td>';
    _string += '<td class="_control">' + _educationRank + '</td>';
    _string += '<td class="_control"><input type="text" name="educationProfession[]" class="_control_input"></td>';
    _string += '<td class="_control"><input type="text" name="educationDiplomNumber[]" class="_control_input"></td>';
    _string += '<td class="_control"><input type="text" name="educationDiplomDescription[]" class="_control_input"></td>';

    _string += '<td class="text-center">';
    _string += '<ul class="icons-list">';
    _string += '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
    _string += '</ul>';
    _string += '</td>';
    _string += '</tr>';

    $(_rootContainerId).find('.lists-hr-people-education').append(_string);
    $('.select2').select2();
    $('.radio, .checkbox').uniform();
    $(".init-date").datepicker({
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        dateFormat: _dateFormat
    });
    $.unblockUI();
}
function _listsHrPeopleEducationDoctor(param) {
    $.ajax({
        type: 'post',
        url: _hrPeopleModRootPath + 'listsHrPeopleEducationDoctor',
        data: {peopleId: param.peopleId},
        dataType: 'json',
        success: function (data) {
            $('#init-hr-people-education-doctor').html(data);
        }
    }).done(function () {
        $(".init-date").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        });
    });
}
function _addHrPeopleEducationDoctor(param) {
    var _string = '';
    var _dataNumber = parseInt($(_rootContainerId).find('.lists-hr-people-education-doctor tr').last().attr('data-number')) + 1;

    _string += '<tr data-number="' + _dataNumber + '">';
    _string += '<td class="_control"><input type="hidden" name="educationDoctorNumber[]" value="number' + _dataNumber + '"><input type="text" name="educationDoctorRank[]" class="_control_input"></td>';
    _string += '<td class="_control"><input type="text" name="educationDoctorIssuePalace[]" class="_control_input"></td>';
    _string += '<td class="_control"><input type="text" name="educationDoctorIssueDate[]" class="_control_input init-date"></td>';
    _string += '<td class="_control"><input type="text" name="educationDoctorDiplomNumber[]" class="_control_input"></td>';
    _string += '<td class="_control"><input type="text" name="educationDoctorDescription[]" class="_control_input"></td>';
    _string += '<td class="text-center">';
    _string += '<ul class="icons-list">';
    _string += '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
    _string += '</ul>';
    _string += '</td>';
    _string += '</tr>';

    $(_rootContainerId).find('.lists-hr-people-education-doctor').append(_string);
    $(".init-date").datepicker({
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        dateFormat: _dateFormat
    });
}
function _listsHrPeopleCourse(param) {
    $.ajax({
        type: 'post',
        url: _hrPeopleModRootPath + 'listsHrPeopleCourse',
        data: {peopleId: param.peopleId},
        dataType: 'json',
        success: function (data) {
            $('#init-hr-people-course').html(data);
        }
    }).done(function () {
        $(".init-date").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        });
    });
}
function _addHrPeopleCourse(param) {
    var _string = '';
    var _dataNumber = parseInt($(_rootContainerId).find('.lists-hr-people-course tr').last().attr('data-number')) + 1;
    _string += '<tr data-number="' + _dataNumber + '">';
    _string += '<td class="_control"><input type="hidden" name="courseAutoNumber[]" value="number' + _dataNumber + '"><input type="text" name="courseOrganizationTitle[]" class="_control_input"></td>';
    _string += '<td class="_control"><input type="text" name="courseInDate[]" class="_control_input init-date"></td>';
    _string += '<td class="_control"><input type="text" name="courseOutDate[]" class="_control_input init-date"></td>';
    _string += '<td class="_control"><input type="text" name="courseDuration[]" class="_control_input"></td>';
    _string += '<td class="_control"><input type="text" name="courseAbout[]" class="_control_input"></td>';
    _string += '<td class="_control"><input type="text" name="courseInfo[]" class="_control_input"></td>';
    _string += '<td class="text-center">';
    _string += '<ul class="icons-list">';
    _string += '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
    _string += '</ul>';
    _string += '</td>';
    _string += '</tr>';
    $(_rootContainerId).find('.lists-hr-people-course').append(_string);
    $(".init-date").datepicker({
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        dateFormat: _dateFormat
    });

}
function _listsHrPeoplePositionRank(param) {
    $.ajax({
        type: 'post',
        url: _hrPeopleModRootPath + 'listsHrPeoplePositionRank',
        data: {peopleId: param.peopleId},
        dataType: 'json',
        success: function (data) {
            $('#init-hr-people-position-rank').html(data);
        }
    }).done(function () {
        $.unblockUI();
    });
}

function _addHrPeoplePositionRank(param) {
    var _string = '';
    var _dataNumber = parseInt($(_rootContainerId).find('.lists-hr-people-position-rank tr').last().attr('data-number')) + 1;

    _string += '<tr data-number="' + _dataNumber + '">';
    _string += '<td class="_control"><input type="hidden" name="positionRankAutoNumber[]" value="number' + _dataNumber + '"><input type="text" name="positionRankRankLevel[]" class="_control_input"></td>';
    _string += '<td class="_control"><input type="text" name="positionRankRankName[]" class="_control_input"></td>';
    _string += '<td class="_control"><input type="text" name="positionRankDocumentInfo[]" class="_control_input"></td>';
    _string += '<td class="_control"><input type="text" name="positionRankCertNumber[]" class="_control_input"></td>';
    _string += '<td class="text-center">';
    _string += '<ul class="icons-list">';
    _string += '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
    _string += '</ul>';
    _string += '</td>';
    _string += '</tr>';

    $(_rootContainerId).find('.lists-hr-people-position-rank').append(_string);
}
function _listsHrPeopleEducationRank(param) {
    $.ajax({
        type: 'post',
        url: _hrPeopleModRootPath + 'listsHrPeopleEducationRank',
        data: {peopleId: param.peopleId},
        dataType: 'json',
        success: function (data) {
            $('#init-hr-people-education-rank').html(data);
        }
    }).done(function () {
        _initDate({loadName: '.init-date'});
    });
}
function _addHrPeopleEducationRank() {
    var _string = '';
    var _dataNumber = parseInt($(_rootContainerId).find('.lists-hr-people-education-rank tr').last().attr('data-number')) + 1;

    _string += '<tr data-number="' + _dataNumber + '">';
    _string += '<td class="_control"><input type="hidden" name="educationRankAutoNumber[]" value="number' + _dataNumber + '"><input type="text" name="educationRankTitle[]" class="_control_input"></td>';
    _string += '<td class="_control"><input type="text" name="educationRankRegisterOrganization[]" class="_control_input"></td>';
    _string += '<td class="_control"><input type="text" name="educationRankInDate[]" class="_control_input init-date"></td>';
    _string += '<td class="_control"><input type="text" name="educationRankAbout[]" class="_control_input"></td>';
    _string += '<td class="text-center">';
    _string += '<ul class="icons-list">';
    _string += '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
    _string += '</ul>';
    _string += '</td>';
    _string += '</tr>';

    $(_rootContainerId).find('.lists-hr-people-education-rank').append(_string);
    _initDate({loadName: '.init-date'});
}
function _listsHrPeopleLanguage(param) {
    $.ajax({
        type: 'post',
        url: _hrPeopleModRootPath + 'listsHrPeopleLanguage',
        data: {peopleId: param.peopleId},
        dataType: 'json',
        success: function (data) {
            $('#init-hr-people-language').html(data);
        }
    }).done(function () {
        $('.radio, .checkbox').uniform();
        $.unblockUI();
    });
}
function _addHrPeopleLanguage() {
    var _string = '';
    var _dataNumber = parseInt($(_rootContainerId).find('.lists-hr-people-language tr').last().attr('data-number')) + 1;

    _string += '<tr data-number="' + _dataNumber + '">';

    _string += '<td class="_control"><input type="hidden" name="languageAutoNumber[]" value="number' + _dataNumber + '"><input type="text" name="languageTitle[]" class="_control_input"></td>';

    _string += '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageListeningMiddle[]" class="language-listening language-listening-middle-' + _dataNumber + '" value="0"><input type="radio" class="radio" value="1" name="languageListening' + _dataNumber + '" onclick="_setPeopleLanguageValue({elem: this, addClass: \'language-listening-middle-' + _dataNumber + '\', removeClass: \'language-listening\'});"> Дунд</label></div></td>';
    _string += '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageListeningGood[]" class="language-listening language-listening-good-' + _dataNumber + '" value="0"><input type="radio" class="radio" value="2" name="languageListening' + _dataNumber + '" onclick="_setPeopleLanguageValue({elem: this, addClass: \'language-listening-good-' + _dataNumber + '\', removeClass: \'language-listening\'});"> Сайн</label></div></td>';
    _string += '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageListeningExcellent[]" class="language-listening language-listening-excellent-' + _dataNumber + '" value="0"><input type="radio" class="radio" value="3" name="languageListening' + _dataNumber + '" onclick="_setPeopleLanguageValue({elem: this, addClass: \'language-listening-excellent-' + _dataNumber + '\', removeClass: \'language-listening\'});"> Онц</label></div></td>';

    _string += '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageSpeakMiddle[]" class="language-speak language-speak-middle-' + _dataNumber + '" value="0"><input type="radio" class="radio" value="1" name="languageSpeak' + _dataNumber + '" onclick="_setPeopleLanguageValue({elem: this, addClass: \'language-speak-middle-' + _dataNumber + '\', removeClass: \'language-speak\'});"> Дунд</label></div></td>';
    _string += '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageSpeakGood[]" class="language-speak language-speak-good-' + _dataNumber + '" value="0"><input type="radio" class="radio" value="2" name="languageSpeak' + _dataNumber + '" onclick="_setPeopleLanguageValue({elem: this, addClass: \'language-speak-good-' + _dataNumber + '\', removeClass: \'language-speak\'});"> Сайн</label></div></td>';
    _string += '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageSpeakExcellent[]" class="language-speak language-speak-excellent-' + _dataNumber + '" value="0"><input type="radio" class="radio" value="3" name="languageSpeak' + _dataNumber + '" onclick="_setPeopleLanguageValue({elem: this, addClass: \'language-speak-excellent-' + _dataNumber + '\', removeClass: \'language-speak\'});"> Онц</label></div></td>';

    _string += '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageReadMiddle[]" class="language-read language-read-middle-' + _dataNumber + '" value="0"><input type="radio" class="radio" value="1" name="languageRead' + _dataNumber + '" onclick="_setPeopleLanguageValue({elem: this, addClass: \'language-read-middle-' + _dataNumber + '\', removeClass: \'language-read\'});"> Дунд</label></div></td>';
    _string += '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageReadGood[]" class="language-read language-read-good-' + _dataNumber + '" value="0"><input type="radio" class="radio" value="2" name="languageRead' + _dataNumber + '" onclick="_setPeopleLanguageValue({elem: this, addClass: \'language-read-good-' + _dataNumber + '\', removeClass: \'language-read\'});"> Сайн</label></div></td>';
    _string += '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageReadExcellent[]" class="language-read language-read-excellent-' + _dataNumber + '" value="0"><input type="radio" class="radio" value="3" name="languageRead' + _dataNumber + '" onclick="_setPeopleLanguageValue({elem: this, addClass: \'language-read-excellent-' + _dataNumber + '\', removeClass: \'language-read\'});"> Онц</label></div></td>';

    _string += '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageWriteMiddle[]" class="language-write language-write-middle-' + _dataNumber + '" value="0"><input type="radio" class="radio" value="1" name="languageWrite' + _dataNumber + '" onclick="_setPeopleLanguageValue({elem: this, addClass: \'language-write-middle-' + _dataNumber + '\', removeClass: \'language-write\'});"> Дунд</label></div></td>';
    _string += '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageWriteGood[]" class="language-write language-write-good-' + _dataNumber + '" value="0"><input type="radio" class="radio" value="2" name="languageWrite' + _dataNumber + '" onclick="_setPeopleLanguageValue({elem: this, addClass: \'language-write-good-' + _dataNumber + '\', removeClass: \'language-write\'});"> Сайн</label></div></td>';
    _string += '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageWriteExcellent[]" class="language-write language-write-excellent-' + _dataNumber + '" value="0"><input type="radio" class="radio" value="3" name="languageWrite' + _dataNumber + '" onclick="_setPeopleLanguageValue({elem: this, addClass: \'language-write-excellent-' + _dataNumber + '\', removeClass: \'language-write\'});"> Онц</label></div></td>';

    _string += '<td class="text-center">';
    _string += '<ul class="icons-list">';
    _string += '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
    _string += '</ul>';
    _string += '</td>';

    _string += '</tr>';

    $(_rootContainerId).find('.lists-hr-people-language').append(_string);
    $('.radio, .checkbox').uniform();
}

function _listsHrPeopleAward(param) {
    $.ajax({
        type: 'post',
        url: _hrPeopleModRootPath + 'listsHrPeopleAward',
        data: {peopleId: param.peopleId},
        dataType: 'json',
        success: function (data) {
            $('#init-hr-people-award').html(data);
        }
    }).done(function () {
        $('.select2').select2();
        _initDate({loadName: '.init-date'});
    });
}
function _addHrPeopleAward() {
    var _string = _controlAwardDropdown = '';

    $.ajax({
        type: 'post',
        url: _categoryModRootPath + 'controlCategoryListDropdown',
        data: {name: 'awardCatId[]', modId: 68, selectedId: 0},
        dataType: 'json',
        async: false,
        success: function (data) {
            _controlAwardDropdown = data;
        }
    });
    var _dataNumber = parseInt($(_rootContainerId).find('.lists-hr-people-award tr').last().attr('data-number')) + 1;

    _string += '<tr data-number="' + _dataNumber + '">';
    _string += '<td class="_control"><input type="hidden" name="awardAutoNumber[]" value="number' + _dataNumber + '">' + _controlAwardDropdown + '</td>';
    _string += '<td class="_control text-center"><input type="text" name="awardDate[]" class="_control_input init-date"></td>';
    _string += '<td class="_control text-center"><input type="text" name="awardTitle[]" class="_control_input"></td>';
    _string += '<td class="text-center">';
    _string += '<ul class="icons-list">';
    _string += '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
    _string += '</ul>';
    _string += '</td>';
    _string += '</tr>';

    $(_rootContainerId).find('.lists-hr-people-award').append(_string);
    $('.select2').select2();
    _initDate({loadName: '.init-date'});

}
function _listsHrPeopleReport(param) {
    $.ajax({
        type: 'post',
        url: _hrPeopleModRootPath + 'listsHrPeopleReport',
        data: {peopleId: param.peopleId},
        dataType: 'json',
        success: function (data) {
            $('#init-hr-people-report').html(data);
        }
    }).done(function () {
        $('.select2').select2();
        _initDate({loadName: '.init-date'});
    });
}
function _hrPeopleReportForm(param) {
    var _dialogAlertDialogId = '#hrPeopleReportForm';
    if (!$(_dialogAlertDialogId).length) {
        $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: _hrPeopleModRootPath + 'reportForm',
        data: {selectedId: param.selectedId, peopleId: param.peopleId},
        dataType: 'json',
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
                width: data.width,
                height: "auto",
                modal: true,
                close: function () {
                    $(_dialogAlertDialogId).dialog('close').empty();
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default legitRipple', click: function () {
                            $(_dialogAlertDialogId).dialog('close').empty();
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            
                            var _form = $(_dialogAlertDialogId).find('form');
                                $(_form).validate({errorPlacement: function () {
                                    }});
                                
                            $(_form).ajaxSubmit({
                                type: 'post',
                                url: _hrPeopleModRootPath + 'insertHrPeopleReport',
                                dataType: 'json',
                                data: _form.serialize(),
                                beforeSend: function () {
                                    $.blockUI({
                                        message: _jqueryBlockUiMessage,
                                        overlayCSS: _jqueryBlockUiOverlayCSS,
                                        css: _jqueryBlockUiMessageCSS
                                    });
                                },
                                success: function (data) {

                                    _PNotify({status: data.status, message: data.message});
                                    $(_dialogAlertDialogId).dialog('close').empty();
                                    _listsHrPeopleReport({peopleId: param.peopleId});
                                    $.unblockUI();
                                }
                            });
                            
                            $.unblockUI();

                        }}
                ]
            });
            $(_dialogAlertDialogId).dialog('open');

        }
    }).done(function () {
        $.unblockUI();
        _initDate({loadName: '.init-date'});
    });
}
function _showHrPeopleReport(param) {
    var _dialogAlertDialogId = '#showHrPeopleReport';
    if (!$(_dialogAlertDialogId).length) {
        $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: _hrPeopleModRootPath + 'reportShow',
        data: {selectedId: param.selectedId, peopleId: param.peopleId},
        dataType: 'json',
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
                width: data.width,
                height: "auto",
                modal: true,
                close: function () {
                    $(_dialogAlertDialogId).dialog('close').empty();
                }
            });
            $(_dialogAlertDialogId).dialog('open');

        }
    }).done(function () {
        $.unblockUI();
        _initDate({loadName: '.init-date'});
    });
}
function _deleteHrPeopleReport(param) {
    var _this = $(param.elem);
    var _root = _this.parent().parent();
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
                        url: _hrPeopleModRootPath + 'deleteHrPeopleReport',
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
                            _listsHrPeopleReport({peopleId: param.peopleId});
                            $.unblockUI();
                        }
                    });
                    $(_dialogAlertDialogId).empty().dialog('close');
                }}
        ]
    });
    $(_dialogAlertDialogId).dialog('open');
}
function _listsHrPeopleConviction(param) {
    $.ajax({
        type: 'post',
        url: _hrPeopleModRootPath + 'listsHrPeopleConviction',
        data: {peopleId: param.peopleId},
        dataType: 'json',
        success: function (data) {
            $('#init-hr-people-conviction').html(data);
        }
    }).done(function () {
        $('.select2').select2();
        _initDate({loadName: '.init-date'});
    });
}
function _addHrPeopleConviction() {
    var _string = '';
    var _dataNumber = parseInt($(_rootContainerId).find('.lists-hr-people-conviction tr').last().attr('data-number')) + 1;

    _string += '<tr data-number="' + _dataNumber + '">';
    _string += '<td class="_control"><input type="hidden" name="convictionAutoNumber[]" value="number' + _dataNumber + '"><input type="text" name="convictionInDate[]" class="_control_input init-date"></td>';
    _string += '<td class="_control"><input type="text" name="convictionTitle[]" class="_control_input"></td>';
    _string += '<td class="_control text-center"><input type="text" name="convictionDesription[]" class="_control_input"></td>';
    _string += '<td class="text-center">';
    _string += '<ul class="icons-list">';
    _string += '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
    _string += '</ul>';
    _string += '</td>';
    _string += '</tr>';

    $(_rootContainerId).find('.lists-hr-people-conviction').append(_string);
    $('.select2').select2();
    _initDate({loadName: '.init-date'});
}
function _setPeopleLanguageValue(param) {
    var _this = $(param.elem);
    var _tr = _this.parents('tr');
    $(_tr).find('.' + param.removeClass).val(0);
    $(_tr).find('.' + param.addClass).val(_this.val());
}
function _setPeopleWorkIsCurrentyValue(param) {
    var _this = $(param.elem);
    var _td = _this.closest('td');
    var _tr = _this.closest('tr');
    _tr.find('.work-out-date-' + _tr.attr('data-number')).val('');
    $('input[name="workIsCurrenty[]"]').val(0);
    _td.find('input[name="workIsCurrenty[]"]').val(1);

}

function _listsHrPeopleWork(param) {
    $.ajax({
        type: 'post',
        url: _hrPeopleModRootPath + 'listsHrPeopleWork',
        data: {peopleId: param.peopleId},
        dataType: 'json',
        success: function (data) {
            $('#init-hr-people-work').html(data);
        }
    }).done(function () {
        $('.select2').select2();
        $('.radio, .checkbox').uniform();
        $(".init-date").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        });
        $.unblockUI();
    });
}
function _addHrPeopleWork(param) {
    var _string = _workDepartment = _workPosition = _workRank = '';

    var _dataNumber = parseInt($(_rootContainerId).find('.lists-hr-people-work-tr').last().attr('data-number')) + 1;

    $.ajax({
        type: 'post',
        url: _hrPeopleDepartmentModRootPath + 'controlHrPeopleDepartmentDropdown',
        data: {name: 'workDepartmentId[]', modId: 69, selectedId: 0},
        dataType: 'json',
        async: false,
        success: function (data) {
            _workDepartment = data;
        }
    });

    $.ajax({
        type: 'post',
        url: _hrPeoplePositionModRootPath + 'controlHrPeoplePositionDropDown',
        data: {name: 'workPositionId[]', selectedId: 0},
        dataType: 'json',
        async: false,
        success: function (data) {
            _workPosition = data;
        }
    });

    $.ajax({
        type: 'post',
        url: _hrPeopleRankModRootPath + 'controlHrPeopleRankDropDown',
        data: {name: 'workRankId[]', selectedId: 0},
        dataType: 'json',
        async: false,
        success: function (data) {
            _workRank = data;
        }
    });

    _string += '<tr class="lists-hr-people-work-tr" data-number="' + _dataNumber + '">';
    _string += '<td class="_control"><input type="hidden" name="workAutoNumber[]" value="number' + _dataNumber + '">' + _workDepartment + '</td>';
    _string += '<td class="_control">' + _workPosition + '</td>';
    _string += '<td class="_control">' + _workRank + '</td>';
    _string += '<td class="_control"><input type="text" name="workInDate[]" class="_control_input init-date"></td>';
    _string += '<td class="_control"><input type="text" name="workOutDate[]" class="_control_input init-date work-out-date-' + _dataNumber + '"></td>';
    _string += '<td class="_control text-center"><input type="hidden" name="workIsCurrenty[]" value="0" class="is-currenty"><input type="radio" name="isCurrentyRB[]" value="0" class="radio" onclick="_setPeopleWorkIsCurrentyValue({elem: this, class: \'is-currenty\'});"></td>';
    _string += '<td class="_control"><input type="text" name="workTitle[]" class="_control_input"></td>';
    _string += '<td class="text-center">';
    _string += '<ul class="icons-list">';
    _string += '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
    _string += '</ul>';
    _string += '</td>';
    _string += '</tr>';

    $(_rootContainerId).find('.lists-hr-people-work').append(_string);
    $('.select2').select2();
    $('.radio, .checkbox').uniform();
    $(".init-date").datepicker({
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        dateFormat: _dateFormat
    });
    $.unblockUI();
}
function _insertHrPeople(param) {
    var _form = $(_hrPeopleFormMainId);

//    var _isSave = true;
//    $('input[name="isCurrentyRB[]"]').each(function(){
//        var _this = $(this);
//
//        if (_this.is(':checked')) {
//            var _tr = _this.closest('tr');
//            _tr.addClass('_error');
//            _isSave = false;
//        }
//        
//    });
    _form.validate({
        errorPlacement: function () {
        }});
    if (_form.valid()) {
        $.ajax({
            type: 'post',
            url: _hrPeopleModRootPath + 'insert',
            data: _form.serialize(),
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
                _initHrPeople({page: 0, searchQuery: {}});
                $.unblockUI();
            }
        });
    }
}

function _updateHrPeople(param) {
    var _form = $(_hrPeopleFormMainId);
    console.log($('input[name="isCurrentyRB[]"]'));
    _form.validate({
        errorPlacement: function () {
        }});
    if (_form.valid()) {
        $.ajax({
            type: 'post',
            url: _hrPeopleModRootPath + 'update',
            data: _form.serialize(),
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
                _initHrPeople({page: 0, searchQuery: {}});
                $.unblockUI();
            }
        });
    }
}

function _addControlHrPeople(param) {

    var _notSelectedId = $(param.elem).parents('.input-group').find('select').val();
    if (_notSelectedId != 0) {

        $('input[name="isMixx"]').val(1);
        var _mixCheckBox = $('input[name="mixCheckBox"]');
        _mixCheckBox.parent('span').addClass('checked');
        _mixCheckBox.prop("checked", true);

        $.ajax({
            type: 'post',
            url: _hrPeopleModRootPath + 'controlHrPeopleMultiListDropdown',
            data: {modId: param.modId, contId: param.contId, catId: param.catId, isDeleteButton: 1, name: param.name, initControlHtml: param.initControlHtml, isExtraValue: param.isExtraValue},
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $('#' + param.initControlHtml).append(data);
            }
        }).done(function () {
            $('.select2').select2();
            $.unblockUI();
            $('select[name="expertId[]"]').on('change', function () {
                var _isExtraExpertValue = false;
                $('select[name="expertId[]"]').each(function () {
                    var _thisVal = $(this).val();
                    if (_thisVal == '643' || _thisVal == '644' || _thisVal == '645' || _thisVal == '646') {
                        _isExtraExpertValue = true;
                    }
                });

                if (_isExtraExpertValue) {
                    $('#' + param.initControlHtml + 'Extra').removeClass('hide');
                    $('#' + param.initControlHtml + 'Extra').addClass('show');
                } else {
                    $('#' + param.initControlHtml + 'Extra').removeClass('show');
                    $('#' + param.initControlHtml + 'Extra').addClass('hide');
                    $('textarea[name="extraExpertValue"]').val('');
                }
            });
        });
    } else {
        if (!$(_dialogAlertDialogId).length) {
            $('<div id="' + _dialogAlertDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $(_dialogAlertDialogId).empty().html("<div class=\"p-2\">Та шинжээч, эмчийн мэдээлэл сонгоогүй байна.</div>");
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
                {text: _dialogAlertBtnClose, class: 'btn btn-primary active legitRipple', click: function () {
                        $(_dialogAlertDialogId).dialog('close').empty();
                    }}
            ]
        });
        $(_dialogAlertDialogId).dialog('open');
    }

}
function _removeControlHrPeople(param) {

    var _this = $(param.elem);
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
            $(_dialogAlertDialogId).dialog('close').empty();
        },
        buttons: [
            {text: _dialogAlertBtnNo, class: 'btn btn-default', click: function () {
                    $(_dialogAlertDialogId).dialog('close').empty();
                }},
            {text: _dialogAlertBtnYes, class: 'btn btn-primary active legitRipple', click: function () {

                    _this.parents('[data-hr-people-row="hr-people-row"]').remove();
                    $(_dialogAlertDialogId).dialog('close').empty();
                }}

        ]
    });
    $(_dialogAlertDialogId).dialog('open');
}