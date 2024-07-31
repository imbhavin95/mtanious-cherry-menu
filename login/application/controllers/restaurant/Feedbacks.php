<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Feedbacks extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Feedbacks_model','Users_model']);
        if(is_sub_admin())
        {
            $this->session->set_flashdata('error', 'Access denied!');
            redirect('restaurant/menus');
        }
    }

    public function index()
    {
        $data['title'] = WEBNAME.' | Feedbacks';
        $data['head'] = 'Feedbacks';
        $this->template->load('default', 'Backend/restaurant/feedbacks/index', $data);
    }

    /**
     * This function used to get feedbacks data for ajax table
     * */
    public function get_feedbacks()
    {
        $final['recordsFiltered'] = $final['recordsTotal'] = $this->Feedbacks_model->get_feedback('count');
        $final['redraw'] = 1;
        $feedbacks = $this->Feedbacks_model->get_feedback('result');
        $start = $this->input->get('start') + 1;
        foreach ($feedbacks as $key => $val) {
            $feedbacks[$key] = $val;
            $feedbacks[$key]['sr_no'] = $start++;
            $feedbacks[$key]['created_at'] = date('d M Y', strtotime($val['created_at']));
        }
        $final['data'] = $feedbacks;
        echo json_encode($final);
    }

   /**
    * Delete Feedback
    * @param int $id
    * */
    public function delete($id = null)
    {
        $id = base64_decode($id);
        if (is_numeric($id)) {
            $feedback = $this->Feedbacks_model->get_feedback_detail(['id' => $id], 'id,feedback');
            if ($feedback) {
                $update_array = array(
                    'is_deleted' => 1,
                );
                $this->Feedbacks_model->common_insert_update('update', TBL_FEEDBACKS, $update_array, ['id' => $id]);
                $this->session->set_flashdata('success','Feedback has been deleted successfully!');
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('restaurant/feedbacks');
        } else {
            show_404();
        }
    }

    /**
     * View Item
     * @return : View
     * @author : sm
     */
    public function view_item()
    {
        $item_id = base64_decode($this->input->post('id'));
        $item = $this->Items_model->get_item_detail(['id' => $item_id]);
        if ($item) {
            $data['item'] = $item;
            return $this->load->view('Backend/restaurant/items/view', $data);
        } else {
            show_404();
        }
    }


    /**
     * enable and disable of categories
     * @param int $id
     * */
    public function change_status()
    {
        $id = $this->input->post('id');
        if (is_numeric($id)) {
            $feedback = $this->Feedbacks_model->get_feedback_detail(['id' => $id], 'id,feedback,is_active');
            $is_active;
            $msg = array();
            if ($feedback) {
                if ($feedback['is_active'] == 1) {
                    $is_active = 0;
                    $msg['status'] = 1;
                    $msg['msg'] = 'Feedback has been disable successfully!';
                } else {
                    $is_active = 1;
                    $msg['status'] = 0;
                    $msg['msg'] = 'Feedback has been enable successfully!';
                }
                $update_array = array(
                    'is_active' => $is_active,
                );
                $this->Feedbacks_model->common_insert_update('update', TBL_FEEDBACKS, $update_array, ['id' => $id]);
                header('Content-Type: application/json');
                echo json_encode($msg);
                exit;
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('restaurant/feedbacks');
        } else {
            show_404();
        }
    }

}
