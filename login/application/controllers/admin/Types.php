<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Types extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Types_model');
    }

    public function index()
    {
        $data['title'] = WEBNAME.' | Types';
        $data['head'] = 'Types';
        $this->template->load('default', 'Backend/admin/types/index', $data);
    }

    /**
     * This function used to get helpTopics data for ajax table
     * */
    public function get_types()
    {
        $final['recordsFiltered'] = $final['recordsTotal'] = $this->Types_model->get_types('count');
        $final['redraw'] = 1;
        $helpTopics = $this->Types_model->get_types('result');
        $start = $this->input->get('start') + 1;
        foreach ($helpTopics as $key => $val) {
            $helpTopics[$key] = $val;
            $helpTopics[$key]['sr_no'] = $start++;
            $helpTopics[$key]['type'] = htmlentities($val['type']);
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
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $data['title'] = WEBNAME.' | Add Type';
        $data['head'] = 'Add';
        $data['label'] = 'Create New';
        if ($this->form_validation->run() == true) {
            $dataArr = array(
                'type' => trim($this->input->post('type')),
            );
            $inserted_id = $this->Types_model->common_insert_update('insert', TBL_TYPES, $dataArr);
            $this->session->set_flashdata('success', 'Type has been added successfully');
            redirect('admin/types');
        }
        $this->template->load('default', 'Backend/admin/types/manage', $data);
    }

    /**
     * Edit helpTopics data
     * @param int $id
     * */
    public function edit($id)
    {
        if (!is_null($id)) {
            $id = base64_decode($id);
        }else{
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('admin/types');
        }
        if (is_numeric($id)) {
            $types = $this->Types_model->get_types_detail(['id' => $id, 'is_deleted' => 0]);
            if ($types) {
                $data['type'] = $types;
                $data['title'] = WEBNAME.' | Edit Type';
                $data['head'] = 'Edit';
                $data['label'] = 'Edit';
                $this->form_validation->set_rules('type', 'Type', 'trim|required');
                if ($this->form_validation->run() == true) 
                {
                    $dataArr = array('type' => trim($this->input->post('type')));
                    $dataArr['updated_at'] = date('Y-m-d H:i:s');
                    $this->Types_model->common_insert_update('update', TBL_TYPES, $dataArr, ['id' => $id]);
                    $this->session->set_flashdata('success', 'Type\'s data has been updated successfully.');
                    redirect('admin/types');
                } else {
                    $this->template->load('default', 'Backend/admin/types/manage', $data);
                }
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('admin/types');
            }
        }
        else
        {
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('admin/types');
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
     * Delete Types
     * @param int $id
     * */
    public function delete($id = null)
    {
        $id = base64_decode($id);
        if (is_numeric($id)) {
            $types = $this->Types_model->get_types_detail(['id' => $id], 'id,type');
            if ($types) {
                $update_array = array(
                    'is_deleted' => 1,
                );
                $this->Types_model->common_insert_update('update', TBL_TYPES, $update_array, ['id' => $id]);
                $this->session->set_flashdata('success', htmlentities($types['type']) . ' has been deleted successfully!');
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('admin/types');
        } else {
            show_404();
        }
    }

    /**
     * This function used to check Unique Title at the time of restaurant's add at admin side
     * */
    public function checkUniqueType()
    {
        $type = trim($this->input->post('type'));
        $type_id = base64_decode($this->input->post('type_id'));
        $types = $this->Types_model->check_unique_type($type, $type_id);
        if ($types) {
            echo "false";
        } else {
            echo "true";
        }
        exit;
    }

    /**
     * enable and disable of restaurant
     * @param int $id
     * */
    public function change_status()
    {
        $id = $this->input->post('id');
        if (is_numeric($id)) {
            $types = $this->Types_model->get_types_detail(['id' => $id], 'id,type,is_active');
            $is_active;
            $msg = array();
            if ($types) {
                if ($types['is_active'] == 1) {
                    $is_active = 0;
                    $msg['status'] = 1;
                    $msg['msg'] = htmlentities($types['type']) . ' has been disable successfully!';
                } else {
                    $is_active = 1;
                    $msg['status'] = 0;
                    $msg['msg'] = htmlentities($types['type']) . ' has been enable successfully!';
                }
                $update_array = array(
                    'is_active' => $is_active,
                );
                $this->Types_model->common_insert_update('update', TBL_TYPES, $update_array, ['id' => $id]);
                header('Content-Type: application/json');
                echo json_encode($msg);
                exit;
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('admin/types');
        } else {
            show_404();
        }
    }
}
