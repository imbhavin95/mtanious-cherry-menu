<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reports_model');
        if(is_sub_admin())
        {
            $this->session->set_flashdata('error', 'Access denied!');
            redirect('restaurant/menus');
        }
    }

    public function index()
    {
        $data['title'] = WEBNAME.' | Reports';
        $data['head'] = 'Reports';
        $this->template->load('default', 'Backend/restaurant/reports/index', $data);
    }

    /**
     * This function used to get categories data for ajax table
     * */
    public function get_reports()
    {
        $final['recordsFiltered'] = $final['recordsTotal'] = $this->Reports_model->get_reports('count');
        $final['redraw'] = 1;
        $items = $this->Reports_model->get_reports('result');
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) 
        {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['name'] = htmlentities($val['name']);
            $items[$key]['title'] = htmlentities($val['title']);
            $items[$key]['type'] = htmlentities($val['type']);
            // $items[$key]['category_title'] = htmlentities($val['category_title']);
            $items[$key]['created_at'] = date('d M Y', strtotime($val['created_at']));
        }
        $final['data'] = $items;
        // print_r($final);
        echo json_encode($final);
    }


    public function category_clicks()
    {
        $data['title'] = WEBNAME.' | Reports';
        $data['head'] = 'Reports';
        $this->template->load('default', 'Backend/restaurant/reports/category_index', $data);
    }

    /**
     * This function used to get categories data for ajax table
     * */
    public function get_category_reports()
    {
        $final['recordsFiltered'] = $final['recordsTotal'] = $this->Reports_model->get_category_reports('count');
        $final['redraw'] = 1;
        $items = $this->Reports_model->get_category_reports('result');
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) 
        {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['name'] = htmlentities($val['name']);
            $items[$key]['title'] = htmlentities($val['title']);
            $items[$key]['created_at'] = date('d M Y', strtotime($val['created_at']));
        }
        $final['data'] = $items;
        // print_r($final);
        echo json_encode($final);
    }
}
