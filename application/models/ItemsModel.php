<?php
/**
 * Manage items table related database operation
 * @author sm
 */
class ItemsModel extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->check_login_user();
    }

    /**
     * Return items detail
     * @param string/array $where
     * @param string/array $select
     * @return array
     */
    public function get_item_detail($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get(TBL_ITEMS)->row_array();
    }

    /**
     * Get items for datatable
     * @param string $type - Either result or count
     * @return array for result or int for count
     */
    public function get_items($type = 'result')
    {
        $columns = ['id', 'title', 'arabian_title', 'price','is_featured','is_price_show','is_active','created_at', 'is_deleted','calories','time'];
        $keyword = $this->input->get('search');
        $is_active = $this->input->get('is_active');
        $category_array = $this->input->get('category_array');
        $menus_array = $this->input->get('menus_array');

        $this->db->select('i.*');
        $this->db->join(TBL_CATEGORIES . ' as ca', 'i.category_id=ca.id', 'left');
        $this->db->join(TBL_MENUS . ' as m', 'ca.menu_id=m.id', 'left');
        if (!empty($keyword['value'])) {
            $this->db->where('(i.title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.arabian_title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') .' OR i.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.arabian_description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%'). ')');
        }
        $this->db->where(['m.restaurant_id' => $this->restaurant_id,'i.is_deleted' => 0,'ca.is_deleted' => 0,'m.is_deleted' => 0]);
        if (!empty($is_active) && $is_active  == 1) {
            $this->db->where(['i.is_active' => $is_active]);
        }

        //get all items based on categories
        if (!empty($category_array)) {
            $this->db->where_in('i.category_id', $category_array);
        }

        //get all items based on menus
        if (!empty($menus_array)) {
            $this->db->where_in('ca.menu_id', $menus_array);
        }
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ITEMS.' i');
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_ITEMS.' i');
            return $query->num_rows();
        }
    }
     
    /**
     * Get items for datatable
     * @param string $type - Either result or count
     * @return array for result or int for count
     */
    public function get_item_images($type = 'result',$id)
    {
        $columns = ['id','image', 'is_active','created_at', 'is_deleted'];
        $this->db->select('img.*');
        $this->db->join(TBL_ITEMS . ' as i', 'img.item_id=i.id', 'left');
        
        $this->db->where(['img.item_id' => $id,'img.is_deleted' => 0,'i.is_deleted' => 0]);
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ITEM_IMAGES.' img');
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_ITEM_IMAGES.' img');
            return $query->num_rows();
        }
    } 

    /**
     * Return items detail
     * @param string/array $where
     * @param string/array $select
     * @return array
     */
    public function get_item_img_detail($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get(TBL_ITEM_IMAGES)->row_array();
    }

    public function get_items1($type = 'result')
    {
        $columns = ['id', 'title', 'arabian_title', 'price','is_featured', 'is_price_show','is_active','created_at', 'is_deleted','calories','time'];
        $keyword = $this->input->get('search');
        $is_active = $this->input->get('is_active');
        $category_array = $this->input->get('category_array');
        $menus_array = $this->input->get('menus_array');

        $this->db->select('i.*');
        $this->db->join(TBL_ITEMS . ' as i', 'idls.item_id=i.id', 'left');
        $this->db->join(TBL_CATEGORIES . ' as ca', 'idls.category_id=ca.id', 'left');
        $this->db->join(TBL_MENUS . ' as m', 'idls.menu_id=m.id', 'left');
        if (!empty($keyword['value'])) {
            $this->db->where('(i.title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.arabian_title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') .' OR i.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.arabian_description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%'). ')');
        }
        $this->db->where(['m.restaurant_id' => $this->restaurant_id,'i.is_deleted' => 0,'ca.is_deleted' => 0,'m.is_deleted' => 0]);
        if (!empty($is_active) && $is_active  == 1) {
            $this->db->where(['i.is_active' => $is_active]);
        }

        //get all items based on categories
        if (!empty($category_array)) {
            $this->db->where_in('idls.category_id', $category_array);
        }

        //get all items based on menus
        if (!empty($menus_array)) {
            $this->db->where_in('idls.menu_id', $menus_array);
        }

        if(!empty($this->input->get('order')[0]['column']))
        {
            $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        }
        $this->db->group_by('i.id');
        $this->db->order_by("i.order", "asc");
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ITEM_DETAILS.' idls');
            //print_r($this->db->last_query());die;
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_ITEM_DETAILS.' idls');
            return $query->num_rows();
        }
    }

    public function getCategories($id)
    {
        if (!empty($id)) 
        {
            $this->db->select('ca.*');
            $this->db->join(TBL_MENUS . ' as m', 'cd.menu_id=m.id', 'left');
            $this->db->join(TBL_CATEGORIES . ' as ca', 'cd.category_id=ca.id', 'left');
            $this->db->where(['cd.menu_id' =>  $id,'ca.is_deleted' => 0,'m.is_deleted' => 0]);
            $this->db->group_by('ca.id');
            $categories = $this->db->get(TBL_CATEGORY_DETAILS.' cd');
            return $categories->result_array();
        }else
        {
            return null;
        }
    }

    public function getAllCategories()
    {
        $this->db->select('ca.*');
        $this->db->join(TBL_MENUS . ' as m', 'cd.menu_id=m.id', 'left');
        $this->db->join(TBL_CATEGORIES . ' as ca', 'cd.category_id=ca.id', 'left');
        $this->db->where(['m.restaurant_id' =>  $this->restaurant_id,'ca.is_deleted' => 0,'m.is_deleted' => 0]);
        $this->db->group_by('ca.id');
        $this->db->order_by("ca.order", "asc");
        $query = $this->db->get(TBL_CATEGORY_DETAILS.' cd');
        return $query->result_array();
    }

    public function get_menu_ids($category_id=null)
    {
        $this->db->select('m.*');
        $this->db->join(TBL_MENUS . ' as m', 'cd.menu_id=m.id', 'left');
        $this->db->join(TBL_CATEGORIES . ' as ca', 'cd.category_id=ca.id', 'left');
        $this->db->where(['m.restaurant_id' =>  $this->restaurant_id,'ca.is_deleted' => 0,'m.is_deleted' => 0,'cd.category_id' => $category_id]);
        //$this->db->group_by('ca.id');
        //$this->db->order_by("ca.order", "asc");
        $query = $this->db->get(TBL_CATEGORY_DETAILS.' cd');
        return $query->result_array();
    }

      public function get_menu_ids_new($category_id=null,$restaurant_id='')
    {
        $this->db->select('m.*');
        $this->db->join(TBL_MENUS . ' as m', 'cd.menu_id=m.id', 'left');
        $this->db->join(TBL_CATEGORIES . ' as ca', 'cd.category_id=ca.id', 'left');
        $this->db->where(['m.restaurant_id' => $restaurant_id,'ca.is_deleted' => 0,'m.is_deleted' => 0,'cd.category_id' => $category_id]);
        //$this->db->group_by('ca.id');
        //$this->db->order_by("ca.order", "asc");
        $query = $this->db->get(TBL_CATEGORY_DETAILS.' cd');
        return $query->result_array();
    }

   public function delete_item_ref($id)
    {
       $this->db->where('item_id', $id);
       $this->db->delete('item_details'); 
    }

    public function get_default_item($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get('item_details')->result_array();
    }

     public function get_default_item_data($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get('items')->result_array();
    }

        public function get_items_default($type = 'result',$userId='')
    {
        $this->restaurant_id=$userId;
        $columns = ['id', 'title', 'arabian_title', 'price','is_featured', 'is_price_show','is_active','created_at', 'is_deleted','calories','time'];
        $keyword = $this->input->get('search');
        $is_active = $this->input->get('is_active');
        $category_array = $this->input->get('category_array');
        $menus_array = $this->input->get('menus_array');

        $this->db->select('i.*');
        $this->db->join(TBL_ITEMS . ' as i', 'idls.item_id=i.id', 'left');
        $this->db->join(TBL_CATEGORIES . ' as ca', 'idls.category_id=ca.id', 'left');
        $this->db->join(TBL_MENUS . ' as m', 'idls.menu_id=m.id', 'left');
        if (!empty($keyword['value'])) {
            $this->db->where('(i.title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.arabian_title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') .' OR i.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.arabian_description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%'). ')');
        }
        $this->db->where(['m.restaurant_id' => $this->restaurant_id,'i.is_deleted' => 0,'ca.is_deleted' => 0,'m.is_deleted' => 0]);
        if (!empty($is_active) && $is_active  == 1) {
            $this->db->where(['i.is_active' => $is_active]);
        }

        //get all items based on categories
        if (!empty($category_array)) {
            $this->db->where_in('idls.category_id', $category_array);
        }

        //get all items based on menus
        if (!empty($menus_array)) {
            $this->db->where_in('idls.menu_id', $menus_array);
        }

        if(!empty($this->input->get('order')[0]['column']))
        {
           // $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        }
        $this->db->group_by('i.id');
        $this->db->order_by("i.order", "asc");
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ITEM_DETAILS.' idls');
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_ITEM_DETAILS.' idls');
            return $query->num_rows();
        }
    }


       public function get_item_images_new($type = 'result',$id)
    {
       // $columns = ['id','image', 'is_active','created_at', 'is_deleted'];
        $this->db->select('img.*');
        $this->db->join(TBL_ITEMS . ' as i', 'img.item_id=i.id', 'left');
        
        $this->db->where(['img.item_id' => $id,'img.is_deleted' => 0,'i.is_deleted' => 0]);
       // $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ITEM_IMAGES.' img');
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_ITEM_IMAGES.' img');
            return $query->num_rows();
        }
    }


    public function get_items_bymenus($id){
        $this->db->select('id');
        $this->db->where('restaurant_id',$id);
        $res_menu_ids= $this->db->get(TBL_MENUS)->result_array();
        foreach ($res_menu_ids as   $value) {
             $menu_ids[]= $value['id']; 

        $sql="SELECT * FROM items s LEFT JOIN item_details itd on itd.item_id=s.id LEFT JOIN item_images timg on itd.item_id=itd.item_id WHERE itd.`menu_id` = ".$value['id']." and s.is_deleted=0 and s.is_active=1 GROUP by s.id,s.title ";
        $items_data= $this->db->query($sql)->result_array();
        
         if(!empty($items_data)){
        $new_data_result[]=array($value['id'] => $items_data);
         }
         }
        
        return $new_data_result;
    }



    public function get_items_bycategory($id){
        $this->db->select('id');
        $where=array(
            'restaurant_id'=>$id,
            'is_webmenu_active'=>1
        );
        $this->db->where($where);
        $res_menu_ids= $this->db->get(TBL_MENUS)->result_array();
         
        foreach ($res_menu_ids as   $value) {
             $menu_ids[]= $value['id']; 

          $sql="SELECT category_id FROM   item_details where `menu_id` = ".$value['id']."   ";
        $cat_data= $this->db->query($sql)->result_array();

         foreach ($cat_data as  $value1) {
            $cat_ids[]=$value1['category_id'];
         }

          $sql="SELECT category_id FROM   new_categories as cate where `menu_id` = ".$value['id']."     ";
        $cat_data1= $this->db->query($sql)->result_array();

         foreach ($cat_data1 as  $value2) {
            $cat_ids[]=$value2['category_id'];
         }


        }
        if(empty($cat_ids)){
           return false;
        }
        $ncat_ids=array_unique($cat_ids);
        $ncat_ids_exp=implode("','", $ncat_ids);
        $sql11="SELECT id FROM `categories` as cate where id IN ('".$ncat_ids_exp."') and is_active=1 order by cate.order ";
        $cat_sorted_data= $this->db->query($sql11)->result_array();
        foreach ($cat_sorted_data as  $sort_value) {
            $cat_sorted_ids[]=$sort_value['id'];
         }
         //print_r($ncat_ids);
        $new_items_data=null;
        foreach ($cat_sorted_ids as   $value2) {
        //     $sql="SELECT * FROM `items` it,item_images ig,item_details itd where it.id=ig.item_id and itd.item_id=it.id and itd.category_id=".$value2." and ig.is_deleted=0 and it.is_deleted=0 and it.is_active=1 GROUP by it.id ";
        // $item_data= $this->db->query($sql)->result_array();
            // echo $value2;
        $item_data2= $this->get_items_bycat($value2);

        $item_data=$item_data2['new'];
        
        $this->db->select('title,arabian_title');
        $this->db->where('id',$value2);
        $this->db->where('is_active',1);
        $this->db->order_by("order", "asc");
        $res_cat_names= $this->db->get(TBL_CATEGORIES)->row();
         //echo $this->db->last_query();
        if(isset($item_data) && !empty($item_data)){
        $new_items_data[]=array($value2=>$item_data,'name'=>$res_cat_names->title,'arabic_name'=>$res_cat_names->arabian_title);
       }
    }
       
        return $new_items_data;
    }



    public function single_item_detail($itemID){
         $this->load->model('UsersModel');
         $timestamp = null;
        $items = $this->UsersModel->sql_select(TBL_ITEMS, '*', ['where' =>['is_active' => 1,'is_deleted' => 0, 'id' => $itemID]], ['single' => false]);

        if(!empty($items))
                    {
                        $data = null;
                        foreach ($items as $key => $value)
                        {
                            $data[$key] = array(
                                'id' => $value['id'],
                                'title' => $value['title'],
                                'arabian_title' => $value['arabian_title'],
                                'price' => $value['price'],
                                'description' => $value['description'],
                                'arabian_description' => $value['arabian_description'],
                                'calories' => $value['calories'],
                                'is_featured' => $value['is_featured'],
                                'is_price_show' => $value['is_price_show'],
                                'is_dish_new' => $value['is_dish_new'],
                                'time' => $value['time'],
                                'type' => $value['type'],
                                'is_active' => $value['is_active'],
                            );
                        }
                        $where = ['item_id' => $itemID];
                        //get all records when didnt pass any time stamp
                        if(!is_null($timestamp))
                        {
                            $timestamp = urldecode($timestamp);
                            $new = $this->UsersModel->sql_select(TBL_ITEM_IMAGES, '*', ['where' =>array_merge($where, ['created_at > '=> $timestamp, 'is_deleted' => 0])], ['single' => false]);
                            if(!empty($new))
                            {
                                $where = ['item_id'=> $itemID ,'created_at <'=> $timestamp] ;
                                $updated = $this->UsersModel->sql_select(TBL_ITEM_IMAGES, '*', ['where' =>array_merge($where, ['updated_at >='=> $timestamp,'updated_at!=' => NULL, 'is_deleted' => 0])], ['single' => false]);
                                $deleted = $this->UsersModel->sql_select(TBL_ITEM_IMAGES, '*', ['where' =>array_merge($where, ['is_deleted' => 1])], ['single' => false]);
                                // print_r($this->db->last_query());
                                // die;
                            }else
                            {
                                $where = ['item_id'=> $itemID ,'updated_at >= '=> $timestamp] ;
                                $updated = $this->UsersModel->sql_select(TBL_ITEM_IMAGES, '*', ['where' =>array_merge($where, ['updated_at!=' => NULL, 'is_deleted' => 0])], ['single' => false]);
                                $deleted = $this->UsersModel->sql_select(TBL_ITEM_IMAGES, '*', ['where' =>array_merge($where, ['is_deleted' => 1])], ['single' => false]);
                            } 
                        } 
                            $new = $this->UsersModel->sql_select(TBL_ITEM_IMAGES, '*', ['where' =>array_merge($where, ['is_deleted' => 0])], ['single' => false]);
                            $updated = [];
                            $deleted = [];
                        $data1['Item']=$data;
                        $data1['images']=['new' => $new, 'updated' => $updated,'deleted' => $deleted];
                        return $data1;
                    }else{
                        return false;
                    }
    }






    public function get_items_bycat($categoryId, $timestamp = null){
        if (!is_null($categoryId)) 
                {
                    $categories = $this->UsersModel->sql_select(TBL_CATEGORIES, '*', ['where' =>['is_deleted' => 0, 'id' => $categoryId]], ['single' => false]);
                    if(!empty($categories))
                    {
                        if(!is_null($timestamp))
                        {
                            $timestamp = urldecode($timestamp);
                            $new = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_price_show,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 0  AND item.created_at > "'.$timestamp.'"  AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                            if(!empty($new))
                            {
                                $updated = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_price_show,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 0 AND item.created_at < "'.$timestamp.'" AND item.updated_at != "" AND item.updated_at >= "'.$timestamp.'" AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                                $deleted = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_price_show,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 1 AND item.created_at < "'.$timestamp.'" AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                                
                                $deleted_item = $this->db->query('SELECT * from  '.TBL_DELETE_ITEMS.' where is_deleted = 1 AND category_id = '.$categoryId.' AND created_at >= "'.$timestamp.'"')->result_array();
                                $new_item = $this->db->query('SELECT * from  '.TBL_NEW_ITEMS.' where category_id = '.$categoryId.' AND created_at >= "'.$timestamp.'"')->result_array();
                                if(!empty($new_item))
                                {
                                    $new_data;
                                    foreach ($new_item as $key => $rows) 
                                    {
                                        $get_items= $this->db->query('SELECT * from  '.TBL_ITEMS.' where id = '.$rows['item_id'].'')->row_array();
                                        $new_data[$key] = array('id' => $get_items['id'],
                                        'order' => $get_items['order'],
                                        'title' => $get_items['title'],
                                        'arabian_title' => $get_items['arabian_title'],
                                        'price' => $get_items['price'],
                                        'description' => $get_items['description'],
                                        'arabian_description' => $get_items['arabian_description'],
                                        'calories' => $get_items['calories'],
                                        'is_featured' => $get_items['is_featured'],
                                        'is_price_show' => $get_items['is_price_show'],
                                        'is_dish_new' => $get_items['is_dish_new'],
                                        'time' => $get_items['time'],
                                        'type' => $get_items['type'],
                                        'is_active' => $get_items['is_active'],
                                        'is_deleted' => 0,
                                        'created_at' => $get_items['created_at'],
                                        'updated_at' => $rows['created_at'], //get timestamp from 
                                        'category_id' => $rows['category_id'] //get category_id
                                       );
                                    }
                                    $new = $new_data;
                                }
                                if(!empty($deleted_item))
                                {
                                    foreach ($deleted_item as $key => $rows) 
                                    {
                                        $get_items= $this->db->query('SELECT * from  '.TBL_ITEMS.' where id = '.$rows['item_id'].'')->row_array();
                                        $data[$key] = array('id' => $get_items['id'],
                                        'order' => $get_items['order'],
                                        'title' => $get_items['title'],
                                        'arabian_title' => $get_items['arabian_title'],
                                        'price' => $get_items['price'],
                                        'description' => $get_items['description'],
                                        'arabian_description' => $get_items['arabian_description'],
                                        'calories' => $get_items['calories'],
                                        'is_featured' => $get_items['is_featured'],
                                        'is_price_show' => $get_items['is_price_show'],
                                        'is_dish_new' => $get_items['is_dish_new'],
                                        'time' => $get_items['time'],
                                        'type' => $get_items['type'],
                                        'is_active' => $get_items['is_active'],
                                        'is_deleted' => 0,
                                        'created_at' => $get_items['created_at'],
                                        'updated_at' => $rows['created_at'], //get timestamp from 
                                        'category_id' => $rows['category_id'] //get category_id
                                       );
                                    }
                                    $deleted = $data;
                                }
                            }else
                            {
                                $updated = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_price_show,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 0 AND item.updated_at != " " AND item.updated_at >= "'.$timestamp.'" AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                                $deleted = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_price_show,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 1 AND item.updated_at >= "'.$timestamp.'" AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                                $deleted_item = $this->db->query('SELECT * from  '.TBL_DELETE_ITEMS.' where is_deleted = 1 AND category_id = '.$categoryId.' AND created_at >= "'.$timestamp.'"')->result_array();
                                if(!empty($deleted_item))
                                {
                                    foreach ($deleted_item as $key => $rows) 
                                    {
                                        $get_items= $this->db->query('SELECT * from  '.TBL_ITEMS.' where id = '.$rows['item_id'].'')->row_array();
                                        $data[$key] = array('id' => $get_items['id'],
                                        'order' => $get_items['order'],
                                        'title' => $get_items['title'],
                                        'arabian_title' => $get_items['arabian_title'],
                                        'price' => $get_items['price'],
                                        'description' => $get_items['description'],
                                        'arabian_description' => $get_items['arabian_description'],
                                        'calories' => $get_items['calories'],
                                        'is_featured' => $get_items['is_featured'],
                                        'is_price_show' => $get_items['is_price_show'],
                                        'is_dish_new' => $get_items['is_dish_new'],
                                        'time' => $get_items['time'],
                                        'type' => $get_items['type'],
                                        'is_active' => $get_items['is_active'],
                                        'is_deleted' => 0,
                                        'created_at' => $get_items['created_at'],
                                        'updated_at' => $rows['created_at'], //get timestamp from 
                                        'category_id' => $rows['category_id'] //get category_id
                                       );
                                    }
                                    $deleted = $data;
                                }
                            } 
                        }else
                        {
                            $new = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_price_show,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 0 and item.is_active=1 AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                            $updated = [];
                            $deleted = [];
                        }
                        
                        // Thumbnail images
                        if(!empty($new))
                        {
                            foreach ($new as $key => $rows) 
                            {
                                $itemImages = $this->UsersModel->sql_select(TBL_ITEM_IMAGES, '*', ['where' =>['item_id' => $rows['id'] ,'is_deleted' => 0]], ['single' => false]);
                                $img = null;
                                foreach($itemImages as $row)
                                {
                                    $thumbnail = explode('.',$row['image']);
                                    if ($thumbnail['1'] === 'jpg' || $thumbnail['1'] === 'jpeg' || $thumbnail['1'] === 'png' || $thumbnail['1'] === 'PNG' || $thumbnail['1'] === 'JPG' || $thumbnail['1'] === 'JPEG') {
                                        $img = $thumbnail['0'].'_thumb.'.$thumbnail['1'];
                                        break;
                                    }
                                }
                                $data[$key] = array('id' => $rows['id'],
                                'order' => $rows['order'],
                                'title' => $rows['title'],
                                'arabian_title' => $rows['arabian_title'],
                                'price' => $rows['price'],
                                'description' => $rows['description'],
                                'arabian_description' => $rows['arabian_description'],
                                'calories' => $rows['calories'],
                                'is_featured' => $rows['is_featured'],
                                'is_price_show' => $rows['is_price_show'],
                                'is_dish_new' => $rows['is_dish_new'],
                                'time' => $rows['time'],
                                'type' => $rows['type'],
                                'is_active' => $rows['is_active'],
                                'is_deleted' => 0,
                                'created_at' => $rows['created_at'],
                                'updated_at' => $rows['updated_at'], //get timestamp from 
                                'category_id' => $rows['category_id'], //get category_id
                                'thumbnail' => $img
                               );
                            }
                            $new = $data;
                        }

                        // Thumbnail images
                        if(!empty($updated))
                        {
                            foreach ($updated as $key => $rows) 
                            {
                                $itemImages = $this->UsersModel->sql_select(TBL_ITEM_IMAGES, '*', ['where' =>['item_id' => $rows['id'] ,'is_deleted' => 0]], ['single' => false]);
                                $img = null;
                                foreach($itemImages as $row)
                                {
                                    $thumbnail = explode('.',$row['image']);
                                    if ($thumbnail['1'] === 'jpg' || $thumbnail['1'] === 'jpeg' || $thumbnail['1'] === 'png' || $thumbnail['1'] === 'PNG' || $thumbnail['1'] === 'JPG' || $thumbnail['1'] === 'JPEG') {
                                        $img = $thumbnail['0'].'_thumb.'.$thumbnail['1'];
                                        break;
                                    }
                                }
                                // if(!empty($itemImages[0]))
                                // {
                                //     $img = $itemImages[0]['image'];
                                //     $thumbnail = explode('.',$img);
                                //     $img = $thumbnail['0'].'_thumb.'.$thumbnail['1'];
                                // }
                                $data[$key] = array('id' => $rows['id'],
                                'order' => $rows['order'],
                                'title' => $rows['title'],
                                'arabian_title' => $rows['arabian_title'],
                                'price' => $rows['price'],
                                'description' => $rows['description'],
                                'arabian_description' => $rows['arabian_description'],
                                'calories' => $rows['calories'],
                                'is_featured' => $rows['is_featured'],
                                'is_price_show' => $rows['is_price_show'],
                                'is_dish_new' => $rows['is_dish_new'],
                                'time' => $rows['time'],
                                'type' => $rows['type'],
                                'is_active' => $rows['is_active'],
                                'is_deleted' => 0,
                                'created_at' => $rows['created_at'],
                                'updated_at' => $rows['updated_at'], //get timestamp from 
                                'category_id' => $rows['category_id'], //get category_id
                                'thumbnail' => $img
                               );
                            }
                            $updated = $data;
                        }

                        // Thumbnail images
                        if(!empty($deleted))
                        {
                            $dele;
                            foreach ($deleted as $key => $rows) 
                            {
                               // print_r($rows['id']);
                                $itemImages = $this->users_model->sql_select(TBL_ITEM_IMAGES, '*', ['where' =>['item_id' => $rows['id'] ,'is_deleted' => 0]], ['single' => false]);
                                $img = null;
                                foreach($itemImages as $row)
                                {
                                    $thumbnail = explode('.',$row['image']);
                                    if ($thumbnail['1'] === 'jpg' || $thumbnail['1'] === 'jpeg' || $thumbnail['1'] === 'png' || $thumbnail['1'] === 'PNG' || $thumbnail['1'] === 'JPG' || $thumbnail['1'] === 'JPEG') {
                                        $img = $thumbnail['0'].'_thumb.'.$thumbnail['1'];
                                        break;
                                    }
                                }
                                // if(!empty($itemImages[0]))
                                // {
                                //     $img = $itemImages[0]['image'];
                                //     $thumbnail = explode('.',$img);
                                //     $img = $thumbnail['0'].'_thumb.'.$thumbnail['1'];
                                // }
                                $dele[$key] = array('id' => $rows['id'],
                                'order' => $rows['order'],
                                'title' => $rows['title'],
                                'arabian_title' => $rows['arabian_title'],
                                'price' => $rows['price'],
                                'description' => $rows['description'],
                                'arabian_description' => $rows['arabian_description'],
                                'calories' => $rows['calories'],
                                'is_featured' => $rows['is_featured'],
                                'is_price_show' => $rows['is_price_show'],
                                'is_dish_new' => $rows['is_dish_new'],
                                'time' => $rows['time'],
                                'type' => $rows['type'],
                                'is_active' => $rows['is_active'],
                                'is_deleted' => 0,
                                'created_at' => $rows['created_at'],
                                'updated_at' => $rows['updated_at'], //get timestamp from 
                                'category_id' => $rows['category_id'], //get category_id
                                'thumbnail' => $img
                               );
                            }
                            $deleted = $dele;
                        }

                        $resdata['new']=$new;
                        // $resdata['updated']=$updated;
                        // $resdata['deleted']=$deleted;
                         return $resdata;
                    }else
                    {
                         return false;
                    }
                }else{
                    return false;
                }
    }


    public function get_menu_name($catid){
        $sql="SELECT DISTINCT ms.title FROM `item_details` itd,menus ms WHERE ms.id=itd.menu_id and itd.category_id='".$catid."'";
        $res=$this->db->query($sql)->result_array();
        // print_r($res);
        if(isset($res) && !empty($res)){
            return $res[0]['title'];
        }else{
            return false;
        }
        
    }

    
    
}
