<?php
/**
 * Manage categories table related database operation
 * @author sm
 */
class Categories_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->check_login_user();
    }

    /**
     * Return categories detail
     * @param string/array $where
     * @param string/array $select
     * @return array
     */
    public function get_category_detail($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get(TBL_CATEGORIES)->row_array();
    }

    /**
     * Get categories for datatable
     * @param string $type - Either result or count
     * @return array for result or int for count
     */

    public function get_categories($type = 'result')
    {
        $columns = ['id', 'title', 'arabian_title','background_image','image', 'is_active','created_at', 'is_deleted'];
        $keyword = $this->input->get('search');
        $menus_array = $this->input->get('menus_array');
        
        $this->db->select('ca.*');
        $this->db->join(TBL_MENUS . ' as m', 'cd.menu_id=m.id', 'left');
        $this->db->join(TBL_CATEGORIES . ' as ca', 'cd.category_id=ca.id', 'left');
        if (!empty($keyword['value'])) {
            $this->db->where('(ca.title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ca.arabian_title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')');
        }
        $this->db->where(['m.restaurant_id' =>  $this->restaurant_id,'ca.is_deleted' => 0,'m.is_deleted' => 0]);
        //get all items based on menus
        if (!empty($menus_array)) {
            $this->db->where_in('cd.menu_id', $menus_array);
        }
        
        if(!empty($this->input->get('order')[0]['column']))
        {
            $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        }
        $this->db->group_by('ca.id');
        $this->db->order_by("ca.order", "asc");
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_CATEGORY_DETAILS.' cd');
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_CATEGORY_DETAILS.' cd');
            return $query->num_rows();
        }
    } 

     public function get_categories_default($type = 'result')
    {
         $this->restaurant_id=505;
        $columns = ['id', 'title', 'arabian_title','background_image','image', 'is_active','created_at', 'is_deleted'];
        $keyword = $this->input->get('search');
        $menus_array = $this->input->get('menus_array');
        
        $this->db->select('ca.*');
        $this->db->join(TBL_MENUS . ' as m', 'cd.menu_id=m.id', 'left');
        $this->db->join(TBL_CATEGORIES . ' as ca', 'cd.category_id=ca.id', 'left');
        if (!empty($keyword['value'])) {
            $this->db->where('(ca.title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ca.arabian_title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')');
        }
        $this->db->where(['m.restaurant_id' =>  $this->restaurant_id,'ca.is_deleted' => 0,'m.is_deleted' => 0]);
        //get all items based on menus
        if (!empty($menus_array)) {
            $this->db->where_in('cd.menu_id', $menus_array);
        }
        
        if(!empty($this->input->get('order')[0]['column']))
        {
            $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        }
        $this->db->group_by('ca.id');
        $this->db->order_by("ca.order", "asc");
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_CATEGORY_DETAILS.' cd');
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_CATEGORY_DETAILS.' cd');
            return $query->num_rows();
        }
    } 

        public function get_categories_user_default($type = 'result',$userId)
    {
         $this->restaurant_id=$userId;
        $columns = ['id', 'title', 'arabian_title','background_image','image', 'is_active','created_at', 'is_deleted'];
        $keyword = $this->input->get('search');
        $menus_array = $this->input->get('menus_array');
        
        $this->db->select('ca.*');
        $this->db->join(TBL_MENUS . ' as m', 'cd.menu_id=m.id', 'left');
        $this->db->join(TBL_CATEGORIES . ' as ca', 'cd.category_id=ca.id', 'left');
        if (!empty($keyword['value'])) {
            $this->db->where('(ca.title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ca.arabian_title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')');
        }
        $this->db->where(['m.restaurant_id' =>  $this->restaurant_id,'ca.is_deleted' => 0,'m.is_deleted' => 0]);
        //get all items based on menus
        if (!empty($menus_array)) {
            $this->db->where_in('cd.menu_id', $menus_array);
        }
        
        if(!empty($this->input->get('order')[0]['column']))
        {
            $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        }
        $this->db->group_by('ca.id');
        $this->db->order_by("ca.order", "asc");
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_CATEGORY_DETAILS.' cd');
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_CATEGORY_DETAILS.' cd');
            return $query->num_rows();
        }
    } 


        public function get_default_cat($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get('categories')->result_array();
    }

        public function get_category_details($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get('category_details')->result_array();
    }
}
