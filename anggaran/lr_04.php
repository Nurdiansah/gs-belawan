<?php

if ($_POST['cetak']) {
    header("Location: cetak_lr04.php?tahun=" . enkripRambo($_POST['tahun']) . "&project=" . enkripRambo($_POST['project']) . "&divisi=" . enkripRambo($_POST['divisi']) . "&sub_divisi=" . enkripRambo($_POST['sub_divisi']) . "");
}

if ($_POST['tampil']) {
    $tahun = $_POST['tahun'];
    $project = $_POST['project'];
    $divisi = $_POST['divisi'];
    $sub_divisi = $_POST['sub_divisi'];
} else {
    $tahun = date("Y");
    $project = "1";
    $divisi = "1";
    $sub_divisi = "20";
}

$query = mysqli_query($koneksi, "SELECT DISTINCT agg.id_anggaran, nm_divisi, nm_header, nm_subheader, no_coa, nm_coa, nm_item, 
                                        januari_nominal + februari_nominal + maret_nominal + april_nominal + mei_nominal + juni_nominal + juli_nominal + agustus_nominal + september_nominal + oktober_nominal + november_nominal + desember_nominal AS jml_nominal,
                                        januari_realisasi + februari_realisasi + maret_realisasi + april_realisasi + mei_realisasi + juni_realisasi + juli_realisasi + agustus_realisasi + september_realisasi + oktober_realisasi + november_realisasi + desember_realisasi AS jml_realisasi,
                                        IFNULL(SUM(nota.nominal), 0) AS nota, IFNULL(SUM(pra_nota.nominal), 0) AS pra_nota
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
                                    JOIN sub_header sh
                                        ON subheader_id = id_subheader
                                    JOIN header h
                                        ON sh.id_header = h.id_header
                                    WHERE agg.tahun = '$tahun'
                                    AND id_pt = '$project'
                                    AND id_parent = '$divisi'
                                    AND agg.id_divisi = '$sub_divisi'
                                    GROUP BY agg.id_anggaran
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

$querySubDivisi = mysqli_query($koneksi, "SELECT * FROM divisi
                                            JOIN parent_divisi
                                                ON id_parent = parent_id
                                            JOIN cost_center
                                                ON id_divisi = divisi_id
                                            WHERE parent_id = '$divisi'
                                            AND pt_id = '$project'
                                            AND id_divisi <> '0'
                                            ORDER BY nm_divisi ASC
                                ")

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
                <select name="project" class="form-control project_id" id="project" required>
                    <?php while ($dataPT = mysqli_fetch_assoc($queryPT)) { ?>
                        <option value="<?= $dataPT['id_pt']; ?>" <?= $dataPT['id_pt'] == $project ? "selected" : ""; ?>><?= $dataPT['nm_pt']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label id="tes" for="divisi" class="col-sm-offset-4 col-sm-1 control-label">Divisi</label>
            <div class="col-sm-offset- col-sm-3">
                <select name="divisi" class="form-control divisi_id" id="divisi" required>
                    <?php while ($dataDivisi = mysqli_fetch_assoc($queryDivisi)) { ?>
                        <option value="<?= $dataDivisi['id_parent']; ?>" <?= $dataDivisi['id_parent'] == $divisi ? "selected" : ""; ?>><?= $dataDivisi['nm_parent']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label id="tes" for="sub_divisi" class="col-sm-offset-4 col-sm-1 control-label">Sub Divisi</label>
            <div class="col-sm-offset- col-sm-3">
                <select name="sub_divisi" class="form-control sub_divisi" id="sub_divisi" required>
                    <?php while ($dataSubDivisi = mysqli_fetch_assoc($querySubDivisi)) { ?>
                        <option value="<?= $dataSubDivisi['id_divisi']; ?>" <?= $dataSubDivisi['id_divisi'] == $sub_divisi ? "selected" : ""; ?>><?= $dataSubDivisi['nm_divisi']; ?></option>
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
    <div class="table-responsive">
        <table class="table text-center table table-striped table-hover" id="material" border="2">
            <tr style="background-color: #87CEFA;">
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th>Divisi</th>
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
            $sub_header = 0;
            $sub_header_nominal = 0;
            $sub_header_realisasi = 0;
            $sub_header_nota = 0;
            $sub_header_pranota = 0;
            $sub_header_jumlah_realisasi = 0;
            $sub_header_sisa_anggaran = 0;
            $sub_header_realisasi_persen = 0;

            // variabel per header, dideklarasiin 0 dulu
            $header = 0;
            $header_nominal = 0;
            $header_realisasi = 0;
            $header_nota = 0;
            $header_pranota = 0;
            $header_jumlah_realisasi = 0;
            $header_sisa_anggaran = 0;
            $header_realisasi_persen = 0;

            $no = 1;

            while ($data = mysqli_fetch_assoc($query)) {

                // Sub total per sub header ditengah
                if ($no > 1 && $sub_header != $data['nm_subheader']) {  ?>
                    <tr style="background-color: yellow;">
                        <th></th>
                        <th><?= $sub_header; ?></th>
                        <th></th>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nominal); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_header_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_header_pranota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_header_jumlah_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($sub_header_sisa_anggaran); ?></b></td>
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
                $sub_header_nominal += $data['jml_nominal'];
                $sub_header_realisasi += $data['jml_realisasi'];
                $sub_header_nota += $data['nota'];
                $sub_header_pranota += $data['pra_nota'];
                $sub_header_jumlah_realisasi = $sub_header_realisasi + $sub_header_nota + $sub_header_pranota;
                $sub_header_sisa_anggaran = $sub_header_nominal - $sub_header_jumlah_realisasi;
                $sub_header_realisasi_persen = cekPersenNew($sub_header_jumlah_realisasi, $sub_header_nominal);
                // END sub total per sub header ditengah

                // Sub total per header ditengah
                if ($no > 1 && $header != $data['nm_header']) {  ?>
                    <tr style="background-color: red;">
                        <th></th>
                        <th><?= $header; ?></th>
                        <th></th>
                        <td style="text-align: right;"><b><?= formatRupiah2($header_nominal); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($header_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($header_nota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($header_pranota); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($header_jumlah_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= formatRupiah2($header_sisa_anggaran); ?></b></td>
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
                $header_nominal += $data['jml_nominal'];
                $header_realisasi += $data['jml_realisasi'];
                $header_nota += $data['nota'];
                $header_pranota += $data['pra_nota'];
                $header_jumlah_realisasi = $header_realisasi + $header_nota + $header_pranota;
                $header_sisa_anggaran = $header_nominal - $header_jumlah_realisasi;
                $header_realisasi_persen = cekPersenNew($header_jumlah_realisasi, $header_nominal);
                // END total per header ditengah

                // isi
                $jumlah_realisasi =   $data['jml_realisasi'] + $data['nota'] + $data['pra_nota'];
                $sisa_anggaran = $data['jml_nominal'] - $jumlah_realisasi;
                $realisasi_persen = cekPersenNew($jumlah_realisasi, $data['jml_nominal']);
                ?>
                <tr>
                    <td><?= $data['no_coa']; ?></td>
                    <td><?= $data['nm_coa']; ?></td>
                    <td><?= $data['nm_divisi']; ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($data['jml_nominal']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($data['jml_realisasi']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($data['nota']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($data['pra_nota']); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($jumlah_realisasi); ?></td>
                    <td style="text-align: right;"><?= formatRupiah2($sisa_anggaran); ?></td>
                    <td style="text-align: center;"><?= $realisasi_persen; ?>%</td>
                    <!-- <td>-</td> -->
                </tr>
                <!-- end isi -->

            <?php
                $sub_header = $data['nm_subheader'];
                $header = $data['nm_header'];
                $no++;
            }
            ?>

            <!-- sub total sub header paling akhir -->
            <tr style="background-color: yellow;">
                <th></th>
                <th><?= $sub_header; ?></th>
                <th></th>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nominal); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_header_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_header_pranota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_header_jumlah_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($sub_header_sisa_anggaran); ?></b></td>
                <th><?= $sub_header_realisasi_persen; ?>%</th>
                <!-- <th></th> -->
            </tr>

            <!-- total header paling akhir -->
            <tr style="background-color: red;">
                <th></th>
                <th><?= $header; ?></th>
                <th></th>
                <td style="text-align: right;"><b><?= formatRupiah2($header_nominal); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($header_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($header_nota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($header_pranota); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($header_jumlah_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= formatRupiah2($header_sisa_anggaran); ?></b></td>
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


    // nampilin divisi ketika dipilih projectnya
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

                $('#sub_divisi').empty();
                $('#divisi').empty();
                $('#divisi').append($('<option>').text("-- Pilih Divisi --"))
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

    // nampilin sub divisi ketika dipilih divisinya
    $('.divisi_id').on('change', function() {
        let divisiId = this.value;
        let project = document.getElementById('project').value

        // console.log(prj)
        $.ajax({
            url: host + 'api/anggaran/getSubDivisi.php',
            data: {
                id: divisiId,
                prj: project
            },
            method: 'post',
            dataType: 'json',
            success: function(data) {
                // console.log(data);

                $('#sub_divisi').empty();
                $.each(data, function(i, value) {
                    $('#sub_divisi').append($('<option>').text(value.nm_divisi).attr('value', value.id_divisi));
                });
            }
        });
        // }
    });
    $('.sub_divisi').on('change', function() {
        let sub_divisi = this.value;
    });
</script>