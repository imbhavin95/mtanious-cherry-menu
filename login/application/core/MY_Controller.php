<?php

/**
 * For default operation
 * @author KU
 * */
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public $is_loggedin = false;
    public $restaurant_id;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('encryption');
        $session = $this->session->userdata('login_user');
        if (!empty($session['id']) && !empty($session['email'])) {
            $this->is_loggedin = true;
            $loginuser = $this->users_model->get_user_detail(['id' =>$this->session->userdata('login_user')['id']]);
            if($loginuser['is_deleted'])
            {
                $this->is_loggedin = false;
                $this->session->unset_userdata('logged_in');
                $this->session->set_flashdata('logout', true);
                $this->session->sess_destroy();
                delete_cookie(REMEMBER_ME_COOKIE_NAME);
                redirect(base_url(), 'refresh');
            }
        } else {
            $encoded_email = get_cookie(REMEMBER_ME_COOKIE_NAME);
            $email = $this->encryption->decrypt($encoded_email);
            if (!empty($email)) {
                $user = $this->users_model->get_user_detail(['email' => $email]);
                if (!empty($user)) {
                    $this->session->set_userdata('login_user', $user);
                    $this->is_loggedin = true;
                }
            }
        }
        $this->controller = $this->router->fetch_class();
        $this->action = $this->router->fetch_method();
        //-- If not logged in and try to access inner pages then redirect user to login page
        if (!$this->is_loggedin) {
            if (strtolower($this->controller) != 'login') {
                $redirect = base_url(uri_string());
                redirect(base_url() . 'login');
            }
        } else { //-- If logged in and access login page the redirect user to home page
            if (strtolower($this->controller) == 'login' && strtolower($this->action) != 'logout') {
                
                if(is_admin())
                {
                    redirect(base_url() .'admin/Home');
                }else{
                    redirect(base_url() .'restaurant/Home');
                }
            }
        }
        /*Below code is commented*/
        /*$this->total_restaurant = ($this->users_model->sql_select(TBL_USERS, 'id', ['where' => ['is_deleted' => 0, 'role' => RESTAURANT]], ['count' => true]));
         $this->total_users = ($this->db->query("SELECT u1.id FROM ".TBL_USERS." u1, ".TBL_USERS." u2 where u1.role IN('staff','sub_admin') AND u1.is_deleted = 0 AND u2.id=u1.restaurant_id AND u2.is_deleted = 0")->num_rows());
        $this->total_categories = ($this->db->query("SELECT DISTINCT cd.category_id FROM ".TBL_USERS." u,".TBL_MENUS." m, ".TBL_CATEGORIES." c,".TBL_CATEGORY_DETAILS." cd WHERE u.id = m.restaurant_id AND m.id = cd.menu_id AND c.id = cd.category_id AND u.is_deleted = 0 AND m.is_deleted = 0 AND c.is_deleted = 0")->num_rows());
        $this->total_items = ($this->db->query("SELECT DISTINCT item_d.item_id FROM ".TBL_ITEMS." i, ".TBL_ITEM_DETAILS." item_d, ".TBL_USERS." u,".TBL_MENUS." m, ".TBL_CATEGORIES." c WHERE u.id = m.restaurant_id AND m.id = item_d.menu_id AND i.id = item_d.item_id AND c.id = item_d.category_id AND u.is_deleted = 0 AND m.is_deleted = 0 AND c.is_deleted = 0 AND i.is_deleted = 0")->num_rows());*/
        /*Below code is commented*/


        $this->total_users=($this->db->query("SELECT * from totalnoofusers")->num_rows());
       $this->total_restaurant=($this->db->query("SELECT * from restaurantlist ")->num_rows());
       $this->total_categories=($this->db->query("SELECT * from totalnoofcategories ")->num_rows());
       $this->total_items=($this->db->query("SELECT * from totalnoofitems ")->num_rows());


        if ($this->is_loggedin)
        {
            $user = $this->users_model->get_user_detail(['id' =>$this->session->userdata('login_user')['id']]);
            $this->session->set_userdata('login_user', $user);
        }
    }
    
    /**
     * Check Free Trial Registration
     * */
    public function check_free_pacakge()
    {
        $free_package = $this->Packages_model->get_package_detail(['is_deleted'=> 0,'type' => 'free','is_active' => 1]);
        if(!empty($free_package))
        {
           $data =  $this->Package_request->get_detail(['restaurant_id' => $this->session->userdata('login_user')['id'],'status' => 'activate' , 'package_id' => $free_package['id'],'flag' =>'1'], '*');
           if(!empty($data))
           {
               return $data;
           }else
           {
               return NULL;
           }
        }
        return NULL;
    }

    /**
    * Check Login user is restauran or sub-admin
    * */
    public function check_login_user()
    {
        if(is_sub_admin())
        {
            $this->restaurant_id = $this->session->userdata('login_user')['restaurant_id'];
            $user = $this->Users_model->get_user_detail(['is_deleted' => 0,'is_active' => 1,'id' => $this->restaurant_id]);
            $_SESSION['login_user']['users_limit'] = $user['users_limit']; 
            $_SESSION['login_user']['menus_limit'] = $user['menus_limit']; 
            $_SESSION['login_user']['categories_limit'] = $user['categories_limit']; 
            $_SESSION['login_user']['items_limit'] = $user['items_limit']; 
            $_SESSION['login_user']['devices_limit'] = $user['devices_limit']; 
        }
        else
        {
            $this->restaurant_id = $this->session->userdata('login_user')['id'];
        }
    }

    //Count total Categories 
    public function ListCategories($id = null,$option)
    {
        if(!empty($id))
        {
            $this->db->select('ca.*');
            $this->db->join(TBL_MENUS . ' as m', 'cd.menu_id=m.id', 'left');
            $this->db->join(TBL_CATEGORIES . ' as ca', 'cd.category_id=ca.id', 'left');
            $this->db->where(array('ca.is_deleted' => 0,'m.is_deleted' => 0));
            $this->db->where_in('cd.menu_id', $id);
            $this->db->group_by('ca.id');
            if($option == 'result')
            {
                $categories = $this->db->get(TBL_CATEGORY_DETAILS.' cd')->result_array();
            }
            else
            {
                $categories = $this->db->get(TBL_CATEGORY_DETAILS.' cd')->num_rows();
            }
            return $categories;
        }
        else
        {
            return null;
        }
    }
    
    //Count total Items 
    public function ListItems($id = null,$option)
    {
        if(!empty($id))
        {
            $this->db->select('i.*');
            $this->db->join(TBL_ITEMS . ' as i', 'item_d.item_id=i.id', 'left');
            $this->db->join(TBL_CATEGORIES . ' as ca', 'item_d.category_id=ca.id', 'left');
            $this->db->where(array('i.is_deleted' => 0,'ca.is_deleted' => 0));
            $this->db->where_in('item_d.category_id', $id);
            $this->db->group_by('i.id');
            if($option == 'result')
            {
                $categories = $this->db->get(TBL_ITEM_DETAILS.' item_d')->result_array();
            }
            else
            {
                $categories = $this->db->get(TBL_ITEM_DETAILS.' item_d')->num_rows();
            }
            return $categories;
        }
        else
        {
            return null;
        }
    }
}
