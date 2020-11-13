<?php

if ($contentMediaItem->media_type_id == 1) {
    echo '<div class="text-center">';
    echo '<img src="' . UPLOADS_CONTENT_PATH . CROP_LARGE . $contentMediaItem->pic . '">';
    echo '</div>';
}

if ($contentMediaItem->media_type_id == 2) {
    echo '<video style="width:100%; min-height:500px;" controls><source src="' . UPLOADS_CONTENT_PATH . $contentMediaItem->attach_file . '" type="video/mp4"></video>';
}

if ($contentMediaItem->media_type_id == 3) {
    echo '<object style="width:100%; min-height:500px;">
    <param name="movie" value="http://www.youtube.com/v/' . getYoutubeId(array('url' => $contentMediaItem->attach_file)) . '"></param>
    <param name="wmode" value="transparent"></param>
    <embed src="http://www.youtube.com/v/" type="application/x-shockwave-flash" wmode="transparent"></embed>
</object>';
}

if ($contentMediaItem->media_type_id == 4) {
    echo '<iframe src="https://docs.google.com/viewer?embedded=true&url=' . base_url(UPLOADS_CONTENT_PATH . $contentMediaItem->attach_file) . '" frameborder="no" style="width:100%;height:600px"></iframe>';
}

echo '<br>';
echo $contentMediaItem->intro_text;