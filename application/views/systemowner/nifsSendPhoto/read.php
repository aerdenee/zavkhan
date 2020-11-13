<?php 

$pic = ltrim($row->pic, UPLOADS_NIFS_SEND_PHOTO_PATH . CROP_SMALL);

echo '<img src="' . UPLOADS_NIFS_SEND_PHOTO_PATH . $pic . '" style="width:100%;">';
