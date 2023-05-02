 <section class="content-header">
   <h1>
     Dashboard
     <small>Control panel</small>
   </h1>
   <ol class="breadcrumb">
     <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
     <li class="active">Dashboard</li>
   </ol>
 </section>

 <!-- Main content -->
 <section class="content">
   <!-- Small boxes (Stat box) -->
   <!-- HTML -->
   <!-- <div id="chartdiv"></div> -->
   <!-- row -->
   <div class="row">
     <div class="col-lg-4 col-xs-6">
       <div class="list-group">
         <a href="#" class="list-group-item active">
           Biaya Umum
         </a>
         <a href="index.php?p=approval_biayanonops" class="list-group-item"> <i class="fa fa-check-square-o"></i> Approval <span class="badge label-danger"><?php if ($data['jumlah'] >= 1) {
                                                                                                                                                              echo $data['jumlah'];
                                                                                                                                                            } ?></span></a>
         <a href="index.php?p=proses_biayanonops" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-info"><?php if ($dataProsesbno['jumlah_proses'] >= 1) {
                                                                                                                                                  echo $dataProsesbno['jumlah_proses'];
                                                                                                                                                } ?></span> </a>
         <a href="index.php?p=ditolak_bno" class="list-group-item"> <i class="fa fa-close"></i> Ditolak <span class="badge label-danger"><?php if ($dataTolakBNO['jumlah'] > 0) {
                                                                                                                                            echo $dataTolakBNO['jumlah'];
                                                                                                                                          } ?></span> </a>
         <a href="index.php?p=lihat_bno" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> Transaksi <span class="badge label-success"></span></a>
       </div>
     </div>
     <div class="col-lg-4 col-xs-6">
       <div class="list-group">
         <a href="#" class="list-group-item label-warning">
           Material Request
         </a>
         <a href="index.php?p=approval_mr" class="list-group-item"> <i class="fa fa-check-square-o"></i> Approval <span class="badge label-danger"><?php if ($dataAM['jumlah'] > 0) {
                                                                                                                                                      echo $dataAM['jumlah'];
                                                                                                                                                    } ?></span></a>
         <a href="index.php?p=proses_mr" class="list-group-item"><i class="fa fa-spinner"></i>Proses<span class="badge label-info"><?php if ($dataPMR['jumlah'] > 0) {
                                                                                                                                      echo $dataPMR['jumlah'];
                                                                                                                                    } ?></span> </a>
         <a href="#" class="list-group-item"> <i class="fa fa-window-minimize"> </i> <span class="badge label-success"></span></a>
         <a href="#" class="list-group-item"> <i class="fa fa-window-minimize"> </i> <span class="badge label-success"></span></a>
       </div>
     </div>
     <div class="col-lg-4 col-xs-6">
       <div class="list-group">
         <a href="#" class="list-group-item label-danger">
           Petty Cash
         </a>
         <a href="index.php?p=approval_pettycash" class="list-group-item"> <i class="fa fa-check-square-o"></i> Approval <span class="badge label-warning"><?php echo $dataAP['jumlah'] >= 1 ? $dataAP['jumlah'] : ''; ?></span></a>
         <a href="index.php?p=proses_pettycash" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-info"><?= $dataProsesPetty['jumlah'] > 0 ? $dataProsesPetty['jumlah'] : ''; ?></span> </a>
         <a href="index.php?p=transaksi_pettycash" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> Transaksi <span class="badge label-success"></span></a>
         <a href="#" class="list-group-item"> <i class="fa fa-window-minimize"> </i> <span class="badge label-success"></span></a>
       </div>
     </div>
     <!-- </div> -->
     <!-- /row -->
     <!-- row -->
     <!-- <div class="row"> -->
     <div class="col-lg-4 col-xs-6">
       <div class="list-group">
         <a href="#" class="list-group-item label-default">
           Service Order
         </a>
         <a href="index.php?p=approval_sr" class="list-group-item"> <i class="fa fa-check-square-o"></i> Approval <span class="badge label-info"><?php if ($dataSR['jumlah'] > 0) {
                                                                                                                                                    echo $dataSR['jumlah'];
                                                                                                                                                  } ?></span></a>
         <a href="index.php?p=proses_sr" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-info"></span> </a>
         <a href="#" class="list-group-item"> <i class="fa fa-window-minimize"> </i> <span class="badge label-success"></span></a>
         <a href="#" class="list-group-item"> <i class="fa fa-window-minimize"> </i> <span class="badge label-success"></span></a>
       </div>
     </div>
     <div class="col-lg-4 col-xs-6">
       <div class="list-group">
         <a href="#" class="list-group-item label-success">
           Kasbon
         </a>
         <a href="index.php?p=approval_kasbon" class="list-group-item"> <i class="fa fa-check-square-o"></i> Approval <span class="badge label-info"><?php echo $dataAK['jumlah'] >= 1 ? $dataAK['jumlah'] : ''; ?></span></a>
         <a href="index.php?p=proses_kasbon&sp=kp_purchasing" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-info"><?= $totalProsesKasbon > 0 ? $totalProsesKasbon : ''; ?></span> </a>
         <a href="index.php?p=ditolak_kasbon&sp=tolak_purchasing" class="list-group-item"> <i class="fa fa-close"></i> Ditolak <span class="badge label-danger"><?php if ($total_tolak > 0) {
                                                                                                                                                                  echo $total_tolak;
                                                                                                                                                                } ?></span> </a>
         <a href="index.php?p=transaksi_kasbon" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> Transaksi <span class="badge label-success"></span></a>
       </div>
     </div>
     <div class="col-lg-4 col-xs-6">
       <div class="list-group">
         <a href="#" class="list-group-item label-primary">
           PO
         </a>
         <a href="index.php?p=po_proses" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-info"></span> </a>
         <a href="index.php?p=po_transaksi" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> Transaksi <span class="badge label-success"></span></a>
         <a href="#" class="list-group-item"> <i class="fa fa-window-minimize"> </i> <span class="badge label-success"></span></a>
         <a href="#" class="list-group-item"> <i class="fa fa-window-minimize"> </i> <span class="badge label-success"></span></a>
       </div>
     </div>
   </div>
   <br>
   <!-- HTML -->


 </section>

 <!-- Chart code -->
 <script>
   am4core.ready(function() {

     // Themes begin
     am4core.useTheme(am4themes_animated);
     // Themes end

     // Create chart instance
     var chart = am4core.create("chartdiv", am4charts.XYChart);

     // Add data
     chart.data = [{
       "year": 2016,
       "income": 23.5,
       "expenses": 18.1
     }, {
       "year": 2017,
       "income": 26.2,
       "expenses": 22.8
     }, {
       "year": 2018,
       "income": 30.1,
       "expenses": 23.9
     }, {
       "year": 2019,
       "income": 29.5,
       "expenses": 25.1
     }, {
       "year": 2020,
       "income": 24.6,
       "expenses": 5
     }];

     // Create axes
     var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
     categoryAxis.dataFields.category = "year";
     categoryAxis.numberFormatter.numberFormat = "#";
     categoryAxis.renderer.inversed = true;
     categoryAxis.renderer.grid.template.location = 0;
     categoryAxis.renderer.cellStartLocation = 0.1;
     categoryAxis.renderer.cellEndLocation = 0.9;

     var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
     valueAxis.renderer.opposite = true;

     // Create series
     function createSeries(field, name) {
       var series = chart.series.push(new am4charts.ColumnSeries());
       series.dataFields.valueX = field;
       series.dataFields.categoryY = "year";
       series.name = name;
       series.columns.template.tooltipText = "{name}: [bold]{valueX}[/]";
       series.columns.template.height = am4core.percent(100);
       series.sequencedInterpolation = true;

       var valueLabel = series.bullets.push(new am4charts.LabelBullet());
       valueLabel.label.text = "{valueX}";
       valueLabel.label.horizontalCenter = "left";
       valueLabel.label.dx = 10;
       valueLabel.label.hideOversized = false;
       valueLabel.label.truncate = false;

       var categoryLabel = series.bullets.push(new am4charts.LabelBullet());
       categoryLabel.label.text = "{name}";
       categoryLabel.label.horizontalCenter = "right";
       categoryLabel.label.dx = -10;
       categoryLabel.label.fill = am4core.color("#fff");
       categoryLabel.label.hideOversized = false;
       categoryLabel.label.truncate = false;
     }

     createSeries("income", "Anggaran");
     createSeries("expenses", "Realisasi");




   }); // end am4core.ready()
 </script>