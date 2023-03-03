<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
	$id = $_POST['id'];

	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$nama = $rowUser['nama'];

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");


	// BEGIN/START TRANSACTION        
	mysqli_begin_transaction($koneksi);

	$query1 = mysqli_query($koneksi, "UPDATE bkk_final
										  SET status_bkk = 3, v_mgr_finance = '$tanggal', v_direktur = DATE_ADD(NOW(), INTERVAL 1 HOUR)
										  WHERE id= '$id' ");

	// query data buat diemail
	$queryEmail = mysqli_query($koneksi, "SELECT * FROM bkk_final
											WHERE id = '$id'
											");
	$dataEmail = mysqli_fetch_assoc($queryEmail);

	// query buat ngirim keorang email
	$queryUser = mysqli_query($koneksi, "SELECT * FROM user u
											INNER JOIN divisi d
												ON u.id_divisi = d.id_divisi
											WHERE nm_divisi = 'kasir'
											AND level = 'kasir'");

	// data email
	while ($dataUser = mysqli_fetch_assoc($queryUser)) {
		$link = "url=index.php?p=biaya_khusus&lvl=kasir";
		$name = $dataUser['nama'];
		$email = $dataUser['email'];
		$subject = "Payment Biaya Khusus " . $dataEmail['id'];
		$body = addslashes("<font style='font-family: Courier;'>
				Dear Bapak/Ibu <b>$name</b>,<br><br>
				Diberitahukan bahwa pengajuan Biaya Khusus <b>" . $dataEmail['keterangan'] . "</b> sudah di Approve, dengan rincian sbb:<br>
				<table>
					<tr>
						<td style='font-family: Courier;'>ID</td>
						<td style='font-family: Courier;'>: " . $dataEmail['id'] . "</td>
					</tr>
					<tr>
						<td style='font-family: Courier;'>Nominal</td>
						<td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['nominal']) . "</td>
					</tr>
					<tr>
						<td style='font-family: Courier;'>Keterangan</td>
						<td style='font-family: Courier;'>: " . $dataEmail['keterangan'] . "</td>
					</tr>
					<tr>
						<td style='font-family: Courier;'>Remarks</td>
						<td style='font-family: Courier;'>: " . $dataEmail['remarks'] . "</td>
					</tr>
					<tr>
						<td style='font-family: Courier;'>Tanggal Pengajuan</td>
						<td style='font-family: Courier;'>: " . $dataEmail['created_on_bkk'] . "</td>
					</tr>
					<tr>
						<td style='font-family: Courier;'>Approve Manager Finance</td>
						<td style='font-family: Courier;'>: " . $dataEmail['v_mgr_finance'] . "</td>
					</tr>
					<tr>
						<td style='font-family: Courier;'>Approve Direktur</td>
						<td style='font-family: Courier;'>: " . $tanggal . "</td>
					</tr>
				</table>
				<br>
				Mohon untuk melakukan <i>Payment</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
				Best Regards,<br>
				This email auto generate by system.
			</font>");

		$queue = createQueueEmail($name, $email, $subject, $body);
	}

	if ($query1 && $queue) {
		# jika semua query berhasil di jalankan
		mysqli_commit($koneksi);

		setcookie('pesan', 'Biaya Operasional berhasil di Approved!', time() + (3), '/');
		setcookie('warna', 'alert-success', time() + (3), '/');
	} else {
		#jika ada query yang gagal
		mysqli_rollback($koneksi);

		setcookie('pesan', 'Biaya Operasional gagal di Approved!', time() + (3), '/');
		setcookie('warna', 'alert-danger', time() + (3), '/');
	}
	header("location:index.php?p=verifikasi_biayaops");
}
