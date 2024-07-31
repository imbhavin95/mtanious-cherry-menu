<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Packages extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Packages_model','Package_request']);
        if(is_sub_admin())
        {
            $this->session->set_flashdata('error', 'Access denied!');
            redirect('restaurant/menus');
        }
    }

    public function index()
    {
        $data['title'] = WEBNAME.' | Packages';
        $data['head'] = 'Packages';
        $data['active_package'] = $this->users_model->custom_Query('SELECT pd.*,p.users,p.menus,p.categories,p.items,p.devices_limit,p.name,p.type FROM '.TBL_PACKAGE_DETAILS.' as pd,'.TBL_PACKAGES.' as p,'.TBL_USERS.' as user WHERE p.id = pd.package_id AND user.id = pd.restaurant_id AND pd.restaurant_id = '.$this->session->userdata('login_user')['id'].' AND pd.is_deleted = 0 AND pd.status="activate" AND pd.flag=1')->result();
       // echo $this->db->last_query();die;
        if(!empty($data['active_package']))
        {
            if($data['active_package'][0]->type == 'free')
            {
                $data['remaining_day'] = $this->count_days($data['active_package'][0]->created_at);
            }
        }
         /*USER CUSTOMIZED PLAN*/
        $data['custom_user_plan']=$this->users_model->custom_Query('SELECT *  FROM `users` WHERE `id` = '.$this->session->userdata('login_user')['id'].' and is_active=1 and is_deleted=0')->result();
        /*USER CUSTOMIZED PLAN*/
        $data['package'] = ($this->Packages_model->sql_select(TBL_PACKAGES, '*', ['where' => ['is_deleted' => 0, 'is_active' => 1,'type' =>'paid']]));
        $data['package_details'] = $this->users_model->custom_Query('SELECT pd.id,pd.updated_at,p.price,p.id as pkgid FROM '.TBL_PACKAGE_DETAILS.' as pd,'.TBL_PACKAGES.' as p,'.TBL_USERS.' as user WHERE p.id = pd.package_id AND user.id = pd.restaurant_id AND pd.restaurant_id = '.$this->session->userdata('login_user')['id'].' AND pd.is_deleted = 0 AND pd.status="activate" AND p.type!="free" and pd.updated_at is not NULL')->result();
        // echo $this->db->last_query();die;
        $this->template->load('default', 'Backend/restaurant/packages/index', $data);
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
            $packages[$key]['start_date'] = date('d M Y', strtotime($val['start_date']));
            if(!empty($val['end_date'])){
                $packages[$key]['end_date'] = date('d M Y', strtotime($val['end_date']));
            }else{
                $packages[$key]['end_date'] = '';
            }
            
        }
        $final['data'] = $packages;
        echo json_encode($final);
    }

    /**
     * This function use for listing package
     * */
    public function list()
    {
        $data['title'] = WEBNAME.' | Packages';
        $data['head'] = 'Packages';
         $data['package_details'] = $this->users_model->custom_Query('SELECT DISTINCT p.id as pid   FROM '.TBL_PACKAGE_DETAILS.' as pd,'.TBL_PACKAGES.' as p,'.TBL_USERS.' as user WHERE p.id = pd.package_id AND user.id = pd.restaurant_id AND pd.restaurant_id = '.$this->session->userdata('login_user')['id'].' AND pd.is_deleted = 0 AND pd.status="activate" AND p.type!="free" and flag=1  or p.id = pd.package_id AND user.id = pd.restaurant_id AND pd.restaurant_id = '.$this->session->userdata('login_user')['id'].' AND pd.is_deleted = 0 AND pd.status="new" AND p.type!="free"')->result();
         $maindata= $this->users_model->custom_Query('SELECT DISTINCT p.id FROM '.TBL_PACKAGE_DETAILS.' as pd,'.TBL_PACKAGES.' as p,'.TBL_USERS.' as user WHERE p.id = pd.package_id AND user.id = pd.restaurant_id AND pd.restaurant_id = '.$this->session->userdata('login_user')['id'].' AND pd.is_deleted = 0 AND pd.status="activate" AND p.type!="free" and DATE(now()) <= DATE(pd.end_date) or p.id = pd.package_id AND user.id = pd.restaurant_id AND pd.restaurant_id = '.$this->session->userdata('login_user')['id'].' AND pd.is_deleted = 0 AND pd.status="new" AND p.type!="free"')->result();

      
      
          if(isset($maindata) && !empty($maindata)){
            $data['package_details']=$maindata;
          }
        // echo $this->db->last_query();die;
        $this->template->load('default', 'Backend/restaurant/packages/list', $data);
    }

    /**
     * Delete user
     * @param int $id
     * */
    public function send_request($id = null)
    {
        $id = base64_decode($id);
        if (is_numeric($id)) {
            $package = $this->Packages_model->get_package_detail(['id' => $id], 'id,name');
            if ($package) {
                $dataArr = array(
                    'package_id' => $id,
                    'restaurant_id' => $this->session->userdata('login_user')['id'],
                    'status' => 'new',
                );
                $dataArr['request_date'] = date('Y-m-d');
                $dataArr['created_at'] = date('Y-m-d H:i:s');
                $this->Package_request->common_insert_update('insert', TBL_PACKAGE_DETAILS, $dataArr);


                $package_details = $this->Package_request->get_detail(['id' => $id], '*');
            $restaurant = $this->Package_request->get_all_details(TBL_USERS,['id'=> $dataArr['restaurant_id'],'is_deleted' => 0])->result_array();
            $package = $this->Package_request->get_all_details(TBL_PACKAGES,['id'=> $dataArr['package_id'],'is_deleted' => 0])->result_array();

            $email_data = ['name' => trim($restaurant[0]['name']), 'email' => trim($restaurant[0]['email']),'subject' => "Request for ".$package[0]['name']];
                $email_data['head_title'] = "Request for a Plan";
                $email_data['message'] = "You have requested for a (".$package[0]['name'].") subscription . Your request will be processed shortly. A confirmation e-mail will be sent once done.";
                 //generate invoices PDF
                $invoice['restaurant'] = $restaurant[0]; 
                $invoice['package'] = $package[0]; 
                $invoice['package_detail'] = $package_details;
                //$newFile  = APPPATH.'/../assets/Backend/invoice.pdf';
                $view =  $this->load->view('Backend/invoice',$invoice,true);
                // $this->load->library('m_pdf');
                // $this->m_pdf->pdf->WriteHTML($view);
                
                send_email(trim($restaurant[0]['email']), 'reminder', $email_data,$newFile);
                send_email('info@cherrymenu.com', 'reminder', $email_data,$newFile);

                $this->session->set_flashdata('success', htmlentities('Request has been sent successfully!'));
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('restaurant/packages');
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
            return $this->load->view('Backend/restaurant/packages/view', $data);
        } else {
            show_404();
        }
    }

    /*
    *   Assign Packages to restaurant
    */
    public function invoices($id)
    {
        if(!is_null($id)) 
        {
            $id = base64_decode($id);
        }else 
        {
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('restaurant/packages');
        }

        if (is_numeric($id)) 
        {
            $package_details = $this->Package_request->get_detail(['id' => $id], '*');
            $restaurant = $this->Package_request->get_all_details(TBL_USERS,['id'=> $package_details['restaurant_id'],'is_deleted' => 0])->result_array();
            $package = $this->Package_request->get_all_details(TBL_PACKAGES,['id'=> $package_details['package_id'],'is_deleted' => 0])->result_array();
            if (!empty($package) && !empty($restaurant)) 
            {  
                //generate invoices PDF
                $data['restaurant'] =$restaurant[0]; 
                $data['package'] =$package[0]; 
                $data['package_detail'] =$package_details;
                $view =  $this->load->view('Backend/invoice',$data,true);
                $this->load->library('m_pdf');
                $this->m_pdf->pdf->WriteHTML($view);
                $this->m_pdf->pdf->Output('invoice.pdf', 'D');
                exit;
            } 
            else 
            {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('restaurant/package');
        } 
        else 
        {
            show_404();
        }
    }

    public function test()
    {
        $id=13;
        $package_details = $this->Package_request->get_detail(['id' => $id], '*');
        $restaurant = $this->Package_request->get_all_details(TBL_USERS,['id'=> $package_details['restaurant_id'],'is_deleted' => 0])->result_array();
        $package = $this->Package_request->get_all_details(TBL_PACKAGES,['id'=> $package_details['package_id'],'is_deleted' => 0])->result_array();
        if (!empty($package) && !empty($restaurant)) 
        {  
            //generate invoices PDF
            $data['restaurant'] =$restaurant[0]; 
            $data['package'] =$package[0]; 
            $data['package_detail'] =$package_details;
            $view =  $this->load->view('Backend/invoice',$data,true);
            $this->load->library('m_pdf');
            $this->m_pdf->pdf->WriteHTML($view);
            $this->m_pdf->pdf->Output();
            exit;
        }
    }


    public function count_days($date)
    {
        $now = date('Y-m-d H:i:s');
        $days = 30;
        $created_date = strtotime("+".$days." days", strtotime($date));
        $last_date = date("Y-m-d H:i:s", $created_date);
        $datediff =  strtotime($last_date) - strtotime($now);
        return round($datediff / (60 * 60 * 24));
    }




    public function send_request1($id = null)
    {
        $id = base64_decode($id);
        if (is_numeric($id)) {
            $package = $this->Packages_model->get_package_detail(['id' => $id], 'id,name');
            if ($package) {
                $dataArr = array(
                    'package_id' => $id,
                    'restaurant_id' => $this->session->userdata('login_user')['id'],
                    'status' => 'new',
                );
                $dataArr['request_date'] = date('Y-m-d');
                $dataArr['created_at'] = date('Y-m-d H:i:s');
                $this->Package_request->common_insert_update('insert', TBL_PACKAGE_DETAILS, $dataArr);


                $package_details = $this->Package_request->get_detail(['id' => $id], '*');
            $restaurant = $this->Package_request->get_all_details(TBL_USERS,['id'=> $dataArr['restaurant_id'],'is_deleted' => 0])->result_array();
            $package = $this->Package_request->get_all_details(TBL_PACKAGES,['id'=> $dataArr['package_id'],'is_deleted' => 0])->result_array();

            $email_data = ['name' => trim($restaurant[0]['name']), 'email' => trim($restaurant[0]['email']),'subject' => "Request for ".$package[0]['name']];
                $email_data['head_title'] = "Request for a Plan";
                $email_data['message'] = "You have requested for a (".$package[0]['name'].") subscription . Your request will be processed shortly. A confirmation e-mail will be sent once done.";
                 //generate invoices PDF
                $invoice['restaurant'] = $restaurant[0]; 
                $invoice['package'] = $package[0]; 
                $invoice['package_detail'] = $package_details;
                 $newFile  = APPPATH.'/../assets/Backend/invoice.pdf';
                $view =  $this->load->view('Backend/invoice',$invoice,true);
                  $this->load->library('m_pdf');
                  $this->m_pdf->pdf->WriteHTML($view);
                
                //send_email(trim($restaurant[0]['email']), 'reminder', $email_data,$newFile);
                send_email('vinayak@virtualdusk.com', 'reminder', $email_data,$newFile);

                $this->session->set_flashdata('success', htmlentities('Request has been sent successfully!'));
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('restaurant/packages');
        } else {
            show_404();
        }
    }


}
