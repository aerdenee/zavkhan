<script type="text/javascript">
    $('body').attr('class', 'has-detached-right');
</script>
<style type="text/css">
    .navigation li.navigation-divider {
        margin: 0;
    }
</style>
<!-- Detached content -->
<div class="container-detached">
    <div class="content-detached">
        <div class="panel panel-flat form-horizontal">
            <div class="panel-heading"></div>
            <div class="panel-body">
                <div id="load-profile-container"></div>
            </div>
        </div>
    </div>
</div>
<div class="sidebar-detached">
    <div class="sidebar sidebar-default">
        <div class="sidebar-content">

            <!-- Sidebar search -->
            <div class="sidebar-category">
                <div class="sidebar-user">
                    <div class="category-content">
                        <div class="media" style="text-align: center;" data-view="profileInformation" onclick="_changeProfileContainer({elem:this});">

                            <img src="<?php echo UPLOADS_USER_PATH . CROP_SMALL . $row->pic; ?>" class="img-circle" style="width: 40%; text-align: center;">
                            <div class="media-body">
                                <span class="media-heading text-semibold" style="text-align: center; margin-top: 20px;"><?php echo $row->full_name; ?></span>
                                <div class="text-size-mini text-muted"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /sidebar search -->
            <div class="sidebar-category">

                <div class="category-content no-padding">
                    <ul class="navigation navigation-alt navigation-accordion profileMenuList" style="padding: 0;">
                        <li class="navigation-divider"></li>
                        <li class="active" data-view="profileForm" onclick="_changeProfileContainer({elem:this});"><a href="javascript:;"><i class="icon-googleplus5"></i> Хувийн мэдээлэл</a></li>
                        <li class="active" data-view="profileChangePhoto" onclick="_changeProfileContainer({elem:this});"><a href="javascript:;"><i class="icon-googleplus5"></i> Зураг солих</a></li>
                        <li data-view="profileAwardLists" onclick="_changeProfileContainer({elem:this});"><a href="javascript:;"><i class="icon-googleplus5"></i> Шагнал</a></li>
                        <li data-view="profileAdministrativeMeasuresLists" onclick="_changeProfileContainer({elem:this});"><a href="javascript:;"><i class="icon-googleplus5"></i> Хариуцлага</a></li>
                        <li data-view="profileChangePassword" onclick="_changeProfileContainer({elem:this});"><a href="javascript:;"><i class="icon-cog5"></i> Нууц үг солих</a></li>
                        <li class="navigation-divider"></li>
                        <li><a href="<?php echo MY_ADMIN ;?>/logout"><i class="icon-switch2"></i> Гарах</a></li>                    
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /detached content -->

<script type="text/javascript">
    $(function(){
       $('div[data-view="profileInformation"]').trigger('click');
    });
</script>