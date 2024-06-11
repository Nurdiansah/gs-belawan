<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/koneksipusat.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
    $id_po = $_POST['id_po'];
    $id_bkk = $_POST['id_bkk'];
    $id_tagihan = $_POST['id_tagihan'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $biaya_lain = $_POST['biaya_lain'];
    $potongan = $_POST['potongan'];
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


    if ($metode_pembayaran == 'Transfer') {
        // bkk ke pusat
        $updateBkkPusat = mysqli_query($koneksiPusat, "UPDATE bkk_final SET status_bkk = '1'
                                                                        -- nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa', potongan = '$potongan',
                                                                        -- nilai_ppn = '$nilai_ppn', nilai_pph = '$nilai_pph', biaya_lain = '$biaya_lain',
                                                                        -- id_pph = '$id_pph', nominal = '$harga', status_bkk = '1'
                                                                    WHERE id_tagihan = '$id_tagihan' AND id_area = '2'
                                                                    ");

        $hasil = mysqli_query($koneksi, "UPDATE bkk_ke_pusat SET status_bkk = '1'
                                                    -- nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa', potongan = '$potongan', 
                                                    -- nilai_ppn = '$nilai_ppn', nilai_pph = '$nilai_pph', biaya_lain = '$biaya_lain',
                                                    -- id_pph = '$id_pph', nominal = '$harga', status_bkk = '1'
                                                WHERE id = '$id_bkk'
                                        ");
    } else {
        $hasil = mysqli_query($koneksi, "UPDATE bkk_final SET status_bkk = '1'
                                                        -- nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa', potongan = '$potongan',
                                                        -- nilai_ppn = '$nilai_ppn', nilai_pph = '$nilai_pph', biaya_lain = '$biaya_lain',
                                                        -- id_pph = '$id_pph', nominal = '$harga', status_bkk = '1'
                                                    WHERE id = '$id_bkk'
                                        ");
    }

    // $updTagihan = mysqli_query($koneksi, "UPDATE tagihan_po SET nominal = '$harga' WHERE bkk_id = '$id_bkk'");

    // $updPO = mysqli_query($koneksi, "UPDATE po SET app_pajak = NOW() WHERE id_po = '$id_po'");


    if ($hasil) {
        // mysql commit transaction
        mysqli_commit($koneksi);

        setcookie('pesan', 'PO berhasil di Submit!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        // mysql rollback transaction
        mysqli_rollback($koneksi);

        setcookie('pesan', 'PO gagal di Submit!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=verifikasi_po");
} else if (isset($_POST['simpan'])) {

    // ada tambahan untuk edit sub, total pembulatan po

    $id_po = $_POST['id_po'];
    $id_bkk = $_POST['id_bkk'];
    $id_tagihan = $_POST['id_tagihan'];

    $metode_pembayaran = $_POST['metode_pembayaran'];

    $nilai_barang = $_POST['nilai_barang'];
    $nilai_jasa = $_POST['nilai_jasa'];
    $nilai_ppn = str_replace(".", "", $_POST['ppn_nilai']);
    $id_pph = $_POST['id_pph'];
    $harga = str_replace(".", "", $_POST['jml']);
    $biaya_lain = $_POST['biaya_lain'];
    $potongan = $_POST['potongan'];

    if ($_POST['pph_nilai2'] == 0) {
        $nilai_pph = penghilangTitik($_POST['pph_nilai']);
    } else {
        $nilai_pph = $_POST['pph_nilai2'];
    }

    // cek user
    $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $nama = $rowUser['nama'];

    $tanggal = dateNow();

    mysqli_begin_transaction($koneksi);

    if ($metode_pembayaran == 'Transfer') {
        // bkk ke pusat
        $updateBkkPusat = mysqli_query($koneksiPusat, "UPDATE bkk_final SET nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa', potongan = '$potongan',
                                                                            nilai_ppn = '$nilai_ppn', nilai_pph = '$nilai_pph', biaya_lain = '$biaya_lain',
                                                                            id_pph = '$id_pph', nominal = '$harga'
                                                                        WHERE id_tagihan = '$id_tagihan' AND id_area = '2'
                                                                        ");

        // bkk 
        $hasil = mysqli_query($koneksi, "UPDATE bkk_ke_pusat SET nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa', biaya_lain = '$biaya_lain',
                                                                        nilai_ppn = '$nilai_ppn', nilai_pph = '$nilai_pph', potongan = '$potongan',
                                                                        id_pph = '$id_pph', nominal = '$harga'
                                                                        WHERE id = '$id_bkk'
                                                                        ");
    } else {
        // bkk 
        $hasil = mysqli_query($koneksi, "UPDATE bkk_final SET nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa' , biaya_lain = '$biaya_lain',
                                                                        nilai_ppn = '$nilai_ppn', nilai_pph = '$nilai_pph', potongan = '$potongan',
                                                                        id_pph = '$id_pph', nominal = '$harga'
                                                                        WHERE id = '$id_bkk'
                                                                        ");
    }



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
    header("location:index.php?p=verifikasi_dpo&id=" . enkripRambo($id_po) . "&bkk=" . enkripRambo($id_bkk) . "&id_tagihan=" . enkripRambo($id_tagihan));
}
?>
<!-- pindah -->
<!--  -->