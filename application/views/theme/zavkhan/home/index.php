<div class="_theme-layout">
    <div class="container">
        <div class="row">
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
                        <li class="nav-item"><a href="#tab-date" class="nav-link active show" data-toggle="tab"><i class="fa fa-clock-o"></i></a></li>
                        <li class="nav-item"><a href="#tab-click" class="nav-link" data-toggle="tab"><i class="fa fa-signal"></i></a></li>
                        <li class="nav-item"><a href="#tab-comment" class="nav-link" data-toggle="tab"><i class="fa fa-comments"></i></a></li>
                    </ul>

                    <div class="tab-content _theme-tab-toperdenet-content">
                        <div class="tab-pane fade active show" id="tab-date">
                            <?php
                            if ($tabNewsListsDate) {
                                echo $tabNewsListsDate;
                            }
                            ?>
                        </div>

                        <div class="tab-pane fade" id="tab-click">
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
            <div class="col-md-6" id="mainMiddle">
                <div class="topNewsArea">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($topNewsOne) {
                                foreach ($topNewsOne as $key => $rowTopNewsOne) {
                                    echo '<a href="' . $rowTopNewsOne->url . '">';
                                    echo '<div class="topNews topNewsFirst">';
                                    echo '<div class="image imghover" style="background-image: url(\'' . UPLOADS_CONTENT_PATH . $rowTopNewsOne->pic . '\')"></div>';
                                    echo '<h5 class="right">' . word_limiter($rowTopNewsOne->title, 20, '...') . '</h5>';
                                    echo '<div class="timer" style="text-align: right">' . dateDiff($rowTopNewsOne->is_active_date) . '</div>';
                                    echo '</div>';
                                    echo '<div class="topNewsFirstDesc">' . word_limiter($rowTopNewsOne->intro_text, 30, '...') . '</div>';
                                    echo '</a>';
                                }
                            }
                            ?>

                        </div>
                        <?php 
                        if ($topNewsFour) {
                            foreach ($topNewsFour as $key => $rowTopNewsFour) {
                                echo '<div class="col-md-6">';
                                    echo '<div class="_theme-media">';
                                        echo '<div class="imglink">';
                                            echo '<img class="in_shadow" src="' . UPLOADS_CONTENT_PATH . CROP_SMALL . $rowTopNewsFour->pic . '" alt="' . $rowTopNewsFour->link_title . '">';
                                        echo '</div>';
                                        echo '<div class="fill_absolute in_shadow">';
                                            echo '<a href="' . $rowTopNewsFour->url . '" title="' . $rowTopNewsFour->link_title . '"></a>';
                                        echo '</div>';
                                        echo '<div class="poster-cant-footer">';
                                            echo '<a href="' . $rowTopNewsFour->url . '" title="' . $rowTopNewsFour->link_title . '">' . word_limiter($rowTopNewsFour->title, 20, '...') . '</a>';
                                        echo '</div>';
                                    echo '</div>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>
            <div class="col-md-3">
                <div class="_theme-live-toperdenet">
                    <div class="_header">
                        <h3>Ярилцлага</h3>
                    </div>
                    <?php
                    if ($topInterview) {
                        echo $topInterview;
                    }
                    ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12">
                <div class="_theme-layout-media">
                    <h3>Завхан &nbsp;</h3>
                    <?php
                    if ($newsHorzintalLists) {
                        echo $newsHorzintalLists;
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>
</div>
