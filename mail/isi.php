<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

date_default_timezone_set('Asia/Jakarta');
$tanggal = date("Y-m-d H:i:s");

$kode_otomatis = "11";


// query data buat diemail
$queryEmail = mysqli_query($koneksi, "SELECT * FROM kasbon ks
JOIN sr sr
	ON id_sr = sr_id
JOIN divisi d
	ON d.id_divisi = divisi_id
WHERE id_kasbon = '$kode_otomatis'
");
$dataEmail = mysqli_fetch_assoc($queryEmail);

// query buat ngirim keorang email
$queryUser = mysqli_query($koneksi, "SELECT * FROM user u
INNER JOIN divisi d
	ON u.id_divisi = d.id_divisi
WHERE nm_divisi = 'ga umum'
AND level = 'manager_ga'
");

// data email
while ($dataUser = mysqli_fetch_assoc($queryUser)) {
	$link = "url=index.php?p=verifikasi_kasbon&sp=vk_sr&lvl=manager_ga";
	$name = $dataUser['nama'];
	$email = $dataUser['email'];
	$subject = "Approval Kasbon " . $kode_otomatis;
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
                            Mohon untuk melakukan <i>Approve</i> / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                            Best Regards,<br>
                            This email auto generate by system.
                        </font>");

	echo $name . "<br>" . $email . "<br>" . $subject . "<br><br>" . $body . "<br><br><hr>";
}
// if ($var) {
//     // mysql commit transaction
//     mysqli_commit($koneksi);

//     setcookie('pesan', 'Kasbon berhasil di Approve!', time() + (3), '/');
//     setcookie('warna', 'alert-success', time() + (3), '/');
// } else {
//     // mysql rollback transaction
//     mysqli_rollback($koneksi);

//     setcookie('pesan', 'Kasbon gagal di Approve!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
//     setcookie('warna', 'alert-danger', time() + (3), '/');
// }



// <?php
// if (isset($_COOKIE['pesan'])) {
//     echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
// }
// 
