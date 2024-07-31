<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Package_request');
        $this->load->model('Packages_model');
        if(is_sub_admin())
        {
            $this->session->set_flashdata('error', 'Access denied!');
            redirect('restaurant/menus');
        }
    }

    public function index()
    {
        $data['title'] = WEBNAME.' | Users';
        $data['head'] = 'Users';
        $data['total_users'] = ($this->users_model->sql_select(TBL_USERS, 'id', ['where' => ['is_deleted' => 0,'restaurant_id' => $this->session->userdata('login_user')['id']]], ['count' => true]));
        $data['package'] = $this->check_free_pacakge(); //check free trial assign or not.
        $this->template->load('default', 'Backend/restaurant/users/index', $data);
    }

    /**
     * This function used to get Users data for ajax table
     * */
    public function get_users()
    {
        $final['recordsFiltered'] = $final['recordsTotal'] = $this->users_model->get_users('count');
        $final['redraw'] = 1;
        $restaurant = $this->users_model->get_users('result');
        $start = $this->input->get('start') + 1;
        foreach ($restaurant as $key => $val) {
            $restaurant[$key] = $val;
            $restaurant[$key]['name'] = htmlentities($val['name']);
            $restaurant[$key]['sr_no'] = $start++;
            $restaurant[$key]['count_users'] = ($this->users_model->sql_select(TBL_USERS, 'id', ['where' => ['is_deleted' => 0, 'role' => USERS, 'restaurant_id' => $val['id']]], ['count' => true]));
            $restaurant[$key]['count_staffs'] = ($this->users_model->sql_select(TBL_USERS, 'id', ['where' => ['is_deleted' => 0, 'role' => STAFF, 'restaurant_id' => $val['id']]], ['count' => true]));
            $restaurant[$key]['count_waiters'] = ($this->users_model->sql_select(TBL_USERS, 'id', ['where' => ['is_deleted' => 0, 'role' => WAITER, 'restaurant_id' => $val['id']]], ['count' => true]));
            $restaurant[$key]['created_at'] = date('d M Y', strtotime($val['created_at']));
        }
        $final['data'] = $restaurant;
        echo json_encode($final);
    }

    /**
     * This function used to add / edit Users data
     * @param int $id
     * */
    public function add($id = null)
    {
        $total_users = ($this->users_model->sql_select(TBL_USERS, 'id', ['where' => ['is_deleted' => 0,'restaurant_id' => $this->session->userdata('login_user')['id']]], ['count' => true]));
        $package = $this->check_free_pacakge();
        if ($total_users < $this->session->userdata('login_user')['users_limit'] || !empty($package)) 
        {
            if (!is_null($id)) {
                $id = base64_decode($id);
            }
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            // $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|callback_is_uniquemail');
            $image = null;
            $data['title'] = WEBNAME.' | Add User';
            $data['head'] = 'Add';
            $data['label'] = 'Create New';
            if ($this->form_validation->run() == true) {
                $dataArr = array(
                    'role' => trim($this->input->post('role')),
                    'name' => trim($this->input->post('name')),
                    'restaurant_id' => $this->session->userdata('login_user')['id'],
                    'is_active' => 1,
                );

                $password = randomPassword();
                $dataArr['email'] = trim($this->input->post('email'));
                if($this->input->post('role') == SUB_ADMIN)
                {
                    $dataArr['password'] = password_hash($password, PASSWORD_BCRYPT);
                    $email_data = ['name' => trim($this->input->post('name')), 'type' => 'Sub-admin', 'email' => trim($this->input->post('email')), 'url' => base_url('login'), 'password' => $password, 'subject' => 'Welcome to Cherry Menu.'];
                    send_email(trim($this->input->post('email')), 'add_restaurant', $email_data);
                }
                $dataArr['created_at'] = date('Y-m-d H:i:s');
                $inserted_id = $this->users_model->common_insert_update('insert', TBL_USERS, $dataArr);
                $directory = RESTAURANT_IMAGES . '/' . $this->session->userdata('login_user')['id']. '/users/' .$inserted_id;
                if (!file_exists(RESTAURANT_IMAGES . '/' . $this->session->userdata('login_user')['id']. '/users/')) {
                    mkdir(RESTAURANT_IMAGES . '/' . $this->session->userdata('login_user')['id']. '/users/');
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
                $this->session->set_flashdata('success', 'User has been added successfully');
                redirect('restaurant/users');
            }
            $this->template->load('default', 'Backend/restaurant/users/manage', $data);
        }
        else
        {
            $this->session->set_flashdata('error', 'Limitation is finised!');
            redirect('restaurant/users'); 
        }
    }

    /**
     * Edit Users data
     * @param int $id
     * */
    public function edit($id)
    {
        if (!is_null($id)) {
            $id = base64_decode($id);
        }else{
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('restaurant/users');
        }
        if (is_numeric($id)) {
            $user = $this->users_model->get_user_detail(['id' => $id, 'is_deleted' => 0]);
            if ($user) {
                $data['user'] = $user;
                $data['title'] = WEBNAME.' | Edit User';
                $data['head'] = 'Edit';
                $data['label'] = 'Edit';
                $this->form_validation->set_rules('name', 'Name', 'trim|required');
                $image = $user['image'];
                if ($this->form_validation->run() == true) {
                    $directory = RESTAURANT_IMAGES . '/' . $this->session->userdata('login_user')['id']. '/users/' .$id;
                    if (!file_exists(RESTAURANT_IMAGES . '/' . $this->session->userdata('login_user')['id']. '/users/')) {
                        mkdir(RESTAURANT_IMAGES . '/' . $this->session->userdata('login_user')['id']. '/users/');
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
                        'role' => trim($this->input->post('role')),
                        'name' => trim($this->input->post('name')),
                        'email' => trim($this->input->post('email')),
                        'restaurant_id' => $this->session->userdata('login_user')['id'],
                        'image' => $image,
                    );
                    $dataArr['updated_at'] = date('Y-m-d H:i:s');
                    
                    //Check it's staff or sub-admin when creat. and assign password based on that.
                    if($user['role'] == STAFF && trim($this->input->post('role')) == SUB_ADMIN)
                    {
                        $password = randomPassword();
                        $dataArr['password'] = password_hash($password, PASSWORD_BCRYPT);
                        $email_data = ['name' => trim($this->input->post('name')), 'type' => 'Sub-admin', 'email' => trim($this->input->post('email')), 'url' => base_url('login'), 'password' => $password, 'subject' => 'Role Changed - '.WEBNAME];
                        send_email(trim($this->input->post('email')), 'add_restaurant', $email_data);
                    }
                    $this->users_model->common_insert_update('update', TBL_USERS, $dataArr, ['id' => $id]);
                    $this->session->set_flashdata('success', 'User\'s data has been updated successfully.');
                    redirect('restaurant/users');
                } else {
                    $this->template->load('default', 'Backend/restaurant/users/manage', $data);
                }
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
                redirect('restaurant/users');
            }
        } else {
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('restaurant/users');
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
            $users = $this->users_model->get_user_detail(['id' => $id], 'id,name');
            if ($users) {
                $update_array = array(
                    'is_deleted' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->users_model->common_insert_update('update', TBL_USERS, $update_array, ['id' => $id]);
                $this->session->set_flashdata('success', htmlentities($users['name']) . ' has been deleted successfully!');
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('restaurant/users');
        } else {
            show_404();
        }
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

    /**
     * View Users
     * @return : View
     * @author : sm
     */
    public function view_user()
    {
        $user_id = base64_decode($this->input->post('id'));
        $user = $this->users_model->get_user_detail(['id' => $user_id]);
        if ($user) {
            $data['user'] = $user;
            return $this->load->view('Backend/restaurant/users/view', $data);
        } else {
            show_404();
        }
    }

    /**
     * enable and disable of Users
     * @param int $id
     * */
    public function change_status()
    {
        $id = $this->input->post('id');
        if (is_numeric($id)) {
            $users = $this->users_model->get_user_detail(['id' => $id], 'id,name,is_active');
            $is_active;
            $msg = array();
            if ($users) {
                if ($users['is_active'] == 1) {
                    $is_active = 0;
                    $msg['status'] = 1;
                    $msg['msg'] = htmlentities($users['name']) . ' has been disable successfully!';
                } else {
                    $is_active = 1;
                    $msg['status'] = 0;
                    $msg['msg'] = htmlentities($users['name']) . ' has been enable successfully!';
                }
                $update_array = array(
                    'is_active' => $is_active,
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $this->users_model->common_insert_update('update', TBL_USERS, $update_array, ['id' => $id]);
                $sql = "SELECT GREATEST(MAX(IFNULL(m.created_at,0)), MAX(IFNULL(m.updated_at,0)),MAX(IFNULL(u.created_at,0)), MAX(IFNULL(u.updated_at,0)),MAX(IFNULL(s.created_at,0)), MAX(IFNULL(s.updated_at,0))) as latesttimestamp FROM menus m LEFT JOIN users as u on u.restaurant_id = m.restaurant_id LEFT JOIN settings as s on s.user_id = m.restaurant_id WHERE m.restaurant_id = '".$this->session->userdata('login_user')['id']."'";
                    $query = $this->db->query($sql);
                    if ($query->num_rows() > 0) {
                  foreach ($query->result() as $row) { 
                    $latesttimestamp = $row->latesttimestamp; }
                }
                $msg['latesttimestamp'] = $latesttimestamp;
                header('Content-Type: application/json');
                echo json_encode($msg);
                exit;
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('restaurant/users');
        } else {
            show_404();
        }
    }
}
