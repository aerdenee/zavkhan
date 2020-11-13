<style type="text/css">
    body {
        margin: 0;
        padding: 0;
    }
</style>
    
<?php
if ($row) {
    if ($row->mime_type == 'image/jpeg' or $row->mime_type == 'image/jpg') {

        echo '<img src="' . UPLOADS_DOCUMENT_PATH . $row->attach_file . '" style="margin-bottom:20px; max-width:100%;">';
    } else if ($row->mime_type == 'application/pdf') {

        echo '<object data="' . UPLOADS_DOCUMENT_PATH . $row->attach_file . '" type="application/pdf" style="width:100%;height:1200px;" internalinstanceid="59">alt : <a href="' . base_url(UPLOADS_DOCUMENT_PATH . $row->attach_file) . '">' . base_url(UPLOADS_DOCUMENT_PATH . $row->attach_file) . '</a></object>';
    }
} else {
    if ($row->mime_type == 'image/jpeg' or $row->mime_type == 'image/jpg') {

        echo '<img src="' . UPLOADS_TEMP_PATH . $row->attach_file . '" style="margin-bottom:20px; max-width:100%;">';
    } else if ($row->mime_type == 'application/pdf') {

        echo '<object data="' . UPLOADS_TEMP_PATH . $row->attach_file . '" type="application/pdf" style="width:100%;height:1200px;" internalinstanceid="59">alt : <a href="' . base_url(UPLOADS_TEMP_PATH . $row->attach_file) . '">' . base_url(UPLOADS_TEMP_PATH . $row->attach_file) . '</a></object>';
    }
}