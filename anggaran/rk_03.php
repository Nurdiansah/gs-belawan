<?php

if ($_POST['cetak']) {
    header("Location: cetak_rk03.php?tahun=" . enkripRambo($_POST['tahun']) . "&project=" . enkripRambo($_POST['project']) . "&divisi=" . enkripRambo($_POST['divisi']) . "");
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


$queryOpex = mysqli_query($koneksi, "SELECT DISTINCT agg.id_anggaran, kd_pt, nm_pt, nm_divisi, kd_programkerja, nm_programkerja, no_coa, nm_coa, nm_item, 
                                            januari_nominal + februari_nominal + maret_nominal + april_nominal + mei_nominal + juni_nominal + juli_nominal + agustus_nominal + september_nominal + oktober_nominal + november_nominal + desember_nominal AS jml_nominal,
                                            januari_realisasi + februari_realisasi + maret_realisasi + april_realisasi + mei_realisasi + juni_realisasi + juli_realisasi + agustus_realisasi + september_realisasi + oktober_realisasi + november_realisasi + desember_realisasi AS jml_realisasi,
                                            IFNULL(SUM(nota.nominal), 0) AS nota, IFNULL(SUM(pra_nota.nominal), 0) AS pra_nota
                                            -- (januari_realisasi + februari_realisasi + maret_realisasi + april_realisasi + mei_realisasi + juni_realisasi + juli_realisasi + agustus_realisasi + september_realisasi + oktober_realisasi + november_realisasi + desember_realisasi) + IFNULL(SUM(nota.nominal), 0) + IFNULL(SUM(pra_nota.nominal), 0) AS total_realisasi
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
                                        LEFT JOIN realisasi_sementara nota
                                            ON agg.id_anggaran = nota.id_anggaran
                                            AND nota.pengajuan = 'BUM'
                                            AND nota.is_deleted = '0'
                                        LEFT JOIN realisasi_sementara pra_nota
                                            ON agg.id_anggaran = pra_nota.id_anggaran
                                            AND pra_nota.pengajuan = 'PO'
                                            AND pra_nota.is_deleted = '0'
                                        WHERE agg.tahun = '$tahun'
                                        AND id_pt = '$project'
                                        AND id_parent = '$divisi'
                                        AND tipe_anggaran = 'OPEX'
                                        GROUP BY agg.id_anggaran
                                        ORDER BY jenis_anggaran DESC, nm_pt, nm_divisi, nm_programkerja ASC
                            ");

$queryCapex = mysqli_query($koneksi, "SELECT DISTINCT agg.id_anggaran, kd_pt, nm_pt, nm_divisi, kd_programkerja, nm_programkerja, no_coa, nm_coa, nm_item, 
                                            januari_nominal + februari_nominal + maret_nominal + april_nominal + mei_nominal + juni_nominal + juli_nominal + agustus_nominal + september_nominal + oktober_nominal + november_nominal + desember_nominal AS jml_nominal,
                                            januari_realisasi + februari_realisasi + maret_realisasi + april_realisasi + mei_realisasi + juni_realisasi + juli_realisasi + agustus_realisasi + september_realisasi + oktober_realisasi + november_realisasi + desember_realisasi AS jml_realisasi,
                                            IFNULL(SUM(nota.nominal), 0) AS nota, IFNULL(SUM(pra_nota.nominal), 0) AS pra_nota
                                            -- (januari_realisasi + februari_realisasi + maret_realisasi + april_realisasi + mei_realisasi + juni_realisasi + juli_realisasi + agustus_realisasi + september_realisasi + oktober_realisasi + november_realisasi + desember_realisasi) + IFNULL(SUM(nota.nominal), 0) + IFNULL(SUM(pra_nota.nominal), 0) AS total_realisasi
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
                                        LEFT JOIN realisasi_sementara nota
                                            ON agg.id_anggaran = nota.id_anggaran
                                            AND nota.pengajuan = 'BUM'
                                            AND nota.is_deleted = '0'
                                        LEFT JOIN realisasi_sementara pra_nota
                                            ON agg.id_anggaran = pra_nota.id_anggaran
                                            AND pra_nota.pengajuan = 'PO'
                                            AND pra_nota.is_deleted = '0'
                                        WHERE agg.tahun = '$tahun'
                                        AND id_pt = '$project'
                                        AND id_parent = '$divisi'
                                        AND tipe_anggaran = 'CAPEX'
                                        GROUP BY agg.id_anggaran
                                        ORDER BY jenis_anggaran DESC, nm_pt, nm_divisi, nm_programkerja ASC
                            ");


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
            <label id="tes" for="tahun" class="col-sm-offset-4 col-sm-1 control-label">Tahun</label>
            <div class="col-sm-offset- col-sm-3">
                <select name="tahun" class="form-control" required>
                    <?php foreach (range(2021, $tahunAyeuna) as $tahunLoop) { ?>
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
                <input type="submit" name="tampil" class="btn btn-success col-sm-offset-1" value="Tampilkan">
                <input type="submit" name="cetak" class="btn btn-primary col-sm-offset-" value="Cetak">
                <!-- <input type="reset" name="batal" class="btn btn-danger col-sm-offset-" value="Batal"> -->
            </div>
        </div>
    </form>

    <br>
    <hr>
    <h4><b><u>OPEX</u></b></h4>
    <div class="table-responsive">
        <table class="table text-center table table-striped table-hover" id="material" border="2">
            <tr style="background-color: #98FB98;">
                <th>Kode Proyek</th>
                <th>Deskripsi Proyek</th>
                <th>Kode Departemen</th>
                <th>Kode Sub Departemen</th>
                <th>Kode Rencana Kerja</th>
                <th>Deskripsi Rencana Kerja</th>
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th>Deskripsi Biaya</th>
                <th>Anggaran</th>
                <th>Realisasi Kas</th>
                <th>Realisasi Nota</th>
                <th>Realisasi Pra Nota</th>
                <th>Total Realisasi</th>
                <th>Sisa Anggaran</th>
                <th>% Realisasi</th>
                <th>Link</th>
            </tr>
            <?php

            $program_kerja = 0;
            $divisi = 0;
            $pt = 0;
            $no = 1;

            // variabel per program kerja, dideklarasiin 0 dulu
            $pk_kd_pt = "";
            $pk_nm_pt = "";
            $pk_kd_dept = "";
            $pk_kd_subdept = "";
            $pk_kd_subrenja = "";
            $sub_pk_nominal = 0;
            $sub_pk_realisasi = 0;
            $sub_pk_nota = 0;
            $sub_pk_pranota = 0;
            $sub_pk_jumlah_realisasi = 0;
            $sub_pk_sisa_anggaran = 0;
            $sub_pk_realisasi_persen = 0;

            // variabel nominal per divisi, dideklarasiin 0 dulu
            $sub_divisi_nominal = 0;
            $sub_divisi_realisasi = 0;
            $sub_divisi_nota = 0;
            $sub_divisi_pranota = 0;
            $sub_divisi_jumlah_realisasi = 0;
            $sub_divisi_sisa_anggaran = 0;
            $sub_divisi_realisasi_persen = 0;

            // variabel nominal per pt, dideklarasiin 0 dulu
            $sub_pt_nominal = 0;
            $sub_pt_realisasi = 0;
            $sub_pt_nota = 0;
            $sub_pt_pranota = 0;
            $sub_pt_jumlah_realisasi = 0;
            $sub_pt_sisa_anggaran = 0;
            $sub_pt_realisasi_persen = 0;

            while ($dataOpex = mysqli_fetch_assoc($queryOpex)) {

                // sub total per program kerja ditengah
                if ($no > 1 && $program_kerja != $dataOpex['nm_programkerja']) {  ?>
                    <!-- tampilin sub total per PK nya -->
                    <tr>
                        <th><?= $pk_kd_pt; ?></th>
                        <th><?= $pk_nm_pt; ?></th>
                        <th><?= $pk_kd_dept; ?></th>
                        <th><?= $pk_kd_subdept; ?></th>
                        <th><?= $pk_kd_subrenja; ?></th>
                        <th style="text-align: left;"><?= $program_kerja; ?></th>
                        <th></th>
                        <th></th>
                        <th>Total</th>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_nominal); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_nota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_pranota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_jumlah_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungSurplus2($sub_pk_nominal, $sub_pk_jumlah_realisasi); ?></b></td>
                        <th><?= $sub_pk_realisasi_persen; ?>%</th>
                        <th></th>
                    </tr>

                <?php
                    // setelah ditampilin, dideklarasiin lagi ke 0
                    $pk_kd_pt = "";
                    $pk_nm_pt = "";
                    $pk_kd_dept = "";
                    $pk_kd_subdept = "";
                    $pk_kd_subrenja = "";
                    $sub_pk_nominal = 0;
                    $sub_pk_realisasi = 0;
                    $sub_pk_nota = 0;
                    $sub_pk_pranota = 0;
                    $sub_pk_jumlah_realisasi = 0;
                    $sub_pk_sisa_anggaran = 0;
                    $sub_pk_realisasi_persen = 0;
                }
                $pk_kd_pt = $dataOpex['kd_pt'];
                $pk_nm_pt = $dataOpex['nm_pt'];
                $pk_kd_dept = substr($dataOpex['kd_programkerja'], 5, 2);
                $pk_kd_subdept = substr($dataOpex['kd_programkerja'], 5, 4);
                $pk_kd_subrenja = $dataOpex['kd_programkerja'];
                $sub_pk_nominal += $dataOpex['jml_nominal'];
                $sub_pk_realisasi += $dataOpex['jml_realisasi'];
                $sub_pk_nota += $dataOpex['nota'];
                $sub_pk_pranota += $dataOpex['pra_nota'];
                $sub_pk_jumlah_realisasi = $sub_pk_realisasi + $sub_pk_nota + $sub_pk_pranota;
                $sub_pk_sisa_anggaran = $sub_pk_nominal - $sub_pk_jumlah_realisasi;
                $sub_pk_realisasi_persen = cekPersenNew($sub_pk_jumlah_realisasi, $sub_pk_nominal);
                // END sub total per program kerja ditengah


                // sub total per divisi ditengah
                if ($no > 1 && $divisi != $dataOpex['nm_divisi']) { ?>
                    <tr style="background-color: #F0E68C">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Total Divisi <?= $divisi; ?></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_nominal); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_nota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_pranota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_jumlah_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungSurplus2($sub_divisi_nominal, $sub_divisi_jumlah_realisasi); ?></b></td>
                        <th><?= $sub_divisi_realisasi_persen; ?>%</th>
                        <th></th>
                    </tr>
                <?php

                    $sub_divisi_nominal = 0;
                    $sub_divisi_realisasi = 0;
                    $sub_divisi_nota = 0;
                    $sub_divisi_pranota = 0;
                    $sub_divisi_jumlah_realisasi = 0;
                    $sub_divisi_sisa_anggaran = 0;
                    $sub_divisi_realisasi_persen = 0;
                }
                $sub_divisi_nominal += $dataOpex['jml_nominal'];
                $sub_divisi_realisasi += $dataOpex['jml_realisasi'];
                $sub_divisi_nota += $dataOpex['nota'];
                $sub_divisi_pranota += $dataOpex['pra_nota'];
                $sub_divisi_jumlah_realisasi = $sub_divisi_realisasi + $sub_divisi_nota + $sub_divisi_pranota;
                $sub_divisi_sisa_anggaran = $sub_divisi_nominal - $sub_divisi_jumlah_realisasi;
                $sub_divisi_realisasi_persen = cekPersenNew($sub_divisi_jumlah_realisasi, $sub_divisi_nominal);
                // END sub total per divisi ditengah


                // sub total per pt ditengah
                if ($no > 1 && $pt != $dataOpex['nm_pt']) { ?>
                    <tr style="background-color: #87CEFA">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Total - <?= $pt; ?></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_nominal); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_nota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_pranota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_jumlah_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungSurplus2($sub_pt_nominal, $sub_pt_jumlah_realisasi); ?></b></td>
                        <th><?= $sub_pt_realisasi_persen; ?>%</th>
                        <th></th>
                    </tr>
                <?php

                    $sub_pt_nominal = 0;
                    $sub_pt_realisasi = 0;
                    $sub_pt_nota = 0;
                    $sub_pt_pranota = 0;
                    $sub_pt_jumlah_realisasi = 0;
                    $sub_pt_sisa_anggaran = 0;
                    $sub_pt_realisasi_persen = 0;
                }
                $sub_pt_nominal += $dataOpex['jml_nominal'];
                $sub_pt_realisasi += $dataOpex['jml_realisasi'];
                $sub_pt_nota += $dataOpex['nota'];
                $sub_pt_pranota += $dataOpex['pra_nota'];
                $sub_pt_jumlah_realisasi = $sub_pt_realisasi + $sub_pt_nota + $sub_pt_pranota;
                $sub_pt_sisa_anggaran = $sub_pt_nominal - $sub_pt_jumlah_realisasi;
                $sub_pt_realisasi_persen = cekPersenNew($sub_pt_jumlah_realisasi, $sub_pt_nominal);
                // END sub total per divisi ditengah

                ?>


                <!-- isi  -->
                <?php
                $jumlah_realisasi =   $dataOpex['jml_realisasi'] + $dataOpex['nota'] + $dataOpex['pra_nota'];
                $sisa_anggaran = $dataOpex['jml_nominal'] - $jumlah_realisasi;
                $realisasi_persen = cekPersenNew($jumlah_realisasi, $dataOpex['jml_nominal']);
                ?>
                <tr>
                    <td><?= $dataOpex['kd_pt']; ?></td>
                    <td><?= $dataOpex['nm_pt']; ?></td>
                    <td><?= substr($dataOpex['kd_programkerja'], 5, 2); ?></td>
                    <td><?= substr($dataOpex['kd_programkerja'], 5, 4); ?></td>
                    <td><?= $dataOpex['kd_programkerja']; ?></td>
                    <td style="text-align: left;"><?= $dataOpex['nm_programkerja']; ?></td>
                    <td><?= $dataOpex['no_coa']; ?></td>
                    <td style="text-align: left;"><?= $dataOpex['nm_coa']; ?></td>
                    <td style="text-align: left;"><?= $dataOpex['nm_item']; ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($dataOpex['jml_nominal']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($dataOpex['jml_realisasi']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($dataOpex['nota']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($dataOpex['pra_nota']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($jumlah_realisasi); ?></td>
                    <td style="text-align: right;"><?= kurungSurplus2($dataOpex['jml_nominal'], $jumlah_realisasi); ?></td>
                    <td><?= $realisasi_persen; ?>%</td>
                    <td><a target="_blank" href="<?= host(); ?>index.php?<?= $link; ?>&sp=<?= enkripRambo($dataOpex['id_anggaran']); ?>">Lihat BKK</a></td>
                </tr>
                <!-- end isi -->

            <?php
                $program_kerja = $dataOpex['nm_programkerja'];
                $divisi = $dataOpex['nm_divisi'];
                $pt = $dataOpex['nm_pt'];
                $no++;
            }


            // sub total program kerja & divisi paling akhir
            // if ($no > 1 &&  $no == $total + 1) { 
            ?>
            <!-- program kerja -->
            <tr>
                <th><?= $pk_kd_pt; ?></th>
                <th><?= $pk_nm_pt; ?></th>
                <th><?= $pk_kd_dept; ?></th>
                <th><?= $pk_kd_subdept; ?></th>
                <th><?= $pk_kd_subrenja; ?></th>
                <th style="text-align: left;"><?= $program_kerja; ?></th>
                <th></th>
                <th></th>
                <th>Total</th>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_nominal); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_nota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_pranota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_jumlah_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= kurungSurplus2($sub_pk_nominal, $sub_pk_jumlah_realisasi); ?></b></td>
                <th><?= $sub_pk_realisasi_persen; ?>%</th>
                <th></th>
            </tr>

            <!-- divisi -->
            <tr style="background-color: #F0E68C">
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Total Divisi <?= $divisi; ?></th>
                <th></th>
                <th></th>
                <th></th>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_nominal); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_nota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_pranota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_jumlah_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= kurungSurplus2($sub_divisi_nominal, $sub_divisi_jumlah_realisasi); ?></b></td>
                <th><?= $sub_divisi_realisasi_persen; ?>%</th>
                <th></th>
            </tr>

            <!-- pt -->
            <tr style="background-color: #87CEFA">
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Total - <?= $pt; ?></th>
                <th></th>
                <th></th>
                <th></th>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_nominal); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_nota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_pranota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_jumlah_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= kurungSurplus2($sub_pt_nominal, $sub_pt_jumlah_realisasi); ?></b></td>
                <th><?= $sub_pt_realisasi_persen; ?>%</th>
                <th></th>
            </tr>
        </table>
    </div>

    <!-- ------------ CAPEX ------------ -->
    <br><br>
    <h4><b><u>CAPEX</u></b></h4>
    <div class="table-responsive">
        <table class="table text-center table table-striped table-hover" id="material" border="2">
            <tr style="background-color: #98FB98;">
                <th>Kode Proyek</th>
                <th>Deskripsi Proyek</th>
                <th>Kode Departemen</th>
                <th>Kode Sub Departemen</th>
                <th>Kode Rencana Kerja</th>
                <th>Deskripsi Rencana Kerja</th>
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th>Deskripsi Biaya</th>
                <th>Anggaran</th>
                <th>Realisasi Kas</th>
                <th>Realisasi Nota</th>
                <th>Realisasi Pra Nota</th>
                <th>Total Realisasi</th>
                <th>Sisa Anggaran</th>
                <th>% Realisasi</th>
                <th>Link</th>
            </tr>
            <?php

            $program_kerja = 0;
            $divisi = 0;
            $pt = 0;
            $no = 1;

            // variabel per program kerja, dideklarasiin 0 dulu
            $pk_kd_pt = "";
            $pk_nm_pt = "";
            $pk_kd_dept = "";
            $pk_kd_subdept = "";
            $pk_kd_subrenja = "";
            $sub_pk_nominal = 0;
            $sub_pk_realisasi = 0;
            $sub_pk_nota = 0;
            $sub_pk_pranota = 0;
            $sub_pk_jumlah_realisasi = 0;
            $sub_pk_sisa_anggaran = 0;
            $sub_pk_realisasi_persen = 0;

            // variabel nominal per divisi, dideklarasiin 0 dulu
            $sub_divisi_nominal = 0;
            $sub_divisi_realisasi = 0;
            $sub_divisi_nota = 0;
            $sub_divisi_pranota = 0;
            $sub_divisi_jumlah_realisasi = 0;
            $sub_divisi_sisa_anggaran = 0;
            $sub_divisi_realisasi_persen = 0;

            // variabel nominal per pt, dideklarasiin 0 dulu
            $sub_pt_nominal = 0;
            $sub_pt_realisasi = 0;
            $sub_pt_nota = 0;
            $sub_pt_pranota = 0;
            $sub_pt_jumlah_realisasi = 0;
            $sub_pt_sisa_anggaran = 0;
            $sub_pt_realisasi_persen = 0;

            while ($dataCapex = mysqli_fetch_assoc($queryCapex)) {

                // sub total per program kerja ditengah
                if ($no > 1 && $program_kerja != $dataCapex['nm_programkerja']) {  ?>
                    <!-- tampilin sub total per PK nya -->
                    <tr>
                        <th><?= $pk_kd_pt; ?></th>
                        <th><?= $pk_nm_pt; ?></th>
                        <th><?= $pk_kd_dept; ?></th>
                        <th><?= $pk_kd_subdept; ?></th>
                        <th><?= $pk_kd_subrenja; ?></th>
                        <th style="text-align: left;"><?= $program_kerja; ?></th>
                        <th></th>
                        <th></th>
                        <th>Total</th>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_nominal); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_nota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_pranota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_jumlah_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungSurplus2($sub_pk_nominal, $sub_pk_jumlah_realisasi); ?></b></td>
                        <th><?= $sub_pk_realisasi_persen; ?>%</th>
                        <th></th>
                    </tr>

                <?php
                    // setelah ditampilin, dideklarasiin lagi ke 0
                    $pk_kd_pt = "";
                    $pk_nm_pt = "";
                    $pk_kd_dept = "";
                    $pk_kd_subdept = "";
                    $pk_kd_subrenja = "";
                    $sub_pk_nominal = 0;
                    $sub_pk_realisasi = 0;
                    $sub_pk_nota = 0;
                    $sub_pk_pranota = 0;
                    $sub_pk_jumlah_realisasi = 0;
                    $sub_pk_sisa_anggaran = 0;
                    $sub_pk_realisasi_persen = 0;
                }
                $pk_kd_pt = $dataCapex['kd_pt'];
                $pk_nm_pt = $dataCapex['nm_pt'];
                $pk_kd_dept = substr($dataCapex['kd_programkerja'], 5, 2);
                $pk_kd_subdept = substr($dataCapex['kd_programkerja'], 5, 4);
                $pk_kd_subrenja = $dataCapex['kd_programkerja'];
                $sub_pk_nominal += $dataCapex['jml_nominal'];
                $sub_pk_realisasi += $dataCapex['jml_realisasi'];
                $sub_pk_nota += $dataCapex['nota'];
                $sub_pk_pranota += $dataCapex['pra_nota'];
                $sub_pk_jumlah_realisasi = $sub_pk_realisasi + $sub_pk_nota + $sub_pk_pranota;
                $sub_pk_sisa_anggaran = $sub_pk_nominal - $sub_pk_jumlah_realisasi;
                $sub_pk_realisasi_persen = cekPersenNew($sub_pk_jumlah_realisasi, $sub_pk_nominal);
                // END sub total per program kerja ditengah


                // sub total per divisi ditengah
                if ($no > 1 && $divisi != $dataCapex['nm_divisi']) { ?>
                    <tr style="background-color: #F0E68C">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Total Divisi <?= $divisi; ?></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_nominal); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_nota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_pranota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_jumlah_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungSurplus2($sub_divisi_nominal, $sub_divisi_jumlah_realisasi); ?></b></td>
                        <th><?= $sub_divisi_realisasi_persen; ?>%</th>
                        <th></th>
                    </tr>
                <?php

                    $sub_divisi_nominal = 0;
                    $sub_divisi_realisasi = 0;
                    $sub_divisi_nota = 0;
                    $sub_divisi_pranota = 0;
                    $sub_divisi_jumlah_realisasi = 0;
                    $sub_divisi_sisa_anggaran = 0;
                    $sub_divisi_realisasi_persen = 0;
                }
                $sub_divisi_nominal += $dataCapex['jml_nominal'];
                $sub_divisi_realisasi += $dataCapex['jml_realisasi'];
                $sub_divisi_nota += $dataCapex['nota'];
                $sub_divisi_pranota += $dataCapex['pra_nota'];
                $sub_divisi_jumlah_realisasi = $sub_divisi_realisasi + $sub_divisi_nota + $sub_divisi_pranota;
                $sub_divisi_sisa_anggaran = $sub_divisi_nominal - $sub_divisi_jumlah_realisasi;
                $sub_divisi_realisasi_persen = cekPersenNew($sub_divisi_jumlah_realisasi, $sub_divisi_nominal);
                // END sub total per divisi ditengah


                // sub total per pt ditengah
                if ($no > 1 && $pt != $dataCapex['nm_pt']) { ?>
                    <tr style="background-color: #87CEFA">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Total - <?= $pt; ?></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_nominal); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_nota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_pranota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_jumlah_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungSurplus2($sub_pt_nominal, $sub_pt_jumlah_realisasi); ?></b></td>
                        <th><?= $sub_pt_realisasi_persen; ?>%</th>
                        <th></th>
                    </tr>
                <?php

                    $sub_pt_nominal = 0;
                    $sub_pt_realisasi = 0;
                    $sub_pt_nota = 0;
                    $sub_pt_pranota = 0;
                    $sub_pt_jumlah_realisasi = 0;
                    $sub_pt_sisa_anggaran = 0;
                    $sub_pt_realisasi_persen = 0;
                }
                $sub_pt_nominal += $dataCapex['jml_nominal'];
                $sub_pt_realisasi += $dataCapex['jml_realisasi'];
                $sub_pt_nota += $dataCapex['nota'];
                $sub_pt_pranota += $dataCapex['pra_nota'];
                $sub_pt_jumlah_realisasi = $sub_pt_realisasi + $sub_pt_nota + $sub_pt_pranota;
                $sub_pt_sisa_anggaran = $sub_pt_nominal - $sub_pt_jumlah_realisasi;
                $sub_pt_realisasi_persen = cekPersenNew($sub_pt_jumlah_realisasi, $sub_pt_nominal);
                // END sub total per divisi ditengah

                ?>


                <!-- isi  -->
                <?php
                $jumlah_realisasi =   $dataCapex['jml_realisasi'] + $dataCapex['nota'] + $dataCapex['pra_nota'];
                $sisa_anggaran = $dataCapex['jml_nominal'] - $jumlah_realisasi;
                $realisasi_persen = cekPersenNew($jumlah_realisasi, $dataCapex['jml_nominal']);
                ?>
                <tr>
                    <td><?= $dataCapex['kd_pt']; ?></td>
                    <td><?= $dataCapex['nm_pt']; ?></td>
                    <td><?= substr($dataCapex['kd_programkerja'], 5, 2); ?></td>
                    <td><?= substr($dataCapex['kd_programkerja'], 5, 4); ?></td>
                    <td><?= $dataCapex['kd_programkerja']; ?></td>
                    <td style="text-align: left;"><?= $dataCapex['nm_programkerja']; ?></td>
                    <td><?= $dataCapex['no_coa']; ?></td>
                    <td style="text-align: left;"><?= $dataCapex['nm_coa']; ?></td>
                    <td style="text-align: left;"><?= $dataCapex['nm_item']; ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($dataCapex['jml_nominal']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($dataCapex['jml_realisasi']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($dataCapex['nota']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($dataCapex['pra_nota']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($jumlah_realisasi); ?></td>
                    <td style="text-align: right;"><?= kurungSurplus2($dataCapex['jml_nominal'], $jumlah_realisasi); ?></td>
                    <td><?= $realisasi_persen; ?>%</td>
                    <td><a target="_blank" href="<?= host(); ?>index.php?<?= $link; ?>&sp=<?= enkripRambo($dataCapex['id_anggaran']); ?>">Lihat BKK</a></td>
                </tr>
                <!-- end isi -->

            <?php
                $program_kerja = $dataCapex['nm_programkerja'];
                $divisi = $dataCapex['nm_divisi'];
                $pt = $dataCapex['nm_pt'];
                $no++;
            }


            // sub total program kerja & divisi paling akhir
            // if ($no > 1 &&  $no == $total + 1) { 
            ?>
            <!-- program kerja -->
            <tr>
                <th><?= $pk_kd_pt; ?></th>
                <th><?= $pk_nm_pt; ?></th>
                <th><?= $pk_kd_dept; ?></th>
                <th><?= $pk_kd_subdept; ?></th>
                <th><?= $pk_kd_subrenja; ?></th>
                <th style="text-align: left;"><?= $program_kerja; ?></th>
                <th></th>
                <th></th>
                <th>Total</th>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_nominal); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_nota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_pranota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_jumlah_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= kurungSurplus2($sub_pk_nominal, $sub_pk_jumlah_realisasi); ?></b></td>
                <th><?= $sub_pk_realisasi_persen; ?>%</th>
                <th></th>
            </tr>

            <!-- divisi -->
            <tr style="background-color: #F0E68C">
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Total Divisi <?= $divisi; ?></th>
                <th></th>
                <th></th>
                <th></th>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_nominal); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_nota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_pranota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_jumlah_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= kurungSurplus2($sub_divisi_nominal, $sub_divisi_jumlah_realisasi); ?></b></td>
                <th><?= $sub_divisi_realisasi_persen; ?>%</th>
                <th></th>
            </tr>

            <!-- pt -->
            <tr style="background-color: #87CEFA">
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Total - <?= $pt; ?></th>
                <th></th>
                <th></th>
                <th></th>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_nominal); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_nota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_pranota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_jumlah_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= kurungSurplus2($sub_pt_nominal, $sub_pt_jumlah_realisasi); ?></b></td>
                <th><?= $sub_pt_realisasi_persen; ?>%</th>
                <th></th>
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