<div class="clearfix"></div>
</div>
        </div>
        
<!--        <div class="clearfix"></div>
<div class="footer navbar-fixed-bottom text-muted">
    &copy; <?php echo (date('Y') > 2018 ? '2018-' : '') . date('Y'); ?>, Шүүхийн шинжилгээний Үндэсний хүрээлэнгийн захиалгаар бүтээв.
</div>-->
<?php

if (isset($jsFile)) {
    foreach ($jsFile as $js) {
        echo '<script src="' . $js . '?v=' . CSS_JS_VERSION . '" type="text/javascript" async defer></script>' . "\n";
    }
}
?>
</body>
</html>