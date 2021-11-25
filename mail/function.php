<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include "../fungsi/koneksi.php";

function sendEmail($emailAddress)
{

    function email_data()
    {
        // global $email;

        // $queryData = mysqli_query($koneksi, "SELECT * FROM queue_mail 
        // 											  ORDER BY created_at DESC
        // 								");									

        $pesan = "";

        $pesan .= "Dear Bapak/Ibu <b>PT. Graha Segara</b> <br><br>
								";

        $pesan .= "Email Test";

        $pesan .= "<br>		Email ini otomatis dibuat oleh sistem <br>
							Salam Hormat, <br>
							ENC Sistem. <br>";

        return $pesan;
    }

    require_once "library/PHPMailer.php";
    require_once "library/Exception.php";
    require_once "library/OAuth.php";
    require_once "library/POP3.php";
    require_once "library/SMTP.php";

    $mail = new PHPMailer;

    //Enable SMTP debugging. 
    // $mail->SMTPDebug = 3;    //NGILANGIN DEBUG KEKNYA                           
    //Set PHPMailer to use SMTP.
    $mail->isSMTP();
    //Set SMTP host name                          
    $mail->Host = "tls://smtp.gmail.com"; //host mail server
    //Set this to true if SMTP host requires authentication to send email
    $mail->SMTPAuth = true;
    //Provide username and password     
    $mail->Username = "system.ekanuri@gmail.com";   //nama-email smtp          
    $mail->Password = base64_decode("U3lzdGVtMTM1Nzk=");           //password email smtp
    //If SMTP requires TLS encryption then set it
    $mail->SMTPSecure = "tls";
    //Set TCP port to koneksiect to 
    $mail->Port = 587;

    $mail->From = "system.ekanuri@gmail.com"; //email pengirim
    $mail->FromName = "System Ekanuri (No Reply)"; //nama pengirim

    $mail->addAddress($emailAddress); //email penerima 
    // $mail->addAddress("ahmadjuantoro@gmail.com", "Ahmad Juantoro"); //email penerima 

    $mail->isHTML(true);

    $mail->Subject = "Contoh judul"; //subject
    $mail->Body = email_data(); // isi pesan
    $mail->AltBody = ""; //body email (optional)


    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Message has been sent successfully";
        // echo $key + 1 . '.' . $email['address_email'] . ' success <br>';
    }
}
