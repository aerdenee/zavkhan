<div class="container bg-white">
    <div class="row">

        <div class="col-md-9">
            <article class="news-container">
                <h1 class="mb-1 pt-3"><?php echo $row->title; ?></h1>
                <div class="entry-content">
                    <ul class="list-inline entry-meta mb-2 pull-right">
                        <li><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo date('Y-m-d H:i', strtotime($row->modified_date)); ?></li>
                        <li><a href="javascript:;"><i class="fa fa-user-o" aria-hidden="true"></i> <?php echo $hrPeople->full_name; ?></a></li>

                        <li><i class="fa fa-comments-o"></i> <?php echo ($row->comment_count == 0 ? 'Та анхны сэтгэгдэлийг үлдээх боломжтой' : 'Үзсэн тоо: ' . $row->comment_count); ?></li>
                    </ul>

                    <div class="clearfix"></div>

                    <div class="unuudur-content journalist_content">

                        <?php
                        if ($row->show_pic_inside == 1) {
                            echo '<div class="text-center" style="padding:20px;">';
                            echo '<img src="' . UPLOADS_CONTENT_PATH . $row->pic . '" class="img full-img">';
                            echo '</div>';
                        }
                        ?>

                        <div class="no_first_image"><?php echo $row->intro_text; ?></div>

                        <?php
                        if ($contentMedia) {
                            $i = 0;
                            foreach ($contentMedia as $contentMediaKey => $contentMediaItem) {

                                //echo '<div class="panel-body">';

                                if ($contentMediaItem->media_type_id == 1) {
                                    echo '<div class="text-center">';
                                    echo '<img src="' . UPLOADS_CONTENT_PATH . $contentMediaItem->pic . '">';
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

                                //echo '</div>';
                            }
                        }
                        ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="bannerB2">



                </div>
                <div class="clearfix"></div>
                <?php
                $this->load->view(MY_ADMIN . '/comment/index', array('comment' => array('modId' => $row->mod_id, 'contId' => $row->id)));

                //echo $showItemMirrorList;
                ?>

            </article>
        </div>
        <div class="col-md-3">


            <div class="social-widget p-2">
                <a href="javascript:;">
                    <img src="<?php echo UPLOADS_USER_PATH . $hrPeople->pic;?>" style="width: 100px; height: 100px;" class="img-circle img-thumbnail">
                </a>
                <h4><a href="javascript:;"><?php echo $hrPeople->full_name;?></a></h4>
                <?php 
                $hrPeopleParam = json_decode($hrPeople->param)->position;

                echo $hrPeopleParam->position . ' ' . $hrPeopleParam->rank . ' ' . $hrPeopleParam->department;

                ?>
            </div>

            <hr class="divider my-2">
            
            
        <?php echo $showItemOtherList;?>

            <div id="content-sidebar" class="" style="z-index: auto; position: static; top: auto;">
                <div class="sw-A03">

                    fadsfdsafdsafdsafds fdsa fdsa fdsa fdsa fdsa fd

                </div>

            </div>
        </div>
    </div>
</div>
