<style type="text/css">
    body {
        margin: 0;
        padding: 0;
    }
</style>

<?php
if ($row) {

    if ($row->media_type_id == 1) {
        /*         * Photo* */
        echo '<img src="' . UPLOADS_CONTENT_PATH . $row->pic . '" style="margin-bottom:20px; max-width:100%;">';
    } else if ($row->media_type_id == 2) {
        /*         * MP4* */
        echo '<video style="width:100%; height:100%;" controls> <source src="' . UPLOADS_CONTENT_PATH . $row->attach_file . '" type="video/mp4"></video>';
    } else if ($row->media_type_id == 3) {
        /*         * Youtube* */

        echo '<iframe style="width:100%; height:450px;" src="https://www.youtube.com/embed/' . getYoutubeId(array('url' => $row->attach_file)) . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
    } else if ($row->media_type_id == 4) {
        /*         * Document* */

        if ($row->attach_file_mime_type == 'application/pdf') {

            echo '<object data="' . UPLOADS_CONTENT_PATH . $row->attach_file . '" type="application/pdf" style="width:100%;height:1200px;" internalinstanceid="59">alt : <a href="' . base_url(UPLOADS_DOCUMENT_PATH . $row->attach_file) . '">' . base_url(UPLOADS_DOCUMENT_PATH . $row->attach_file) . '</a></object>';
        }
    } else if ($row->media_type_id == 5) {
        /*         * Facebook* */
        echo '<iframe src="https://www.facebook.com/plugins/video.php?href=' . $row->attach_file . '&width=780&show_text=false&appId=' . FACEBOOK_API_KEY . '&height=450" width="780" height="450" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media" allowFullScreen="true"></iframe>';
    }
}