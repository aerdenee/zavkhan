<div id="window-reservation"><?php echo $dataHtml; ?></div>
<script type="text/javascript">
    var windowId = "#window-reservation";
    $(function () {

        $.contextMenu({
            selector: '.reservation-dtl-selected-row',
            callback: function (key, options) {
                var _tr = $(this).parents('tr');

                if (key === 'delete') {
                    _removeReservationDtlItem(_tr.attr('data-id'), _tr.attr('data-reservation-id'));
                }
            },
            items: {
                "delete": {name: "Устгах", icon: "trash"}
            }
        });

        $.contextMenu({
            selector: '.context-menu-selected-row',
            callback: function (key, options) {
                var _tr = $(this).parents('tr');
                if (key === 'add') {
                    window.location.href = '<?php echo Sreservation::$path . 'add/' . $modId; ?>';
                }
                if (key === 'edit') {
                    _editRow(_tr.attr('data-id'));
                }
                if (key === 'delete') {
                    _removeItem(_tr.attr('data-id'));
                }
            },
            items: {
                "add": {name: "Шинэ захиалга", icon: "plus"},
                "edit": {name: "Захиалга засах", icon: "edit"},
                "delete": {name: "Захиалга устгах", icon: "trash"}
            }
        });
    });

    function _editRow(id) {
        $.ajax({
            url: '<?php echo Sreservation::$path; ?>edit',
            type: 'POST',
            dataType: 'json',
            data: {id: id, modId: <?php echo $modId; ?>},
            beforeSend: function () {
                $.blockUI({message: null});
            },
            success: function (data) {
                $(windowId).html(data.html);
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function () {
            $('.styled', windowId).uniform({
                radioClass: 'choice',
                wrapperClass: 'border-primary text-primary'
            });
            $('.select').select2();
        });
    }

    function _updateReservation(elem) {

        var _form = $(windowId).find('#form-reservation');

        _form.validate({errorPlacement: function () {}});
        if (_form.valid()) {
            $.ajax({
                type: 'post',
                url: '<?php echo Sreservation::$path; ?>update',
                data: _form.serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $.blockUI({message: null});
                },
                success: function (data) {
                    if (data.status === 'success') {
                        new PNotify({
                            title: data.title,
                            text: data.message,
                            addclass: 'bg-success'
                        });
                        window.location.href = '<?php echo Sreservation::$path . 'index/' . $modId; ?>';
                    } else {
                        new PNotify({
                            title: data.title,
                            text: data.message,
                            addclass: 'bg-danger'
                        });
                    }
                    $.unblockUI();
                }
            });
        }
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
            isStaff.val(0);
        }
    }

    function _setPartnerValue(elem) {

        var dialogId = 'removeDialog';
        if (!$("#" + dialogId).length) {
            $('<div id="' + dialogId + '"></div>').appendTo('body');
        }
        $("#" + dialogId).empty().html("Та аялалын холбоо барих мэдээллийг өөрчлөхдөө итгэлтэй байна уу?");
        $("#" + dialogId).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: "Сануулга",
            width: 300,
            height: "auto",
            modal: true,
            dialogClass: 'dialog-blue',
            close: function () {
                $("#" + dialogId).empty().dialog('close');
            },
            buttons: [
                {text: "Тийм", class: 'btn btn-xs btn-success', click: function () {

                        var _this = $(windowId);
                        if ($(elem).val() == '0' || $(elem).val() == '') {
                            _this.find('#partnername').fadeOut().removeClass('hide').addClass('show');
                            _this.find('#partnerTitle').val('');
                            _this.find('#managerName').val('');
                            _this.find('#managerPhone').val('');
                            _this.find('#email').val('');
                        } else {
                            _this.find('#partnername').fadeOut().removeClass('show').addClass('hide');
                            $.ajax({
                                type: 'post',
                                url: '<?php echo Sreservation::$path; ?>getPartnerInformation',
                                dataType: "json",
                                data: {partnerId: $(elem).val()},
                                beforeSend: function () {
                                    $.blockUI({message: null});
                                },
                                success: function (data) {
                                    _this.find('#partnerTitle').val(data.title);
                                    _this.find('#managerName').val(data.manager_name);
                                    _this.find('#managerPhone').val(data.manager_phone);
                                    _this.find('#email').val(data.email);
                                    $.unblockUI();
                                }
                            });
                        }


                        $("#" + dialogId).dialog('close');
                    }},
                {text: "Үгүй", class: 'btn btn-xs btn-default', click: function () {
                        $("#" + dialogId).dialog('close');
                    }}
            ]
        });
        $("#" + dialogId).dialog('open');
    }

    function _removeItem(id) {
        var dialogId = 'removeDialog';
        if (!$("#" + dialogId).length) {
            $('<div id="' + dialogId + '"></div>').appendTo('body');
        }
        $("#" + dialogId).empty().html("Та энэ мэдээллийг устгахдаа итгэлтэй байна");
        $("#" + dialogId).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: "Сануулга",
            width: 300,
            height: "auto",
            modal: true,
            dialogClass: 'dialog-blue',
            close: function () {
                $("#" + dialogId).empty().dialog('close');
            },
            buttons: [
                {text: "Тийм", class: 'btn btn-xs btn-success', click: function () {
                        $.ajax({
                            type: 'post',
                            url: '<?php echo Sreservation::$path; ?>delete',
                            dataType: "json",
                            data: {id: [id]},
                            beforeSend: function () {
                                $.blockUI({message: null});
                            },
                            success: function (data) {
                                if (data.status === 'success') {
                                    new PNotify({
                                        text: data.message,
                                        addclass: 'bg-success'
                                    });
                                    window.location = '<?php echo Sreservation::$path . 'index/' . $modId; ?>';
                                } else {
                                    new PNotify({
                                        text: data.message,
                                        addclass: 'bg-danger'
                                    });
                                }
                                $.unblockUI();
                            }
                        });
                        $("#" + dialogId).dialog('close');
                    }},
                {text: "Үгүй", class: 'btn btn-xs btn-default', click: function () {
                        $("#" + dialogId).dialog('close');
                    }}
            ]
        });
        $("#" + dialogId).dialog('open');

    }

    function _removeReservationDtlItem(reservationDtlId, reservationId) {

        var dialogId = 'removeReservationDtlDialog';
        if (!$("#" + dialogId).length) {
            $('<div id="' + dialogId + '"></div>').appendTo('body');
        }

        $("#" + dialogId).empty().html("Та энэ мэдээллийг устгахдаа итгэлтэй байна");
        $("#" + dialogId).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: "Сануулга",
            width: 300,
            height: "auto",
            modal: true,
            dialogClass: 'dialog-blue',
            close: function () {
                $("#" + dialogId).empty().dialog('close');
            },
            buttons: [
                {text: "Тийм", class: 'btn btn-xs btn-success', click: function () {
                        $.ajax({
                            type: 'post',
                            url: '<?php echo Sreservation::$path; ?>deleteReservationDtl',
                            dataType: "json",
                            data: {id: reservationDtlId, reservationId: reservationId},
                            beforeSend: function () {
                                $.blockUI({message: null});
                            },
                            success: function (data) {
                                if (data.status === 'success') {
                                    new PNotify({
                                        text: data.message,
                                        addclass: 'bg-success'
                                    });
                                    window.location = '<?php echo Sreservation::$path . 'index/' . $modId; ?>';
                                } else {
                                    new PNotify({
                                        text: data.message,
                                        addclass: 'bg-danger'
                                    });
                                }
                                $.unblockUI();
                            }
                        });
                        $("#" + dialogId).dialog('close');
                    }},
                {text: "Үгүй", class: 'btn btn-xs btn-default', click: function () {
                        $("#" + dialogId).dialog('close');
                    }}
            ]
        });
        $("#" + dialogId).dialog('open');
    }

    function _export(elem) {
        var _this = $(elem);
        var _form = _this.parents('form');
        $.fileDownload('/sreservation/export', {
            httpMethod: "POST",
            data: _form.serialize()
        }).done(function () {
            alert('ok download');
            var dialogId = 'alertDialog';
            if (!$('#' + dialogId).length) {
                $('<div id="' + dialogId + '"></div>').appendTo('body');
            }
            $('#' + dialogId).html('Захиалгыг амжилттай excel файл болгон хөрвүүллээ');

            $('#' + dialogId).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Санамж',
                width: 300,
                height: "auto",
                modal: true,
                dialogClass: 'dialog-blue dialog-notification',
                close: function () {
                    $('#' + dialogId).empty().dialog('close');
                },
                buttons: [
                    {text: "Хаах", class: 'btn blue btn-xs', click: function () {
                            $('#' + dialogId).dialog('close');
                        }}
                ]
            });
            $('#' + dialogId).dialog('open');
        }).fail(function () {
            _dialog({
                dialogId: 'alertDialog',
                message: 'Алдаа гарлаа. Та дахин шүүж хөрвүүлэлтийг хийнэ үү',
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
        });
    }

    function _advensedSearch(elem) {
        var dialogId = 'advencedSearchDialog';
        if (!$('#' + dialogId).length) {
            $('<div id="' + dialogId + '"></div>').appendTo('body');
            $.ajax({
                url: '<?php echo Sreservation::$path; ?>searchForm',
                type: 'POST',
                dataType: 'json',
                data: {modId: $('input[name="modId"]').val()},
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
                        width: 600,
                        height: "auto",
                        modal: true,
                        dialogClass: 'dialog-blue',
                        close: function () {
                            $('#' + dialogId).dialog('close');
                        },
                        buttons: [
                            {text: "Хайх", class: 'btn btn-xs btn-success pull-left', click: function () {
                                    var _form = $('#' + dialogId).find('form');
                                    _form.submit();
                                    //$('#' + dialogId).dialog('close');
                                }},
                            {text: "Үгүй", class: 'btn  btn-default btn-xs', click: function () {
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
                $('.styled').uniform({
                    radioClass: 'choice'
                });
                $('.select2').select2();
            });
        } else {
            $('#' + dialogId).dialog('open');
        }
    }

    function _reSearchAccommodation(reservationId, organizationId, _dateIn, _dateOut) {

        var _elem = {reservationId: reservationId, modId:<?php echo $modId; ?>, organizationId: organizationId, dateIn: _dateIn, dateOut: _dateOut};
        if (_elem.organizationId > 0 && _elem.dateIn.length > 0 && _elem.dateOut.length > 0) {
            $.ajax({
                type: 'post',
                url: '<?php echo Sreservation::$path; ?>resultCheckDate',
                dataType: "json",
                data: _elem,
                beforeSend: function () {
                    $.blockUI({message: null});
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
                                    }
                                }]
                        });
                    }
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

    }

    function _insertReservationDtl(elem) {
        var _this = $(elem);
        var _form = _this.parents('form');
        _form.validate({errorPlacement: function () {}});
        if (_form.valid()) {
            $.ajax({
                type: 'post',
                url: '<?php echo Sreservation::$path; ?>insertReservationDtl',
                data: _form.serialize(),
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

    function _reservationStatusChange(elem) {
        if ($(elem).prop('checked')) {
            $('input[name="status"]').val(2);
        } else {
            $('input[name="status"]').val(1);
        }
    }

    function _viewReservation(id, modId) {
        var mywindow = '';
        $.ajax({
            type: 'post',
            url: 'sreservation/viewReservation',
            dataType: "json",
            data: {id: id, modId: modId},
            beforeSend: function () {
                $.blockUI({message: null});
            },
            success: function (data) {
                if (data.html) {
                    mywindow = window.open('', 'PRINT', 'height=900,width=1000');
                    mywindow.document.write('<html><head><title>' + data.title + '</title>');
                    mywindow.document.write('</head><body style="font-family:arial;">' + data.html + '</body></html>');
                } else {
                    alert('Менежер хийсэн захиалга байна');
                }

                $.unblockUI();
            }
        });
    }
</script>

