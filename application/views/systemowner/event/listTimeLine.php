<div id="window-timeline" class="default-form-tab"></div>
<script type="text/javascript">
    $(function () {
        $.contextMenu({
            selector: '.content-timeline-context-menu-selected-row',
            callback: function (key, options) {
                var _tr = $(this).parents('tr');
                if (key === 'add') {
                    _formTimeLineDialog({modId: _tr.attr('data-mod-id'), contId: _tr.attr('data-cont-id'), id:0, mode:'timeLineInsert', elem:this, type:7});
                }
                if (key === 'edit') {
                    _formTimeLineDialog({modId: _tr.attr('data-mod-id'), contId: _tr.attr('data-cont-id'), id:_tr.attr('data-id'), mode:'timeLineUpdate', elem:this, type:7});
                }
                if (key === 'delete') {
                    _removeTimeLineItem({modId: _tr.attr('data-mod-id'), contId: _tr.attr('data-cont-id'), id: _tr.attr('data-id'), type:7});
                }
            },
            items: {
                "add": {name: "Нэмэх", icon: "plus"},
                "edit": {name: "Засах", icon: "edit"},
                "delete": {name: "Устгах", icon: "trash"}
            }
        });
        _initTimeLineList({modId:<?php echo $modId;?>, contId: <?php echo $contId;?>, type: 7});
    });

</script>