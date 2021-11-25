
<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";
date_default_timezone_set('Asia/Jakarta');

if (isset($_POST['payment'])) {
    $id = $_POST['id'];
    $tanggal = $_POST['tanggal'] . " " . date("H:i:s");
    $regulasi_tempo = $_POST['regulasi_tempo'];

    $path = $_FILES['bukti_pembayaran']['tmp_name'];
    $bukti_pembayaran = $_FILES['bukti_pembayaran']['name'];
    $ekstensi = pathinfo($bukti_pembayaran, PATHINFO_EXTENSION);
    $nm_baru = time() . "-bukti-pembayaran-outstanding-cek." . $ekstensi;

    mysqli_begin_transaction($koneksi);

    if ($regulasi_tempo == '0') {
        $status = '18';
    } else {
        $status = '4';
    }

    $bulan    = date('n', strtotime($tanggal));
    $bulan    = date('n');

    $nomorBkk = getNomorBkk($bulan);

    $nomorAkhir = substr($nomorBkk, 0, 3);

    // buat ngambil data anggaran dll
    $queryBKK = mysqli_query($koneksi, "SELECT * FROM bkk_final bf
                                                 LEFT JOIN tagihan_po tp
                                                 ON bf.id_tagihan = tp.id_tagihan
                                                 WHERE bf.id = '$id'");
    $dataBKK = mysqli_fetch_assoc($queryBKK);
    $id_anggaran = $dataBKK['id_anggaran'];
    $pengajuan = $dataBKK['pengajuan'];


    $DPP = $dataBKK['nilai_barang'] + $dataBKK['nilai_jasa'];

    //query realisasi anggaran
    $fieldRealisasi = fieldRealisasi($bulan);
    $queryRealisasi = mysqli_query($koneksi, "SELECT $fieldRealisasi as bulan , jumlah_realisasi, realisasi_kuantitas  from anggaran WHERE id_anggaran = '$id_anggaran' ");
    $dataRealisasi = mysqli_fetch_assoc($queryRealisasi);
    $bulan_realisasi = $dataRealisasi['bulan'] + $DPP;
    $jumlah_realisasi = $dataRealisasi['jumlah_realisasi'] + $DPP;

    $updateRealisasi = mysqli_query($koneksi, "UPDATE anggaran SET $fieldRealisasi = '$bulan_realisasi', jumlah_realisasi = $jumlah_realisasi
												WHERE id_anggaran = '$id_anggaran' ");


    if ($pengajuan == 'PO') {
        # code...
        $id_po = $dataBKK['id_kdtransaksi'];
        $id_tagihan = $dataBKK['id_tagihan'];
        $persentase = $dataBKK['persentase'];

        if ($persentase == 100) {
            // status 10 untuk transaksi sesesai
            $status_po = '10';
        } else {
            $qtp = mysqli_query($koneksi, "SELECT SUM(persentase) AS jumlah FROM tagihan_po
            WHERE po_id = '$id_po'");
            $dtp = mysqli_fetch_assoc($qtp);
            $jumlahPTP = $dtp['jumlah'];

            if ($jumlahPTP == 100) {
                # code...
                $status_po = '10';
            } else {
                # code...
                $status_po = '9';
            }

            // status 9 untuk outstanding po
        }

        // print_r($nomorBkk);
        // die;
        // query update po
        $updatePo = mysqli_query($koneksi, "UPDATE po SET status_po = '$status_po'
                                            WHERE id_po = '$id_po'");

        // query update tagihan
        $updateTagihan = mysqli_query($koneksi, "UPDATE tagihan_po SET status_tagihan = '5'
                                            WHERE id_tagihan = '$id_tagihan'");
    } else {
        // query update po
        $updatePo = "Berhasil";
        // query update tagihan
        $updateTagihan = "Berhasil";
    }

    $queryUpdate = mysqli_query($koneksi, "UPDATE bkk_final SET release_on_bkk = '$tanggal',
                                                bukti_pembayaran = '$nm_baru',
                                                nomor = '$nomorAkhir',
                                                no_bkk = '$nomorBkk',
                                                status_bkk = '$status'
                                            WHERE id = '$id'");

    if ($queryUpdate && $updateRealisasi && $updatePo && $updateTagihan) {
        move_uploaded_file($path, "../file/bukti_pembayaran/" . $nm_baru);

        mysqli_commit($koneksi);

        setcookie('pesan', 'BKK berhasil dipayment!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        mysqli_rollback($koneksi);

        setcookie('pesan', 'BKK gagal dipayment!<br>Error cui ' . mysqli_error($koneksi), time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }

    header("location: index.php?p=outstanding_cek");

    // untuk form submit
} else if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $id_tagihan = $_POST['id_tagihan'];

    $bulan    = date('n');
    $tanggal = dateNow();
    //baca lokasi file sementara dan nama file dari form (doc_ptw)		
    $lokasi_doc = ($_FILES['doc_faktur']['tmp_name']);
    $doc = ($_FILES['doc_faktur']['name']);
    $ekstensi = pathinfo($doc, PATHINFO_EXTENSION);

    // Jika file yang di upload bukan pdf
    if ($ekstensi != 'pdf') {

        setcookie('pesan', 'File yang anda upload bukan berbentuk pdf , silahkan upload ulang dengan extensi pdf !', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');

        header("location: index.php?p=outstanding_cek");
    } else {

        $namadoc = md5($id_tagihan) . "faktur." . $ekstensi;

        // BEGIN/START TRANSACTION        
        mysqli_begin_transaction($koneksi);

        $updateTagihan = mysqli_query($koneksi, "UPDATE tagihan_po SET status_tagihan = '5', doc_faktur = '$namadoc'
										WHERE id_tagihan ='$id_tagihan' ");


        # jika semua query berhasil di jalankan
        $queryUpdate = mysqli_query($koneksi, "UPDATE bkk_final SET release_on_bkk = '$tanggal',                                               
                                                status_bkk = '4'
                                                WHERE id = '$id'");


        if ($queryUpdate && $updateTagihan) {
            move_uploaded_file($lokasi_doc, "../file/invoice/" . $namadoc);

            mysqli_commit($koneksi);

            setcookie('pesan', 'Invoice berhasil di submit!', time() + (3), '/');
            setcookie('warna', 'alert-success', time() + (3), '/');
        } else {

            mysqli_rollback($koneksi);

            setcookie('pesan', 'Invoice gagal di submit! ' . mysqli_error($koneksi), time() + (3), '/');
            setcookie('warna', 'alert-danger', time() + (3), '/');
        }

        header("location: index.php?p=outstanding_cek");
    }
}
