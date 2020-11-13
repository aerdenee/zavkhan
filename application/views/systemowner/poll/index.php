<div id="window-poll"><?php echo $dataHtml;?></div>

<script type="text/javascript">
    var uIdCurrent = <?php echo $this->session->adminUserId; ?>;
    $(function () {
        $.contextMenu({selector: '.context-menu-selected-row', items: _loadContextMenu()});
    });

    function _loadContextMenu() {
        return {
            "add": {
                name: "Нэмэх",
                icon: "plus",
                callback: function () {
                    window.location = '<?php echo Spoll::$path . 'add/' . $modId; ?>';
                },
                disabled: function (key, opt) {
                    if ($('input[name="our[\'create\']"]').val() == 1) {
                        return this.data('');
                    } else {
                        return !this.data('');
                    }
                }
            },
            "edit": {
                name: "Засах",
                icon: "edit",
                callback: function () {
                    var _tr = $(this).parents('tr');
                    window.location = '<?php echo Spoll::$path . 'edit/' . $modId; ?>/' + _tr.attr('data-id');
                },
                disabled: function (key, opt) {
                    var _tr = $(this).parents('tr');

                    if (($('input[name="our[\'update\']"]').val() == 1 && _tr.attr('data-uid') == uIdCurrent) || ($('input[name="your[\'update\']"]').val() == 1 && _tr.attr('data-uid') != uIdCurrent)) {
                        return this.data('');
                    } else {
                        return !this.data('');
                    }
                    return !this.data('');
                }
            },
            "separator": '---------',
            "delete": {
                name: "Устгах",
                icon: "trash",
                callback: function () {
                    var _tr = $(this).parents('tr');
                    _removeItem({id:_tr.attr('data-id'), modId:_tr.attr('data-mod-id')});
                },
                disabled: function (key, opt) {
                    var _tr = $(this).parents('tr');
                    if (($('input[name="our[\'delete\']"]').val() == 1 && _tr.attr('data-uid') == uIdCurrent) || ($('input[name="your[\'delete\']"]').val() == 1 && _tr.attr('data-uid') != uIdCurrent)) {
                        return this.data('');
                    } else {
                        return !this.data('');
                    }
                }
            }
        }
    }
</script>