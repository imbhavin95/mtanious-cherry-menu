<?php
/**
 * Manage menus table related database operation
 * @author sm
 */
class Menus_model extends MY_Model
{
    
    public function __construct()
    {
        parent::__construct();
        $this->check_login_user();
    }

    /**
     * Return menus detail
     * @param string/array $where
     * @param string/array $select
     * @return array
     */
    public function get_menu_detail($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get(TBL_MENUS)->row_array();
    }

     public function get_menu_detail_new($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get('default_menus')->row_array();
    }

       public function get_menu_all($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get('default_menus')->result_array();
    }

    /**
     * Get menus for datatable
     * @param string $type - Either result or count
     * @return array for result or int for count
     */
    public function get_menus($type = 'result')
    {
        $columns = ['id','background_image', 'title', 'arabian_title','is_disable_feedback', 'is_active', 'created_at', 'is_deleted'];
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $this->db->where('(title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR arabian_title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')');
        }
        $this->db->where(['restaurant_id' => $this->restaurant_id, 'is_deleted' => 0]);
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_MENUS);
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_MENUS);
            return $query->num_rows();
        }
    }

       public function get_menus_new($type = 'result')
    {
        $columns = ['id','background_image', 'title', 'arabian_title','is_disable_feedback', 'is_active', 'created_at', 'is_deleted'];
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $this->db->where('(title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR arabian_title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')');
        }
        $this->db->where(['is_deleted' => 0]);
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get('default_menus');
            return $query->result_array();
        } else {
            $query = $this->db->get('default_menus');
            return $query->num_rows();
        }
    }


     public function get_menus_default($type = '')
    {
       $this->restaurant_id=505;
        $columns = ['id','background_image', 'title', 'arabian_title','is_disable_feedback', 'is_active', 'created_at', 'is_deleted'];
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $this->db->where('(title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR arabian_title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')');
        }
        $this->db->where(['restaurant_id' => $this->restaurant_id, 'is_deleted' => 0]);
        //$this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_MENUS);
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_MENUS);
            return $query->num_rows();
        }
    }


     public function get_user_menus($type = '',$userId='')
    {
       $this->restaurant_id=$userId;
        $columns = ['id','background_image', 'title', 'arabian_title','is_disable_feedback', 'is_active', 'created_at', 'is_deleted'];
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $this->db->where('(title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR arabian_title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')');
        }
        $this->db->where(['restaurant_id' => $this->restaurant_id, 'is_deleted' => 0]);
      //  $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_MENUS);
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_MENUS);
            return $query->num_rows();
        }
    }


       public function get_default_menu($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get('menus')->result_array();
    }

}
