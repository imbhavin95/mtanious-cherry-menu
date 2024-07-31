<?php
defined('BASEPATH') or exit('No direct script access allowed');

class V1 extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
        $this->load->helper('string');
        $this->load->library('Form_validation');
        $this->load->model('Packages_model');
        $this->load->model('Package_request');
        $this->load->model('Items_model');
        $this->load->model('Users_model');
        $this->load->model('Settings_model');
    }


    public function index(){
        $aa=urldecode($this->uri->segment('2'));
        $b= explode('-',$aa);
          
        $a=$b[0];
        $sesurl="https://www.cherrymenu.com/login/v1/".$b[0];
        $_SESSION['sesurl']=$sesurl;
        $res = $this->Settings_model->get_settings_detail(['rest_name' =>  $a, 'is_deleted' => 0, 'is_active' => 1]);
        // $where=array('name'=>$a);
        // $res=$this->Users_model->get_user_id($where,'');
         // print_r($res);
        if(!$res){
          $this->load->view('CustomMenu/404');
        }else{

            $restid=$res['user_id'];

          $query="SELECT * FROM `package_details` where DATE_ADD(created_at, INTERVAL 30 DAY)>=CURRENT_TIMESTAMP and `restaurant_id` = '".$restid."' and status='activate' and package_id='3' and flag=1";
                    $resultdata=$this->users_model->run_manual_query($query);
                    if(!$resultdata){
              $query1="SELECT * FROM `package_details` where  `restaurant_id` = '".$restid."' and status!='activate' and package_id!='3' or `restaurant_id` = '".$restid."' and status='activate' and package_id!='3' and date(now())>date(end_date)  and flag=1";
                        $resultdata1=$this->users_model->run_manual_query($query1);

                        $query2="SELECT * FROM `package_details` where  `restaurant_id` = '".$restid."' and status='activate' and package_id!='3' and date(now())<=date(end_date) and flag=1";
                        $resultdata2=$this->users_model->run_manual_query($query2);

                        $query3="SELECT * FROM `package_details` where  `restaurant_id` = '".$restid."' and status!='activate' and package_id='3'";
                        $resultdata3=$this->users_model->run_manual_query($query3);

                          $query4="SELECT * FROM `package_details` where  `restaurant_id` = '".$restid."' and status='activate' and package_id='3' and DATE_ADD(created_at, INTERVAL 30 DAY)<CURRENT_TIMESTAMP";
                        $resultdata4=$this->users_model->run_manual_query($query4);
                        
                        
                        if($resultdata2)
                        { 

                   /*       $data = array(
                        'id' => $result['id'],
                        'name' => $result['name'],
                        'email' => $result['email'],
                        'role' => $result['role'],
                        'token' => $token,
                        'order_feature' => $order_feature,
                    );
                    $this->response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code*/

                                            $userdata = $this->Users_model->get_user_detail(['id' => $restid] , 'name,image');
                              $data['rest_image']=$res['logo'];
                              $data['restid']=$restid;
                              $data['currency']=$res['currency'];
                              $data['item_data']=@$this->Items_model->get_items_bycategory($restid);
                              $data['main_langid']=@$b[1];
                              if(!$data['rest_image'] || !$data['restid'] || !$data['item_data']){
                                $this->load->view('CustomMenu/custom_message'); 
                               }else{
                                  $this->load->view('CustomMenu/restaurantstest1', $data);
                               }

                        }else
                        if($resultdata1 && !$resultdata2)
                        { 
                           /* $this->set_response([
                            'status' => false,
                            'message' => 'The Plan is expired! Contact the Administrator to Renew the Plan',
                        ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
*/                      $data['message']='Your Subscription has expired. Kindly contact our team to renew your plan.'; 
                        $this->load->view('CustomMenu/custom_message1',$data); 
                        }else
                        if(($resultdata3 || $resultdata4) && !$resultdata2)
                        {   $data['message']='Your Free Plan has expired. kindly contact our team to subscribe to a plan.'; 
                           $this->load->view('CustomMenu/custom_message1',$data); 
                          /*$this->set_response([
                            'status' => false,
                            'message' => 'The Free Plan is expired! Contact the Administrator to Renew the Plan',
                        ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code*/

                        } 
                        }else{
                                        $userdata = $this->Users_model->get_user_detail(['id' => $restid] , 'name,image');
                              $data['rest_image']=$res['logo'];
                              $data['restid']=$restid;
                              $data['currency']=$res['currency'];
                              $data['item_data']=$this->Items_model->get_items_bycategory($restid);
                              $data['main_langid']=@$b[1];
                              if(!$data['rest_image'] || !$data['restid'] || !$data['item_data']){
                                $this->load->view('CustomMenu/custom_message'); 
                               }else{
                                  $this->load->view('CustomMenu/restaurantstest1', $data);
                               }
                        } 



        
      
        
    }
    }
 
    public function items(){
        $catid=$this->input->get('cid');
         $restid=$this->input->get('rid');
         $itid=$this->input->get('itid');
         $sid=$this->input->get('sid');
        $data['singleitem']=$this->Items_model->single_item_detail($itid);
        $data['restid']=$restid;
        $data['cat_id']=$catid;
        $data['sid']=$sid;
         $userdata = $this->Users_model->get_user_detail(['id' => $restid] , 'name,image');
        $data['rest_name']=$userdata['name'];
        //$data['currency']=$userdata['currency'];
        $res = $this->Settings_model->get_settings_detail(['user_id' =>  $restid, 'is_deleted' => 0, 'is_active' => 1]);
        $data['currency']=$res['currency'];
        $data['rest_image']=$res['logo'];
        $data['sesurl']="https://www.cherrymenu.com/".$res['rest_name'];
        $data['menu_name'] = $this->Items_model->get_menu_name($catid);
        if(!$data['menu_name'] || !$userdata || !$data['singleitem']){
          $this->load->view('CustomMenu/404'); 
         }else{
        $item_data2=$this->Items_model->get_items_bycat($catid);
        $item_data=$item_data2['new'];
        foreach ($item_data as $key => $value) {
            if($value['id']==$itid){
                unset($item_data[$key]);
             }
        } $i=0;
        foreach ($item_data as $key => $value) {
            if($i>2){
               unset($item_data[$key]);
            }
            $i++; 
        }
        // echo "<pre>";
        // print_r($item_data);
        // echo "<pre>";
        // print_r($data['singleitem']);
        $data['item_data']=$item_data;
     $this->load->view('CustomMenu/restaurantstest2', $data);  
         }
   }

   // Test Code Functions



public function test(){
	// $_COOKIE['langid'];
          $a=urldecode($this->uri->segment('3'));//exit;
        $sesurl="https://www.cherrymenu.com/login/v1/".$a;
        $_SESSION['sesurl']=$sesurl;
        $res = $this->Settings_model->get_settings_detail(['rest_name' =>  $a, 'is_deleted' => 0, 'is_active' => 1]);
        // $where=array('name'=>$a);
        // $res=$this->Users_model->get_user_id($where,'');
        // print_r($res);
        if(!$res){
          $this->load->view('CustomMenu/404');
        }else{
        $restid=$res['user_id'];
        $userdata = $this->Users_model->get_user_detail(['id' => $restid] , 'name,image');
        $data['rest_image']=$res['logo'];
        $data['restid']=$restid;
        $data['currency']=$res['currency'];
        $data['item_data']=$this->Items_model->get_items_bycategory($restid);
        if(!$data['rest_image'] || !$data['restid'] || !$data['item_data']){
          $this->load->view('CustomMenu/custom_message'); 
         }else{
            $this->load->view('CustomMenu/restaurantstest1', $data);
         }
        
    }
    } 
   public function testitems(){
        $catid=$this->input->get('cid');
         $restid=$this->input->get('rid');
         $itid=$this->input->get('itid');
        $data['singleitem']=$this->Items_model->single_item_detail($itid);
        $data['restid']=$restid;
        $data['cat_id']=$catid;
         $userdata = $this->Users_model->get_user_detail(['id' => $restid] , 'name,image');
        $data['rest_name']=$userdata['name'];
        //$data['currency']=$userdata['currency'];
        $res = $this->Settings_model->get_settings_detail(['user_id' =>  $restid, 'is_deleted' => 0, 'is_active' => 1]);
        $data['currency']=$res['currency'];
        $data['rest_image']=$res['logo'];
        $data['sesurl']="https://www.cherrymenu.com/".$res['rest_name'];
        $data['menu_name'] = $this->Items_model->get_menu_name($catid);
        if(!$data['menu_name'] || !$userdata || !$data['singleitem']){
          $this->load->view('CustomMenu/404'); 
         }else{
        $item_data2=$this->Items_model->get_items_bycat($catid);
        $item_data=$item_data2['new'];
        foreach ($item_data as $key => $value) {
            if($value['id']==$itid){
                unset($item_data[$key]);
             }
        } $i=0;
        foreach ($item_data as $key => $value) {
            if($i>2){
               unset($item_data[$key]);
            }
            $i++; 
        }
        $data['item_data']=$item_data;
     $this->load->view('CustomMenu/webmenu2', $data);  
         }
   }




   // Test Code Functions


   public function test123(){
    print file_get_contents('https://www.cherrymenu.com/login/v1/Bawarchi_Gregfgen');
  } 


  public function test2121(){
    echo "string";
  }


  public function getdirname(){
  /*  echo "gghghghgh";
    $directories = glob('/var/www/html/login/public/restaurants' . '/*' , GLOB_ONLYDIR);
    echo "<pre>";
    print_r($directories);
    foreach ($directories as $key => $value) {
     $ar=explode('/', $value);
    //  echo "<pre>";
    // print_r($ar);
     echo $mainvalue=$ar[7];echo "<br>";
     $sql="insert into testtb(restid)values('$mainvalue')";
     $res=$this->db->query($sql);

    }*/

  /*  $sql="SELECT m.tid as idd,m.restid,u.id FROM `testtb` m LEFT JOIN users u ON m.restid=u.id WHERE u.id is NULL order by u.id";
        $res=$this->db->query($sql)->result_array();
         //echo "<pre>";
            //print_r($res);//die;
            foreach ($res as   $value) {
             $mainvalue=$value['restid'];
             $sql="insert into testtb1(restid)values('$mainvalue')";
     $res=$this->db->query($sql);
            }
  }*/
  // $dirname='/var/www/html/login/public/restaurants/'
  // array_map('unlink', glob("$dirname/*.*"));

  }

      public function testfn_unamed(){
        $aa=urldecode($this->uri->segment('3'));
        $b= explode('-',$aa);
          
        $a=$b[0];
        $sesurl="https://www.cherrymenu.com/login/v1/".$b[0];
        $_SESSION['sesurl']=$sesurl;
        $res = $this->Settings_model->get_settings_detail(['rest_name' =>  $a, 'is_deleted' => 0, 'is_active' => 1]);
        // $where=array('name'=>$a);
        // $res=$this->Users_model->get_user_id($where,'');
         // print_r($res);
        if(!$res){
          $this->load->view('CustomMenu/404');
        }else{

            $restid=$res['user_id'];

          $query="SELECT * FROM `package_details` where DATE_ADD(created_at, INTERVAL 30 DAY)>=CURRENT_TIMESTAMP and `restaurant_id` = '".$restid."' and status='activate' and package_id='3' and flag=1";
                    $resultdata=$this->users_model->run_manual_query($query);
                    if(!$resultdata){
              $query1="SELECT * FROM `package_details` where  `restaurant_id` = '".$restid."' and status!='activate' and package_id!='3' or `restaurant_id` = '".$restid."' and status='activate' and package_id!='3' and date(now())>date(end_date)  and flag=1";
                        $resultdata1=$this->users_model->run_manual_query($query1);

                        $query2="SELECT * FROM `package_details` where  `restaurant_id` = '".$restid."' and status='activate' and package_id!='3' and date(now())<=date(end_date) and flag=1";
                        $resultdata2=$this->users_model->run_manual_query($query2);

                        $query3="SELECT * FROM `package_details` where  `restaurant_id` = '".$restid."' and status!='activate' and package_id='3'";
                        $resultdata3=$this->users_model->run_manual_query($query3);

                          $query4="SELECT * FROM `package_details` where  `restaurant_id` = '".$restid."' and status='activate' and package_id='3' and DATE_ADD(created_at, INTERVAL 30 DAY)<CURRENT_TIMESTAMP";
                        $resultdata4=$this->users_model->run_manual_query($query4);
                        
                        
                        if($resultdata2)
                        { 

                   /*       $data = array(
                        'id' => $result['id'],
                        'name' => $result['name'],
                        'email' => $result['email'],
                        'role' => $result['role'],
                        'token' => $token,
                        'order_feature' => $order_feature,
                    );
                    $this->response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code*/

                                            $userdata = $this->Users_model->get_user_detail(['id' => $restid] , 'name,image');
                              $data['rest_image']=$res['logo'];
                              $data['restid']=$restid;
                              $data['currency']=$res['currency'];
                              $data['item_data']=@$this->Items_model->get_items_bycategory($restid);
                              $data['main_langid']=@$b[1];
                              if(!$data['rest_image'] || !$data['restid'] || !$data['item_data']){
                                $this->load->view('CustomMenu/custom_message'); 
                               }else{
                                  $this->load->view('CustomMenu/webmenu1', $data);
                               }

                        }else
                        if($resultdata1 && !$resultdata2)
                        { 
                           /* $this->set_response([
                            'status' => false,
                            'message' => 'The Plan is expired! Contact the Administrator to Renew the Plan',
                        ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
*/                      $data['message']='Your Subscription has expired. Kindly contact our team to renew your plan.'; 
                        $this->load->view('CustomMenu/custom_message1',$data); 
                        }else
                        if(($resultdata3 || $resultdata4) && !$resultdata2)
                        {   $data['message']='Your Free Plan has expired. kindly contact our team to subscribe to a plan.'; 
                           $this->load->view('CustomMenu/custom_message1',$data); 
                          /*$this->set_response([
                            'status' => false,
                            'message' => 'The Free Plan is expired! Contact the Administrator to Renew the Plan',
                        ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code*/

                        } 
                        }else{
                                        $userdata = $this->Users_model->get_user_detail(['id' => $restid] , 'name,image');
                              $data['rest_image']=$res['logo'];
                              $data['restid']=$restid;
                              $data['currency']=$res['currency'];
                              $data['item_data']=$this->Items_model->get_items_bycategory($restid);
                              $data['main_langid']=@$b[1];
                              if(!$data['rest_image'] || !$data['restid'] || !$data['item_data']){
                                $this->load->view('CustomMenu/custom_message'); 
                               }else{
                                  $this->load->view('CustomMenu/webmenu1', $data);
                               }
                        } 



        
      
        
    }
    }



}
