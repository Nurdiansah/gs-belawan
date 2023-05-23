<?php

if ($_POST['cetak']) {
    header("Location: cetak_lr03.php?tahun=" . enkripRambo($_POST['tahun']) . "&project=" . enkripRambo($_POST['project']) . "&divisi=" . enkripRambo($_POST['divisi']) . "");
}

if ($_POST['tampil']) {
    $tahun = $_POST['tahun'];
    $project = $_POST['project'];
    $divisi = $_POST['divisi'];
} else {
    $tahun = date("Y");
    $project = "1";
    $divisi = "1";
}

$queryOpex = mysqli_query($koneksi, "SELECT DISTINCT nm_header, nm_subheader, no_coa, nm_coa, no_coa AS nocoa,
                                        SUM(januari_nominal) + SUM(februari_nominal) + SUM(maret_nominal) + SUM(april_nominal) + SUM(mei_nominal) + SUM(juni_nominal) + SUM(juli_nominal) + SUM(agustus_nominal) + SUM(september_nominal) + SUM(oktober_nominal) + SUM(november_nominal) + SUM(desember_nominal) AS jml_nominal,
                                        SUM(januari_realisasi) + SUM(februari_realisasi) + SUM(maret_realisasi) + SUM(april_realisasi) + SUM(mei_realisasi) + SUM(juni_realisasi) + SUM(juli_realisasi) + SUM(agustus_realisasi) + SUM(september_realisasi) + SUM(oktober_realisasi) + SUM(november_realisasi) + SUM(desember_realisasi) AS jml_realisasi,
                                        IFNULL((SELECT SUM(nominal)
                                                    FROM realisasi_sementara rs
                                                    JOIN anggaran ag
                                                        ON ag.id_anggaran = rs.id_anggaran
                                                    WHERE no_coa = nocoa
                                                    AND pengajuan = 'BUM'
                                                    AND is_deleted = '0'
                                                    GROUP BY no_coa), '0') AS nota,
                                        IFNULL((SELECT SUM(nominal)
                                                    FROM realisasi_sementara rs
                                                    JOIN anggaran ag
                                                        ON ag.id_anggaran = rs.id_anggaran
                                                    WHERE no_coa = nocoa
                                                    AND pengajuan = 'PO'
                                                    AND is_deleted = '0'
                                                    GROUP BY no_coa), '0') AS pra_nota
                                        FROM program_kerja pk
                                        JOIN anggaran agg
                                            ON programkerja_id = id_programkerja
                                        JOIN cost_center cc
                                            ON costcenter_id = id_costcenter
                                        JOIN pt_copy pt
                                            ON pt_id = id_pt
                                        JOIN divisi_copy dvs
                                            ON divisi_id = dvs.id_divisi
                                        JOIN parent_divisi_copy pd
                                            ON parent_id = id_parent
                                        JOIN sub_header sh
                                            ON subheader_id = id_subheader
                                        JOIN header h
                                            ON sh.id_header = h.id_header
                                        WHERE agg.tahun = '$tahun'
                                        AND id_pt = '$project'
                                        AND id_parent = '$divisi'
                                        AND tipe_anggaran = 'OPEX'
                                        GROUP BY no_coa, nm_coa, nm_header, nm_subheader
                                        ORDER BY nm_header, nm_subheader, nm_coa ASC");

$queryCapex = mysqli_query($koneksi, "SELECT DISTINCT nm_header, nm_subheader, no_coa, nm_coa, no_coa AS nocoa,
                                        SUM(januari_nominal) + SUM(februari_nominal) + SUM(maret_nominal) + SUM(april_nominal) + SUM(mei_nominal) + SUM(juni_nominal) + SUM(juli_nominal) + SUM(agustus_nominal) + SUM(september_nominal) + SUM(oktober_nominal) + SUM(november_nominal) + SUM(desember_nominal) AS jml_nominal,
                                        SUM(januari_realisasi) + SUM(februari_realisasi) + SUM(maret_realisasi) + SUM(april_realisasi) + SUM(mei_realisasi) + SUM(juni_realisasi) + SUM(juli_realisasi) + SUM(agustus_realisasi) + SUM(september_realisasi) + SUM(oktober_realisasi) + SUM(november_realisasi) + SUM(desember_realisasi) AS jml_realisasi,
                                        IFNULL((SELECT SUM(nominal)
                                                    FROM realisasi_sementara rs
                                                    JOIN anggaran ag
                                                        ON ag.id_anggaran = rs.id_anggaran
                                                    WHERE no_coa = nocoa
                                                    AND pengajuan = 'BUM'
                                                    AND is_deleted = '0'
                                                    GROUP BY no_coa), '0') AS nota,
                                        IFNULL((SELECT SUM(nominal)
                                                    FROM realisasi_sementara rs
                                                    JOIN anggaran ag
                                                        ON ag.id_anggaran = rs.id_anggaran
                                                    WHERE no_coa = nocoa
                                                    AND pengajuan = 'PO'
                                                    AND is_deleted = '0'
                                                    GROUP BY no_coa), '0') AS pra_nota
                                        FROM program_kerja pk
                                        JOIN anggaran agg
                                            ON programkerja_id = id_programkerja
                                        JOIN cost_center cc
                                            ON costcenter_id = id_costcenter
                                        JOIN pt_copy pt
                                            ON pt_id = id_pt
                                        JOIN divisi_copy dvs
                                            ON divisi_id = dvs.id_divisi
                                        JOIN parent_divisi_copy pd
                                            ON parent_id = id_parent
                                        JOIN sub_header sh
                                            ON subheader_id = id_subheader
                                        JOIN header h
                                            ON sh.id_header = h.id_header
                                        WHERE agg.tahun = '$tahun'
                                        AND id_pt = '$project'
                                        AND id_parent = '$divisi'
                                        AND tipe_anggaran = 'CAPEX'
                                        GROUP BY no_coa, nm_coa, nm_header, nm_subheader
                                        ORDER BY nm_header, nm_subheader, nm_coa ASC");


$link = "url=index.php?p=transaksi_bkk&lvl=anggaran";

$queryPT = mysqli_query($koneksi, "SELECT * FROM pt WHERE id_pt <> '0' ORDER BY nm_pt ASC");

$queryDivisi = mysqli_query($koneksi, "SELECT DISTINCT id_parent, nm_parent
                                        FROM parent_divisi
                                        RIGHT JOIN divisi
                                            ON id_parent = parent_id
                                        JOIN cost_center
                                            ON id_divisi = divisi_id
                                        JOIN pt
                                            ON id_pt = pt_id
                                        WHERE id_pt = '$project'
                                        AND id_parent <> '0'
                                        ORDER BY nm_parent ASC
                            ");

?>

<div class="box-body">
    <form method="POST" enctype="multipart/form-data" action="" class="form-horizontal">
        <div class="form-group">
            <label id="tes" for="divisi" class="col-sm-offset-4 col-sm-1 control-label">Tahun</label>
            <div class="col-sm-offset- col-sm-3">
                <select name="tahun" class="form-control" required>
                    <?php foreach (range(2019, $tahunAyeuna) as $tahunLoop) { ?>
                        <option value="<?= $tahunLoop; ?>" <?= $tahunLoop == $tahun ? "selected" : ""; ?>><?= $tahunLoop; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label id="tes" for="project" class="col-sm-offset-4 col-sm-1 control-label">Project</label>
            <div class="col-sm-offset- col-sm-3">
                <select name="project" class="form-control project_id" required>
                    <?php while ($dataPT = mysqli_fetch_assoc($queryPT)) { ?>
                        <option value="<?= $dataPT['id_pt']; ?>" <?= $dataPT['id_pt'] == $project ? "selected" : ""; ?>><?= $dataPT['nm_pt']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label id="tes" for="divisi" class="col-sm-offset-4 col-sm-1 control-label">Divisi</label>
            <div class="col-sm-offset- col-sm-3">
                <select name="divisi" class="form-control divisi" id="divisi" required>
                    <?php while ($dataDivisi = mysqli_fetch_assoc($queryDivisi)) { ?>
                        <option value="<?= $dataDivisi['id_parent']; ?>" <?= $dataDivisi['id_parent'] == $divisi ? "selected" : ""; ?>><?= $dataDivisi['nm_parent']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-5">
                <input type="submit" name="tampil" class="btn btn-primary col-sm-offset-1" value="Tampilkan">
                <input type="submit" name="cetak" class="btn btn-success col-sm-offset-" value="Cetak">
            </div>
        </div>
    </form>

    <br>
    <hr>
    <h4><b><u>OPEX</u></b></h4>
    <div class="table-responsive">
        <table class="table text-center table table-striped table-hover" id="material" border="2">
            <tr style="background-color: #87CEFA;">
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <!-- <th>Divisi</th> -->
                <th>Anggaran</th>
                <th>Realisasi Kas</th>
                <th>Realisasi Nota</th>
                <th>Realisasi Pra Nota</th>
                <th>Total Realisasi</th>
                <th>Sisa Anggaran</th>
                <th>% Realisasi</th>
                <!-- <th>Link</th> -->
            </tr>

            <?php

            // variabel per sub header, dideklarasiin 0 dulu
            $sub_header = "";
            $sub_header_nominal = 0;
            $sub_header_realisasi = 0;
            $sub_header_nota = 0;
            $sub_header_pranota = 0;
            $sub_header_jumlah_realisasi = 0;
            $sub_header_sisa_anggaran = 0;
            $sub_header_realisasi_persen = 0;

            // variabel per header, dideklarasiin 0 dulu
            $header = "";
            $header_nominal = 0;
            $header_realisasi = 0;
            $header_nota = 0;
            $header_pranota = 0;
            $header_jumlah_realisasi = 0;
            $header_sisa_anggaran = 0;
            $header_realisasi_persen = 0;

            $no = 1;

            while ($dataOpex = mysqli_fetch_assoc($queryOpex)) {

                // Sub total per sub header ditengah
                if ($no > 1 && $sub_header != $dataOpex['nm_subheader']) {  ?>
                    <tr style="background-color: yellow;">
                        <th></th>
                        <th style="text-align: left;"><?= $sub_header; ?></th>
                        <!-- <th></th> -->
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nominal); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_header_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_header_pranota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_header_jumlah_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungSurplus2($sub_header_nominal, $sub_header_jumlah_realisasi); ?></b></td>
                        <th><?= $sub_header_realisasi_persen; ?>%</th>
                        <!-- <th></th> -->
                    </tr>

                <?php
                    // setelah ditampilin, dideklarasiin lagi ke 0
                    $sub_header_nominal = 0;
                    $sub_header_realisasi = 0;
                    $sub_header_nota = 0;
                    $sub_header_pranota = 0;
                    $sub_header_jumlah_realisasi = 0;
                    $sub_header_sisa_anggaran = 0;
                    $sub_header_realisasi_persen = 0;
                }
                $sub_header_nominal += $dataOpex['jml_nominal'];
                $sub_header_realisasi += $dataOpex['jml_realisasi'];
                $sub_header_nota += $dataOpex['nota'];
                $sub_header_pranota += $dataOpex['pra_nota'];
                $sub_header_jumlah_realisasi = $sub_header_realisasi + $sub_header_nota + $sub_header_pranota;
                $sub_header_sisa_anggaran = $sub_header_nominal - $sub_header_jumlah_realisasi;
                $sub_header_realisasi_persen = cekPersenNew($sub_header_jumlah_realisasi, $sub_header_nominal);
                // END sub total per sub header ditengah

                // Sub total per header ditengah
                if ($no > 1 && $header != $dataOpex['nm_header']) {  ?>
                    <tr style="background-color: red;">
                        <th></th>
                        <th style="text-align: left;"><?= $header; ?></th>
                        <!-- <th></th> -->
                        <td style="text-align: right;"><b><?= formatRupiah2($header_nominal); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($header_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($header_nota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($header_pranota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($header_jumlah_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungSurplus2($header_nominal, $header_jumlah_realisasi); ?></b></td>
                        <th><?= $header_realisasi_persen; ?>%</th>
                        <!-- <th></th> -->
                    </tr>

                <?php
                    // setelah ditampilin, dideklarasiin lagi ke 0
                    $header_nominal = 0;
                    $header_realisasi = 0;
                    $header_nota = 0;
                    $header_pranota = 0;
                    $header_jumlah_realisasi = 0;
                    $header_sisa_anggaran = 0;
                    $header_realisasi_persen = 0;
                }
                $header_nominal += $dataOpex['jml_nominal'];
                $header_realisasi += $dataOpex['jml_realisasi'];
                $header_nota += $dataOpex['nota'];
                $header_pranota += $dataOpex['pra_nota'];
                $header_jumlah_realisasi = $header_realisasi + $header_nota + $header_pranota;
                $header_sisa_anggaran = $header_nominal - $header_jumlah_realisasi;
                $header_realisasi_persen = cekPersenNew($header_jumlah_realisasi, $header_nominal);
                // END total per header ditengah

                // isi
                $jumlah_realisasi =   $dataOpex['jml_realisasi'] + $dataOpex['nota'] + $dataOpex['pra_nota'];
                $sisa_anggaran = $dataOpex['jml_nominal'] - $jumlah_realisasi;
                $realisasi_persen = cekPersenNew($jumlah_realisasi, $dataOpex['jml_nominal']);
                ?>
                <tr>
                    <td><?= $dataOpex['no_coa']; ?></td>
                    <td style="text-align: left;"><?= $dataOpex['nm_coa']; ?></td>
                    <!-- <td><?= $dataOpex['nm_divisi']; ?></td> -->
                    <td style="text-align: right;"><?= formatRupiah2($dataOpex['jml_nominal']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($dataOpex['jml_realisasi']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($dataOpex['nota']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($dataOpex['pra_nota']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($jumlah_realisasi); ?></td>
                    <td style="text-align: right;"><?= kurungSurplus2($dataOpex['jml_nominal'], $jumlah_realisasi); ?></td>
                    <td style="text-align: center;"><?= $realisasi_persen; ?>%</td>
                    <!-- <td>-</td> -->
                </tr>
                <!-- end isi -->

            <?php
                $sub_header = $dataOpex['nm_subheader'];
                $header = $dataOpex['nm_header'];
                $no++;
            }
            ?>

            <!-- sub total sub header paling akhir -->
            <tr style="background-color: yellow;">
                <th></th>
                <th style="text-align: left;"><?= $sub_header; ?></th>
                <!-- <th></th> -->
                <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nominal); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_header_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_header_pranota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_header_jumlah_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= kurungSurplus2($sub_header_nominal, $sub_header_jumlah_realisasi); ?></b></td>
                <th><?= $sub_header_realisasi_persen; ?>%</th>
                <!-- <th></th> -->
            </tr>

            <!-- total header paling akhir -->
            <tr style="background-color: red;">
                <th></th>
                <th style="text-align: left;"><?= $header; ?></th>
                <!-- <th></th> -->
                <td style="text-align: right;"><b><?= formatRupiah2($header_nominal); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($header_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($header_nota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($header_pranota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($header_jumlah_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= kurungSurplus2($header_nominal, $header_jumlah_realisasi); ?></b></td>
                <th><?= $header_realisasi_persen; ?>%</th>
                <!-- <th></th> -->
            </tr>
        </table>
    </div>


    <!-- ------------ CAPEX ------------ -->
    <br><br>
    <h4><b><u>CAPEX</u></b></h4>
    <div class="table-responsive">
        <table class="table text-center table table-striped table-hover" id="material" border="2">
            <tr style="background-color: #87CEFA;">
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <!-- <th>Divisi</th> -->
                <th>Anggaran</th>
                <th>Realisasi Kas</th>
                <th>Realisasi Nota</th>
                <th>Realisasi Pra Nota</th>
                <th>Total Realisasi</th>
                <th>Sisa Anggaran</th>
                <th>% Realisasi</th>
                <!-- <th>Link</th> -->
            </tr>

            <?php

            // variabel per sub header, dideklarasiin 0 dulu
            $sub_header = "";
            $sub_header_nominal = 0;
            $sub_header_realisasi = 0;
            $sub_header_nota = 0;
            $sub_header_pranota = 0;
            $sub_header_jumlah_realisasi = 0;
            $sub_header_sisa_anggaran = 0;
            $sub_header_realisasi_persen = 0;

            // variabel per header, dideklarasiin 0 dulu
            $header = "";
            $header_nominal = 0;
            $header_realisasi = 0;
            $header_nota = 0;
            $header_pranota = 0;
            $header_jumlah_realisasi = 0;
            $header_sisa_anggaran = 0;
            $header_realisasi_persen = 0;

            $no = 1;

            while ($dataCapex = mysqli_fetch_assoc($queryCapex)) {

                // Sub total per sub header ditengah
                if ($no > 1 && $sub_header != $dataCapex['nm_subheader']) {  ?>
                    <tr style="background-color: yellow;">
                        <th></th>
                        <th style="text-align: left;"><?= $sub_header; ?></th>
                        <!-- <th></th> -->
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nominal); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_header_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_header_pranota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_header_jumlah_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungSurplus2($sub_header_nominal, $sub_header_jumlah_realisasi); ?></b></td>
                        <th><?= $sub_header_realisasi_persen; ?>%</th>
                        <!-- <th></th> -->
                    </tr>

                <?php
                    // setelah ditampilin, dideklarasiin lagi ke 0
                    $sub_header_nominal = 0;
                    $sub_header_realisasi = 0;
                    $sub_header_nota = 0;
                    $sub_header_pranota = 0;
                    $sub_header_jumlah_realisasi = 0;
                    $sub_header_sisa_anggaran = 0;
                    $sub_header_realisasi_persen = 0;
                }
                $sub_header_nominal += $dataCapex['jml_nominal'];
                $sub_header_realisasi += $dataCapex['jml_realisasi'];
                $sub_header_nota += $dataCapex['nota'];
                $sub_header_pranota += $dataCapex['pra_nota'];
                $sub_header_jumlah_realisasi = $sub_header_realisasi + $sub_header_nota + $sub_header_pranota;
                $sub_header_sisa_anggaran = $sub_header_nominal - $sub_header_jumlah_realisasi;
                $sub_header_realisasi_persen = cekPersenNew($sub_header_jumlah_realisasi, $sub_header_nominal);
                // END sub total per sub header ditengah

                // Sub total per header ditengah
                if ($no > 1 && $header != $dataCapex['nm_header']) {  ?>
                    <tr style="background-color: red;">
                        <th></th>
                        <th style="text-align: left;"><?= $header; ?></th>
                        <!-- <th></th> -->
                        <td style="text-align: right;"><b><?= formatRupiah2($header_nominal); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($header_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($header_nota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($header_pranota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($header_jumlah_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungSurplus2($header_nominal, $header_jumlah_realisasi); ?></b></td>
                        <th><?= $header_realisasi_persen; ?>%</th>
                        <!-- <th></th> -->
                    </tr>

                <?php
                    // setelah ditampilin, dideklarasiin lagi ke 0
                    $header_nominal = 0;
                    $header_realisasi = 0;
                    $header_nota = 0;
                    $header_pranota = 0;
                    $header_jumlah_realisasi = 0;
                    $header_sisa_anggaran = 0;
                    $header_realisasi_persen = 0;
                }
                $header_nominal += $dataCapex['jml_nominal'];
                $header_realisasi += $dataCapex['jml_realisasi'];
                $header_nota += $dataCapex['nota'];
                $header_pranota += $dataCapex['pra_nota'];
                $header_jumlah_realisasi = $header_realisasi + $header_nota + $header_pranota;
                $header_sisa_anggaran = $header_nominal - $header_jumlah_realisasi;
                $header_realisasi_persen = cekPersenNew($header_jumlah_realisasi, $header_nominal);
                // END total per header ditengah

                // isi
                $jumlah_realisasi =   $dataCapex['jml_realisasi'] + $dataCapex['nota'] + $dataCapex['pra_nota'];
                $sisa_anggaran = $dataCapex['jml_nominal'] - $jumlah_realisasi;
                $realisasi_persen = cekPersenNew($jumlah_realisasi, $dataCapex['jml_nominal']);
                ?>
                <tr>
                    <td><?= $dataCapex['no_coa']; ?></td>
                    <td style="text-align: left;"><?= $dataCapex['nm_coa']; ?></td>
                    <!-- <td><?= $dataCapex['nm_divisi']; ?></td> -->
                    <td style="text-align: right;"><?= formatRupiah2($dataCapex['jml_nominal']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($dataCapex['jml_realisasi']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($dataCapex['nota']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($dataCapex['pra_nota']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($jumlah_realisasi); ?></td>
                    <td style="text-align: right;"><?= kurungSurplus2($dataCapex['jml_nominal'], $jumlah_realisasi); ?></td>
                    <td style="text-align: center;"><?= $realisasi_persen; ?>%</td>
                    <!-- <td>-</td> -->
                </tr>
                <!-- end isi -->

            <?php
                $sub_header = $dataCapex['nm_subheader'];
                $header = $dataCapex['nm_header'];
                $no++;
            }
            ?>

            <!-- sub total sub header paling akhir -->
            <tr style="background-color: yellow;">
                <th></th>
                <th style="text-align: left;"><?= $sub_header; ?></th>
                <!-- <th></th> -->
                <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nominal); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_header_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_header_pranota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_header_jumlah_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= kurungSurplus2($sub_header_nominal, $sub_header_jumlah_realisasi); ?></b></td>
                <th><?= $sub_header_realisasi_persen; ?>%</th>
                <!-- <th></th> -->
            </tr>

            <!-- total header paling akhir -->
            <tr style="background-color: red;">
                <th></th>
                <th style="text-align: left;"><?= $header; ?></th>
                <!-- <th></th> -->
                <td style="text-align: right;"><b><?= formatRupiah2($header_nominal); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($header_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($header_nota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($header_pranota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($header_jumlah_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= kurungSurplus2($header_nominal, $header_jumlah_realisasi); ?></b></td>
                <th><?= $header_realisasi_persen; ?>%</th>
                <!-- <th></th> -->
            </tr>
        </table>
    </div>
</div>

<script>
    var host = '<?= host(); ?>';

    $(function() {
        $("#material").DataTable({
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
                "sEmptyTable": "Tidak ada data di database"
            }
        });
    });


    $('.project_id').on('change', function() {
        let projectId = this.value;

        // console.log(projectId);
        $.ajax({
            url: host + 'api/anggaran/getDivisiProject.php',
            data: {
                id: projectId
            },
            method: 'post',
            dataType: 'json',
            success: function(data) {
                // console.log(data);

                $('#divisi').empty();
                $.each(data, function(i, value) {
                    $('#divisi').append($('<option>').text(value.nm_parent).attr('value', value.id_parent));
                });
            }
        });
        // }
    });
    $('.divisi').on('change', function() {
        let divisi = this.value;
    });
</script>