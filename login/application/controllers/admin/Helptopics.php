<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HelpTopics extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Help_topics');
    }

    public function index()
    {
        $data['title'] = WEBNAME.' | HelpTopics';
        $data['head'] = 'Help Topics';
        $this->template->load('default', 'Backend/admin/helptopics/index', $data);
    }

    /**
     * This function used to get helpTopics data for ajax table
     * */
    public function get_topics()
    {
        $final['recordsFiltered'] = $final['recordsTotal'] = $this->Help_topics->get_help_topics('count');
        $final['redraw'] = 1;
        $helpTopics = $this->Help_topics->get_help_topics('result');
        $start = $this->input->get('start') + 1;
        foreach ($helpTopics as $key => $val) {
            $helpTopics[$key] = $val;
            $helpTopics[$key]['sr_no'] = $start++;
            $helpTopics[$key]['title'] = htmlentities($val['title']);
            // $helpTopics[$key]['description'] = htmlentities($val['description']);
            $helpTopics[$key]['created_at'] = date('d M Y', strtotime($val['created_at']));
        }
        $final['data'] = $helpTopics;
        echo json_encode($final);
    }

    /**
     * This function used to add / edit helpTopics data
     * @param int $id
     * */
    public function add($id = null)
    {
        if (!is_null($id)) {
            $id = base64_decode($id);
        }
        $this->form_validation->set_rules('title', 'Titte', 'trim|required');
        $image = null;
        $data['title'] = WEBNAME.' | Add Help Topics';
        $data['head'] = 'Add';
        $data['label'] = 'Create New';
        if ($this->form_validation->run() == true) {
            $dataArr = array(
                'title' => trim($this->input->post('title')),
                'description' => $this->input->post('description'),
            );
            $inserted_id = $this->Help_topics->common_insert_update('insert', TBL_HELP_TOPICS, $dataArr);
            $this->session->set_flashdata('success', 'Help topic has been added successfully');
            redirect('admin/helptopics');
        }
        $this->template->load('default', 'Backend/admin/helptopics/manage', $data);
    }

    /**
     * Edit helpTopics data
     * @param int $id
     * */
    public function edit($id)
    {
        if (!is_null($id)) {
            $id = base64_decode($id);
        }else {
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('admin/Helptopics');
        }
        
        if (is_numeric($id)) {
            $helptopic = $this->Help_topics->get_help_topics_detail(['id' => $id, 'is_deleted' => 0, 'is_active' => 1]);
            if ($helptopic) {
                $data['helptopics'] = $helptopic;
                $data['title'] = WEBNAME.' | Edit Help Topics';
                $data['head'] = 'Edit';
                $data['label'] = 'Edit';
                $this->form_validation->set_rules('title', 'Title', 'trim|required');
                if ($this->form_validation->run() == true) 
                {
                    $flag = 0;
                    if ($flag == 0) {
                        $dataArr = array(
                            'title' => trim($this->input->post('title')),
                            'description' => trim($this->input->post('description')),
                        );
                    }
                    $dataArr['updated_at'] = date('Y-m-d H:i:s');
                    $this->Help_topics->common_insert_update('update', TBL_HELP_TOPICS, $dataArr, ['id' => $id]);
                    $this->session->set_flashdata('success', 'Help Topic\'s data has been updated successfully.');
                    redirect('admin/Helptopics');
                } else {
                    $this->template->load('default', 'Backend/admin/helptopics/manage', $data);
                }
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
                redirect('admin/Helptopics');
            }
        }else {
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('admin/Helptopics');
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
            $helptopic = $this->Help_topics->get_help_topics_detail(['id' => $id], 'id,title');
            if ($helptopic) {
                $update_array = array(
                    'is_deleted' => 1,
                );
                $this->Help_topics->common_insert_update('update', TBL_HELP_TOPICS, $update_array, ['id' => $id]);
                $this->session->set_flashdata('success', htmlentities($helptopic['title']) . ' has been deleted successfully!');
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('admin/Helptopics');
        } else {
            show_404();
        }
    }

    /**
     * This function used to check Unique Title at the time of restaurant's add at admin side
     * */
    public function checkUniqueTitle()
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
    public function view_helptopic()
    {
        $helptopic_id = base64_decode($this->input->post('id'));
        $helptopic = $this->Help_topics->get_help_topics_detail(['id' => $helptopic_id]);
        if ($helptopic) {
            $data['helptopic'] = $helptopic;
            return $this->load->view('Backend/admin/helptopics/view', $data);
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
            $helptopic = $this->Help_topics->get_help_topics_detail(['id' => $id], 'id,title,is_active');
            $is_active;
            $msg = array();
            if ($helptopic) {
                if ($helptopic['is_active'] == 1) {
                    $is_active = 0;
                    $msg['status'] = 1;
                    $msg['msg'] = htmlentities($helptopic['title']) . ' has been disable successfully!';
                } else {
                    $is_active = 1;
                    $msg['status'] = 0;
                    $msg['msg'] = htmlentities($helptopic['title']) . ' has been enable successfully!';
                }
                $update_array = array(
                    'is_active' => $is_active,
                );
                $this->Help_topics->common_insert_update('update', TBL_HELP_TOPICS, $update_array, ['id' => $id]);
                header('Content-Type: application/json');
                echo json_encode($msg);
                exit;
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('admin/Helptopics');
        } else {
            show_404();
        }
    }
}
