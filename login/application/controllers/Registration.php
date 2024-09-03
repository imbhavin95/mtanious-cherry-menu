<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Registration extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
        $this->load->helper('string');
        $this->load->library('Form_validation');
        $this->load->model('Packages_model');
        $this->load->model('Package_request');
        $this->load->model('Menus_model');
        $this->load->model('users_model');
        $this->load->model('Categories_model');
        $this->load->model('Items_model');
          
           /*$Category_Id=385;
           $menus_ids = $this->Items_model->get_menu_ids($Category_Id);
           print_r($this->db->last_query());die;
                   print_r($menus_ids);die;*/
                  
     
    }

    /**
    * Display login page for login
    */
    public function index()
    {
        $data['title'] = 'Registration';
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[20]|strip_tags');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('currency_code', 'Currency Code', 'required');
        $data['currencies']=$this->users_model->run_manual_query('SELECT * FROM `default_currencies` ');
        if ($this->form_validation->run() == true) {
            $dataArr = array(
                'role' => RESTAURANT,
                'name' => trim($this->input->post('name')),
                'devices_limit' => 0,
                'users_limit' => 0,
                'menus_limit' => 0,
                'categories_limit' => 0,
                'items_limit' => 0,
                'is_active' => 0
            );
            $password = $this->input->post('password');
            $phone = $this->input->post('phone');
            $dataArr['email'] = trim($this->input->post('email'));
            $dataArr['password'] = password_hash($password, PASSWORD_BCRYPT);
             $dataArr['phone_number'] =  $phone;
            $dataArr['created_at'] = date('Y-m-d H:i:s');
            $inserted_id = $this->users_model->common_insert_update('insert', TBL_USERS, $dataArr); 

            $default_staff_id = $this->users_model->common_insert_update('insert', TBL_USERS, ['role' => STAFF,'name' => 'staff','restaurant_id' => $inserted_id,'is_active' => 1]); //Insert Default Staff user 

            $directory = RESTAURANT_IMAGES . '/' . $inserted_id;
            if (!file_exists(RESTAURANT_IMAGES)) {
                mkdir(RESTAURANT_IMAGES);
            }
            if (!file_exists($directory)) {
                mkdir($directory);
            }
            
            $verification_code = verification_code();
            $this->users_model->common_insert_update('update', TBL_USERS, array('verification_code' => $verification_code), array('id' => $inserted_id));

            $dataArr_settings = array(
                'user_id' => $inserted_id,
                 'currency' => $this->input->post('currency_code')
            );
            $dataArr_settings['created_at'] = date('Y-m-d H:i:s');
                $inserted_id2 = $this->users_model->common_insert_update('insert', TBL_SETTINGS, $dataArr_settings);

            $url = base_url() . 'login?code='.base64_encode($verification_code);
            $email_data = ['name' => trim($this->input->post('name')), 'type' => 'restaurant','email' => trim($this->input->post('email')), 'url' => $url, 'password' => $password, 'subject' => 'Welcome! Please confirm your email'];
            
              $email_data2 = ['name' => trim($this->input->post('name')), 'type' => 'restaurant','email' => trim($this->input->post('email')), 'phone_number' => @$phone,'subject' => 'New User signup In Cherrymenu '];
            //Send registration mail.

           send_email(trim($this->input->post('email')), 'new_restaurant', $email_data);
           send_email('info@cherrymenu.com', 'new_restaurant2', $email_data2);

//           $freeTriel = $this->Packages_model->get_package_detail(['is_deleted' => 0 ,'is_active' => 1 ,'type' => 'free']);
//             if(!empty($freeTriel))
//             {
//                 $package = array(
//                     'package_id' => $freeTriel['id'],
//                     'restaurant_id' => $inserted_id,
//                     'status' => 'activate',
//                     'flag' => 1
//                 );
//                 $package['request_date'] = date('Y-m-d');
//                 $package['created_at'] = date('Y-m-d H:i:s');
//                 //Assign free packages limit.
//                 $this->users_model->common_insert_update('update', TBL_USERS, ['devices_limit' => $freeTriel['devices_limit'], 'users_limit' => $freeTriel['users'], 'menus_limit' => $freeTriel['menus'], 'categories_limit' => $freeTriel['categories'],'items_limit' => $freeTriel['items']], ['id' => $inserted_id]);
//                 $package_detail_id = $this->users_model->common_insert_update('insert', TBL_PACKAGE_DETAILS, $package);
//             }

            $this->defaultVal($inserted_id);
            $this->session->set_flashdata('success', 'Restaurant has been registered successfully and Email has been sent to user successfully');
            redirect('login');
        }    
        $this->load->view('Backend/signup', $data);
    }

    /**
    * This function used to check Unique email at the time of signup
    * */
    public function checkUniqueEmail()
    {
        $email = trim($this->input->post('email'));
        $restaurant = $this->users_model->check_unique_email($email);
        if ($restaurant) {
            echo "false";
        } else {
            echo "true";
        }
        exit;
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

    /**
    * This function used to send email for free trial
    */
    public function tenDaysLeft()
    {
        //$this->users_model->common_insert_update('insert', 'test', array('name' => 'simal')); 
        $users = $this->Package_request->get_all_details(TBL_PACKAGE_DETAILS,['is_deleted' => 0])->result_array();
        if(!empty($users))
        {
            foreach ($users as $key => $row) {
                //$data[$key] = $this->addDayswithdate($row['request_date'],20);
                $result = $this->addDayswithdate($row['created_at'],20);
                if(!empty($result))
                {
                    if(date('Y-m-d H',strtotime($result)) == date('Y-m-d H'))
                    {
                        $data = $this->users_model->get_user_detail(['id' => $row['restaurant_id'],'is_active' => 1,'is_deleted' => 0]);
                        $free_package = $this->Packages_model->get_package_detail(['is_deleted'=> 0,'type' => 'free','id' => $row['package_id']]);
                        if(!empty($data) && !empty($free_package))
                        {
                            $email_data = ['name' => trim($data['name']), 'email' => trim($data['email']),'subject' => 'Knock, Knock! Your demo is about to expire'];
                            $email_data['head_title'] = "Hope you’re enjoying your trial of Cherry Menu";
                            $email_data['message'] = "We would like to remind you that your trial will expire in  <b>10 days</b>";
                            //$this->load->view('Backend/email_templates/free_trial',$email_data);
                            send_email(trim($data['email']), 'reminder', $email_data);
                        }
                    }
                }
            }
        }
        exit;
    }

    function addDayswithdate($date,$days){
        $date = strtotime("+".$days." days", strtotime($date));
        return  date("Y-m-d H:i:s", $date);
    }


    function addDayswithdate1($date,$days){
        $date = strtotime("-".$days." days", strtotime($date));
          //echo 'dgghfghg'.date("Y-m-d H:i:s", $date);
        return  date("Y-m-d H:i:s", $date);
    }

    public function test_date()
    {
        // date('Y-m-d H',strtotime($result)) == date('Y-m-d H')
        //print_r($this->addDayswithdate('2018-10-28 15:25:42',30));
        print_r($this->addDayswithdate('2021-01-19 15:25:42',30));
        echo '<br/>'.date('Y-m-d H');
        echo '<br/>'.date('Y-m-d H',strtotime('2018-10-26 13:25:42'));
        die;
    }
    /**
    * This function used to send email for free trial
    * 
    */
    public function twoDaysLeft()
    {
        $users = $this->Package_request->get_all_details(TBL_PACKAGE_DETAILS,['is_deleted' => 0])->result_array();
        if(!empty($users)){
            foreach ($users as $key => $row) {
                $result = $this->addDayswithdate($row['created_at'],28);
                if(!empty($result)){
                    if(date('Y-m-d H',strtotime($result)) == date('Y-m-d H')){
                        $data = $this->users_model->get_user_detail(['id' => $row['restaurant_id'],'is_active' => 1,'is_deleted' => 0]); // Check valid restaurant or not?
                        $free_package = $this->Packages_model->get_package_detail(['is_deleted'=> 0,'type' => 'free','id' => $row['package_id']]); //check free package or not?
                        if(!empty($data) && !empty($free_package)){
                            $email_data = ['name' => trim($data['name']), 'email' => trim($data['email']),'subject' => 'Knock, Knock! Your demo is about to expire'];
                            $email_data['head_title'] = "Hope you’re enjoying your trial of Cherry Menu";
                            $email_data['message'] = "We would like to remind you that your trial will expire in  <b>2 days</b>";
                            send_email(trim($data['email']), 'reminder', $email_data);
                        }
                    }
                }
            }
        }
        exit;
    }

    /**
    * This function used to send email for free trial
    * 
    */

    public function expired()
    {
        $users = $this->Package_request->get_all_details(TBL_PACKAGE_DETAILS,['is_deleted' => 0])->result_array();
        
        if(!empty($users)){
            foreach ($users as $key => $row) {
                $result = $this->addDayswithdate($row['created_at'],30);
                if(!empty($result)){
                    if(date('Y-m-d H',strtotime($result)) == date('Y-m-d H')){
                        $data = $this->users_model->get_user_detail(['id' => $row['restaurant_id'],'is_active' => 1,'is_deleted' => 0]); // Check valid restaurant or not?
                        $free_package = $this->Packages_model->get_package_detail(['is_deleted'=> 0,'type' => 'free','flag' => 1,'id' => $row['package_id']]); //check free package or not?
                        
                        if(!empty($data) && !empty($free_package))
                        {
                            $update_array = array('is_active' => 0);
                            $this->users_model->common_insert_update('update', TBL_USERS, $update_array, ['id' => $row['restaurant_id']]); // Block account after free trial expired
                            $this->users_model->common_insert_update('update', TBL_PACKAGE_DETAILS, ['status' => 'deactivate'],['id' => $row['id']]); 
                            $email_data = ['name' => trim($data['name']), 'email' => trim($data['email']),'subject' => 'Your trial with Cherry Menu has expired!'];
                            $email_data['head_title'] = "Hope you’ve enjoyed your trial of Cherry Menu";
                            $email_data['message'] = "Your trial has expired but it’s not too late! you can either visit your account to subscribe to one of the packages or you can contact us if you’d like an extension of your trial version.";
                            send_email(trim($data['email']), 'reminder', $email_data);
                        }
                    }
                }
            }
        }
        exit;
    }



    public function package_expired()
    {
        $users = $this->Package_request->get_all_details(TBL_PACKAGE_DETAILS,['is_deleted' => 0,'flag' => 1])->result_array();
        $filterrestid=array();
        if(!empty($users)){
            foreach ($users as $key => $row) { 
                     //echo "restaurant_id".$row['restaurant_id']." ".$row['end_date'];echo "<br>";
                      //echo "restaurant_id".$row['restaurant_id']." ".
                        $rem1 = $this->addDayswithdate1($row['end_date'],7); //echo "<br>";echo "<br>";
                      $rem2 = $this->addDayswithdate1($row['end_date'],30);//echo "<br>";
                      $res_ar=array(date('Y-m-d',strtotime($rem1)),date('Y-m-d',strtotime($rem2)));
                      $currentdate=date('Y-m-d'); //date('Y-m-d',strtotime($rem1));
                   
                if(!empty($res_ar)){
                    if(in_array($currentdate, $res_ar)  && !in_array($row['restaurant_id'], $filterrestid)){
                        $filterrestid[]=$row['restaurant_id'];  
                        $data = $this->users_model->get_user_detail(['id' => $row['restaurant_id'],'is_active' => 1,'is_deleted' => 0]); // Check valid restaurant or not?
                        $free_package = $this->Packages_model->get_package_detail1(['is_deleted'=> 0,'flag' => 1,'restaurant_id' => $row['restaurant_id']]); //check free package or not?
                        //below line to get packagename
                          $package_name =  $this->Packages_model->get_package_detail(['is_deleted'=> 0,'id' => $row['package_id']],'name'); //check free package or not?  echo "in loops";
                          $restaurant = $this->Package_request->get_all_details(TBL_USERS,['id'=> $row['restaurant_id'],'is_deleted' => 0])->result_array();
                          $package = $this->Package_request->get_all_details(TBL_PACKAGES,['id'=> $row['package_id'],'is_deleted' => 0])->result_array();

                        if(!empty($data) && !empty($free_package)) // && $row['restaurant_id']=='118'
                        {    
                            $update_array = array('is_active' => 0);
                            $email_data = ['name' => trim($data['name']), 'email' => trim($data['email']),'subject' => 'Your '.$package_name['name'].' with Cherry Menu is about to Expire'];
                            $email_data['head_title'] = "Hope you’ve enjoying your ".$package_name['name']." of Cherry Menu";
                           
                            $message = "Your subscription to the ".$package_name['name']." will expire on ".date('d-m-Y',strtotime($row['end_date']));     
                            $message.= '<br>';
                            $message.='Kindly sign in to your dashboard to request renewal of your subscription to avoid any service interruption.';
                            $message.= '<br>';
                            $message.= 'If you need help, you can always contact our team on info@cherrymenu.com';
                            $email_data['message'] = $message;

                              //generate invoices PDF
                            $invoice['restaurant'] = $restaurant[0];
                            $total_ar = array(
                             '1' => '1428',
                             '2' => '2388',
                             '5' => '4788',
                             '9' => '900',
                            );

                            foreach ($total_ar as $key1 => $value1) {
                                if($key1==$package[0]['id']){
                                   $package[0]['price']=$value1;
                                }
                            } 
                            $invoice['package'] = $package[0];
                            $invoicename='invoice-'.time(); 
                            // $invoice['package_detail'] = $package_details;
                            $newFile  = APPPATH."/../assets/Backend/$invoicename.pdf";
                            $view =  $this->load->view('Backend/invoice1_new',$invoice,true);
                            $this->load->library('m_pdf');
                            $this->m_pdf->pdf->WriteHTML($view);
                            // $mpdf->Output('example000.pdf', 'F');
                            $this->m_pdf->pdf->Output($newFile, 'F');
                            //$this->m_pdf->pdf->Output('/var/www/html/login/assets/Backend','invoice.pdf');


                            send_email(trim($data['email']), 'reminder', $email_data);
                            // send_email(trim('vinayak@virtualdusk.com'), 'reminder', $email_data,$newFile);
                             //unlink('/var/www/html/login/assets/Backend/invoice.pdf') ; 
                         }
                    }
                }
            }
        }
        exit;
    }





    public function package_reminder()
    {
        $users = $this->Package_request->get_all_details(TBL_PACKAGE_DETAILS,['is_deleted' => 0,'flag' => 1])->result_array();
        $filterrestid=array();
        if(!empty($users)){
            foreach ($users as $key => $row) { 
                     //echo "restaurant_id".$row['restaurant_id']." ".$row['end_date'];echo "<br>";
                      //echo "restaurant_id".$row['restaurant_id']." ".
                        $rem1 = $this->addDayswithdate1($row['end_date'],7); //echo "<br>";echo "<br>";
                      $rem2 = $this->addDayswithdate1($row['end_date'],30);//echo "<br>";
                      $res_ar=array(date('Y-m-d',strtotime($rem1)),date('Y-m-d',strtotime($rem2)));
                      $currentdate=date('Y-m-d');//date('Y-m-d',strtotime($rem1));
                   
                if(!empty($res_ar)){
                    if(in_array($currentdate, $res_ar)  && !in_array($row['restaurant_id'], $filterrestid)){
                        $filterrestid[]=$row['restaurant_id'];  
                        $data = $this->users_model->get_user_detail(['id' => $row['restaurant_id'],'is_active' => 1,'is_deleted' => 0]); // Check valid restaurant or not?
                        $free_package = $this->Packages_model->get_package_detail1(['is_deleted'=> 0,'flag' => 1,'restaurant_id' => $row['restaurant_id']]); //check free package or not?
                        //below line to get packagename
                          $package_name =  $this->Packages_model->get_package_detail(['is_deleted'=> 0,'id' => $row['package_id']],'name'); //check free package or not?  echo "in loops";
                          $restaurant = $this->Package_request->get_all_details(TBL_USERS,['id'=> $row['restaurant_id'],'is_deleted' => 0])->result_array();
                          $package = $this->Package_request->get_all_details(TBL_PACKAGES,['id'=> $row['package_id'],'is_deleted' => 0])->result_array();

                        if(!empty($data) && !empty($free_package) )
                        {    
                            $update_array = array('is_active' => 0);
                            $email_data = ['name' => trim('Admin'), 'email' => trim($data['email']),'subject' => 'Cherrymenu Package Expiry Notification'];
                            $email_data['head_title'] = "Invoice attached for Restaurant- ".trim($data['name'])." and the package is  ".$package_name['name']." of Cherry Menu and will expire by ".date('d-m-Y',strtotime($row['end_date']));
                           
                            
                            $email_data['message'] = '';//$message;

                              //generate invoices PDF
                            $invoice['restaurant'] = $restaurant[0]; 
                            $total_ar = array(
                             '1' => '1428',
                             '2' => '2388',
                             '5' => '4788',
                             '9' => '900',
                            );

                            foreach ($total_ar as $key1 => $value1) {
                                if($key1==$package[0]['id']){
                                   $package[0]['price']=$value1;
                                }
                            }
                            $invoice['package'] = $package[0];
                            $invoicename='invoice-'.time(); 
                            // $invoice['package_detail'] = $package_details;
                            $newFile  = APPPATH."/../assets/Backend/$invoicename.pdf";
                            $view =  $this->load->view('Backend/invoice1_new',$invoice,true);
                            $this->load->library('m_pdf');
                            $this->m_pdf->pdf->WriteHTML($view);
                            // $mpdf->Output('example000.pdf', 'F');
                            $this->m_pdf->pdf->Output($newFile, 'F');
                            //$this->m_pdf->pdf->Output('/var/www/html/login/assets/Backend','invoice.pdf');


                            //send_email(trim($data['email']), 'reminder', $email_data);
                             // send_email(trim('vinayak@virtualdusk.com'), 'reminder', $email_data,$newFile); 
                             send_email(trim('info@cherrymenu.com'), 'reminder', $email_data,$newFile);
                             //unlink('/var/www/html/login/assets/Backend/invoice.pdf') ; 
                         }
                    }
                }
            }
        }

        $this->testfnrunfreetrial();
        exit;
    }
 
    ///////////////For Free Trial Package//////////////////////////////////////////////////////////////////////////////////////////
     public function package_free_trial_reminder()
    {
        $users = $this->Package_request->get_all_details(TBL_PACKAGE_DETAILS,['is_deleted' => 0,'flag' => 1,'package_id' => 3,'status' => 'activate'])->result_array();
        $filterrestid=array();
        if(!empty($users)){
            foreach ($users as $key => $row) { 
                     // echo "restaurant_id".$row['restaurant_id']." ".$row['created_at'];echo "<br>";
                     //  echo "restaurant_id".$row['restaurant_id']." ";
                      $rem1 = $this->addDayswithdate($row['created_at'],23); //echo "<br>";echo "<br>";
                      $rem2 = $this->addDayswithdate($row['created_at'],20);//echo "<br>";
                      $enddate = $this->addDayswithdate($row['created_at'],30);//echo "<br>";
                      $res_ar=array(date('Y-m-d',strtotime($rem1)),date('Y-m-d',strtotime($rem2)));
                      $currentdate= date('Y-m-d');//date('Y-m-d',strtotime($rem1)); 
                   
                if(!empty($res_ar)){
                    if(in_array($currentdate, $res_ar)  && !in_array($row['restaurant_id'], $filterrestid)){
                        $filterrestid[]=$row['restaurant_id'];  
                        $data = $this->users_model->get_user_detail(['id' => $row['restaurant_id'],'is_active' => 1,'is_deleted' => 0]); // Check valid restaurant or not?
                        $free_package = $this->Packages_model->get_package_detail(['is_deleted'=> 0,'type' => 'free','id' => $row['package_id']]); //check free package or not?
                        //below line to get packagename
                          $package_name =  $this->Packages_model->get_package_detail(['is_deleted'=> 0,'id' => $row['package_id']],'name'); //check free package or not?  echo "in loops";
                          $restaurant = $this->Package_request->get_all_details(TBL_USERS,['id'=> $row['restaurant_id'],'is_deleted' => 0])->result_array();
                          $package = $this->Package_request->get_all_details(TBL_PACKAGES,['id'=> $row['package_id'],'is_deleted' => 0])->result_array();

                        if(!empty($data) && !empty($free_package)) // &&  $row['restaurant_id']==118
                        {    
                            $update_array = array('is_active' => 0);
                            $email_data = ['name' => trim('Admin'), 'email' => trim($data['email']),'subject' => 'Cherrymenu Free Package Expiry Notification'];
                            $email_data['head_title'] = "Invoice attached for Restaurant- ".trim($data['name'])." and the package is  ".$package_name['name']." of Cherry Menu and will expire by ".date('d-m-Y',strtotime($enddate));
                           
                            
                            $email_data['message'] = '';//$message;

                              //generate invoices PDF
                            $invoice['restaurant'] = $restaurant[0]; 
                            $invoice['package'] = $package[0];
                            $invoicename='invoice-'.time(); 
                            // $invoice['package_detail'] = $package_details;
                            $newFile  = APPPATH."/../assets/Backend/$invoicename.pdf";
                            $view =  $this->load->view('Backend/invoice1',$invoice,true);
                            $this->load->library('m_pdf');
                            $this->m_pdf->pdf->WriteHTML($view);
                            // $mpdf->Output('example000.pdf', 'F');
                            $this->m_pdf->pdf->Output($newFile, 'F');
                            //$this->m_pdf->pdf->Output('/var/www/html/login/assets/Backend','invoice.pdf');

                         
                            //send_email(trim($data['email']), 'reminder', $email_data);
                            // send_email(trim('vinayak@virtualdusk.com'), 'reminder', $email_data,$newFile);
                            // unlink("/var/www/html/login/assets/Backend/$invoicename.pdf") ; 
                            send_email(trim('info@cherrymenu.com'), 'reminder', $email_data,$newFile);
                             //unlink('/var/www/html/login/assets/Backend/invoice.pdf') ; 
                         }
                    }
                }
            }
        }
        
    }
    // ------------------------------------------------------------------------------------------------------------------------------
     public function package_free_trial_expired()
    {
        $users = $this->Package_request->get_all_details(TBL_PACKAGE_DETAILS,['is_deleted' => 0,'flag' => 1,'package_id' => 3,'status' => 'activate'])->result_array();
        $filterrestid=array();
        if(!empty($users)){
            foreach ($users as $key => $row) { 
                     //echo "restaurant_id".$row['restaurant_id']." ".$row['end_date'];echo "<br>";
                      //echo "restaurant_id".$row['restaurant_id']." ".
                      $rem1 = $this->addDayswithdate($row['created_at'],23); //echo "<br>";echo "<br>";
                      $rem2 = $this->addDayswithdate($row['created_at'],20);//echo "<br>";
                      $enddate = $this->addDayswithdate($row['created_at'],30);//echo "<br>";
                      $res_ar=array(date('Y-m-d',strtotime($rem1)),date('Y-m-d',strtotime($rem2)));
                      $currentdate= date('Y-m-d');// date('Y-m-d',strtotime($rem1));
                   
                if(!empty($res_ar)){
                    if(in_array($currentdate, $res_ar)  && !in_array($row['restaurant_id'], $filterrestid)){
                        $filterrestid[]=$row['restaurant_id'];  
                        $data = $this->users_model->get_user_detail(['id' => $row['restaurant_id'],'is_active' => 1,'is_deleted' => 0]); // Check valid restaurant or not?
                        $free_package = $this->Packages_model->get_package_detail(['is_deleted'=> 0,'type' => 'free','id' => $row['package_id']]); //check free package or not?
                        //below line to get packagename
                          $package_name =  $this->Packages_model->get_package_detail(['is_deleted'=> 0,'id' => $row['package_id']],'name'); //check free package or not?  echo "in loops";
                          $restaurant = $this->Package_request->get_all_details(TBL_USERS,['id'=> $row['restaurant_id'],'is_deleted' => 0])->result_array();
                          $package = $this->Package_request->get_all_details(TBL_PACKAGES,['id'=> $row['package_id'],'is_deleted' => 0])->result_array();

                        if(!empty($data) && !empty($free_package)) // && $row['restaurant_id']=='118'
                        {    
                             $email_data = ['name' => trim($data['name']), 'email' => trim($data['email']),'subject' => 'Knock, Knock! Your Cherrymenu Free Trial is about to expire'];
                            $email_data['head_title'] = "Hope you’re enjoying your trial of Cherry Menu";
                            $email_data['message'] = "We would like to remind you that your free trial will expire on  <b>".date('d-m-Y',strtotime($enddate))."</b>";



                            $update_array = array('is_active' => 0);
                            /*$email_data = ['name' => trim($data['name']), 'email' => trim($data['email']),'subject' => 'Your '.$package_name['name'].' with Cherry Menu has expired!'];
                            $email_data['head_title'] = "Hope you’ve enjoyed your ".$package_name['name']." of Cherry Menu";
                            $message = "Your subscription to the ".$package_name['name']." will expire on ".date('d-m-Y',strtotime($row['end_date']));*/     
                            $email_data['message'].= '<br>';
                            $email_data['message'].='Kindly sign in to your dashboard to Subscribe for a plan to avoid any service interruption.';
                            $email_data['message'].= '<br>';
                            $email_data['message'].= 'If you need help, you can always contact our team on info@cherrymenu.com';
                            //$email_data['message'] = $message;

                              //generate invoices PDF
                            $invoice['restaurant'] = $restaurant[0]; 
                            $invoice['package'] = $package[0];
                            $invoicename='invoice-'.time(); 
                            // $invoice['package_detail'] = $package_details;
                            $newFile1  = APPPATH."/../assets/Backend/$invoicename.pdf";
                            $view =  $this->load->view('Backend/invoice1',$invoice,true);
                            $this->load->library('m_pdf');
                            $this->m_pdf->pdf->WriteHTML($view);
                            // $mpdf->Output('example000.pdf', 'F');
                            $this->m_pdf->pdf->Output($newFile1, 'F');
                            //$this->m_pdf->pdf->Output('/var/www/html/login/assets/Backend','invoice.pdf');


                            send_email(trim($data['email']), 'reminder', $email_data);
                            // send_email(trim('vinayak@virtualdusk.com'), 'reminder', $email_data,$newFile1);
                             // unlink("/var/www/html/login/assets/Backend/$invoicename.pdf") ; 
                         }
                    }
                }
            }
        }
        
    }
    // ------------------------------------------------------------------------------------------------------------------------------
    ///////////////For Free Trial Package//////////////////////////////////////////////////////////////////////////////////////////


    public function testfnrunfreetrial(){
        $this->package_free_trial_reminder();
        $this->package_free_trial_expired();
    }


    public function testdelete1(){
        //echo "testdelete";
        //$sql="SELECT * FROM `users` WHERE `email` LIKE '%dim-coin.com%'";
        //$sql="SELECT * FROM `users` WHERE name=' ' AND is_active=0 and is_deleted=0";
        //$sql="SELECT *  FROM `users` WHERE  `created_at` LIKE '%2020-12-21%'";
        //$sql="SELECT *  FROM `users` WHERE  `created_at` LIKE '%2020-12-21%' and is_active=1 and is_deleted=0 and name='staff1'";
        //$sql="SELECT * FROM `users` WHERE `created_at` LIKE '%2020-12-21%' and is_active=0 and is_deleted=0";
        //$sql="SELECT * FROM `users` WHERE `created_at` LIKE '%2020-12-20%' and is_active=0 and is_deleted=0";
        //$sql="SELECT * FROM `users` WHERE `created_at` LIKE '%2020-12-19%' and is_active=0 and is_deleted=0";
        //$sql="SELECT * FROM `users` WHERE `created_at` LIKE '%2020-05-11%' and is_active=0 and is_deleted=0";
        //$sql="SELECT * FROM `users` WHERE `created_at` LIKE '%2020-12-18%' and is_active=0 and is_deleted=0";
        //$sql="SELECT * FROM `users` WHERE `is_active` = 0 AND `is_deleted` = 0 AND `users_limit` = 0 AND `menus_limit` = 0 AND `categories_limit` = 0 AND `items_limit` = 0 AND `devices_limit` = 0 AND `order_feature` = 0";
        //$sql="SELECT * FROM `users` WHERE name=' ' and id!=1460";
        //$sql="SELECT * FROM `users` WHERE `is_active` = 1 AND `is_deleted` = 1 and email='tester@gmail.com'";
        //$sql="SELECT * FROM `users` WHERE `is_active` = 1 AND `is_deleted` = 1 and email='wocoyekile@spindl-e.com'";
        //$sql="SELECT * FROM `users` WHERE `is_active` = 1 AND `is_deleted` = 1 and restaurant_id is NULL";
        $sql="SELECT * FROM `users` WHERE `is_active` = 1 AND `is_deleted` = 1 AND restaurant_id in (40,121,136,311)";
        $res=$this->db->query($sql)->result_array();
        foreach ($res as   $value) {
             $sql1="delete from users where id='".$value['id']."'";
             $this->db->query($sql1);
             $sql11="delete from settings where user_id='".$value['id']."'";
           $this->db->query($sql11);
             $sql111="delete from users where restaurant_id='".$value['id']."'";
             $this->db->query($sql111);
             $sql1111="delete FROM `package_details` WHERE restaurant_id='".$value['id']."'";
             $this->db->query($sql1111);
            $sql2="select id from menus where restaurant_id='".$value['id']."'";
            //$res2=$this->db->query($sql2)->result_array();
           // echo "start";
           // echo "<pre>";
           // print_r($res2);
           //  echo "end";

            // foreach ($res2 as   $value2) {
            //     $menuids[]=$value2['id'];
            // $sql3="insert into delete_test_menuids(menuid)values('".$value2['id']."')";
            //  $this->db->query($sql3); 
            // }

          // die;
           //  foreach ($res2 as   $value1) {
           //  $sql3="SELECT * FROM `item_details` WHERE `menu_id` = '".$value['id']."'";
           //  $res3=$this->db->query($sql3)->result_array();
           //  $categoryids1[]=$res3['category_id'];
           //  $item_ids1[]=$res3['item_id'];

           //  $category_ids=array_unique($categoryids1);
           //  $item_ids=array_unique($item_ids1);

           //  echo "start";
           // echo "<pre>";
           // print_r($category_ids);
           // print_r($item_ids);
           //  echo "end";
           //  }



        }

         // echo "start";
         //   echo "<pre>";
         //   print_r($menuids);
         //    echo "end";echo "<br>";

            //echo $menuidd="'".implode("','", $menuids)."'";
   }



   public function testdelete2(){
    //echo "string"; die;
     //$sql="SELECT * FROM `delete_test_menuids` limit 100,200";
     $sql="SELECT m.id as idd,m.restaurant_id,u.id FROM `menus` m LEFT JOIN users u ON m.restaurant_id=u.id WHERE u.id is NULL ";
        $res=$this->db->query($sql)->result_array();
         //echo "<pre>";
            //print_r($res);//die;
            foreach ($res as   $value) {
             $menus_ids[]=$value['idd'];
            }
        $resval="'".implode("','", $menus_ids)."'"; echo "<br>";
            //echo $sql3=" SELECT * FROM `item_details` where menu_id IN ($resval)";  
            echo $sql3=" SELECT * FROM `new_items` where menu_id IN ($resval)";  
            //echo $sql3=" SELECT * FROM `new_categories` where menu_id IN ($resval)";  
            $res3=$this->db->query($sql3)->result_array();
            echo "<pre>";
            print_r($res3);
            if(isset($res3) && !empty($res3)){

            foreach ($res3 as  $value3) {
            $categoryids1[]=$value3['category_id'];
            $item_ids1[]=$value3['item_id'];
            }
        }



   //      foreach ($res as   $value) {
   //            $sql3="SELECT * FROM `item_details` WHERE `menu_id` = '".$value['idd']."'";
   //          $res3=$this->db->query($sql3)->result_array();
   //          // echo "<pre>";
   //          // print_r($res3);
   //          if(isset($res3) && !empty($res3)){

   //          foreach ($res3 as  $value3) {
   //          $categoryids1[]=$value3['category_id'];
   //          $item_ids1[]=$value3['item_id'];
   //          } 

            
   //      }
   // }

            if(isset($categoryids1) && !empty($categoryids1))
            {
              $category_ids=array_unique($categoryids1);
            }else{
              $category_ids=array();
            }
            if(isset($categoryids1) && !empty($categoryids1))
            {
                $item_ids=array_unique($item_ids1);
            }else{
                 $item_ids=array();
            }
             
            
    echo "start";
           echo "<pre>";
           print_r($category_ids);
           print_r($item_ids);
            echo "endddddddddddddddddddddddddddddddddddddd"; 


            $category_idsval="'".implode("','", $category_ids)."'"; echo "<br>";
            echo $sql4=" DELETE FROM `categories` where id IN ($category_idsval)"; echo "<br>";
               $this->db->query($sql4);
            
            $item_idsval="'".implode("','", $item_ids)."'";
            echo $sql5=" DELETE FROM `items` where id IN ($item_idsval)"; echo "<br>";
           $this->db->query($sql5);


            //echo $sql6=" DELETE FROM `item_details` where item_id IN ($item_idsval)"; echo "<br>";
            echo $sql6=" DELETE FROM `new_items` where item_id IN ($item_idsval)"; echo "<br>";
           $this->db->query($sql6); 




            /*$menuids=array();
            foreach ($category_ids as   $value4) {
            //if(in_array($value4, $menuids)){
               // $menuids[]=$value4; 
                //$sql4="insert into delete_test_catids(catid)values('".$value4."')";
                $sql4=" DELETE FROM `categories` where id='".$value4."'";
                $this->db->query($sql4);
            //} 
            }*/
            /*foreach ($item_ids as   $value5) {
                //$menuids[]=$value5; 
            //$sql5="insert into delete_test_itemids(itemids)values('".$value5."')";
            $sql5=" DELETE FROM `items` where id='".$value5."'";
             $this->db->query($sql5); 
           
            $sql6=" DELETE FROM `item_details` where item_id='".$value5."'";
             $this->db->query($sql6); 
            }*/

            echo "start";
           echo "<pre>";
           print_r($category_ids);
           print_r($item_ids);
            echo "end";
            echo "-----------------------------";
            echo "Categories count------------------------------".count($category_ids);echo "<br>";
            echo "Items count------------------------------".count($item_ids);echo "<br>";
            echo "-------------------------------------------------------------------";echo "<br>";
            echo "-------------------------------------------------------------------";echo "<br>";
            echo "-------------------------------------------------------------------";echo "<br>";
            echo "-------------------------------------------------------------------";echo "<br>";
            die;
   }


   public function testdelete3(){
    $sql="SELECT pd.restaurant_id as rid FROM `package_details` pd LEFT JOIN users u ON pd.restaurant_id=u.id WHERE u.id IS NULL";
        $res=$this->db->query($sql)->result_array();
        foreach ($res as   $value) {echo "<br>";
            echo $sql4="delete FROM `package_details` where  restaurant_id='".$value['rid']."'";
               $this->db->query($sql4);
   }
   }




    public function testdelete232(){
     $sql="SELECT m.id as idd,m.restaurant_id,u.id FROM `menus` m LEFT JOIN users u ON m.restaurant_id=u.id WHERE u.id is NULL ";
    // $sql="SELECT m.id,m.restaurant_id,u.id FROM `item_clicks` m LEFT JOIN users u ON m.restaurant_id=u.id WHERE u.id is NULL";
     //$sql="SELECT m.id,m.restaurant_id as idd,u.id FROM `category_clicks` m LEFT JOIN users u ON m.restaurant_id=u.id WHERE u.id is NULL";
        $res=$this->db->query($sql)->result_array();
            foreach ($res as   $value) {
             $menus_ids[]=$value['idd'];
            }
        $resval="'".implode("','", $menus_ids)."'"; echo "<br>";
            //echo $sql3=" SELECT * FROM `item_details` where menu_id IN ($resval)";  
           // echo $sql3=" SELECT * FROM `new_items` where menu_id IN ($resval)";  
            //echo $sql3=" SELECT * FROM `new_categories` where menu_id IN ($resval)";  
            //echo $sql3="SELECT * FROM `deleted_items` where menu_id IN ($resval)";  
            //echo $sql3="SELECT * FROM `category_details` where menu_id IN ($resval)";  
            echo $sql3="DELETE from `menus` where id IN ($resval)";  
            $res3=$this->db->query($sql3); 
            die;
           
            if(isset($res3) && !empty($res3)){ 
            foreach ($res3 as  $value3) {
            $categoryids1[]=$value3['category_id'];
            //$item_ids1[]=$value3['item_id'];
            }
        }


 

            if(isset($categoryids1) && !empty($categoryids1))
            {
              $category_ids=array_unique($categoryids1);
            }else{
              $category_ids=array();
            }
           /* if(isset($categoryids1) && !empty($categoryids1))
            {
                $item_ids=array_unique($item_ids1);
            }else{
                 $item_ids=array();
            }*/
             
            
    echo "start";
           echo "<pre>";
           print_r($category_ids);
          // print_r($item_ids);
            echo "endddddddddddddddddddddddddddddddddddddd"; 


            $category_idsval="'".implode("','", $category_ids)."'"; echo "<br>";
            echo $sql4=" DELETE FROM `categories` where id IN ($category_idsval)"; echo "<br>";
               $this->db->query($sql4);
            
         /*   $item_idsval="'".implode("','", $item_ids)."'";
            echo $sql5=" DELETE FROM `items` where id IN ($item_idsval)"; echo "<br>";
           $this->db->query($sql5);*/


            echo $sql6=" DELETE FROM `category_details` where category_id IN ($category_idsval)"; echo "<br>";
            //echo $sql6=" DELETE FROM `deleted_items` where item_id IN ($item_idsval)"; echo "<br>";
            //echo $sql6=" DELETE FROM `new_items` where item_id IN ($item_idsval)"; echo "<br>";
           $this->db->query($sql6);  
die;
           // echo $sql7=" DELETE FROM `item_details` where category_id IN ($category_idsval)"; echo "<br>";
           // $this->db->query($sql7); 




            /*$menuids=array();
            foreach ($category_ids as   $value4) {
            //if(in_array($value4, $menuids)){
               // $menuids[]=$value4; 
                //$sql4="insert into delete_test_catids(catid)values('".$value4."')";
                $sql4=" DELETE FROM `categories` where id='".$value4."'";
                $this->db->query($sql4);
            //} 
            }*/
            /*foreach ($item_ids as   $value5) {
                //$menuids[]=$value5; 
            //$sql5="insert into delete_test_itemids(itemids)values('".$value5."')";
            $sql5=" DELETE FROM `items` where id='".$value5."'";
             $this->db->query($sql5); 
           
            $sql6=" DELETE FROM `item_details` where item_id='".$value5."'";
             $this->db->query($sql6); 
            }*/

            echo "start";
           echo "<pre>";
           print_r($category_ids);
           print_r($item_ids);
            echo "end";
            echo "-----------------------------";
            echo "Categories count------------------------------".count($category_ids);echo "<br>";
            echo "Items count------------------------------".count($item_ids);echo "<br>";
            echo "-------------------------------------------------------------------";echo "<br>";
            echo "-------------------------------------------------------------------";echo "<br>";
            echo "-------------------------------------------------------------------";echo "<br>";
            echo "-------------------------------------------------------------------";echo "<br>";
            die;
   }


     

    public function test()
    {
        
        // $email_data = ['name' => 'simal', 'email' => 'sm@narola.email','subject' => 'Your trial has started!'];
        // $email_data['head_title'] = "Hope you’re enjoying your trial of Cherry Menu";
        // $email_data['message'] = "We would like to remind you that your trial will expire in  <b>2 days</b>";
        $email_data = ['name' => 'simal', 'type' => 'restaurant','email' => 'sm@narola.email', 'url' => 'url', 'subject' => 'Welcome! Please confirm your email'];
        $this->load->view('Backend/email_templates/new_restaurant',$email_data);
    }


    function defaultVal($ins_id=''){

         $userId=$ins_id;
                $directory_background = RESTAURANT_IMAGES . '/' . $userId. '/menus/backgrounds/';
                if (!file_exists(RESTAURANT_IMAGES . '/' . $userId. '/menus/')) {
                    mkdir(RESTAURANT_IMAGES . '/' . $userId. '/menus/');
                }
                if (!file_exists($directory_background)) {
                    mkdir($directory_background);
                }
            
                $file = '/var/www/html/login/public/bck.png';
                copy($file,'/var/www/html/login/'.$directory_background.'bck.png');
              //move_uploaded_file('/var/www/html/login/public/bck.png', $directory_background."bck.png");

       

         $menus = $this->Menus_model->get_menus_default('result');
         foreach ($menus as $key => $val) {
         
               $file = '/var/www/html/login/public/restaurants/505/menus/backgrounds/'.$val['background_image'];
                copy($file,'/var/www/html/login/'.$directory_background.''.$val['background_image']);

            $dataArr = array(
                  'restaurant_id' => $userId,
                 'title' => $val['title'],
                 'arabian_title' => $val['arabian_title'],
                'background_image' => $val['background_image'],
                'is_disable_feedback' =>$val['is_disable_feedback'],
               'is_active' =>$val['is_active'],
                );
          $dataArr['created_at'] = date('Y-m-d H:i:s');
          $menuId=$this->Menus_model->common_insert_update('insert', TBL_MENUS, $dataArr);
        }
          
       $categories = $this->Categories_model->get_categories_default('result');
     
     foreach($categories as $category){
          

            $id = $category['id'];
            $menu_ids = ($this->Categories_model->sql_select(TBL_CATEGORY_DETAILS, '*', ['where' => ['category_id' => $id]]));
           
                 $directory_background2 = RESTAURANT_IMAGES . '/' . $userId. '/categories/backgrounds/';
                 $directory_background3 = RESTAURANT_IMAGES . '/' . $userId. '/categories/';
                if (!file_exists(RESTAURANT_IMAGES . '/' . $userId. '/categories/')) {
                    mkdir(RESTAURANT_IMAGES . '/' . $userId. '/categories/');
                }
                if (!file_exists($directory_background2)) {
                    mkdir($directory_background2);
                }            
            
    
         $defaultId=505;

        $file1=RESTAURANT_IMAGES . '/' . $defaultId. '/categories/backgrounds/'.$category['background_image'];
       copy($file1,'/var/www/html/login/'.$directory_background2.''.$category['background_image']);

        $file2=RESTAURANT_IMAGES . '/' . $defaultId. '/categories/'.$category['image'];
      copy($file2,'/var/www/html/login/'.$directory_background3.''.$category['image']);

               $dataArr3 = array(
                    'title' => trim($category['title']),
                    'arabian_title' => trim($category['arabian_title']),
                    'background_image' =>$category['background_image'],
                    'image' => $category['image'],
                    'is_active' =>$category['is_active'],
                );
                $dataArr3['created_at'] = date('Y-m-d H:i:s');

             $inserted_id = $this->Categories_model->common_insert_update('insert', TBL_CATEGORIES, $dataArr3);

       
             $user_menus = $this->Menus_model->get_user_menus('result',$userId);

             //$default_menus = $this->Menus_model->get_user_menus('result',505);
            
             foreach($menu_ids as $user_menusId)
                {  
                   
                   $default_data=$this->Menus_model->get_default_menu(['id' => $user_menusId['menu_id'], 'is_deleted' => 0]);
               
                   if(!empty($default_data)){

                     foreach ($user_menus as  $user_menu) {
                       
                       if($default_data[0]['title']==$user_menu['title']){

                     $Menu_Id=$user_menu['id'];
                  $this->users_model->common_delete(TBL_CATEGORY_DETAILS, ['menu_id' => $Menu_Id,'category_id' => $inserted_id]);
                    $this->Categories_model->common_insert_update('insert', TBL_NEW_CATEGORY, array('menu_id' => $Menu_Id,'category_id' => $inserted_id,'created_at' =>  date('Y-m-d H:i:s')));
                    $this->Categories_model->common_insert_update('insert', TBL_CATEGORY_DETAILS, array('menu_id' => $Menu_Id,'category_id' => $inserted_id));
                    $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $Menu_Id]);



                       }
                     } 


                   }             
                                            
                 
                                            

                }

            
       }               
                     
               

                $this->item1($userId);    
        
     }


function item1($userId=''){

        $userId=$userId;
        $defaultId=505;
        $sql = "SELECT `i`.*
        FROM `item_details` `idls`
        LEFT JOIN `items` as `i` ON `idls`.`item_id`=`i`.`id`
        LEFT JOIN `categories` as `ca` ON `idls`.`category_id`=`ca`.`id`
        LEFT JOIN `menus` as `m` ON `idls`.`menu_id`=`m`.`id`
        WHERE `m`.`restaurant_id` = '505'
        AND `i`.`is_deleted` = 0
        AND `ca`.`is_deleted` = 0
        AND `m`.`is_deleted` = 0
        GROUP BY `i`.`id`
        ORDER BY `i`.`order` ASC
        LIMIT 50";
        $query=$this->db->query($sql);
        $results = $query->result_array();
        $userItem=[];

      foreach($results as $result){


          $userItem['title']=$result['title'];
          $userItem['arabian_title']=$result['arabian_title'];
          $userItem['price']=$result['price'];
          $userItem['calories']=$result['calories'];
          $userItem['description']=$result['description'];
          $userItem['arabian_description']=$result['arabian_description'];
          $userItem['type']=$result['type'];
          $userItem['time']=$result['time'];
          $userItem['is_featured']=$result['is_featured'];
          $userItem['is_dish_new']=$result['is_dish_new'];
          $userItem['created_at']=date('Y-m-d H:i:s');
          $inserted_id = $this->Items_model->common_insert_update('insert', TBL_ITEMS, $userItem);
       
        

          $itemId=$result['id'];
          $itemTitle=$result['title'];


        $itemImages=$this->Items_model->get_item_images_new($type = 'result',$itemId);
        if(!empty($itemImages)){
           
             foreach ($itemImages as  $itemImage) {

           $thumbnail = explode('.',$itemImage['image']);
            if ($thumbnail['1'] === 'jpg' || $thumbnail['1'] === 'jpeg' || $thumbnail['1'] === 'png') {
                 $thumbnailImg = $thumbnail['0'].'_thumb.'.$thumbnail['1'];
               
            }
                    
                    $directory = RESTAURANT_IMAGES . $userId. '/items/' .$inserted_id;
                    $thumbnailDirectory = RESTAURANT_IMAGES . $userId. '/items/' .$inserted_id.'/thumbnail';
                    if (!file_exists(RESTAURANT_IMAGES . $userId. '/items/')) {
                        mkdir(RESTAURANT_IMAGES . $userId. '/items/');
                    }


                     if (!file_exists($thumbnailDirectory)) {
                        mkdir($thumbnailDirectory,0777, true); 
                    }
                     if (!file_exists($directory)) {
                           mkdir($directory);
                      }

                 /*   $thumbnailDirectory ="/var/www/html/login/public/restaurants/$userId/items/$inserted_id/thumbnail";
                  if (!file_exists($thumbnailDirectory)) {
                        mkdir( $thumbnailDirectory, 0777, true);
                    }*/
                     $file2=RESTAURANT_IMAGES . '/' . $defaultId. '/items/'.$itemId.'/'.$itemImage['image'];
                    copy($file2,'/var/www/html/login/'.$directory.'/'.$itemImage['image']);
                    
                    $file3=RESTAURANT_IMAGES .'/'. $defaultId.'/items/'.$itemId.'/thumbnail/'.$thumbnailImg;
                    copy($file3,'/var/www/html/login/'.$thumbnailDirectory.'/'.$thumbnailImg);
                  

        $dataArrNew = array(
            'item_id' => $inserted_id,
            'image' => $itemImage['image'],
        );
        $dataArrNew['created_at'] = date('Y-m-d H:i:s');
         $this->Items_model->common_insert_update('insert', TBL_ITEM_IMAGES, $dataArrNew);
          
          }
        }          

          $demoItemsDetails=$this->Items_model->get_default_item(['item_id ' =>$itemId]);

          $demoCatIds=[];

           foreach ($demoItemsDetails as $key => $demoItemData) {
              $demoCatIds[]=$demoItemData['category_id'];
          
          }


                    foreach ($demoCatIds as $key => $demoCatId) {
           $catDetail=$this->Categories_model->get_category_detail(['id ' =>$demoCatId]);
           $demoCatTitle=$catDetail['title'];
           $user_categories =$this->Categories_model->get_categories_user_default($type = 'result',$userId);
           foreach ($user_categories as  $user_categor) {
            if( trim($demoCatTitle) == trim($user_categor['title']) ){
            	       $userCatId=$user_categor['id'];
            	       $Category_Id=$user_categor['id'];
            	       $user_cat_details =$this->Categories_model->get_category_details(['category_id ' =>$userCatId]);
                     foreach ($user_cat_details as  $user_cat_detail) {
                     $menus_ids = $this->Items_model->get_menu_ids_new($userCatId,$userId);
                      if(!empty($menus_ids))
                    {
                        foreach ($menus_ids as $key => $rows) 
                        {
                            //echo "in";
                            $this->Categories_model->common_delete(TBL_NEW_ITEMS, ['menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id]);
                            $this->Categories_model->common_insert_update('insert', TBL_NEW_ITEMS, array('menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id,'created_at'=>date('Y-m-d H:i:s')));
                            $this->Categories_model->common_insert_update('insert', TBL_ITEM_DETAILS, array('menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id));
                            $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $Category_Id]);
                            $menus = ($this->Items_model->sql_select(TBL_CATEGORY_DETAILS, 'menu_id', ['where' => ['category_id' => $Category_Id]])); //get menu Id's
                            if(!empty($menus))
                            {
                                foreach ($menus as $key => $value) 
                                {
                                    $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $value['menu_id']]);   
                                }
                            }
                        }
                        
                    }


                     }


                   }
              }

          }


      }


}
function item2($cat_Id='',$userId=''){

   $cat_Id=$cat_Id;
   $restaurant_id=$userId;
   $myArray = array(
             array(
                "title" => "Moroccan Tea - Three People",
                "arabian_title" =>"شاي مغربي (٣ اشخاص)",
                "price" => 40,
                "calories" => '',
                "description" => 'Tea, mint and sugar',
                "arabian_description" => "شاي مع نعناع مع سكر",
                "type" =>'',
                "time" =>'15-20',
                "is_featured"=>0,
                "is_dish_new"=>0,
                'created_at'=>date('Y-m-d H:i:s')
              ),
             array(

                "title" => "Turkish Tea",
                "arabian_title" =>"شاي تركي",
                "price" => 35,
                "calories" => '',
                "description" => "Quality Turkish tea served the traditional way",
                "arabian_description" => "شاي تركي جودة عالية مقدم بطريقة تقليدية",
                "type" =>'Veg',
                "time" =>'10',
                 "is_featured"=>0,
                 "is_dish_new"=>0,
                 'created_at'=>date('Y-m-d H:i:s')

          ),
           array(

                "title" => "Karak Tea",
                "arabian_title" =>"شاي كرك",
                "price" => 15,
                "calories" => '',
               "description" => "Milk based tea with clove and cardamom ",
               "arabian_description" => "شاي، ماء، زنجبيل، قرفة، قرفة وقرنفل",
                "type" =>'',
                "time" =>'10', 
                 "is_featured"=>0,
                 "is_dish_new"=>0,
                 'created_at'=>date('Y-m-d H:i:s')

          ) 
     

 
    );   
  
        foreach($myArray as $menudata){  
          $inserted_id = $this->Items_model->common_insert_update('insert', TBL_ITEMS, $menudata);
          $arrayName = array("Category_Id" =>$cat_Id );

              foreach($arrayName as $Category_Id)
                {                                       
                    $menus_ids = $this->Items_model->get_menu_ids_new($Category_Id,$restaurant_id);
            
                    if(!empty($menus_ids))
                    {
                        foreach ($menus_ids as $key => $rows) 
                        {
                            //echo "in";
                            $this->Categories_model->common_delete(TBL_NEW_ITEMS, ['menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id]);
                            $this->Categories_model->common_insert_update('insert', TBL_NEW_ITEMS, array('menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id,'created_at'=>date('Y-m-d H:i:s')));
                            $this->Categories_model->common_insert_update('insert', TBL_ITEM_DETAILS, array('menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id));
                            $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $Category_Id]);
                            $menus = ($this->Items_model->sql_select(TBL_CATEGORY_DETAILS, 'menu_id', ['where' => ['category_id' => $Category_Id]])); //get menu Id's
                            if(!empty($menus))
                            {
                                foreach ($menus as $key => $value) 
                                {
                                    $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $value['menu_id']]);   
                                }
                            }
                        }
                        
                    }
                }

        }       
                
   
}

function item3($cat_Id='',$userId=''){

   $cat_Id=$cat_Id;
   $restaurant_id=$userId;
   $myArray = array(
             array(
                "title" => "Affogato",
                "arabian_title" =>"أفوكاد",
                "price" => 30,
                "calories" => '',
                "description" => 'Espresso and Ice-cream',
                "arabian_description" => "الاسبريسو مع الايسكريم",
                "type" =>'',
                "time" =>'10',
                "is_featured"=>0,
                "is_dish_new"=>0,
                'created_at'=>date('Y-m-d H:i:s')
              ),
             array(

                "title" => "Iced Latte",
                "arabian_title" =>"الاتيه البارد",
                "price" => 25,
                "calories" => '',
                "description" => "Espresso, milk and ice",
                "arabian_description" => "اسبريسو، حليب وثلج",
                "time" =>'10',
                 "is_featured"=>1,
                 "is_dish_new"=>0,
                 'created_at'=>date('Y-m-d H:i:s')

          ),
           array(

                "title" => "Cold Mocha",
                "arabian_title" =>"موكا  بارد",
                "price" => 15,
                "calories" => '',
               "description" => "Milk based tea with clove and cardamom ",
               "arabian_description" => "شاي، ماء، زنجبيل، قرفة، قرفة وقرنفل",
                "type" =>'',
                "time" =>'25', 
                 "is_featured"=>0,
                 "is_dish_new"=>0,
                 'created_at'=>date('Y-m-d H:i:s')

          ) 
     

 
    );   
  
        foreach($myArray as $menudata){  
          $inserted_id = $this->Items_model->common_insert_update('insert', TBL_ITEMS, $menudata);
          $arrayName = array("Category_Id" =>$cat_Id );

              foreach($arrayName as $Category_Id)
                {                                       
                    $menus_ids = $this->Items_model->get_menu_ids_new($Category_Id,$restaurant_id);
            
                    if(!empty($menus_ids))
                    {
                        foreach ($menus_ids as $key => $rows) 
                        {
                            //echo "in";
                            $this->Categories_model->common_delete(TBL_NEW_ITEMS, ['menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id]);
                            $this->Categories_model->common_insert_update('insert', TBL_NEW_ITEMS, array('menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id,'created_at'=>date('Y-m-d H:i:s')));
                            $this->Categories_model->common_insert_update('insert', TBL_ITEM_DETAILS, array('menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id));
                            $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $Category_Id]);
                            $menus = ($this->Items_model->sql_select(TBL_CATEGORY_DETAILS, 'menu_id', ['where' => ['category_id' => $Category_Id]])); //get menu Id's
                            if(!empty($menus))
                            {
                                foreach ($menus as $key => $value) 
                                {
                                    $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $value['menu_id']]);   
                                }
                            }
                        }
                        
                    }
                }

        }       
                
   
}
function item4($cat_Id='',$userId=''){

   $cat_Id=$cat_Id;
   $restaurant_id=$userId;
   $myArray = array(
             array(
                "title" => "Pistachio Lamb Chop",
                "arabian_title" =>"شرائح لحم الضان مع الفستق الحلبي",
                "price" => 120,
                "calories" => '',
                "description" => '3 pieces of lamb chops served with mashed potato & grilled vegetable.',
                "arabian_description" => "٣ قطع من شرائح لحم الضان مع البطاطا المهروسة والخضروات المشوية",
                "type" =>'',
                "time" =>'15-20',
                "is_featured"=>1,
                "is_dish_new"=>1,
                'created_at'=>date('Y-m-d H:i:s')
              ),
             array(

                "title" => "Biryani Chicken",
                "arabian_title" =>"برياني الدجاج",
                "price" => 45,
                "calories" => '',
                "description" => "Chicken Biryani prepared the Arabic way served with Ratia sauce",
                "arabian_description" => "برياني دجاج محضر بالطريقة العربية مقدم مع لبن الراتيا",
                 "type" =>'Spicy',
                "time" =>'15-20',
                 "is_featured"=>1,
                 "is_dish_new"=>0,
                 'created_at'=>date('Y-m-d H:i:s')

          ),
           array(

                "title" => "Golden Chicken",
                "arabian_title" =>"الدجاج الذهبي",
                "price" => 45,
                "calories" => '',
               "description" => "A crispy coated chicken breast with gravy, vegetables and mashed potato",
               "arabian_description" => "صدر دجاج مقرمش مع الصلصة والخضار والبطاطاس المهروسة",
                "type" =>'',
                "time" =>'15-20', 
                 "is_featured"=>0,
                 "is_dish_new"=>0,
                 'created_at'=>date('Y-m-d H:i:s')

          ) 
     

 
    );   
  
        foreach($myArray as $menudata){  
          $inserted_id = $this->Items_model->common_insert_update('insert', TBL_ITEMS, $menudata);
          $arrayName = array("Category_Id" =>$cat_Id );

              foreach($arrayName as $Category_Id)
                {                                       
                    $menus_ids = $this->Items_model->get_menu_ids_new($Category_Id,$restaurant_id);
            
                    if(!empty($menus_ids))
                    {
                        foreach ($menus_ids as $key => $rows) 
                        {
                            //echo "in";
                            $this->Categories_model->common_delete(TBL_NEW_ITEMS, ['menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id]);
                            $this->Categories_model->common_insert_update('insert', TBL_NEW_ITEMS, array('menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id,'created_at'=>date('Y-m-d H:i:s')));
                            $this->Categories_model->common_insert_update('insert', TBL_ITEM_DETAILS, array('menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id));
                            $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $Category_Id]);
                            $menus = ($this->Items_model->sql_select(TBL_CATEGORY_DETAILS, 'menu_id', ['where' => ['category_id' => $Category_Id]])); //get menu Id's
                            if(!empty($menus))
                            {
                                foreach ($menus as $key => $value) 
                                {
                                    $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $value['menu_id']]);   
                                }
                            }
                        }
                        
                    }
                }

        }       
                
   
}



    function send_email_new($to = '', $template = '', $data = [],$attach = null)
{
    echo $to;
    if (empty($to) || empty($template) || empty($data)) {
        return false;
    }
    
    $this->load->library('email');

    $config['protocol'] = 'smtp';
    $config['smtp_host'] = 'ssl://smtp.gmail.com';
    $config['smtp_port'] = '465';
    $config['smtp_user'] = 'info@cherrymenu.com';//'pav.narola@gmail.com';
    $config['smtp_pass'] = 'Tony6@tony';//'narola21';
    $config['charset'] = 'utf-8';
    $config['newline'] = "\r\n";
    $config['mailtype'] = 'html';
    $config['validation'] = true;

    $this->email->initialize($config);

    $this->email->to($to);
    $this->email->from('no-reply@Cherrymenu.com', 'Cherrymenu');
    $this->email->subject($data['subject']);
    $view = $this->load->view('Backend/email_templates/' . $template, $data, true);
    $this->email->message($view);
    if($attach != null)
    {
        $this->email->attach($attach);
    }
    $this->email->send();
    print_r($this->email->print_debugger());die;
}




// ----------------------------------------------------------------------test-------------------------------------------------------------------------
 public function package_reminder_test()
    {
        $users = $this->Package_request->get_all_details(TBL_PACKAGE_DETAILS,['is_deleted' => 0,'flag' => 1])->result_array();
        $filterrestid=array();
        if(!empty($users)){
            foreach ($users as $key => $row) { 
                     //echo "restaurant_id".$row['restaurant_id']." ".$row['end_date'];echo "<br>";
                      //echo "restaurant_id".$row['restaurant_id']." ".
                        $rem1 = $this->addDayswithdate1($row['end_date'],7); //echo "<br>";echo "<br>";
                      $rem2 = $this->addDayswithdate1($row['end_date'],30);//echo "<br>";
                      $res_ar=array(date('Y-m-d'));//array(date('Y-m-d',strtotime($rem1)),date('Y-m-d',strtotime($rem2)));
                      $currentdate=date('Y-m-d');//date('Y-m-d',strtotime($rem1));
                   
                if(!empty($res_ar)){
                    if(in_array($currentdate, $res_ar)  && !in_array($row['restaurant_id'], $filterrestid)){
                        $filterrestid[]=$row['restaurant_id'];  
                        $data = $this->users_model->get_user_detail(['id' => $row['restaurant_id'],'is_active' => 1,'is_deleted' => 0]); // Check valid restaurant or not?
                        $free_package = $this->Packages_model->get_package_detail1(['is_deleted'=> 0,'flag' => 1,'restaurant_id' => $row['restaurant_id']]); //check free package or not?
                        //below line to get packagename
                          $package_name =  $this->Packages_model->get_package_detail(['is_deleted'=> 0,'id' => $row['package_id']],'name'); //check free package or not?  echo "in loops";
                          $restaurant = $this->Package_request->get_all_details(TBL_USERS,['id'=> $row['restaurant_id'],'is_deleted' => 0])->result_array();
                          $package = $this->Package_request->get_all_details(TBL_PACKAGES,['id'=> $row['package_id'],'is_deleted' => 0])->result_array();

                        if(!empty($data) && !empty($free_package) )
                        {    
                            $update_array = array('is_active' => 0);
                            $email_data = ['name' => trim('Admin'), 'email' => trim($data['email']),'subject' => 'Cherrymenu Package Expiry Notification'];
                            $email_data['head_title'] = "Invoice attached for Restaurant- ".trim($data['name'])." and the package is  ".$package_name['name']." of Cherry Menu and will expire by ".date('d-m-Y',strtotime($row['end_date']));
                           
                            
                            $email_data['message'] = '';//$message;

                              //generate invoices PDF
                            $invoice['restaurant'] = $restaurant[0]; 
                            $total_ar = array(
                             '1' => '1428',
                             '2' => '2388',
                             '5' => '4788',
                             '9' => '900',
                            );

                            foreach ($total_ar as $key1 => $value1) {
                                if($key1==$package[0]['id']){
                                   $package[0]['price']=$value1;
                                }
                            }
                            $invoice['package'] = $package[0];
                           

                            echo "after";
                            echo "<pre>";
                            print_r($package[0]);


                            $invoicename='invoice-'.time(); 
                            // $invoice['package_detail'] = $package_details;
                            $newFile  = APPPATH."/../assets/Backend/$invoicename.pdf";
                            $view =  $this->load->view('Backend/invoice1_new',$invoice,true);
                           $this->load->library('m_pdf');
                           $this->m_pdf->pdf->WriteHTML($view);
                            // $mpdf->Output('example000.pdf', 'F');
                            $this->m_pdf->pdf->Output($newFile, 'F');
                            //$this->m_pdf->pdf->Output('/var/www/html/login/assets/Backend','invoice.pdf');
                            

                            //send_email(trim($data['email']), 'reminder', $email_data);
                          // send_email(trim('vinayak@virtualdusk.com'), 'reminder', $email_data,$newFile);
                          die;
                             //send_email(trim('info@cherrymenu.com'), 'reminder', $email_data,$newFile);
                             //unlink('/var/www/html/login/assets/Backend/invoice.pdf') ; 
                         }
                    }
                }
            }
        }
 
    }
// ----------------------------------------------------------------------test-------------------------------------------------------------------------
}
