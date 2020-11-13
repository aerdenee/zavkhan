<div id="window-content"><?php echo $dataHtml;?></div>

<script type="text/javascript">
    var windowId = "#window-content";
    $(function(){
        $.contextMenu({
            selector: '.context-menu-selected-row',
            callback: function (key, options) {
                var _tr = $(this).parents('tr');
                if (key === 'add') {
                    window.location = '<?php echo Sfeedback::$path . 'add/' . $modId;?>';
                }
                if (key === 'edit') {
                    window.location = '<?php echo Sfeedback::$path . 'edit/' . $modId;?>/' + _tr.attr('data-id');
                }
                if (key === 'delete') {
                    _removeItem({id:_tr.attr('data-id'), modId:_tr.attr('data-mod-id')});
                }
            },
            items: {
                "add": {name: "Нэмэх", icon: "plus"},
                "edit": {name: "Засах", icon: "edit"},
                "delete": {name: "Устгах", icon: "trash"}
            }
        });
    });
</script>