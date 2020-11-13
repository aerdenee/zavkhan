<div id="window-tour-itinerary" class="default-form-tab"></div>
<script type="text/javascript">
    $(function () {
        $.contextMenu({
            selector: '.tour-itinerary-context-menu-selected-row',
            callback: function (key, options) {
                var _tr = $(this).parents('tr');
                if (key === 'add') {
                    _formTourItineraryDialog({modId: _tr.attr('data-mod-id'), contId: _tr.attr('data-cont-id'), id:0, mode:'tourItineraryInsert', elem:this});
                }
                if (key === 'edit') {
                    _formTourItineraryDialog({modId: _tr.attr('data-mod-id'), contId: _tr.attr('data-cont-id'), id:_tr.attr('data-id'), mode:'tourItineraryUpdate', elem:this});
                }
                if (key === 'delete') {
                    _removeTourItineraryItem({modId: _tr.attr('data-mod-id'), contId: _tr.attr('data-cont-id'), id: _tr.attr('data-id')});
                }
            },
            items: {
                "add": {name: "Нэмэх", icon: "plus"},
                "edit": {name: "Засах", icon: "edit"},
                "delete": {name: "Устгах", icon: "trash"}
            }
        });
        _initTourItineraryList({modId:<?php echo $modId;?>, contId: <?php echo $contId;?>});
    });

</script>