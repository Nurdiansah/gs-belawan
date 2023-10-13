<div class="panel-group" id="accordion">
    <?php while ($dataAnggaran = mysqli_fetch_assoc($queryAnggaran)) {
        $id_anggaran = $dataAnggaran['id_anggaran'];

        // buat ngambil data total realisasi smeentara
        $dataTotalAgg = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id_rs) as jumlah FROM realisasi_sementara WHERE id_anggaran = '$id_anggaran' AND is_deleted = '0'"));
    ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <button type="button" class="btn btn-primary btn-xs " data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $no; ?>" style="cursor:pointer;"><i class="fa fa-plus "></i> </button>
                    <b><?= $dataAnggaran['nm_item'] . " ["  . $dataAnggaran['kd_anggaran']; ?>]</b>

                    <span class="label label-default pull-right"><?= $dataTotalAgg['jumlah']; ?></span>
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
                                        <th>Pengajuan</th>
                                        <th>Kode Pengajuan</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // nampilin data relisasi sementara
                                    $queryPraNota = mysqli_query($koneksi, "SELECT id_rs, id_anggaran, id_kdtransaksi AS id_kd,
                                                                                IF(pengajuan = 'PO', 'PO',
                                                                                    IF(pengajuan = 'KBN', 'Kasbon',
                                                                                        IF(pengajuan = 'BUM', 'Biaya Umum',
                                                                                            IF(pengajuan = 'PCS', 'Pettycash',
                                                                                                IF(pengajuan = 'BKS', 'Biaya Khusus', '')
                                                                                            )
                                                                                        )
                                                                                    )
                                                                                ) AS pengajuan,
                                                                                    
                                                                                IF(pengajuan = 'PO', (SELECT po_number FROM po WHERE id_po = id_kd),
                                                                                    IF(pengajuan = 'KBN', (SELECT id_kasbon FROM kasbon WHERE id_kasbon = id_kd),
                                                                                        IF(pengajuan = 'BUM', (SELECT kd_transaksi FROM bkk WHERE id_bkk = id_kd),
                                                                                            IF(pengajuan = 'PCS', (SELECT kd_pettycash FROM transaksi_pettycash WHERE id_pettycash = id_kd),
                                                                                                IF(pengajuan = 'BKS', (SELECT CONCAT('BK', id) FROM bkk_final WHERE id = id_kd), ''
                                                                                                )
                                                                                            )
                                                                                        )
                                                                                    )
                                                                                ) AS kd_pengajuan,
                                                                                nominal, is_deleted, created_at
                                                                            FROM realisasi_sementara
                                                                            WHERE id_anggaran = '$id_anggaran'
                                                                            AND is_deleted = '0'
                                                                            ORDER BY created_by ASC");

                                    $totalPraNota = 0;
                                    $noPraNota = 1;

                                    while ($dataPraNota = mysqli_fetch_assoc($queryPraNota)) { ?>
                                        <tr>
                                            <td><?= $noPraNota; ?></td>
                                            <td><?= $dataPraNota['pengajuan']; ?></td>
                                            <td><?= is_null($dataPraNota['kd_pengajuan']) ? "?" : $dataPraNota['kd_pengajuan']; ?></td>
                                            <td><?= formatRupiah2($dataPraNota['nominal']); ?></td>
                                            <td><?= is_null($dataPraNota['kd_pengajuan']) ? "<span class='label label-danger'>Silahkan Tanyakan IT</scan>" : "<span class='label label-default'>Active Pranota</span>"; ?></td>
                                            <td><?= $dataPraNota['created_at']; ?></td>
                                        </tr>
                                    <?php
                                        $totalPraNota += $dataPraNota['nominal'];
                                        $noPraNota++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </li>
                </ul>
                <div class="panel-footer">Total Nominal <b><?= formatRupiah2($totalPraNota); ?></b></div>
                <!-- </div> -->
            </div>
        </div>

    <?php $no++;
    } ?>
</div>