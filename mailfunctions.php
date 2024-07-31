<?php 
include 'Mailin.php';
//include('phpmailer/class.phpmailer.php');
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
require 'phpmailer/class.phpmailer.php';
require 'phpmailer/PHPMailerAutoload.php';
//require 'fpdf/fpdf.php';
//require 'html2pdf/html2pdf.php';

function getbaseTemplate()
{
	$message = file_get_contents("emailTemplates/basehtml.txt"); 
	return $message;
}
function getbaseTemplate_1()
{
	$message = file_get_contents("emailTemplates/basehtml_manideep.txt"); 
	return $message;
}







// mailgun email sending 


function send_email_helper($customer_email, $m9,$subject){
		global $error;
        $mail = new PHPMailer;
        try{

            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'mail.cherrymenu.com';//'smtp.mailgun.org';                     // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;      
            //Send@cherrymenu.com                         // Enable SMTP authentication
            $mail->Username = 'info@cherrymenu.com'; //'postmaster@mailer.mytourisma.com'; // SMTP username//
            $mail->Password = 'Info13579/';//noreply13579//';                   // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable encryption, only 'tls' is accepted
            $mail->isHTML(true); 
            $mail->From = 'info@cherrymenu.com';
            $mail->FromName = 'myTourisma';
            $mail->addAddress($customer_email);                   // Add a recipient
            //$mail->AddCC($pdf_data['track_mail']);
            //$mail->AddBCC('info@mytourisma.com');
            //$mail->WordWrap = 50;                               // Set word wrap to 50 characters
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;//'myTourisma - Purchase Alert';
            //$mail->Body    = $CI->load->view('ticket/email_template', $pdf_data, TRUE);
            $mail->Body    = $m9;
            //$mail->addAttachment($directoryName); 
            
            $mail->send();

        }catch (Exception $e) {                                                   
            log_message('error',$mail->ErrorInfo);
        }
}

?>
