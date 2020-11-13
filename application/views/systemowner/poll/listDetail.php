<div id="window-media" class="default-form-tab"></div>
<script type="text/javascript">
    $(function () {
        $.contextMenu({
            selector: '.content-media-context-menu-selected-row',
            callback: function (key, options) {
                var _tr = $(this).parents('tr');
                if (key === 'add') {
                    _formMediaDialog({modId: _tr.attr('data-mod-id'), pollId: _tr.attr('data-poll-id'), id:0, mode:'mediaInsert', elem:this});
                }
                if (key === 'edit') {
                    _formMediaDialog({modId: _tr.attr('data-mod-id'), pollId: _tr.attr('data-poll-id'), id:_tr.attr('data-id'), mode:'mediaUpdate', elem:this});
                }
                if (key === 'delete') {
                    _removeMeidaItem({modId: _tr.attr('data-mod-id'), pollId: _tr.attr('data-poll-id'), id: _tr.attr('data-id')});
                }
            },
            items: {
                "add": {name: "Нэмэх", icon: "plus"},
                "edit": {name: "Засах", icon: "edit"},
                "delete": {name: "Устгах", icon: "trash"}
            }
        });
        _initMediaList({modId:<?php echo $modId;?>, pollId:<?php echo $pollId;?>});
        
    });

</script>