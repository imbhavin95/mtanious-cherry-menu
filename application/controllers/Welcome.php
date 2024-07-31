<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function tony_test(){
		$this->load->view('welcome_message_tony');
	}

	public function test()
	{     $_SESSION['test']="HI";
		$this->load->view('welcome_message');
	}

	public function features()
	{
		$this->load->view('features');
	}

	public function pricing()
	{
		$this->load->view('pricing');
	}
	public function PrivacyPolicy()
	{
		$this->load->view('Privacy-Policy');
	}
	public function terms_of_services()
	{
		$this->load->view('terms-of-services');
	}

	public function contact()
	{   //echo "string";
		$this->load->view('contact');
	}

	public function contact_submit(){
		include_once('/var/www/html/mailfunctions.php');
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
include "/var/www/html/phpmailer/class.smtp.php";

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
	}
}
