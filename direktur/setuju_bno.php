<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['approve'])) {

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");
	$dateNow = date("Y-m-d");

	$id = $_POST['id'];
	$id_user = $_POST['id_user'];
	$id_direktur = $_POST['id_direktur'];
	$nama = $_POST['nama'];

	// BEGIN TRAN, mysql
	mysqli_begin_transaction($koneksi);

	if (empty($id_direktur)) {
		// Jika id direktur masih kosong , direktur ini approve di id direksi 1 		

		$hasil = mysqli_query($koneksi, "UPDATE bkk 
										 SET id_direktur = $id_user, tgl_verifikasidireksi = '$tanggal'
										 WHERE id_bkk='$id' ");

		$queue = "commit";
	} else {
		// id direktur sudah terisi , direktur ini approve di id direktur 2 
		// dan pengajuan di lanjutkan ke kasir		

		$hasil = mysqli_query($koneksi, "UPDATE bkk 
											SET status_bkk = 7,
											id_direktur2 = $id_user,
											tgl_verifikasidireksi2 = '$tanggal'
										WHERE id_bkk='$id' ");

		// query data BU
		$queryEmail = mysqli_query($koneksi, "SELECT *, mgr.nama as nm_mgr, usr.nama as nm_pemohon, mgr.email as email_mgr
												FROM bkk bkk
												JOIN divisi dvs
													ON bkk.id_divisi = dvs.id_divisi
												JOIN user mgr
													ON bkk.id_manager = mgr.id_user
												JOIN user usr
													ON id_pemohon = usr.id_user
												WHERE id_bkk = '$id'");
		$dataEmail = mysqli_fetch_assoc($queryEmail);

		// query buat ngirim email
		$queryKasir = mysqli_query($koneksi, "SELECT * FROM user u
												INNER JOIN divisi d
													ON u.id_divisi = d.id_divisi
												WHERE nm_divisi = 'kasir'
												AND level = 'kasir'");

		// data email
		while ($dataKasir = mysqli_fetch_assoc($queryKasir)) {
			$tanggal_tempo = $dataEmail['tgl_tempo'];

			$link = "url=index.php?p=payment_kaskeluar&lvl=kasir";
			$name = $dataKasir['nama'];
			$email = $dataKasir['email'];
			$subject = "Payment Biaya Umum " . $dataEmail['kd_transaksi'];
			$body = addslashes("<font style='font-family: Courier;'>
							Dear Bapak/Ibu <b>$name</b>,<br><br>
							Diberitahukan bahwa <b>" . $dataEmail['nm_pemohon'] . "</b> telah membuat pengajuan Biaya Umum, dengan rincian sbb:<br>
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
									<td style='font-family: Courier;'>Nama Vendor</td>
									<td style='font-family: Courier;'>: " . $dataEmail['nm_vendor'] . "</td>
								</tr>
								<tr>
									<td style='font-family: Courier;'>Keterangan</td>
									<td style='font-family: Courier;'>: " . $dataEmail['keterangan'] . "</td>
								</tr>
								<tr>
									<td style='font-family: Courier;'>Nilai Barang</td>
									<td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['nilai_barang']) . "</td>
								</tr>
								<tr>
									<td style='font-family: Courier;'>Nilai Jasa</td>
									<td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['nilai_jasa']) . "</td>
								</tr>
								<tr>
									<td style='font-family: Courier;'>PPN</td>
									<td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['ppn_nilai']) . " (" . $dataEmail['ppn_persen'] . "%)</td>
								</tr>
								<tr>
									<td style='font-family: Courier;'>PPH</td>
									<td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['pph_nilai']) . " (" . $dataEmail['pph_persen'] . "%)</td>
								</tr>
								<tr>
									<td style='font-family: Courier;'>Total</td>
									<td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['jml_bkk']) . "</td>
								</tr>
								<tr>
									<td style='font-family: Courier;'>Tanggal Pengajuan</td>
									<td style='font-family: Courier;'>: " . $dataEmail['tgl_pengajuan'] . "</td>
								</tr>
								<tr>
									<td style='font-family: Courier;'>Approve Manager</td>
									<td style='font-family: Courier;'>: " . $dataEmail['tgl_verifikasimanager'] . "</td>
								</tr>
								<tr>
									<td style='font-family: Courier;'>Verifikasi Pajak</td>
									<td style='font-family: Courier;'>: " . $dataEmail['tgl_verifikasipajak'] . "</td>
								</tr>
								<tr>
									<td style='font-family: Courier;'>Approve Manager Finance</td>
									<td style='font-family: Courier;'>: " . $dataEmail['tgl_verifikasimanagerkeuangan'] . "</td>
								</tr>
								<tr>
									<td style='font-family: Courier;'>Approve Direktur 1</td>
									<td style='font-family: Courier;'>: " . $dataEmail['tgl_verifikasidireksi'] . "</td>
								</tr>
								<tr>
									<td style='font-family: Courier;'>Approve Direktur 2</td>
									<td style='font-family: Courier;'>: $tanggal</td>
								</tr>
							</table>
							<br>
							Mohon untuk melakukan <i>Payment</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
							Best Regards,<br>
							This email auto generate by system.
						</font>");

			if ($dataEmail['jenis'] == "umum") {
				$queue = createQueueEmail($name, $email, $subject, $body);
			} else {
				$queue = createQueueEmailTempo($name, $email, $subject, $body, $tanggal_tempo);
			}
		}
	}

	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Menyetujui Pengajuan Biaya Non OPS id: $id');

									";
	mysqli_query($koneksi, $queryLog);

	if ($hasil && $queue) {
		# jika semua query berhasil di jalankan
		mysqli_commit($koneksi);

		setcookie('pesan', 'Biaya Umum berhasil di Approve!', time() + (3), '/');
		setcookie('warna', 'alert-success', time() + (3), '/');
	} else {
		#jika ada query yang gagal
		mysqli_rollback($koneksi);

		setcookie('pesan', 'Biaya Umum gagal di Approve!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
		setcookie('warna', 'alert-danger', time() + (3), '/');
	}
	header("location:index.php?p=verifikasi_bno");
}
