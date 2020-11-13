
<!-- Sidebars overview -->
<div class="panel panel-white _content-item">

    <div class="panel-heading">
        <h6 class="panel-title text-semibold"><?php echo $row->title; ?><a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>

        <div class="heading-elements">
            <ul class="list-inline list-inline-separate heading-text">
                <li><i class="icon-watch text-size-base text-blue-300"></i><span class="text-muted position-right"> <?php echo ($row->click == 0 ? 'Энэ хичээлийн анхны уншигч нь та' : 'Үзсэн тоо: ' . $row->click); ?></span></li>
                <li>
                    <span class="text-muted position-right comment-count-html"><?php echo ($row->comment_count == 0 ? 'Энэ хичээлийн анхны уншигч нь та' : 'Үзсэн тоо: ' . $row->comment_count); ?></span>
                </li>
            </ul>
        </div>
    </div>

<!--    <div class="panel-body">

        <?php
//        if ($row->show_pic_inside == 1) {
//            echo '<div class="text-center" style="padding:20px;">';
//            echo '<img src="' . UPLOADS_CONTENT_PATH . CROP_LARGE . $row->pic . '">';
//            echo '</div>';
//        }
//
//        echo $row->full_text;
        ?>
    </div>-->

    <?php
    if ($contentMedia) {
        $i = 0;
        foreach ($contentMedia as $contentMediaKey => $contentMediaItem) {
            
            echo '<div class="panel-body">';
            
            if ($contentMediaItem->media_type_id == 1) {
                echo '<div class="text-center">';
                echo '<img src="' . UPLOADS_CONTENT_PATH . CROP_LARGE . $contentMediaItem->pic . '">';
                echo '</div>';
            }

            if ($contentMediaItem->media_type_id == 2) {
                echo '<video id=\'my-video\' class=\'video-js\' controls preload=\'auto\' style="width:100%; height:500px;"
  poster=\'' . UPLOADS_CONTENT_PATH . $row->pic . '\' data-setup=\'{}\'><source src="' . UPLOADS_CONTENT_PATH . $contentMediaItem->attach_file . '" type="video/mp4"></video>';
            }

            if ($contentMediaItem->media_type_id == 3) {
                echo '<object style="width:100%; min-height:500px;">
    <param name="movie" value="http://www.youtube.com/v/' . getYoutubeId(array('url' => $contentMediaItem->attach_file)) . '"></param>
    <param name="wmode" value="transparent"></param>
    <embed src="http://www.youtube.com/v/" type="application/x-shockwave-flash" wmode="transparent"></embed>
</object>';
            }

            if ($contentMediaItem->media_type_id == 4) {
                echo '<object data="' . UPLOADS_CONTENT_PATH . $contentMediaItem->attach_file . '" type="application/pdf" style="width:100%;height:1200px;" internalinstanceid="59">alt : <a href="' . base_url(UPLOADS_CONTENT_PATH . $contentMediaItem->attach_file) . '">' . base_url(UPLOADS_CONTENT_PATH . $contentMediaItem->attach_file) . '</a></object>';
                //echo '<iframe src="https://docs.google.com/viewer?embedded=true&url=" frameborder="no" style="width:100%;height:600px"></iframe>';
            }

            echo '<br>';
            echo $contentMediaItem->intro_text;
            
            echo '</div>';
        }
    }
//    if ($contentMedia) {
//        echo '<div class="table-responsive">
//                                        <table class="table table-striped">
//                                            <thead>
//                                                <tr>
//                                                    <th style="width: 120px">Агуулга №</th>
//                                                    <th style="width: 250px">Нэр</th>
//                                                    <th>Тайлбар</th>
//                                                    <th class="text-center" style="width: 120px">Төлөв</th>
//                                                    <th class="text-center" style="width: 100px">Огноо</th>
//                                                </tr>
//                                            </thead>
//                                            <tbody>';
//        $i = 0;
//        foreach ($contentMedia as $contentMediaKey => $contentMediaRow) {
//            echo '<tr style="cursor:pointer;" onclick="_showMediaItem({selectedId: ' . $contentMediaRow->id . '});">';
//            echo '<td>Агуулга ' . ++$i . '</td>';
//            echo '<td>' . $contentMediaRow->title . ' <br>[' . $contentMediaRow->content_media_type_title . ']</td>';
//            echo '<td>' . word_limiter($contentMediaRow->intro_text, 25) . '</td>';
//            echo '<td class="text-center">';
//            if ($contentMediaRow->is_active == 1) {
//                echo '<span class="label label-success">Нээлттэй</span>';
//            } else {
//                echo '<span class="label label-default">Хаалттай</span>';
//            }
//
//            echo '</td>';
//            echo '<td class="text-center">' . date('Y-m-d', strtotime($contentMediaRow->modified_date)) . '</td>';
//            echo '</tr>';
//        }
//        echo '</tbody>
//                                        </table>';
//    }
    ?>

    <?php
    $this->load->view(MY_ADMIN . '/comment/index', array('comment' => array('modId' => $row->mod_id, 'contId' => $row->id)));
    ?>

</div>
<!-- /sidebars overview -->

<script type="text/javascript">
    function _showMediaItem(param) {

        if (!$(_contentMediaDialogId).length) {
            $('<div id="' + _contentMediaDialogId.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            url: _learningModRootPath + 'showMediaItem',
            type: 'POST',
            dataType: 'json',
            data: {moduleMenuId: _MODULE_MENU_ID, selectedId: param.selectedId},
            beforeSend: function () {
                $.blockUI({
                    message: _jqueryBlockUiMessage,
                    overlayCSS: _jqueryBlockUiOverlayCSS,
                    css: _jqueryBlockUiMessageCSS
                });
            },
            success: function (data) {
                $(_contentMediaDialogId).html(data.html);
                $(_contentMediaDialogId).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: false,
                    autoOpen: false,
                    title: data.title,
                    width: data.width,
                    height: "auto",
                    modal: true,
                    position: {my: "center", at: "top"},
                    close: function () {
                        $(_contentMediaDialogId).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.btn_close, class: 'btn btn-default', click: function () {
                                $(_contentMediaDialogId).empty().dialog('close');
                            }}
                    ]
                });
                $(_contentMediaDialogId).dialog('open');
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
            }
        }).done(function (data) {

        });

    }
</script>