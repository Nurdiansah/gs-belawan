<?php
include "../fungsi/koneksi.php";
?>

<section class="content">
    <div class="box-header with-border">
        <h3 class="text-center">Cetak Laporan Perdivisi</h3>
    </div>
    <form method="POST" enctype="multipart/form-data" action="cetak-xls.php" class="form-horizontal">
        <div class="box-body">
            <div class="form-group">
                <div class="col-sm-3 col-sm-offset-4">
                    <select name="divisi" required class="form-control col-sm-offset-2">
                        <option value="0">-- Pilih Divisi --</option>
                        <?php
                        $querydivisi = mysqli_query($koneksi, "SELECT * FROM divisi order by nm_divisi asc");
                        if (mysqli_num_rows($querydivisi)) {
                            while ($rowdivisi = mysqli_fetch_assoc($querydivisi)) :
                        ?>
                                <option value="<?= $rowdivisi['id_divisi']; ?>" type="checkbox"><?= $rowdivisi['nm_divisi']; ?></option>
                        <?php endwhile;
                        } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3 col-sm-offset-4">
                    <select name="tahun" required class="form-control col-sm-offset-2">
                        <option value="0">-- Pilih Tahun --</option>
                        <?php
                        $tahunSekarang = date('Y');
                        foreach (range(2019, $tahunSekarang) as $tahun) {
                            echo "<option value=" . $tahun . ">" . $tahun . "</option> ";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-5">
                    <input type="submit" name="cetak" class="btn btn-primary col-sm-offset-1" value="Cetak">
                </div>
            </div>
        </div>
    </form>
</section>