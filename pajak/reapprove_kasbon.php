<?php
include "../fungsi/koneksi.php";

if (isset($_POST['simpan']) || isset($_POST['submit'])) {

    // Deklarasi
    $id_kasbon = $_POST['id_kasbon'];
    $from_user = $_POST['from_user'];
    $vrf_pajak = $_POST['vrf_pajak'];
    $nilai_barang = $_POST['nilai_barang'];
    $nilai_jasa = $_POST['nilai_jasa'];

    $nilai_ppn = penghilangTitik($_POST['nilai_ppn']);

    $id_pph = $_POST['id_pph'];

    if ($_POST['nilai_pph2'] == 0) {
        $nilai_pph = penghilangTitik($_POST['nilai_pph']);
    } else {
        $nilai_pph = $_POST['nilai_pph2'];
    }


    $biaya_lain = $_POST['biaya_lain'];
    $potongan = $_POST['potongan'];
    $pembulatan = $_POST['pembulatan'];

    $harga = penghilangTitik($_POST['harga_akhir']);

    $tanggal = dateNow();

    // Simpan data
    if (isset($_POST['simpan'])) {
        // Simpan

        // BEGIN/START TRANSACTION        
        mysqli_begin_transaction($koneksi);

        $update = mysqli_query($koneksi, "UPDATE kasbon SET nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa' , 
                nilai_ppn = '$nilai_ppn', nilai_pph = '$nilai_pph', 
                id_pph = '$id_pph', biaya_lain = '$biaya_lain', potongan = '$potongan', 
                harga_akhir = '$harga', app_pajak = '$tanggal'                                              
                WHERE id_kasbon ='$id_kasbon' ");

        if ($update) {
            # jika semua query berhasil di jalankan
            mysqli_commit($koneksi);

            setcookie('pesan', 'Kasbon berhasil di Simpan!', time() + (3), '/');
            setcookie('warna', 'alert-success', time() + (3), '/');
        } else {
            #jika ada query yang gagal
            mysqli_rollback($koneksi);
            echo mysqli_error($koneksi);
            die;
            setcookie('pesan', 'Kasbon gagal di Simpan!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
            setcookie('warna', 'alert-danger', time() + (3), '/');
        }
        header("location:index.php?p=verifikasi_kasbon&sp=vk_user");
    }

    // Submit atau release
    if (isset($_POST['submit'])) {
        // Submit atau release       
        // cek user
        $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
        $rowUser = mysqli_fetch_assoc($queryUser);
        $nama = $rowUser['nama'];

        // cek jika kasbon dari kasir, maka proses langsung ke direksi
        $queryCekKasbon = mysqli_query($koneksi, "SELECT * FROM kasbon
                                                    JOIN detail_biayaops
                                                        ON id_dbo = id
                                                    WHERE id_kasbon = '$id_kasbon'");
        $dataCekKasbon = mysqli_fetch_assoc($queryCekKasbon);
        // end cek        

        // BEGIN/START TRANSACTION        
        mysqli_begin_transaction($koneksi);

        // kemanager finance
        $status_kasbon = "5";


        #kondisi jika verfikasi pajak sebelum pembayaran            

        $query = "UPDATE kasbon SET nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa' , 
                                        nilai_ppn = '$nilai_ppn', nilai_pph = '$nilai_pph', 
                                        id_pph = '$id_pph', biaya_lain = '$biaya_lain', potongan = '$potongan', 
                                        harga_akhir = '$harga', app_pajak = '$tanggal', status_kasbon = '$status_kasbon'                                       
                                        WHERE id_kasbon ='$id_kasbon' ";

        $hasil = mysqli_query($koneksi, $query);


        if ($hasil) {
            # jika semua query berhasil di jalankan
            mysqli_commit($koneksi);

            setcookie('pesan', 'Kasbon berhasil di Verifikasi!', time() + (3), '/');
            setcookie('warna', 'alert-success', time() + (3), '/');
        } else {
            #jika ada query yang gagal
            mysqli_rollback($koneksi);
            echo mysqli_error($koneksi);
            die;
            setcookie('pesan', 'Kasbon gagal di Verifikasi!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
            setcookie('warna', 'alert-danger', time() + (3), '/');
        }
        header("location:index.php?p=verifikasi_kasbon&sp=vk_user");
    }
}
