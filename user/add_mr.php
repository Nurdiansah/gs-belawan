<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['id'])) {
	$id = $_GET['id'];

	$query = mysqli_query($koneksi, "SELECT MAX(kd_transaksi) from biaya_ops ");

	$id_joborder = mysqli_fetch_array($query);
	if ($id_joborder) {

		$nilaikode = substr($id_joborder[0], 2);
		$kode = (int) $nilaikode;

		//setiap kode ditambah 1
		$kode = $kode + 1;
		$kode_otomatis = "B" . str_pad($kode, 5, "0", STR_PAD_LEFT);
	} else {
		$kode_otomatis = "B00001";
	}

	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$id_user = $rowUser['id_user'];
	$id_divisi = $rowUser['id_divisi'];
	$id_manager = $rowUser['id_manager'];
	$nama = $rowUser['nama'];

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	// BEGIN/START TRANSACTION        
	mysqli_begin_transaction($koneksi);

	// query log
	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Pembuatan Biaya OPS $kode_otomatis');

									";
	mysqli_query($koneksi, $queryLog);

	$queryDetail = "UPDATE detail_biayaops SET status = '1', kd_transaksi = '$kode_otomatis'  
                                            -- WHERE id_divisi ='$id_divisi' AND status = '0' AND is_for = 'mr'
											WHERE id = '$id'";

	$hasilDtl = mysqli_query($koneksi, $queryDetail);
	// query insert 
	$query = "INSERT INTO biaya_ops ( kd_transaksi, id_divisi, tgl_pengajuan, id_manager, created_by, created_on, id_jenispengajuan) VALUES 
								( '$kode_otomatis','$id_divisi',  NOW(), '$id_manager', '$nama', '$tanggal', '1');
			";

	$hasil = mysqli_query($koneksi, $query);


	// query buat data email
	$queryEmail = mysqli_query($koneksi, "SELECT * FROM biaya_ops bo
											JOIN divisi dvs
												ON dvs.id_divisi = bo.id_divisi
											JOIN jenis_pengajuan jp
												ON jp.id_jenispengajuan = bo.id_jenispengajuan
											JOIN user usr
												ON id_user = bo.id_manager
											WHERE bo.kd_transaksi = '$kode_otomatis'
											");
	$dataEmail = mysqli_fetch_assoc($queryEmail);

	$queryDtl = mysqli_query($koneksi, "SELECT * FROM detail_biayaops WHERE kd_transaksi = '$kode_otomatis' ORDER BY nm_barang ASC");

	// data email
	$name = $dataEmail['nama'];
	$email = $dataEmail['email'];
	$subject = "Approval " . $dataEmail['nm_pengajuan'] . " " . $dataEmail['kd_transaksi'];
	function email_body()
	{
		global $dataEmail;
		global $queryDtl;
		$link = "url=index.php?p=approval_mr&lvl=manager";
		$no = 1;

		$body = "";
		// tabel dalem tabel
		$body .= addslashes("<font style='font-family: Courier;'>
						Dear Bapak/Ibu <b>" . $dataEmail['nama'] . "</b>,<br><br>
						Diberitahukan bahwa <b>" . $dataEmail['created_by'] . "</b> telah membuat pengajuan " . $dataEmail['nm_pengajuan'] . ", dengan rincian sbb:<br>
						<table>
						<tr>
							<td style='font-family: Courier;'>Kode Transaksi</td>
							<td style='font-family: Courier;'>: " . $dataEmail['kd_transaksi'] . "</td>
						</tr>
						<tr>
							<td style='font-family: Courier;'>Divisi</td>
							<td style='font-family: Courier;'>: " . $dataEmail['nm_divisi'] . "</td>
						</tr>
						<tr>
							<td style='font-family: Courier;'>Total</td>
							<td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['total']) . "</td>
						</tr>
						<tr>
							<td style='font-family: Courier;'>Tanggal Pengajuan</td>
							<td style='font-family: Courier;'>: " . $dataEmail['tgl_pengajuan'] . "</td>
						</tr>
						<tr>
							<td style='font-family: Courier;'>Detail</td>
							<td style='font-family: Courier;'>:</td>
						</tr>
						<tr>
							<td style='font-family: Courier;'></td>
							<td style='font-family: Courier;'>
						<table border='1' style='border-collapse: collapse;'>
							<tr>
								<th style='font-family: Courier;'>No</th>
								<th style='font-family: Courier;'>Nama Barang</th>
								<th style='font-family: Courier;'>Merk</th>
								<th style='font-family: Courier;'>Tipe</th>
								<th style='font-family: Courier;'>Spesifikasi</th>
								<th style='font-family: Courier;'>Satuan</th>
								<th style='font-family: Courier;'>Jumlah</th>
								<th style='font-family: Courier;'>Keterangan</th>
								<th style='font-family: Courier;'>Harga Estimasi</th>
							</tr>");
		while ($dataDtl = mysqli_fetch_assoc($queryDtl)) {
			$body .= addslashes("<tr>
						<td style='font-family: Courier;'>" . $no++ . "</td>
						<td style='font-family: Courier;'>" . $dataDtl['nm_barang'] . "</td>
						<td style='font-family: Courier;'>" . $dataDtl['merk'] . "</td>
						<td style='font-family: Courier;'>" . $dataDtl['type'] . "</td>
						<td style='font-family: Courier;'>" . $dataDtl['spesifikasi'] . "</td>
						<td style='font-family: Courier;'>" . $dataDtl['satuan'] . "</td>
						<td style='font-family: Courier;'>" . $dataDtl['jumlah'] . "</td>
						<td style='font-family: Courier;'>" . $dataDtl['keterangan'] . "</td>
						<td style='font-family: Courier;'>" . formatRupiah2($dataDtl['harga_estimasi']) . "</td>
					</tr>");
		}
		$body .= addslashes("</table>
						</td>
					</tr>
				</table>
				<br>
				Mohon untuk melakukan <i>Approval</i> / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
				Best Regards,<br>
				This email auto generate by system.
			</font>");
		return $body;
	}

	// insert queue email
	$queue = createQueueEmail($name, $email, $subject, email_body());

	if ($hasil && $queue && $hasilDtl) {
		# jika semua query berhasil di jalankan
		mysqli_commit($koneksi);

		setcookie('pesan', 'MR berhasil disubmit!', time() + (3), '/');
		setcookie('warna', 'alert-success', time() + (3), '/');
	} else {
		#jika ada query yang gagal
		mysqli_rollback($koneksi);

		setcookie('pesan', 'MR gagal disubmit!', time() + (3), '/');
		setcookie('warna', 'alert-danger', time() + (3), '/');
	}
	header("location:index.php?p=proses_mr");
}

?>
<!-- pindah -->
<!--  -->