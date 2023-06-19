
<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
    $id_divisi = $_POST['id_divisi'];
    $id_anggaran = $_POST['id_anggaran'] == "" ? $_POST['id_anggaran_spj'] : $_POST['id_anggaran'];
    $totalPengajuan = penghilangTitik($_POST['nominal']);
    $keterangan = $_POST['keterangan'];

    // if ($ekstensi != 'pdf') {
    //     setcookie('pesan', 'File yang anda upload bukan berbentuk pdf , silahkan upload ulang dengan extensi pdf !', time() + (3), '/');
    //     setcookie('warna', 'alert-danger', time() + (3), '/');
    // 5000000
    //     header("location:index.php?p=buat_kasbon");
    // } 

    if ($totalPengajuan > 5000000) {
        setcookie('pesan', 'Nominal kasbon anda melebihi jumlah maximal sebesar Rp.5.000.000 !', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');

        header("location:index.php?p=buat_kasbon");
    } else {

        $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
        $rowUser = mysqli_fetch_assoc($queryUser);
        $nama = $rowUser['nama'];
        $id_manager = $rowUser['id_manager'];

        date_default_timezone_set('Asia/Jakarta');
        $tanggal = date("Y-m-d H:i:s");

        $queryHight = mysqli_query($koneksi, "SELECT MAX(id_kasbon) from kasbon ");

        $id_joborder = mysqli_fetch_array($queryHight);


        if ($id_joborder) {

            $nilaikode = substr($id_joborder[0], 2);
            $kode = (int) $nilaikode;

            //setiap kode ditambah 1
            $kode = $kode + 1;
            $kode_otomatis = "KS" . str_pad($kode, 4, "0", STR_PAD_LEFT);
        } else {
            $kode_otomatis = "KS0001";
        }

        //baca lokasi file sementara dan nama file dari form (doc_ptw)		
        $lokasi_doc = ($_FILES['doc']['tmp_name']);
        $doc = ($_FILES['doc']['name']);
        $ekstensi = pathinfo($doc, PATHINFO_EXTENSION);

        // Jika file yang di upload bukan pdf
        if ($ekstensi != 'pdf') {
            setcookie('pesan', 'File yang anda upload bukan berbentuk pdf , silahkan upload ulang dengan extensi pdf !', time() + (3), '/');
            setcookie('warna', 'alert-danger', time() + (3), '/');

            header("location:index.php?p=buat_kasbon");
        } else {

            $namabaru = $kode_otomatis . "-doc-pendukung." . $ekstensi;
            move_uploaded_file($lokasi_doc, "../file/doc_pendukung/" . $namabaru);


            //query detail biaya ops
            $queryDBO = "INSERT detail_biayaops ( id_divisi , id_anggaran, keterangan, is_for) VALUES
                                           ('$id_divisi',  '$id_anggaran', '$keterangan', 'ku');
                                ";
            $iDBO = mysqli_query($koneksi, $queryDBO);

            $maxDbo = mysqli_query($koneksi, "SELECT MAX(id) AS id from detail_biayaops ");

            $id_dbo = mysqli_fetch_assoc($maxDbo);
            $id_dbo = $id_dbo['id'];

            //query kasbon
            $queryKasbon = "INSERT kasbon ( id_kasbon , id_dbo, nilai_barang, harga_akhir, tgl_kasbon, id_manager, from_user, doc_pendukung,  status_kasbon) VALUES
                                    ('$kode_otomatis', '$id_dbo', '$totalPengajuan', '$totalPengajuan','$tanggal', '$id_manager', 1, '$namabaru', 0 );
                                    ";
            $hasil = mysqli_query($koneksi, $queryKasbon);

            if ($queryKasbon && $iDBO) {
                setcookie('pesan', 'Kasbon Berhasil di buat!', time() + (3), '/');
                setcookie('warna', 'alert-success', time() + (3), '/');

                header("location:index.php?p=buat_kasbon");
            } else {
                echo "ada yang salah" . mysqli_error($koneksi);
            }
        }
    }



    //sdf
}
