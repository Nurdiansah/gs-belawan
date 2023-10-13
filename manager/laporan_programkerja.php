<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$tahunSekarang = date('Y');

// ngambil id divisi dari user terkait
$user = $_SESSION['username_blw'];
$queryUser = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$user'");
$dataUser = mysqli_fetch_assoc($queryUser);

$divisi = $dataUser['id_divisi'];
if (isset($_POST['cari'])) {
    $tahun = $_POST['tahun'];
} else {
    $tahun = $tahunSekarang;
}

// query opex
$queryChart = mysqli_query($koneksi, "SELECT DISTINCT id_programkerja AS id_pk, nm_programkerja, kd_programkerja, nm_user, SUM(januari_nominal) + SUM(februari_nominal) + SUM(maret_nominal) + SUM(april_nominal) + SUM(mei_nominal) + SUM(juni_nominal) + SUM(juli_nominal) + SUM(agustus_nominal) + SUM(september_nominal) + SUM(oktober_nominal) + SUM(november_nominal) + SUM(desember_nominal) AS total_budget,
                                            SUM(januari_realisasi) + SUM(februari_realisasi) + SUM(maret_realisasi) + SUM(april_realisasi) + SUM(mei_realisasi) + SUM(juni_realisasi) + SUM(juli_realisasi) + SUM(agustus_realisasi) + SUM(september_realisasi) + SUM(oktober_realisasi) + SUM(november_realisasi) + SUM(desember_realisasi) AS total_realisasi,
                                            IFNULL((SELECT SUM(nominal)
                                                    FROM realisasi_sementara rs
                                                    JOIN anggaran ag
                                                        ON ag.id_anggaran = rs.id_anggaran
                                                    WHERE programkerja_id = id_pk
                                                    AND is_deleted = '0'
                                                    GROUP BY programkerja_id), '0') AS pra_nota
                                        FROM anggaran a
                                        JOIN program_kerja p
                                            ON programkerja_id = id_programkerja
                                        WHERE a.tahun = '$tahun'
                                        AND p.tahun = '$tahun'
                                        AND id_divisi = '$divisi'
                                        AND id_programkerja <> 0
                                        AND jenis_anggaran = 'BIAYA'
                                        AND tipe_anggaran = 'OPEX'
                                        GROUP BY id_programkerja, nm_programkerja, nm_user
                                        ORDER BY nm_programkerja ASC");

// query capex
$queryChartCapex = mysqli_query($koneksi, "SELECT DISTINCT id_programkerja AS id_pk, nm_programkerja, kd_programkerja, nm_user, SUM(januari_nominal) + SUM(februari_nominal) + SUM(maret_nominal) + SUM(april_nominal) + SUM(mei_nominal) + SUM(juni_nominal) + SUM(juli_nominal) + SUM(agustus_nominal) + SUM(september_nominal) + SUM(oktober_nominal) + SUM(november_nominal) + SUM(desember_nominal) AS total_budget,
                                            SUM(januari_realisasi) + SUM(februari_realisasi) + SUM(maret_realisasi) + SUM(april_realisasi) + SUM(mei_realisasi) + SUM(juni_realisasi) + SUM(juli_realisasi) + SUM(agustus_realisasi) + SUM(september_realisasi) + SUM(oktober_realisasi) + SUM(november_realisasi) + SUM(desember_realisasi) AS total_realisasi,
                                            IFNULL((SELECT SUM(nominal)
                                                    FROM realisasi_sementara rs
                                                    JOIN anggaran ag
                                                        ON ag.id_anggaran = rs.id_anggaran
                                                    WHERE programkerja_id = id_pk
                                                    AND is_deleted = '0'
                                                    GROUP BY programkerja_id), '0') AS pra_nota
                                            FROM anggaran a
                                            JOIN program_kerja p
                                                ON programkerja_id = id_programkerja
                                            WHERE a.tahun = '$tahun'
                                            AND p.tahun = '$tahun'
                                            AND id_divisi = '$divisi'
                                            AND id_programkerja <> 0
                                            AND jenis_anggaran = 'BIAYA'
                                            AND tipe_anggaran = 'CAPEX'
                                            GROUP BY id_programkerja, nm_programkerja, nm_user
                                            ORDER BY nm_programkerja ASC");

$queryChart2 = mysqli_query($koneksi, "SELECT DISTINCT id_programkerja AS id_pk, nm_programkerja, kd_programkerja, nm_user, SUM(januari_nominal) + SUM(februari_nominal) + SUM(maret_nominal) + SUM(april_nominal) + SUM(mei_nominal) + SUM(juni_nominal) + SUM(juli_nominal) + SUM(agustus_nominal) + SUM(september_nominal) + SUM(oktober_nominal) + SUM(november_nominal) + SUM(desember_nominal) AS total_budget,
                                            SUM(januari_realisasi) + SUM(februari_realisasi) + SUM(maret_realisasi) + SUM(april_realisasi) + SUM(mei_realisasi) + SUM(juni_realisasi) + SUM(juli_realisasi) + SUM(agustus_realisasi) + SUM(september_realisasi) + SUM(oktober_realisasi) + SUM(november_realisasi) + SUM(desember_realisasi) AS total_realisasi,
                                            IFNULL((SELECT SUM(nominal)
                                                    FROM realisasi_sementara rs
                                                    JOIN anggaran ag
                                                        ON ag.id_anggaran = rs.id_anggaran
                                                    WHERE programkerja_id = id_pk
                                                    AND is_deleted = '0'
                                                    GROUP BY programkerja_id), '0') AS pra_nota
                                        FROM anggaran a
                                        JOIN program_kerja p
                                            ON programkerja_id = id_programkerja
                                        WHERE a.tahun = '$tahun'
                                        AND p.tahun = '$tahun'
                                        AND id_divisi = '$divisi'
                                        AND id_programkerja <> 0
                                        AND jenis_anggaran = 'BIAYA'
                                        GROUP BY id_programkerja, nm_programkerja, nm_user
                                        ORDER BY nm_programkerja ASC");

$queryDivisi = mysqli_query($koneksi, "SELECT * FROM divisi WHERE id_divisi = '$divisi'");
$dataDivisi = mysqli_fetch_assoc($queryDivisi);

$no = 1;
?>

<section class="content">
    <div class="row">
        <div class="col-sm-6">
            <a href="index.php?p=laporan_anggaran" class="btn btn-success btn-lg"><i class="fa fa-bar-chart-o"></i> Anggaran</a>
            <a href="#" class="btn btn-default btn-lg"><i class="fa fa-signal"></i> Program Kerja</a>
        </div>
        <br>
    </div>
    <div class="row">
        <h3 class="text-center">Grafik Laporan Program Kerja</h3>
        <h4 class="text-center">(Divisi <?= $dataDivisi['nm_divisi'] . " " . $tahun ?>)</h4>
        <form action="" method="POST">
            <div class="form-group">
                <div class="col-sm-offset- col-sm-2">
                    <select name="tahun" class="form-control" required>
                        <?php
                        if (isset($_POST['cari'])) {
                            foreach (range(2021, $tahunSekarang) as $tahunLoop) { ?>
                                <option value="<?= $tahunLoop; ?>" <?= $tahunLoop == $_POST['tahun'] ? "selected" : ""; ?>><?= $tahunLoop; ?></option>
                            <?php }
                        } else {
                            foreach (range(2021, $tahunSekarang) as $tahunLoop) { ?>
                                <option value="<?= $tahunLoop; ?>" <?= $tahunLoop == $tahunSekarang ? "selected=selected" : ""; ?>><?= $tahunLoop; ?></option>
                        <?php }
                        } ?>
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
                    <h3 class="text-center">Table Laporan Program Kerja</h3>
                    <h4 class="text-center">(Divisi <?= $dataDivisi['nm_divisi'] . " " . $tahun ?>)</h4>
                    <!-- <?php if (isset($_POST['cari'])) { ?>
                        <a href="cetak_excel_summary.php?tahun=<?= enkripRambo($_POST['tahun']); ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Excel</a> -->
                    <!-- <a href="cetak_excel_detail.php?tahun=<?= enkripRambo($_POST['tahun']); ?>" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Detail</a> -->
                    <!-- <?php } else { ?>
                        <a href="cetak_excel_summary.php" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Excel</a> -->
                    <!-- <a href="cetak_excel_detail.php" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Detail</a> -->
                    <!-- <?php } ?> -->
                </div>
                <div class="box-body">
                    <table class="table table-striped table text-center">
                        <thead class="bg-primary">
                            <tr>
                                <th>No</th>
                                <th>Program Kerja</th>
                                <th>Nama User</th>
                                <th>Budget</th>
                                <th>Pra Nota</th>
                                <th>Realisasi</th>
                                <th>Total Realisasi</th>
                                <th>Surplus (Defisit)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th colspan="8" class="text-center">
                                    <h4><b>OPEX</b></h4>
                                </th>
                            </tr>
                            <?php
                            $grand_nominal = 0;
                            $grand_pranota = 0;
                            $grand_realisasi = 0;
                            $grand_jumlah_realisasi = 0;
                            $grand_total = 0;
                            // data OPEX
                            while ($dataChart = mysqli_fetch_assoc($queryChart)) { ?>
                                <tr <?= warnaSurplus($dataChart['total_budget'], $dataChart['total_realisasi'], $dataChart['pra_nota']); ?>>
                                    <td><?= $no; ?></td>
                                    <td style="text-align: left;"><a href="index.php?p=dtl_laporanpk&sp=pra_nota&id_pk=<?= enkripRambo($dataChart['id_pk']); ?>" target="_blank" title="Klik Detail"><u><?= $dataChart['nm_programkerja'] . " <b>[" . $dataChart['kd_programkerja']; ?>]</b></u></a></td>
                                    <td><?= $dataChart['nm_user']; ?></td>
                                    <td style="text-align: right;"><?= formatRupiah($dataChart['total_budget']); ?></td>
                                    <td style="text-align: right;"><?= formatRupiah($dataChart['pra_nota']); ?></td>
                                    <td style="text-align: right;"><?= formatRupiah($dataChart['total_realisasi']); ?></td>
                                    <td style="text-align: right;"><?= formatRupiah($dataChart['total_realisasi'] + $dataChart['pra_nota']); ?></td>
                                    <td style="text-align: right;"><?= kurungSurplus($dataChart['total_budget'], $dataChart['total_realisasi'] + $dataChart['pra_nota']); ?></td>
                                </tr>
                            <?php
                                $no++;
                                $grand_nominal += $dataChart['total_budget'];
                                $grand_pranota += $dataChart['pra_nota'];
                                $grand_realisasi += $dataChart['total_realisasi'];
                                $grand_jumlah_realisasi += $dataChart['total_realisasi'] + $dataChart['pra_nota'];
                                $grand_total += $dataChart['total_budget'] - $dataChart['total_realisasi'];
                            }
                            ?>
                            <tr>
                                <td>
                                    <h4>#</h4>
                                </td>
                                <td colspan="2" style="text-align: center;">
                                    <h4>Sub Total Opex</h4>
                                </td>
                                <td>
                                    <h4 class="text-right"><?= formatRupiah($grand_nominal); ?></h4>
                                </td>
                                <td>
                                    <h4 class="text-right"><?= formatRupiah($grand_pranota); ?></h4>
                                </td>
                                <td>
                                    <h4 class="text-right"><?= formatRupiah($grand_realisasi); ?></h4>
                                </td>
                                <td>
                                    <h4 class="text-right"><?= formatRupiah($grand_jumlah_realisasi); ?></h4>
                                </td>
                                <td>
                                    <h4 class="text-right"><?= formatRupiah($grand_total); ?></h4>
                                </td>
                            </tr>

                            <tr>
                                <th colspan="8" class="text-center">
                                    <h4><b>CAPEX</b></h4>
                                </th>
                            </tr>
                            <?php
                            // data CAPEX
                            while ($dataChartCapex = mysqli_fetch_assoc($queryChartCapex)) { ?>
                                <tr <?= warnaSurplus($dataChartCapex['total_budget'], $dataChartCapex['total_realisasi'], $dataChartCapex['pra_nota']); ?>>
                                    <td><?= $no; ?></td>
                                    <td style="text-align: left;"><a href="index.php?p=dtl_laporanpk&sp=pra_nota&id_pk=<?= enkripRambo($dataChartCapex['id_pk']); ?>" target="_blank" title="Klik Detail"><u><?= $dataChartCapex['nm_programkerja'] . " <b>[" . $dataChartCapex['kd_programkerja']; ?>]</b></u></a></td>
                                    <td><?= $dataChartCapex['nm_user']; ?></td>
                                    <td style="text-align: right;"><?= formatRupiah($dataChartCapex['total_budget']); ?></td>
                                    <td style="text-align: right;"><?= formatRupiah($dataChartCapex['pra_nota']); ?></td>
                                    <td style="text-align: right;"><?= formatRupiah($dataChartCapex['total_realisasi']); ?></td>
                                    <td style="text-align: right;"><?= formatRupiah($dataChartCapex['total_realisasi'] + $dataChartCapex['pra_nota']); ?></td>
                                    <td style="text-align: right;"><?= kurungSurplus($dataChartCapex['total_budget'], $dataChartCapex['total_realisasi'] + $dataChartCapex['pra_nota']); ?></td>
                                </tr>
                            <?php
                                $no++;
                                $grand_nominal_capex += $dataChartCapex['total_budget'];
                                $grand_pranota_capex += $dataChartCapex['pra_nota'];
                                $grand_realisasi_capex += $dataChartCapex['total_realisasi'];
                                $grand_jumlah_realisasi_capex += $dataChartCapex['total_realisasi'] + $dataChartCapex['pra_nota'];
                                $grand_total_capex += $dataChartCapex['total_budget'] - $dataChartCapex['total_realisasi'];
                            }

                            $total_nominal = $grand_nominal + $grand_nominal_capex;
                            $total_pranota = $grand_pranota + $grand_pranota_capex;
                            $total_realisasi = $grand_realisasi + $grand_realisasi_capex;
                            $total_jumlah_realisasi = $total_realisasi + $total_pranota;
                            $total_total = $grand_total + $grand_total_capex;
                            ?>
                            <tr>
                                <td>
                                    <h3>#</h3>
                                </td>
                                <td colspan="2" style="text-align: center;">
                                    <h4>Sub Total Capex</h4>
                                </td>
                                <td>
                                    <h4 class="text-right"><?= formatRupiah($grand_nominal_capex); ?></h4>
                                </td>
                                <td>
                                    <h4 class="text-right"><?= formatRupiah($grand_pranota_capex); ?></h4>
                                </td>
                                <td>
                                    <h4 class="text-right"><?= formatRupiah($grand_realisasi_capex); ?></h4>
                                </td>
                                <td>
                                    <h4 class="text-right"><?= formatRupiah($grand_jumlah_realisasi_capex); ?></h4>
                                </td>
                                <td>
                                    <h4 class="text-right"><?= formatRupiah($grand_total_capex); ?></h4>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <h3>#</h3>
                                </td>
                                <td colspan="2" style="text-align: center;">
                                    <h3>Grand Total</h3>
                                </td>
                                <td>
                                    <h3 class="text-right"><?= formatRupiah($total_nominal); ?></h3>
                                </td>
                                <td>
                                    <h3 class="text-right"><?= formatRupiah($total_pranota); ?></h3>
                                </td>
                                <td>
                                    <h3 class="text-right"><?= formatRupiah($total_realisasi); ?></h3>
                                </td>
                                <td>
                                    <h3 class="text-right"><?= formatRupiah($total_jumlah_realisasi); ?></h3>
                                </td>
                                <td>
                                    <h3 class="text-right"><?= formatRupiah($total_total); ?></h3>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</section>


<!-- Styles -->
<!-- <style>
    #chartdiv {
        width: 100%;
        height: 700px;
    }
</style> -->

<!-- Resources -->
<!-- <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script> -->

<!-- Chart code -->
<script>
    // am5.ready(function() {

    //     // Create root element
    //     // https://www.amcharts.com/docs/v5/getting-started/#Root_element
    //     var root = am5.Root.new("chartdiv");


    //     // Set themes
    //     // https://www.amcharts.com/docs/v5/concepts/themes/
    //     root.setThemes([
    //         am5themes_Animated.new(root)
    //     ]);


    //     // Create chart
    //     // https://www.amcharts.com/docs/v5/charts/xy-chart/
    //     var chart = root.container.children.push(am5xy.XYChart.new(root, {
    //         panX: false,
    //         panY: false,
    //         wheelX: "panX",
    //         wheelY: "zoomX",
    //         layout: root.verticalLayout
    //     }));


    //     // Add legend
    //     // https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
    //     var legend = chart.children.push(am5.Legend.new(root, {
    //         centerX: am5.p50,
    //         x: am5.p50
    //     }))


    //     // Data
    //     var data = [{
    // year: "2020",
    // income: 29.5,
    // expenses: 25.1
    // },
    //     ];


    //     // Create axes
    //     // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
    //     var yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, {
    //         categoryField: "programkerja",
    //         renderer: am5xy.AxisRendererY.new(root, {
    //             inversed: true,
    //             cellStartLocation: 0.1,
    //             cellEndLocation: 0.9
    //         })
    //     }));

    //     yAxis.data.setAll(data);

    //     var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
    //         renderer: am5xy.AxisRendererX.new(root, {}),
    //         min: 0
    //     }));


    //     // Add series
    //     // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
    //     function createSeries(field, name) {
    //         var series = chart.series.push(am5xy.ColumnSeries.new(root, {
    //             name: name,
    //             xAxis: xAxis,
    //             yAxis: yAxis,
    //             valueXField: field,
    //             categoryYField: "programkerja",
    //             sequencedInterpolation: true,
    //             tooltip: am5.Tooltip.new(root, {
    //                 pointerOrientation: "horizontal",
    //                 labelText: "[bold]{name}[/]\n{categoryY}: {valueX}"
    //             })
    //         }));

    //         series.columns.template.setAll({
    //             height: am5.p100
    //         });


    //         series.bullets.push(function() {
    //             return am5.Bullet.new(root, {
    //                 locationX: 1,
    //                 locationY: 0.5,
    //                 sprite: am5.Label.new(root, {
    //                     centerY: am5.p50,
    //                     text: "{valueX}",
    //                     populateText: true
    //                 })
    //             });
    //         });

    //         series.bullets.push(function() {
    //             return am5.Bullet.new(root, {
    //                 locationX: 1,
    //                 locationY: 0.5,
    //                 sprite: am5.Label.new(root, {
    //                     centerX: am5.p100,
    //                     centerY: am5.p50,
    //                     text: "{name}",
    //                     fill: am5.color(0xffffff),
    //                     populateText: true
    //                 })
    //             });
    //         });

    //         series.data.setAll(data);
    //         series.appear();

    //         return series;
    //     }

    //     createSeries("Nominal", "Nominal");
    //     createSeries("Realisasi", "Realisasi");


    //     // Add legend
    //     // https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
    //     var legend = chart.children.push(am5.Legend.new(root, {
    //         centerX: am5.p50,
    //         x: am5.p50
    //     }));

    //     legend.data.setAll(chart.series.values);


    //     // Add cursor
    //     // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
    //     var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
    //         behavior: "zoomY"
    //     }));
    //     cursor.lineY.set("forceHidden", true);
    //     cursor.lineX.set("forceHidden", true);


    //     // Make stuff animate on load
    //     // https://www.amcharts.com/docs/v5/concepts/animations/
    //     chart.appear(1000, 100);

    // }); // end am5.ready()
</script>




<!-- Styles -->
<style>
    #chartdiv {
        width: 100%;
        height: 500px;
    }
</style>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<!-- Chart code -->
<script>
    am5.ready(function() {

        // Create root element
        // https://www.amcharts.com/docs/v5/getting-started/#Root_element
        var root = am5.Root.new("chartdiv");

        // Set themes
        // https://www.amcharts.com/docs/v5/concepts/themes/
        root.setThemes([
            am5themes_Animated.new(root)
        ]);

        // Create chart
        // https://www.amcharts.com/docs/v5/charts/xy-chart/
        var chart = root.container.children.push(
            am5xy.XYChart.new(root, {
                panX: false,
                panY: false,
                wheelX: "panX",
                wheelY: "zoomX",
                layout: root.horizontalLayout,
                arrangeTooltips: false
            })
        );

        // Use only absolute numbers
        root.numberFormatter.set("numberFormat", "#.#s'%");

        // Add legend
        // https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
        var legend = chart.children.push(
            am5.Legend.new(root, {
                centerX: am5.p50,
                x: am5.p50
            })
        );

        // Data
        // var data = [{
        //     category: "Search engines",
        //     negative1: -0.1,
        //     negative2: -0.9,
        //     positive2: 94
        // }, {
        //     category: "Online encyclopedias",
        //     negative1: -2,
        //     negative2: -4,
        //     positive2: 75
        // }];

        var data = [
            <?php while ($dataPK = mysqli_fetch_assoc($queryChart2)) { ?> {
                    category: "<?= $dataPK['nm_programkerja']; ?>",
                    negative1: -"<?= $dataPK['total_realisasi']; ?>",
                    negative2: -"<?= $dataPK['pra_nota']; ?>",
                    positive2: <?= ($dataPK['total_budget'] - $dataPK['total_realisasi']) + $dataPK['pra_nota'] <= 0 ? 0 : ($dataPK['total_budget'] - $dataPK['total_realisasi']) + $dataPK['pra_nota']; ?>
                },
            <?php } ?>
        ];

        // Create axes
        // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
        var yAxis = chart.yAxes.push(
            am5xy.CategoryAxis.new(root, {
                categoryField: "category",
                renderer: am5xy.AxisRendererY.new(root, {
                    inversed: true,
                    cellStartLocation: 0.1,
                    cellEndLocation: 0.9
                })
            })
        );

        yAxis.data.setAll(data);

        var xAxis = chart.xAxes.push(
            am5xy.ValueAxis.new(root, {
                calculateTotals: true,
                min: -100,
                max: 100,
                renderer: am5xy.AxisRendererX.new(root, {
                    minGridDistance: 50
                })
            })
        );

        var xRenderer = yAxis.get("renderer");
        xRenderer.axisFills.template.setAll({
            fill: am5.color(0x000000),
            fillOpacity: 0.05,
            visible: true
        });

        // Add series
        // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
        function createSeries(field, name, color) {
            var series = chart.series.push(
                am5xy.ColumnSeries.new(root, {
                    xAxis: xAxis,
                    yAxis: yAxis,
                    name: name,
                    valueXField: field,
                    valueXShow: "valueXTotalPercent",
                    categoryYField: "category",
                    sequencedInterpolation: true,
                    stacked: true,
                    fill: color,
                    stroke: color,
                    calculateAggregates: true
                })
            );

            series.columns.template.setAll({
                height: am5.p100
            });

            series.bullets.push(function(root, series) {
                return am5.Bullet.new(root, {
                    locationX: 0.5,
                    locationY: 0.5,
                    sprite: am5.Label.new(root, {
                        fill: am5.color(0xffffff),
                        centerX: am5.p50,
                        centerY: am5.p50,
                        text: "{valueX}",
                        populateText: true,
                        oversizedBehavior: "hide"
                    })
                });
            });

            series.data.setAll(data);
            series.appear();

            return series;
        }

        var positiveColor = root.interfaceColors.get("positive");
        var negativeColor = root.interfaceColors.get("negative");

        createSeries("negative1", "Realisasi", negativeColor);
        createSeries("negative2", "Pra Nota", am5.Color.lighten(negativeColor, 0.5));
        createSeries("positive2", "Sisa Budget", positiveColor);

        // Add legend
        // https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
        var legend = chart.children.push(
            am5.Legend.new(root, {
                centerY: am5.p50,
                y: am5.p50,
                layout: root.verticalLayout,
                marginLeft: 50
            })
        );

        legend.data.setAll(chart.series.values);

        // Make stuff animate on load
        // https://www.amcharts.com/docs/v5/concepts/animations/
        chart.appear(1000, 100);

    }); // end am5.ready()



    // am5.ready(function() {

    //     // Create root element
    //     // https://www.amcharts.com/docs/v5/getting-started/#Root_element
    //     var root = am5.Root.new("chartdiv");


    //     // Set themes
    //     // https://www.amcharts.com/docs/v5/concepts/themes/
    //     root.setThemes([
    //         am5themes_Animated.new(root)
    //     ]);


    //     // Create chart
    //     // https://www.amcharts.com/docs/v5/charts/xy-chart/
    //     var chart = root.container.children.push(am5xy.XYChart.new(root, {
    //         panX: false,
    //         panY: false,
    //         wheelX: "none",
    //         wheelY: "none",
    //         layout: root.horizontalLayout
    //     }));


    //     // Add legend
    //     // https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
    //     var legendData = [];
    //     var legend = chart.children.push(
    //         am5.Legend.new(root, {
    //             nameField: "name",
    //             fillField: "color",
    //             strokeField: "color",
    //             //centerY: am5.p50,
    //             marginLeft: 20,
    //             y: 20,
    //             layout: root.verticalLayout,
    //             clickTarget: "none"
    //         })
    //     );

    //     var data = [
    //         <?php while ($dataPK = mysqli_fetch_assoc($queryChart2)) { ?> {
    //                 programkerja: "<?= $dataDivisi['nm_divisi']; ?>",
    //                 anggaran: "<?= $dataPK['nm_programkerja']; ?>",
    //                 budget: <?= $dataPK['total_budget']; ?>
    //             },
    //         <?php } ?>
    //         // {
    //         //     programkerja: "",
    //         //     anggaran: "",
    //         //     budget: 0
    //         // }
    //     ];


    //     // Create axes
    //     // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
    //     var yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, {
    //         categoryField: "anggaran",
    //         renderer: am5xy.AxisRendererY.new(root, {
    //             minGridDistance: 10
    //         }),
    //         tooltip: am5.Tooltip.new(root, {})
    //     }));

    //     yAxis.get("renderer").labels.template.setAll({
    //         fontSize: 12,
    //         location: 0.5
    //     })

    //     yAxis.data.setAll(data);

    //     var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
    //         renderer: am5xy.AxisRendererX.new(root, {}),
    //         tooltip: am5.Tooltip.new(root, {})
    //     }));


    //     // Add series
    //     // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
    //     var series = chart.series.push(am5xy.ColumnSeries.new(root, {
    //         xAxis: xAxis,
    //         yAxis: yAxis,
    //         valueXField: "budget",
    //         categoryYField: "anggaran",
    //         tooltip: am5.Tooltip.new(root, {
    //             pointerOrientation: "horizontal"
    //         })
    //     }));

    //     series.columns.template.setAll({
    //         tooltipText: "{categoryY}: [bold]{valueX}[/]",
    //         width: am5.percent(90),
    //         strokeOpacity: 0
    //     });

    //     series.columns.template.adapters.add("fill", function(fill, target) {
    //         if (target.dataItem) {
    //             switch (target.dataItem.dataContext.programkerja) {
    //                 case "<?= $dataDivisi['nm_divisi']; ?>":
    //                     return chart.get("colors").getIndex(0);
    //                     break;
    //                     // case "East":
    //                     //     return chart.get("colors").getIndex(1);
    //                     //     break;
    //                     // case "South":
    //                     //     return chart.get("colors").getIndex(2);
    //                     //     break;
    //                     // case "West":
    //                     //     return chart.get("colors").getIndex(3);
    //                     //     break;
    //             }
    //         }
    //         return fill;
    //     })

    //     series.data.setAll(data);

    //     function createRange(label, category, color) {
    //         var rangeDataItem = yAxis.makeDataItem({
    //             category: category
    //         });

    //         var range = yAxis.createAxisRange(rangeDataItem);

    //         rangeDataItem.get("label").setAll({
    //             fill: color,
    //             text: label,
    //             location: 1,
    //             fontWeight: "bold",
    //             dx: -130
    //         });

    //         rangeDataItem.get("grid").setAll({
    //             stroke: color,
    //             strokeOpacity: 1,
    //             location: 1
    //         });

    //         rangeDataItem.get("tick").setAll({
    //             stroke: color,
    //             strokeOpacity: 1,
    //             location: 1,
    //             visible: true,
    //             length: 130
    //         });

    //         legendData.push({
    //             name: label,
    //             color: color
    //         });

    //     }

    //     createRange("<?= $dataDivisi['nm_divisi']; ?>", "<?= $tahun; ?>", chart.get("colors").getIndex(0));
    //     // createRange("East", "New York", chart.get("colors").getIndex(1));
    //     // createRange("South", "Florida", chart.get("colors").getIndex(2));
    //     // createRange("West", "California", chart.get("colors").getIndex(3));

    //     legend.data.setAll(legendData);

    //     // Add cursor
    //     // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
    //     var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
    //         xAxis: xAxis,
    //         yAxis: yAxis
    //     }));


    //     // Make stuff animate on load
    //     // https://www.amcharts.com/docs/v5/concepts/animations/
    //     series.appear();
    //     chart.appear(1000, 100);

    // }); // end am5.ready()
</script>