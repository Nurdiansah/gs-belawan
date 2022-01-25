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
    <div class="col-lg-3 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item active">
          Verifikasi
        </a>
        <a href="index.php?p=verifikasi_refill" class="list-group-item"> <i class="fa fa-refresh"></i> Refill Funds <span class="badge label-info"><?php echo $dataAR['jumlah'] >= 1 ? $dataAR['jumlah'] : ''; ?></span> </a>
        <a href="index.php?p=approval_bno" class="list-group-item"> <i class="fa fa-calendar-check-o"></i> Biaya Umum<span class="badge label-warning"><?php if ($dataVBU['jumlah'] >= 1) {
                                                                                                                                                          echo $dataVBU['jumlah'];
                                                                                                                                                        } ?></span></a>
        <a href="index.php?p=verifikasi_kasbon&sp=vk_purchasing" class="list-group-item"> <i class="fa fa-money"></i> Kasbon <span class="badge label-info"><?php if ($jkv >= 1) {
                                                                                                                                                              echo $jkv;
                                                                                                                                                            } ?></span> </a>
        <a href="index.php?p=verifikasi_po" class="list-group-item"> <i class="fa fa-list"></i> PO <span class="badge label-success"><?php if ($dataPV['jumlah'] >= 1) {
                                                                                                                                        echo $dataPV['jumlah'];
                                                                                                                                      } ?></span></a>
        <a href="index.php?p=verifikasi_bkk" class="list-group-item"> <i class="fa fa-print"> </i> BKK <span class="badge label-success"><?= $dataBKK['jumlah'] > 0 ? $dataBKK['jumlah'] : ''; ?></span></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item label-default">
          Biaya Umum
        </a>
        <a href="index.php?p=approval_biayanonops" class="list-group-item"> <i class="fa fa-check-square-o"></i> Approval <span class="badge label-danger"><?php if ($data['jumlah'] >= 1) {
                                                                                                                                                              echo $data['jumlah'];
                                                                                                                                                            } ?></span></a>
        <a href="index.php?p=proses_biayanonops" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-info"><?php if ($dataProsesbno['jumlah_proses'] >= 1) {
                                                                                                                                                echo $dataProsesbno['jumlah_proses'];
                                                                                                                                              } ?></span> </a>
        <a href="index.php?p=ditolak_bno" class="list-group-item"> <i class="fa fa-close"> </i> Ditolak <span class="badge label-danger"><?php if ($dataTolakBNO['jumlah'] > 0) {
                                                                                                                                            echo $dataTolakBNO['jumlah'];
                                                                                                                                          } ?></span></a>
        <a href="index.php?p=lihat_bno" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> Transaksi <span class="badge label-success"><?php if ($dataSelesai['jumlah_Selesai'] >= 1) {
                                                                                                                                                  echo $dataSelesai['jumlah_Selesai'];
                                                                                                                                                } ?></span></a>
        <a href="" class="list-group-item"> <i class="fa fa-window-minimize"> </i> <span class="badge label-success"></span></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item label-warning">
          Material Request
        </a>
        <a href="index.php?p=approval_mr" class="list-group-item"> <i class="fa fa-check-square-o"></i> Approval <span class="badge label-danger"><?php if ($dataAM['jumlah'] > 0) {
                                                                                                                                                    echo $dataAM['jumlah'];
                                                                                                                                                  } ?></span></a>
        <a href="index.php?p=proses_mr" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-info"></span> </a>
        <a href="" class="list-group-item"> <i class="fa fa-window-minimize"> </i> <span class="badge label-success"></span></a>
        <a href="" class="list-group-item"> <i class="fa fa-window-minimize"> </i> <span class="badge label-success"></span></a>
        <a href="" class="list-group-item"> <i class="fa fa-window-minimize"> </i> <span class="badge label-success"></span></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item label-success">
          Kasbon
        </a>
        <a href="index.php?p=approval_kasbon" class="list-group-item"> <i class="fa fa-check-square-o"></i> Approval <span class="badge label-danger"><?php if ($dataKasbonDivisi['jumlah'] > 0) {
                                                                                                                                                        echo $dataKasbonDivisi['jumlah'];
                                                                                                                                                      } ?></span></a>
        <a href="index.php?p=proses_kasbon&sp=kp_user" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-info"></span> </a>
        <a href="index.php?p=ditolak_kasbon&sp=tolak_purchasing" class="list-group-item"> <i class="fa fa-close"></i> Ditolak <span class="badge label-danger"><?php if ($totalTolakKasbon > 0) {
                                                                                                                                                                  echo $totalTolakKasbon;
                                                                                                                                                                } ?></span> </a>
        <a href="#" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> Transaksi <span class="badge label-success"></span></a>
        <a href="" class="list-group-item"> <i class="fa fa-window-minimize"> </i> <span class="badge label-success"></span></a>
      </div>
    </div>

  </div>
  <!-- /row -->
  <!-- row -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item label-danger">
          Petty Cash
        </a>
        <a href="index.php?p=approval_pettycash" class="list-group-item"> <i class="fa fa-check-square-o"></i> Approval <span class="badge label-info"><?php echo $dataAP['jumlah'] >= 1 ? $dataAP['jumlah'] : ''; ?></span></a>
        <a href="#" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-info"></span> </a>
        <a href="#" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> Transaksi <span class="badge label-success"></span></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item label-warning">
          Service Order
        </a>
        <a href="index.php?p=approval_sr" class="list-group-item"> <i class="fa fa-check-square-o"></i> Approval <span class="badge label-info"><?php if ($dataSO['jumlah'] > 0) {
                                                                                                                                                  echo $dataSO['jumlah'];
                                                                                                                                                } ?></span></a>
        <a href="index.php?p=proses_sr" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-info"></span> </a>
        <a href="index.php?p=ditolak_so" class="list-group-item"> <i class="fa fa-close"></i> Ditolak <span class="badge label-danger"><?php if ($dataTolakSO['jumlah'] > 0) {
                                                                                                                                          echo $dataTolakSO['jumlah'];
                                                                                                                                        } ?></span></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item label-info">
          Service Request
        </a>
        <a href="index.php?p=approval_srga" class="list-group-item"> <i class="fa fa-check-square-o"></i> Approval <span class="badge label-info"><?php if ($dataSR['jumlah'] == 1) {
                                                                                                                                                    echo $dataSR['jumlah'];
                                                                                                                                                  } ?></span></a>
        <a href="#" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-info"></span> </a>
        <a href="#" class="list-group-item"> <i class="fa fa-window-minimize"> </i> <span class="badge label-success"></span></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item label-primary">
          PO
        </a>
        <a href="#" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-info"></span> </a>
        <a href="index.php?p=ditolak_po" class="list-group-item"> <i class="fa fa-close"></i> Ditolak <span class="badge label-danger"><?php if ($dataTolakPO['jumlah'] > 0) {
                                                                                                                                          echo $dataTolakPO['jumlah'];
                                                                                                                                        } ?></span> </a>
        <a href="#" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> Transaksi <span class="badge label-success"></span></a>
      </div>
    </div>
  </div>
  <br>
  <!-- HTML -->
  <!-- <div id="pie1"></div> -->


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


    // pie 1
    var chart = am4core.create("pie1", am4charts.PieChart3D);
    chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

    chart.data = [{
        country: "Biaya Ops",
        litres: 501.9
      },
      {
        country: "Biaya Umum",
        litres: 301.9
      },
      {
        country: "Rumah Tangga Kantor",
        litres: 201.1
      },
      {
        country: "Repair & Maintenance",
        litres: 165.8
      },
      {
        country: "Telepon Internet & Fax",
        litres: 139.9
      },
      {
        country: "Non Rab",
        litres: 128.3
      }
    ];

    chart.innerRadius = am4core.percent(40);
    chart.depth = 120;

    chart.legend = new am4charts.Legend();

    var series = chart.series.push(new am4charts.PieSeries3D());
    series.dataFields.value = "litres";
    series.dataFields.depthValue = "litres";
    series.dataFields.category = "country";
    series.slices.template.cornerRadius = 5;
    series.colors.step = 3;

  }); // end am4core.ready()
</script>