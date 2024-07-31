<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ItemImages extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Items_model');
        $this->load->model('Categories_model');
        $this->load->model('Menus_model');
    }

    public function index($id)
    {
        if (!is_null($id)) {
            $id = $id;
        }
        $items = $this->Items_model->get_item_detail(['id' => base64_decode($id)]);
        if(!empty($items))
        {
            $data['itemId'] = $id;
            $data['title'] = WEBNAME.' | Item Images';
            $data['head'] = 'Item Images';
            $this->template->load('default', 'Backend/restaurant/items_images/index', $data);
        }else
        {
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('restaurant/items');
        }
    }

    /**
     * This function used to get categories data for ajax table
     * */
    public function get_item_images($id)
    {
        if (!is_null($id)) {
            $id = base64_decode($id);
        }
        $final['recordsFiltered'] = $final['recordsTotal'] = $this->Items_model->get_item_images('count',$id);
        $final['redraw'] = 1;
        $items = $this->Items_model->get_item_images('result',$id);
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['created_at'] = date('d M Y', strtotime($val['created_at']));
        }
        $final['data'] = $items;
        echo json_encode($final);
    }

    /**
     * This function used to add / edit categories data
     * @param int $id
     * */
    public function add($id = null)
    {
        $image = null;
        $directory = RESTAURANT_IMAGES . '/' . $this->session->userdata('login_user')['id']. '/items/' .base64_decode($this->input->post('itemId'));
        if (!file_exists(RESTAURANT_IMAGES . '/' . $this->session->userdata('login_user')['id']. '/items/')) {
            mkdir(RESTAURANT_IMAGES . '/' . $this->session->userdata('login_user')['id']. '/items/');
        }
        
        if (!file_exists($directory)) {
            mkdir($directory);
        }

        if ($_FILES['itemimage']['name'] != '') {
            $image_data = upload_item_images('itemimage', $directory);
            if (is_array($image_data)) {
                $data['item_image_validation'] = $image_data['errors'];
            } else {
                if ($image != '') {
                    unlink($directory . '/'.$image);
                }
                $image = $image_data;
            }
        }
        $dataArr = array(
            'item_id' => base64_decode($this->input->post('itemId')),
            'image' => $image,
        );
        $dataArr['created_at'] = date('Y-m-d H:i:s');
        $inserted_id = $this->Items_model->common_insert_update('insert', TBL_ITEM_IMAGES, $dataArr);

        $this->Items_model->common_insert_update('update', TBL_ITEMS, ['updated_at' => date('Y-m-d H:i:s')], ['id' => base64_decode($this->input->post('itemId'))]);
        $item = $this->Items_model->get_item_detail(['id' =>  base64_decode($this->input->post('itemId')), 'is_deleted' => 0, 'is_active' => 1]);
        $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $item['category_id']]);
        $category = $this->Categories_model->get_category_detail(['id' => $item['category_id'], 'is_deleted' => 0, 'is_active' => 1]);
        $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $category['menu_id']]);

        $this->session->set_flashdata('success', 'Item image has been added successfully');
        redirect('restaurant/ItemImages/index/'.$this->input->post('itemId'));
    }

    /**
     * Edit catgegory data
     * @param int $id
     * */
    public function edit($id=null)
    {
        $id = base64_decode($id);
        $item = $this->Items_model->get_item_img_detail(['id' => $id, 'is_deleted' => 0, 'is_active' => 1]);
        if ($item) {
            $data['item'] = $item;
            $data['title'] = WEBNAME.' | Edit Item Images';
            $data['head'] = 'Edit Item Images';
            $data['itemId'] = base64_encode($item['item_id']);
            $data['imgId'] = base64_encode($item['id']);
            $image = $item['image'];
            if(isset($_FILES['itemimage']))
            {
                $directory = RESTAURANT_IMAGES . '/' . $this->session->userdata('login_user')['id']. '/items/' .$item['item_id'];
                if (!file_exists(RESTAURANT_IMAGES . '/' . $this->session->userdata('login_user')['id']. '/items/')) {
                    mkdir(RESTAURANT_IMAGES . '/' . $this->session->userdata('login_user')['id']. '/items/');
                }
                if (!file_exists($directory)) {
                    mkdir($directory);
                }

                if ($_FILES['itemimage']['name'] != '') {
                    $image_data = upload_item_images('itemimage', $directory);
                    if (is_array($image_data)) {
                        $flag = 1;
                        $data['item_image_validation'] = $image_data['errors'];
                    } else {
                        if ($image != '') {
                            unlink($directory .'/'. $image);
                        }
                        $image = $image_data;
                    }
                }
                $dataArr = array(
                    'image' => $image,
                );
                $dataArr['updated_at'] = date('Y-m-d H:i:s');
                $this->Items_model->common_insert_update('update', TBL_ITEM_IMAGES, $dataArr, ['id' => base64_decode($this->input->post('imgId'))]);

                $this->Items_model->common_insert_update('update', TBL_ITEMS, ['updated_at' => date('Y-m-d H:i:s')], ['id' => $item['item_id']]);
                $item = $this->Items_model->get_item_detail(['id' =>  base64_decode($this->input->post('itemId')), 'is_deleted' => 0, 'is_active' => 1]);
                $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $item['category_id']]);
                $category = $this->Categories_model->get_category_detail(['id' => $item['category_id'], 'is_deleted' => 0, 'is_active' => 1]);
                $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $category['menu_id']]);

                $this->session->set_flashdata('success', 'Item Image\'s data has been updated successfully.');
                redirect('restaurant/ItemImages/index/'.$this->input->post('itemId'));
            }
            $this->template->load('default', 'Backend/restaurant/items_images/index', $data);
        }else 
        {
            $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            redirect('restaurant/items');
        }
    }

   /**
     * Delete Item Images
     * @param int $id
     * */
    public function delete($id = null)
    {
        $id = base64_decode($id);
        if (is_numeric($id)) {
            $item = $this->Items_model->get_item_img_detail(['id' => $id], 'id,item_id,item_id');
            if ($item) {
                $update_array = array(
                    'is_deleted' => 1,
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $this->Items_model->common_insert_update('update', TBL_ITEM_IMAGES, $update_array, ['id' => $id]);

                $this->Items_model->common_insert_update('update', TBL_ITEMS, ['updated_at' => date('Y-m-d H:i:s')], ['id' => $item['item_id']]);
                $item_detail = $this->Items_model->get_item_detail(['id' =>  $item['item_id'], 'is_deleted' => 0]);
                $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $item_detail['category_id']]);
                $category = $this->Categories_model->get_category_detail(['id' => $item_detail['category_id'], 'is_deleted' => 0]);
                $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $category['menu_id']]);

                $this->session->set_flashdata('success','Image has been deleted successfully!');
            } else {
                $this->session->set_flashdata('error', 'Invalid request. Please try again!');
            }
            redirect('restaurant/ItemImages/index/'.base64_encode($item['item_id']));
        } else {
            show_404();
        }
    }

    /**
     * enable and disable of item image
     * @param int $id
     * */
    public function change_status()
    {
        $id = $this->input->post('id');
        if (is_numeric($id)) {
            $itemimg = $this->Items_model->get_item_img_detail(['id' => $id], 'id,item_id,is_active');
            $is_active;
            $msg = array();
            if ($itemimg) {
                if ($itemimg['is_active'] == 1) {
                    $is_active = 0;
                    $msg['status'] = 1;
                    $msg['msg'] ='item image has been disable successfully!';
                } else {
                    $is_active = 1;
                    $msg['status'] = 0;
                    $msg['msg'] = 'item image has been enable successfully!';
                }
                $update_array = array(
                    'is_active' => $is_active,
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $this->Items_model->common_insert_update('update', TBL_ITEM_IMAGES, $update_array, ['id' => $id]);

                $this->Items_model->common_insert_update('update', TBL_ITEMS, ['updated_at' => date('Y-m-d H:i:s')], ['id' => $itemimg['item_id']]);
                $item_detail = $this->Items_model->get_item_detail(['id' =>  $itemimg['item_id'], 'is_deleted' => 0]);
                $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $item_detail['category_id']]);
                $category = $this->Categories_model->get_category_detail(['id' => $item_detail['category_id'], 'is_deleted' => 0]);
                $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $category['menu_id']]);

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

    public function upload_gallary($slug) {
        $slug;
        $where = 'slug = ' . $this->db->escape(urldecode($slug)) . ' AND is_delete = 0';
        $result = $this->users_model->get_result(TBL_SPOTS, $where);
        if ($result) {
            $check_cover_image = $this->users_model->check_cover_image($result[0]['id']);
            $type = 2;
            if ($check_cover_image == 0) {
                $type = 1;
            }
            $_FILES['images']['name'] = $_FILES['files']['name'][0];
            $_FILES['images']['type'] = $_FILES['files']['type'][0];
            $_FILES['images']['tmp_name'] = $_FILES['files']['tmp_name'][0];
            $_FILES['images']['error'] = $_FILES['files']['error'][0];
            $_FILES['images']['size'] = $_FILES['files']['size'][0];
            $image_name = upload_image('images', SPOT_GALLARY_ORIGINAL);
            if ($image_name) {
                copy(SPOT_GALLARY_ORIGINAL . '/' . $image_name, SPOT_GALLARY_THUMB . '/' . $image_name);
                crop(SPOT_GALLARY_THUMB . '/' . $image_name, 110, 110);

                // resize_image(SPOT_GALLARY_ORIGINAL.'/'.$image_name,SPOT_GALLARY_MEDIUM,262,207);
                copy(SPOT_GALLARY_ORIGINAL . '/' . $image_name, SPOT_GALLARY_MEDIUM . '/' . $image_name);
                crop(SPOT_GALLARY_MEDIUM . '/' . $image_name, 262, 207);

                // resize_image(SPOT_GALLARY_ORIGINAL.'/'.$image_name,SPOT_GALLARY_GALLERY_VIEW,750,500);
                copy(SPOT_GALLARY_ORIGINAL . '/' . $image_name, SPOT_GALLARY_GALLERY_VIEW . '/' . $image_name);
                crop(SPOT_GALLARY_GALLERY_VIEW . '/' . $image_name, 750, 500);

                // resize_image(SPOT_GALLARY_ORIGINAL.'/'.$image_name,SPOT_GALLARY_ZOOM_VIEW,1000,667);
                copy(SPOT_GALLARY_ORIGINAL . '/' . $image_name, SPOT_GALLARY_ZOOM_VIEW . '/' . $image_name);
                crop(SPOT_GALLARY_ZOOM_VIEW . '/' . $image_name, 1000, 667);
                $insert_data = array(
                    'spot_image_name' => $image_name,
                    'spot_id' => $result[0]['id'],
                    'user_id' => $this->session->userdata('user')['id'],
                    'type' => $type
                );
                $this->users_model->insert(TBL_SPOT_IMAGES, $insert_data);
                $_FILES['files']['url'] = SPOT_GALLARY_MEDIUM . '/' . $image_name;

                $info = new StdClass;
                $info->name = $image_name;
                $info->size = $_FILES['files']['size'][0];
                $info->type = $_FILES['files']['type'][0];
                $info->url = site_url() . SPOT_GALLARY_ORIGINAL . '/' . $image_name;
                $info->preview = SPOT_GALLARY_ORIGINAL . '/' . $image_name;
                $info->thumbnailUrl = SPOT_GALLARY_THUMB . '/' . $image_name;
                $info->deleteUrl = site_url() . 'user/delete_spot_gallery_image/' . $this->db->insert_id();
                $info->deleteType = 'DELETE';
                $info->error = null;
                $files[] = $info;
                echo json_encode(array("files" => $files));
                //exit;
            }
        } else {
            show_404();
        }
    }
}
