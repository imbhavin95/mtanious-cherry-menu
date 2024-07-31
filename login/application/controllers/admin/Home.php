<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model');
    }

    public function index()
    {
        $data['title'] = WEBNAME.' | Dashboard';
		$data['head'] = 'Dashboard';
        $data['total_restaurant'] = $this->total_restaurant;
        $data['total_users'] = $this->total_users;
        $data['total_categories'] = $this->total_categories;
        $data['total_items'] = $this->total_items;
        $data['items'] = ($this->users_model->sql_select(TBL_ITEMS, 'title,price,created_at', ['where' => ['is_deleted' => 0]]));
        $data['restaurants'] = ($this->users_model->sql_select(TBL_USERS, '*', ['where' => ['is_deleted' => 0,'role' => RESTAURANT]]));
        $data['pointStart'] = ($this->users_model->sql_select(TBL_USERS, 'MIN(created_at) as min_date', ['where' => ['is_deleted' => 0,'role' => RESTAURANT]]));
        $data['type'] = ($this->users_model->sql_select(TBL_TYPES, 'type', ['where' => ['is_deleted' => 0]]));
        $type = array();
        foreach($data['type'] as $row)
        {
            $type['types'][$row['type']] = $this->db->like('type', $row['type'], 'both')->get(TBL_ITEMS)->num_rows(); 
        }

        //-- Chart data
        //-- Returns the number of free images purchased
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
        
        $date_array = array('created_at >= ' => date('Y-m-d', strtotime($start_date)), 'created_at <= ' => date('Y-m-d', strtotime($end_date)));
        $date_string = ' AND created_at >= "' . date('Y-m-d', strtotime($start_date)) . '" AND created_at <= "' . date('Y-m-d', strtotime($end_date)) . '"';
        $event_arr['from_date'] = date('Y-m-d', strtotime($start_date));
        $event_arr['to_date'] = date('Y-m-d', strtotime($end_date));
        $data['json'] = json_encode("");
     
        //-- Json data for chart
        $json_data = array(
            'restaurant' => $this->users_model->num_of_records_by_date(TBL_USERS, array_merge($date_array, array('is_deleted' => 0, 'role' => RESTAURANT))),
        );
        
        $new_json_data = array();
        $key_arrays = array();

        foreach ($json_data as $key => $val) {
            $new_array = array();
            foreach ($val as $val1) {
                $new_array[$val1['date']] = $val1['count'];
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
        $data['type'] = $type['types'];
        $this->template->load('default', 'Backend/dashboard', $data);
    }

    public function profile()
    {
        $data['title'] = WEBNAME.' | Profile';
        $data['head'] = 'Profile Setting';
        $data['label'] = 'Profile Setting';
        $data['user'] = $this->Users_model->get_user_detail(['id' => $this->session->userdata('login_user')['id']], 'id,name,email,image');
        $image = $data['user']['image'];
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            if ($this->form_validation->run() == true) {

            $directory = RESTAURANT_IMAGES . '/' . $this->session->userdata('login_user')['id'];
            if (!file_exists(RESTAURANT_IMAGES)) {
                mkdir(RESTAURANT_IMAGES);
            }
            if (!file_exists($directory)) {
                mkdir($directory);
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
                redirect('admin/home/profile');
            }
        }
        $this->template->load('default', 'Backend/profile_setting', $data);
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
            redirect('admin/home/profile');
        }
        redirect('admin/home/profile');
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
}
