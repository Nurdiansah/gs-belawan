<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['simpan'])) {


    $id_divisi = $_POST['id_divisi'];
    $id_tahun = $_POST['id_tahun'];
    $no_coa = $_POST['no_coa'];
    $kd_transaksi = $_POST['kd_transaksi'];
    $id_golongan = $_POST['id_golongan'];
    $id_subgolongan = $_POST['id_subgolongan'];
    $nm_item = $_POST['nm_item'];
    $harga = $_POST['harga'];
    $januari_kuantitas = $_POST['januari_kuantitas'];
    $februari_kuantitas = $_POST['februari_kuantitas'];
    $maret_kuantitas = $_POST['maret_kuantitas'];
    $april_kuantitas = $_POST['april_kuantitas'];
    $mei_kuantitas = $_POST['mei_kuantitas'];
    $juni_kuantitas = $_POST['juni_kuantitas'];
    $juli_kuantitas = $_POST['juli_kuantitas'];
    $agustus_kuantitas = $_POST['agustus_kuantitas'];
    $september_kuantitas = $_POST['september_kuantitas'];
    $oktober_kuantitas = $_POST['oktober_kuantitas'];
    $november_kuantitas = $_POST['november_kuantitas'];
    $desember_kuantitas = $_POST['desember_kuantitas'];
    $nominal_januari = $_POST['nominal_januari'];
    $januari_nominal = str_replace(".", "", $nominal_januari);
    $nominal_februari = $_POST['nominal_februari'];
    $februari_nominal = str_replace(".", "", $nominal_februari);
    $nominal_maret = $_POST['nominal_maret'];
    $maret_nominal = str_replace(".", "", $nominal_maret);
    $nominal_april = $_POST['nominal_april'];
    $april_nominal = str_replace(".", "", $nominal_april);
    $nominal_mei = $_POST['nominal_mei'];
    $mei_nominal = str_replace(".", "", $nominal_mei);
    $nominal_juni = $_POST['nominal_juni'];
    $juni_nominal = str_replace(".", "", $nominal_juni);
    $nominal_juli = $_POST['nominal_juli'];
    $juli_nominal = str_replace(".", "", $nominal_juli);
    $nominal_agustus = $_POST['nominal_agustus'];
    $agustus_nominal = str_replace(".", "", $nominal_agustus);
    $nominal_september = $_POST['nominal_september'];
    $september_nominal = str_replace(".", "", $nominal_september);
    $nominal_oktober = $_POST['nominal_oktober'];
    $oktober_nominal = str_replace(".", "", $nominal_oktober);
    $nominal_november = $_POST['nominal_november'];
    $november_nominal = str_replace(".", "", $nominal_november);
    $nominal_desember = $_POST['nominal_desember'];
    $desember_nominal = str_replace(".", "", $nominal_desember);
    $jumlah_kuantitasa = $_POST['jml_kuantitas'];
    $jumlah_kuantitas = str_replace(".", "", $jumlah_kuantitasa);
    $jumlah_nominala = $_POST['jml_nominal'];
    $jumlah_nominal = str_replace(".", "", $jumlah_nominala);
    $waktuSekarang = $_POST['waktu'];

    $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $nama = $rowUser['nama'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Tambah Anggaran' );

									";
    mysqli_query($koneksi, $queryLog);


    $query = "INSERT INTO anggaran ( id_divisi, id_tahun, no_coa, kd_anggaran, id_golongan, id_subgolongan, 
                                        nm_item, harga, januari_kuantitas, februari_kuantitas, maret_kuantitas, 
                                        april_kuantitas, mei_kuantitas, juni_kuantitas, juli_kuantitas, agustus_kuantitas, 
                                        september_kuantitas, oktober_kuantitas, november_kuantitas, desember_kuantitas, 
                                        januari_nominal,februari_nominal,maret_nominal,april_nominal,mei_nominal,
                                        juni_nominal,juli_nominal,agustus_nominal,september_nominal,oktober_nominal,
                                        november_nominal,desember_nominal,
                                        jumlah_kuantitas, jumlah_nominal,
                                        created_by, created_on, last_modified_by, last_modified_on, row_version) VALUES 
										('$id_divisi', '$id_tahun', '$no_coa', '$kd_transaksi', '$id_golongan', '$id_subgolongan',
                                        '$nm_item','$harga','$januari_kuantitas','$februari_kuantitas','$maret_kuantitas',
                                        '$april_kuantitas','$mei_kuantitas','$juni_kuantitas','$juli_kuantitas','$agustus_kuantitas',
                                        '$september_kuantitas','$oktober_kuantitas','$november_kuantitas','$desember_kuantitas',
                                        '$januari_nominal','$februari_nominal','$maret_nominal','$april_nominal','$mei_nominal',
                                        '$juni_nominal','$juli_nominal','$agustus_nominal','$september_nominal','$oktober_nominal',
                                        '$november_nominal','$desember_nominal',
                                        '$jumlah_kuantitas','$jumlah_nominal',
                                        '$nama','$tanggal','$nama','$tanggal','1');
			";


    $hasil = mysqli_query($koneksi, $query);
    if ($hasil) {
        header("location:index.php?p=tambah_anggaran");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!-- pindah -->
<!--  -->