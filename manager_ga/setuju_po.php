<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['id'])) {
	$id_po = $_GET['id'];
	$free_approve = dekripRambo($_GET['free_approve']);

	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$nama = $rowUser['nama'];

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	mysqli_begin_transaction($koneksi);

	if ($free_approve == 1) {
		$query1 = mysqli_query($koneksi, "UPDATE po 
										  	SET status_po = 6, app_mgr_ga = '$tanggal',
											  	app_mgr_finance = '$tanggal',
												app_direksi = '$tanggal',
												app_direksi2 = '$tanggal'
										  WHERE id_po = '$id_po' ");
	} else {
		$query1 = mysqli_query($koneksi, "UPDATE po 
										  	SET status_po = 4, app_mgr_ga = '$tanggal' 
										  WHERE id_po = '$id_po' ");
	}
	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Selesai melakukan verifikasi PO id: $id_po');

									";
	mysqli_query($koneksi, $queryLog);

	// REMARK KARNA SEKARANG EMAILNYA LANGSUNG KE MANAGER FINANCE
	// query data buat diemail
	// $queryEmail = mysqli_query($koneksi, "SELECT * FROM po po
	// 										JOIN detail_biayaops dbo
	// 											ON po.kd_transaksi = dbo.kd_transaksi
	// 										JOIN divisi d
	// 											ON d.id_divisi = dbo.id_divisi
	// 										JOIN biaya_ops bo
	// 											ON dbo.kd_transaksi = bo.kd_transaksi
	// 										WHERE id_po = '$id_po'
	// 												");
	// $dataEmail = mysqli_fetch_assoc($queryEmail);

	// // query buat ngirim keorang email
	// $queryUser = mysqli_query($koneksi, "SELECT * FROM user u
	// 										INNER JOIN divisi d
	// 										ON u.id_divisi = d.id_divisi
	// 										WHERE nm_divisi = 'pajak'
	// 										AND level = 'kordinator_pajak'");

	// // data email
	// while ($dataUser = mysqli_fetch_assoc($queryUser)) {
	// 	$link = "url=index.php?p=verifikasi_po&lvl=kordinator_pajak";
	// 	$name = $dataUser['nama'];
	// 	$email = $dataUser['email'];
	// 	$subject = "Approval PO [" . $dataEmail['po_number'] . "]";
	// 	$body = addslashes("<font style='font-family: Courier;'>
	//                             Dear Bapak/Ibu <b>$name</b>,<br><br>
	//                             Diberitahukan bahwa divisi <b>" . $dataEmail['nm_divisi'] . "</b> telah membuat pengajuan PO, dengan rincian sbb:<br>
	//                             <table>
	//                                 <tr>
	//                                     <td style='font-family: Courier;'>No PO</td>
	//                                     <td style='font-family: Courier;'>: " . $dataEmail['po_number'] . "</td>
	//                                 </tr>
	//                                 <tr>
	//                                     <td style='font-family: Courier;'>Divisi</td>
	//                                     <td style='font-family: Courier;'>: " . $dataEmail['nm_divisi'] . "</td>
	//                                 </tr>
	//                                 <tr>
	//                                     <td style='font-family: Courier;'>Nama Barang</td>
	//                                     <td style='font-family: Courier;'>: " . $dataEmail['nm_barang'] . "</td>
	//                                 </tr>
	//                                 <tr>
	//                                     <td style='font-family: Courier;'>Keterangan</td>
	//                                     <td style='font-family: Courier;'>: " . $dataEmail['keterangan'] . "</td>
	//                                 </tr>
	//                                 <tr>
	//                                     <td style='font-family: Courier;'>Total</td>
	//                                     <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['grand_totalpo']) . "</td>
	//                                 </tr>
	//                                 <tr>
	//                                     <td style='font-family: Courier;'>Tanggal Pengajuan</td>
	//                                     <td style='font-family: Courier;'>: " . $dataEmail['tgl_po'] . "</td>
	//                                 </tr>
	//                                 <tr>
	//                                     <td style='font-family: Courier;'>Approve Manager</td>
	//                                     <td style='font-family: Courier;'>: " . $dataEmail['app_mgr'] . "</td>
	//                                 </tr>
	//                                 <tr>
	//                                     <td style='font-family: Courier;'>Bidding Purchasing</td>
	//                                     <td style='font-family: Courier;'>: " . $dataEmail['app_purchasing'] . "</td>
	//                                 </tr>
	//                                 <tr>
	//                                     <td style='font-family: Courier;'>Approve Manager GA</td>
	//                                     <td style='font-family: Courier;'>: " . $tanggal . "</td>
	//                                 </tr>
	//                             </table>
	//                             <br>
	//                             Mohon untuk melakukan <i>Verifikasi</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
	//                             Best Regards,<br>
	//                             This email auto generate by system.
	//                         </font>");

	// 	$queue = createQueueEmail($name, $email, $subject, $body);
	// }

	// query data buat diemail
	$queryEmail = mysqli_query($koneksi, "SELECT * FROM po po
                                        JOIN detail_biayaops dbo
                                            ON po.kd_transaksi = dbo.kd_transaksi
                                        JOIN divisi d
                                            ON d.id_divisi = dbo.id_divisi
                                        JOIN biaya_ops bo
                                            ON dbo.kd_transaksi = bo.kd_transaksi
                                        WHERE id_po = '$id_po'
                        ");
	$dataEmail = mysqli_fetch_assoc($queryEmail);

	// query buat ngirim keorang email
	$queryUser = mysqli_query($koneksi, "SELECT * FROM user 
                                                WHERE level = 'gm'
						");

	// data email
	while ($dataUser = mysqli_fetch_assoc($queryUser)) {
		$link = "url=index.php?p=verifikasi_po&lvl=manager_keuangan";
		$name = $dataUser['nama'];
		$email = $dataUser['email'];
		$subject = "Approval PO [" . $dataEmail['po_number'] . "]";
		$body = addslashes("<font style='font-family: Courier;'>
                                Dear Bapak/Ibu <b>$name</b>,<br><br>
                                Diberitahukan bahwa divisi <b>" . $dataEmail['nm_divisi'] . "</b> telah membuat pengajuan PO, dengan rincian sbb:<br>
                                <table>
                                    <tr>
                                        <td style='font-family: Courier;'>No PO</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['po_number'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Divisi</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['nm_divisi'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Nama Barang</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['nm_barang'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Keterangan</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['keterangan'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Total</td>
                                        <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['grand_totalpo']) . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Tanggal Pengajuan</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['tgl_po'] . "</td>
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
                                        <td style='font-family: Courier;'>Approve Manager GA</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['app_mgr_ga'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Verifikasi Pajak</td>
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

	if ($query1 && $queue) {
		// mysql commit transaction
		mysqli_commit($koneksi);

		setcookie('pesan', 'PO berhasil di Approve!', time() + (3), '/');
		setcookie('warna', 'alert-success', time() + (3), '/');
	} else {
		// mysql rollback transaction
		mysqli_rollback($koneksi);

		setcookie('pesan', 'PO gagal di Approve!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
		setcookie('warna', 'alert-danger', time() + (3), '/');
	}
	header("location:index.php?p=verifikasi_po");
}
