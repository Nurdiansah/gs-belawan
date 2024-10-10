<div class="panel-group" id="accordion">
    <?php while ($dataAnggaran = mysqli_fetch_assoc($queryAnggaran)) {
        $id_anggaran = $dataAnggaran['id_anggaran'];

        // buat ngambil data total realisasi smeentara
        $totalBKK = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id) AS jumlah
                                                                    FROM bkk_final
                                                                    WHERE id_anggaran = '$id_anggaran'
                                                                    AND (status_bkk = '4' OR no_bkk IS NOT NULL)"));

        // gs belawan yg dibayarin dijakarta (dibuat dijakarta)
        $BKKJkt = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id) AS jumlah
                                                                FROM gs.bkk_final
                                                                WHERE id_anggaran = '$id_anggaran'
                                                                AND (status_bkk = '4' OR no_bkk IS NOT NULL)
                                                                AND id_area = '2'"));

        // gs belawan yg dibayarin dijakarta (dibuat dibelawan)
        $BKKBlwJkt = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id) AS jumlah
                                                                    FROM bkk_ke_pusat
                                                                    WHERE id_anggaran = '$id_anggaran'
                                                                    AND (status_bkk = '4' OR no_bkk IS NOT NULL)
                                                                    AND id_kdtransaksi NOT IN (SELECT id_kdtransaksi FROM gs.bkk_final
                                                                                                WHERE id_anggaran = '$id_anggaran'
                                                                                                AND (status_bkk = '4' OR no_bkk IS NOT NULL)
                                                                                                AND id_area = '2')
                                                                    AND pengajuan NOT IN (SELECT pengajuan FROM gs.bkk_final
                                                                                            WHERE id_anggaran = '$id_anggaran'
                                                                                            AND (status_bkk = '4' OR no_bkk IS NOT NULL)
                                                                                            AND id_area = '2')"));

        $totalPetty = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id_pettycash) AS jumlah
                                                                    FROM transaksi_pettycash
                                                                    WHERE id_anggaran = '$id_anggaran'
                                                                    AND status_pettycash = '5'"));

        $totalBP = $totalBKK['jumlah'] + $BKKJkt['jumlah'] + $BKKBlwJkt['jumlah'] + $totalPetty['jumlah'];

    ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <button type="button" class="btn btn-primary btn-xs " data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $no; ?>" style="cursor:pointer;"><i class="fa fa-plus "></i> </button>
                    <b><?= $dataAnggaran['nm_item'] . " ["  . $dataAnggaran['kd_anggaran']; ?>]</b>

                    <span class="label label-default pull-right"><?= $totalBP; ?></span>
                    <!-- <button type="button" title="Hapus Header <?= $dataAnggaran['nm_header']; ?>" class="btn btn-danger btn-xs  pull-right" data-toggle="modal" data-target="#hapusHeader_<?= $dataAnggaran['id_header']; ?>"><i class="fa fa-trash "></i> Hapus Header</button>&nbsp;&nbsp;
                    <button type="button" title="Rubah Header <?= $dataAnggaran['nm_header']; ?>" class="btn btn-warning btn-xs  pull-right" data-toggle="modal" data-target="#rubahHeader_<?= $dataAnggaran['id_header']; ?>"><i class="fa fa-pencil "></i> Rubah Header</button>&nbsp;&nbsp;
                    <button type="button" title="Tambah Sub Header <?= $dataAnggaran['nm_header']; ?>" class="btn btn-primary btn-xs  pull-right" data-toggle="modal" data-target="#tambahSub_<?= $dataAnggaran['id_header']; ?>"><i class="fa fa-plus "></i> Tambah Sub Header</button>&nbsp;&nbsp; -->
                </h4>
            </div>
            <div id="collapse<?= $no; ?>" class="panel-collapse collapse">
                <!-- <div class="panel-body"> -->
                <ul class="list-group">
                    <li class="list-group-item">
                        <div class="box-body">
                            <table class="table text-center table table-striped ">
                                <thead class="bg-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>No BKK/Petycash</th>
                                        <th>Pengajuan</th>
                                        <th>Kode Pengajuan</th>
                                        <th>Keterangan</th>
                                        <th>Nilai Barang</th>
                                        <th>Nilai Jasa</th>
                                        <th>Nominal</th>
                                        <th>Pembayaran</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // nampilin data relisasi sementara
                                    $queryRealKas = mysqli_query($koneksi, "SELECT id AS id, no_bkk AS kd_transaksi, IFNULL(pengajuan, '-') AS pengajuan, IFNULL(id_kdtransaksi, '-') AS id_kd, keterangan AS keterangan, nilai_barang, nilai_jasa, nominal AS nominal, release_on_bkk AS tanggal,
                                                                                IF(pengajuan = 'PO', (SELECT po_number FROM po WHERE id_po = id_kd), id_kdtransaksi) AS id_kdtransaksi, 'Belawan' AS pembayaran, 'success' AS warna
                                                                            FROM bkk_final
                                                                            WHERE id_anggaran = '$id_anggaran'
                                                                            AND (status_bkk = '4' OR no_bkk IS NOT NULL)
                                                                            
                                                                            UNION ALL

                                                                            -- gs belawan yg dibayarin dijakarta (dibuat dijakarta)
                                                                            SELECT id AS id, no_bkk AS kd_transaksi, IFNULL(pengajuan, '-') AS pengajuan, IFNULL(id_kdtransaksi, '-') AS id_kd, keterangan AS keterangan, nilai_barang, nilai_jasa, nominal AS nominal, release_on_bkk AS tanggal,
                                                                                IF(pengajuan = 'PO', (SELECT po_number FROM po WHERE id_po = id_kd), id_kdtransaksi) AS id_kdtransaksi, 'Jakarta' AS pembayaran, 'primary' AS warna
                                                                            FROM gs.bkk_final
                                                                            WHERE id_anggaran = '$id_anggaran'
                                                                            AND (status_bkk = '4' OR no_bkk IS NOT NULL)
                                                                            AND id_area = '2'

                                                                            UNION ALL
                                                                            
                                                                            -- gs belawan yg dibayarin dijakarta (dibuat dibelawan)
                                                                            SELECT id AS id, no_bkk AS kd_transaksi, IFNULL(pengajuan, '-') AS pengajuan, IFNULL(id_kdtransaksi, '-') AS id_kd, keterangan AS keterangan, nilai_barang, nilai_jasa, nominal AS nominal, release_on_bkk AS tanggal,
                                                                                IF(pengajuan = 'PO', (SELECT po_number FROM po WHERE id_po = id_kd), id_kdtransaksi) AS id_kdtransaksi, 'Jakarta' AS pembayaran, 'primary' AS warna
                                                                            FROM bkk_ke_pusat
                                                                            WHERE id_anggaran = '$id_anggaran'
                                                                            AND (status_bkk = '4' OR no_bkk IS NOT NULL)
                                                                            AND id_kdtransaksi NOT IN (SELECT id_kdtransaksi FROM gs.bkk_final
                                                                                                        WHERE id_anggaran = '$id_anggaran'
                                                                                                        AND (status_bkk = '4' OR no_bkk IS NOT NULL)
                                                                                                        AND id_area = '2')
                                                                            AND pengajuan NOT IN (SELECT pengajuan FROM gs.bkk_final
                                                                                                    WHERE id_anggaran = '$id_anggaran'
                                                                                                    AND (status_bkk = '4' OR no_bkk IS NOT NULL)
                                                                                                    AND id_area = '2')

                                                                            UNION ALL

                                                                            SELECT id_pettycash AS id, kd_pettycash AS kd_transaksi, '-' AS pengajuan, '-' AS id_kdtransaksi, keterangan_pettycash AS keterangan, '0' AS nilai_barang, '0' AS nilai_jasa, total_pettycash AS nominal, pym_ksr AS tanggal, '-' AS id_kd, 'Belawan' AS pembayaran, 'success' AS warna
                                                                            FROM transaksi_pettycash
                                                                            WHERE id_anggaran = '$id_anggaran'
                                                                            AND status_pettycash = '5'
                                                                ");

                                    $totalBarangKas = 0;
                                    $totalJasaKas = 0;
                                    $totalNominalKas = 0;
                                    $noRealKas = 1;

                                    while ($dataRealKas = mysqli_fetch_assoc($queryRealKas)) { ?>
                                        <tr>
                                            <td><?= $noRealKas; ?></td>
                                            <td><?= $dataRealKas['kd_transaksi']; ?></td>
                                            <td><?= $dataRealKas['pengajuan']; ?></td>
                                            <td><?= $dataRealKas['id_kdtransaksi']; ?></td>
                                            <td><?= $dataRealKas['keterangan']; ?></td>
                                            <td><?= formatRupiah2(round($dataRealKas['nilai_barang'])); ?></td>
                                            <td><?= formatRupiah2(round($dataRealKas['nilai_jasa'])); ?></td>
                                            <td><?= formatRupiah2(round($dataRealKas['nominal'])); ?></td>
                                            <th><span class="label label-<?= $dataRealKas['warna']; ?>"><?= $dataRealKas['pembayaran']; ?></span></th>
                                            <td><?= $dataRealKas['tanggal']; ?></td>
                                        </tr>
                                    <?php
                                        $totalBarangKas += $dataRealKas['nilai_barang'];
                                        $totalJasaKas += $dataRealKas['nilai_jasa'];
                                        $totalNominalKas += $dataRealKas['nominal'];
                                        $noRealKas++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </li>
                </ul>
                <div class="panel-footer">Total Nilai Barang <b><?= formatRupiah2($totalBarangKas); ?></b> <br>
                    Total Nilai Jasa <b><?= formatRupiah2($totalJasaKas); ?></b> <br>
                    Total Nominal <b><?= formatRupiah2($totalNominalKas); ?></b></div>
                <!-- </div> -->
            </div>
        </div>

    <?php $no++;
    } ?>
</div>