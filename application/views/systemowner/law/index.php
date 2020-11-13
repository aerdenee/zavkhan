<div id="window-content"><?php echo $dataHtml; ?></div>

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
                    window.location = '<?php echo Slaw::$path . 'add/' . $modId; ?>';
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
                    window.location = '<?php echo Slaw::$path . 'edit/' . $modId; ?>/' + _tr.attr('data-id');
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
            "print": {
                name: "Агуулга хэвлэх",
                icon: "print",
                callback: function () {
                    console.log('print page');
                },
                disabled: function (key, opt) {
                    var _tr = $(this).parents('tr');
                    if (($('input[name="our[\'read\']"]').val() == 1 && _tr.attr('data-uid') == uIdCurrent) || ($('input[name="your[\'read\']"]').val() == 1 && _tr.attr('data-uid') != uIdCurrent)) {
                        return this.data('');
                    } else {
                        return !this.data('');
                    }
                }
            },
            "separator1": '---------',
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