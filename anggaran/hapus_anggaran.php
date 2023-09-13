<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['id'])) {
	$id = dekripRambo($_GET['id']);

	// BUAT NGAMBIL DATA USER
	$rowUser = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id_area, nama FROM en_fin.user WHERE username  = '$_SESSION[username_en]'"));
	$Area = $rowUser['id_area'];
	$nama = $rowUser['nama'];

	// ngambil data anggaran sebelum dirubah, untuk dimasukin ke log
	$dataAgg = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_anggaran = '$id'"));

	$keterangan = "Divisi              : " . $dataAgg['id_divisi'] . "
Program Kerja       : " . $dataAgg['programkerja_id'] . "
Sub Header          : " . $dataAgg['subheader_id'] . "
Tahun               : " . $dataAgg['tahun'] . "
Segmen              : " . $dataAgg['id_segmen'] . "
No COA              : " . $dataAgg['no_coa'] . "
Nama COA            : " . $dataAgg['nm_coa'] . "
Tipe Anggaran       : " . $dataAgg['tipe_anggaran'] . "
Jenis Anggaran      : " . $dataAgg['jenis_anggaran'] . "
Deskripsi           : " . $dataAgg['nm_item'] . "
Kode Anggaran       : " . $dataAgg['kd_anggaran'] . "
Perdin              : " . $dataAgg['spj'] . "
Unlock              : " . $dataAgg['unlock'] . "
Januari Nominal     : " . $dataAgg['januari_nominal'] . "
Februari Nominal    : " . $dataAgg['februari_nominal'] . "
Maret Nominal       : " . $dataAgg['maret_nominal'] . "
April Nominal       : " . $dataAgg['april_nominal'] . "
Mei Nominal         : " . $dataAgg['mei_nominal'] . "
Juni Nominal        : " . $dataAgg['juni_nominal'] . "
Juli Nominal        : " . $dataAgg['juli_nominal'] . "
Agustus Nominal     : " . $dataAgg['agustus_nominal'] . "
September Nominal   : " . $dataAgg['september_nominal'] . "
Oktober Nominal     : " . $dataAgg['oktober_nominal'] . "
November Nominal    : " . $dataAgg['november_nominal'] . "
Desember Nominal    : " . $dataAgg['desember_nominal'] . "
Jumlah Nominal      : " . $dataAgg['jumlah_nominal'] . "";
	// echo $keterangan;
	// die;
	$updateLog = mysqli_query($koneksi, "INSERT INTO log_anggaran (id_anggaran, aksi, keterangan, dirubah_oleh, waktu_dirubah) VALUES
                                                                    ('$id', 'HAPUS ANGGARAN', '$keterangan', '$nama', NOW())
                        ");

	// DELETE ANGGARANNYA
	$query = mysqli_query($koneksi, "DELETE FROM anggaran WHERE id_anggaran = '$id'");

	if ($query) {
		header("Location: index.php?p=anggaran&sp=budget&tahun=" . $_GET['thn'] . "&divisi=" . $_GET['dvs'] . "");
	} else {
		echo 'gagal cuii : ' . mysqli_error($koneksi);
		die;
	}
}
