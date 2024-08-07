<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ItemsModel');
        $this->load->model('UsersModel');
        $this->load->model('SettingsModel');
    }

    public function index()
    {
        $this->load->view('welcome_message');
    }

    public function tony_test()
    {
        $this->load->view('welcome_message_tony');
    }

    public function restaurant_landing_page()
    {
//        $this->load->view('welcome_message');
        $urlVariables = urldecode($this->uri->segment('2'));

        $urlVariablesArray = explode('-', $urlVariables);

        $restaurantName = $urlVariablesArray[0];
        $restaurantSettings = $this->SettingsModel->get_settings_detail(['rest_name' =>  $restaurantName, 'is_deleted' => 0, 'is_active' => 1]);
        if(!$restaurantSettings){
            $this->load->view('restaurants/include/header');
            $this->load->view('restaurants/error-404');
            $this->load->view('restaurants/include/footer');
        }else{
            $restaurantId = $restaurantSettings['user_id'];

            // $userdata = $this->UsersModel->get_user_detail(['id' => $restaurantId] , 'name,image');

            $query = "SELECT * FROM `package_details` where DATE_ADD(created_at, INTERVAL 30 DAY)>=CURRENT_TIMESTAMP and `restaurant_id` = '".$restaurantId."' and status='activate' and package_id='3' and flag=1";
            // $query = "SELECT * FROM `package_details` where `restaurant_id` = '".$restaurantId."' and status='activate' and flag=1";
            $restaurantDetails = $this->UsersModel->run_manual_query($query);

            if(!$restaurantDetails){
                $query1 = "SELECT * FROM `package_details` where  `restaurant_id` = '".$restaurantId."' and status!='activate' and package_id!='3' or `restaurant_id` = '".$restaurantId."' and status='activate' and package_id!='3' and date(now())>date(end_date)  and flag=1";
                $resultdata1 = $this->UsersModel->run_manual_query($query1);


                $query2 = "SELECT * FROM `package_details` where  `restaurant_id` = '".$restaurantId."' and status='activate' and package_id!='3' and date(now())<=date(end_date) and flag=1";
                $resultdata2 = $this->UsersModel->run_manual_query($query2);

                $query3 = "SELECT * FROM `package_details` where  `restaurant_id` = '".$restaurantId."' and status!='activate' and package_id='3'";
                $resultdata3 = $this->UsersModel->run_manual_query($query3);

                $query4 = "SELECT * FROM `package_details` where  `restaurant_id` = '".$restaurantId."' and status='activate' and package_id='3' and DATE_ADD(created_at, INTERVAL 30 DAY)<CURRENT_TIMESTAMP";
                $resultdata4 = $this->UsersModel->run_manual_query($query4);


                if($resultdata2)
                {
                    // $userdata = $this->UsersModel->get_user_detail(['id' => $restaurantId] , 'name,image');
                    $data['rest_image'] = $restaurantSettings['logo'];
                    $data['restid'] = $restaurantId;
                    $data['currency'] = $restaurantSettings['currency'];
                    $data['item_data'] = @$this->ItemsModel->get_items_bycategory($restaurantId);
                    $data['main_langid'] = @$urlVariablesArray[1];

                    if(!$data['rest_image'] || !$data['restid'] || !$data['item_data']){
                        $data['message']='Either Menu or Category or Items are disabled. Please check and come back';
                        $this->load->view('restaurants/include/header', ['data' => $data]);
                        $this->load->view('restaurants/error-general', $data);
                        $this->load->view('restaurants/include/footer');
                    }else{
                        $this->load->view('restaurants/include/header', ['data' => $data]);
                        $this->load->view('restaurants/index', ['data' => $data]);
                        $this->load->view('restaurants/include/footer');
                    }

                }else
                    if($resultdata1 && !$resultdata2)
                    {
                        $data['message']='Your Subscription has expired. Kindly contact our team to renew your plan.';
                        $this->load->view('restaurants/include/header', ['data' => $data]);
                        $this->load->view('restaurants/error-general', $data);
                        $this->load->view('restaurants/include/footer');
                    }else
                        if(($resultdata3 || $resultdata4) && !$resultdata2)
                        {
                            $data['message']='Your Free Plan has expired. kindly contact our team to subscribe to a plan.';
                            $this->load->view('restaurants/include/header', ['data' =>$data]);
                            $this->load->view('restaurants/error-general', $data);
                            $this->load->view('restaurants/include/footer');
                        }
            }else{
                // $userdata = $this->UsersModel->get_user_detail(['id' => $restaurantId] , 'name, image');
                $data['rest_image'] = $restaurantSettings['logo'];
                $data['restid'] = $restaurantId;
                $data['currency'] = $restaurantSettings['currency'];
                $data['item_data'] = $this->ItemsModel->get_items_bycategory($restaurantId);
                $data['main_langid'] = @$urlVariablesArray[1];

                if(!$data['rest_image'] || !$data['restid'] || !$data['item_data']){
                    $data['message']='Either Menu or Category or Items are disabled. Please check and come back';
                    $this->load->view('restaurants/include/header', ['data' =>$data]);
                    $this->load->view('restaurants/error-general', $data);
                    $this->load->view('restaurants/include/footer');
                }else{
                    $this->load->view('restaurants/include/header', ['data' =>$data]);
                    $this->load->view('restaurants/index' , $data);
                    $this->load->view('restaurants/include/footer');
                }
            }
        }

    }

    // https://www.cherrymenu.com/login/item_detail?cid=48356&itid=75220&rid=24404&sid=1
    public function item_detail()
    {
        $catid = $this->input->get('cid');
        $restid = $this->input->get('rid');
        $itid = $this->input->get('itid');
        $sid = $this->input->get('sid');
        $data['singleitem'] = $this->ItemsModel->single_item_detail($itid);
        $data['restid'] = $restid;
        $data['cat_id'] = $catid;
        $data['sid'] = $sid;
        $userdata = $this->UsersModel->get_user_detail(['id' => $restid], 'name,image');
        $data['rest_name'] = $userdata['name'];
        $data['name'] = $userdata['name'];
        $res = $this->SettingsModel->get_settings_detail(['user_id' => $restid, 'is_deleted' => 0, 'is_active' => 1]);
        $data['currency'] = $res['currency'];
        $data['rest_image'] = $res['logo'];
        $data['sesurl'] = "https://www.cherrymenu.com/" . $res['rest_name'];
        $data['menu_name'] = $this->ItemsModel->get_menu_name($catid);
        if (!$data['menu_name'] || !$userdata || !$data['singleitem']) {
            $this->load->view('restaurants/include/header', ['data' => $userdata]);
            $this->load->view('restaurants/error-404');
            $this->load->view('restaurants/include/footer');
        } else {
            $item_data2 = $this->ItemsModel->get_items_bycat($catid);
            $item_data = $item_data2['new'];
            foreach ($item_data as $key => $value) {
                if ($value['id'] == $itid) {
                    unset($item_data[$key]);
                }
            }
            $i = 0;
            foreach ($item_data as $key => $value) {
                if ($i > 2) {
                    unset($item_data[$key]);
                }
                $i++;
            }
            $data['item_data'] = $item_data;
            $this->load->view('restaurants/include/header', ['data' => $data]);
            $this->load->view('restaurants/product-detail', ['data' => $data]);
            $this->load->view('restaurants/include/footer');
        }
    }

    public function review()
    {
        $restid = $this->input->get('rid');
        $itid = $this->input->get('itid');
        $data['singleitem'] = $this->ItemsModel->single_item_detail($itid);
        $data['restid'] = $restid;
        $userdata = $this->UsersModel->get_user_detail(['id' => $restid], 'name,image');
        $data['rest_name'] = $userdata['name'];
        $data['name'] = $userdata['name'];
        $res = $this->SettingsModel->get_settings_detail(['user_id' => $restid, 'is_deleted' => 0, 'is_active' => 1]);
        $data['currency'] = $res['currency'];
        $data['rest_image'] = $res['logo'];

        $this->load->view('restaurants/include/header', ['data' => $data]);
        $this->load->view('restaurants/review', ['data' => $data]);
        $this->load->view('restaurants/include/footer');
    }

    public function submitReview()
    {
        $feedback = "INSERT INTO `feedbacks`(`staff_id`, `restaurant_id`, `staff_name`, `stars`,`customer_name`,`feedback`,`is_active`,`is_deleted`) VALUES ('0', '".$_POST['rid']."', 'staff', '".$_POST['rating']."', '".$_POST['name']."', '".$_POST['feedback']."', '1', '0')";
        $feedbackQuery = $this->db->query($feedback);

        if($feedbackQuery){
            $this->session->set_flashdata('review_success', 'Your review has been submitted!');
            redirect('/review?rid='.$_POST['rid']);
        }
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

            } else{
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
