<?php
if ($getDataMediaList) {
    foreach ($getDataMediaList as $mediaKey => $mediaRow) {
        echo '<img src="' . UPLOADS_URGUDUL_PATH . $row['create_number'] . '/' . CROP_BIG . $mediaRow->attach_file . '" style="width:100%;">';
    }
}