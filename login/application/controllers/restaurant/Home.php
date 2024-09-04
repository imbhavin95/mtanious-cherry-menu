<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model');
        $this->load->model('Settings_model');
    }

    public function index()
    {
        if(is_sub_admin())
        {
            $this->session->set_flashdata('error', 'Access denied!');
            redirect('restaurant/menus');
        }
        $data['title'] = WEBNAME.' | Dashboard';
        $data['head'] = 'Dashboard';
        $data['total_users'] = ($this->users_model->sql_select(TBL_USERS, 'id', ['where' => ['is_deleted' => 0,'restaurant_id' => $this->session->userdata('login_user')['id']]], ['count' => true]));
        $data['total_menus'] = ($this->users_model->sql_select(TBL_MENUS, 'id', ['where' => ['is_deleted' => 0,'restaurant_id' => $this->session->userdata('login_user')['id']]], ['count' => true]));
        $menus = ($this->users_model->sql_select(TBL_MENUS, 'id', ['where' => ['is_deleted' => 0,'restaurant_id' => $this->session->userdata('login_user')['id']]]));
        $menu = array();
        $data['total_categories'] = $data['categories'] = $data['total_items'] = $data['items'] = $data['type'] = 0;
        $res = $this->Settings_model->get_settings_detail(['user_id' => $this->session->userdata('login_user')['id'], 'is_deleted' => 0, 'is_active' => 1]);
        if(isset($res['rest_name']) && !empty($res['rest_name'])){
         $data['sesurl']="https://app.cherrymenu.com/".$res['rest_name'];
        }else{
            $data['sesurl']="";
        }
        
        $category = array();
        if(!empty($menus))
        {
            foreach($menus as $key => $row)
            {
                $menu[$key] = $row['id'];
            }
            //$data['total_categories'] = ($this->users_model->sql_select(TBL_CATEGORIES, 'id', ['where' => ['is_deleted' => 0],'where_in' => ['menu_id' => $menu]], ['count' => true]));
            $data['total_categories'] = $this->ListCategories($menu,'count');
            $data['categories'] = $this->ListCategories($menu,'result');
            //$data['categories'] = ($this->users_model->sql_select(TBL_CATEGORIES, '*', ['where' => ['is_deleted' => 0],'where_in' => ['menu_id' => $menu]]));
            
            if(!empty($data['categories']))
            {
                foreach($data['categories'] as $key => $row)
                {
                    $category[$key] = $row['id'];
                }
                $data['total_items'] = $this->ListItems($category,'count');
                //$data['items'] = $this->ListItems($category,'result');
            } 
        }

        //-- Chart data
        $date = $this->input->get('date');
        $date_array = array();
        $date_string = '';
        $event_arr = array();
        //-- By default take current months start and ending date
        $start_date = date('Y-m-01'); // hard-coded '01' for first day
        $end_date = date('Y-m-t');

        if ($date != '') {
            $dates = explode('-', $date);
            $start_date = $dates[0];
            $end_date = $dates[1];
        }

        //Items id of login restaurant.
        // $items_id = $all_items = array();
        // if(!empty($data['items']))
        // {
        //     foreach($data['items'] as $key => $row)
        //     {
        //         $items_id[$key] = $row['id'];
        //         $all_items = $this->users_model->sql_select(TBL_ITEM_CLICKS,'*',array('item_id' => $row['id'] ,'is_deleted' => 0));
        //     }
        // }

        $key_arrays = array();
            
        $json_data['items_click'] = NULL;
        
        $json_data = array(
           // 'items_click' => $this->users_model->sql_select(TBL_ITEM_CLICKS,"*,SUM(no_of_clicks) as sum,DATE_FORMAT(created_at,'%Y-%m-%d') as date", ['where' => ['is_deleted' => 0,'restaurant_id' => $this->session->userdata('login_user')['id'] ,'created_at >= ' => date('Y-m-d', strtotime($start_date)), 'created_at <= ' => date('Y-m-d', strtotime($end_date))]],['group_by' => 'date']),
            'items_click' => $this->users_model->home_graph($start_date,$end_date),
        );
        $data['json'] = json_encode("");
        $new_json_data = $key_arrays = $new_array = array();
        foreach ($json_data as $key => $val) {
            foreach ($val as $i => $val1) {
                $new_array[$val1['date']] = $val1['sum'];
                $key_arrays[] = array($val1['date'], date('jS M \'y', strtotime($val1['date'])));
            }
            $new_json_data[$key] = $new_array;
        }
    
        $key_arrays = array_unique($key_arrays, SORT_REGULAR);
        usort($key_arrays, array($this, 'sortFunction'));
        
        $actions = [];
        foreach ($new_json_data as $k => $data_value) {
        
            $actions[$k] = array();
            foreach ($key_arrays as $key => $value) {
                if (isset($data_value[$value[0]])) {
                    $actions[$k][$value[0]] = array(
                        $data_value[$value[0]], $value[1]
                    );
                }
            }
        }
        
        $actions['key_array'] = $key_arrays;
        $data['json'] = json_encode($actions);
                
        $category_id = $all_categories = array();
        if(!empty($data['categories']))
        {
            foreach($data['categories'] as $key => $row)
            {
                $category_id[$key] = $row['id'];
                //$all_categories = $this->users_model->sql_select(TBL_CATEGORY_CLICKS,'*',array('category_id' => $row['id'] ,'is_deleted' => 0));
            }
        }
        //$category_clicks_time = $this->users_model->sql_select(TBL_CATEGORY_CLICKS_TIME,'category_click_id,DATE_FORMAT(date_time,"%Y-%m-%d") as date',['where' => ['date_time >= ' => date('Y-m-d', strtotime($start_date)), 'date_time <= ' => date('Y-m-d', strtotime($end_date))]],['group_by' => 'category_click_id']);
        
        $key_arrays1 = array();
            
        $json_data['category_click'] = NULL;
        
            $json_data = array(
                //'category_click' => $this->users_model->sql_select(TBL_CATEGORY_CLICKS,"*,SUM(no_of_clicks) as sum,DATE_FORMAT(created_at,'%Y-%m-%d') as date", ['where' => ['is_deleted' => 0,'restaurant_id' => $this->session->userdata('login_user')['id'] ,'created_at >= ' => date('Y-m-d', strtotime($start_date)), 'created_at <= ' => date('Y-m-d', strtotime($end_date))]],['group_by' => 'date']),
                'category_click' => $this->users_model->home_graph_cat($start_date,$end_date),
            );
           
            $data['json1'] = json_encode("");
            $new_json_data1 = $key_arrays1 = $new_array1 = array();
            foreach ($json_data as $key => $val) {
                foreach ($val as $i => $val1) {
                    $new_array1[$val1['date']] = $val1['sum'];
                    $key_arrays1[] = array($val1['date'], date('jS M \'y', strtotime($val1['date'])));
                }
                $new_json_data1[$key] = $new_array1;
            }
        
            $key_arrays1 = array_unique($key_arrays1, SORT_REGULAR);
            usort($key_arrays1, array($this, 'sortFunction'));
            
            $actions1 = [];
            foreach ($new_json_data1 as $k => $data_value) 
            {
            
                $actions1[$k] = array();
                foreach ($key_arrays1 as $key => $value) {
                    if (isset($data_value[$value[0]])) {
                        $actions1[$k][$value[0]] = array(
                            $data_value[$value[0]], $value[1]
                        );
                    }
                }
            }
        $actions1['key_array1'] = $key_arrays1;
        $data['json1'] = json_encode($actions1);
        $this->template->load('default', 'Backend/restaurant/dashboard', $data);
    }


    public function test(){
            if(is_sub_admin())
        {
            $this->session->set_flashdata('error', 'Access denied!');
            redirect('restaurant/menus');
        }
        $data['title'] = WEBNAME.' | Dashboard';
        $data['head'] = 'Dashboard';
        $data['total_users'] = ($this->users_model->sql_select(TBL_USERS, 'id', ['where' => ['is_deleted' => 0,'restaurant_id' => $this->session->userdata('login_user')['id']]], ['count' => true]));
        $data['total_menus'] = ($this->users_model->sql_select(TBL_MENUS, 'id', ['where' => ['is_deleted' => 0,'restaurant_id' => $this->session->userdata('login_user')['id']]], ['count' => true]));
        $menus = ($this->users_model->sql_select(TBL_MENUS, 'id', ['where' => ['is_deleted' => 0,'restaurant_id' => $this->session->userdata('login_user')['id']]]));
        $menu = array();
        $data['total_categories'] = $data['categories'] = $data['total_items'] = $data['items'] = $data['type'] = 0;
        $res = $this->Settings_model->get_settings_detail(['user_id' => $this->session->userdata('login_user')['id'], 'is_deleted' => 0, 'is_active' => 1]);
        if(isset($res['rest_name']) && !empty($res['rest_name'])){
         $data['sesurl']="https://www.cherrymenu.com/".$res['rest_name'];
        }else{
            $data['sesurl']="";
        }
        
        $category = array();
        if(!empty($menus))
        {
            foreach($menus as $key => $row)
            {
                $menu[$key] = $row['id'];
            }
            //$data['total_categories'] = ($this->users_model->sql_select(TBL_CATEGORIES, 'id', ['where' => ['is_deleted' => 0],'where_in' => ['menu_id' => $menu]], ['count' => true]));
            $data['total_categories'] = $this->ListCategories($menu,'count');
            $data['categories'] = $this->ListCategories($menu,'result');
            //$data['categories'] = ($this->users_model->sql_select(TBL_CATEGORIES, '*', ['where' => ['is_deleted' => 0],'where_in' => ['menu_id' => $menu]]));
            
            if(!empty($data['categories']))
            {
                foreach($data['categories'] as $key => $row)
                {
                    $category[$key] = $row['id'];
                }
                $data['total_items'] = $this->ListItems($category,'count');
                //$data['items'] = $this->ListItems($category,'result');
            } 
        }

        //-- Chart data
        $date = $this->input->get('date');
        $date_array = array();
        $date_string = '';
        $event_arr = array();
        //-- By default take current months start and ending date
        $start_date = date('Y-m-01'); // hard-coded '01' for first day
        $end_date = date('Y-m-t');

        if ($date != '') {
            $dates = explode('-', $date);
            $start_date = $dates[0];
            $end_date = $dates[1];
        }

        //Items id of login restaurant.
        // $items_id = $all_items = array();
        // if(!empty($data['items']))
        // {
        //     foreach($data['items'] as $key => $row)
        //     {
        //         $items_id[$key] = $row['id'];
        //         $all_items = $this->users_model->sql_select(TBL_ITEM_CLICKS,'*',array('item_id' => $row['id'] ,'is_deleted' => 0));
        //     }
        // }

        $key_arrays = array();
            
        $json_data['items_click'] = NULL;
        
        $json_data = array(
           // 'items_click' => $this->users_model->sql_select(TBL_ITEM_CLICKS,"*,SUM(no_of_clicks) as sum,DATE_FORMAT(created_at,'%Y-%m-%d') as date", ['where' => ['is_deleted' => 0,'restaurant_id' => $this->session->userdata('login_user')['id'] ,'created_at >= ' => date('Y-m-d', strtotime($start_date)), 'created_at <= ' => date('Y-m-d', strtotime($end_date))]],['group_by' => 'date']),
            'items_click' => $this->users_model->home_graph($start_date,$end_date),
        );
        $data['json'] = json_encode("");
        $new_json_data = $key_arrays = $new_array = array();
        foreach ($json_data as $key => $val) {
            foreach ($val as $i => $val1) {
                $new_array[$val1['date']] = $val1['sum'];
                $key_arrays[] = array($val1['date'], date('jS M \'y', strtotime($val1['date'])));
            }
            $new_json_data[$key] = $new_array;
        }
    
        $key_arrays = array_unique($key_arrays, SORT_REGULAR);
        usort($key_arrays, array($this, 'sortFunction'));
        
        $actions = [];
        foreach ($new_json_data as $k => $data_value) {
        
            $actions[$k] = array();
            foreach ($key_arrays as $key => $value) {
                if (isset($data_value[$value[0]])) {
                    $actions[$k][$value[0]] = array(
                        $data_value[$value[0]], $value[1]
                    );
                }
            }
        }
        
        $actions['key_array'] = $key_arrays;
        $data['json'] = json_encode($actions);
                
        $category_id = $all_categories = array();
        if(!empty($data['categories']))
        {
            foreach($data['categories'] as $key => $row)
            {
                $category_id[$key] = $row['id'];
                $all_categories = $this->users_model->sql_select(TBL_CATEGORY_CLICKS,'*',array('category_id' => $row['id'] ,'is_deleted' => 0));
            }
        }
        //$category_clicks_time = $this->users_model->sql_select(TBL_CATEGORY_CLICKS_TIME,'category_click_id,DATE_FORMAT(date_time,"%Y-%m-%d") as date',['where' => ['date_time >= ' => date('Y-m-d', strtotime($start_date)), 'date_time <= ' => date('Y-m-d', strtotime($end_date))]],['group_by' => 'category_click_id']);
        
        $key_arrays1 = array();
            
        $json_data['category_click'] = NULL;
        
            $json_data = array(
                //'category_click' => $this->users_model->sql_select(TBL_CATEGORY_CLICKS,"*,SUM(no_of_clicks) as sum,DATE_FORMAT(created_at,'%Y-%m-%d') as date", ['where' => ['is_deleted' => 0,'restaurant_id' => $this->session->userdata('login_user')['id'] ,'created_at >= ' => date('Y-m-d', strtotime($start_date)), 'created_at <= ' => date('Y-m-d', strtotime($end_date))]],['group_by' => 'date']),
                'category_click' => $this->users_model->home_graph_cat($start_date,$end_date),
            );
           
            $data['json1'] = json_encode("");
            $new_json_data1 = $key_arrays1 = $new_array1 = array();
            foreach ($json_data as $key => $val) {
                foreach ($val as $i => $val1) {
                    $new_array1[$val1['date']] = $val1['sum'];
                    $key_arrays1[] = array($val1['date'], date('jS M \'y', strtotime($val1['date'])));
                }
                $new_json_data1[$key] = $new_array1;
            }
        
            $key_arrays1 = array_unique($key_arrays1, SORT_REGULAR);
            usort($key_arrays1, array($this, 'sortFunction'));
            
            $actions1 = [];
            foreach ($new_json_data1 as $k => $data_value) 
            {
            
                $actions1[$k] = array();
                foreach ($key_arrays1 as $key => $value) {
                    if (isset($data_value[$value[0]])) {
                        $actions1[$k][$value[0]] = array(
                            $data_value[$value[0]], $value[1]
                        );
                    }
                }
            }
        $actions1['key_array1'] = $key_arrays1;
        $data['json1'] = json_encode($actions1);
        echo "<pre>";
        print_r($data);
    }

    public function profile()
    {
        $data['title'] = WEBNAME.' | Profile';
        $data['head'] = 'Profile Setting';
        $data['label'] = 'Profile Setting';
        $data['user'] = $this->Users_model->get_user_detail(['id' => $this->session->userdata('login_user')['id']], 'id,name,email,image');
        $image = $data['user']['image'];
        $this->form_validation->set_rules('name', 'Name', 'trim|required|strip_tags');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            if ($this->form_validation->run() == true) {
                if(is_sub_admin())
                {
                    $directory = RESTAURANT_IMAGES . '/' . $this->session->userdata('login_user')['restaurant_id']. '/users/' .$this->session->userdata('login_user')['id'];
                    if (!file_exists(RESTAURANT_IMAGES . '/' . $this->session->userdata('login_user')['restaurant_id']. '/users/')) {
                        mkdir(RESTAURANT_IMAGES . '/' . $this->session->userdata('login_user')['restaurant_id']. '/users/');
                    }
                    if (!file_exists($directory)) {
                        mkdir($directory);
                    }
                }
                else
                {
                    $directory = RESTAURANT_IMAGES . '/' . $this->session->userdata('login_user')['id'];
                    if (!file_exists(RESTAURANT_IMAGES)) {
                        mkdir(RESTAURANT_IMAGES);
                    }
                    if (!file_exists($directory)) {
                        mkdir($directory);
                    }
                }
                if ($_FILES['profileimg']['name'] != '') {
                    $image_data = upload_image('profileimg', $directory);
                    if (is_array($image_data)) {
                        $data['profile_image_validation'] = $image_data['errors'];
                    } else {
                        if ($image != '') {
                            unlink($directory .'/'. $image);
                        }
                        $image = $image_data;
                    }
                }
                $dataArr = array(
                    'name' => trim($this->input->post('name')),
                    'email' => trim($this->input->post('email')),
                    'image' => $image,
                );
                $dataArr['updated_at'] = date('Y-m-d H:i:s');
                $this->users_model->common_insert_update('update', TBL_USERS, $dataArr, ['id' => $this->session->userdata('login_user')['id']]);
                $result = $this->users_model->get_user_detail(['id' => $this->session->userdata('login_user')['id'], 'is_deleted' => 0]);
                if(!empty($result))
                {
                    $this->session->set_userdata('login_user', $result);
                }
                $this->session->set_flashdata('success', 'Profile has been updated successfully.');
                redirect('restaurant/home/profile');
            }
        }
        $this->template->load('default', 'Backend/profile_setting', $data);
    }

    /**
     * This function used to check Unique email at the time of Users's add at admin side
     * */
    public function checkUniqueEmail()
    {
        $email = trim($this->input->post('email'));
        $user_id = base64_decode($this->input->post('user_id'));
        $user = $this->users_model->check_unique_email($email, $user_id);
        if ($user) {
            echo "false";
        } else {
            echo "true";
        }
        exit;
    }

    public function checkOldPassword()
    {
        $user_id = base64_decode($this->input->post('user_id'));
        $result = $this->users_model->get_user_detail(['id' => $user_id, 'is_deleted' => 0]);
        if ($result) {
            $password = trim($this->input->post('password'));
            if (!password_verify($this->input->post('password'), $result['password'])) {
                echo "false";
            } else {
                echo "true";
            }
        }
        exit;
    }

    /**
    * Reset password 
    */
    public function reset_password()
    {
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('confirm_password', 'Confirm password', 'trim|required|matches[password]');
        if ($this->form_validation->run() == false) {
            $data['error'] = validation_errors();
        } else {
           $data = array(
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
            );
            $this->users_model->common_insert_update('update', TBL_USERS, $data, ['id' => $this->session->userdata('login_user')['id']]);
            $this->session->set_flashdata('success', 'Your password changed successfully');
            redirect('restaurant/home/profile');
        }
        redirect('restaurant/home/profile');
    }

    /**
    * Specifies sort for date array
    * @param string $a
    * @param string $b
    * @return type
    */
    function sortFunction($a, $b) {
        return strtotime($a[0]) - strtotime($b[0]);
    }

    public function category_clicks()
    {
        $category_id = $all_categories = array();
        if(!empty($data['categories']))
        {
            foreach($data['categories'] as $key => $row)
            {
                $category_id[$key] = $row['id'];
                $all_categories = $this->users_model->sql_select(TBL_CATEGORY_CLICKS,'*',array('category_id' => $row['id'] ,'is_deleted' => 0));
            }
        }
        $category_clicks_time = $this->users_model->sql_select(TBL_CATEGORY_CLICKS_TIME,'category_click_id,DATE_FORMAT(date_time,"%Y-%m-%d") as date',['where' => ['date_time >= ' => date('Y-m-d', strtotime($start_date)), 'date_time <= ' => date('Y-m-d', strtotime($end_date))]],['group_by' => 'category_click_id']);
        
        $key_arrays1 = array();
        if(!empty($category_clicks_time))
        {
            foreach($category_clicks_time as $key => $time)
            {
               $where_in[$key] = $time['category_click_id'];
            }
            $json_data['category_click'] = NULL;
            if(!empty($where_in))
            {
                $json_data = array(
                    'category_click' => $this->users_model->sql_select(TBL_CATEGORY_CLICKS,"*,SUM(no_of_clicks) as sum,DATE_FORMAT(created_at,'%Y-%m-%d') as date", ['where' => ['is_deleted' => 0,'restaurant_id' => $this->session->userdata('login_user')['id']], 'where_in' => ['id' => $where_in]],['group_by' => 'date']),
                );
           
                $data['json'] = json_encode("");
                $new_json_data = $key_arrays1 = $new_array = array();
                foreach ($json_data as $key => $val) {
                    foreach ($val as $i => $val1) {
                        $new_array[$val1['date']] = $val1['sum'];
                        $key_arrays1[] = array($val1['date'], date('jS M \'y', strtotime($val1['date'])));
                    }
                    $new_json_data[$key] = $new_array;
                }
            
                $key_arrays1 = array_unique($key_arrays1, SORT_REGULAR);
                usort($key_arrays1, array($this, 'sortFunction'));
                
                $actions = [];
                foreach ($new_json_data as $k => $data_value) {
                
                    $actions[$k] = array();
                    foreach ($key_arrays1 as $key => $value) {
                        if (isset($data_value[$value[0]])) {
                            $actions[$k][$value[0]] = array(
                                $data_value[$value[0]], $value[1]
                            );
                        }
                    }
                }
            }
        }
    }
}
