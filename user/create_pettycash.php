<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {

    $id_anggaran = $_POST['id_anggaran'];
    $keterangan_pettycash = $_POST['keterangan'];
    $total_pettycash = str_replace(".", "", $_POST['nominal']);



    // KODE OTOMATIS
    $query = mysqli_query($koneksi, "SELECT MAX(kd_pettycash) FROM transaksi_pettycash ");

    $id_joborder = mysqli_fetch_array($query);
    if ($id_joborder) {

        $nilaikode = substr($id_joborder[0], 2);
        $kode = (int) $nilaikode;

        //setiap kode ditambah 1
        $kode = $kode + 1;
        $kode_otomatis = "P" . str_pad($kode, 6, "0", STR_PAD_LEFT);
    } else {
        $kode_otomatis = "P000001";
    }

    // upload document
    $lokasi_doc_lpj = ($_FILES['doc_lpj']['tmp_name']);
    $doc_lpj = ($_FILES['doc_lpj']['name']);
    $ekstensi = pathinfo($doc_lpj, PATHINFO_EXTENSION);

    // Jika file yang di upload bukan pdf
    if ($ekstensi != 'pdf') {
        setcookie('pesan', 'File yang anda upload bukan berbentuk pdf , silahkan upload ulang dengan extensi pdf !', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');

        header("location:index.php?p=buat_petty");
    } elseif ($total_pettycash > 100000) {      // jika diatas 100rb gabisa
        setcookie('pesan', 'Nominal Pettycash harus lebih kecil dari Rp. 100.000 !', time() + (3), '/');
        setcookie('warna', 'alert-warning', time() + (3), '/');

        header("location:index.php?p=buat_petty");
    } else {


        $nama_doc = $kode_otomatis . "-lpj-pettycash." . $ekstensi;
        move_uploaded_file($lokasi_doc_lpj, "../file/doc_lpj/" . $nama_doc);

        $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
        $rowUser = mysqli_fetch_assoc($queryUser);
        $id_user = $rowUser['id_user'];
        $nama = $rowUser['nama'];
        $id_divisi = $rowUser['id_divisi'];
        $id_manager = $rowUser['id_manager'];

        date_default_timezone_set('Asia/Jakarta');
        $tanggal = date("Y-m-d H:i:s");


        // LOG
        $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
                                        ('$tanggal', '$nama', 'Membuat Pengajuan Petty Cash ');

                                        ";
        mysqli_query($koneksi, $queryLog);



        $query = "INSERT INTO transaksi_pettycash (kd_pettycash, id_anggaran, keterangan_pettycash, total_pettycash, doc_lpj_pettycash, id_divisi, id_manager, status_pettycash,  created_pettycash_on, created_pettycash_by, `from`) VALUES 
										( '$kode_otomatis', '$id_anggaran', '$keterangan_pettycash', '$total_pettycash', '$nama_doc' ,'$id_divisi', '$id_manager', '0','$tanggal','$nama', 'user');
			";

        // move_uploaded_file($tmp,"file/pjsm/$Doc_pjsm");
        $hasil = mysqli_query($koneksi, $query);
        if ($hasil) {
            setcookie('pesan', 'Pettycash Berhasil di buat!', time() + (3), '/');
            setcookie('warna', 'alert-success', time() + (3), '/');

            header("location:index.php?p=buat_petty");
        } else {
            die("ada kesalahan : " . mysqli_error($koneksi));
        }
    }
}

?>
<!-- pindah -->
<!--  -->