<?php if ($row['id'] != ''): ?>
    <?php echo form_button('send', '<i class="fa fa-plus"></i> Нэмэх', 'class="btn btn-success btn-xs" onclick="formMediaDialog(0, \'insertMedia\');"', 'button');?>
    <div class="clearfix margin-bottom-20"></div>
    <table class="table table-bordered table-hover" id="mediaTable">
        <thead>
            <tr>
                <th class="table-checkbox text-center">#</th>
                <th style="width: 60px;">Зураг</th>
                <th>Гарчиг</th>
                <th style="width: 70px;">Төлөв</th>
                <th style="width: 15px;"><i class="fa fa-sort"></i></th>
                <th style="width: 15px;"><i class="fa fa-trash"></i></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <script type="text/javascript">
        
        var mediaWindowId = "#content-media";
        var mediaTableName = "mediaTable";
        var oMediaTable = $('#' + mediaTableName, mediaWindowId).dataTable({
            scrollCollapse: false,
            ordering: false,
            info: false,
            autoWidth: false,
            dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<span>Хайлт:</span> _INPUT_',
                lengthMenu: '<span>Харуулах:</span> _MENU_',
                paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
            },
            drawCallback: function () {
                $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
            },
            preDrawCallback: function () {
                $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
            },
            select: {
                style: 'multi',
                selector: 'td:first-child'
            }
        });
        $('.dataTables_filter input[type=search]').attr('placeholder', 'Хайлт хийх түлхүүр үг...');
        initTableMedias();
        
        function formMediaDialog(id, mode) {
            var dialogId = 'dialog-media';
            var mediaVideoPhotoFormId = '#form-media-video-photo';
            if (!$(dialogId).length) {
                $('<div id="' + dialogId + '"></div>').appendTo('body');
            }
            $.ajax({
                type: 'post',
                url: '<?php echo Saccommodation::$path; ?>formMedia',
                data: {modId: <?php echo $row['mod_id']; ?>, contId: <?php echo $row['id']; ?>, mediaId: id},
                dataType: 'json',
                beforeSend: function () {
                    $.blockUI({message: null});
                },
                success: function (data) {
                    $("#" + dialogId).empty().html(data.html);
                    $("#" + dialogId).dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.title,
                        width: 800,
                        height: "auto",
                        modal: true,
                        close: function () {
                            $("#" + dialogId).empty().dialog('close');
                        },
                        buttons: [
                            {text: data.btn_ok, class: 'btn btn-success btn-xs', click: function () {
                                    $(mediaVideoPhotoFormId).validate({errorPlacement: function () {}});
                                    if ($(mediaVideoPhotoFormId).valid()) {
                                        $(mediaVideoPhotoFormId).ajaxSubmit({
                                            type: 'post',
                                            url: '<?php echo Saccommodation::$path; ?>' + mode,
                                            data: $(mediaVideoPhotoFormId, mediaWindowId).serialize(),
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
                                                    $("#" + dialogId).dialog('close');
                                                    initTableMedias();
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
                                }},
                            {text: data.btn_no, class: 'btn blue btn-xs', click: function () {
                                    $("#" + dialogId).dialog('close');
                                }}
                        ]
                    });
                    $("#" + dialogId).dialog('open');
                    $.unblockUI();
                }
            }).done(function () {
            });
        }
        function initTableMedias() {
            $.ajax({
                url: '<?php echo Saccommodation::$path; ?>listsMedia',
                type: 'POST',
                dataType: 'json',
                data: {modId: <?php echo $modId; ?>, contId: <?php echo $row['id'];?>},
                beforeSend: function () {
                    $.blockUI({message: null});
                },
                success: function (data) {
                    oMediaTable.fnClearTable();
                    for (i = 0; i < data.length; i++) {
                        oMediaTable.fnAddData([data[i][0], data[i][1], data[i][2], data[i][3], data[i][4], data[i][5]]);
                    }
                    $.unblockUI();
                },
                error: function () {
                    $.unblockUI();
                }
            });
        }
        function isActiveMedia(isActive, id) {
            $.ajax({
                type: 'post',
                url: '<?php echo Saccommodation::$path; ?>isActiveMedia',
                dataType: "json",
                data: {isActive: isActive, id: id},
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
                        initTableMedias();
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
        function removeItemMedia(id) {
            var dialogId = 'removeMeidaDialog';
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
                close: function () {
                    $("#" + dialogId).empty().dialog('close');
                },
                buttons: [
                    {text: "Тийм", class: 'btn btn-xs green pull-left', click: function () {
                            $.ajax({
                                type: 'post',
                                url: '<?php echo Saccommodation::$path; ?>deleteMedia',
                                dataType: "json",
                                data: {id: [id]},
                                success: function (data) {
                                    if (data.status === 'success') {
                                        new PNotify({
                                            title: data.title,
                                            text: data.message,
                                            addclass: 'bg-success'
                                        });
                                        initTableMedias();
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
                            $("#" + dialogId).dialog('close');
                        }},
                    {text: "Үгүй", class: 'btn blue btn-xs', click: function () {
                            $("#" + dialogId).dialog('close');
                        }}
                ]
            });
            $("#" + dialogId).dialog('open');
        }
    </script>
<?php endif; ?>