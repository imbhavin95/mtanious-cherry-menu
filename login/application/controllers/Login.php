<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
        $this->load->helper('string');
        $this->load->library('Form_validation');
        $this->load->model('Packages_model');
        $this->load->model('Package_request');
         

    }

    /**
     * Display login page for login
     */
    public function index()
    {

//        echo password_hash('123456789', PASSWORD_BCRYPT);
//        exit;
        $verification_code = $this->input->get('code');
        if(!empty($verification_code))
        {
            $verification_code = base64_decode($verification_code);
            //--- check varification code is valid or not
            $result = $this->users_model->check_verification_code($verification_code);
            if (!empty($result)) 
            {
                $this->session->set_flashdata('success', 'Account has been successfully activated');
                $new_verification_code = verification_code();
                $id = $result['id'];
                $data = array(
                    'is_active' => 1,
                    'verification_code' => $new_verification_code,
                );
                $this->users_model->common_insert_update('update', TBL_USERS, $data, ['id' => $id]);
                
                $this->freeTrial(trim($result['email']), trim($result['name']));
                //Get Free package if exist in Package Detail table.
                $freeTriel = $this->Packages_model->get_package_detail(['is_deleted' => 0,'is_active' => 1,'type' => 'free']);
                if(!empty($freeTriel))
                {
                    $package = array(
                        'package_id' => $freeTriel['id'],
                        'restaurant_id' => $id,
                        'status' => 'activate',
                        'flag' => 1,
                        'end_date' => date('Y-m-d', strtotime('+1 year'))
                    );
                    $package['request_date'] = date('Y-m-d');
                    $package['created_at'] = date('Y-m-d H:i:s');
                    //Assign free packages limit.
                    $this->users_model->common_insert_update('update', TBL_USERS, ['devices_limit' => $freeTriel['devices_limit'], 'users_limit' => $freeTriel['users'], 'menus_limit' => $freeTriel['menus'], 'categories_limit' => $freeTriel['categories'],'items_limit' => $freeTriel['items']], ['id' => $id]);
                    $package_detail_id = $this->users_model->common_insert_update('insert', TBL_PACKAGE_DETAILS, $package); 
                    redirect(base_url(),'login');
                }
            }else
            {
                $this->session->set_flashdata('error', 'Wrong email or password');
                redirect(base_url(),'login');
            }
        }
        $this->form_validation->set_rules('email', 'Email', 'trim|required|callback_login_validation');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == false) {
            $data['error'] = validation_errors();
        } else {

            //-- If redirect is set in URL then redirect user back to that page
            if ($this->input->get('redirect')) {
                redirect(base64_decode($this->input->get('redirect')));
            } else {
                if (is_admin()) {
                    redirect(base_url() . 'admin/Home');
                } else if(is_restaurant()) {
                    redirect(base_url() . 'restaurant/Home');
                } else {
                    redirect(base_url() . 'restaurant/menus');
                }
            }
        }
        $data['title'] = 'Login';
        $this->load->view('Backend/login', $data);
    }



    /**
     * Callback Validate function to check Admin/Restaurant Login
     * @return boolean
     */
    public function pre_login_validation()
    {
    	$result = $this->users_model->get_user_detail(['email' => trim($this->input->post('email')), 'is_deleted' => 0,'is_active' => 0]);
        if ($result) {
            $this->form_validation->set_message('login_validation', 'Please confirm the mail to verify ');
        	return false;
        } else {
            return true;
        } 
    }

    /**
     * Callback Validate function to check Admin/Restaurant Login
     * @return boolean
     */
    public function login_validation()
    {

        $result = $this->users_model->get_user_detail(['email' => trim($this->input->post('email')), 'is_deleted' => 0]);
        if ($result) {
            if($result['role'] === ADMIN || $result['role'] === RESTAURANT || $result['role'] === SUB_ADMIN)
            {
            	if ($result['is_active'] == 0) {
            		//Please confirm your mail
                    $this->form_validation->set_message('login_validation', 'Please confirm the mail to verify');
                    return false;
                }else
                if ($result['is_deleted'] == 1) {
            		$this->form_validation->set_message('login_validation', 'Your Account has been blocked! Please contact system administrator');
                    return false;
                }
                else
                if (!password_verify($this->input->post('password'), $result['password'])) {
                    $this->form_validation->set_message('login_validation', 'Password does not matched.');
                    return false;
                }else {
                	 if($result['id'])
                	{
                		$id=$result['id'];
                	}else{
                		$id=$result['restaurant_id'];
                	} $flggg=0;

                      if($result['id']!=1 && $flggg==1){
                    $query="SELECT * FROM `package_details` where DATE_ADD(created_at, INTERVAL 30 DAY)>=CURRENT_TIMESTAMP and `restaurant_id` = '".$id."' and status='activate' and package_id='3'";
                    $resultdata=$this->users_model->run_manual_query($query);
                    if(!$resultdata){

                     $query="SELECT * FROM `package_details` where  `restaurant_id` = '".$id."' and status!='activate' and package_id!='3'";
                    	$resultdata1=$this->users_model->run_manual_query($query);
                    	if($resultdata1)
                    	{
                    		$this->form_validation->set_message('login_validation', 'The Plan is expired! Contact the Administrator to Renew the Plan');
                         	return false;

                    	}

                    	$query="SELECT * FROM `package_details` where  `restaurant_id` = '".$id."' and status='activate' and package_id!='3'";
                    	$resultdata1=$this->users_model->run_manual_query($query);


                    	if($resultdata1)
                    	{
                             if(isset($_POST['remember_me']) && !empty($_POST['remember_me'])){
                          $email = $this->encryption->encrypt($this->input->post('email'));
                          $password = $this->encryption->encrypt($this->input->post('password'));

                          $encoded_email = set_cookie('remember_me_email',$email,172800);
                          $encoded_ck = set_cookie('remember_me_ck',true,172800);
                          $encoded_email = set_cookie('remember_me_password',$password,172800);
                            }
                            
                          $this->session->set_userdata('login_user', $result);
                    	 return true; 
                    	 } 

                        $query1="SELECT * FROM `package_details` where  `restaurant_id` = '".$id."' and status!='activate' and package_id='3'";
                    	$resultdata2=$this->users_model->run_manual_query($query1);
                    	if($resultdata2)
                    	{
                    		$this->form_validation->set_message('login_validation', 'The Free Plan is expired! Contact the Administrator to Renew the Plan');
                         	return false;

                    	}

                    	$this->form_validation->set_message('login_validation', 'The Free Plan is expired! Contact the Administrator to Renew the Plan');
                         	return false;
                         }
                     }

                    if (isset($_POST['remember_me']) && !empty($_POST['remember_me'])) {
                        $email = $this->encryption->encrypt($this->input->post('email'));
                        $password = $this->encryption->encrypt($this->input->post('password'));
                        $encoded_ck = set_cookie('remember_me_ck',true,172800);
                        $encoded_email = set_cookie('remember_me_email', $this->input->post('email'), 172800);
                        $encoded_password = set_cookie('remember_me_password', $this->input->post('password'), 172800);
                    }
                            

                   $this->session->set_userdata('login_user', $result);
                    return true;
                }
                
            }
            else
            {
                $this->form_validation->set_message('login_validation', 'Invalid Email/Password.');
                return false;
            }
        } else {
            $this->form_validation->set_message('login_validation', 'Invalid Email/Password.');
            return false;
        }
    }
 
    /**
     * Use for Logout.
     * @author sm
     */
    public function logout()
    {
        if ($this->session->userdata('logged_in') !== false) {
            $this->session->unset_userdata('logged_in');
            $this->session->set_flashdata('logout', true);
            $this->session->sess_destroy();
            delete_cookie(REMEMBER_ME_COOKIE_NAME);
            redirect(base_url(), 'refresh');
        }
    }

    /**
     * viewProfile : viewProfile.
     * @return
     * @author sm
     */

    public function viewProfile()
    {
        $data['user'] = $this->Login->getDetail('users', '*', array('id' => $this->session->userdata('id'), 'is_deleted' => 0));
        $data["pageTitle"] = "Profile";
        $this->template->load('default', 'layout/my_profile', $data);
    }

    /**
     * Forgot password page
     */
    public function forgot_password()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|callback_email_validation');
        if ($this->form_validation->run() == false) {
            $data['error'] = validation_errors();
        } else {
            $user = $this->users_model->get_user_detail(['email' => trim($this->input->post('email')), 'is_deleted' => 0, 'is_active' => 1]);
            $verification_code = verification_code();
            $this->users_model->common_insert_update('update', TBL_USERS, array('verification_code' => $verification_code), array('id' => $user['id']));
            $verification_code = base64_encode($verification_code);
            $encoded_verification_code = $verification_code;
            $email_data = [];
            $email_data['url'] = base_url() . 'Login/reset_password?code=' . $encoded_verification_code;
            $email_data['name'] = $user['name'];
            $email_data['email'] = trim($this->input->post('email'));
            $email_data['subject'] = 'Reset Password';
            send_email(trim($this->input->post('email')), 'forgot_password', $email_data);
            $this->session->set_flashdata('success', 'Email has been successfully sent to reset password! Please check email');
            redirect(base_url(),'login');
        }
        $data['title'] = WEBNAME.' | Forgot Password';
        $this->load->view('Backend/forgot_password', $data);
    }

/**
 * Reset password page
 */
    public function reset_password()
    {
        $data['title'] = WEBNAME.' | Reset Password';
        $verification_code = $this->input->get('code');
        $verification_code = base64_decode($verification_code);
        //--- check varification code is valid or not
        $result = $this->users_model->check_verification_code($verification_code);
        if (!empty($result)) {
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            $this->form_validation->set_rules('con_password', 'Confirm password', 'trim|required|matches[password]');
            if ($this->form_validation->run() == false) {
                $data['error'] = validation_errors();
            } else {
                //--- if valid then reset password and generate new verification code
                //--- generate verification code
                $new_verification_code = verification_code();
                $id = $result['id'];
                $data = array(
                    'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                    'verification_code' => $new_verification_code,
                );
                $this->users_model->common_insert_update('update', TBL_USERS, $data, ['id' => $id]);
                $this->session->set_flashdata('success', 'Your password changed successfully');
                redirect(base_url(),'login');
            }
            $this->load->view('Backend/reset_password', $data);
        } else {
            //--- if invalid verification code
            $this->session->set_flashdata('error', 'Invalid request or already changed password');
            redirect(base_url(),'login');
        }
    }

    /**
     * Check email is valid or not
     */
    public function check_email()
    {
        $requested_email = trim($this->input->get('email'));
        $user = $this->users_model->get_user_detail(['email' => $requested_email, 'is_deleted' => 0, 'is_active' => 1]);
        if ($user) {
            echo "true";
        } else {
            echo "false";
        }
        exit;
    }

    /**
     * Forgot password email validation
     */
    public function email_validation()
    {
        $requested_email = trim($this->input->post('email'));
        $user = $this->users_model->get_user_detail(['email' => $requested_email, 'is_deleted' => 0, 'is_active' => 1]);
        if (empty($user)) {
            $this->form_validation->set_message('email_validation', 'This email id is not registered with us');
            return false;
        } else {
            return true;
        }
    }


    /**
    * This function used to send email for free trial
    * 
    */
    public function freeTrial($email,$name)
    {
        $email_data = ['name' => $name, 'email' => trim($email),'subject' => 'Your trial has started!'];
        $email_data['head_title'] = "Welcome on board!";
        $email_data['message'] = "Your 30 days trial has started, we shall be sending you few reminders before the trial expires";
        //$this->load->view('Backend/email_templates/free_trial',$email_data);
        send_email(trim($email), 'reminder', $email_data);
    }
}
