<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Categories extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Categories_model','Menus_model','Package_request','Packages_model','Users_model']);
        $this->check_login_user();
 /*$categories = $this->Categories_model->get_categories_default('result');
 print_r($categories );die;*/
/*  $id=461;
   $menu_ids = ($this->Categories_model->sql_select(TBL_CATEGORY_DETAILS, '*', ['where' => ['category_id' => $id]]));*/
  /* $default_menuId=238;/*
    $default_data=$this->Menus_model->get_default_menu(['id' => $default_menuId, 'is_deleted' => 0]);*/
   /* $catIds= ($this->Categories_model->sql_select(TBL_CATEGORY_DETAILS, '*', ['where' => ['menu_id' => 232]]));*/
   /*$catdata=$this->Categories_model->get_default_cat(['id' => 476, 'is_deleted' => 0]);*/
  /* $menus = $this->Menus_model->get_menus_default('result');
   print_r($menus );die;*/
    }

    public function index()
    {
        $data['title'] = WEBNAME.' | Categories';
        $data['head'] = 'Categories';
        $categories = $this->Menus_model->custom_Query('SELECT DISTINCT cd.category_id FROM '.TBL_CATEGORIES.' as cate, '.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND cate.id = cd.category_id AND menu.id = cd.menu_id AND user.id = '.$this->restaurant_id.' AND cate.is_deleted = 0');
        // $categories = $this->Menus_model->custom_Query('SELECT cate.id FROM '.TBL_CATEGORIES.' as cate,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND menu.id = cate.menu_id AND user.id = '.$this->restaurant_id.' AND cate.is_deleted = 0');
        $data['category_limit'] = $categories->result_id->num_rows;
        //get categories ID from item table if available.
        $data['package'] = $this->check_free_pacakge(); //check free trial assign or not.
        /*$data['categories'] = $this->db->query('SELECT DISTINCT cate.id FROM  '.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND menu.id = item_d.menu_id AND cate.id = item_d.category_id AND user.id = '.$this->restaurant_id.' AND cate.is_deleted = 0')->result();*/

        $data['categories'] = $this->db->query('SELECT DISTINCT cate.id FROM item_details as item_d ,categories as cate,items as itemm,menus as menu,users as user WHERE user.id = menu.restaurant_id AND menu.id = item_d.menu_id AND cate.id = item_d.category_id AND user.id = '.$this->restaurant_id.' AND cate.is_deleted = 0 and itemm.id=item_d.item_id and itemm.is_deleted=0')->result();

        /*SELECT DISTINCT cate.id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND menu.id = item_d.menu_id AND cate.id = item_d.category_id AND user.id = '.$this->restaurant_id.' AND item.is_deleted = 0'*/


        //$data['categories'] = $this->db->query('SELECT DISTINCT menu.id FROM '.TBL_ITEM_DETAILS.' as item_d,'.TBL_CATEGORIES.' as cate,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND menu.id = cate.menu_id AND user.id = '.$this->restaurant_id.'')->result();
        $menus = ($this->Categories_model->sql_select(TBL_MENUS, '*', ['where' => ['is_deleted' => 0, 'is_active' => 1, 'restaurant_id' => $this->restaurant_id]]));
        $data['menus'] = $menus;
        $this->template->load('default', 'Backend/restaurant/categories/index', $data);
    }

    /**
     * This function used to get categories data for ajax table
     * */
    public function get_categories()
    {
        $final['recordsFiltered'] = $final['recordsTotal'] = $this->Categories_model->get_categories('count');
        $final['redraw'] = 1;
        $categories = $this->Categories_model->get_categories('result');
        $start = $this->input->get('start') + 1;
        foreach ($categories as $key => $val) {
            $categories[$key] = $val;
            $categories[$key]['sr_no'] = $start++;
            $categories[$key]['title'] = htmlentities($val['title']);
            $categories[$key]['arabian_title'] = htmlentities($val['arabian_title']);
            $categories[$key]['created_at'] = date('d M Y', strtotime($val['created_at']));
            $categories[$key]['is_deleted'] = $val['is_deleted'];
        }
        $final['data'] = $categories;
        echo json_encode($final);
    }

    /**
     * This function used to add / edit categories data
     * @param int $id
     * */
    public function add($id = null)
    {
        $categories = $this->Menus_model->custom_Query('SELECT DISTINCT cd.category_id FROM '.TBL_CATEGORIES.' as cate, '.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND cate.id = cd.category_id AND menu.id = cd.menu_id AND user.id = '.$this->restaurant_id.' AND cate.is_deleted = 0');
        $categories_limit = $categories->result_id->num_rows;
        $package = $this->check_free_pacakge(); //check free trial assign or not
        if($categories_limit < $this->session->userdata('login_user')['categories_limit'])
        {
            $menus = ($this->Categories_model->sql_select(TBL_MENUS, '*', ['where' => ['is_deleted' => 0, 'restaurant_id' => $this->restaurant_id]]));
            $data['menus'] = $menus;
            if (!is_null($id)) {
                $id = base64_decode($id);
            }
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            // $this->form_validation->set_rules('arabian_title', 'Arabian Title', 'trim|required');
            $this->form_validation->set_rules('menus[]', 'Menus', 'trim|required');
            $image = null;
            $background_image = null;
            $data['title'] = WEBNAME.' | Add Category';
            $data['head'] = 'Add';
            $data['label'] = 'Create New';
            if ($this->form_validation->run() == true) {
                $directory_image = RESTAURANT_IMAGES . '/' . $this->restaurant_id. '/categories/';
                if (!file_exists($directory_image)) {
                    mkdir($directory_image);
                }

                if ($_FILES['categoryimage']['name'] != '') {
                    /*$image_data = upload_image('categoryimage', $directory_image);
                    if (is_array($image_data)) {
                        $data['profile_image_validation'] = $image_data['errors'];
                    } else {
                        if ($image != '') {
                            unlink($directory_image . $image);
                        }
                        $image = $image_data;
                    }*/
                    // compress image and upload
                    $extension = explode('/', $_FILES['categoryimage']['type']);
                        $randname = uniqid() . time() . '.' . end($extension);
                        if($_FILES['categoryimage']['size'] / 1024 <= 2048) { // 2MB
                         if($_FILES['categoryimage']['type'] == 'image/jpeg' || 
                         $_FILES['categoryimage']['type'] == 'image/pjpeg' || 
                         $_FILES['categoryimage']['type'] == 'image/png' ||
                         $_FILES['categoryimage']['type'] == 'image/gif'){
                      
                            $source_file = $_FILES['categoryimage']['tmp_name'];
                            $target_file = $directory_image . $randname; 
                            $width      = '';
                            $height     = '';
                            $quality    = '40';
                            //$image_name = $_FILES['uploadImg']['name'];
                            $success = compress_image($source_file, $target_file, $width, $height, $quality);
                            if ($image != '') {
                            unlink($directory_image . $image);
                            }
                            $image = $randname;
                                }
                             }

                }
                // upload background image
                $directory_background = RESTAURANT_IMAGES . '/' . $this->restaurant_id. '/categories/backgrounds/';
                if (!file_exists(RESTAURANT_IMAGES . '/' . $this->restaurant_id. '/categories/')) {
                    mkdir(RESTAURANT_IMAGES . '/' . $this->restaurant_id. '/categories/');
                }
                if (!file_exists($directory_background)) {
                    mkdir($directory_background);
                }
                if ($_FILES['backgroundimg']['name'] != '') {
                    /*$image_data = upload_image('backgroundimg', $directory_background);
                    if (is_array($image_data)) {
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
                    'title' => trim($this->input->post('title')),
                    'arabian_title' => trim($this->input->post('arabian_title')),
                    'background_image' => $background_image,
                    'image' => $image,
                    'is_active' => 1,
                );
                $dataArr['created_at'] = date('Y-m-d H:i:s');
                $inserted_id = $this->Categories_model->common_insert_update('insert', TBL_CATEGORIES, $dataArr);
                foreach($this->input->post('menus[]') as $Menu_Id)
                {
                    $this->users_model->common_delete(TBL_CATEGORY_DETAILS, ['menu_id' => $Menu_Id,'category_id' => $inserted_id]);
                    $this->Categories_model->common_insert_update('insert', TBL_NEW_CATEGORY, array('menu_id' => $Menu_Id,'category_id' => $inserted_id,'created_at' =>  date('Y-m-d H:i:s')));
                    $this->Categories_model->common_insert_update('insert', TBL_CATEGORY_DETAILS, array('menu_id' => $Menu_Id,'category_id' => $inserted_id));
                    $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $Menu_Id]);
                }
                $this->session->set_flashdata('success', 'Category has been added successfully');
                redirect('restaurant/categories');
            }
            $this->template->load('default', 'Backend/restaurant/categories/manage', $data);
        }
        else
        {
            $this->session->set_flashdata('error', 'Limitation is finised!');
            redirect('restaurant/categories');
        }
    }



     public function cat_main_upload_fn(){
        $sql="SELECT * FROM `file_upload_cats__2`";
        $res=$this->db->query($sql)->result_array();
        foreach ($res as   $value) {
            $this->cats_add_file_upload($value);
        }
    }



    public function cats_add_file_upload($data){
        // $background_image='';
        // $image='';
  exit;
        $dataArr = array(
                    'title' => trim($data['title']),
                    'arabian_title' => trim($data['arabian_title']),
                    'background_image' => $data['background_image'],
                    'image' => $data['image'],
                    'is_active' => 1,
                );
                $dataArr['created_at'] = date('Y-m-d H:i:s');
                 echo "<pre>";
             print_r($dataArr);
             $menu_ids=explode(',', $data['menuids']);
             echo "<pre>";
             print_r($menu_ids); 
            
                $inserted_id = $this->Categories_model->common_insert_update('insert', TBL_CATEGORIES, $dataArr);
                foreach($menu_ids as $Menu_Id)
                {
                    $this->users_model->common_delete(TBL_CATEGORY_DETAILS, ['menu_id' => $Menu_Id,'category_id' => $inserted_id]);
                    $this->Categories_model->common_insert_update('insert', TBL_NEW_CATEGORY, array('menu_id' => $Menu_Id,'category_id' => $inserted_id,'created_at' =>  date('Y-m-d H:i:s')));
                    $this->Categories_model->common_insert_update('insert', TBL_CATEGORY_DETAILS, array('menu_id' => $Menu_Id,'category_id' => $inserted_id));
                    $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $Menu_Id]);
                }
                //$this->session->set_flashdata('success', 'Category has been added successfully');
                //redirect('restaurant/categories');
                echo "data insertion completed";
                  

    }

    /**
     * Edit catgegory data
     * @param int $id
     * */
    public function edit($id)
    {
        $delete_menu_ids;
        $menus = ($this->Categories_model->sql_select(TBL_MENUS, '*', ['where' => ['is_deleted' => 0, 'restaurant_id' =>$this->restaurant_id]]));
        $data['menus'] = $menus;
        if (!is_null($id)) {
            $id = base64_decode($id);
            $menu_ids = ($this->Categories_model->sql_select(TBL_CATEGORY_DETAILS, '*', ['where' => ['category_id' => $id]]));
            $data['menu_ids'] = $menu_ids;
        }else{
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('restaurant/categories');
        }
        if (is_numeric($id)) {
            $category = $this->Categories_model->get_category_detail(['id' => $id, 'is_deleted' => 0]);
            if ($category) {
                $data['category'] = $category;
                $data['title'] = WEBNAME.' | Edit Category';
                $data['head'] = 'Edit';
                $data['label'] = 'Edit';
                $this->form_validation->set_rules('title', 'Title', 'trim|required');
                // $this->form_validation->set_rules('arabian_title', 'Arabian Title', 'trim|required');
                $this->form_validation->set_rules('menus[]', 'Menus', 'trim|required');
                $image = $category['image'];
                $background_image = $category['background_image'];
                if ($this->form_validation->run() == true) {
                    $flag = 0;
                    $directory_image = RESTAURANT_IMAGES . '/' . $this->restaurant_id. '/categories/';
                    if (!file_exists($directory_image)) {
                        mkdir($directory_image);
                    }
                    if ($_FILES['categoryimage']['name'] != '') {
                        /*$image_data = upload_image('categoryimage', $directory_image);
                        if (is_array($image_data)) {
                            $flag = 1;
                            $data['profile_image_validation'] = $image_data['errors'];
                        } else {
                            if ($image != '') {
                                unlink($directory_image . $image);
                            }
                            $image = $image_data;
                        }*/
                        // compress image and upload
                    $extension = explode('/', $_FILES['categoryimage']['type']);
                        $randname = uniqid() . time() . '.' . end($extension);
                        if($_FILES['categoryimage']['size'] / 1024 <= 2048) { // 2MB
                         if($_FILES['categoryimage']['type'] == 'image/jpeg' || 
                         $_FILES['categoryimage']['type'] == 'image/pjpeg' || 
                         $_FILES['categoryimage']['type'] == 'image/png' ||
                         $_FILES['categoryimage']['type'] == 'image/gif'){
                      
                            $source_file = $_FILES['categoryimage']['tmp_name'];
                            $target_file = $directory_image . $randname; 
                            $width      = '';
                            $height     = '';
                            $quality    = '40';
                            //$image_name = $_FILES['uploadImg']['name'];
                            $success = compress_image($source_file, $target_file, $width, $height, $quality);
                            if ($image != '') {
                                unlink($directory_image . $image);
                            }
                            $image = $randname;
                                }
                             }
                    }
                    // Edit Background Image
                    $directory_background = RESTAURANT_IMAGES . '/' . $this->restaurant_id. '/categories/backgrounds/';
                    if (!file_exists(RESTAURANT_IMAGES . '/' . $this->restaurant_id. '/categories/')) {
                        mkdir(RESTAURANT_IMAGES . '/' . $this->restaurant_id. '/categories/');
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

                    if ($flag == 0) {
                        $dataArr = array(
                            'title' => trim($this->input->post('title')),
                            'arabian_title' => trim($this->input->post('arabian_title')),
                            'background_image' => $background_image,
                            'image' => $image,
                        );
                    }
                    $dataArr['updated_at'] = date('Y-m-d H:i:s');
                    $this->users_model->common_insert_update('update', TBL_CATEGORIES, $dataArr, ['id' => $id]); //Update category data
                    $this->users_model->common_delete(TBL_CATEGORY_DETAILS, ['category_id' => $id]); // Delete all categories from category details table.
                    foreach($this->input->post('menus[]') as $Menu_Id)
                    {
                        $this->Categories_model->common_insert_update('insert', TBL_CATEGORY_DETAILS, array('menu_id' => $Menu_Id,'category_id' => $id));
                        $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $Menu_Id]);
                    }

                    //Add records for deleted menus in delete_category table
                    if(!empty($menu_ids))
                    {
                        foreach ($menu_ids as $key => $row) {
                            $delete_menu_ids[$key]=$row['menu_id'];
                        }
                    }
                    $result = array_diff($delete_menu_ids,$this->input->post('menus[]'));
                    $new_records = array_diff($this->input->post('menus[]'),$delete_menu_ids);
                    if(!empty($result))
                    {
                        foreach($result as $row)
                        {
                            $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $row]);
                            $this->users_model->common_delete(TBL_DELETE_CATEGORY, ['category_id' => $id,'menu_id' => $row]); // Delete all categories from category details table.
                            $this->users_model->common_delete(TBL_NEW_CATEGORY, ['category_id' => $id,'menu_id' => $row]); // Delete all categories from category details table.
                            $this->Categories_model->common_insert_update('insert', TBL_DELETE_CATEGORY, array('menu_id' => $row,'category_id' => $id,'created_at'=>  date('Y-m-d H:i:s')));
                        }
                    }

                    if(!empty($new_records))
                    {
                        foreach($new_records as $row)
                        {
                            $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $row]);
                            $this->users_model->common_delete(TBL_DELETE_CATEGORY, ['category_id' => $id,'menu_id' => $row]); // Delete all categories from category details table.
                            $this->users_model->common_delete(TBL_NEW_CATEGORY, ['category_id' => $id,'menu_id' => $row]); // Delete all categories from category details table.
                            $this->Categories_model->common_insert_update('insert', TBL_NEW_CATEGORY, array('menu_id' => $row,'category_id' => $id,'created_at' =>  date('Y-m-d H:i:s')));
                        }
                    }

                    $this->session->set_flashdata('success', 'Category\'s data has been updated successfully.');
                    redirect('restaurant/categories');
                } else {
                    $this->template->load('default', 'Backend/restaurant/categories/manage', $data);
                }
            }else{
                $this->session->set_flashdata('error', 'Disabled category can\'t be modified.');
                redirect('restaurant/categories');
            }
        }else{
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('restaurant/categories');
        }
    }

   /**
     * Delete category
     * @param int $id
     * */
    public function delete($id = null)
    {
        $id = base64_decode($id);
        if (is_numeric($id)) {
            $category = $this->Categories_model->get_category_detail(['id' => $id], 'id,title');
            $menu_ids = ($this->Categories_model->sql_select(TBL_CATEGORY_DETAILS, '*', ['where' => ['category_id' => $id]]));
            $data['menu_ids'] = $menu_ids;
            if ($category) {
                $update_array = array(
                    'is_deleted' => 1,
                    'updated_at' => date('Y-m-d H:i:s')
                );
                
                if(!empty($menu_ids))
                {
                    foreach($menu_ids as $row)
                    {
                        $this->Categories_model->common_insert_update('insert', TBL_DELETE_CATEGORY, array('menu_id' => $row['menu_id'],'category_id' => $id,'is_deleted' => 1,'created_at' => date('Y-m-d H:i:s')));
                    }
                }

                $this->Categories_model->common_insert_update('update', TBL_CATEGORIES, $update_array, ['id' => $id]);
                //$this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $category['menu_id']]);
                $this->db->query('UPDATE '.TBL_CATEGORIES.' as cate,'.TBL_CATEGORY_DETAILS.' as cd,'.TBL_MENUS.' as menu SET menu.updated_at = "'.date('Y-m-d H:i:s').'" WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cd.category_id = '.$id.' '); //Change Status of all parent menus
                $this->session->set_flashdata('success', htmlentities($category['title']) . ' has been deleted successfully!');
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('restaurant/categories');
        } else {
            show_404();
        }
    }

    /**
     * View category
     * @return : View
     * @author : sm
     */
    public function view_categories()
    {
        $category_id = base64_decode($this->input->post('id'));
        $category = $this->Categories_model->get_category_detail(['id' => $category_id]);
        $menu = $this->Menus_model->custom_Query('SELECT menu.title FROM '.TBL_CATEGORIES.' as cate, '.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_MENUS.' as menu WHERE cate.id = cd.category_id AND menu.id = cd.menu_id AND cd.category_id = '.$category_id.'')->result();
        $menus_array = null;
        foreach ($menu as $key => $value) {
            $menus_array[$key] = $value->title;
        }

        if ($category) {
            $data['category'] = $category;
            $data['menu'] = implode(',',$menus_array);
            return $this->load->view('Backend/restaurant/categories/view', $data);
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
            $category = $this->Categories_model->get_category_detail(['id' => $id], 'id,title,is_active');
            $is_active;
            $msg = array();
            $items = array();
            if ($category) {
                if ($category['is_active'] == 1) {
                    $is_active = 0;
                    $msg['status'] = 1;
                    $msg['msg'] = htmlentities($category['title']) . ' has been disable successfully!';
                } else {
                    $is_active = 1;
                    $msg['status'] = 0;
                    $msg['msg'] = htmlentities($category['title']) . ' has been enable successfully!';
                }
                $update_array = array(
                    'is_active' => $is_active,
                    'updated_at' => date('Y-m-d H:i:s')
                );

                $this->Categories_model->common_insert_update('update', TBL_CATEGORIES, $update_array, ['id' => $id]);
                $this->db->query('UPDATE '.TBL_CATEGORIES.' as cate,'.TBL_ITEM_DETAILS.' as item_d,'.TBL_ITEMS.' as item,'.TBL_MENUS.' as menu SET item.updated_at = "'.date('Y-m-d H:i:s').'", item.is_active = '.$is_active.' WHERE menu.id = item_d.menu_id AND cate.id = item_d.category_id AND item_d.item_id = item.id AND item_d.category_id = '.$id.' '); //Change Status of all items
               
                //$this->users_model->common_insert_update('update', TBL_ITEMS,$update_array , ['category_id' => $id]);
                
                $this->db->query('UPDATE '.TBL_CATEGORIES.' as cate,'.TBL_CATEGORY_DETAILS.' as cd,'.TBL_MENUS.' as menu SET menu.updated_at = "'.date('Y-m-d H:i:s').'" WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cd.category_id = '.$id.' '); //Change updated_at of all parent menus
                //$this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $category['menu_id']]);
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
            redirect('restaurant/categories');
        } else {
            show_404();
        }
    }

    //Rearrange categories
    public function sortabledatatable()
    {
        $order = $this->input->post('order');
        if(!empty($order))
        {
            foreach ($order as $order) 
            {
                $this->Categories_model->common_insert_update('update', TBL_CATEGORIES, ['order' => $order['position'],'updated_at' => date('Y-m-d H:i:s')], ['id' => $order['id']]);
                $this->db->query('UPDATE '.TBL_CATEGORIES.' as cate,'.TBL_CATEGORY_DETAILS.' as cd,'.TBL_MENUS.' as menu SET menu.updated_at = "'.date('Y-m-d H:i:s').'" WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cd.category_id = '.$order['id'].' '); //Change Status of all parent menus
            }
            $sql = "SELECT GREATEST(MAX(IFNULL(m.created_at,0)), MAX(IFNULL(m.updated_at,0)),MAX(IFNULL(u.created_at,0)), MAX(IFNULL(u.updated_at,0)),MAX(IFNULL(s.created_at,0)), MAX(IFNULL(s.updated_at,0))) as latesttimestamp FROM menus m LEFT JOIN users as u on u.restaurant_id = m.restaurant_id LEFT JOIN settings as s on s.user_id = m.restaurant_id WHERE m.restaurant_id = '".$this->session->userdata('login_user')['id']."'";
                    $query = $this->db->query($sql);
                    if ($query->num_rows() > 0) {
                  foreach ($query->result() as $row) { 
                    $latesttimestamp = $row->latesttimestamp; }
                }
            header('Content-Type: application/json');
            echo json_encode(array('status' => 'success','latesttimestamp' =>$latesttimestamp));
            exit;
        }
        else
        {
            header('Content-Type: application/json');
            echo json_encode(array('status' => 'in valid request'));
            exit;
        } 
    }
}
