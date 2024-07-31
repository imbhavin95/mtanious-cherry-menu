<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Items extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Items_model','Categories_model','Menus_model','Package_request','Packages_model','Users_model','Settings_model']);
        $this->check_login_user(); // Check login user is sub-admin? and assign limitaion of supper user(restaurant)
        
         /* $Category_Id=399;
          $menus_ids = $this->Items_model->get_menu_ids_new($Category_Id,$_SESSION['login_user']['id']);
          print_r($menus_ids);die;*/
       // print_r($_SESSION['login_user']['id']);die;
          /*$data=$this->Items_model->get_items_default($type = '',505);
          print_r($data);die;*/
       /*   $user_categories =$this->Categories_model->get_categories_default('result');
   print_r($user_categories);die;*/

    }

    public function index()
    { //echo "test"; die;
        // $this->item1(406);
        $data['title'] = WEBNAME.' | Items';
        $data['head'] = 'Items';
        $menu = array();
        // $items = $this->Menus_model->custom_Query('SELECT DISTINCT item.id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND menu.id = item_d.menu_id AND item.id = item_d.item_id AND cate.id = item_d.category_id AND user.id = '.$this->restaurant_id.' AND item.is_deleted = 0');
        //echo $this->db->last_query();
        // $data['item_limit'] = $items->result_id->num_rows;
        $data['item_limit'] =  $this->Items_model->get_items1('count');
        $data['package'] = $this->check_free_pacakge(); //check free trial assign or not.
        $menus = ($this->Items_model->sql_select(TBL_MENUS, '*', ['where' => ['is_deleted' => 0, 'is_active' => 1, 'restaurant_id' => $this->restaurant_id]]));
        $data['categories'] = $this->db->query('SELECT DISTINCT cate.* FROM  '.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND menu.id = item_d.menu_id AND cate.id = item_d.category_id AND user.id = '.$this->restaurant_id.' AND cate.is_deleted = 0')->result_array();

        /*'SELECT DISTINCT cate.* FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND menu.id = item_d.menu_id AND cate.id = item_d.category_id AND user.id = '.$this->restaurant_id.' AND item.is_deleted = 0'*/

        $data['menus'] = $menus;
        $data['itemlist'] = $this->db->query("SELECT DISTINCT i.id FROM items i INNER JOIN item_details as id ON id.item_id = i.id INNER JOIN menus as m ON m.id = id.menu_id INNER JOIN categories as c ON c.id = id.category_id WHERE i.is_active='1' and i.is_deleted = '0' and m.is_active='1' and m.is_deleted = '0' and c.is_active='1' and c.is_deleted = '0' and m.restaurant_id = '".$this->restaurant_id."'")->result_array();

        $settings = $this->Settings_model->get_settings_detail(['user_id' => $this->restaurant_id, 'is_deleted' => 0, 'is_active' => 1]);
        $data['currency_code']=$settings['currency'];

        $this->template->load('default', 'Backend/restaurant/items/index', $data);
    }

    /**
     * This function used to get items data for ajax table
     * */

    public function get_items()
    {
        $final['recordsFiltered'] = $final['recordsTotal'] = $this->Items_model->get_items1('count');
        $final['redraw'] = 1;
        $items = $this->Items_model->get_items1('result');

       // print_r( $items);die;
        $types = ($this->Items_model->sql_select(TBL_TYPES, 'type', ['where' => ['is_deleted' => 0, 'is_active' => 1]]));
        $check_type = array();
        foreach($types as $key => $row)
        {
            $check_type[$key] = $row['type'];
        }
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['title'] = htmlentities($val['title']);
            $items[$key]['arabian_title'] = htmlentities($val['arabian_title']);
            $items[$key]['status'] = $val['is_active'];
            //$items[$key]['type'] = htmlentities(implode(",",array_intersect($check_type,explode(',',$val['type']))));// $val['type'];
            $items[$key]['created_at'] = date('d M Y', strtotime($val['created_at']));
        }
        $final['data'] = $items;
        echo json_encode($final);
    }
    
    /**
     * This function used to add items data
     * @param int $id
     * */
    public function add($id = null)
    {
        // $items = $this->Menus_model->custom_Query('SELECT DISTINCT item.id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND menu.id = item_d.menu_id AND item.id = item_d.item_id AND cate.id = item_d.category_id AND user.id = '.$this->restaurant_id.' AND item.is_deleted = 0');
        // $items_limit = $items->result_id->num_rows;
        $items_limit = $this->Items_model->get_items1('count');
        
        $package = $this->check_free_pacakge(); //check free trial assign or not.
        if($items_limit < $this->session->userdata('login_user')['items_limit'])
        {
            $menus = ($this->Items_model->sql_select(TBL_MENUS, '*', ['where' => ['is_deleted' => 0, 'is_active' => 1, 'restaurant_id' => $this->restaurant_id]]));
            $types = ($this->Items_model->sql_select(TBL_TYPES, '*', ['where' => ['is_deleted' => 0, 'is_active' => 1]]));
            $data['menus'] = $menus;
            $data['categories'] = $this->Items_model->getAllCategories();
            $data['types'] = $types;
            if (!is_null($id)) {
                $id = base64_decode($id);
            }
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            //$this->form_validation->set_rules('arabian_title', 'Arabian Title', 'trim|required');
            $this->form_validation->set_rules('categories[]', 'categories', 'trim|required');
            $this->form_validation->set_rules('price', 'Price', 'trim|required');
            $image = null;
            $data['title'] = WEBNAME.' | Add Item';
            $data['head'] = 'Add';
            $data['label'] = 'Create New';

            if ($this->form_validation->run() == true) {
                $Type = '';
                    if(!empty($this->input->post('type[]')))
                    {
                        $Type = implode(',',$this->input->post('type[]'));
                    }
                $dataArr = array(
                    'title' => trim($this->input->post('title')),
                    'arabian_title' => trim($this->input->post('arabian_title')),
                    'price' => $this->input->post('price'),
                    'calories' => $this->input->post('calories'),
                    'is_featured' => $this->input->post('is_featured'),
                    'is_dish_new' => $this->input->post('is_dish_new'),
                    'description' => $this->input->post('description'),
                    'arabian_description' => $this->input->post('arabian_description'),
                    'type' => $Type,
                    'time' => $this->input->post('time'),
                );
                $dataArr['created_at'] = date('Y-m-d H:i:s');
                $inserted_id = $this->Items_model->common_insert_update('insert', TBL_ITEMS, $dataArr);

                foreach($this->input->post('categories[]') as $Category_Id)
                {
                    $menus_ids = $this->Items_model->get_menu_ids($Category_Id);
                    if(!empty($menus_ids))
                    {
                        foreach ($menus_ids as $key => $rows) 
                        {
                            $this->Categories_model->common_delete(TBL_NEW_ITEMS, ['menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id]);
                            $this->Categories_model->common_insert_update('insert', TBL_NEW_ITEMS, array('menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id,'created_at'=>date('Y-m-d H:i:s')));
                            $this->Categories_model->common_insert_update('insert', TBL_ITEM_DETAILS, array('menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id));
                            $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $Category_Id]);
                            $menus = ($this->Items_model->sql_select(TBL_CATEGORY_DETAILS, 'menu_id', ['where' => ['category_id' => $Category_Id]])); //get menu Id's
                            if(!empty($menus))
                            {
                                foreach ($menus as $key => $value) 
                                {
                                    $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $value['menu_id']]);   
                                }
                            }
                        }
                    }
                }
                $this->session->set_flashdata('success', 'Item has been added successfully');
                echo json_encode(array('insert_id'=>$inserted_id));
                exit;
            }
            $this->template->load('default', 'Backend/restaurant/items/manage', $data);
        }
        else
        {
            $this->session->set_flashdata('error', 'Limitation is finised!');
            redirect('restaurant/items');
        }
    }




    public function item_main_upload_fn(){
        $sql="SELECT * FROM `file_upload_items__2`";
        $res=$this->db->query($sql)->result_array();
        echo "<pre>";
        print_r($res);
        foreach ($res as   $value) {
           $this->items_add_file_upload($value);
        }
        exit;
    }



    public function items_add_file_upload($data){

        

        /*$Type = '';
                    if(!empty($this->input->post('type[]')))
                    {
                        $Type = implode(',',$this->input->post('type[]'));
                    }*/
                $dataArr = array(
                    'title' => trim($data['title']),
                    'arabian_title' => trim($data['arabian_title']),
                    'price' => $data['price'],
                    'calories' => $data['calories'],
                    'is_featured' => $data['is_featured'],
                    'is_dish_new' => $data['is_dish_new'],
                    'description' => $data['description'],
                    'arabian_description' => $data['arabian_description'],
                    'type' => $data['type'],
                    'time' => $data['time'],
                );
                $dataArr['created_at'] = date('Y-m-d H:i:s');
             $inserted_id = $this->Items_model->common_insert_update('insert', TBL_ITEMS, $dataArr);
             echo "<pre>";
             print_r($dataArr); 
          $catsids=explode(',', $data['catids']);
             echo "<pre>";
             print_r($catsids);

          // exit;
          foreach($catsids as $Category_Id)
                {
                    $menus_ids = $this->Items_model->get_menu_ids($Category_Id);
                    if(!empty($menus_ids))
                    {
                        foreach ($menus_ids as $key => $rows) 
                        {
                            $this->Categories_model->common_delete(TBL_NEW_ITEMS, ['menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id]);
                            $this->Categories_model->common_insert_update('insert', TBL_NEW_ITEMS, array('menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id,'created_at'=>date('Y-m-d H:i:s')));
                            $this->Categories_model->common_insert_update('insert', TBL_ITEM_DETAILS, array('menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id));
                            $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $Category_Id]);
                            $menus = ($this->Items_model->sql_select(TBL_CATEGORY_DETAILS, 'menu_id', ['where' => ['category_id' => $Category_Id]])); //get menu Id's
                            if(!empty($menus))
                            {
                                foreach ($menus as $key => $value) 
                                {
                                    $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $value['menu_id']]);   
                                }
                            }
                        }
                    }
                }
                //$this->session->set_flashdata('success', 'Item has been added successfully');
                echo "success";
                echo json_encode(array('insert_id'=>$inserted_id));
                //exit;


    }

    /*
    * Upload multiple images
    */
    public function upload_images($id = null)
    {
        if (!is_null($id)) {
            $id = $id;
        }else{
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('restaurant/items');
        }
        $image = null;
        $login_user_id  = null;
        if(is_sub_admin())
        {
            $login_user_id = $this->session->userdata('login_user')['restaurant_id'];
        }else{
            $login_user_id = $this->session->userdata('login_user')['id'];
        }
        $directory = RESTAURANT_IMAGES . $login_user_id. '/items/' .$id;
        if (!file_exists(RESTAURANT_IMAGES . $login_user_id. '/items/')) {
            mkdir(RESTAURANT_IMAGES . $login_user_id. '/items/');
        }
        
        if (!file_exists($directory)) {
            mkdir($directory);
        }
        
        if ($_FILES['itemimage']['name'] != '') {
            $image_data = upload_item_images('itemimage', $directory);
            if (is_array($image_data)) {
                
                $data['item_image_validation'] = $image_data['errors'];
                $this->session->set_flashdata('error', $image_data['errors']);
                echo json_encode(array("files" => 'Uploaded'));
                exit;
            } else {
                if ($image != '') {
                    unlink($directory . '/'.$image);
                }
                $image = $image_data;
            }
        }
        $dataArr = array(
            'item_id' => $id,
            'image' => $image,
        );
        $dataArr['created_at'] = date('Y-m-d H:i:s');
        $inserted_id = $this->Items_model->common_insert_update('insert', TBL_ITEM_IMAGES, $dataArr);

        $this->Items_model->common_insert_update('update', TBL_ITEMS, ['updated_at' => date('Y-m-d H:i:s')], ['id' => base64_decode($this->input->post('itemId'))]);
        $item = $this->Items_model->get_item_detail(['id' =>  base64_decode($this->input->post('itemId')), 'is_deleted' => 0, 'is_active' => 1]);
        $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $item['category_id']]);
        $category = $this->Categories_model->get_category_detail(['id' => $item['category_id'], 'is_deleted' => 0, 'is_active' => 1]);
        $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $category['menu_id']]);

        $this->session->set_flashdata('success', 'Item has been added successfully');
        echo json_encode(array("files" => 'Uploaded'));
        exit;
    }

    /**
     * Edit catgegory data
     * @param int $id
     * */
    public function edit($id)
    {
        //89
        $delete_category_ids;
        $menus = ($this->Items_model->sql_select(TBL_MENUS, '*', ['where' => ['is_deleted' => 0, 'is_active' => 1, 'restaurant_id' => $this->restaurant_id]]));
        $types = ($this->Items_model->sql_select(TBL_TYPES, '*', ['where' => ['is_deleted' => 0, 'is_active' => 1]]));
        
        $data['types'] = $types;
        $data['menus'] = $menus;
        $data['categories'] = $this->Items_model->getAllCategories();
        
        if (!is_null($id)) {
            $id = base64_decode($id);
        }else{
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('restaurant/items');
        }

        if (is_numeric($id)) {
           // $category_ids = ($this->Items_model->sql_select(TBL_ITEM_DETAILS, '*', ['where' => ['item_id' => $id]],['group_by' => 'category_id']));
            $category_ids = ($this->Items_model->sql_select(TBL_ITEM_DETAILS, '*', ['where' => ['item_id' => $id]]));

            $uniq_category_ids=$this->unique_multidim_array( $category_ids,'category_id');
             //print_r($uniq_category_ids);die;
            $data['category_ids'] = $uniq_category_ids;
           
            $item = $this->Items_model->get_item_detail(['id' => $id, 'is_deleted' => 0]);
            $item_images = ($this->Items_model->sql_select(TBL_ITEM_IMAGES, '*', ['where' => ['is_deleted' => 0, 'is_active' => 1,'item_id' => $id]]));
            if ($item) {
                $data['item'] = $item;
                $data['item_images'] = $item_images;
                $data['title'] = WEBNAME.' | Edit Item';
                $data['head'] = 'Edit';
                $data['label'] = 'Edit';

                //$categories = ($this->Items_model->sql_select(TBL_CATEGORIES, '*', ['where' => ['is_deleted' => 0, 'is_active' => 1, 'id' => $item['category_id']]])); //get single category based on category_id from item table.
                $menu_selected = ($this->Items_model->sql_select(TBL_ITEM_DETAILS, 'menu_id', ['where' => ['item_id' => $id]],['group_by' => 'menu_id']));
                $data['selectedmenu'] = $menu_selected;
                if(!empty($menu_selected[0]['menu_id']))
                {
                    $data['allcategories'] = $this->Items_model->getCategories($menu_selected[0]['menu_id']); //Get all categories based on menu
                }

                $this->form_validation->set_rules('title', 'Title', 'trim|required');
                //$this->form_validation->set_rules('arabian_title', 'Arabian Title', 'trim|required');
                $this->form_validation->set_rules('categories[]', 'categories', 'trim|required');
                $this->form_validation->set_rules('price', 'Price', 'trim|required');
                //$image = $item['image'];
                if ($this->form_validation->run() == true) {
                    $Type = '';
                    if(!empty($this->input->post('type[]')))
                    {
                        $Type = implode(',',$this->input->post('type[]'));
                    }

                  // echo "fgfdgfdgfd";
                    $valiprice=$this->input->post('price');
                   $iprice=(is_numeric( $valiprice ) && floor( $valiprice ) != $valiprice) ? number_format((float)$valiprice, 2, '.', '') : $valiprice; 
                // die;
                        $dataArr = array(
                            'title' => trim($this->input->post('title')),
                            'arabian_title' => trim($this->input->post('arabian_title')),
                            // 'price' => $this->input->post('price'),
                            'price' => $iprice,
                            'calories' => $this->input->post('calories'),
                            'is_featured' => $this->input->post('is_featured'),
                            'is_dish_new' => $this->input->post('is_dish_new'),
                            'description' => $this->input->post('description'),
                            'arabian_description' => $this->input->post('arabian_description'),
                            'type' => $Type,
                            'time' => $this->input->post('time'),
                        );
                    
                    $dataArr['updated_at'] = date('Y-m-d H:i:s');
                    $this->Items_model->common_insert_update('update', TBL_ITEMS, $dataArr, ['id' => $id]);
                    $this->users_model->common_delete(TBL_ITEM_DETAILS, ['item_id' => $id]);
                    foreach($this->input->post('categories[]') as $Category_Id)
                    {
                        $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $Category_Id]);
                        $menus_ids = $this->Items_model->get_menu_ids($Category_Id);
                        if(!empty($menus_ids))
                        {
                            foreach ($menus_ids as $key => $rows) 
                            {
                                $this->Categories_model->common_insert_update('insert', TBL_ITEM_DETAILS, array('menu_id' => $rows['id'],'item_id' => $id,'category_id' => $Category_Id));
                            }
                        }
                        //get menu Id
                        $menus = ($this->Items_model->sql_select(TBL_CATEGORY_DETAILS, 'menu_id', ['where' => ['category_id' => $Category_Id]])); //get menu Id's
                        if(!empty($menus))
                        {
                            foreach ($menus as $key => $value) 
                            {
                                $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $value['menu_id']]);   
                            }
                        }
                    }

                    //Add records for deleted categories in delete_item table
                    if(!empty($category_ids))
                    {
                        foreach ($category_ids as $key => $row) {
                            $delete_category_ids[$key]=$row['category_id'];
                        }
                    }
                    $result = array_diff($delete_category_ids,$this->input->post('categories[]'));
                    $new_records = array_diff($this->input->post('categories[]'),$delete_category_ids);
                    if(!empty($result))
                    {
                        foreach($result as $row)
                        {
                            $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $row]);
                            $menus_ids = $this->Items_model->get_menu_ids($row);
                            if(!empty($menus_ids))
                            {
                                foreach ($menus_ids as $key => $rows) {
                                    $this->users_model->common_delete(TBL_DELETE_ITEMS, ['item_id' => $id,'category_id' => $row,'menu_id' => $rows['id']]); // Delete all categories from category details table.
                                    $this->users_model->common_delete(TBL_NEW_ITEMS, ['item_id' => $id,'category_id' => $row,'menu_id' => $rows['id']]); // Delete all categories from category details table.
                                    $this->Categories_model->common_insert_update('insert', TBL_DELETE_ITEMS, array('menu_id' => $rows['id'],'category_id' => $row,'item_id' => $id,'created_at'=>  date('Y-m-d H:i:s')));
                                }
                            }
                        }
                    }

                    if(!empty($new_records))
                    {
                        foreach($new_records as $row)
                        {
                            $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $row]);
                            $menus_ids = $this->Items_model->get_menu_ids($row);
                            if(!empty($menus_ids))
                            {
                                foreach ($menus_ids as $key => $rows) {
                                    $this->users_model->common_delete(TBL_DELETE_ITEMS, ['menu_id' => $rows['id'],'item_id' => $id,'category_id' => $row]); // Delete all categories from category details table.
                                    $this->users_model->common_delete(TBL_NEW_ITEMS, ['menu_id' => $rows['id'],'item_id' => $id,'category_id' => $row]); // Delete all categories from category details table.
                                    $this->Categories_model->common_insert_update('insert', TBL_NEW_ITEMS, array('menu_id' => $rows['id'],'category_id' => $row,'item_id' => $id,'created_at' =>  date('Y-m-d H:i:s')));
                                }
                            }
                        }
                    }

                    $this->session->set_flashdata('success', 'Item\'s data has been updated successfully.');
                    // redirect('restaurant/items');
                    echo json_encode(['insert_id'=>$id]);
                    exit;
                } else {
                    $this->template->load('default', 'Backend/restaurant/items/manage', $data);
                }
            }else{
                $this->session->set_flashdata('error', 'Disabled item can\'t be modified.');
                redirect('restaurant/items');
            }
        }else{
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('restaurant/items');
        }
    }

   /**
     * Delete Item
     * @param int $id
     * */
    public function delete($id = null)
    {
        $id = base64_decode($id);
        if (is_numeric($id)) {
            $item = $this->Items_model->get_item_detail(['id' => $id], 'id,title');
            $category_ids = ($this->Items_model->sql_select(TBL_ITEM_DETAILS, '*', ['where' => ['item_id' => $id]]));
           
            if ($item) {
                $update_array = array(
                    'is_deleted' => 1,
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $this->Items_model->common_insert_update('update', TBL_ITEMS, $update_array, ['id' => $id]);
                // $this->users_model->common_insert_update('update', TBL_ITEM_IMAGES,['is_deleted' => 1] , ['item_id' => $id]); // Deleted all item images
                //$category_ids = ($this->Items_model->sql_select(TBL_ITEM_DETAILS, '*', ['where' => ['item_id' => base64_decode($id)]]));
                if(!empty($category_ids))
                {
                    foreach($category_ids as $row)
                    {
                        $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $row['category_id']]);
                        $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $row['menu_id']]);
                        $this->Categories_model->common_insert_update('insert', TBL_DELETE_ITEMS, array('menu_id' => $row['menu_id'],'category_id' => $row['category_id'],'item_id' => $id,'is_deleted' => 1,'created_at' => date('Y-m-d H:i:s')));
                    }
                }
               // $this->Items_model->delete_item_ref($id);
                $this->session->set_flashdata('success', htmlentities($item['title']) . ' has been deleted successfully!');
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('restaurant/items');
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
        $types = ($this->Items_model->sql_select(TBL_TYPES, 'type', ['where' => ['is_deleted' => 0, 'is_active' => 1]]));
        $check_type = array();
        foreach($types as $key => $row)
        {
            $check_type[$key] = $row['type'];
        }
        $item['type'] = implode(",",array_intersect($check_type,explode(',',$item['type'])));
        if ($item) {
            $data['item'] = $item;
            return $this->load->view('Backend/restaurant/items/view', $data);
        } else {
            show_404();
        }
    }

    /**
     * getCategories: get categories based on menus
     * @param int $id
     * */
    public function getCategories()
    {
        $id = $this->input->post('menuId');
        if (!empty($id)) 
        {
            $this->db->select('ca.*');
            $this->db->join(TBL_MENUS . ' as m', 'cd.menu_id=m.id', 'left');
            $this->db->join(TBL_CATEGORIES . ' as ca', 'cd.category_id=ca.id', 'left');
            $this->db->where(array('ca.is_deleted' => 0,'m.is_deleted' => 0));
            $this->db->where_in('cd.menu_id', $id);
            $this->db->group_by('ca.id');
            $categories = $this->db->get(TBL_CATEGORY_DETAILS.' cd')->result_array();
            //$categories = $this->db->query('SELECT DISTINCT cate.* FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.is_deleted = 0 AND cd.menu_id IN '.$id.'')->result_array();
            header('Content-Type: application/json');
            echo json_encode($categories);
            exit;
        }
        else 
        {
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
        }
        redirect('restaurant/items');
    }

     /**
     * getCategories: get categories based on menus
     * @param int $id
     * */
    public function getAllCategories()
    { 
        $menus = ($this->Items_model->sql_select(TBL_MENUS, '*', ['where' => ['is_deleted' => 0, 'is_active' => 1, 'restaurant_id' => $this->restaurant_id]]));
        if(!empty($menus))
        {
            foreach($menus as $key => $row)
            {
                $menu[$key] = $row['id'];
            }
            // $categories = ($this->users_model->sql_select(TBL_CATEGORIES, '*', ['where' => ['is_deleted' => 0,'is_active' => 1],'where_in' => ['menu_id' => $menu]]));
            $categories = $this->db->query('SELECT DISTINCT cate.* FROM  '.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND menu.id = item_d.menu_id AND cate.id = item_d.category_id AND user.id = '.$this->restaurant_id.' AND cate.is_deleted = 0')->result_array();



            /*'SELECT DISTINCT cate.* FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND menu.id = item_d.menu_id AND cate.id = item_d.category_id AND user.id = '.$this->restaurant_id.' AND item.is_deleted = 0'*/
            if($categories)
            {
             echo json_encode($categories);
            }else
            {
              echo json_encode($categories = null);
            }
            exit;
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
            $item = $this->Items_model->get_item_detail(['id' => $id], 'id,title,is_active');
            $is_active;
            $msg = array();
            if ($item) {
                if ($item['is_active'] == 1) {
                    $is_active = 0;
                    $msg['status'] = 1;
                    $msg['msg'] = htmlentities($item['title']) . ' has been disable successfully!';
                } else {
                    $is_active = 1;
                    $msg['status'] = 0;
                    $msg['msg'] = htmlentities($item['title']) . ' has been enable successfully!';
                }
                $update_array = array(
                    'is_active' => $is_active,
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $this->Items_model->common_insert_update('update', TBL_ITEMS, $update_array, ['id' => $id]);
                $category_ids = ($this->Items_model->sql_select(TBL_ITEM_DETAILS, '*', ['where' => ['item_id' => $id]]));
                foreach($category_ids as $row)
                {
                    $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $row['category_id']]);
                    $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $row['menu_id']]);
                }
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
            redirect('restaurant/items');
        } else {
            show_404();
        }
    }

    /**
     * enable and disable of categories
     * @param int $id
     * */
    public function is_feature()
    {
        $id = $this->input->post('id');
        if (is_numeric($id)) {
            $item = $this->Items_model->get_item_detail(['id' => $id], 'id,title,is_featured');
            $is_featured;
            $msg = array();
            if ($item) {
                if ($item['is_featured'] == 1) {
                    $is_featured = 0;
                    $msg['status'] = 1;
                    $msg['msg'] = htmlentities($item['title']) . ' has been not featured!';
                } else {
                    $is_featured = 1;
                    $msg['status'] = 0;
                    $msg['msg'] = htmlentities($item['title']) . ' has been featured!';
                }
                $update_array = array(
                    'is_featured' => $is_featured,
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $this->Items_model->common_insert_update('update', TBL_ITEMS, $update_array, ['id' => $id]);

                $category_ids = ($this->Items_model->sql_select(TBL_ITEM_DETAILS, '*', ['where' => ['item_id' => $id]]));
                foreach($category_ids as $row)
                {
                    $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $row['category_id']]);
                    $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $row['menu_id']]);
                }
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
            redirect('restaurant/items');
        } else {
            show_404();
        }
    }

    public function images($id)
    {
        $data['title'] = WEBNAME.' | Item Images';
        $data['head'] = 'Items Images';
        $this->template->load('default', 'Backend/restaurant/items_images/index', $data);
    }

    /*
    * Delete Item Images
    */
    public function delete_images($id = null)
    {
        $id = base64_decode($id);
        if (is_numeric($id)) {
            $item = $this->Items_model->get_item_img_detail(['id' => $id], 'id,item_id');
            if ($item) {
                $update_array = array(
                    'is_deleted' => 1,
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $this->Items_model->common_insert_update('update', TBL_ITEM_IMAGES, $update_array, ['id' => $id]);

                $this->Items_model->common_insert_update('update', TBL_ITEMS, ['updated_at' => date('Y-m-d H:i:s')], ['id' => $item['item_id']]);
                $category_ids = ($this->Items_model->sql_select(TBL_ITEM_DETAILS, '*', ['where' => ['item_id' => $item['item_id']]]));
                foreach($category_ids as $row)
                {
                    $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $row['category_id']]);
                    $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $row['menu_id']]);
                }
                
                //$this->session->set_flashdata('success','Image has been deleted successfully!');
                echo json_encode(array('id'=> $id));
                exit;
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('restaurant/ItemImages/index/'.base64_encode($item['item_id']));
        } else {
            show_404();
        }
    }

    //Rearrange items
    public function sortabledatatable()
    {
        $orders = $this->input->post('order');
        if(!empty($orders))
        {
            foreach ($orders as $order) 
            {
                $this->Items_model->common_insert_update('update', TBL_ITEMS, ['order' => $order['position'],'updated_at' => date('Y-m-d H:i:s')], ['id' => $order['id']]);
                $category_ids = ($this->Items_model->sql_select(TBL_ITEM_DETAILS, '*', ['where' => ['item_id' => $order['id']]]));
                foreach($category_ids as $row)
                {
                    $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $row['category_id']]);
                    $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $row['menu_id']]);
                }
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

    public function get_menus()
    {
        echo '<pre>';
        $menus = $this->Items_model->get_menu_ids(52);
        if(!empty($menus))
        {
            foreach ($menus as $key => $rows) {
              print_r($rows['id']);
            }
        }
        die;
    }

    //Create Script for generate thumbnail images  of get data from folder.
    public function thumbnail()
    {
        $items = $this->Menus_model->custom_Query('SELECT DISTINCT item.id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate,'.TBL_MENUS.' as menu,'.TBL_USERS.' as user WHERE user.id = menu.restaurant_id AND menu.id = item_d.menu_id AND item.id = item_d.item_id AND cate.id = item_d.category_id AND user.id = 207')->result();
        echo '<pre>';
        foreach ($items as $key => $value) {
            $directory = RESTAURANT_IMAGES.'207/items/'.$value->id;
            // print_r($directory.'/thumbnail/');
            // die;
            if (!file_exists($directory)) 
            {
                mkdir($directory);
            }
            if($directory)
            {
                $itemsImage = $this->Menus_model->custom_Query('SELECT * FROM '.TBL_ITEM_IMAGES.' WHERE item_id = '.$value->id.'')->result();
                if($itemsImage)
                {
                    foreach ($itemsImage as $key => $img) {
                        if(!empty($img->image))
                        {
                            $target_path = $directory.'/thumbnail/';
                            if (!file_exists($target_path)) 
                            {
                                mkdir($target_path);
                            }
                            $src = $directory.'/'.$img->image;
                            resize_image($src,$target_path,500,500);
                        }
                    }
                }
                
            }
        }
       
    }

    public function test()
    {
        $itemImages = $this->users_model->sql_select(TBL_ITEM_IMAGES, '*', ['where' =>['item_id' => 110 ,'is_deleted' => 0]], ['single' => false]);
        foreach($itemImages as $row)
        {
            $thumbnail = explode('.',$row['image']);
            if ($thumbnail['1'] === 'jpg' || $thumbnail['1'] === 'jpeg' || $thumbnail['1'] === 'png') {
                $img = $thumbnail['0'].'_thumb.'.$thumbnail['1'];
                break;
            }
        }
    }

    function item1($cat_Id){

  $cat_Id=$cat_Id;
   $restaurant_id=$_SESSION['login_user']['id'];
   $myArray = array(
             array(
                "title" => "Grilled Beef Burger",
                "arabian_title" => "برجر لحم مشوي",
                "price" => 65,
                "calories" => 1.2,
                "description" => 'Waresh signature burger bun with grilled beef and vegetables. Served with fries',
                "arabian_description" => 'خبز برجر الخاص من الوارش مع الحم المشوي والخضروات، يقدم مع البطاطس المقلية',
                "type" =>'Veg',
                "time" =>'15-20',
                "is_featured"=>0,
                "is_dish_new"=>0,
                'created_at'=>date('Y-m-d H:i:s')
              )
     

 
    );
   
  
        foreach($myArray as $menudata){  
          $inserted_id = $this->Items_model->common_insert_update('insert', TBL_ITEMS, $menudata);
          $arrayName = array("Category_Id" =>$cat_Id );
              foreach($arrayName as $Category_Id)
                {                                       
                    $menus_ids = $this->Items_model->get_menu_ids_new($Category_Id,$restaurant_id);
             
                    if(!empty($menus_ids))
                    {
                        foreach ($menus_ids as $key => $rows) 
                        {
                            //echo "in";
                            $this->Categories_model->common_delete(TBL_NEW_ITEMS, ['menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id]);
                            $this->Categories_model->common_insert_update('insert', TBL_NEW_ITEMS, array('menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id,'created_at'=>date('Y-m-d H:i:s')));
                            $this->Categories_model->common_insert_update('insert', TBL_ITEM_DETAILS, array('menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id));
                            $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $Category_Id]);
                            $menus = ($this->Items_model->sql_select(TBL_CATEGORY_DETAILS, 'menu_id', ['where' => ['category_id' => $Category_Id]])); //get menu Id's
                            if(!empty($menus))
                            {
                                foreach ($menus as $key => $value) 
                                {
                                    $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $value['menu_id']]);   
                                }
                            }
                        }
                        
                    }
                }

        }       
                
   
}


function unique_multidim_array($array, $key) { 
    $temp_array = array(); 
    $i = 0; 
    $key_array = array();           
    foreach( $array as $val ) { 
        if ( ! in_array( $val[$key], $key_array ) ) { 
            $key_array[$i] = $val[$key]; 
            $temp_array[$i] = $val; 
        }
        $i++; 
    } 
    return $temp_array; 
}

}
