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
 
    public function items(){
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
     $this->load->view('CustomMenu/restaurantstest2', $data);  
         }
   }




   // Test Code Functions


   public function test123(){
    print file_get_contents('https://www.cherrymenu.com/login/v1/Bawarchi_Gregfgen');
  } 

  }
