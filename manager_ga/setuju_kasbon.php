<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['id'])) {
	$id_kasbon = $_GET['id'];
	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]' ");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$nama = $rowUser['nama'];

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	mysqli_begin_transaction($koneksi);

	// query data buat diemail
	$queryEmail = mysqli_query($koneksi, "SELECT * FROM kasbon ks
											JOIN detail_biayaops dbo
												ON id_dbo = dbo.id
											JOIN biaya_ops bo
												ON bo.kd_transaksi = dbo.kd_transaksi
											JOIN divisi d
												ON d.id_divisi = dbo.id_divisi
											WHERE id_kasbon = '$id_kasbon'
											");
	$dataEmail = mysqli_fetch_assoc($queryEmail);

	// query buat ngirim keorang email
	$queryUser = mysqli_query($koneksi, "SELECT * FROM user u
											INNER JOIN divisi d
											ON u.id_divisi = d.id_divisi
											WHERE nm_divisi = 'finance'
											AND level = 'kordinator_pajak'");

	// data email
	while ($dataUser = mysqli_fetch_assoc($queryUser)) {
		$link = "url=index.php?p=verifikasi_kasbon&sp=vk_purchasing&lvl=kordinator_pajak";
		$name = $dataUser['nama'];
		$email = $dataUser['email'];
		$subject = "Approval Kasbon " . $id_kasbon;
		$body = addslashes("<font style='font-family: Courier;'>
                                Dear Bapak/Ibu <b>$name</b>,<br><br>
                                Diberitahukan bahwa divisi <b>" . $dataEmail['nm_divisi'] . "</b> telah membuat pengajuan Kasbon, dengan rincian sbb:<br>
                                <table>
                                    <tr>
                                        <td style='font-family: Courier;'>Kode Transaksi</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['id_kasbon'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Divisi</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['nm_divisi'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Keterangan</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['keterangan'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Total</td>
                                        <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['harga_akhir']) . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Tanggal Pengajuan</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['tgl_kasbon'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Approve Manager</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['app_mgr'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Bidding Purchasing</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['app_purchasing'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Verifikasi Pajak</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['app_pajak'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Approval Manager GA</td>
                                        <td style='font-family: Courier;'>: " . $tanggal . "</td>
                                    </tr>
                                </table>
                                <br>
                                Mohon untuk melakukan <i>Approval</i> / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                                Best Regards,<br>
                                This email auto generate by system.
                            </font>");

		$queue = createQueueEmail($name, $email, $subject, $body);
	}

	$query1 = mysqli_query($koneksi, "UPDATE kasbon
										  SET status_kasbon=2 , app_mgr_ga = '$tanggal' 
										  WHERE id_kasbon = '$id_kasbon' ");

	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Selesai melakukan verifikasi kasbon id: $id_kasbon');

									";
	mysqli_query($koneksi, $queryLog);

	if ($queue && $query1) {
		// mysql commit transaction
		mysqli_commit($koneksi);

		setcookie('pesan', 'Kasbon berhasil di Approve!', time() + (3), '/');
		setcookie('warna', 'alert-success', time() + (3), '/');
	} else {
		// mysql rollback transaction
		mysqli_rollback($koneksi);
		echo mysqli_error($koneksi);
		die;
		setcookie('pesan', 'Kasbon gagal di Approve!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
		setcookie('warna', 'alert-danger', time() + (3), '/');
	}
	header("location:index.php?p=verifikasi_kasbon&sp=vk_purchasing");
}
