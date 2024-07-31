<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Restaurant extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Packages_model');
        $this->load->model('Package_request');
    }

    public function index()
    {
        $data['title'] = WEBNAME.' | Restaurants';
        $data['head'] = 'Restaurants';
        $this->template->load('default', 'Backend/admin/restaurant/index', $data);
    }

    /**
     * This function used to get restaurant data for ajax table
     * */
    public function get_restaurant()
    {
        $final['recordsFiltered'] = $final['recordsTotal'] = $this->users_model->get_users('count');
        $final['redraw'] = 1;
        $restaurant = $this->users_model->get_users('result');
         //echo $this->db->last_query();
        $start = $this->input->get('start') + 1;
        foreach ($restaurant as $key => $val) {
            $restaurant[$key] = $val;
            $restaurant[$key]['name'] = htmlentities($val['name']);
            $restaurant[$key]['sr_no'] = $start++;
            // $restaurant[$key]['count_users'] = ($this->users_model->sql_select(TBL_USERS, 'id', ['where' => ['is_deleted' => 0, 'role' => USERS, 'restaurant_id' => $val['id']]], ['count' => true]));
            // $restaurant[$key]['count_staffs'] = ($this->users_model->sql_select(TBL_USERS, 'id', ['where' => ['is_deleted' => 0, 'role' => STAFF, 'restaurant_id' => $val['id']]], ['count' => true]));
            // $restaurant[$key]['count_waiters'] = ($this->users_model->sql_select(TBL_USERS, 'id', ['where' => ['is_deleted' => 0, 'role' => WAITER, 'restaurant_id' => $val['id']]], ['count' => true]));
            $restaurant[$key]['created_at'] = date('d M Y', strtotime($val['created_at']));
        }
        $final['data'] = $restaurant;
        echo json_encode($final);
    }


     

    /**
     * This function used to add / edit restaurant data
     * @param int $id
     * */
    public function add($id = null)
    {
        if (!is_null($id)) {
            $id = base64_decode($id);
        }
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_is_uniquemail');
        $this->form_validation->set_rules('devices_limit', 'Devices Limit', 'required|numeric');
        $this->form_validation->set_rules('users_limit', 'Users Limit', 'required|numeric');
        $this->form_validation->set_rules('menus_limit', 'Staffs Limit', 'required|numeric');
        $this->form_validation->set_rules('categories_limit', 'Waiters Limit', 'required|numeric');
        $this->form_validation->set_rules('items_limit', 'Menus Limit', 'required|numeric');
        $this->form_validation->set_rules('order_feature', 'Order Feature', 'required|numeric');
        $image = null;
        $data['title'] = WEBNAME.' | Add Restaurant';
        $data['head'] = 'Add';
        $data['label'] = 'Create New';
        if ($this->form_validation->run() == true) {
            $dataArr = array(
                'role' => RESTAURANT,
                'name' => trim($this->input->post('name')),
                'devices_limit' => $this->input->post('devices_limit'),
                'users_limit' => $this->input->post('users_limit'),
                'menus_limit' => $this->input->post('menus_limit'),
                'categories_limit' => $this->input->post('categories_limit'),
                'items_limit' => $this->input->post('items_limit'),
                'is_active' => 1,
                'order_feature' => $this->input->post('order_feature'),
            );
            $password = randomPassword();
            $dataArr['email'] = trim($this->input->post('email'));
            $dataArr['password'] = password_hash($password, PASSWORD_BCRYPT);
            $dataArr['created_at'] = date('Y-m-d H:i:s');
            $email_data = ['name' => trim($this->input->post('name')), 'type' => 'restaurant', 'email' => trim($this->input->post('email')), 'url' => base_url('login'), 'password' => $password, 'subject' => 'Welcome! Please confirm your email'];
            send_email(trim($this->input->post('email')), 'add_restaurant', $email_data);
            $inserted_id = $this->users_model->common_insert_update('insert', TBL_USERS, $dataArr); 
            //Insert Default Staff user 
            $default_staff_id = $this->users_model->common_insert_update('insert', TBL_USERS, ['role' => STAFF,'name' => 'staff','restaurant_id' => $inserted_id,'is_active' => 1]); 

            $directory = RESTAURANT_IMAGES . '/' . $inserted_id;
            if (!file_exists(RESTAURANT_IMAGES)) {
                mkdir(RESTAURANT_IMAGES);
            }
            if (!file_exists($directory)) {
                mkdir($directory);
            }
            if ($_FILES['profileimage']['name'] != '') {
                $image_data = upload_image('profileimage', $directory);
                if (is_array($image_data)) {
                    $data['profile_image_validation'] = $image_data['errors'];
                } else {
                    if ($image != '') {
                        unlink($directory .'/'. $image);
                    }
                    $image = $image_data;
                }
            }
            $this->users_model->common_insert_update('update', TBL_USERS, ['image' => $image], ['id' => $inserted_id]);
            
            //Assign free package automatically
            $email_data = ['name' => trim($this->input->post('name')), 'email' => trim($this->input->post('email')),'subject' => 'Your trial has started!'];
            $email_data['head_title'] = "Welcome on board!";
            $email_data['message'] = "Your 30 days trial has started, we shall be sending you few reminders before the trial expires";
            send_email(trim($this->input->post('email')), 'reminder', $email_data);

            //Get Free package if exist in Package Detail table.
            $freeTriel = $this->Packages_model->get_package_detail(['is_deleted' => 0,'is_active' => 1,'type' => 'free']);
            if(!empty($freeTriel))
            {
                $package = array(
                    'package_id' => $freeTriel['id'],
                    'restaurant_id' => $inserted_id,
                    'status' => 'activate',
                    'flag' => 1
                );
                $package['request_date'] = date('Y-m-d');
                $package['created_at'] = date('Y-m-d H:i:s');
                
                //Assign free packages limit.
                $restaurant = $this->users_model->get_user_detail(['id' => $inserted_id, 'is_deleted' => 0]);
                $update_restaurant = array('users_limit' => $restaurant['users_limit'] + $freeTriel['users'],
                'menus_limit' => $restaurant['menus_limit'] + $freeTriel['menus'],
                'categories_limit' => $restaurant['categories_limit'] + $freeTriel['categories'],
                'items_limit' => $restaurant['items_limit'] + $freeTriel['items'],
                'devices_limit' => $restaurant['devices_limit'] + $freeTriel['devices_limit']
                );
                $update_restaurant['updated_at'] = date('Y-m-d H:i:s');

                $this->users_model->common_insert_update('update', TBL_USERS, $update_restaurant, ['id' => $inserted_id]);
                $package_detail_id = $this->users_model->common_insert_update('insert', TBL_PACKAGE_DETAILS, $package); 
            }

            $this->session->set_flashdata('success', 'Restaurant has been added successfully and Email has been sent to user successfully');
            redirect('admin/restaurant');
        }
        $this->template->load('default', 'Backend/admin/restaurant/manage', $data);
    }

    /**
     * Edit Restaurant data
     * @param int $id
     * */
    public function edit($id)
    {
        if (!is_null($id)) {
            $id = base64_decode($id);
        }else{
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('admin/restaurant');
        }
        if (is_numeric($id)) {
            $restaurant = $this->users_model->get_user_detail(['id' => $id, 'is_deleted' => 0]);
            if ($restaurant) {
                $data['restaurant'] = $restaurant;
                $data['title'] = WEBNAME.' | Edit Restaurant';
                $data['head'] = 'Edit';
                $data['label'] = 'Edit';
                $this->form_validation->set_rules('name', 'Name', 'trim|required');
                $this->form_validation->set_rules('devices_limit', 'Devices Limit', 'required|numeric');
                $this->form_validation->set_rules('users_limit', 'Users Limit', 'required|numeric');
                $this->form_validation->set_rules('menus_limit', 'Staffs Limit', 'required|numeric');
                $this->form_validation->set_rules('categories_limit', 'Waiters Limit', 'required|numeric');
                $this->form_validation->set_rules('items_limit', 'Menus Limit', 'required|numeric');
                $this->form_validation->set_rules('order_feature', 'Order Feature', 'required|numeric');
                $image = $restaurant['image'];
                if ($this->form_validation->run() == true) {
                    $directory = RESTAURANT_IMAGES . '/' . $id;
                    if (!file_exists(RESTAURANT_IMAGES)) {
                        mkdir(RESTAURANT_IMAGES);
                    }
                    if (!file_exists($directory)) {
                        mkdir($directory);
                    }
                    if ($_FILES['profileimage']['name'] != '') {
                        $image_data = upload_image('profileimage', $directory);
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
                        'devices_limit' => $this->input->post('devices_limit'),
                        'users_limit' => $this->input->post('users_limit'),
                        'menus_limit' => $this->input->post('menus_limit'),
                        'categories_limit' => $this->input->post('categories_limit'),
                        'items_limit' => $this->input->post('items_limit'),
                        'image' => $image,
                        'order_feature' => $this->input->post('order_feature'),
                    );
                    $dataArr['updated_at'] = date('Y-m-d H:i:s');
                    $this->users_model->common_insert_update('update', TBL_USERS, $dataArr, ['id' => $id]);
                    $this->session->set_flashdata('success', 'Restaurant\'s data has been updated successfully.');
                    redirect('admin/restaurant');
                } else {
                    $this->template->load('default', 'Backend/admin/restaurant/manage', $data);
                }
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
                redirect('admin/restaurant');
            }
        }else {
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('admin/restaurant');
        }
    }

    /**
     * Callback function to check email validation - Email is unique or not
     * @param string $str
     * @return boolean
     */
    public function is_uniquemail()
    {
        $email = trim($this->input->post('email'));
        $user = $this->users_model->check_unique_email($email);
        if ($user) {
            $this->form_validation->set_message('is_uniquemail', 'Email address is already in use!');
            return false;
        } else {
            return true;
        }
    }

    /**
     * Delete user
     * @param int $id
     * */
    public function delete($id = null)
    {
        $id = base64_decode($id);
        if (is_numeric($id)) {
            $restaurant = $this->users_model->get_user_detail(['id' => $id], 'id,name');
            if ($restaurant) {
                $update_array = array(
                    'is_deleted' => 1,
                );
                $this->users_model->common_insert_update('update', TBL_USERS, $update_array, ['id' => $id]);
                $this->users_model->common_insert_update('update', TBL_USERS, $update_array, ['restaurant_id' => $id]);
                $this->session->set_flashdata('success', htmlentities($restaurant['name']) . ' has been deleted successfully!');
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('admin/restaurant');
        } else {
            show_404();
        }
    }

    /**
     * This function used to check Unique email at the time of restaurant's add at admin side
     * */
    public function checkUniqueEmail()
    {
        $email = trim($this->input->post('email'));
        $restaurant_id = base64_decode($this->input->post('restaurant_id'));
        $restaurant = $this->users_model->check_unique_email($email, $restaurant_id);
        if ($restaurant) {
            echo "false";
        } else {
            echo "true";
        }
        exit;
    }

    /**
     * View restaurant
     * @return : View
     * @author : sm
     */
    public function view_restaurant()
    {
        $restaurant_id = base64_decode($this->input->post('id'));
        $restaurant = $this->users_model->get_user_detail(['id' => $restaurant_id]);
        if ($restaurant) {
            $data['restaurant'] = $restaurant;
            return $this->load->view('Backend/admin/restaurant/view', $data);
        } else {
            show_404();
        }
    }

    /**
     * enable and disable of restaurant
     * @param int $id
     * */
    public function change_status()
    {
        $id = $this->input->post('id');
        if (is_numeric($id)) {
            $restaurant = $this->users_model->get_user_detail(['id' => $id], 'id,name,is_active');
            $is_active;
            $msg = array();
            if ($restaurant) {
                if ($restaurant['is_active'] == 1) {
                    $is_active = 0;
                    $msg['status'] = 1;
                    $msg['msg'] = htmlentities($restaurant['name']) . ' has been disable successfully!';
                } else {
                    $is_active = 1;
                    $msg['status'] = 0;
                    $msg['msg'] = htmlentities($restaurant['name']) . ' has been enable successfully!';
                }
                $update_array = array(
                    'is_active' => $is_active,
                );
                $this->users_model->common_insert_update('update', TBL_USERS, $update_array, ['id' => $id]);
                header('Content-Type: application/json');
                echo json_encode($msg);
                exit;
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('admin/restaurant');
        } else {
            show_404();
        }
    }

    /**
     * Change Password
     * @return : View
     * @author : sm
     */
    public function change_password()
    {
        $restaurant_id = base64_decode($this->input->post('id'));
        $restaurant = $this->users_model->get_user_detail(['id' => $restaurant_id]);
        if ($restaurant) {
            $data['restaurant'] = $restaurant;
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            show_404();
        }
    }

    /**
     * Change Password
     * @return : View
     * @author : sm
     */
    public function update_password()
    {
            $new_verification_code = verification_code();
            $id = $this->input->post('id');
            $email = $this->input->post('email');
            $restaurant = $this->users_model->get_user_detail(['id' => $id, 'is_deleted' => 0,'email' => $email]);
           
            if($restaurant)
            {
                $data = array(
                    'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT));
                    $this->users_model->common_insert_update('update', TBL_USERS, $data, ['id' => $id]);
                    $email_data = ['name' => trim($restaurant['name']), 'type' => 'restaurant', 'email' => trim($email), 'url' => base_url('login'), 'password' => $this->input->post('password'), 'subject' => 'Change credential'];
                    send_email(trim($email), 'change_password', $email_data);
                    $data['msg'] = 'password has been changed successfully';
                    header('Content-Type: application/json');
                    echo json_encode($data);
                    exit;
            }else
            {
                $data = array(
                    'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                    'verification_code' => $new_verification_code,
                    'email' => $email,
                );
                $this->users_model->common_insert_update('update', TBL_USERS, $data, ['id' => $id]);
                $email_data = ['name' => trim($restaurant['name']), 'type' => 'restaurant', 'email' => trim($email), 'url' => base_url('login'), 'password' => $this->input->post('password'), 'subject' => 'Change credential'];
                send_email(trim($email), 'change_password', $email_data);
                // $email_data = ['name' => trim($this->input->post('name')), 'email' => trim($this->input->post('email')), 'url' => base_url('login'), 'password' => $password, 'subject' => 'Invitation - E-menu'];
                // send_email(trim($this->input->post('email')), 'add_restaurant', $email_data);
                $data['msg'] = 'password and email has been changed successfully';
                header('Content-Type: application/json');
                echo json_encode($data);
                exit;
            }
            redirect('admin/restaurant');
            // echo json_encode($data);
        
        $this->load->view('Backend/reset_password', $data);
    }

    public function checkUserLimit()
    {
        $users_limit = $this->input->post('users_limit');
        $restaurant_id = base64_decode($this->input->post('restaurant_id'));
      
        if ($restaurant_id) {
            $total_users = ($this->users_model->sql_select(TBL_USERS, 'id', ['where' => ['is_deleted' => 0,'is_active' => 1,'restaurant_id' => $restaurant_id]], ['count' => true]));
            if($total_users > $users_limit)
            {
                echo "false";
            }else
            {
                echo "true";
            }
        } else {
            echo "true";
        }
        exit;
    }

    public function checkMenuLimit()
    {
        $menus_limit = $this->input->post('menus_limit');
        $restaurant_id = base64_decode($this->input->post('restaurant_id'));
      
        if ($restaurant_id) {
            $menu_limit = ($this->users_model->sql_select(TBL_MENUS, 'id', ['where' => ['is_deleted' => 0,'is_active' => 1, 'restaurant_id' => $restaurant_id]], ['count' => true]));
            if($menu_limit > $menus_limit)
            {
                echo "false";
            }else
            {
                echo "true";
            }
        } else {
            echo "true";
        }
        exit;
    }

    public function checkCategoryLimit()
    {
        $categories_limit = $this->input->post('categories_limit');
        $restaurant_id = base64_decode($this->input->post('restaurant_id'));
      
        if ($restaurant_id) {
            $categories = $this->users_model->custom_Query("SELECT DISTINCT cd.category_id FROM categories as cate, category_details as cd ,menus as menu,users as user WHERE user.id = menu.restaurant_id AND cate.id = cd.category_id AND menu.id = cd.menu_id AND user.id = ".$restaurant_id." AND cate.is_deleted = 0 and cate.is_active='1'");
            $category_limit = $categories->result_id->num_rows;
            if($category_limit > $categories_limit)
            {
                echo "false";
            }else
            {
                echo "true";
            }
        } else {
            echo "true";
        }
        exit;
    }

    public function checkItemLimit()
    {
        $items_limit = $this->input->post('items_limit');
        $restaurant_id = base64_decode($this->input->post('restaurant_id'));
      
        if ($restaurant_id) {
            $items = $this->users_model->custom_Query('SELECT DISTINCT item.id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND menu.id = item_d.menu_id AND item.id = item_d.item_id AND cate.id = item_d.category_id AND user.id = '.$restaurant_id.' AND item.is_deleted = 0 and  item.is_active=1');
            //$items = $this->users_model->custom_Query('SELECT item.id FROM '.TBL_ITEMS.' as item,'.TBL_CATEGORIES.' as cate,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND menu.id = cate.menu_id AND cate.id = item.category_id AND user.id = '.$restaurant_id.' AND item.is_deleted = 0');
            $item_limit = $items->result_id->num_rows;
            if($item_limit > $items_limit)
            {
                echo "false";
            }else
            {
                echo "true";
            }
        } else {
            echo "true";
        }
        exit;
    }

    // Assign Package.
    public function assignpackage($id)
    {
        if (!is_null($id)) {
            $id = base64_decode($id);
        }else{
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('admin/restaurant');
        }
        if (is_numeric($id)) 
        {
            $restaurant = $this->users_model->get_user_detail(['id' => $id, 'is_deleted' => 0]);
            $packages = $this->Packages_model->get_all_details(TBL_PACKAGES,['is_deleted' => 0,'is_active' => 1])->result_array();
            if (!empty($restaurant) && !empty($packages)) 
            {
                $data['restaurant'] = $restaurant;
                $data['packages'] = $packages;
                $data['title'] = WEBNAME.' | Assign Package';
                $data['head'] = 'Assign Package';
                $data['label'] = 'Assign Package';
                $this->form_validation->set_rules('packages[]', 'Packages', 'required');
                if ($this->form_validation->run() == true) {
                    $pkg = $this->input->post('packages[]');
                    if(!empty($pkg))
                    {
                        foreach($pkg as $row)
                        {
                            //get all information of package
                            $package_data = $this->Packages_model->get_package_detail(['id'=>$row],'*');
                            $dataArr = array('restaurant_id' => $id, 'package_id' => $row, 'status' => 'activate');
                            $dataArr['created_at'] = date('Y-m-d H:i:s');
                            //Assign Package 
                            $this->Package_request->common_insert_update('insert', TBL_PACKAGE_DETAILS, $dataArr);
                            $update_restaurant = array('users_limit' => $restaurant['users_limit'] + $package_data['users'],
                                'menus_limit' => $restaurant['menus_limit'] + $package_data['menus'],
                                'categories_limit' => $restaurant['categories_limit'] + $package_data['categories'],
                                'items_limit' => $restaurant['items_limit'] + $package_data['items'],
                                'devices_limit' => $restaurant['devices_limit'] + $package_data['devices_limit']
                            );
                            $update_restaurant['updated_at'] = date('Y-m-d H:i:s');
                            $this->users_model->common_insert_update('update', TBL_USERS,$update_restaurant, ['id' => $id]);
                            //Send email for Package assign.
                            $email_data = ['name' => trim($restaurant['name']), 'email' => trim($restaurant['email']),'subject' => 'Thank you! Your subscription is now confirmed'];
                            $email_data['head_title'] = "Thank you for your purchase!";
                            $email_data['message'] = "You have been successfully subscriped to <b>".$package['name'].'</b><br/><br/>Attached is a copy of your invoice for your reference.';
                            send_email(trim($restaurant['email']), 'reminder', $email_data);

                        }
                    }
                    $this->session->set_flashdata('success', 'Packages\'s has been assigned successfully.');
                    redirect('admin/restaurant');
                }
                $this->template->load('default', 'Backend/admin/restaurant/assignpackage', $data);
            }
        }else {
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('admin/restaurant');
        }
    }


    public function test_template()
    {
        $password = randomPassword();
            $dataArr['email'] = 'sm@narola.email';
            $dataArr['password'] = password_hash($password, PASSWORD_BCRYPT);
            $dataArr['created_at'] = date('Y-m-d H:i:s');
            $email_data = ['name' => 'simal','type' => 'restaurant', 'email' => 'sm@narola.email', 'url' => base_url('login'), 'password' => $password, 'subject' => 'Welcome! Please confirm your email'];
            $this->load->view('Backend/email_templates/add_restaurant',$email_data);
            // send_email(trim($this->input->post('email')), 'add_restaurant', $email_data);
    }
}
