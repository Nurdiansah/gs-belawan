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
        <a href="index.php?p=biaya_umum" class="list-group-item"> <i class="fa fa-edit"></i> Create <span class="badge label-primary"></span></a>
        <a href="index.php?p=biayaumum_tempo" class="list-group-item"> <i class="fa fa-clock-o"></i> Tempo <span class="badge label-info"><?php echo $dataBUT['jumlah'] >= 1 ? $dataBUT['jumlah'] : ''; ?></span></a>
        <a href="index.php?p=payment_kaskeluar" class="list-group-item"> <i class="fa fa-money"></i> Payment <span class="badge label-warning"><?= $dataBUP >= 1 ? $dataBUP : ''; ?></span></a>
        <a href="index.php?p=proses_payment" class="list-group-item"><i class="fa fa-spinner"></i> Proses<span class="badge label-info"><?= $dataBPP['jumlah'] >= 1 ? $dataBPP['jumlah'] : ''; ?></span></a>
        <a href="index.php?p=lihat_bno" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> Transaksi <span class="badge label-success"></span></a>
      </div>
    </div>
    <div class="col-lg-4 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item label-success">
          BKK
        </a>
        <a href="index.php?p=proses_bkk" class="list-group-item"><i class="fa fa-spinner"></i> Proses<span class="badge label-info"><?php if ($dataBP['jumlah'] >= 1) {
                                                                                                                                      echo $dataBP['jumlah'];
                                                                                                                                    } ?></span> </a>
        <a href="index.php?p=ditolak_bkk" class="list-group-item"><i class="fa fa-close"></i> Ditolak<span class="badge label-info"><?= $totalTolakBKK > 0 ? $totalTolakBKK : ''; ?></span> </a>
        <a href="index.php?p=transaksi_bkk" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> Transaksi<span class="badge label-success"></span></a>
        <a href="#" class="list-group-item"> <i class="fa fa-window-minimize"></i> <span class="badge label-success"></span></a>
        <a href="#" class="list-group-item"> <i class="fa fa-window-minimize"></i> <span class="badge label-success"></span></a>
      </div>
    </div>

    <!-- Refill funds  -->
    <div class="col-lg-4 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item label-primary">
          Refill Fund
        </a>
        <!-- echo $dataBKP['jumlah'] >= 1 ? $dataBKP['jumlah'] : '';  -->
        <a href="index.php?p=create_refill" class="list-group-item"><i class="fa fa-edit"></i> Create<span class="badge label-warning"></span></a>
        <a href="index.php?p=refill_proses" class="list-group-item"><i class="fa fa-spinner"></i> Proses<span class="badge label-info"><?= $dataRP['jumlah'] >= 1 ? $dataRP['jumlah'] : ''; ?></span> </a>
        <a href="index.php?p=refill_transaksi" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> Transaksi <span class="badge label-info"></span></a>
        <a href="#" class="list-group-item"> <i class="fa fa-window-minimize"></i> <span class="badge label-success"></span></a>
        <a href="#" class="list-group-item"> <i class="fa fa-window-minimize"></i> <span class="badge label-success"></span></a>
      </div>
    </div>
  </div>
  <!--  -->
  <!-- SO di bekukan  -->
  <!-- <div class="col-lg-4 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item label-default">
          Service Order
        </a>
        <a href="index.php?p=payment_sr" class="list-group-item"><i class="fa fa-money"></i> Payment<span class="badge label-info"></span> </a>
        <a href="index.php?p=transaksi_sr" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> Transaksi<span class="badge label-success"></span></a>
        <a href="" class="list-group-item"><i class="fa fa-window-minimize"></i> <span class="badge label-info"></span> </a>
        <a href="#" class="list-group-item"><i class="fa fa-minimize"></i> <span class="badge label-info"></span> </a>
      </div>
    </div> -->
  <!--  -->

  <!-- </div> -->
  <!-- /row -->
  <!-- row -->
  <div class="row">
    <div class="col-lg-4 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item label-danger">
          Petty Cash
        </a>
        <a href="index.php?p=payment_pettycash" class="list-group-item"> <i class="fa fa-money"></i> Payment <span class="badge label-warning"><?php echo $dataPTP['jumlah'] >= 1 ? $dataPTP['jumlah'] : ''; ?></span></a>
        <a href="index.php?p=pending_pettycash" class="list-group-item"> <i class="fa fa-hourglass-2"></i> Pending LPJ <span class="badge label-info"><?php echo $dataPTPN['jumlah'] >= 1 ? $dataPTPN['jumlah'] : ''; ?></span></a>
        <a href="index.php?p=verifikasi_pettylpj" class="list-group-item"> <i class="fa fa-tags"></i> Verifikasi LPJ <span class="badge label-info"><?php echo $dataPTV['jumlah'] >= 1 ? $dataPTV['jumlah'] : ''; ?></span></a>
        <a href="index.php?p=transaksi_pettycash" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> Transaksi <span class="badge label-success"></span></a>
      </div>
    </div>
    <div class="col-lg-4 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item label-success">
          Kasbon
        </a>
        <a href="index.php?p=payment_kasbon&sp=pk_purchasing" class="list-group-item"> <i class="fa fa-money"></i> Payment <span class="badge label-warning"><?php if ($jumlahKP >= 1) {
                                                                                                                                                                echo $jumlahKP;
                                                                                                                                                              } ?></span> </a>
        <a href="index.php?p=pending_kasbon&sp=pnk_purchasing" class="list-group-item"> <i class="fa fa-hourglass-2"></i> Pending LPJ <span class="badge label-danger"><?php if ($dataKPL['jumlah'] >= 1) {
                                                                                                                                                                          echo $dataKPL['jumlah'];
                                                                                                                                                                        } ?></span> </a>
        <a href="index.php?p=verifikasi_kasbonlpj&sp=vlk_purchasing" class="list-group-item"> <i class="fa fa-tags"></i> Verifikasi LPJ <span class="badge label-info"><?php if ($jumlahKL >= 1) {
                                                                                                                                                                          echo $jumlahKL;
                                                                                                                                                                        } ?></span> </a>
        <a href="index.php?p=transaksi_kasbon" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> Transaksi <span class="badge label-success"><?php if ($dataKT['jumlah'] >= 1) {
                                                                                                                                                        echo $dataKT['jumlah'];
                                                                                                                                                      } ?></span></a>
      </div>
    </div>
    <div class="col-lg-4 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item label-primary">
          PO
        </a>
        <a href="index.php?p=verifikasi_po" class="list-group-item"> <i class="fa fa-check-square"></i> Verifikasi Term Payment<span class="badge label-info"><?php if ($dataVP['jumlah'] >= 1) {
                                                                                                                                                                echo $dataVP['jumlah'];
                                                                                                                                                              } ?></span></a>
        <!-- <a href="index.php?p=outstanding_po" class="list-group-item"> <i class="fa fa-hourglass-1"></i> Outstanding<span class="badge label-success"><?php if ($dataOP['jumlah'] >= 1) {
                                                                                                                                                            echo $dataOP['jumlah'];
                                                                                                                                                          } ?></span></a> -->
        <a href="index.php?p=list_po" class="list-group-item"> <i class="fa fa-clock-o"></i> Tempo<span class="badge label-warning"><?php if ($dataTP['jumlah'] >= 1) {
                                                                                                                                      echo $dataTP['jumlah'];
                                                                                                                                    } ?></span> </a>
        <a href="index.php?p=payment_po" class="list-group-item"> <i class="fa fa-money"></i> Payment<span class="badge label-warning"><?php echo $dataPRP['jumlah'] >= 1 ? $dataPRP['jumlah'] : ''; ?></span></a>
        <a href="index.php?p=transaksi_po" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> Transaksi<span class="badge label-success"></span></a>
      </div>
    </div>
  </div>
  <br>



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
      "year": 2017,
      "income": 23.5,
      "expenses": 18.1
    }, {
      "year": 2018,
      "income": 26.2,
      "expenses": 22.8
    }, {
      "year": 2019,
      "income": 30.1,
      "expenses": 23.9
    }, {
      "year": 2020,
      "income": 29.5,
      "expenses": 25.1
    }, {
      "year": 2021,
      "income": 24.6,
      "expenses": 25
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