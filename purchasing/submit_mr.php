<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['id'])) {
	$id = $_GET['id'];

	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$nama = $rowUser['nama'];

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	// query total pengajuan
	$queryDbo =  mysqli_query($koneksi, "SELECT * FROM detail_biayaops
                                            WHERE id = '$id'");
	$rowDbo = mysqli_fetch_assoc($queryDbo);

	$totalPengajuan = $rowDbo['harga_estimasi'];
	$kd_transaksi = $rowDbo['kd_transaksi'];
	$doc_pettycash = $rowDbo['foto_item'];
	$id_anggaran = $rowDbo['id_anggaran'];
	// akhir query

	//deklarasi tanggal
	$bulan    = date('n');
	$romawi    = getRomawi($bulan);
	$tahun     = date('Y');
	$nomor     = "/GS/" . $romawi . "/" . $tahun;

	// SQL transaksi mysqli
	mysqli_begin_transaction($koneksi);

	// pengajuan yang akan di jadikan po
	if ($totalPengajuan > 5000000) {

		$queryNomor = mysqli_query($koneksi, "SELECT MAX(nomor_po) from po WHERE year(tgl_po)='$tahun' ");

		$nomorMax = mysqli_fetch_array($queryNomor);
		if ($nomorMax) {

			$nilaikode = substr($nomorMax[0], 2);
			$kode = (int) $nilaikode;

			//setiap kode ditambah 1
			$kode = $kode + 1;
			$nomorAkhir = "" . str_pad($kode, 4, "0", STR_PAD_LEFT);
		} else {
			$nomorAkhir = "0001";
		}

		$po_number = $nomorAkhir . $nomor;

		//query di kualifikasikan ke po
		$hasil = $queryPo = "INSERT po( id_dbo, kd_transaksi, nomor_po, tgl_po, po_number, sub_totalpo, status_po) VALUES
									 ('$id', '$kd_transaksi', '$nomorAkhir', '$tanggal', '$po_number', '$totalPengajuan', '1');
									 ";
		mysqli_query($koneksi, $queryPo);

		$queue = "berhasil";

		$dataPo = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MAX(id_po) FROM po "));
		$id_po = $dataPo[0];

		$insReaSem =  insRealisasiSem('PO', $id_po, $id_anggaran, $totalPengajuan);
	} else if ($totalPengajuan > 100000 && $totalPengajuan <= 5000000) {  //jika total pengajuan kurang dari 10 jt menjadi kasbon

		$queryHight = mysqli_query($koneksi, "SELECT MAX(id_kasbon) from kasbon ");

		$id_joborder = mysqli_fetch_array($queryHight);
		if ($id_joborder) {

			$nilaikode = substr($id_joborder[0], 2);
			$kode = (int) $nilaikode;

			//setiap kode ditambah 1
			$kode = $kode + 1;
			$kode_otomatis = "KS" . str_pad($kode, 4, "0", STR_PAD_LEFT);
		} else {
			$kode_otomatis = "KS0001";
		}


		//query kasbon
		$queryKasbon = "INSERT kasbon ( id_kasbon , id_dbo, kd_transaksi, harga_akhir, tgl_kasbon, status_kasbon ) VALUES
		                            ('$kode_otomatis', '$id', '$kd_transaksi', '$totalPengajuan', '$tanggal', '3');
		                            ";
		$hasil = mysqli_query($koneksi, $queryKasbon);


		// query data buat diemail
		$queryEmail = mysqli_query($koneksi, "SELECT * FROM kasbon ks
                                                JOIN detail_biayaops dbo
                                                    ON id_dbo = dbo.id
                                                JOIN biaya_ops bo
                                                    ON bo.kd_transaksi = dbo.kd_transaksi
                                                JOIN divisi d
                                                    ON d.id_divisi = dbo.id_divisi
                                                WHERE id_kasbon = '$kode_otomatis'
                                                ");
		$dataEmail = mysqli_fetch_assoc($queryEmail);

		// query buat ngirim keorang email
		$queryUser = mysqli_query($koneksi, "SELECT * FROM user 
                                                WHERE  level = 'manager_finance'");

		// data email
		while ($dataUser = mysqli_fetch_assoc($queryUser)) {
			$link = "url=index.php?p=verifikasi_kasbon&sp=vk_purchasing&lvl=kordinator_pajak";
			$name = $dataUser['nama'];
			$email = $dataUser['email'];
			$subject = "Verifikasi Kasbon " . $kode_otomatis;
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
                                        <td style='font-family: Courier;'>: " . $tanggal . "</td>
                                    </tr>
                                </table>
                                <br>
                                Mohon untuk melakukan <i>Verifikasi</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                                Best Regards,<br>
                                This email auto generate by system.
                            </font>");

			$queue = createQueueEmail($name, $email, $subject, $body);
		}

		$insReaSem =  insRealisasiSem('KBN', $kode_otomatis, $id_anggaran, $totalPengajuan);
	} elseif ($totalPengajuan <= 100000) {

		// KODE OTOMATIS
		$query = mysqli_query($koneksi, "SELECT MAX(kd_pettycash) FROM transaksi_pettycash ");

		$id_joborder = mysqli_fetch_array($query);
		if ($id_joborder) {

			$nilaikode = substr($id_joborder[0], 2);
			$kode = (int) $nilaikode;

			//setiap kode ditambah 1
			$kode = $kode + 1;
			$kode_otomatis = "P" . str_pad($kode, 6, "0", STR_PAD_LEFT);
		} else {
			$kode_otomatis = "P000001";
		}

		$hasil = mysqli_query($koneksi, "INSERT INTO transaksi_pettycash (kd_pettycash, id_dbo, id_anggaran, keterangan_pettycash, total_pettycash, id_divisi, id_manager, status_pettycash, created_pettycash_on, created_pettycash_by, `from`) 
																SELECT '$kode_otomatis', dbo.id ,id_anggaran, keterangan, harga_estimasi, dbo.id_divisi, '53', '1', created_on, created_by, 'mr'
																FROM detail_biayaops dbo
																INNER JOIN biaya_ops bo
																	ON dbo.kd_transaksi = bo.kd_transaksi
																WHERE id = '$id'");

		// copy file dari folder FOTO ke DOC_LPJ
		// copy("../file/foto/$doc_pettycash", "../file/doc_lpj/" . $kode_otomatis . "-lpj-pettycash.pdf");

		$queryEmail = mysqli_query($koneksi, "SELECT * -- email, nama, nm_divisi
												FROM transaksi_pettycash tp   
												JOIN user u
													ON u.id_user = tp.id_manager       
												JOIN divisi d
													ON d.id_divisi = u.id_divisi
												WHERE kd_pettycash = '$kode_otomatis'
					");
		$dataEmail = mysqli_fetch_assoc($queryEmail);

		// query buat ngirim keorang email
		$queryUser = mysqli_query($koneksi, "SELECT * FROM user 
												WHERE  level = 'manager_ga'
					");

		// data email
		while ($dataUser = mysqli_fetch_assoc($queryUser)) {
			$link = "url=index.php?p=approval_pettycash&lvl=manager_ga";

			// data email
			$name = $dataEmail['nama'];
			$email = $dataEmail['email'];
			$subject = "Approval Pettycash " . $dataEmail['kd_pettycash'];
			$body = addslashes("<font style='font-family: Courier;'>
									Dear Bapak/Ibu <b>$name</b>,<br><br>
									Diberitahukan bahwa <b>" . $dataEmail['created_pettycash_by'] . "</b> telah membuat pengajuan Petty Cash, dengan rincian sbb:<br>
									<table>
										<tr>
											<td style='font-family: Courier;'>Kode Transaksi</td>
											<td style='font-family: Courier;'>: " . $dataEmail['kd_pettycash'] . "</td>
										</tr>
										<tr>
											<td style='font-family: Courier;'>Divisi</td>
											<td style='font-family: Courier;'>: " . $dataEmail['nm_divisi'] . "</td>
										</tr>
										<tr>
											<td style='font-family: Courier;'>Keterangan</td>
											<td style='font-family: Courier;'>: " . $dataEmail['keterangan_pettycash'] . "</td>
										</tr>
										<tr>
											<td style='font-family: Courier;'>Total</td>
											<td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['total_pettycash']) . "</td>
										</tr>
										<tr>
											<td style='font-family: Courier;'>Tanggal</td>
											<td style='font-family: Courier;'>: " . $dataEmail['created_pettycash_on'] . "</td>
										</tr>
									</table>
									<br>
									Mohon untuk melakukan <i>Approval</i> / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
									Best Regards,<br>
									This email auto generate by system.
								</font>");

			$queue = createQueueEmail($name, $email, $subject, $body);
		}

		$dataPettycash = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MAX(id_pettycash) AS id_pettyacsh FROM transaksi_pettycash "));
		$id_pettycash = $dataPettycash[0];

		$insReaSem =  insRealisasiSem('PCS', $id_pettycash, $id_anggaran, $totalPengajuan);
	}

	$query2 = mysqli_query($koneksi, "UPDATE detail_biayaops 
											SET  status = '3' 
											WHERE id = '$id';
								");

	$query3 = mysqli_query($koneksi, "UPDATE biaya_ops
									SET app_purchasing = NOW()
									WHERE kd_transaksi = '$kd_transaksi'; ");

	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Selesai melakukan bidding MR id: $kd_transaksi');

									";
	mysqli_query($koneksi, $queryLog);

	if ($hasil && $query2 && $insReaSem) {
		// mysql commit transaction
		mysqli_commit($koneksi);

		setcookie('pesan', 'Material Request berhasil di Submit', time() + (3), '/');
		setcookie('warna', 'alert-success', time() + (3), '/');
	} else {
		// mysql rollback transaction
		mysqli_rollback($koneksi);

		setcookie('pesan', 'Material Request gagal di Submit!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
		setcookie('warna', 'alert-danger', time() + (3), '/');
	}
	header("location:index.php?p=list_mr");
}
