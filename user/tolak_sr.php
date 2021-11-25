<?php

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];


    if ($_GET['aksi'] == 'edit') {
        header("location:?p=edit_sr&id=$id&pg=" . enkripRambo("ditolak_kasbon&sp=tolak_sr") . "");
    } else if ($_GET['aksi'] == 'release') {
        header("location:rls_sr.php?id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:del_sr.php?id=$id");
    }
}

?>

<div class="table-responsive">
    <table class="table text-center table table-striped table-hover" id="">
        <thead>
            <tr style="background-color :#B0C4DE;">
                <th>No</th>
                <th>ID Kasbon</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Keterangan</th>
                <th>Kode Anggaran</th>
                <th>Alasan Ditolak</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($dataKSR = mysqli_fetch_assoc($querySR)) { ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= $dataKSR['id_kasbon']; ?></td>
                    <td><?= $dataKSR['tgl_kasbon']; ?></td>
                    <td><?= $dataKSR['nm_barang']; ?></td>
                    <td><?= $dataKSR['keterangan']; ?></td>
                    <td> <?= $dataKSR['kd_anggaran'] . " - " . $dataKSR['nm_item']; ?> </td>
                    <td> <?= $dataKSR['k_komentar']; ?> </td>
                    <td> <?= formatRupiah($dataKSR['harga_akhir']); ?> </td>
                    <td>
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#releaseSr" title='Ajukan Kembali'><i class="fa fa-undo"></i> Ajukan Kembali</button>
                        <a href="?p=ditolak_kasbon&sp=tolak_sr&aksi=edit&id=<?= enkripRambo($dataKSR['id_sr']); ?>"><span data-placement='top' data-toggle='tooltip' title='Edit'><button class="btn btn-success"> <i class="fa fa-edit"></i> Edit</button></span></a>
                        <!-- <a href="?p=ditolak_kasbon&sp=tolak_sr&aksi=hapus&id=<?= enkripRambo($dataKSR['id_sr']); ?>" onclick="javascript: return confirm('Anda yakin ingin menghapus ?')"><span data-placement='top' data-toggle='tooltip' title='Hapus'><button class="btn btn-danger"> <i class="fa fa-trash"></i> Hapus</button></span></a> -->
                    </td>
                </tr>

                <!-- Modal release -->
                <div id="releaseSr" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- konten modal-->
                        <div class="modal-content">
                            <!-- heading modal -->
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Konfirmasi</h4>
                            </div>
                            <!-- body modal -->
                            <div class="modal-body">
                                <div class="perhitungan">
                                    <form method="post" name="form" enctype="multipart/form-data" action="submit_kembali_ksr.php" class="form-horizontal">
                                        <div class="box-body">
                                            <input type="hidden" name="id" value="<?= $dataKSR['id_kasbon']; ?>">
                                            <input type="hidden" name="url" id="url" value="ditolak_kasbon&sp=tolak_sr">
                                            <h4>Apakah anda yakin ingin mensubmit kembali service request <b><?= $dataKSR['nm_barang']; ?> ? </b></h4>
                                            <div class=" modal-footer">
                                                <button class="btn btn-primary" type="submit" name="approve">Ya, Saya yakin</button></span></a>
                                                &nbsp;
                                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                            </div>
                                        </div>
                                    </form>
                                    <!-- div perhitungan -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End release -->
            <?php $no++;
            } ?>
        </tbody>
    </table>
</div>