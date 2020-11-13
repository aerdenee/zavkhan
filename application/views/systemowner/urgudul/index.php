<div id="window-content"><?php echo $dataHtml; ?></div>
<script type="text/javascript">
    var _uIdCurrent = <?php echo $this->session->adminUserId; ?>;
    $(function () {
        $.contextMenu({selector: '.context-menu-selected-row', items: _loadContextMenu()});
    });
    function _loadContextMenu() {
        return {
            "add": {
                name: "Өргөдөл үүсгэх",
                icon: "plus",
                callback: function () {
                    var _tr = $(this).parents('tr');
                    window.location = _modRootPath + 'add/' + _tr.attr('data-mod-id');
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
                name: "Өргөдөл засварлах",
                icon: "edit",
                callback: function () {
                    var _tr = $(this).parents('tr');
                    window.location = _modRootPath + 'edit/' + _tr.attr('data-mod-id') + '/' + _tr.attr('data-id');
                },
                disabled: function (key, opt) {
                    var _tr = $(this).parents('tr');
                    if (($('input[name="our[\'update\']"]').val() == 1 && _tr.attr('data-uid') == _uIdCurrent) || ($('input[name="your[\'update\']"]').val() == 1 && _tr.attr('data-uid') != _uIdCurrent)) {
                        return this.data('');
                    } else {
                        return !this.data('');
                    }
                    return !this.data('');
                }
            },
            "track": {
                name: "Өргөдөл явц",
                icon: "hand-o-right",
                callback: function () {
                    var _tr = $(this).parents('tr');
                    window.location = _modRootPath + 'track/' + _tr.attr('data-mod-id') + '/' + _tr.attr('data-id');
                },
                disabled: function (key, opt) {
                    var _tr = $(this).parents('tr');
                    if (($('input[name="our[\'track\']"]').val() == 1 && _tr.attr('data-uid') == _uIdCurrent) || ($('input[name="your[\'track\']"]').val() == 1 && _tr.attr('data-uid') != _uIdCurrent)) {
                        return this.data('');
                    } else {
                        return !this.data('');
                    }
                    return !this.data('');
                }
            },
            "close": {
                name: "Өргөдөл хаах",
                icon: "key",
                callback: function () {
                    var _tr = $(this).parents('tr');
                    window.location = _modRootPath + 'close/' + _tr.attr('data-mod-id') + '/' + _tr.attr('data-id');
                },
                disabled: function (key, opt) {
                    var _tr = $(this).parents('tr');
                    if (($('input[name="our[\'close\']"]').val() == 1 && _tr.attr('data-uid') == _uIdCurrent) || ($('input[name="your[\'close\']"]').val() == 1 && _tr.attr('data-uid') != _uIdCurrent)) {
                        return this.data('');
                    } else {
                        return !this.data('');
                    }
                    return !this.data('');
                }
            },
            "read": {
                name: "Дэлгэрэнгүй",
                icon: "eye",
                callback: function () {
                    var _tr = $(this).parents('tr');
                    window.location = _modRootPath + 'read/' + _tr.attr('data-mod-id') + '/' + _tr.attr('data-id');
                },
                disabled: function (key, opt) {
                    var _tr = $(this).parents('tr');
                    if (($('input[name="our[\'read\']"]').val() == 1 && _tr.attr('data-uid') == _uIdCurrent) || ($('input[name="your[\'read\']"]').val() == 1 && _tr.attr('data-uid') != _uIdCurrent)) {
                        return this.data('');
                    } else {
                        return !this.data('');
                    }
                    return !this.data('');
                }
            },
            "separator": '---------',
            "printBlank": {
                name: "Маягт хэвлэх",
                icon: "print",
                callback: function () {
                    var _tr = $(this).parents('tr');
                    _printBlank({id: _tr.attr('data-id')});
                },
                disabled: function (key, opt) {
                    var _tr = $(this).parents('tr');
                    if (($('input[name="our[\'read\']"]').val() == 1 && _tr.attr('data-uid') == _uIdCurrent) || ($('input[name="your[\'read\']"]').val() == 1 && _tr.attr('data-uid') != _uIdCurrent)) {
                        return this.data('');
                    } else {
                        return !this.data('');
                    }
                    return !this.data('');
                }
            },
            "printPage": {
                name: "Өргөдөл хэвлэх",
                icon: "print",
                callback: function () {
                    var _tr = $(this).parents('tr');
                    _printPage({id: _tr.attr('data-id')});
                },
                disabled: function (key, opt) {
                    var _tr = $(this).parents('tr');
                    if (($('input[name="our[\'read\']"]').val() == 1 && _tr.attr('data-uid') == _uIdCurrent) || ($('input[name="your[\'read\']"]').val() == 1 && _tr.attr('data-uid') != _uIdCurrent)) {
                        return this.data('');
                    } else {
                        return !this.data('');
                    }
                    return !this.data('');
                }
            },
            "separator1": '---------',
            "delete": {
                name: "Өргөдөл устгах",
                icon: "trash",
                callback: function () {
                    var _tr = $(this).parents('tr');
                    _removeItem({id: _tr.attr('data-id'), modId: _tr.attr('data-mod-id'), createNumber: _tr.attr('data-create-number')});
                },
                disabled: function (key, opt) {
                    var _tr = $(this).parents('tr');
                    if (($('input[name="our[\'delete\']"]').val() == 1 && _tr.attr('data-uid') == _uIdCurrent) || ($('input[name="your[\'delete\']"]').val() == 1 && _tr.attr('data-uid') != _uIdCurrent)) {
                        return this.data('');
                    } else {
                        return !this.data('');
                    }
                }
            }
        }
    }
</script>