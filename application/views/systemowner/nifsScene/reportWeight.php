<div id="window-content"><?php echo $dataHtml;?></div>
<script type="text/javascript">
    var windowId = "#window-content";
    var uIdCurrent = <?php echo $this->session->adminUserId; ?>;
    $(function () {
        $.contextMenu({
            selector: '.context-menu-selected-expert-row',
            callback: function (key, options) {
                var _tr = $(this).parents('tr');
                
                if (key === 'read') {
                    window.location = '<?php echo Scrime::$path . 'index/' . $modId;?>?expertId=' + _tr.attr('data-expert-id') + '&departmentCatId=' + _tr.attr('data-department-cat-id') + '&startDate=' + _tr.attr('data-start-date') + '&endDate=' + _tr.attr('data-end-date');
                }
            },
            items: {
                "read": {name: "Шинжилгээг харах", icon: "search"}
            }
        });
    });

    function _advensedSearch(elem) {
        var dialogId = 'advencedSearchDialog';
        if (!$('#' + dialogId).length) {
            $('<div id="' + dialogId + '"></div>').appendTo('body');
            $.ajax({
                url: '<?php echo Scrime::$path; ?>searchReportForm',
                type: 'POST',
                dataType: 'json',
                data: {modId: <?php echo $modId; ?>, path: '<?php echo $path; ?>'},
                beforeSend: function () {
                    $.blockUI({message: null});
                },
                success: function (data) {
                    $('#' + dialogId).html(data.html);
                    $('#' + dialogId).dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.title,
                        width: data.width,
                        height: "auto",
                        modal: true,
                        dialogClass: 'dialog-blue',
                        close: function () {
                            $('#' + dialogId).dialog('close');
                        },
                        buttons: [
                            {text: data.btn_yes, class: 'btn btn-xs btn-success', click: function () {
                                    var _form = $('#' + dialogId).find('form');
                                    _form.submit();
                                    //$('#' + dialogId).dialog('close');
                                }},
                            {text: data.btn_no, class: 'btn  btn-default btn-xs', click: function () {
                                    $('#' + dialogId).dialog('close');
                                }}
                        ]
                    });
                    $('#' + dialogId).dialog('open');
                    $.unblockUI();
                },
                error: function () {
                    $.unblockUI();
                }
            }).done(function (data) {
                $('.select2').select2();

                var _dateFormat = "yy-mm-dd";
                var _from = $("#startDate").datepicker({
                    changeMonth: true,
                    numberOfMonths: 1,
                    dateFormat: _dateFormat
                }).on("change", function () {
                    _to.datepicker("option", "minDate", getDate(this));
                });
                var _to = $("#endDate").datepicker({
                    changeMonth: true,
                    numberOfMonths: 1,
                    dateFormat: _dateFormat
                }).on("change", function () {
                    _from.datepicker("option", "maxDate", getDate(this));
                });

                function getDate(element) {
                    var date;
                    try {
                        date = $.datepicker.parseDate(_dateFormat, element.value);
                    } catch (error) {
                        date = null;
                    }

                    return date;
                }
                
            });
        } else {
            $('#' + dialogId).dialog('open');
        }
    }
</script>