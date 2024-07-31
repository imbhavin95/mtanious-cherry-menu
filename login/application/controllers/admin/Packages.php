<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Packages extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Packages_model');
    }

    public function index()
    {
        $data['title'] = WEBNAME.' | Packages';
        $data['head'] = 'Packages';
        $this->template->load('default', 'Backend/admin/packages/index', $data);
    }

    /**
     * This function used to get packages data for ajax table
     * */
    public function get_packages()
    {
        $final['recordsFiltered'] = $final['recordsTotal'] = $this->Packages_model->get_packages('count');
        $final['redraw'] = 1;
        $packages = $this->Packages_model->get_packages('result');
        $start = $this->input->get('start') + 1;
        foreach ($packages as $key => $val) {
            $packages[$key] = $val;
            $packages[$key]['sr_no'] = $start++;
            $packages[$key]['name'] = htmlentities($val['name']);
            $packages[$key]['description'] = htmlentities($val['description']);
            $packages[$key]['created_at'] = date('d M Y', strtotime($val['created_at']));
        }
        $final['data'] = $packages;
        echo json_encode($final);
    }

    

    /**
     * This function used to add / edit package data
     * @param int $id
     * */
    public function add($id = null)
    {
        if (!is_null($id)) {
            $id = base64_decode($id);
        }
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('arabic_name', 'arabic name', 'trim|required');
        $this->form_validation->set_rules('price', 'price', 'required');
        $this->form_validation->set_rules('users', 'users', 'required');
        $this->form_validation->set_rules('menus', 'menus', 'required');
        $this->form_validation->set_rules('categories', 'categories', 'required');
        $this->form_validation->set_rules('items', 'items', 'required');
        $this->form_validation->set_rules('discount', 'discount', 'required');
        $data['title'] = WEBNAME.' | Add Package';
        $data['head'] = 'Add';
        $data['label'] = 'Create New';
        $date = $this->input->post('duration');
        if ($date != '') {
            $dates = explode('-', $date);
            $start_date = $dates[0];
            $end_date = $dates[1];
        }
        if ($this->form_validation->run() == true) {
            $dataArr = array(
                'name' => trim($this->input->post('name')),
                'arabic_name' => trim($this->input->post('arabic_name')),
                'price' => trim($this->input->post('price')),
                'type' => 'paid',
                'users' => $this->input->post('users'),
                'menus' => $this->input->post('menus'),
                'categories' => $this->input->post('categories'),
                'items' => $this->input->post('items'),
                'devices_limit' => $this->input->post('devices_limit'),
                'discount' => $this->input->post('discount'),
                'start_date' => date('Y-m-d H:i:s'),
                'duration' => $this->input->post('duration'),
                // 'end_date' => date('Y-m-d', strtotime($end_date)),
                'description' => $this->input->post('description'),
                'arabic_description' => $this->input->post('arabic_description'),
            );
            $dataArr['created_at'] = date('Y-m-d H:i:s');
            $inserted_id = $this->Packages_model->common_insert_update('insert', TBL_PACKAGES, $dataArr);
            $this->session->set_flashdata('success', 'Package has been added successfully');
            redirect('admin/packages');
        }
        $this->template->load('default', 'Backend/admin/packages/manage', $data);
    }

    /**
     * Edit package data
     * @param int $id
     * */
    public function edit($id)
    {
        if (!is_null($id)) {
            $id = base64_decode($id);
        }else {
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('admin/packages');
        }
        
        if (is_numeric($id)) {
            $package = $this->Packages_model->get_package_detail(['id' => $id, 'is_deleted' => 0]);
            if ($package) {
                $data['package'] = $package;
                $data['title'] = WEBNAME.' | Edit Package';
                $data['head'] = 'Edit';
                $data['label'] = 'Edit';
                
                $this->form_validation->set_rules('name', 'name', 'trim|required');
                $this->form_validation->set_rules('arabic_name', 'arabic name', 'trim|required');
                $this->form_validation->set_rules('price', 'price', 'required');
                $this->form_validation->set_rules('menus', 'menus', 'required');
                $this->form_validation->set_rules('categories', 'categories', 'required');
                $this->form_validation->set_rules('items', 'items', 'required');
                $this->form_validation->set_rules('discount', 'discount', 'required');
                $this->form_validation->set_rules('duration', 'duration', 'required');
                $this->form_validation->set_rules('devices_limit', 'devices_limit', 'required');

                if ($this->form_validation->run() == true) 
                {
                   $dataArr = array(
                            'name' => trim($this->input->post('name')),
                            'arabic_name' => trim($this->input->post('arabic_name')),
                            'price' => trim($this->input->post('price')),
                            'type' => 'paid',
                            'menus' => $this->input->post('menus'),
                            'categories' => $this->input->post('categories'),
                            'items' => $this->input->post('items'),
                            'duration' => $this->input->post('duration'),
                            'discount' => $this->input->post('discount'),
                            'description' => $this->input->post('description'),
                            'arabic_description' => $this->input->post('arabic_description'),
                            'devices_limit'  => $this->input->post('devices_limit') 
                        );
                    $dataArr['updated_at'] = date('Y-m-d H:i:s');
                    $this->Packages_model->common_insert_update('update', TBL_PACKAGES, $dataArr, ['id' => $id]);
                    $this->session->set_flashdata('success', 'Package\'s data has been updated successfully.');
                    redirect('admin/packages');
                } else {
                    $this->template->load('default', 'Backend/admin/packages/manage', $data);
                }
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
                redirect('admin/packages');
            }
        }else {
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('admin/packages');
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
            $package = $this->Packages_model->get_package_detail(['id' => $id], 'id,name');
            if ($package) {
                $update_array = array(
                    'is_deleted' => 1,
                );
                $this->Packages_model->common_insert_update('update', TBL_PACKAGES, $update_array, ['id' => $id]);
                $this->session->set_flashdata('success', htmlentities($package['name']) . ' has been deleted successfully!');
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('admin/packages');
        } else {
            show_404();
        }
    }

    /**
     * View package
     * @return : View
     * @author : sm
     */
    public function view_package()
    {
        $package_id = base64_decode($this->input->post('id'));
        $package = $this->Packages_model->get_package_detail(['id' => $package_id]);
        if ($package) {
            $data['package'] = $package;
            return $this->load->view('Backend/admin/packages/view', $data);
        } else {
            show_404();
        }
    }

    /**
     * enable and disable of package
     * @param int $id
     * */
    public function change_status()
    {
        $id = $this->input->post('id');
        if (is_numeric($id)) {
            $package = $this->Packages_model->get_package_detail(['id' => $id], 'id,name,is_active');
            $is_active;
            $msg = array();
            if ($package) {
                if ($package['is_active'] == 1) {
                    $is_active = 0;
                    $msg['status'] = 1;
                    $msg['msg'] = htmlentities($package['name']) . ' has been disable successfully!';
                } else {
                    $is_active = 1;
                    $msg['status'] = 0;
                    $msg['msg'] = htmlentities($package['name']) . ' has been enable successfully!';
                }
                $update_array = array(
                    'is_active' => $is_active,
                );
                $this->Packages_model->common_insert_update('update', TBL_PACKAGES, $update_array, ['id' => $id]);
                header('Content-Type: application/json');
                echo json_encode($msg);
                exit;
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('admin/packages');
        } else {
            show_404();
        }
    }


    public function freePackage()
    {
        $package = $this->Packages_model->get_package_detail(['type' => 'free']);
        if ($package) 
        {
            $data['package'] = $package;
        }
        // echo '<pre>';
        // print_r($package['id']);
        // die;
        
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('arabic_name', 'arabic name', 'trim|required');
        $this->form_validation->set_rules('devices_limit', 'Devices Limit', 'required');
        $this->form_validation->set_rules('users', 'users', 'required');
        $this->form_validation->set_rules('menus', 'menus', 'required');
        $this->form_validation->set_rules('categories', 'categories', 'required');
        $this->form_validation->set_rules('items', 'items', 'required');
        $data['title'] = WEBNAME.' | Free Package';
        $data['head'] = 'Free Trial';
        $data['label'] = 'Free Trial';
        // $date = $this->input->post('date');
        // if ($date != '') {
        //     $dates = explode('-', $date);
        //     $start_date = $dates[0];
        //     $end_date = $dates[1];
        // }
        if ($this->form_validation->run() == true) {
            $dataArr = array(
                'name' => trim($this->input->post('name')),
                'arabic_name' => trim($this->input->post('arabic_name')),
                'type' => 'free',
                'devices_limit' => $this->input->post('devices_limit'),
                'users' => $this->input->post('users'),
                'menus' => $this->input->post('menus'),
                'categories' => $this->input->post('categories'),
                'items' => $this->input->post('items'),
                'description' => $this->input->post('description'),
                'arabic_description' => $this->input->post('arabic_description'),
            );
           
            if (empty($package)) 
            {
                $dataArr['created_at'] = date('Y-m-d H:i:s');
                $inserted_id = $this->Packages_model->common_insert_update('insert', TBL_PACKAGES, $dataArr);
                $this->session->set_flashdata('success', 'Package has been added successfully');
            }
            else
            {
                $dataArr['updated_at'] = date('Y-m-d H:i:s');
                $this->Packages_model->common_insert_update('update', TBL_PACKAGES, $dataArr, ['id' => $package['id']]);
                $this->session->set_flashdata('success', 'Package\'s data has been updated successfully.');
            }
            redirect('admin/packages/freePackage');
        }
        $this->template->load('default', 'Backend/admin/packages/freetrial', $data);
    }

    /*
    *   Assign Packages to restaurant
    */
    public function assign_package($id)
    {
        if (!is_null($id)) 
        {
            $id = base64_decode($id);
        }else 
        {
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('admin/packages');
        }

        if (is_numeric($id)) {
            $restaurant = $this->Package_request->get_all_details(TBL_USERS,['is_deleted' => 0,'is_active' => 1])->result_array();
            $package = $this->Packages_model->get_package_detail(['id' => $id, 'is_deleted' => 0]);
            if (!empty($package) && !empty($users)) {
                $data['package'] = $package;
                $data['restaurant'] = $restaurant;
                $data['title'] = WEBNAME.' | Assign Package';
                $data['head'] = 'Assign Package';
                $data['label'] = 'Assign Package';
            }
        }
    }
}
