<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
    $id_po = $_POST['id_po'];
    $id_bkk = $_POST['id_bkk'];

    $nilai_barang = $_POST['nilai_barang'];
    $nilai_jasa = $_POST['nilai_jasa'];
    $nilai_ppn = str_replace(".", "", $_POST['ppn_nilai']);
    $id_pph = $_POST['id_pph'];
    $harga = str_replace(".", "", $_POST['jml']);
    if ($_POST['pph_nilai2'] == 0) {
        $nilai_pph = penghilangTitik($_POST['pph_nilai']);
    } else {
        $nilai_pph = $_POST['pph_nilai2'];
    }

    $komentar = "Disubmit kembali oleh Pajak";

    mysqli_begin_transaction($koneksi);

    $cekReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_bkk_final WHERE id_bkk_final = '$id_bkk'");
    $dataReapp = mysqli_num_rows($cekReapp);

    if ($dataReapp == 0) {
        $aksi_reapp = "INSERT INTO reapprove_bkk_final (id_bkk_final, alasan_reapprove_pajak, waktu_reapprove_pajak) VALUES
                        ('$id_bkk', '$komentar', NOW())";
    } else {
        $aksi_reapp = "UPDATE reapprove_bkk_final SET alasan_reapprove_pajak = '$komentar',
                                waktu_reapprove_pajak = NOW()
                        WHERE id_bkk_final = '$id_bkk'";
    }

    $reApp = mysqli_query($koneksi, $aksi_reapp);

    $query = "UPDATE bkk_final SET nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa' , 
                                nilai_ppn = '$nilai_ppn', nilai_pph = '$nilai_pph',
                                id_pph = '$id_pph', nominal = '$harga', status_bkk = '1'
                            WHERE id = '$id_bkk'
                ";
    $hasil = mysqli_query($koneksi, $query);

    $updTagihan = mysqli_query($koneksi, "UPDATE tagihan_po SET nominal = '$harga' WHERE bkk_id = '$id_bkk'");

    $updPO = mysqli_query($koneksi, "UPDATE po SET app_pajak = NOW() WHERE id_po = '$id_po'");

    if ($hasil) {
        // mysql commit transaction
        mysqli_commit($koneksi);

        setcookie('pesan', 'PO berhasil di submit!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        // mysql rollback transaction
        mysqli_rollback($koneksi);

        setcookie('pesan', 'PO gagal di submit!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    // header("location:index.php?p=ditolak_po&id=" . enkripRambo($id_po) . "&bkk=" . enkripRambo($id_bkk));
    header('Location: index.php?p=ditolak_po');
} else if (isset($_POST['simpan'])) {

    $id_po = $_POST['id_po'];
    $id_bkk = $_POST['id_bkk'];

    $nilai_barang = $_POST['nilai_barang'];
    $nilai_jasa = $_POST['nilai_jasa'];
    $nilai_ppn = str_replace(".", "", $_POST['ppn_nilai']);
    $id_pph = $_POST['id_pph'];
    $harga = str_replace(".", "", $_POST['jml']);

    if ($_POST['pph_nilai2'] == 0) {
        $nilai_pph = penghilangTitik($_POST['pph_nilai']);
    } else {
        $nilai_pph = $_POST['pph_nilai2'];
    }

    // cek user
    $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $nama = $rowUser['nama'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    mysqli_begin_transaction($koneksi);

    $query = "UPDATE bkk_final SET nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa' , 
                                nilai_ppn = '$nilai_ppn', nilai_pph = '$nilai_pph', 
                                id_pph = '$id_pph', nominal = '$harga'
                            WHERE id = '$id_bkk'
                ";

    $hasil = mysqli_query($koneksi, $query);

    $updTagihan = mysqli_query($koneksi, "UPDATE tagihan_po SET nominal = '$harga' WHERE bkk_id = '$id_bkk'");

    $updPO = mysqli_query($koneksi, "UPDATE po SET app_pajak = NOW() WHERE id_po = '$id_po'");

    if ($hasil) {
        // mysql commit transaction
        mysqli_commit($koneksi);

        setcookie('pesan', 'PO berhasil di simpan!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        // mysql rollback transaction
        mysqli_rollback($koneksi);

        setcookie('pesan', 'PO gagal di simpan!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=dtl_ditolakpo&id=" . enkripRambo($id_po) . "&bkk=" . enkripRambo($id_bkk));
}
