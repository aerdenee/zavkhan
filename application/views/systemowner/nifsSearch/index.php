<div class="page-container">
    <div class="page-content" style="padding-bottom:0;">
        <form action="javascript:;" method="get" id="form-nifs-search" class="input-group" style="width:auto; min-width:500px;">
            <div class="form-group-feedback form-group-feedback-left">
                <input type="text" name="protocolNumber" class="form-control form-control-lg alpha-grey" placeholder="Хэргийн дугаар">
                <div class="form-control-feedback form-control-feedback-lg">
                    <i class="icon-list-numbered text-muted"></i>
                </div>
            </div>
            
            <div class="form-group-feedback form-group-feedback-left" style="width: auto;">
                <input type="text" name="keyword" class="form-control form-control-lg alpha-grey" placeholder="Улсын хэмжээний нэгдсэн хайлт...">
                <div class="form-control-feedback form-control-feedback-lg">
                    <i class="icon-search4 text-muted"></i>
                </div>
            </div>

            <div class="input-group-append">
                <button type="button" class="btn btn-primary btn-lg" onclick="_nifsSearch({elem: this});">Хайх</button>
            </div>
        </form>
    </div>
<!--
    <div class="page-content" style="padding-top:0;">
        <div>Дэлгэрэнгүй хайлт</div>
    </div>-->

    <div class="page-content">
        <div class="w-100" id="window-nifs-search"><?php echo $emptyResult;?></div>
    </div>
</div>