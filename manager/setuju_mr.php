<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
	$id = $_POST['id_item'];

	$kd_transaksi = $_POST['kd_transaksi'];

	if (!isset($id)) {
		setcookie('pesan', 'Anda belum melakukan ceklis item pengajuan !', time() + (3), '/');

		header("location:index.php?p=app_dmr&id=$kd_transaksi");
	} else {

		// fungsi begin tran mysql
		mysqli_begin_transaction($koneksi);

		// echo "<p> Yang Anda Pilih : </p>";
		foreach ($id as $nilai) {
			$queryD = mysqli_query($koneksi, "UPDATE detail_biayaops 
										  SET status='2' 
										  WHERE id ='$nilai' ");
		}

		$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
		$rowUser = mysqli_fetch_assoc($queryUser);
		$nama = $rowUser['nama'];

		date_default_timezone_set('Asia/Jakarta');
		$tanggal = date("Y-m-d H:i:s");

		// query buat data email
		$queryEmail = mysqli_query($koneksi, "SELECT * FROM biaya_ops bo
											JOIN divisi dvs
												ON dvs.id_divisi = bo.id_divisi
											JOIN jenis_pengajuan jp
												ON jp.id_jenispengajuan = bo.id_jenispengajuan
											JOIN user usr
												ON id_user = bo.id_manager
											WHERE bo.kd_transaksi = '$kd_transaksi'
											");
		$dataEmail = mysqli_fetch_assoc($queryEmail);

		$queryUser = mysqli_query($koneksi, "SELECT * FROM user 
                                        WHERE level = 'purchasing'");

		// data email
		while ($dataUser = mysqli_fetch_assoc($queryUser)) {
			$name = $dataUser['nama'];
			$email = $dataUser['email'];
			$subject = "Bidding " . $dataEmail['nm_pengajuan'] . " " . $dataEmail['kd_transaksi'];
			if (!function_exists('email_body')) {
				function email_body()
				{
					global $koneksi, $dataEmail, $queryDtl, $dataUser, $kd_transaksi;
					date_default_timezone_set('Asia/Jakarta');
					$tanggal = date("Y-m-d H:i:s");
					$link = "url=index.php?p=list_mr&lvl=purchasing";
					$no = 1;

					$body = "";
					// tabel dalem tabel yg ngelooping
					$body .= addslashes("<font style='font-family: Courier;'>
								Dear Bapak/Ibu <b>" . $dataUser['nama'] . "</b>,<br><br>
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
									<td style='font-family: Courier;'>Approve Manager</td>
									<td style='font-family: Courier;'>: " . $tanggal . "</td>
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

					$queryDtl = mysqli_query($koneksi, "SELECT * FROM detail_biayaops WHERE kd_transaksi = '$kd_transaksi' ORDER BY nm_barang ASC");

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
								Mohon untuk melakukan <i>Bidding</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
								Best Regards,<br>
								This email auto generate by system.
							</font>");
					return $body;
				}
			}
			// insert queue email
			$queue = createQueueEmail($name, $email, $subject, email_body());
		}


		$query1 = mysqli_query($koneksi, "UPDATE biaya_ops 
										  SET status_biayaops=2 , app_mgr = '$tanggal' 
										  WHERE kd_transaksi='$kd_transaksi' ");

		$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Menyetujui Pengajuan MR id: $id');

									";
		mysqli_query($koneksi, $queryLog);


		mysqli_query($koneksi, $query1);


		if ($query1  && $queryD) {
			# jika semua query berhasil di jalankan
			mysqli_commit($koneksi);

			setcookie('pesan', 'Material Request berhasil di Approve!', time() + (3), '/');
			setcookie('warna', 'alert-success', time() + (3), '/');
		} else {
			#jika ada query yang gagal
			mysqli_rollback($koneksi);

			setcookie('pesan', 'Material Request gagal di Approve!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
			setcookie('warna', 'alert-danger', time() + (3), '/');
		}
		header("location:index.php?p=approval_mr");
	}
}
