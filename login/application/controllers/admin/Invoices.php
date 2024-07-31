<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoices extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Users_model','Active_devices','Package_request','Invoices_model']);
    }

    public function index()
    {
        $data['title'] = WEBNAME.' | Invoices';
        $data['head'] = 'Invoices';
        $this->template->load('default', 'Backend/admin/invoices/index', $data);
    }

    /**
     * This function used to get packages data for ajax table
     * */
    public function get_details()
    {
        $final['recordsFiltered'] = $final['recordsTotal'] = $this->Invoices_model->get_package_request('count');
        $final['redraw'] = 1;
        $packages = $this->Invoices_model->get_package_request('result');
        $start = $this->input->get('start') + 1;
        foreach ($packages as $key => $val) {
            $packages[$key] = $val;
            $packages[$key]['sr_no'] = $start++;
            $packages[$key]['user_name'] = htmlentities($val['user_name']);
        }
        $final['data'] = $packages;
        echo json_encode($final);
    }

    /**
     * Generate Invoices
     * */
    public function generate_invoce($id)
    {
        // require_once APPPATH.'/../third_party/mpdf/mpdf.php';
        // $mpdf = new Mpdf();
        // $mpdf->SetDisplayMode('fullpage');
        // $mpdf->list_indent_first_level = 0;
        // $mpdf->WriteHTML('<h1>Hello world!</h1>');
        // $mpdf->Output();
    }
}