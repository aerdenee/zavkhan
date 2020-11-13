<div class="content-wrapper">

    <!-- Content area -->
    <div class="content">

        <!-- Dashboard content -->
        <div class="row">
            <div class="col-xl-8">


                <!-- Quick stats boxes -->
                <div class="row">
                    <div class="col-lg-3">

                        <div class="card bg-blue-800 animated bounceInDown">
                            <div class="card-body">
                                <div class="d-flex">
                                    <h3 class="font-weight-semibold mb-0"><?php echo $getCrimeCount; ?></h3>
                                    <span class="badge bg-blue-700 badge-pill align-self-center ml-auto">КРИМИНАЛИСТИК</span>
                                </div>

                                <div>
                                    Улсын хэмжээнд <?php echo $getYear; ?> хойш хийгдсэн шинжилгээ
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3">

                        <div class="card bg-blue-700 animated bounceInDown">
                            <div class="card-body">
                                <div class="d-flex">
                                    <h3 class="font-weight-semibold mb-0"><?php echo $getExtraCount; ?></h3>
                                    <span class="badge bg-blue-600 badge-pill align-self-center ml-auto">Тусгай шинжилгээ</span>
                                </div>

                                <div>
                                    Улсын хэмжээнд <?php echo $getYear; ?> хойш хийгдсэн шинжилгээ
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3">

                        <div class="card bg-blue-600 animated bounceInDown">
                            <div class="card-body">
                                <div class="d-flex">
                                    <h3 class="font-weight-semibold mb-0"><?php echo $getEconomyCount; ?></h3>
                                    <span class="badge bg-blue-400 badge-pill align-self-center ml-auto">Эдийн засаг</span>
                                </div>

                                <div>
                                    Улсын хэмжээнд <?php echo $getYear; ?> хойш хийгдсэн шинжилгээ
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3">

                        <div class="card bg-blue-400 animated bounceInDown">
                            <div class="card-body">
                                <div class="d-flex">
                                    <h3 class="font-weight-semibold mb-0"><?php echo $getSendDocumentCount; ?></h3>
                                    <span class="badge bg-blue-300 badge-pill align-self-center ml-auto">Илгээх бичиг</span>
                                </div>

                                <div>
                                    Улсын хэмжээнд <?php echo $getYear; ?> хойш хийгдсэн шинжилгээ
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4">

                        <div class="card bg-purple-800 animated bounceInDown">
                            <div class="card-body">
                                <div class="d-flex">
                                    <h3 class="font-weight-semibold mb-0"><?php echo $getFileFolderCount; ?></h3>
                                    <span class="badge bg-purple-700 badge-pill align-self-center ml-auto">Хавтаст хэрэг</span>
                                </div>

                                <div>
                                    Улсын хэмжээнд <?php echo $getYear; ?> хойш хийгдсэн шинжилгээ
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4">

                        <div class="card bg-purple-700 animated bounceInDown">
                            <div class="card-body">
                                <div class="d-flex">
                                    <h3 class="font-weight-semibold mb-0"><?php echo $getAnatomyCount; ?></h3>
                                    <span class="badge bg-purple-800 badge-pill align-self-center ml-auto">Задлан шинжилгээ</span>
                                </div>

                                <div>
                                    Улсын хэмжээнд <?php echo $getYear; ?> хойш хийгдсэн шинжилгээ
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4">

                        <div class="card bg-purple-600 animated bounceInDown">
                            <div class="card-body">
                                <div class="d-flex">
                                    <h3 class="font-weight-semibold mb-0"><?php echo $getDoctorViewCount; ?></h3>
                                    <span class="badge bg-purple-700 badge-pill align-self-center ml-auto">Эмчийн үзлэг</span>
                                </div>

                                <div>
                                    Улсын хэмжээнд <?php echo $getYear; ?> хойш хийгдсэн шинжилгээ
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
                <!-- /quick stats boxes -->

                <div class="row">
                    <?php echo $learningLists; ?>
                </div>

                <div class="row">
                    <div class="col-12">
                        <?php echo $nifsGeneralChartCity; ?>
                    </div>

                </div>
            </div>

            <div class="col-xl-4">

                <!-- Progress counters -->
                <div class="row">
                    <div class="col-sm-6">

                        <!-- Available hours -->
                        <div class="card text-center animated bounceInLeft">
                            <div class="card-body">

                                <div class="_profile-last-information">
                                    <div class="_photo" style="background-image: url('<?php echo $this->session->userdata['adminPic']; ?>');">
                                        &nbsp;
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-weight-semibold mb-0 pt-3 pb-1" style="text-transform: uppercase;"><?php echo $this->session->userdata['adminFullName']; ?></h5>
                                    <span class="font-size-sm text-muted" style="line-height: 0.9rem; display: block;">Хамгийн сүүлд <?php echo date('Y оны m сарын d-ны H:i цагт', strtotime($this->session->userdata['lastVisitDate'])); ?> системд нэвтэрсэн.</span>
                                </div>

                            </div>
                        </div>
                        <!-- /available hours -->

                    </div>

                    <div class="col-sm-6">

                        <!-- Productivity goal -->
                        <div class="card text-center animated bounceInRight">
                            <div class="card-header header-elements-inline">
                                <div class="header-elements">
                                    <span><i class="icon-history text-warning mr-2"></i>
                                        <?php
                                        (int) $_oneDay = 86400;
                                        (int) $_dayInterval = 30;
                                        $_dayInterval = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')); // 31
                                        $_endDate = date('Ymd');
                                        $diffDay = ($_oneDay * $_dayInterval);

                                        $_beginDate = date('Y.m.d', (strtotime($_endDate) - ($_oneDay * $_dayInterval)));
                                        echo $_beginDate . ' - ' . date('Y.m.d', strtotime($_endDate));
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">

                                <!-- Progress counter -->
                                <div>Системийн хэмжээнд нэг ажилтанд сүүлийн <?php echo $_dayInterval; ?> хоногт дундажаар <strong>52</strong> шинжилгээ хуваарилагдсан байна.  Харин танд <strong>20</strong> байна.</div>
                                <!-- /progress counter -->

                                <!-- Bars -->
                                <div id="goal-bars" style="padding-top: 1.5rem"><svg width="187.8625030517578" height="40"><g width="187.8625030517578"><rect class="d3-random-bars" width="5.411677042643229" x="2.319290161132812" height="24" y="16" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="10.050257364908854" height="38" y="2" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="17.781224568684895" height="40" y="0" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="25.512191772460938" height="22" y="18" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="33.24315897623698" height="26" y="14" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="40.974126180013016" height="32" y="8" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="48.70509338378906" height="22" y="18" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="56.4360605875651" height="30" y="10" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="64.16702779134114" height="36" y="4" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="71.89799499511719" height="26" y="14" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="79.62896219889322" height="32" y="8" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="87.35992940266927" height="34" y="6" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="95.09089660644531" height="26" y="14" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="102.82186381022134" height="22" y="18" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="110.55283101399739" height="36" y="4" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="118.28379821777342" height="36" y="4" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="126.01476542154947" height="24" y="16" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="133.7457326253255" height="40" y="0" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="141.47669982910156" height="36" y="4" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="149.2076670328776" height="30" y="10" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="156.93863423665363" height="36" y="4" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="164.6696014404297" height="24" y="16" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="172.40056864420572" height="36" y="4" style="fill: rgb(92, 107, 192);"></rect><rect class="d3-random-bars" width="5.411677042643229" x="180.13153584798175" height="20" y="20" style="fill: rgb(92, 107, 192);"></rect></g></svg></div>
                                <!-- /bars -->

                            </div>
                        </div>
                        <!-- /productivity goal -->

                    </div>
                </div>
                <!-- /progress counters -->





                <!-- Daily financials -->
                <?php echo $dashboardContactData; ?>
                <!-- /daily financials -->

            </div>
            <div class="col-lg-12">
                <?php echo $nifsGeneralChartProvince; ?>
            </div>
            <div class="col-lg-12">
                <?php echo $chartCenterGeneralData;?>
            </div>
        </div>
        <!-- /dashboard content -->

    </div>
    <!-- /content area -->

</div>