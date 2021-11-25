<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$tgl_sekarang = date("Y-m-d");

// data email yg ada tanggal tempo, jika tgl tempo lebih kecil sama dengan dari hari ini maka dijalanin
$queryMail = mysqli_query($koneksi, "SELECT * FROM queue_email_tempo
                                    WHERE tanggal_tempo <= '$tgl_sekarang'
                                    ORDER BY created_at ASC");
$totalMail = mysqli_num_rows($queryMail);

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
        $mail->Host = "tls://smtp.gmail.com";    //host mail server
        //Set this to true if SMTP host requires authentication to send email
        $mail->SMTPAuth = true;
        //Provide username and password     
        $mail->Username = "system.ekanuri@gmail.com";        //nama-email smtp          
        $mail->Password = base64_decode("U3lzdGVtMTM1Nzk=");        //password email smtp
        //If SMTP requires TLS encryption then set it
        $mail->SMTPSecure = "tls";
        //Set TCP port to connect to 
        $mail->Port = 587;

        $mail->From = "system.ekanuri@gmail.com";        //email pengirim
        $mail->FromName = "System Ekanuri (No Reply)";        //nama pengirim

        $mail->addAddress($dataMail['address_email'], $dataMail['name_email']);        //email penerima

        $mail->isHTML(true);

        $mail->Subject = $dataMail['subject_email'];        //subject
        // $mail->Body = email_data();		// isi pesan
        $mail->Body = $dataMail['body'];        // isi pesan
        $mail->AltBody = "";        //body email (optional)


        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo . "<br>";
        } else {
            echo "Message has been sent successfully<br>";
            echo "Antrian berhasil di hapus<br>";
        }
        mysqli_query($koneksi, "DELETE FROM queue_email_tempo WHERE id_queue = '$id_queue'");
    }
}
