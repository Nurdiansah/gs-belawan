<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['submit'])) {
	$id_kasbon = $_POST['id_kasbon'];
	$harga = $_POST['harga'];
	$nominal_pengembalian = str_replace(".", "", $_POST['nominal_pengembalian']);
	$aksi = $_POST['aksi'];
    $nilai_barang = $_POST['nilai_barang'];
    $nilai_jasa = $_POST['nilai_jasa'];

    if ($aksi == "pengembalian") {
        $hargaAkhir = $harga - $nominal_pengembalian;
        $field = $aksi . " = '" . $nominal_pengembalian . "', ";

        if ($nilai_barang > 0) {
            // Nilai Barang
            $nilai_barang = $nilai_barang - $nominal_pengembalian;
        } else {
            if ($nilai_jasa > 0) {
                // Nilai Jasa
                $nilai_jasa = $nilai_jasa - $nominal_pengembalian;
            }
        }
    } elseif ($aksi == "penambahan") {
        $hargaAkhir = $harga + $nominal_pengembalian;
        $field = $aksi . " = '" . $nominal_pengembalian . "', ";

        if ($nilai_barang > 0) {
            // Nilai Barang
            $nilai_barang = $nilai_barang + $nominal_pengembalian;
        } else {
            if ($nilai_jasa > 0) {
                // Nilai Jasa
                $nilai_jasa = $nilai_jasa + $nominal_pengembalian;
            }
        }
    } else {
        $hargaAkhir = $harga;
        $field = "";
    }

	// echo $hargaAkhir . "<br>" . $field;
	// die;

	$lokasi_doc_lpj = ($_FILES['doc_lpj']['tmp_name']);
	$doc_lpj = ($_FILES['doc_lpj']['name']);
	$ekstensi = pathinfo($doc_lpj, PATHINFO_EXTENSION);

	$nama_doc = $id_kasbon . md5(time()) . "-doc-lpj-kasbon." . $ekstensi;
	move_uploaded_file($lokasi_doc_lpj, "../file/doc_lpj/" . $nama_doc);

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$id_user = $rowUser['id_user'];
	$nama = $rowUser['nama'];

	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Melakukan LPJ Kasbon id : $id_kasbon');

									";
	mysqli_query($koneksi, $queryLog);

	$query = "UPDATE kasbon SET doc_lpj = '$nama_doc', $field
									status_kasbon = '9', waktu_lpj = '$tanggal' , harga_akhir = '$hargaAkhir', komentar = NULL,
									nilai_barang = '$nilai_barang', nilai_jasa = '$nilai_jasa'
                                WHERE id_kasbon ='$id_kasbon' ";

	$hasil = mysqli_query($koneksi, $query);

	if ($hasil) {
		header("location:index.php?p=lpj_kasbon&sp=lpj_kmr");
	} else {
		die("ada kesalahan : " . mysqli_error($koneksi));
	}
}

?>
<!-- pindah -->
<!--  -->