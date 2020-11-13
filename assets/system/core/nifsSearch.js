var _dgNifsSearch = '';
$(document).ready(function () {

    if (getUrlParameter('keyword') === undefined) {

    } else {
        _initNifsSearch({searchQuery: 'keyword=' + getUrlParameter('keyword')});
    }

    $('input[type="text"]').keypress(function () {
        if (event.keyCode == 13) {
            _nifsSearch({elem: this});
        }
    });
});

function _nifsSearch(param) {
    var _form = $('#form-nifs-search');
    $(_form).validate({errorPlacement: function () {
        }});
    if ($(_form).valid()) {
        _initNifsSearch({searchQuery: _form.serialize()});
    }
}

function _initNifsSearch(param) {

    var _string = '';
    var _stringHtml = '';
    var _height = $(_rootContainerId).height() - 100;
    var _width = $(_rootContainerId).width() - 30;

    var _param = $.parseParams(param.searchQuery);

    if (_param.keyword != '') {
        _string += '<span class="label label-default label-rounded">' + _param.keyword + '</span>';
    }
    if (_param.protocolNumber != '') {
        _string += '<span class="label label-default label-rounded">' + _param.protocolNumber + '</span>';
    }
    if (_param.keyword != '' || _param.protocolNumber != '') {
        _stringHtml = '<div class="_search-result-inner">Хайлтын үр дүн: ' + _string + '<a href="javascript:;" onclick="_nifsSearchEmptyPage();"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a></div>';
    }
    $('#window-nifs-search').html(_stringHtml + '<table id="dgNifsSearch" style="width:100%;"></table>');

    _dgNifsSearch = $('#dgNifsSearch').datagrid({
        url: '/snifsSearch/lists',
        method: 'get',
        queryParams: $.parseParams(param.searchQuery),
        title: 'Нэгдсэн хайлтын үр дүн',
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
        columns: [[
                {field: 'number', title: '#'},
                {field: 'create_number_html', title: 'Бүртгэл, №', width: 200},
                {field: 'in_out_date', title: 'Огноо', width: 96},
                {field: 'full_name', title: 'Товч тайлбар', width: 150},
                {field: 'partner', title: 'Эрх бүхий байгууллага', width: 150},
                {field: 'description', title: 'Тайлбар', width: 150},
                {field: 'close_info', title: 'Хаалт', width: 200}
            ]],
        onHeaderContextMenu: function (e, field) {
            e.preventDefault();
        },
        onRowContextMenu: function (e, index, row) {
            e.preventDefault();
        }, onDblClickRow: function () {
            var _row = _dgNifsSearch.datagrid('getSelected');
            if (_row.mod_id == 33) {
                window.location.replace('/snifsCrime/index/5?selectedId=' + _row.id + '&inDate=' + _row.in_date + '&outDate=' + _row.out_date + '&departmentId=' + _row.department_id + '&keyword=' + _param.keyword + '&protocolNumber=' + _param.protocolNumber);
            }

            if (_row.mod_id == 50) {
                window.location.replace('/snifsFileFolder/index/14?selectedId=' + _row.id + '&inDate=' + _row.in_date + '&outDate=' + _row.out_date + '&departmentId=' + _row.department_id + '&keyword=' + _param.keyword + '&protocolNumber=' + _param.protocolNumber);
            }

            if (_row.mod_id == 52) {
                window.location.replace('/snifsAnatomy/index/15?selectedId=' + _row.id + '&inDate=' + _row.in_date + '&outDate=' + _row.out_date + '&departmentId=' + _row.department_id + '&keyword=' + _param.keyword + '&protocolNumber=' + _param.protocolNumber);
            }

            if (_row.mod_id == 55) {
                window.location.replace('/snifsExtra/index/22?selectedId=' + _row.id + '&inDate=' + _row.in_date + '&outDate=' + _row.out_date + '&departmentId=' + _row.department_id + '&keyword=' + _param.keyword + '&protocolNumber=' + _param.protocolNumber);
            }

            if (_row.mod_id == 51) {
                window.location.replace('/snifsDoctorView/index/16?selectedId=' + _row.id + '&inDate=' + _row.in_date + '&outDate=' + _row.out_date + '&departmentId=' + _row.department_id + '&keyword=' + _param.keyword + '&protocolNumber=' + _param.protocolNumber);
            }

        }
    });

}

function _nifsSearchEmptyPage(param) {
    $.ajax({
        url: '/snifsSearch/emptyPage',
        type: 'POST',
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: _jqueryBlockUiMessage,
                overlayCSS: _jqueryBlockUiOverlayCSS,
                css: _jqueryBlockUiMessageCSS
            });
        },
        success: function (data) {
            $('#window-nifs-search').html(data);
            $('input[name="keyword"]').val('');
            $('input[name="protocolNumber"]').val('');
            $.unblockUI();
        },
        error: function () {
            $.unblockUI();
        }
    });
}