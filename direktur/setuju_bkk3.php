<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
	$id = $_POST['id'];
	$pengajuan = $_POST['pengajuan'];
	$jenis_bu = $_POST['jenis_bu'];
	$bulanCOB = $_POST['bulan'];

	//query user
	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]' ");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$nama = $rowUser['nama'];

	// tanggal
	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	$bulan    = date('n', strtotime($bulanCOB));


	$bulanSekarang    = date('n');

	// BEGIN/START TRANSACTION        
	mysqli_begin_transaction($koneksi);

	if ($pengajuan == 'KASBON' || $pengajuan == 'BIAYA UMUM') {


		//deklarasi tanggal

		$romawi    = getRomawi($bulan);
		$tahun     = date('Y');
		$nomor     = "/GS-GK/" . $romawi . "/" . $tahun;

		$queryNomor = mysqli_query($koneksi, "SELECT MAX(nomor) from bkk_final WHERE month(created_on_bkk)='$bulan' ");

		$nomorMax = mysqli_fetch_array($queryNomor);
		if ($nomorMax) {

			$nilaikode = substr($nomorMax[0], 0);
			$kode = (int) $nilaikode;

			//setiap kode ditambah 1
			$kode = $kode + 1;
			$nomorAkhir = "" . str_pad($kode, 3, "0", STR_PAD_LEFT);
		} else {
			$nomorAkhir = "001";
		}

		$nomorBkk = $nomorAkhir . $nomor;
		$queue = "berhasil";

		// print_r($nomorBkk);
		// die;

		if ($jenis_bu == 'kontrak') {
			# jika biaya umum kontrak release on bkk di kosongin status di ganti 17
			// UPDATE BKK
			$query1 = mysqli_query($koneksi, "UPDATE bkk_final
													SET status_bkk = 17 , v_direktur = '$tanggal'
													WHERE id= '$id' ");
		} else {

			if ($bulan == $bulanSekarang) {
				// Jika kasbon dan biaya umum yang umum
				$query1 = mysqli_query($koneksi, "UPDATE bkk_final
															SET status_bkk = 4 , v_direktur = '$tanggal',
																nomor = '$nomorAkhir', no_bkk = '$nomorBkk', 
																release_on_bkk = '$tanggal'
															WHERE id= '$id' ");
			} else {


				$tanggal = date('Y-m-t', strtotime($bulanCOB));
				// Jika kasbon dan biaya umum yang umum
				$query1 = mysqli_query($koneksi, "UPDATE bkk_final
													SET status_bkk = 4 , v_direktur = '$tanggal',
														nomor = '$nomorAkhir', no_bkk = '$nomorBkk', 
														release_on_bkk = '$tanggal'
													WHERE id= '$id' ");
			}
		}

		// untuk pengajuan PO
	} else if ($pengajuan == 'PO') {
		// UPDATE BKK
		$query1 = mysqli_query($koneksi, "UPDATE bkk_final
										SET status_bkk = 17 , v_direktur = '$tanggal'
										WHERE id= '$id' ");

		$queue = "berhasil";
	} else {
		// untuk biaya operasional
		$query1 = mysqli_query($koneksi, "UPDATE bkk_final
										  SET status_bkk = 3 , v_direktur = '$tanggal'
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
	}


	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'direktur Finance Menyetujui BKK: $id');

									";
	mysqli_query($koneksi, $queryLog);


	if ($queue && $query1) {
		# jika semua query berhasil di jalankan
		mysqli_commit($koneksi);

		setcookie('pesan', 'BKK berhasil di Approved!', time() + (3), '/');
		setcookie('warna', 'alert-success', time() + (3), '/');
	} else {
		#jika ada query yang gagal
		mysqli_rollback($koneksi);

		setcookie('pesan', 'BKK gagal di Approved!', time() + (3), '/');
		setcookie('warna', 'alert-danger', time() + (3), '/');
	}
	header("location:index.php?p=verifikasi_bkk");
}
