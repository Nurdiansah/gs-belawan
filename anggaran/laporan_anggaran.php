<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$tahunSekarang = date('Y');

if (isset($_POST['cari'])) {
    $tahun = $_POST['tahun'];
    $divisi = $_POST['divisi'];

    // jika milih semua divisi jalanin query yg atas else bawahnya
    if ($divisi == "all") {
        $queryChart = mysqli_query($koneksi, "SELECT SUM(januari_nominal) as januari_nominal, SUM(januari_realisasi) as januari_realisasi,
                                                    SUM(februari_nominal) as februari_nominal, SUM(februari_realisasi) as februari_realisasi, 
                                                    SUM(maret_nominal) as maret_nominal, SUM(maret_realisasi) as maret_realisasi, 
                                                    SUM(april_nominal) as april_nominal, SUM(april_realisasi) as april_realisasi, 
                                                    SUM(mei_nominal) as mei_nominal, SUM(mei_realisasi) as mei_realisasi, 
                                                    SUM(juni_nominal) as juni_nominal, SUM(juni_realisasi) as juni_realisasi, 
                                                    SUM(juli_nominal) as juli_nominal, SUM(juli_realisasi) as juli_realisasi, 
                                                    SUM(agustus_nominal) as agustus_nominal, SUM(agustus_realisasi) as agustus_realisasi, 
                                                    SUM(september_nominal) as september_nominal, SUM(september_realisasi) as september_realisasi, 
                                                    SUM(oktober_nominal) as oktober_nominal, SUM(oktober_realisasi) as oktober_realisasi, 
                                                    SUM(november_nominal) as november_nominal, SUM(november_realisasi) as november_realisasi, 
                                                    SUM(desember_nominal) as desember_nominal, SUM(desember_realisasi) as desember_realisasi,
                                                    SUM(januari_nominal) + SUM(februari_nominal) + SUM(maret_nominal) + SUM(april_nominal) + SUM(mei_nominal) + SUM(juni_nominal) + SUM(juli_nominal) + SUM(agustus_nominal) + SUM(september_nominal) + SUM(oktober_nominal) + SUM(november_nominal) + SUM(desember_nominal) as total_nominal,
                                                    SUM(januari_realisasi) + SUM(februari_realisasi) + SUM(maret_realisasi) + SUM(april_realisasi) + SUM(mei_realisasi) + SUM(juni_realisasi) + SUM(juli_realisasi) + SUM(agustus_realisasi) + SUM(september_realisasi) + SUM(oktober_realisasi) + SUM(november_realisasi) + SUM(desember_realisasi) as total_realisasi
                                                FROM anggaran
                                                WHERE tahun = '$tahun'");
    } else {
        $queryChart = mysqli_query($koneksi, "SELECT SUM(januari_nominal) as januari_nominal, SUM(januari_realisasi) as januari_realisasi,
                                                    SUM(februari_nominal) as februari_nominal, SUM(februari_realisasi) as februari_realisasi, 
                                                    SUM(maret_nominal) as maret_nominal, SUM(maret_realisasi) as maret_realisasi, 
                                                    SUM(april_nominal) as april_nominal, SUM(april_realisasi) as april_realisasi, 
                                                    SUM(mei_nominal) as mei_nominal, SUM(mei_realisasi) as mei_realisasi, 
                                                    SUM(juni_nominal) as juni_nominal, SUM(juni_realisasi) as juni_realisasi, 
                                                    SUM(juli_nominal) as juli_nominal, SUM(juli_realisasi) as juli_realisasi, 
                                                    SUM(agustus_nominal) as agustus_nominal, SUM(agustus_realisasi) as agustus_realisasi, 
                                                    SUM(september_nominal) as september_nominal, SUM(september_realisasi) as september_realisasi, 
                                                    SUM(oktober_nominal) as oktober_nominal, SUM(oktober_realisasi) as oktober_realisasi, 
                                                    SUM(november_nominal) as november_nominal, SUM(november_realisasi) as november_realisasi, 
                                                    SUM(desember_nominal) as desember_nominal, SUM(desember_realisasi) as desember_realisasi,
                                                    SUM(januari_nominal) + SUM(februari_nominal) + SUM(maret_nominal) + SUM(april_nominal) + SUM(mei_nominal) + SUM(juni_nominal) + SUM(juli_nominal) + SUM(agustus_nominal) + SUM(september_nominal) + SUM(oktober_nominal) + SUM(november_nominal) + SUM(desember_nominal) as total_nominal,
                                                    SUM(januari_realisasi) + SUM(februari_realisasi) + SUM(maret_realisasi) + SUM(april_realisasi) + SUM(mei_realisasi) + SUM(juni_realisasi) + SUM(juli_realisasi) + SUM(agustus_realisasi) + SUM(september_realisasi) + SUM(oktober_realisasi) + SUM(november_realisasi) + SUM(desember_realisasi) as total_realisasi
                                            FROM anggaran
                                            WHERE tahun = '$tahun'
                                            AND id_divisi = '$divisi'");
    }

    $queryDiv = mysqli_query($koneksi, "SELECT * FROM divisi WHERE id_divisi = '$divisi'");
    $dataDiv = mysqli_fetch_assoc($queryDiv);
} else {
    $queryChart = mysqli_query($koneksi, "SELECT SUM(januari_nominal) as januari_nominal, SUM(januari_realisasi) as januari_realisasi,
                                                    SUM(februari_nominal) as februari_nominal, SUM(februari_realisasi) as februari_realisasi, 
                                                    SUM(maret_nominal) as maret_nominal, SUM(maret_realisasi) as maret_realisasi, 
                                                    SUM(april_nominal) as april_nominal, SUM(april_realisasi) as april_realisasi, 
                                                    SUM(mei_nominal) as mei_nominal, SUM(mei_realisasi) as mei_realisasi, 
                                                    SUM(juni_nominal) as juni_nominal, SUM(juni_realisasi) as juni_realisasi, 
                                                    SUM(juli_nominal) as juli_nominal, SUM(juli_realisasi) as juli_realisasi, 
                                                    SUM(agustus_nominal) as agustus_nominal, SUM(agustus_realisasi) as agustus_realisasi, 
                                                    SUM(september_nominal) as september_nominal, SUM(september_realisasi) as september_realisasi, 
                                                    SUM(oktober_nominal) as oktober_nominal, SUM(oktober_realisasi) as oktober_realisasi, 
                                                    SUM(november_nominal) as november_nominal, SUM(november_realisasi) as november_realisasi, 
                                                    SUM(desember_nominal) as desember_nominal, SUM(desember_realisasi) as desember_realisasi,
                                                    SUM(januari_nominal) + SUM(februari_nominal) + SUM(maret_nominal) + SUM(april_nominal) + SUM(mei_nominal) + SUM(juni_nominal) + SUM(juli_nominal) + SUM(agustus_nominal) + SUM(september_nominal) + SUM(oktober_nominal) + SUM(november_nominal) + SUM(desember_nominal) as total_nominal,
                                                    SUM(januari_realisasi) + SUM(februari_realisasi) + SUM(maret_realisasi) + SUM(april_realisasi) + SUM(mei_realisasi) + SUM(juni_realisasi) + SUM(juli_realisasi) + SUM(agustus_realisasi) + SUM(september_realisasi) + SUM(oktober_realisasi) + SUM(november_realisasi) + SUM(desember_realisasi) as total_realisasi
                                            FROM anggaran
                                            WHERE tahun = '$tahunSekarang'");
}
$dataChart = mysqli_fetch_assoc($queryChart);


$queryDivisi = mysqli_query($koneksi, "SELECT * FROM divisi WHERE id_divisi <> '0' ORDER BY nm_divisi ASC");

?>

<section class="content">
    <div class="row">
        <div class="col-sm-6">
            <a href="#" class="btn btn-default btn-lg"><i class="fa fa-bar-chart-o"></i> Anggaran</a>
            <a href="index.php?p=laporan_programkerja" class="btn btn-success btn-lg"><i class="fa fa-signal"></i> Program Kerja</a>
        </div>
        <br>
    </div>
    <div class="row">
        <h3 class="text-center">Grafik Laporan Anggaran</h3>
        <?php if (isset($_POST['divisi']) && $_POST['divisi'] != "all") { ?>
            <h4 class="text-center">(Divisi <?php echo $dataDiv['nm_divisi'] . " " . $_POST['tahun'] ?>)</h4>
        <?php } ?>
        <form action="" method="POST">
            <div class="form-group mt-3">
                <div class="col-sm-offset- col-sm-2">
                    <select id="select2" name="divisi" class="form-control" required>
                        <option value="all">Semua Divisi</option>
                        <?php if (isset($_POST['cari'])) {
                            while ($dataDivisi = mysqli_fetch_assoc($queryDivisi)) { ?>
                                <option value="<?= $dataDivisi['id_divisi']; ?>" <?php if ($dataDivisi['id_divisi'] == $_POST['divisi']) {
                                                                                        echo "selected=selected";
                                                                                    } ?>><?= $dataDivisi['nm_divisi']; ?></option>
                            <?php }
                        } else { ?>
                            <?php
                            while ($dataDivisi = mysqli_fetch_assoc($queryDivisi)) { ?>
                                <option value="<?= $dataDivisi['id_divisi']; ?>"><?= $dataDivisi['nm_divisi']; ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset- col-sm-2">
                    <select name="tahun" class="form-control" required>
                        <?php
                        if (isset($_POST['cari'])) {
                            foreach (range(2021, $tahunSekarang + 1) as $tahun) { ?>
                                <option value="<?= $tahun; ?>" <?php if ($tahun == $_POST['tahun']) {
                                                                    echo "selected=selected";
                                                                } ?>><?= $tahun; ?></option>
                            <?php }
                        } else {
                            foreach (range(2021, $tahunSekarang + 1) as $tahun) { ?>
                                <option value="<?= $tahun; ?>" <?php if ($tahun == $tahunSekarang) {
                                                                    echo "selected=selected";
                                                                } ?>><?= $tahun; ?></option>
                        <?php }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <button type="submit" name="cari" class="btn bg-primary"><i class="fa fa-search"></i> Cari</button>
        </form>
        <br>
        <!-- AMCHART -->
        <div id="chartdiv"></div>
        <br>
        <!-- TABEL -->
        <div class="col-sm-12 ">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Table Laporan Anggaran</h3>
                    <?php if (isset($_POST['divisi']) && $_POST['divisi'] != "all") { ?>
                        <h4 class="text-center">(Divisi <?php echo $dataDiv['nm_divisi'] . " " . $_POST['tahun'] ?>)</h4>
                    <?php } ?>
                </div>
                <div class="box-body">
                    <table class="table table-striped">
                        <thead class="bg-primary">
                            <tr>
                                <th>No</th>
                                <th>Bulan</th>
                                <th>Nominal</th>
                                <th>Realisasi</th>
                                <th>Surplus (Defisit)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr <?= warnaSurplus($dataChart['januari_realisasi'], $dataChart['januari_nominal']); ?>>
                                <td>1</td>
                                <td>Januari</td>
                                <td><?= formatRupiah($dataChart['januari_nominal']); ?></td>
                                <td><?= formatRupiah($dataChart['januari_realisasi']); ?></td>
                                <td><?= kurungSurplus($dataChart['januari_nominal'], $dataChart['januari_realisasi']); ?></td>
                            </tr>
                            <tr <?= warnaSurplus($dataChart['februari_realisasi'], $dataChart['februari_nominal']); ?>>
                                <td>2</td>
                                <td>Februari</td>
                                <td><?= formatRupiah($dataChart['februari_nominal']); ?></td>
                                <td><?= formatRupiah($dataChart['februari_realisasi']); ?></td>
                                <td><?= kurungSurplus($dataChart['februari_nominal'], $dataChart['februari_realisasi']); ?></td>
                            </tr>
                            <tr <?= warnaSurplus($dataChart['maret_realisasi'], $dataChart['maret_nominal']); ?>>
                                <td>3</td>
                                <td>Maret</td>
                                <td><?= formatRupiah($dataChart['maret_nominal']); ?></td>
                                <td><?= formatRupiah($dataChart['maret_realisasi']); ?></td>
                                <td><?= kurungSurplus($dataChart['maret_nominal'], $dataChart['maret_realisasi']); ?></td>
                            </tr>
                            <tr <?= warnaSurplus($dataChart['april_realisasi'], $dataChart['april_nominal']); ?>>
                                <td>4</td>
                                <td>April</td>
                                <td><?= formatRupiah($dataChart['april_nominal']); ?></td>
                                <td><?= formatRupiah($dataChart['april_realisasi']); ?></td>
                                <td><?= kurungSurplus($dataChart['april_nominal'], $dataChart['april_realisasi']); ?></td>
                            </tr>
                            <tr <?= warnaSurplus($dataChart['mei_realisasi'], $dataChart['mei_nominal']); ?>>
                                <td>5</td>
                                <td>Mei</td>
                                <td><?= formatRupiah($dataChart['mei_nominal']); ?></td>
                                <td><?= formatRupiah($dataChart['mei_realisasi']); ?></td>
                                <td><?= kurungSurplus($dataChart['mei_nominal'], $dataChart['mei_realisasi']); ?></td>
                            </tr>
                            <tr <?= warnaSurplus($dataChart['juni_realisasi'], $dataChart['juni_nominal']); ?>>
                                <td>6</td>
                                <td>Juni</td>
                                <td><?= formatRupiah($dataChart['juni_nominal']); ?></td>
                                <td><?= formatRupiah($dataChart['juni_realisasi']); ?></td>
                                <td><?= kurungSurplus($dataChart['juni_nominal'], $dataChart['juni_realisasi']); ?></td>
                            </tr>
                            <tr <?= warnaSurplus($dataChart['juli_realisasi'], $dataChart['juli_nominal']); ?>>
                                <td>7</td>
                                <td>Juli</td>
                                <td><?= formatRupiah($dataChart['juli_nominal']); ?></td>
                                <td><?= formatRupiah($dataChart['juli_realisasi']); ?></td>
                                <td><?= kurungSurplus($dataChart['juli_nominal'], $dataChart['juli_realisasi']); ?></td>
                            </tr>
                            <tr <?= warnaSurplus($dataChart['agustus_realisasi'], $dataChart['agustus_nominal']); ?>>
                                <td>8</td>
                                <td>Agustus</td>
                                <td><?= formatRupiah($dataChart['agustus_nominal']); ?></td>
                                <td><?= formatRupiah($dataChart['agustus_realisasi']); ?></td>
                                <td><?= kurungSurplus($dataChart['agustus_nominal'], $dataChart['agustus_realisasi']); ?></td>
                            </tr>
                            <tr <?= warnaSurplus($dataChart['september_realisasi'], $dataChart['september_nominal']); ?>>
                                <td>9</td>
                                <td>September</td>
                                <td><?= formatRupiah($dataChart['september_nominal']); ?></td>
                                <td><?= formatRupiah($dataChart['september_realisasi']); ?></td>
                                <td><?= kurungSurplus($dataChart['september_nominal'], $dataChart['september_realisasi']); ?></td>
                            </tr>
                            <tr <?= warnaSurplus($dataChart['oktober_realisasi'], $dataChart['oktober_nominal']); ?>>
                                <td>10</td>
                                <td>Oktober</td>
                                <td><?= formatRupiah($dataChart['oktober_nominal']); ?></td>
                                <td><?= formatRupiah($dataChart['oktober_realisasi']); ?></td>
                                <td><?= kurungSurplus($dataChart['oktober_nominal'], $dataChart['oktober_realisasi']); ?></td>
                            </tr>
                            <tr <?= warnaSurplus($dataChart['november_realisasi'], $dataChart['november_nominal']); ?>>
                                <td>11</td>
                                <td>November</td>
                                <td><?= formatRupiah($dataChart['november_nominal']); ?></td>
                                <td><?= formatRupiah($dataChart['november_realisasi']); ?></td>
                                <td><?= kurungSurplus($dataChart['november_nominal'], $dataChart['november_realisasi']); ?></td>
                            </tr>
                            <tr <?= warnaSurplus($dataChart['desember_realisasi'], $dataChart['desember_nominal']); ?>>
                                <td>12</td>
                                <td>Desember</td>
                                <td><?= formatRupiah($dataChart['desember_nominal']); ?></td>
                                <td><?= formatRupiah($dataChart['desember_realisasi']); ?></td>
                                <td><?= kurungSurplus($dataChart['desember_nominal'], $dataChart['desember_realisasi']); ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <h3>#</h3>
                                </td>
                                <td>
                                    <h3>Total</h3>
                                </td>
                                <td>
                                    <h3><?= formatRupiah($dataChart['total_nominal']); ?></h3>
                                </td>
                                <td>
                                    <h3><?= formatRupiah($dataChart['total_realisasi']); ?></h3>
                                </td>
                                <td>
                                    <h3>
                                        <?= kurungSurplus($dataChart['total_nominal'], $dataChart['total_realisasi']); ?>
                                    </h3>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</section>


<!-- Styles -->
<style>
    #chartdiv {
        width: 100%;
        height: 500px;
    }
</style>

<!-- REMARK, KARNA PAKE YG DIINDEX -->
<!-- Resources -->
<!-- <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script> -->

<!-- Chart code -->
<script>
    am4core.ready(function() {

        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        // Create chart instance
        var chart = am4core.create("chartdiv", am4charts.XYChart);

        // Export
        chart.exporting.menu = new am4core.ExportMenu();

        // Data for both series
        var data = [{
                "bulan": "Januari",
                "budget": <?= $dataChart['januari_nominal']; ?>,
                "realisasi": <?= $dataChart['januari_realisasi']; ?>,
                <?php if ($dataChart['januari_realisasi'] == 0) { ?> "lineDash": "5,5",
                    "columnDash": "5,5",
                    "fillOpacity": 0.2,
                    "additional": "(Relisasi 0)",
                <?php } ?>
            }, {
                "bulan": "Februari",
                "budget": <?= $dataChart['februari_nominal']; ?>,
                "realisasi": <?= $dataChart['februari_realisasi']; ?>,
                <?php if ($dataChart['februari_realisasi'] == 0) { ?> "lineDash": "5,5",
                    "columnDash": "5,5",
                    "fillOpacity": 0.2,
                    "additional": "(Relisasi 0)",
                <?php } ?>
            }, {
                "bulan": "Maret",
                "budget": <?= $dataChart['maret_nominal']; ?>,
                "realisasi": <?= $dataChart['maret_realisasi']; ?>,
                <?php if ($dataChart['maret_realisasi'] == 0) { ?> "lineDash": "5,5",
                    "columnDash": "5,5",
                    "fillOpacity": 0.2,
                    "additional": "(Relisasi 0)",
                <?php } ?>
            }, {
                "bulan": "April",
                "budget": <?= $dataChart['april_nominal']; ?>,
                "realisasi": <?= $dataChart['april_realisasi']; ?>,
                <?php if ($dataChart['april_realisasi'] == 0) { ?> "lineDash": "5,5",
                    "columnDash": "5,5",
                    "fillOpacity": 0.2,
                    "additional": "(Relisasi 0)",
                <?php } ?>
            }, {
                "bulan": "Mei",
                "budget": <?= $dataChart['mei_nominal']; ?>,
                "realisasi": <?= $dataChart['mei_realisasi']; ?>,
                <?php if ($dataChart['mei_realisasi'] == 0) { ?> "lineDash": "5,5",
                    "columnDash": "5,5",
                    "fillOpacity": 0.2,
                    "additional": "(Relisasi 0)",
                <?php } ?>
            }, {
                "bulan": "Juni",
                "budget": <?= $dataChart['juni_nominal']; ?>,
                "realisasi": <?= $dataChart['juni_realisasi']; ?>,
                <?php if ($dataChart['juni_realisasi'] == 0) { ?> "lineDash": "5,5",
                    "columnDash": "5,5",
                    "fillOpacity": 0.2,
                    "additional": "(Relisasi 0)",
                <?php } ?>
            }, {
                "bulan": "Juli",
                "budget": <?= $dataChart['juli_nominal']; ?>,
                "realisasi": <?= $dataChart['juli_realisasi']; ?>,
                <?php if ($dataChart['juli_realisasi'] == 0) { ?> "lineDash": "5,5",
                    "columnDash": "5,5",
                    "fillOpacity": 0.2,
                    "additional": "(Relisasi 0)",
                <?php } ?>
            }, {
                "bulan": "Agustus",
                "budget": <?= $dataChart['agustus_nominal']; ?>,
                "realisasi": <?= $dataChart['agustus_realisasi']; ?>,
                <?php if ($dataChart['agustus_realisasi'] == 0) { ?> "lineDash": "5,5",
                    "columnDash": "5,5",
                    "fillOpacity": 0.2,
                    "additional": "(Relisasi 0)",
                <?php } ?>
            }, {
                "bulan": "September",
                "budget": <?= $dataChart['september_nominal']; ?>,
                "realisasi": <?= $dataChart['september_realisasi']; ?>,
                <?php if ($dataChart['september_realisasi'] == 0) { ?> "lineDash": "5,5",
                    "columnDash": "5,5",
                    "fillOpacity": 0.2,
                    "additional": "(Relisasi 0)",
                <?php } ?>
            }, {
                "bulan": "Oktober",
                "budget": <?= $dataChart['oktober_nominal']; ?>,
                "realisasi": <?= $dataChart['oktober_realisasi']; ?>,
                <?php if ($dataChart['oktober_realisasi'] == 0) { ?> "lineDash": "5,5",
                    "columnDash": "5,5",
                    "fillOpacity": 0.2,
                    "additional": "(Relisasi 0)",
                <?php } ?>
            }, {
                "bulan": "November",
                "budget": <?= $dataChart['november_nominal']; ?>,
                "realisasi": <?= $dataChart['november_realisasi']; ?>,
                <?php if ($dataChart['november_realisasi'] == 0) { ?> "lineDash": "5,5",
                    "columnDash": "5,5",
                    "fillOpacity": 0.2,
                    "additional": "(Relisasi 0)",
                <?php } ?>
            }, {
                "bulan": "Desember",
                "budget": <?= $dataChart['desember_nominal']; ?>,
                "realisasi": <?= $dataChart['desember_realisasi']; ?>,
                <?php if ($dataChart['desember_realisasi'] == 0) { ?> "lineDash": "5,5",
                    "columnDash": "5,5",
                    "fillOpacity": 0.2,
                    "additional": "(Relisasi 0)",
                <?php } ?>
            },
            // {
            //     "bulan": "Juni",
            //     "budget": 34.1,
            //     "realisasi": 32.9,
            //     "strokeWidth": 1,
            //     "columnDash": "5,5",
            //     "fillOpacity": 0.2,
            //     "additional": "(projection)"
            // }
        ];

        /* Create axes */
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "bulan";
        categoryAxis.renderer.minGridDistance = 30;

        /* Create value axis */
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

        /* Create series */
        var columnSeries = chart.series.push(new am4charts.ColumnSeries());
        columnSeries.name = "Budget";
        columnSeries.dataFields.valueY = "budget";
        columnSeries.dataFields.categoryX = "bulan";

        columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
        columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
        columnSeries.columns.template.propertyFields.stroke = "stroke";
        columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
        columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
        columnSeries.tooltip.label.textAlign = "middle";

        var lineSeries = chart.series.push(new am4charts.LineSeries());
        lineSeries.name = "Realisasi";
        lineSeries.dataFields.valueY = "realisasi";
        lineSeries.dataFields.categoryX = "bulan";

        lineSeries.stroke = am4core.color("#fdd400");
        lineSeries.strokeWidth = 3;
        lineSeries.propertyFields.strokeDasharray = "lineDash";
        lineSeries.tooltip.label.textAlign = "middle";

        var bullet = lineSeries.bullets.push(new am4charts.Bullet());
        bullet.fill = am4core.color("#fdd400"); // tooltips grab fill from parent by default
        bullet.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
        var circle = bullet.createChild(am4core.Circle);
        circle.radius = 4;
        circle.fill = am4core.color("#fff");
        circle.strokeWidth = 3;

        chart.data = data;

    }); // end am4core.ready()
</script>