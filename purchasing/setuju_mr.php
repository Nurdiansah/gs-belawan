<?php  

session_start();
	include "../fungsi/koneksi.php";
	include "../fungsi/fungsi.php";

	if(isset($_GET['id'])) {
        $kd_transaksi = $_GET['id'];	
            

		$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
		$rowUser=mysqli_fetch_assoc($queryUser);	
		$nama=$rowUser['nama'];

		date_default_timezone_set('Asia/Jakarta');
		$tanggal= date("Y-m-d H:i:s");		

		// query total pengajuan
		$queryBo =  mysqli_query($koneksi, "SELECT * FROM detail_biayaops
											WHERE kd_transaksi='$kd_transaksi' AND status = '2' ");
				
		$totalPengajuan = 0;
        if (mysqli_num_rows($queryBo)) {
			while($row=mysqli_fetch_assoc($queryBo)):
			
			$nominal = $row['harga_estimasi'];
											  
			$totalPengajuan += $nominal;
		endwhile; }
		// akhir query

		// pengajuan yang akan di jadikan po
		if ($totalPengajuan >= 10000000) {

				//deklarasi tanggal
				$bulan    = date('n');
				$romawi    = getRomawi($bulan);
				$tahun     = date ('Y');
				$nomor     = "/GS/".$romawi."/".$tahun;

				$queryNomor = mysqli_query($koneksi, "SELECT MAX(nomor_po) from po WHERE month(tgl_po)='$bulan' ");
		
				$nomorMax = mysqli_fetch_array($queryNomor);    
				if ($nomorMax) {
						
						$nilaikode = substr($nomorMax[0], 2);
						$kode = (int) $nilaikode;
				
						//setiap kode ditambah 1
						$kode = $kode + 1;
						$nomorAkhir = "".str_pad($kode, 4, "0", STR_PAD_LEFT);                   
						
					
				} else {
					$nomorAkhir = "0001";
				}

				$po_number = $nomorAkhir.$nomor;

				//query di kualifikasikan ke po
				$queryPo = "INSERT po( kd_transaksi, nomor_po, tgl_po, po_number, total_po) VALUES
									 ('$kd_transaksi', '$nomorAkhir', '$tanggal', '$po_number', '$totalPengajuan');
									 ";
				mysqli_query($koneksi, $queryPo);

				// query 
				$query1 = mysqli_query($koneksi, "UPDATE biaya_ops 
										  SET status_biayaops=15 
										  WHERE kd_transaksi='$kd_transaksi' ");

		} else if($totalPengajuan < 10000000){  //jika total pengajuan kurang dari 10 jt menjadi kasbon

			$query1 = mysqli_query($koneksi, "UPDATE biaya_ops 
										  SET status_biayaops=3 , app_purchasing = '$tanggal' 
										  WHERE kd_transaksi='$kd_transaksi' ");
		}

		// akhir 
						
		$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Selesai melakukan bidding MR id: $kd_transaksi');

									";
		mysqli_query($koneksi, $queryLog);		

		if($queryLog) {
			header("location:index.php?p=list_mr");
		} else {
			echo "ada yang salah" . mysqli_error($koneksi);
		}
	}
