function _initDocClose(param) {

    if (!$(_docCloseDialogId).length) {
        $('<div id="' + _docCloseDialogId.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        url: _docCloseModRootPath + 'basket',
        type: 'POST',
        dataType: 'json',
        data: {docDetialId: param.docDetialId, docCloseId: param.docCloseId, type: param.type},
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $(_docCloseDialogId).empty().html(data.html);
            $(_docCloseDialogId).dialog({
                cache: false,
                resizable: false,
                bgiframe: false,
                autoOpen: false,
                title: data.title,
                width: data.width,
                height: "auto",
                modal: true,
                close: function () {
                    $(_docCloseDialogId).empty().dialog('close');
                },
                buttons: [
                    {text: data.btn_no, class: 'btn btn-default', click: function () {
                            $(_docCloseDialogId).empty().dialog('close');
                        }},
                    {text: data.btn_yes, class: 'btn btn-primary active legitRipple', click: function () {
                            var _form = $(_docCloseDialogId).find('form');
                            $(_form).validate({errorPlacement: function () {
                                }});
                            if ($(_form).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: _docCloseModRootPath + 'update',
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
                                        console.log(data);
                                        $(_docCloseDialogId).empty().dialog('close');
                                        _PNotify({status: data.status, message: data.message});
                                        if (param.type == 'doc-in') {
                                            _initDocIn({page: 0, searchQuery: {}});
                                        } else if (param.type == 'doc-out') {
                                            _initDocOut({page: 0, searchQuery: {}});
                                        }
                                        $.unblockUI();
                                    }
                                });
                            }

                        }}
                ]
            });
            $(_docCloseDialogId).dialog('open');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    }).done(function (data) {

        _initDocCloseDatagrid({searchQuery: $(_docCloseDialogId).find('form#form-doc-close').serialize()});

        $('#departmentId').on('change', function () {
            var _this = $(this);
            if (_this.val() == 60) {
                $.ajax({
                    url: _partnerModRootPath + 'controlPartnerDropdown',
                    type: 'GET',
                    dataType: 'json',
                    data: {modId: _partnerModId, selectedId: 0, name: 'partnerId'},
                    beforeSend: function () {
                        $.blockUI({
                            message: _jqueryBlockUiMessage,
                            overlayCSS: _jqueryBlockUiOverlayCSS,
                            css: _jqueryBlockUiMessageCSS
                        });
                    },
                    success: function (data) {
                        $('#init-control-partner-people-doc-close-html').html(data + '<input type="hidden" name="peopleId" value="0">');
                    }
                }).done(function () {
                    $('.select2').select2();
                    $.unblockUI();
                });
            } else {
                $.ajax({
                    url: _hrPeopleModRootPath + 'controlHrPeopleListDropdown',
                    type: 'POST',
                    dataType: 'json',
                    data: {name: 'fromPeopleId', departmentId: _this.val(), selectedId: 0},
                    beforeSend: function () {
                        $.blockUI({
                            message: _jqueryBlockUiMessage,
                            overlayCSS: _jqueryBlockUiOverlayCSS,
                            css: _jqueryBlockUiMessageCSS
                        });
                    },
                    success: function (data) {
                        $('#init-control-partner-people-doc-close-html').html(data + '<input type="hidden" name="partnerId" value="0">');
                    }
                }).done(function () {
                    $('.select2').select2();
                    $.unblockUI();
                });
            }
        });

        $('.select2').select2();
        $('.radio, .checkbox').uniform();
        $('input[name="docNumber"]').focus();
        _initDate({loadName: '.init-date'});

        $.ajax({
            url: _docCloseModRootPath + 'getData',
            type: 'POST',
            dataType: 'json',
            data: {selectedId: param.docCloseId},
            success: function (data) {
                var _html = '';
                console.log(data.id);
                $('input[name="docCloseId"]').val(data.id);
                _html += '<span class="_search-result-inner">';
                _html += '<span class="label label-default label-rounded">' + data.doc_date + ' өдрийн ' + data.doc_number + ' тоот албан бичиг</span>';
                _html += '<a href="javascript:;" onclick="_clearDocClose({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
                _html += '</span>';
                if (data.id.lenght > 0) {
                    $('#selected-basket-doc').html(_html);
                }
                
            }
        });
    });

}
function _initDocCloseDatagrid(param) {

    _dgDocClose = $('#dgDocClose').datagrid({
        url: _docCloseModRootPath + 'lists',
        method: 'get',
        queryParams: $.parseParams(param.searchQuery),
        iconCls: 'icon-save',
        pageList: [10, 20, 50, 100, 110, 120, 150, 200],
        pageSize: 100,
        fitColumns: true,
        rownumbers: false,
        width: 723,
        height: 392,
        singleSelect: true,
        pagination: true,
        loadMsg: 'Боловсруулж байна...',
        columns: [[
                {field: 'num', title: '#',
                    styler: function (value, row, index) {
                        return row.row_status;

                    }},
                {field: 'doc_number', title: 'Дугаар', width: 60, align: 'center'},
                {field: 'doc_date', title: 'Огноо', width: 100},
                {field: 'department', title: 'Салбар, хэлтэс', width: 300},
                {field: 'description', title: 'Товч агуулга', width: 300}
            ]],
        onHeaderContextMenu: function (e, field) {
            e.preventDefault();
        },
        onRowContextMenu: function (e, index, row) {
            e.preventDefault();
        },
        onLoadSuccess: function (data) {
            $('.pagination-info').prepend('<span class="pr-2 _definition"><span class="_definition-space"><span class="_definition-box" style="background-color:#4CAF50;"></span> - Хугацаа хэтэрч хаагдсан</span><span class="_definition-space"><span class="_definition-box" style="background-color:#F44336;"></span> - Хугацаа хэтэрсэн хаагдаагүй</span></span>');

        }, onDblClickRow: function () {
            var _html = '';
            var _row = _dgDocClose.datagrid('getSelected');
            $('input[name="docCloseId"]').val(_row.id);
            _html += '<span class="_search-result-inner">';
            _html += '<span class="label label-default label-rounded">' + _row.doc_date + ' өдрийн ' + _row.doc_number + ' тоот албан бичиг</span>';
            _html += '<a href="javascript:;" onclick="_clearDocClose({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            _html += '</span>';
            $('#selected-basket-doc').html(_html);
        }
    });
}

function _clearDocClose() {
    $('input[name="docCloseId"]').val(0);
    $('#selected-basket-doc').find('._search-result-inner').remove();
}