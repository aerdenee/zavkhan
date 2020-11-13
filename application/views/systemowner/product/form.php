<!-- BEGIN PAGE CONTENT -->
<div class="page-content">
    <div class="container">

        <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
        <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Modal title</h4>
                    </div>
                    <div class="modal-body">
                        Widget settings form goes here
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn blue">Save changes</button>
                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->

        <!-- BEGIN PAGE CONTENT INNER -->

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN VALIDATION STATES-->
                <div class="portlet light">

                    <div class="portlet-body form">
                        <?php $rowEdit = $contentEdit; ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-cogs font-green-sharp"></i>
                                        <span class="caption-subject font-green-sharp bold uppercase">Ангилал</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" class="btn grey-cascade btn-xs formBack"><i class="fa fa-arrow-left"></i> Буцах</button>
                            </div>
                            <div class="clearfix"></div>    
                        </div>

                        <hr>
                        <!-- BEGIN FORM-->

                        <div class="form-body">
                            <div class=" portlet-tabs">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#contentNews" data-toggle="tab" aria-expanded="true">Бараа </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="contentNews">
                                        <?php
                                        $data['rowEdit'] = $rowEdit;
                                        $data['organizationList'] = $organizationList;
                                        $this->load->view('systemowner/product/product', $data);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9 text-right">
                                    <button type="button" class="btn grey-cascade btn-xs formBack"><i class="fa fa-arrow-left"></i> Буцах</button>
                                </div>
                            </div>
                        </div>

                        <!-- END FORM-->


                    </div>
                    <!-- END VALIDATION STATES-->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
</div>
<!-- END PAGE CONTENT -->
<script>
    var frmName = '#frmGaz';
    var frmPostUrl = $(frmName).attr('action');
    $(function () {

        $('#picField > div #picUpload').on('change', function (e) {
            $(frmName).ajaxSubmit({
                type: 'post',
                url: '/sproduct/picUpload',
                dataType: 'json',
                success: function (data) {
                    if (data.status === 'success') {
                        var oldData = $('#picField').html();
                        var _html = '<div class="col-md-12"><img src="/upload/image/' + data.response + '" id="demo8"><div class="btn red sm-btn removeUploadPhoto">Болих</div></div>';
                        $('#pic').val(data.response);
                        $('#picField').html(_html);
                        $('#demo8').Jcrop({
                            aspectRatio: 1,
                            onSelect: updateCoords
                        });
                        function updateCoords(c) {
                            $('#crop_x').val(c.x);
                            $('#crop_y').val(c.y);
                            $('#crop_width').val(c.w);
                            $('#crop_height').val(c.h);
                        }
                        ;
                        $(".removeUploadPhoto").on("click", function () {
                            $('#picField').html(oldData);
                        });
                    } else {
                        alert("error");
                    }
                }
            });

        });
        $("#catid").select2();
<?php if ($rowEdit['catid'] != 0): ?>
            $("#catid").select2('val',<?php echo $rowEdit['catid']; ?>).trigger('change');
<?php endif; ?>
        $('#metakey').tagsInput({
            width: 'auto',
            height: '70px',
            defaultText: 'Түлхүүр үг'
        });
        $('#metakey_tagsinput').addClass('form-control');
        $('.formBack').on('click', function () {
            window.location = '/sproduct/<?php echo $modId; ?>';
        });

        $(".formSubmit").on("click", function () {

            $(frmName).validate({
                messages: {
                    catid: "Ангилал сонгоно уу",
                    title: "Мэдээллийн гарчгийг бичнэ үү"
                }
            });
            if ($(frmName).valid()) {
                $(frmName).submit();
            }
        });
    });
</script>