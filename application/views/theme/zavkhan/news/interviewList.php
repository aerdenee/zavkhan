<div class="container mb-2 mt-2">
    <div class="row">
        <div class="col-md-12">
            <?php 
            if ($category) {
                echo '<div class="_theme-layout-media mt-3 mb-3"><h3>' . $category->title . ' &nbsp;</h3></div>';
            }
            
            if ($result) {
                echo '<div class="row">';
                echo '<ul class="_theme-interview-list">';
                foreach ($result as $key => $row) {
                    echo '<li>';
                    echo '<a href="' . $row->url . '">';
                    echo '<div class="_theme-interview-list-photo">';
                    echo '<div class="imglink">';
                    echo '<img src="' . UPLOADS_CONTENT_PATH . CROP_SMALL . $row->pic . '">';
                    echo '</div>';
                    echo '<div class="fill_absolute in_shadow"></div>';
                    echo '</div>';
                    echo '<div class="_theme-interview-list-text">';
                    
                    echo '<div class="text-muted">';
                    echo '<i class="fa fa-clock-o"></i> ' . dateDiff($row->is_active_date);
                    if ($row->comment_count > 0) {
                        echo '<i class="fa fa-comments-o ml-2"></i> <span>' . $row->comment_count . '</span>';
                    }
                    
                    echo '</div>';
                    
                    echo '<h2>' . $row->title . '</h2>';
                    //echo word_limiter($row->intro_text, 100);
                    echo '</div>';
                    echo '</a>';
                    echo '</li>';
                }
                echo '</ul>';
                echo '</div>';
                echo '<div class="clearfix"></div>';
                echo $pagination;
            }
            ?>
        </div>
    </div>
</div>
