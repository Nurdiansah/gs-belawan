<?php
session_start();
include "../fungsi/koneksi.php";

date_default_timezone_set('Asia/Jakarta');
$tanggal = date("Y-m-d H:i:s");

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$nama = $rowUser['nama'];

if (isset($_POST['simpan'])) {
    $id_divisi = $_POST['id_divisi'];
    $program_kerja  = $_POST['program_kerja'];
    $tahun = $_POST['tahun'];
    $segmen = $_POST['segmen'];
    $no_coa = $_POST['no_coa'];
    $nm_coa = $_POST['nm_coa'];
    $tipe_anggaran = $_POST['tipe_anggaran'];
    $jenis_anggaran = $_POST['jenis_anggaran'];
    $deskripsi = $_POST['deskripsi'];
    $kd_anggaran = $_POST['kd_anggaran'];
    $perdin = $_POST['perdin'] == "1" ? $_POST['perdin'] : "0";
    $unlock = $_POST['unlock'] == "1" ? $_POST['unlock'] : "0";
    $nominal_januari = str_replace(".", "", $_POST['nominal_januari']);
    $nominal_februari = str_replace(".", "", $_POST['nominal_februari']);
    $nominal_maret = str_replace(".", "", $_POST['nominal_maret']);
    $nominal_april = str_replace(".", "", $_POST['nominal_april']);
    $nominal_mei = str_replace(".", "", $_POST['nominal_mei']);
    $nominal_juni = str_replace(".", "", $_POST['nominal_juni']);
    $nominal_juli = str_replace(".", "", $_POST['nominal_juli']);
    $nominal_agustus = str_replace(".", "", $_POST['nominal_agustus']);
    $nominal_september = str_replace(".", "", $_POST['nominal_september']);
    $nominal_oktober = str_replace(".", "", $_POST['nominal_oktober']);
    $nominal_november = str_replace(".", "", $_POST['nominal_november']);
    $nominal_desember = str_replace(".", "", $_POST['nominal_desember']);
    $nominal_jumlah = str_replace(".", "", $_POST['nominal_jumlah']);

    $query = mysqli_query($koneksi, "INSERT INTO anggaran (tahun, programkerja_id, id_divisi, id_segmen, no_coa, nm_coa, kd_anggaran, tipe_anggaran, jenis_anggaran, nm_item, spj, `unlock`,
                                    januari_nominal, februari_nominal, maret_nominal, april_nominal, mei_nominal, juni_nominal, juli_nominal, agustus_nominal, september_nominal,
                                    oktober_nominal, november_nominal, desember_nominal, jumlah_nominal, created_by, created_on, last_modified_by, last_modified_on, row_version) VALUES
									('$tahun', '$program_kerja', '$id_divisi', '$segmen', '$no_coa', '$nm_coa', '$kd_anggaran', '$tipe_anggaran', '$jenis_anggaran', '$deskripsi', '$perdin', '$unlock',
                                    '$nominal_januari', '$nominal_februari', '$nominal_maret', '$nominal_april', '$nominal_mei', '$nominal_juni', '$nominal_juli', '$nominal_agustus', '$nominal_september',
                                    '$nominal_oktober', '$nominal_november', '$nominal_desember', '$nominal_jumlah', '$nama', NOW(), '$nama', NOW(), '1')
			                ");

    if ($query) {
        header("location:index.php?p=anggaran");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!-- pindah -->
<!--  -->