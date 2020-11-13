<div class="container mb-2 mt-2">
    <div class="row">
        <div class="col-md-9">
            <div class="_theme-news-item">

                <?php
                if ($row) {
                    echo '<div id="_theme-social-count-data" ldate="' . strtotime($row->is_active_date) . '" data-fbcount="' . $row->facebook_count . '" data-twcount="' . $row->twitter_count . '" data-url="' . $row->url . '"></div>';
                    echo '<h1>' . $row->title . '</h1>';
                    echo '<div class="_theme-news-author">';
                    echo '<div class="name">' . $row->full_name . ', ' . $_SERVER['HTTP_HOST'] . '</div>';
                    echo '<div class="time" data-time="' . $row->is_active_date . '">' . dateDiff($row->is_active_date) . '</div>';
                    /* social begin */
                    echo '<div class="social-tool-bar">';
                    echo '<div class="social_share">';
                    echo '<a href="javascript:void(0);" class="_theme-share-twitter" data-url="' . current_url() . '" data-text="' . addslashes($row->title) . '">';
                    echo '<span class="logo"></span>';
                    echo '<span class="count">ЖИРГЭХ</span>';
                    echo '<span class="message">ЖИРГЭХ</span>';
                    echo '</a>';

                    echo '<a href="javascript:void(0);" class="_theme-share-facebook " data-url="' . current_url() . '" data-text="' . addslashes($row->title) . '">';
                    echo '<span class="logo"></span>';
                    echo '<span class="count">ХУВААЛЦАХ</span>';
                    echo '<span class="message">ХУВААЛЦАХ</span>';
                    echo '</a>';
                    echo '</div>';
                    echo '</div>';
                    /*                     * social end */
                    echo '</div>';
                    echo '<div class="content">';
                    if ($row->show_pic_inside == 1) {
                        echo '<img src="' . UPLOADS_CONTENT_PATH . $row->pic . '">';
                    }

                    if ($media) {
                        foreach ($media as $key => $mediaRow) {
                            if ($mediaRow->media_type_id == 3) {
                                echo '<iframe style="width:100%;" height="400" src="https://www.youtube.com/embed/' . getYoutubeId(array('url' => $mediaRow->attach_file)) . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                            }
                        }
                    }
                    echo $row->full_text;
                    echo '</div>';

                    echo '<div class="_theme-news-footer-accessories mt-3 mb-3">';
                    /* social begin */
                    echo '<div class="social-tool-bar">';
                    echo '<div class="social_share">';
                    echo '<a href="javascript:void(0);" class="_theme-share-twitter mr-2" data-url="' . current_url() . '" data-text="' . addslashes($row->title) . '">';
                    echo '<span class="logo"></span>';
                    echo '<span class="count">ЖИРГЭХ</span>';
                    echo '<span class="message">ЖИРГЭХ</span>';
                    echo '</a>';

                    echo '<a href="javascript:void(0);" class="_theme-share-facebook " data-url="' . current_url() . '" data-text="' . addslashes($row->title) . '">';
                    echo '<span class="logo"></span>';
                    echo '<span class="count">ХУВААЛЦАХ</span>';
                    echo '<span class="message">ХУВААЛЦАХ</span>';
                    echo '</a>';
                    echo '</div>';
                    echo '</div>';
                    /*                     * social end */
                    echo '</div>';
                    if ($banner3) {
                        echo $banner3;
                    }
                    echo '<div class="fb_share_article">
      
			<table border="0" cellspacing="10" cellpadding="10" width="100%">
				<tr>
					<td valign="middle" width="100" align="right">
            <div class="fb-like" data-href="https://ikon.mn/n/1syb" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
            <div class="fb-send" data-href="https://ikon.mn/n/1syb"></div>
					</td>
				</tr>
        
				<tr>
					<td valign="middle" width="100" align="right">
            <div class="fb-save" data-uri="https://ikon.mn/n/1syb"></div>
					</td>
				</tr>
				
			</table>
		</div>';
                    echo '<div class="initReactionHtml"></div>';
                    echo '<script type="text/javascript">_initReaction({modId: ' . $row->mod_id . ', contId: ' . $row->id . ', initHtml: \'initReactionHtml\'});</script>';
                }
                ?>

            </div>

            <?php
            if ($newsItemHorzintalGetKeywordLists) {
                echo '<div class="_theme-layout-media">';
                echo '<h3>Бусад мэдээ &nbsp;</h3>';
                echo $newsItemHorzintalGetKeywordLists;
                echo '</div>';
            }

            if ($row->show_comment == 1) {
                $this->load->view(DEFAULT_THEME . '/comment/form', array(
                    'modId' => $row->mod_id,
                    'contId' => $row->id));
                echo '<div class="themeInitCommentHtml"></div>';
                echo '<script type="text/javascript">_themeInitComment({modId: ' . $row->mod_id . ', contId: ' . $row->id . ', initHtml: \'themeInitCommentHtml\', sortType: \'DESC\'});</script>';
            }
            ?>

        </div>
        <div class="col-md-3">
            <?php
            if ($banner1) {
                echo '<div class="mb-2">';
                echo $banner1;
                echo '</div>';
            }
            ?>
            <div class="_theme-tab-toperdenet">
                <ul class="nav nav-tabs nav-tabs-toperdenet nav-justified">
                    <li class="nav-item"><a href="#tab-date" class="nav-link" data-toggle="tab"><i class="fa fa-clock-o"></i></a></li>
                    <li class="nav-item"><a href="#tab-click" class="nav-link active show" data-toggle="tab"><i class="fa fa-signal"></i></a></li>
                    <li class="nav-item"><a href="#tab-comment" class="nav-link" data-toggle="tab"><i class="fa fa-comments"></i></a></li>
                </ul>

                <div class="tab-content _theme-tab-toperdenet-content">
                    <div class="tab-pane fade" id="tab-date">
                        <?php
                        if ($tabNewsListsDate) {
                            echo $tabNewsListsDate;
                        }
                        ?>
                    </div>

                    <div class="tab-pane fade active show" id="tab-click">
                        <?php
                        if ($tabNewsListsClick) {
                            echo $tabNewsListsClick;
                        }
                        ?>
                    </div>

                    <div class="tab-pane fade" id="tab-comment">
                        <?php
                        if ($tabNewsListsComment) {
                            echo $tabNewsListsComment;
                        }
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
