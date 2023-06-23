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
  <br>
  <!-- row -->
  <div class="row">
    <div class="col-lg-4 col-xs-4">
      <div class="list-group">
        <a href="#" class="list-group-item label-danger">
          Verifikasi
        </a>
        <a href="index.php?p=verifikasi_bno" class="list-group-item"> <i class="fa fa-calendar-check-o"></i> Biaya Umum<span class="badge label-danger"><?php echo $dataBno['jumlah'] >= 1 ? $dataBno['jumlah'] : ''; ?></span></a>
        <a href="index.php?p=verifikasi_kasbon&sp=vk_purchasing" class="list-group-item"> <i class="fa fa-money"></i> Kasbon <span class="badge label-info"><?php echo $jvk >= 1 ? $jvk : ''; ?></span> </a>
        <a href="index.php?p=verifikasi_po" class="list-group-item"> <i class="fa fa-list"></i> Invoice PO <span class="badge label-success"><?php if ($dataPV['jumlah'] > 0) {
                                                                                                                                                echo $dataPV['jumlah'];
                                                                                                                                              } ?></span></a>
        <a href="index.php?p=verifikasi_sr" class="list-group-item"> <i class="fa fa-gear"></i> Service Order <span class="badge label-success"><?php if ($dataSO['jumlah'] > 0) {
                                                                                                                                                  echo $dataSO['jumlah'];
                                                                                                                                                } ?></span></a>
        <a href="index.php?p=verifikasi_bkm" class="list-group-item"><i class="fa fa-print"></i> BKM <span class="badge label-warning"><?= $dataBKM['jumlah'] > 0 ? $dataBKM['jumlah'] : ''; ?></span></a>
        <a href="index.php?p=verifikasi_lpj" class="list-group-item"><i class="fa fa-tags"></i> LPJ <span class="badge label-warning"><?= $dataLPJ['jumlah'] > 0 ? $dataLPJ['jumlah'] : ''; ?></span></a>
      </div>
    </div>
    <div class="col-lg-4 col-xs-4">
      <div class="list-group">
        <a href="#" class="list-group-item label-primary">
          Transaksi
        </a>
        <a href="#" class="list-group-item"> <i class="fa fa-calendar-check-o"></i> Biaya Umum<span class="badge label-danger"></span></a>
        <a href="#" class="list-group-item"> <i class="fa fa-money"></i> Kasbon <span class="badge label-info"></span> </a>
        <a href="#" class="list-group-item"> <i class="fa fa-list"></i> Invoice PO <span class="badge label-success"></span></a>
        <a href="#" class="list-group-item"> <i class="fa fa-gear"></i> Service Order <span class="badge label-success"></span></a>
        <a href="#" class="list-group-item"><i class="fa fa-window-minimize"></i><span class="badge label-warning"></span></a>
        <a href="#" class="list-group-item"><i class="fa fa-window-minimize"></i><span class="badge label-warning"></span></a>
      </div>
    </div>
    <div class="col-lg-4 col-xs-4">
      <div class="list-group">
        <a href="#" class="list-group-item label-warning">
          Ditolak
        </a>
        <a href="#" class="list-group-item"> <i class="fa fa-calendar-check-o"></i> Biaya Umum<span class="badge label-danger"></span></a>
        <a href="index.php?p=ditolak_kasbon&sp=tolak_purchasing" class="list-group-item"> <i class="fa fa-money"></i> Kasbon <span class="badge label-danger"><?php if ($dataKasbonTolak['jumlah'] > 0) {
                                                                                                                                                                echo $dataKasbonTolak['jumlah'];
                                                                                                                                                              } ?></span> </a>
        <a href="index.php?p=ditolak_po" class="list-group-item"> <i class="fa fa-list"></i> Invoice PO <span class="badge label-danger"><?php if ($dataTolakPO['jumlah'] > 0) {
                                                                                                                                            echo $dataTolakPO['jumlah'];
                                                                                                                                          } ?></span></a>
        <a href="#" class="list-group-item"> <i class="fa fa-gear"></i> Service Order <span class="badge label-success"></span></a>
        <a href="index.php?p=ditolak_bkm" class="list-group-item"><i class="fa fa-print"></i> BKM <span class="badge label-danger"><?= $dataTolakBKM['jumlah'] > 0 ? $dataTolakBKM['jumlah'] : ''; ?></span></a>
        <a href="#" class="list-group-item"><i class="fa fa-window-minimize"></i><span class="badge label-warning"></span></a>
      </div>
    </div>
  </div>
  <!-- /row -->


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