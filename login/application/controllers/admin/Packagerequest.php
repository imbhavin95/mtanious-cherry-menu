<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Packagerequest extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Package_request','Menus_model','Items_model']);
    }

    public function index()
    {
        $data['title'] = WEBNAME.' | Package Request';
        $data['head'] = 'Package Request';
        $this->template->load('default', 'Backend/admin/packagerequest/index', $data);
    }

    /**
     * This function used to get packages data for ajax table
     * */
    public function get_package_request()
    {
        $final['recordsFiltered'] = $final['recordsTotal'] = $this->Package_request->get_package_request('count');
        $final['redraw'] = 1;
        $packages = $this->Package_request->get_package_request('result');
        $start = $this->input->get('start') + 1;
        foreach ($packages as $key => $val) {
            $packages[$key] = $val;
            $packages[$key]['sr_no'] = $start++;
            $packages[$key]['user_name'] = htmlentities($val['user_name']);
            $packages[$key]['package_name'] = htmlentities($val['package_name']);
            $packages[$key]['created_at'] = date('d M Y', strtotime($val['created_at']));
            $packages[$key]['users'] = $val['users'];
            $packages[$key]['menus'] = $val['menus'];
            $packages[$key]['categories'] = $val['categories'];
            $packages[$key]['items'] = $val['items'];
            $packages[$key]['devices_limit'] = $val['devices_limit'];
            $packages[$key]['pkg_id'] = $val['pkg_id'];
        }
        $final['data'] = $packages;
        echo json_encode($final);
    }

    /**
     * enable and disable of package
     * @param int $id
     * */
    public function change_status()
    {
        $id = base64_decode($this->input->post('request_id'));
        $data = array();
        if (is_numeric($id)) {
            $package = $this->Package_request->get_detail(['id' => $id], '*');
            if ($package) {
                $update_array = array(
                    'status' => $this->input->post('status'),
                );
                $this->Package_request->common_insert_update('update', TBL_PACKAGE_DETAILS, $update_array, ['id' => $id]);
                $this->session->set_flashdata('success', 'Status has been changed successfully!');
                $data = array('success' => 'Status has been changed successfully!');
                echo json_encode($data);
                exit;
                // redirect('admin/packagerequest/index');
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('admin/packagerequest');
        } else {
            show_404();
        }
    }

    public function get_status() 
    {
        $data = json_decode (STATUS);
        echo json_encode($data);
        exit;
    }

    /*
    *   Assign Packages to restaurant
    */
    public function assign_package($id)
    {
        if(!is_null($id)) 
        {
            $id = base64_decode($id);
        }else 
        {
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('admin/packages');
        }

        if (is_numeric($id)) 
        {
            $package_details = $this->Package_request->get_detail(['id' => $id], '*');
            $restaurant = $this->Package_request->get_all_details(TBL_USERS,['id'=> $package_details['restaurant_id'],'is_deleted' => 0])->result_array();
            $package = $this->Package_request->get_all_details(TBL_PACKAGES,['id'=> $package_details['package_id'],'is_deleted' => 0])->result_array();
            if (!empty($package) && !empty($restaurant)) 
            {
                $update_restaurant = array('users_limit' => $package[0]['users'],
                                'menus_limit' => $package[0]['menus'],
                                'categories_limit' => $package[0]['categories'],
                                'items_limit' => $package[0]['items'],
                                'devices_limit' => $package[0]['devices_limit']
                            );
                $datevalue=date('Y-m-d H:i:s');
                if($package[0]['duration']=="1 month")
                {
                  $enddatevalue=" +1 months";
                }
                else
                if($package[0]['duration']=="3 months")
                {
                  $enddatevalue=" +3 months";
                }
                else
                if($package[0]['duration']=="6 months")
                {
                  $enddatevalue=" +6 months";
                }else
                if($package[0]['duration']=="1 Year")
                {
                 $enddatevalue=" +12 months";
                }
               $effectiveDate = date('Y-m-d h:i:s', strtotime($enddatevalue, strtotime($datevalue))); 
               // echo $enddatevalue1 = date($datevalue, strtotime($enddatevalue));die;
                 $update_restaurant['updated_at'] = date('Y-m-d H:i:s');
                //increase menus,users,categories and items limit in users table as per package.
                $this->users_model->common_insert_update('update', TBL_USERS,$update_restaurant, ['id' => $package_details['restaurant_id']]);
                $this->users_model->common_insert_update('update', TBL_PACKAGE_DETAILS,['flag' => '0'], ['id !=' => $id,'restaurant_id' => $package_details['restaurant_id']]);
                $this->users_model->common_insert_update('update', TBL_PACKAGE_DETAILS,['status' => 'activate','end_date'=>$effectiveDate,'flag' => '1','updated_at' =>date('Y-m-d H:i:s')], ['id' => $id]);
                //Send email for Package assign.

                $this->users_disable($package_details['restaurant_id'], $package[0]['users']);
                $this->menus_disable($package_details['restaurant_id'], $package[0]['menus']);
                $this->categories_disable($package_details['restaurant_id'], $package[0]['categories']);
                $this->items_disable($package_details['restaurant_id'], $package[0]['items']);
                $this->devices_disable($package_details['restaurant_id'], $package[0]['devices_limit']);

                $email_data = ['name' => trim($restaurant[0]['name']), 'email' => trim($restaurant[0]['email']),'subject' => 'Thank you! Your subscription is now confirmed'];
                $email_data['head_title'] = "Thank you for your purchase!";
                $email_data['message'] = "You have been successfully subscribed to <b>".$package[0]['name'].'</b><br/><br/>Attached is a copy of your invoice for your reference.';
                //$data =  $this->load->view('Backend/email_templates/reminder',$email_data);
                 $email_data22 = ['name' => '', 'email' => trim($restaurant[0]['email']),'subject' => 'Package subscription is now confirmed'];
                $email_data22['head_title'] = "The ".$package[0]['name']." is Activated ";
                $email_data22['message'] = "Restaurant ".$restaurant[0]['email']." is successfully subscribed to <b>".$package[0]['name'].'</b><br/><br/>Attached is a copy of your invoice for your reference.';

                //generate invoices PDF
                $invoice['restaurant'] = $restaurant[0]; 
                $invoice['package'] = $package[0]; 
                $invoice['package_detail'] = $package_details;
                $newFile  = APPPATH.'/../assets/Backend/invoice.pdf';
                $view =  $this->load->view('Backend/invoice',$invoice,true);
                $this->load->library('m_pdf');
                $this->m_pdf->pdf->WriteHTML($view);
                $this->m_pdf->pdf->Output($newFile, 'F');
                send_email('info@cherrymenu.com', 'reminder', $email_data22,$newFile);
                send_email(trim($restaurant[0]['email']), 'reminder', $email_data,$newFile);
                $this->session->set_flashdata('success', htmlentities('Package has been assign successfully and send email to user!'));
            } 
            else 
            {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('admin/packagerequest');
        } 
        else 
        {
            show_404();
        }
    }

    public function users_disable($restaurant_id,$users_limit)
    {
        $users = $this->db->query("SELECT count(id) as total from ".TBL_USERS." where is_active='1' and is_deleted='0' and restaurant_id=".$restaurant_id)->row_array();
        if($users['total'] > $users_limit)
        {
            $changes = $users['total'] - $users_limit;
            $this->db->select('*');
            $this->db->order_by('id', 'DESC');  
            $this->db->from(TBL_USERS);
            $this->db->limit($changes);
            $this->db->where(array('is_deleted' => 0, 'is_active' => 1,'restaurant_id' => $restaurant_id));
            $query = $this->db->get()->result_array();
            if(!empty($query))
            {
                foreach ($query as $key => $row) {
                    $this->users_model->common_insert_update('update', TBL_USERS,['is_active' => 0], ['id' => $row['id']]);
                }
            }
        }
        return true;
    }

    public function menus_disable($restaurant_id,$menu_limit)
    {
        $menus = ($this->Menus_model->sql_select(TBL_MENUS, 'id', ['where' => ['is_deleted' => 0,'is_active' => 1, 'restaurant_id' => $restaurant_id]], ['count' => true]));
        if($menus > $menu_limit)
        {
            $changes = $menus - $menu_limit;
            $this->db->select('*');
            $this->db->order_by('id', 'DESC');  
            $this->db->from(TBL_MENUS);
            $this->db->limit($changes);
            $this->db->where(array('is_deleted' => 0, 'is_active' => 1,'restaurant_id' => $restaurant_id));
            $query = $this->db->get()->result_array();
            if(!empty($query))
            {
                foreach ($query as $key => $row) {
                    $id = $row['id'];
                    $update_date = date('Y-m-d H:i:s');
                    $this->users_model->common_insert_update('update', TBL_MENUS,['is_active' => 0,'updated_at' => date('Y-m-d H:i:s')], ['id' => $id]);
                    $this->db->query('UPDATE '.TBL_CATEGORIES.' as cate,'.TBL_CATEGORY_DETAILS.' as cd,'.TBL_MENUS.' as menu SET cate.updated_at = "'.$update_date.'", cate.is_active = 0 WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cd.menu_id = '.$id.' '); //Change Status of all categories
                    $this->db->query('UPDATE '.TBL_CATEGORIES.' as cate,'.TBL_ITEM_DETAILS.' as item_d,'.TBL_ITEMS.' as item,'.TBL_MENUS.' as menu SET item.updated_at = "'.$update_date.'", item.is_active = 0 WHERE menu.id = item_d.menu_id AND cate.id = item_d.category_id AND item_d.item_id = item.id AND item_d.menu_id = '.$id.''); //Change Status of all items
                }
            }
        }
        return true;
    }

    public function categories_disable($restaurant_id,$categories_limit)
    {
        $categories = $this->Menus_model->custom_Query('SELECT DISTINCT cd.category_id FROM '.TBL_CATEGORIES.' as cate, '.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND cate.id = cd.category_id AND menu.id = cd.menu_id AND user.id = '.$restaurant_id.' AND cate.is_deleted = 0 AND cate.is_active = 1 ');
        $category_limit = $categories->result_id->num_rows;
        if(!empty($category_limit) && $category_limit > $categories_limit)
        {
           $changes = $category_limit - $categories_limit;
           $query = $this->Menus_model->custom_Query('SELECT DISTINCT cd.category_id FROM '.TBL_CATEGORIES.' as cate, '.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND cate.id = cd.category_id AND menu.id = cd.menu_id AND user.id = '.$restaurant_id.' AND cate.is_deleted = 0 AND cate.is_active = 1 ORDER BY cate.id DESC LIMIT '.$changes)->result_array();
           if(!empty($query))
           {
               foreach ($query as $key => $row) {
                $id = $row['category_id'];
                $this->Categories_model->common_insert_update('update', TBL_CATEGORIES, ['is_active' => 0,'updated_at' => date('Y-m-d H:i:s')], ['id' => $id]);
                $this->db->query('UPDATE '.TBL_CATEGORIES.' as cate,'.TBL_ITEM_DETAILS.' as item_d,'.TBL_ITEMS.' as item,'.TBL_MENUS.' as menu SET item.updated_at = "'.date('Y-m-d H:i:s').'", item.is_active = 0 WHERE menu.id = item_d.menu_id AND cate.id = item_d.category_id AND item_d.item_id = item.id AND item_d.category_id = '.$id.' '); //Change Status of all items
                $this->db->query('UPDATE '.TBL_CATEGORIES.' as cate,'.TBL_CATEGORY_DETAILS.' as cd,'.TBL_MENUS.' as menu SET menu.updated_at = "'.date('Y-m-d H:i:s').'" WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cd.category_id = '.$id.' '); //Change updated_at of all parent menus
               }
           }
        }
        return true;
    }

    public function items_disable($restaurant_id,$items_limit)
    {
        $items = $this->Menus_model->custom_Query('SELECT DISTINCT item.id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND menu.id = item_d.menu_id AND item.id = item_d.item_id AND cate.id = item_d.category_id AND user.id = '.$restaurant_id.' AND item.is_deleted = 0 AND item.is_active=1');
        $itemLimit = $items->result_id->num_rows;
        if(!empty($itemLimit) && $itemLimit > $items_limit)
        {
           $changes = $itemLimit - $items_limit;
           $query = $this->Menus_model->custom_Query('SELECT DISTINCT item.id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND menu.id = item_d.menu_id AND item.id = item_d.item_id AND cate.id = item_d.category_id AND user.id = '.$restaurant_id.' AND item.is_deleted = 0 AND item.is_active=1 ORDER BY item.id DESC LIMIT '.$changes)->result_array();
           if(!empty($query))
           {
               foreach ($query as $key => $row) {
                    $id = $row['id'];
                    $this->Items_model->common_insert_update('update', TBL_ITEMS, ['is_active' => 0,'updated_at' => date('Y-m-d H:i:s')], ['id' => $id]);
                    $category_ids = ($this->Items_model->sql_select(TBL_ITEM_DETAILS, '*', ['where' => ['item_id' => $id]]));
                    foreach($category_ids as $row)
                    {
                        $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $row['category_id']]);
                        $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $row['menu_id']]);
                    }
               }
           }
        }
        return true;
    }

    public function devices_disable($restaurant_id,$device_limit)
    {
        $device_info = $this->users_model->sql_select(TBL_ACTIVE_DEVICES, '*', ['where' => ['restaurant_id' => $restaurant_id, 'is_login' => 1, 'is_deleted' => 0]], ['single' => false,'count' => 'id']);
        if($device_info > $device_limit)
        {
            $changes = $device_info - $device_limit;
            $this->db->select('*');
            $this->db->order_by('id', 'DESC');  
            $this->db->from(TBL_ACTIVE_DEVICES);
            $this->db->limit($changes);
            $this->db->where(array('is_deleted' => 0, 'is_login' => 1,'restaurant_id' => $restaurant_id));
            $query = $this->db->get()->result_array();
            if(!empty($query))
            {
                foreach ($query as $key => $row) {
                    $this->users_model->common_insert_update('update', TBL_ACTIVE_DEVICES,['is_login' => 0], ['id' => $row['id']]);
                }
            }
        }
        return true;
    }
}
