<?php 
ini_set( 'display_errors', 1 );
    error_reporting( E_ALL );
include_once('mailfunctions.php');
 // print_r($_POST);die;
if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
        //your site secret key
        $secret = '6LfcNrwUAAAAAC7fc-fq1ZGLh-xKjbvd-GMCQptm';
        //get verify response data
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
         if($responseData->success){
				$firstname = $_POST['name_contact'];
                $email = $_POST['email_contact'];
                $phone = $_POST['phone_contact'];
                $query = $_POST['message_contact'];
                $message = "<p>Hi Admin,"."</br>".'There is one Contact us Request'."</p>";
                $message .= "<p>".'Details are given below'.":</p>";
                $message .= "<p>Name: " . $firstname. "</p>";
                $message .= "<p>Phone: " . $phone . "</p>";
                $message .= "<p>Email: " . $email . "</p>";
                $message .= "<p>Message: " . $query . "</p>";
                $message .= "<p>".'Thanks'.",</p><p>".'cherrymenu Team'."</p>";
              //  send_email_helper('hemanth@virtualdusk.com', $message, 'You have received new contact request');
                $emailto = 'hemanth@virtualdusk.com';
$toname = 'cherrymenu';
$emailfrom = $_POST['email_contact'];
$fromname = $_POST['name_contact'];
$subject = 'You have received new contact request';
//$messagebody = 'Hello.';

/*$headers = 
        'Return-Path: ' . $emailfrom . "\r\n" . 
        'From: ' . $fromname . ' <' . $emailfrom . '>' . "\r\n" . 
        'X-Priority: 3' . "\r\n" . 
        'X-Mailer: PHP ' . phpversion() .  "\r\n" . 
        'Reply-To: ' . $fromname . ' <' . $emailfrom . '>' . "\r\n" .
        'MIME-Version: 1.0' . "\r\n" . 
        'Content-Transfer-Encoding: 8bit' . "\r\n"; 
$params = '-f ' . $emailfrom;*/
//$test = mail($emailto, $subject, $message, $headers, $params);

				//header('Location: https://www.cherrymenu.com/');
// test 
/*$headers = 
        'Return-Path: ' . $emailfrom . "\r\n" . 
        'From: ' . $fromname . ' <' . $emailfrom . '>' . "\r\n" . 
        'X-Priority: 3' . "\r\n" . 
        'X-Mailer: PHP ' . phpversion() .  "\r\n" . 
        'Reply-To: ' . $fromname . ' <' . $emailfrom . '>' . "\r\n" .
        'MIME-Version: 1.0' . "\r\n" . 
        'Content-Transfer-Encoding: 8bit' . "\r\n"; 
$params = '-f ' . $emailfrom;*/
include "phpmailer/class.smtp.php";

$mail = new PHPMailer();

$mail->IsSMTP();
$mail->SMTPSecure = "ssl";
$mail->Host = "smtp.yandex.com";
$mail->SMTPAuth= true;
$mail->Username= "info@cherrymenu.com";
$mail->Password = "ntmmmaff";//"Info13579/";
// $mail->Password = "Tony6@tony";//"Info13579/";
$mail->Port=465;
$mail->SMTPOptions = array (
    'SSL' => array(
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true)
);
$mail->From='info@cherrymenu.com';//$_POST['email_contact'];
$mail->FromName=$_POST['name_contact'];
$mail->AddAddress('info@cherrymenu.com');
$mail->IsHTML(true);
$mail->Subject = "You have received new contact request";
$mail->Body= $message;

if(!$mail->Send())
{
        echo "mail not sent";
               echo 'error'.$mail->ErrorInfo;
               echo ("<script LANGUAGE='JavaScript'>
    window.alert('Please Try Again');
    window.location.href='https://www.cherrymenu.com/';
    </script>");
}else{
       echo ("<script LANGUAGE='JavaScript'>
    window.alert('Thank you,we will contact you soon');
    window.location.href='https://www.cherrymenu.com/';
    </script>");
}

}
else{
        echo ("<script LANGUAGE='JavaScript'>
    window.alert('Invalid Captcha,Please try again');
    window.location.href='https://www.cherrymenu.com/';
    </script>");
}
}else{
          echo ("<script LANGUAGE='JavaScript'>
    window.alert('Solve the Captcha');
    window.location.href='https://www.cherrymenu.com/';
    </script>");
}

/*global $error;
$mail = new PHPMailer;
        try{
            $mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'mail.cherrymenu.com';                     // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'info@cherrymenu.com'; // SMTP username
            $mail->Password = 'Info13579/';                   // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable encryption, only 'tls' is accepted
            $mail->isHTML(true); 
            $mail->From = 'info@cherrymenu.com';
            $mail->FromName = $email;//'cherrymenu';
            $mail->addAddress('info@cherrymenu.com'); 
            $mail->AddReplyTo($email);                   // Add a recipient
            $mail->CharSet = 'UTF-8';
            //$mail->AddCC($pdf_data['track_mail']);
            //$mail->AddBCC('info@mytourisma.com');
            //$mail->WordWrap = 50;                               // Set word wrap to 50 characters

            $mail->Subject = 'You have received new contact request';//'myTourisma - New Purchase Alert';
            //$mail->Body    = $CI->load->view('ticket/email_template', $pdf_data, TRUE);
            $mail->Body    = $message;
           // $mail->addAttachment($directoryName);
            
           //$mail->send();
            if($mail->send()){
             echo ("<script LANGUAGE='JavaScript'>
    window.alert('Thank you,we will contact you soon');
    window.location.href='https://www.cherrymenu.com/';
    </script>");
         }else{
            echo $mail->ErrorInfo; 
         	echo ("<script LANGUAGE='JavaScript'>
    window.alert('Please Try Again');
    window.location.href='https://www.cherrymenu.com/';
    </script>");
         }
        }catch (Exception $e) {                                                   
            log_message('error',$mail->ErrorInfo);
        }*/

// test
//include "phpmailer/class.phpmailer.php";
/*include "phpmailer/class.smtp.php";

$mail = new PHPMailer();

$mail->IsSMTP();
$mail->CharSet = 'UTF-8';
$mail->Host = "mail.cherrymenu.com";
$mail->SMTPDebug  = 2; 
$mail->SMTPAuth= true;
$mail->Username= "info@cherrymenu.com";
$mail->Password = "Info13579/";
$mail->Port=587;
$mail->From=$_POST['email_contact'];
$mail->FromName=$_POST['name_contact'];
$mail->AddAddress('hemanth@virtualdusk.com');//info@cherrymenu.com
$mail->IsHTML(true);
$mail->Subject = "You have received new contact request";
$mail->Body= $message;

if(!$mail->Send())
{
        // echo "mail not sent";
                echo 'error'.$mail->ErrorInfo;
                 echo ("<script LANGUAGE='JavaScript'>
    window.alert('Please Try Again');
    window.location.href='https://www.cherrymenu.com/';
    </script>");
}else{
       echo ("<script LANGUAGE='JavaScript'>
    window.alert('Thank you,we will contact you soon');
    window.location.href='https://www.cherrymenu.com/';
    </script>");
}*/
/*}
}else{
    echo ("<script LANGUAGE='JavaScript'>
    window.alert('reCAPTCHA is mandatory!');
    window.location.href='https://www.cherrymenu.com/';
    </script>");
}*/
				?>