<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menus_default extends MY_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Menus_model','Package_request','Packages_model','Users_model']);
        $this->check_login_user();
    }

    public function index()
    {
        $data['title'] = WEBNAME.' | Menus';
        $data['head'] = 'Menus';
        $data['menu_limit'] = ($this->Menus_model->sql_select('default_menus', 'id', ['where' => ['is_deleted' => 0]], ['count' => true]));
        $data['package'] = $this->check_free_pacakge();
        $data['menus'] = $this->db->query('SELECT DISTINCT menu.id FROM '.TBL_CATEGORY_DETAILS.' as cate,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND menu.id = cate.menu_id AND user.id = '.$this->restaurant_id.'')->result();
       //print_r($this->db->last_query());die;
       // print_r( $data['menus'] );die;
        //$data['menus'] = $this->db->query('SELECT DISTINCT menu.id FROM '.TBL_CATEGORIES.' as cate,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND menu.id = cate.menu_id AND user.id = '.$this->restaurant_id.' AND cate.is_deleted = 0')->result();
        $this->template->load('default', 'Backend/restaurant/menus_default/index', $data);
    }

    /**
     * This function used to get menus data for ajax table
     * */
    public function get_menus()
    {
        $final['recordsFiltered'] = $final['recordsTotal'] = $this->Menus_model->get_menus_new('count');
        $final['redraw'] = 1;
        $menus = $this->Menus_model->get_menus_new('result');
        //print_r( $menus);die;
        $start = $this->input->get('start') + 1;
        foreach ($menus as $key => $val) {
            $menus[$key] = $val;
            $menus[$key]['sr_no'] = $start++;
            $menus[$key]['title'] = htmlentities($val['title']);
            $menus[$key]['arabian_title'] = htmlentities($val['arabian_title']);
            $menus[$key]['created_at'] = date('d M Y', strtotime($val['created_at']));
        }
        $final['data'] = $menus;
        //print_r($final);die;
        echo json_encode($final);
    }

    /**
     * This function used to add / edit menu data
     * @param int $id
     * */
    public function add($id = null)
    {
        
           

        $menu_limit = ($this->Menus_model->sql_select(TBL_MENUS, 'id', ['where' => ['is_deleted' => 0, 'restaurant_id' => $this->restaurant_id]], ['count' => true]));
        $package = $this->check_free_pacakge();
        if ($menu_limit < $this->session->userdata('login_user')['menus_limit']) 
        {
            if (!is_null($id)) {
                $id = base64_decode($id);
            }
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('arabian_title', 'Arabian Title', 'trim|required');
            $background_image = null;
            
            $data['title'] = WEBNAME.' | Add Menu';
            $data['head'] = 'Add';
            $data['label'] = 'Create New';
            if ($this->form_validation->run() == true) {
                $flag = 0;

                $directory_background = RESTAURANT_IMAGES . '/' . $this->restaurant_id. '/menus/backgrounds/';
                if (!file_exists(RESTAURANT_IMAGES . '/' . $this->restaurant_id. '/menus/')) {
                    mkdir(RESTAURANT_IMAGES . '/' . $this->restaurant_id. '/menus/');
                }
                if (!file_exists($directory_background)) {
                    mkdir($directory_background);
                }

                if ($_FILES['backgroundimg']['name'] != '') {
                    /*$image_data = upload_image('backgroundimg', $directory_background);
                    if (is_array($image_data)) {
                        $flag = 1;
                        $data['background_image_validation'] = $image_data['errors'];
                    } else {
                        if ($background_image != '') {
                            unlink($directory_background . $background_image);
                        }
                        $background_image = $image_data;
                    }*/
                    $extension = explode('/', $_FILES['backgroundimg']['type']);
                        $randname = uniqid() . time() . '.' . end($extension);
                        if($_FILES['backgroundimg']['size'] / 1024 <= 2048) { // 2MB
                         if($_FILES['backgroundimg']['type'] == 'image/jpeg' || 
                         $_FILES['backgroundimg']['type'] == 'image/pjpeg' || 
                         $_FILES['backgroundimg']['type'] == 'image/png' ||
                         $_FILES['backgroundimg']['type'] == 'image/gif'){
                      
                            $source_file = $_FILES['backgroundimg']['tmp_name'];
                            $target_file = $directory_background . $randname; 
                            $width      = '';
                            $height     = '';
                            $quality    = '40';
                            //$image_name = $_FILES['uploadImg']['name'];
                            $success = compress_image($source_file, $target_file, $width, $height, $quality);
                            if ($background_image != '') {
                            unlink($directory_background . $background_image);
                                }
                            $background_image = $randname;
                                }
                             }
                }


                
                $dataArr = array(
                    'restaurant_id' => $this->restaurant_id,
                    'title' => trim($this->input->post('title')),
                    'arabian_title' => trim($this->input->post('arabian_title')),
                    'background_image' => $background_image,
                    'is_disable_feedback' =>$this->input->post('is_disable_feedback'),
                    'is_active' => 1,
                );
                $dataArr['created_at'] = date('Y-m-d H:i:s');
                $inserted_id = $this->Menus_model->common_insert_update('insert', TBL_MENUS, $dataArr);
                $this->session->set_flashdata('success', 'Menu has been added successfully');
                redirect('restaurant/menus');
            }
            $this->template->load('default', 'Backend/restaurant/menus/manage', $data);
        }else
        {
            $this->session->set_flashdata('error', 'Limitation is finised!');
            redirect('restaurant/menus'); 
        }
    }

    /**
     * Edit menu data
     * @param int $id
     * */
    public function edit($id)
    {
        //echo $id;die;
        $this->restaurant_id=22334455;
        if (!is_null($id)) {
            $id = base64_decode($id);
        }else{
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('restaurant/menus_default');
        }
        if (is_numeric($id)) {
            $menu = $this->Menus_model->get_menu_detail_new(['id' => $id, 'is_deleted' => 0]);
            if ($menu) {
                $data['menu'] = $menu;
                $data['title'] = WEBNAME.' | Edit Menu';
                $data['head'] = 'Edit';
                $data['label'] = 'Edit';
                $this->form_validation->set_rules('title', 'Title', 'trim|required');
                $this->form_validation->set_rules('arabian_title', 'Arabian Title', 'trim|required');
                $background_image = $menu['background_image'];
               
                if ($this->form_validation->run() == true) {
                    $flag = 0;

                    $directory_background = RESTAURANT_IMAGES . '/' . $this->restaurant_id. '/menus/backgrounds/';
                    if (!file_exists(RESTAURANT_IMAGES . '/' . $this->restaurant_id. '/menus/')) {
                        mkdir(RESTAURANT_IMAGES . '/' . $this->restaurant_id. '/menus/');
                    }
                    if (!file_exists($directory_background)) {
                        mkdir($directory_background);
                    }

                    if ($_FILES['backgroundimg']['name'] != '') {
                        
                        /*$image_data = upload_image('backgroundimg', $directory_background);
                        if (is_array($image_data)) {
                            $flag = 1;
                            $data['background_image_validation'] = $image_data['errors'];
                        } else {
                            if ($background_image != '') {
                                unlink($directory_background . $background_image);
                            }
                            $background_image = $image_data;
                        }*/
                        $extension = explode('/', $_FILES['backgroundimg']['type']);
                        $randname = uniqid() . time() . '.' . end($extension);
                        if($_FILES['backgroundimg']['size'] / 1024 <= 2048) { // 2MB
                         if($_FILES['backgroundimg']['type'] == 'image/jpeg' || 
                         $_FILES['backgroundimg']['type'] == 'image/pjpeg' || 
                         $_FILES['backgroundimg']['type'] == 'image/png' ||
                         $_FILES['backgroundimg']['type'] == 'image/gif'){
                      
                            $source_file = $_FILES['backgroundimg']['tmp_name'];
                            $target_file = $directory_background . $randname; 
                            $width      = '';
                            $height     = '';
                            $quality    = '40';
                            //$image_name = $_FILES['uploadImg']['name'];
                            $success = compress_image($source_file, $target_file, $width, $height, $quality);
                           // print_r($success);die;
                            if ($background_image != '') {
                            unlink($directory_background . $background_image);
                                }
                            $background_image = $randname;
                                }
                             }
                    }

                    

                    if ($flag == 0) {
                        $dataArr = array(
                            'title' => trim($this->input->post('title')),
                            'arabian_title' => trim($this->input->post('arabian_title')),
                            'background_image' => $randname,
                            'is_disable_feedback' =>$this->input->post('is_disable_feedback'),
                        );
                    }
                    $dataArr['updated_at'] = date('Y-m-d H:i:s');
                    $this->Menus_model->common_insert_update('update', 'default_menus', $dataArr, ['id' => $id]);
                    $this->session->set_flashdata('success', 'Menu\'s data has been updated successfully.');
                    redirect('restaurant/menus_default');
                } else {
                    $this->template->load('default', 'Backend/restaurant/menus_default/manage', $data);
                }
            }else{
                $this->session->set_flashdata('error', 'Disabled menu can\'t be modified.');
                redirect('restaurant/menus_default');
            }
        }else{
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('restaurant/menus_default');
        }
    }

    /**
     * Delete menu
     * @param int $id
     * */
/*    public function delete($id = null)
    {
        $id = base64_decode($id);
        if (is_numeric($id)) {
            $menu = $this->Menus_model->get_menu_detail_new(['id' => $id], 'id,title');
            if ($menu) {
                $update_array = array(
                    'is_deleted' => 1,
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $this->Menus_model->common_insert_update('update', 'default_menus', $update_array, ['id' => $id]);
                $this->session->set_flashdata('success', htmlentities($menu['title']) . ' has been deleted successfully!');
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('restaurant/menus_default');
        } else {
            show_404();
        }
    }*/

    /**
     * View menu
     * @return : View
     * @author : sm
     */
    public function view_menu()
    {
        $menu_id = base64_decode($this->input->post('id'));
        $menu = $this->Menus_model->get_menu_detail(['id' => $menu_id]);
        if ($menu) {
            $data['menu'] = $menu;
            return $this->load->view('Backend/restaurant/menus/view', $data);
        } else {
            show_404();
        }
    }

    /**
     * enable and disable of menus
     * @param int $id
     * */
    public function change_status()
    {
        $id = $this->input->post('id');
        if (is_numeric($id)) {
            $menu = $this->Menus_model->get_menu_detail(['id' => $id], 'id,title,is_active');
            $is_active;
            $msg = array();
            $category = array();
            $items = array();
            if ($menu) {
                if ($menu['is_active'] == 1) {
                    $is_active = 0;
                    $msg['status'] = 1;
                    $msg['msg'] = htmlentities($menu['title']) . ' has been disable successfully!';
                } else {
                    $is_active = 1;
                    $msg['status'] = 0;
                    $msg['msg'] = htmlentities($menu['title']) . ' has been enable successfully!';
                }
                $update_array = array(
                    'is_active' => $is_active,
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $update_date = date('Y-m-d H:i:s');

                $this->Menus_model->common_insert_update('update', TBL_MENUS, $update_array, ['id' => $id]);
                $this->db->query('UPDATE '.TBL_CATEGORIES.' as cate,'.TBL_CATEGORY_DETAILS.' as cd,'.TBL_MENUS.' as menu SET cate.updated_at = "'.$update_date.'", cate.is_active = '.$is_active.' WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cd.menu_id = '.$id.' '); //Change Status of all categories
                $this->db->query('UPDATE '.TBL_CATEGORIES.' as cate,'.TBL_ITEM_DETAILS.' as item_d,'.TBL_ITEMS.' as item,'.TBL_MENUS.' as menu SET item.updated_at = "'.$update_date.'", item.is_active = '.$is_active.' WHERE menu.id = item_d.menu_id AND cate.id = item_d.category_id AND item_d.item_id = item.id AND item_d.menu_id = '.$id.' '); //Change Status of all items
                $sql = "SELECT GREATEST(MAX(IFNULL(m.created_at,0)), MAX(IFNULL(m.updated_at,0)),MAX(IFNULL(u.created_at,0)), MAX(IFNULL(u.updated_at,0)),MAX(IFNULL(s.created_at,0)), MAX(IFNULL(s.updated_at,0))) as latesttimestamp FROM menus m LEFT JOIN users as u on u.restaurant_id = m.restaurant_id LEFT JOIN settings as s on s.user_id = m.restaurant_id WHERE m.restaurant_id = '".$this->session->userdata('login_user')['id']."'";
                    $query = $this->db->query($sql);
                    if ($query->num_rows() > 0) {
                  foreach ($query->result() as $row) { 
                    $latesttimestamp = $row->latesttimestamp; }
                }
                $msg['latesttimestamp'] = $latesttimestamp;
                header('Content-Type: application/json');
                echo json_encode($msg);
                exit;
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('restaurant/menus');
        } else {
            show_404();
        }
    }
    /**
     * feedback chaneg
     * @param int $id
     * */
    public function change_feedback()
    {
        $id = $this->input->post('id');
        if (is_numeric($id)) {
            $menu = $this->Menus_model->get_menu_detail(['id' => $id], 'id,title,is_disable_feedback');
            $is_disable_feedback;
            $msg = array();
            if ($menu) {
                if ($menu['is_disable_feedback'] == 1) {
                    $is_disable_feedback = 0;
                    $msg['status'] = 1;
                    $msg['msg'] = htmlentities($menu['title']) . ' has been disable  successfully!';
                } else {
                    $is_disable_feedback = 1;
                    $msg['status'] = 0;
                    $msg['msg'] = htmlentities($menu['title']) . ' has been enable successfully!';
                }
                $update_array = array(
                    'is_disable_feedback' => $is_disable_feedback,
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $this->Menus_model->common_insert_update('update', TBL_MENUS, $update_array, ['id' => $id]);
                $sql = "SELECT GREATEST(MAX(IFNULL(m.created_at,0)), MAX(IFNULL(m.updated_at,0)),MAX(IFNULL(u.created_at,0)), MAX(IFNULL(u.updated_at,0)),MAX(IFNULL(s.created_at,0)), MAX(IFNULL(s.updated_at,0))) as latesttimestamp FROM menus m LEFT JOIN users as u on u.restaurant_id = m.restaurant_id LEFT JOIN settings as s on s.user_id = m.restaurant_id WHERE m.restaurant_id = '".$this->session->userdata('login_user')['id']."'";
                    $query = $this->db->query($sql);
                    if ($query->num_rows() > 0) {
                  foreach ($query->result() as $row) { 
                    $latesttimestamp = $row->latesttimestamp; }
                }
                $msg['latesttimestamp'] = $latesttimestamp;
                header('Content-Type: application/json');
                echo json_encode($msg);
                exit;
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('restaurant/menus');
        } else {
            show_404();
        }
    }

}
