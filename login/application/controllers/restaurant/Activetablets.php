<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Activetablets extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Users_model','Active_devices']);
    }

    public function index()
    {
        if(is_sub_admin())
        {
            $this->session->set_flashdata('error', 'Access denied!');
            redirect('restaurant/menus');
        }
        $data['title'] = WEBNAME.' | Active Devices';
        $data['head'] = 'Active Devices';
        $data['androidversion'] = $this->Active_devices->get_androidversion(); 
        $this->template->load('default', 'Backend/restaurant/activeteblets/index', $data);
    }


    //get all login device
    public function get_devices()
    {
        $final['recordsFiltered'] = $final['recordsTotal'] = $this->Active_devices->get_devices('count');
        $final['redraw'] = 1;
        $devices = $this->Active_devices->get_devices('result');
        $start = $this->input->get('start') + 1;
        foreach ($devices as $key => $val) {
            $devices[$key] = $val;
            $devices[$key]['name'] = htmlentities($val['name']);
            $devices[$key]['sr_no'] = $start++;
            $devices[$key]['created_at'] = date('d M Y', strtotime($val['created_at']));
        }
        $final['data'] = $devices;
        echo json_encode($final);
    }

    /**
    * Delete Active device
    * @param int $id
    * */
    public function delete($id = null)
    {
        $id = base64_decode($id);
        if (is_numeric($id)) {
            $devices = $this->Active_devices->get_device_detail(['id' => $id], 'id,name');
            if ($devices) {
                $update_array = array(
                    'is_deleted' => 1,
                );
                $this->Active_devices->common_insert_update('update', TBL_ACTIVE_DEVICES, $update_array, ['id' => $id]);
                $this->session->set_flashdata('success', htmlentities($devices['name']) . ' has been deleted successfully!');
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('restaurant/activetablets');
        } else {
            show_404();
        }
    }

        /**
     * enable and disable of Active Devices
     * @param int $id
     * */
    public function change_status()
    {
        $id = $this->input->post('id');
        if (is_numeric($id)) {
            $devices = $this->Active_devices->get_device_detail(['id' => $id], 'id,name,is_active');
            $is_active;
            $msg = array();
            if ($devices) {
                if ($devices['is_active'] == 1) {
                    $is_active = 0;
                    $msg['status'] = 1;
                    $msg['msg'] = htmlentities($devices['name']) . ' has been disable successfully!';
                } else {
                    $is_active = 1;
                    $msg['status'] = 0;
                    $msg['msg'] = htmlentities($devices['name']) . ' has been enable successfully!';
                }
                $update_array = array(
                    'is_active' => $is_active,
                );
                $this->Active_devices->common_insert_update('update', TBL_ACTIVE_DEVICES, $update_array, ['id' => $id]);
                header('Content-Type: application/json');
                echo json_encode($msg);
                exit;
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('restaurant/activetablets');
        } else {
            show_404();
        }
    }
}
