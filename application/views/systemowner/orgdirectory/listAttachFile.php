<div id="window-attach-file" class="default-form-tab"></div>
<script type="text/javascript">
    $(function () {
        $.contextMenu({
            selector: '.content-attach-file-context-menu-selected-row',
            callback: function (key, options) {
                var _tr = $(this).parents('tr');
                if (key === 'add') {
                    _formAttachFileDialog({modId: _tr.attr('data-mod-id'), contId: _tr.attr('data-cont-id'), id:0, mode:'attachFileInsert', elem:this, type:2});
                }
                if (key === 'edit') {
                    _formAttachFileDialog({modId: _tr.attr('data-mod-id'), contId: _tr.attr('data-cont-id'), id:_tr.attr('data-id'), mode:'attachFileUpdate', elem:this, type:2});
                }
                if (key === 'delete') {
                    _removeAttachFileItem({modId: _tr.attr('data-mod-id'), contId: _tr.attr('data-cont-id'), id: _tr.attr('data-id'), type:6});
                }
            },
            items: {
                "add": {name: "Нэмэх", icon: "plus"},
                "edit": {name: "Засах", icon: "edit"},
                "delete": {name: "Устгах", icon: "trash"}
            }
        });
        _initAttachFileList({modId:<?php echo $modId;?>, contId: <?php echo $contId;?>, type:2});
    });

</script>