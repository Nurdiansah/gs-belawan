<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}

$id = $_GET['id'];

$queryUser =  mysqli_query($koneksi, "SELECT *
                                                     from user u
                                                     JOIN divisi d
                                                     ON u.id_divisi=d.id_divisi
                                                     WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];
$Divisi = $rowUser['nm_divisi'];

$queryBo =  mysqli_query($koneksi, "SELECT * FROM biaya_ops bo
                                                     JOIN detail_biayaops dbo
                                                     ON dbo.kd_transaksi = bo.kd_transaksi
                                                     JOIN anggaran a
                                                     ON a.id_anggaran = dbo.id_anggaran
                                                     WHERE bo.kd_transaksi='$id' ");
// $data=mysqli_fetch_assoc($queryBo);

$query =  mysqli_query($koneksi, "SELECT * FROM biaya_ops bo
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi 
                                            WHERE kd_transaksi='$id' ");
$data2 = mysqli_fetch_assoc($query);

$kd_transaksi = $data2['kd_transaksi'];

$tanggalCargo = date("Y-m-d");

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    global $kd_transaksi;
    echo $kd_transaksi;


    if ($_GET['aksi'] == 'edit') {
        header("location:?p=edit_item_tolak&id=$id");
    } else if ($_GET['aksi'] == 'lihat') {
        header("location:?p=detail_item&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        $query = mysqli_query($koneksi, "DELETE FROM detail_biayaops WHERE id=$id");
        mysqli_query($koneksi, $query);
        header("location:?p=detail_tolakmr&id=$kd_transaksi");
    } else if ($_GET['aksi'] == 'revisi') {
        header("location:?p=revisi_mr&id=$id");
    }
}

?>

<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <div class="col-md-2">
                        <a href="index.php?p=tolak_mr" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                    </div>
                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Material Request</h3>
                </div>
                <form method="post" name="form" action="#" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset- col-sm-2 control-label">Kode Transaksi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="divisi" value="<?= $data2['kd_transaksi']; ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset- col-sm-2 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['tgl_pengajuan']; ?>">
                            </div>
                        </div>
                        <!-- <div class="form-group">                                                                                               
                                <label for="tgl_pengajuan" class="col-sm-offset- col-sm-9 control-label">Kode Transaksi</label>
                                    <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid"  name="tgl_pengajuan" value="<?= $data2['kd_transaksi']; ?>">                                
                                    </div>                                                       
                        </div>  -->
                        <div class="mb-2">
                            <label for="validationTextarea">Komentar :</label>
                            <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" disabled><?= $data2['komentar']; ?></textarea>
                        </div>
                        <br>
                    </div>
                </form>

                <!--  -->
                <div class="table-responsive datatab">
                    <table class="table text-center table table-striped table-dark table-hover ">
                        <thead style="background-color :#B0C4DE;">
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Kode Anggaran</th>
                            <th>Merk</th>
                            <th>Type</th>
                            <th>Spesifikasi</th>
                            <th>Satuan</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </thead>
                        <tr>
                            <tbody>
                                <tr>
                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($queryBo)) {
                                        while ($row = mysqli_fetch_assoc($queryBo)) :
                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['nm_barang']; ?> </td>
                                            <td> <?= $row['kd_anggaran']; ?> </td>
                                            <td> <?= $row['merk']; ?> </td>
                                            <td> <?= $row['type']; ?> </td>
                                            <td> <?= $row['spesifikasi']; ?> </td>
                                            <td> <?= $row['satuan']; ?> </td>
                                            <td> <?= $row['jumlah']; ?> </td>
                                            <td>
                                                <a href="?p=detail_tolakmr&aksi=lihat&id=<?= $row['id']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info"> <i class="fa fa-search-plus"></i> </button></span></a>
                                                <a href="?p=detail_tolakmr&aksi=edit&id=<?= $row['id']; ?>"><span data-placement='top' data-toggle='tooltip' title='Edit'><button class="btn btn-success"> <i class="fa fa-edit"></i> </button></span></a>
                                                <a href="?p=detail_tolakmr&aksi=hapus&id=<?= $row['id']; ?>" onclick="javascript: return confirm('Anda yakin ingin menghapus ?')"><span data-placement='top' data-toggle='tooltip' title='Hapus'><button class="btn btn-danger"> <i class="fa fa-remove"></i> </button></span></a>
                                            </td>
                                </tr>
                        <?php
                                            $no++;
                                        endwhile;
                                    } ?>
                            </tbody>
                        </tr>
                    </table>
                </div>

                <hr>
                <a href="?p=detail_tolakmr&aksi=revisi&id=<?=$data2['kd_transaksi'];?>"><button class="btn btn-primary col-sm-offset-10 " onclick="javascript: return confirm('Yakin ingin mengajukan kembali ?')">Ajukan Ulang</button></a>
                &nbsp;
                <br>
                <hr>

                <!-- Akhir Modal Tambah  -->

            </div>
        </div>
    </div>
</section>

<script>

</script>