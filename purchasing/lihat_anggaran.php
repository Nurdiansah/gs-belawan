<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'edit') {
        header("#");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=hapus_anggaran&id=$id");
    }
}

// $query = mysqli_query($koneksi, "SELECT * FROM bkk WHERE status_bkk='6' ORDER BY tgl_bkk DESC  ");    
switch ($_GET['act']) {

        // PROSES VIEW DATA LAPORAN trans //      

    case 'view':

        $queryDivisi = mysqli_query($koneksi, "SELECT id_divisi from user WHERE username  = '$_SESSION[username_blw]'");
        $rowDivisi = mysqli_fetch_assoc($queryDivisi);
        $Divisi = $rowDivisi['id_divisi'];

?>
        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->

            <h3> <b>Anggaran Divisi</b></h3>
            <form action="?p=lihat_anggaran&act=cek" method="POST">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="exampleInputEmail1"></label><br>
                        <select name="id_divisi" class="form-control">
                            <?php
                            $queryDivisi = mysqli_query($koneksi, "SELECT * FROM divisi WHERE id_divisi ='$Divisi'");
                            if (mysqli_num_rows($queryDivisi)) {
                                while ($rowDivisi = mysqli_fetch_assoc($queryDivisi)) :
                            ?>
                                    <option value="<?= $rowDivisi['id_divisi']; ?>" type="checkbox"><?= $rowDivisi['nm_divisi']; ?></option>
                            <?php endwhile;
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Mulai Pencarian</label><br>
                        <input type="submit" value="Cek Anggaran" class="btn btn-primary">
                    </div>
                </div>
            </form>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="text-center">Anggaran </h3>
                        </div>
                        <div class="box-body">
                            <div class="row">

                                <br><br>
                            </div>
                            <div class="table-responsive">
                                <table class="table text-center table table-striped table-hover" id=" ">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Coa </th>
                                            <th>Kode Anggaran</th>
                                            <th>Golongan</th>
                                            <th>Sub Golongan</th>
                                            <th>Description</th>
                                            <th>Harga</th>
                                            <th>Quantity</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php
        break;

    case 'cek':

        $queryDivisi = mysqli_query($koneksi, "SELECT id_divisi from user WHERE username  = '$_SESSION[username_blw]'");
        $rowDivisi = mysqli_fetch_assoc($queryDivisi);
        $Divisi = $rowDivisi['id_divisi'];

        $queryAnggaran =  mysqli_query($koneksi, "SELECT * FROM anggaran a
        JOIN tahun t
        ON a.id_tahun = t.id_tahun
        JOIN divisi d
        ON a.id_divisi = d.id_divisi
        JOIN golongan g
        ON a.id_golongan = g.id_golongan
        JOIN sub_golongan sb
        ON a.id_subgolongan = sb.id_subgolongan 
        WHERE a.id_divisi = '$_POST[id_divisi]' ");


        if (mysqli_num_rows($queryAnggaran)) ?>
        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->

            <h3> <b>Anggaran Divisi</b></h3>
            <form action="?p=lihat_anggaran&act=cek" method="POST">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="exampleInputEmail1"></label><br>
                        <select name="id_divisi" class="form-control">
                            <?php
                            $queryDivisi = mysqli_query($koneksi, "SELECT * FROM divisi WHERE id_divisi ='$Divisi'");
                            if (mysqli_num_rows($queryDivisi)) {
                                while ($rowDivisi = mysqli_fetch_assoc($queryDivisi)) :
                            ?>
                                    <option value="<?= $rowDivisi['id_divisi']; ?>" type="checkbox"><?= $rowDivisi['nm_divisi']; ?></option>
                            <?php endwhile;
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Mulai Pencarian</label><br>
                        <input type="submit" value="Cek Anggaran" class="btn btn-primary">
                    </div>
                </div>
            </form>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="text-center">Anggaran </h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <br><br>
                            </div>
                            <div class="table-responsive">
                                <table class="table text-center table table-striped table-hover" id="material">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NO COA</th>
                                            <th>Kode Transaksi</th>
                                            <th>Golongan</th>
                                            <th>Sub Golongan</th>
                                            <th>Description</th>
                                            <th>Harga</th>
                                            <th>Quantity</th>
                                            <th>Nominal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php
                                            $no = 1;
                                            if (mysqli_num_rows($queryAnggaran)) {
                                                while ($row = mysqli_fetch_assoc($queryAnggaran)) :

                                                    $jml_qty = $row['januari_kuantitas'] + $row['februari_kuantitas'] + $row['maret_kuantitas'] + $row['april_kuantitas'] + $row['mei_kuantitas'] + $row['juni_kuantitas']
                                                        + $row['juli_kuantitas'] + $row['agustus_kuantitas'] + $row['september_kuantitas'] + $row['oktober_kuantitas'] + $row['november_kuantitas'] + $row['desember_kuantitas'];
                                                    $jml_nominal = $jml_qty * $row['harga'];

                                                    $hargaFormat = number_format($row['harga'], 0, ",", ".");
                                                    $jmlFormat = number_format($jml_nominal, 0, ",", ".");
                                            ?>
                                                    <td> <?= $no; ?> </td>
                                                    <td> <?= $row['no_coa']; ?> </td>
                                                    <td> <?= $row['kd_anggaran']; ?> </td>
                                                    <td> <?= $row['nm_golongan']; ?> </td>
                                                    <td> <?= $row['nm_subgolongan']; ?> </td>
                                                    <td> <?= $row['nm_item']; ?> </td>
                                                    <td> <?= "Rp." . $hargaFormat; ?> </td>
                                                    <td> <?= $jml_qty; ?> </td>
                                                    <td> <?= "Rp." . $jmlFormat;  ?> </td>
                                                    <td>
                                                        <button type="button" class="btn btn-info col-sm-offset- " data-toggle="modal" data-target="#">Lihat</button>
                                                        <a href="?p=lihat_anggaran&aksi=hapus&id=<?= $row['id_anggaran']; ?>"><span data-placement='top' title='Hapus' onclick=”return confirm(‘Yakin Hapus?’)”><button class="btn btn-warning" onclick=”return confirm(‘Yakin Hapus?’)”>Hapus</button></span></a>
                                                    </td>
                                        </tr>
                                        <!-- Awal Modal -->
                                        <div id="myModal" class="modal fade" role="dialog">
                                            <div class="modal-dialog modal-lg">
                                                <!-- konten modal-->
                                                <div class="modal-content">
                                                    <!-- heading modal -->
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Anggaran</h4>
                                                    </div>
                                                    <!-- body modal -->
                                                    <div class="modal-body">
                                                        <div class="fetched-data"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Akhir Modal -->
                                <?php
                                                    $no++;
                                                endwhile;
                                            } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

<?php
}
?>



<script>
    $(function() {
        $("#material").DataTable({
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
                "sEmptyTable": "Tidak ada data di database"
            }
        });
    });
</script>