
<?php
include "../fungsi/koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d");

    $release = mysqli_query($koneksi, "UPDATE bkk SET status_bkk = '1',
                                            tgl_bkk = '$tanggal',
                                            komentar = NULL,
                                            komentar_mgrfin = NULL,
                                            komentar_direktur = NULL
                                        WHERE id_bkk = '$id'");

    if ($release) {
        header('Location: index.php?p=ditolak_biayanonops');
    }
}
