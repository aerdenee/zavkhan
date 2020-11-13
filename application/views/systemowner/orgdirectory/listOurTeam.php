<div id="window-our-team" class="default-form-tab"></div>
<script type="text/javascript">
    $(function () {
        $.contextMenu({
            selector: '.content-ourteam-context-menu-selected-row',
            callback: function (key, options) {
                var _tr = $(this).parents('tr');
                if (key === 'add') {
                    _formOurTeamDialog({modId: _tr.attr('data-mod-id'), contId: _tr.attr('data-cont-id'), id:0, mode:'ourTeamInsert', elem:this, type:6});
                }
                if (key === 'edit') {
                    _formOurTeamDialog({modId: _tr.attr('data-mod-id'), contId: _tr.attr('data-cont-id'), id:_tr.attr('data-id'), mode:'ourTeamUpdate', elem:this, type:6});
                }
                if (key === 'delete') {
                    _removeOurTeamItem({modId: _tr.attr('data-mod-id'), contId: _tr.attr('data-cont-id'), id: _tr.attr('data-id'), type:6});
                }
            },
            items: {
                "add": {name: "Нэмэх", icon: "plus"},
                "edit": {name: "Засах", icon: "edit"},
                "delete": {name: "Устгах", icon: "trash"}
            }
        });
        _initOurTeamList({modId:<?php echo $modId;?>, contId: <?php echo $contId;?>, type:6});
    });

</script>