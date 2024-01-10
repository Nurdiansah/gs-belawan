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
    <div class="col-lg-6 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item label-warning">
          Material Request
        </a>

        <a href="index.php?p=list_mr" class="list-group-item"> <i class="fa fa-send"></i> Bidding Process <span class="badge label-info"><?php if ($dataLl['jumlah'] > 0) {
                                                                                                                                            echo $dataLl['jumlah'];
                                                                                                                                          } ?></span></a>
        <a href="index.php?p=ditolak_mr&sp=ditolak_kasbon" class="list-group-item"> <i class="fa fa-close"></i> Ditolak <span class="badge label-danger"><?php if ($totalTolakMR > 0) {
                                                                                                                                                            echo $totalTolakMR;
                                                                                                                                                          } ?></span> </a>
        <a href="index.php?p=proses_mr" class="list-group-item"><i class="fa fa-spinner"></i> Proses<span class="badge label-info"></span> </a>
        <a href="#" class="list-group-item"> <i class="fa fa-window-minimize"></i> <span class="badge label-info"></span> </a>

      </div>
    </div>
    <div class="col-lg-6 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item label-default">
          Service Request
        </a>
        <a href="#index.php?p=verifikasi_sr" class="list-group-item"> <i class="fa fa-check-square"></i> Verifikasi <span class="badge label-warning"><?php echo $dataVS['jumlah'] >= 1 ? $dataVS['jumlah'] : ''; ?></span></a>
        <a href="#index.php?p=submit_kembali_so" class="list-group-item"> <i class="fa fa-refresh"></i> Submit Kembali SO <span class="badge label-info"><?php if ($dataSubmitSO['jumlah'] > 0) {
                                                                                                                                                            echo $dataSubmitSO['jumlah'];
                                                                                                                                                          } ?></span> </a>
        <a href="#index.php?p=ditolak_sr&sp=ditolak_kasbon_sr" class="list-group-item"> <i class="fa fa-close"></i> Ditolak <span class="badge label-danger"><?php if ($totalSR > 0) {
                                                                                                                                                                echo $totalSR;
                                                                                                                                                              } ?></span> </a>
        <a href="#index.php?p=proses_sr" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-info"><?= $dataProsesSO['jumlah'] > 0 ? $dataProsesSO['jumlah'] : '' ?></span> </a>
      </div>
    </div>
  </div>
  <!-- /row -->
  <!-- row -->
  <!-- baris pettycash dan kasbon -->
  <div class="row">
    <!-- Petty Cash -->
    <div class="col-lg-6 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item label-danger">
          Petty Cash
        </a>
        <a href="index.php?p=proses_petty" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-info"><?php if ($dataPetty['jumlah']) {
                                                                                                                                          echo $dataPetty['jumlah'];
                                                                                                                                        } ?></span> </a>
        <a href="index.php?p=lpj_petty" class="list-group-item"> <i class="fa fa-tags"></i> LPJ <span class="badge label-danger"><?php if ($dataLPJPetty['jumlah']) {
                                                                                                                                    echo $dataLPJPetty['jumlah'];
                                                                                                                                  } ?></span></a>
        <a href="index.php?p=transaksi_pettycash" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> History <span class="badge label-success"></span></a>
        <a href="" class="list-group-item"> <i class="fa fa"></i> <span class="badge label-info"></span> </a>
        <a href="" class="list-group-item"> <i class="fa fa"></i> <span class="badge label-info"></span> </a>
      </div>
    </div>
    <!-- End Pettycash -->
    <!-- Kasbon -->
    <div class="col-lg-6 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item label-success">
          Kasbon
        </a>
        <a href="index.php?p=submit_kasbon" class="list-group-item"> <i class="fa fa-refresh"></i> Submit Kembali Kasbon <span class="badge label-info"><?php if ($dataSK['jumlah'] >= 1) {
                                                                                                                                                          echo $dataSK['jumlah'];
                                                                                                                                                        } ?></span> </a>
        <a href="index.php?p=kasbon_process&sp=proses_kasbon_mr" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-info"><?php if ($totalKpKs >= 1) {
                                                                                                                                                                echo $totalKpKs;
                                                                                                                                                              } ?></span> </a>
        <a href="index.php?p=lpj_kasbon&sp=lpj_kmr" class="list-group-item"> <i class="fa fa-tags"></i> LPJ Kasbon <span class="badge label-warning"><?php if ($jumlahKl >= 1) {
                                                                                                                                                        echo $jumlahKl;
                                                                                                                                                      } ?></span></a>
        <a href="index.php?p=transaksi_kasbon" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> History <span class="badge label-success"></span></a>
        <a href="" class="list-group-item"> <i class="fa fa-strip"></i> <span class="badge label-success"></span></a>
      </div>
    </div>

  </div>
  <!-- Akhir baris pettycash dan kasbon -->

  <!-- Baris po dan so  -->
  <div class="row">
    <div class="col-lg-6 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item label-primary">
          PO
        </a>
        <a href="index.php?p=submit_po" class="list-group-item"> <i class="fa fa-share"></i> Submit Quatation <span class="badge label-info"><?php if ($dataQtt['jumlah'] >= 1) {
                                                                                                                                                echo $dataQtt['jumlah'];
                                                                                                                                              } ?></span> </a>
        <a href="index.php?p=submit_kembali_po" class="list-group-item"> <i class="fa fa-share"></i> Submit PO <span class="badge label-info"><?php if ($dataSPO['jumlah'] > 0) {
                                                                                                                                                echo $dataSPO['jumlah'];
                                                                                                                                              } ?></span> </a>
        <a href="index.php?p=po_proses" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-warning"><?php echo $dataPOP['jumlah'] >= 1 ? $dataPOP['jumlah'] : ''; ?></span></a>
        <a href="index.php?p=po_rtp" class="list-group-item"> <i class="fa fa-money"></i> Ready To Pay <span class="badge label-success"><?php echo $dataPOR['jumlah'] >= 1 ? $dataPOR['jumlah'] : ''; ?></span></a>
        <a href="index.php?p=po_outstanding" class="list-group-item"> <i class="fa fa-hourglass-1"></i> Outstanding <span class="badge label-danger"><?php if ($dataOpo['jumlah'] >= 1) {
                                                                                                                                                        echo $dataOpo['jumlah'];
                                                                                                                                                      } ?></span></a>
        <a href="index.php?p=transaksi_po" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> History <span class="badge label-success"></span></a>
      </div>
    </div>
    <div class="col-lg-6 col-xs-6">
      <div class="list-group">
        <a href="#" class="list-group-item label-default">
          SO
        </a>
        <a href="" class="list-group-item"> <i class="fa fa-share"></i> Submit Quatation <span class="badge label-info"></span> </a>
        <a href="" class="list-group-item"> <i class="fa fa-share"></i> Submit PO <span class="badge label-info"></span> </a>
        <a href="" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-info"></span> </a>
        <a href="" class="list-group-item"> <i class="fa fa-hourglass-1"></i> Outstanding <span class="badge label-danger"></span></a>
        <a href="" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> History <span class="badge label-success"></span></a>
        <a href="" class="list-group-item"> <i class="fa fa-baro"></i> <span class="badge label-success"></span></a>
      </div>
    </div>
  </div>
  <!-- akhir baris po dan so -->

  <a href="https://wa.me/6289508012755?text=Assalamu'alaikum saya <?= $Nama; ?> mau tanya perihal Efin" target="_blank" title="Revisi/Hapus data, bisa di Email"><i class="fa fa-whatsapp kontak-wa"></i></a>
  <a href="mailto:develop@ekanuri.com?cc=nurdiansah@ekanuri.com&subject=Revisi Pengajuan xxx" target="_blank" title="Revisi/Hapus data, bisa request disini"><i class="fa fa-envelope kontak-email"></i></a>

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
      "year": 2015,
      "income": 23.5,
      "expenses": 18.1
    }, {
      "year": 2016,
      "income": 26.2,
      "expenses": 22.8
    }, {
      "year": 2017,
      "income": 30.1,
      "expenses": 23.9
    }, {
      "year": 2018,
      "income": 29.5,
      "expenses": 25.1
    }, {
      "year": 2019,
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