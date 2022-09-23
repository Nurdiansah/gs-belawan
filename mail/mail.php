<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryMail = mysqli_query($koneksi, "SELECT * FROM queue_email ORDER BY created_at ASC LIMIT 10");
$totalMail = mysqli_num_rows($queryMail);

mysqli_query($koneksi, "DELETE FROM gs.queue_email WHERE address_email IN ('indra@ekanuri.com', 'wildan@ekanuri.com', 'roy@grahasegara.com')");

if ($totalMail > 0) {
	while ($dataMail = mysqli_fetch_assoc($queryMail)) {
		$id_queue = $dataMail['id_queue'];

		require_once "library/PHPMailer.php";
		require_once "library/Exception.php";
		require_once "library/OAuth.php";
		require_once "library/POP3.php";
		require_once "library/SMTP.php";

		$mail = new PHPMailer;

		//Enable SMTP debugging. 
		// $mail->SMTPDebug = 3;	//NGILANGIN DEBUG KEKNYA                           
		//Set PHPMailer to use SMTP.
		$mail->isSMTP();
		//Set SMTP host name                          
		$mail->Host = "tls://smtp.gmail.com";	//host mail server
		//Set this to true if SMTP host requires authentication to send email
		$mail->SMTPAuth = true;
		//Provide username and password     
		$mail->Username = "develop@ekanuri.com";		//nama-email smtp          
		$mail->Password = base64_decode("VlcxR2RGbHRPSGhOZWxVelQxRTlQUT09");		//password email smtp
		//If SMTP requires TLS encryption then set it
		$mail->SMTPSecure = "tls";
		//Set TCP port to connect to 
		$mail->Port = 587;

		$mail->From = "develop@ekanuri.com";		//email pengirim
		$mail->FromName = "Develop Ekanuri";		//nama pengirim

		$mail->addAddress($dataMail['address_email'], $dataMail['name_email']);		//email penerima

		$mail->isHTML(true);

		$mail->Subject = $dataMail['subject_email'];		//subject
		// $mail->Body = email_data();		// isi pesan
		$mail->Body = $dataMail['body'];		// isi pesan
		$mail->AltBody = "";		//body email (optional)


		if (!$mail->send()) {
			// echo "Mailer Error: " . $mail->ErrorInfo . "<br>";
		} else {
			echo "Message has been sent successfully<br>";
			mysqli_query($koneksi, "DELETE FROM queue_email WHERE id_queue = '$id_queue'");
			echo "Antrian berhasil di hapus<br>";
		}
	}
}
