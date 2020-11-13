<div id="window-container"></div>
<script type="text/javascript">
    var windowId = '#window-container';
    var formId = '#form-reservation';
    $(function () {
        _loadCheckDate();
    });

    function _loadCheckDate() {
        var dialogCheckId = 'loadCheckDateDialog';
        if (!$("#" + dialogCheckId).length) {
            $('<div id="' + dialogCheckId + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: '<?php echo Sreservation::$path; ?>formCheckDate',
            dataType: "json",
            beforeSend: function () {
                $.blockUI({message:null});
            },
            success: function (data) {
                $("#" + dialogCheckId).empty().html(data.html);
                $("#" + dialogCheckId).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 500,
                    height: "auto",
                    modal: true,
                    dialogClass: 'dialog-blue',
                    close: function () {
                        $("#" + dialogCheckId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_yes, class: 'btn btn-xs btn-success', click: function () {
                                var _elem = {modId:<?php echo $modId; ?>, organizationId: $("#" + dialogCheckId).find('input[name="organizationId"]').val(), dateIn: $("#" + dialogCheckId).find('#dateIn').val(), dateOut: $("#" + dialogCheckId).find('#dateOut').val(), reservationId: 0};
                                if (_elem.organizationId.length > 0 && _elem.dateIn.length > 0 && _elem.dateOut.length > 0) {
                                    $.ajax({
                                        type: 'post',
                                        url: '<?php echo Sreservation::$path; ?>resultCheckDate',
                                        dataType: "json",
                                        data: _elem,
                                        beforeSend: function () {
                                            $.blockUI({message:null});
                                        },
                                        success: function (data) {
                                            if (data.reservation === true) {
                                                $(windowId).html(data.html);
                                            } else {
                                                _dialog({
                                                    dialogId: 'alertReservationDialog',
                                                    message: data.html,
                                                    title: data.title,
                                                    width: 300,
                                                    class: 'dialog-blue dialog-notification',
                                                    isButton: [{
                                                            text: 'Хаах',
                                                            class: 'btn btn-xs btn-default',
                                                            click: function () {
                                                                $("#alertReservationDialog").dialog('close');
                                                                window.location = '<?php echo Sreservation::$path . 'index/' . $modId; ?>';
                                                            }
                                                        }]
                                                });
                                            }
                                            $("#" + dialogCheckId).empty().dialog('close');
                                            $.unblockUI();
                                        }
                                    }).done(function (data) {
                                        $('.styled', windowId).uniform({
                                            radioClass: 'choice'
                                        });
                                        $('.select').select2();
                                    });
                                } else {
                                    _dialog({
                                        dialogId: 'alertDialog',
                                        message: 'Захиалга хийх хугацаагаа дахин шалгана уу',
                                        title: 'Санамж',
                                        width: 300,
                                        class: 'dialog-blue dialog-notification',
                                        isButton: [{
                                                text: 'Хаах',
                                                class: 'btn btn-xs btn-default',
                                                click: function () {
                                                    $("#alertDialog").dialog('close');
                                                }
                                            }]
                                    });
                                }
                            }},
                        {text: data.btn_no, class: 'btn btn-default btn-xs', click: function () {
                                $("#" + dialogCheckId).empty().dialog('close');
                                window.location = '<?php echo Sreservation::$path . 'index/' . $modId; ?>';
                            }}
                    ]
                });
                $("#" + dialogCheckId).dialog('open');

                $.unblockUI();
            }
        }).done(function (data) {
            $('.styled').uniform({
                radioClass: 'choice'
            });
            var _dateFormat = "yy-mm-dd",
                    from = $("#dateIn")
                    .datepicker({
                        changeMonth: true,
                        numberOfMonths: 1,
                        dateFormat: _dateFormat
                    })
                    .on("change", function () {
                        to.datepicker("option", "minDate", getDate(this));
                    }),
                    to = $("#dateOut").datepicker({
                changeMonth: true,
                numberOfMonths: 1,
                dateFormat: _dateFormat
            })
                    .on("change", function () {
                        from.datepicker("option", "maxDate", getDate(this));
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
    }

    function _bld(elem) {
        var _this = $(elem);
        var _tr = _this.parents('tr');
        var _adult = _tr.find('input[name="adult[]"]');
        var _cook = _tr.find('.cook');
        var _isStaff = _tr.find('input[name="isStaff[]"]');
        var _checkBox = _tr.find('input[type="checkbox"]');
        if (parseInt(_this.val()) > 0) {
            _cook.attr('disabled', false).val(_this.val());
            _adult.attr('disabled', false).val(_this.val());
            _isStaff.attr('disabled', false).val(0);
            _checkBox.attr('disabled', false);
        } else {
            _cook.attr('disabled', true).val('');
            _adult.attr('disabled', true).val('');
            _isStaff.attr('disabled', true).val('');
            _checkBox.attr('disabled', true);
        }
        $('.styled').uniform({
            radioClass: 'choice'
        });
    }

    function _isStaff(elem) {
        var _this = $(elem);
        var isStaff = _this.parents('td').find('input[name="isStaff[]"]');
        if (_this.is(":checked")) {
            isStaff.attr('disabled', false).val(1);
        } else {
            isStaff.attr('disabled', true).val('');
        }
    }

    function _insertReservation(elem) {
        $(formId, windowId).validate({errorPlacement: function () {}});
        if ($(formId, windowId).valid()) {
            $.ajax({
                type: 'post',
                url: '<?php echo Sreservation::$path; ?>insert',
                data: $(formId, windowId).serialize() + '&' + elem,
                dataType: 'json',
                beforeSend: function () {
                    $.blockUI({message: null});
                },
                success: function (data) {
                    if (data.status === 'success') {
                        new PNotify({
                            text: data.message,
                            addclass: 'bg-success'
                        });
                        window.location.href = '<?php echo Sreservation::$path . 'index/' . $modId; ?>';
                    } else {
                        new PNotify({
                            text: data.message,
                            addclass: 'bg-danger'
                        });
                    }
                    $.unblockUI();
                }
            });
        }
    }

    function _confirmForm(elem) {

        var _adultGuide = $(windowId).find('.adult-guide');
        var _isSelected = false;
        for (i = 0; i < _adultGuide.length; i++) {
            if (parseInt($(_adultGuide[i]).val()) > 0) {
                _isSelected = true;
            }
        }

        if (_isSelected) {
            var dialogId = 'checkReservationDialog';
            if (!$("#" + dialogId).length) {
                $('<div id="' + dialogId + '"></div>').appendTo('body');
            }
            $.ajax({
                type: 'post',
                url: '<?php echo Sreservation::$path; ?>formCheckReservation',
                dataType: "json",
                beforeSend: function () {
                    $.blockUI({message:null});
                },
                success: function (data) {
                    $("#" + dialogId).empty().html(data.html);
                    $("#" + dialogId).dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.title,
                        width: 500,
                        height: "auto",
                        modal: true,
                        dialogClass: 'dialog-blue',
                        close: function () {
                            $("#" + dialogId).empty().dialog('close');
                        },
                        buttons: [
                            {text: data.btn_yes, class: 'btn btn-xs btn-success', click: function () {
                                    var _confirmForm = $('#' + dialogId).find('#form-confim-reservation');
                                    _confirmForm.validate({errorPlacement: function () {}});
                                    if (_confirmForm.valid()) {
                                        _insertReservation(_confirmForm.serialize());
                                        $("#" + dialogId).empty().dialog('close');
                                    }
                                }},
                            {text: data.btn_no, class: 'btn btn-default btn-xs', click: function () {
                                    $("#" + dialogId).empty().dialog('close');
                                    window.location = '<?php echo Sreservation::$path . 'index/' . $modId; ?>';
                                }}
                        ]
                    });
                    $("#" + dialogId).dialog('open');
                    $.unblockUI();
                }
            }).done(function () {
                $('.select').select2();
            });
        } else {
            _dialog({
                dialogId: 'alertDialog',
                message: 'Та сууцны мэдээлэл сонгоогүй байна',
                title: 'Санамж',
                width: 300,
                class: 'dialog-blue dialog-notification',
                isButton: [{
                        text: 'Хаах',
                        class: 'btn btn-xs btn-default',
                        click: function () {
                            $("#alertDialog").dialog('close');
                        }
                    }]
            });
        }

    }

    function _updateReservationDtl() {
    
    }
</script>