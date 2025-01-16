<?php

include "koneksi.php";
// include "koneksipusat.php";



function verifikasi($data)
{
	global $koneksi;

	$jenispengajuan_id = $data['jenispengajuan_id'];
	$permohonan_id = $data['permohonan_id'];

	$cekVerifikasi = mysqli_query($koneksi, "SELECT * FROM verifikasi_pajak WHERE jenispengajuan_id = '$jenispengajuan_id' AND permohonan_id = '$permohonan_id'");

	// cek apakah ada sudah ada 
	if ($cekVerifikasi->num_rows != 0) {

		// update data
		$result = update($data);
	} else {
		// store data
		$result = store($data);
	}

	return $result;
}

function store($data)
{
	global $koneksi;

	$jenispengajuan_id = $data['jenispengajuan_id'];
	$permohonan_id = $data['permohonan_id'];
	$nilai_barang = $data['nilai_barang'];
	$nilai_jasa = $data['nilai_jasa'];
	$dpp_nilai_lain = $data['dpp_nilai_lain'];
	$total_harga = $data['total_harga'];
	$nilai_dpp = $data['nilai_dpp'];
	$ppn_nilai = $data['ppn_nilai'];
	$id_pph = $data['id_pph'];
	$pph_persen = $data['pph_persen'];
	$pph_nilai = $data['pph_nilai'];
	$biaya_lain = $data['biaya_lain'];
	$potongan = $data['potongan'];
	$grand_total = $data['grand_total'];
	$with_ppn = $data['with_ppn'];
	$ppn_of = $data['ppn_of'];
	$rounding = $data['rounding'];
	$created_by = $data['created_by'];
	$updated_by = $data['updated_by'];
	$created_at = $data['created_at'];
	$updated_at = $data['updated_at'];

	$insert = mysqli_query($koneksi, "INSERT INTO verifikasi_pajak 
	( jenispengajuan_id, permohonan_id, nilai_barang, nilai_jasa, dpp_nilai_lain, total_harga, nilai_dpp, ppn_nilai, id_pph, pph_persen, pph_nilai, biaya_lain, potongan, grand_total, with_ppn, ppn_of, rounding,created_by, updated_by, created_at, updated_at) VALUES
	( '$jenispengajuan_id', '$permohonan_id', '$nilai_barang', '$nilai_jasa', '$dpp_nilai_lain', '$total_harga', '$nilai_dpp', '$ppn_nilai', '$id_pph', '$pph_persen', '$pph_nilai', '$biaya_lain', '$potongan', '$grand_total', '$with_ppn', '$ppn_of', '$rounding', '$created_by', '$updated_by', '$created_at', '$updated_at')
	");

	return $insert;
}

function update($data)
{
	global $koneksi;

	$permohonan_id = $data['permohonan_id'];
	$nilai_barang = $data['nilai_barang'];
	$nilai_jasa = $data['nilai_jasa'];
	$dpp_nilai_lain = $data['dpp_nilai_lain'];
	$total_harga = $data['total_harga'];
	$nilai_dpp = $data['nilai_dpp'];
	$ppn_nilai = $data['ppn_nilai'];
	$id_pph = $data['id_pph'];
	$pph_persen = $data['pph_persen'];
	$pph_nilai = $data['pph_nilai'];
	$biaya_lain = $data['biaya_lain'];
	$potongan = $data['potongan'];
	$grand_total = $data['grand_total'];
	$with_ppn = $data['with_ppn'];
	$ppn_of = $data['ppn_of'];
	$rounding = $data['rounding'];
	$updated_by = $data['updated_by'];
	$updated_at = $data['updated_at'];

	$update = mysqli_query($koneksi, "UPDATE verifikasi_pajak SET nilai_barang = '$nilai_barang', nilai_jasa = '$nilai_jasa', dpp_nilai_lain = '$dpp_nilai_lain',total_harga = '$total_harga',
										nilai_dpp = '$nilai_dpp',ppn_nilai = '$ppn_nilai',id_pph = '$id_pph',pph_persen = '$pph_persen',pph_nilai = '$pph_nilai',
										biaya_lain = '$biaya_lain',potongan = '$potongan',grand_total = '$grand_total',with_ppn = '$with_ppn',ppn_of = '$ppn_of',
										rounding = '$rounding',updated_by = '$updated_by',updated_at = '$updated_at'
										WHERE permohonan_id = '$permohonan_id'
	");


	return $update;
}
