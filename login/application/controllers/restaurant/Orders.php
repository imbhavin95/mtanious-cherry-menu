<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Orders extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Users_model','Orders_model']);
    }

    public function index()
    {
        
        $data['title'] = WEBNAME.' | Orders';
        $data['head'] = 'Orders';
        $this->template->load('default', 'Backend/restaurant/orders/index', $data);
    }


    //get all login device
    public function get_orders()
    {
        $final['recordsFiltered'] = $final['recordsTotal'] = $this->Orders_model->get_orders('count');
        $final['redraw'] = 1;
        $devices = $this->Orders_model->get_orders('result');
        $start = $this->input->get('start') + 1;
        foreach ($devices as $key => $val) {
            $devices[$key] = $val;
            $devices[$key]['order_id'] = $val['order_id'];
             $devices[$key]['staff_id'] = $val['staff_id'];
            $devices[$key]['sr_no'] = $start++;
            $devices[$key]['ordered_time'] = date('Y-m-d', strtotime($val['ordered_time']));//date('d M Y', strtotime($val['ordered_time']));
        }
        $final['data'] = $devices;
        echo json_encode($final);
    }

    public function view_order()
    {
        $order_id = base64_decode($this->input->post('id'));
        $order = $this->Orders_model->get_order_detail($order_id);
      // echo $this->db->last_query();
        if ($order) {
            $data['order'] = $order;
            return $this->load->view('Backend/restaurant/orders/view', $data);
        } else {
            show_404();
        }
    }
     public function orderreport()
    {
        $data['title'] = WEBNAME.' | Reports';
        $data['head'] = 'Reports';
        $this->template->load('default', 'Backend/restaurant/orders/order_report', $data);
    }
     public function get_reports()
    {
        $final['recordsFiltered'] = $final['recordsTotal'] = $this->Orders_model->get_reports('count');
        $final['redraw'] = 1;
        $items = $this->Orders_model->get_reports('result');
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) 
        {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['name'] = htmlentities($val['name']);
            $items[$key]['title'] = htmlentities($val['title']);
            //$items[$key]['type'] = htmlentities($val['type']);
           // $items[$key]['no_of_clicks'] = htmlentities($val['no_of_clicks']);
            // $items[$key]['category_title'] = htmlentities($val['category_title']);
            //$items[$key]['created_at'] = date('d M Y', strtotime($val['created_at']));
        }
        $final['data'] = $items;
        // print_r($final);
        echo json_encode($final);
    }
}
