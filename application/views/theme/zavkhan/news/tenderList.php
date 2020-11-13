<div class="container mb-2 mt-2">
    <div class="row">
        <div class="col-md-9">
            <?php
            if ($category) {
                echo '<div class="_theme-layout-media mt-3 mb-3"><h3>' . $category->title . ' &nbsp;</h3></div>';
            }

            if ($result) {
                echo '<ul class="_theme-news-list">';
                foreach ($result as $key => $row) {
                    echo '<li>';
                    echo '<a href="' . $row->url . '">';
                    echo '<div class="_theme-news-list-photo">';
                    echo '<img src="' . UPLOADS_CONTENT_PATH . CROP_SMALL . $row->pic . '">';
                    echo '</div>';
                    echo '<div class="_theme-news-list-text">';
                    echo '<h2>' . $row->title . '</h2>';
                    echo '<div class="text-muted">';
                    echo '<i class="fa fa-clock-o"></i> ' . dateDiff($row->is_active_date);
                    if ($row->comment_count > 0) {
                        echo '<i class="fa fa-comments-o ml-2"></i> <span>' . $row->comment_count . '</span>';
                    }

                    echo '</div>';
                    echo word_limiter($row->intro_text, 100);
                    echo '</div>';
                    echo '</a>';
                    echo '</li>';
                }
                echo '</ul>';

                echo $pagination;
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
