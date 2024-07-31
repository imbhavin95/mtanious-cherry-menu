<?php
/**
 * Manage items table related database operation
 * @author sm
 */
class Orders_model extends MY_Model
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
    public function get_device_detail($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get(TBL_ACTIVE_DEVICES)->row_array();
    }

    /**
     * Get items for datatable
     * @param string $type - Either result or count
     * @return array for result or int for count
     */
    public function get_orders($type = 'result')
    {
        
        $columns = ['order_id', 'restaurant_id', 'staff_id', 'ordered_time', 'is_deleted'];
        
        $keyword = $this->input->get('search');
        $this->db->select('ad.*,ru.name as staff_name');
        $this->db->join(TBL_USERS . ' as ru', 'ad.staff_id=ru.id', 'left');

        if (!empty($keyword['value'])) {
                $this->db->where('(ad.order_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ru.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ad.ordered_time LIKE ' . $this->db->escape('%' . $keyword['value'] . '%'). ')');  
        }
        if(is_restaurant())
        {
            $this->db->where(['ad.restaurant_id' => $this->session->userdata('login_user')['id']]);
        }
        $this->db->where(['ad.is_deleted' => 0]);
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        $this->db->order_by('ad.order_id desc');
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ORDER.' ad');
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_ORDER.' ad');
            return $query->num_rows();
        }
    }

    public function get_order_detail($where, $select = '*')
    {
        $this->db->select('i.title,d.item_qty,d.item_price');
        $this->db->where('o.order_id',$where);
        $this->db->join(TBL_ORDER_DETAILS . ' as d', 'd.order_id=o.order_id', 'left');
        $this->db->join(TBL_ITEMS . ' as i', 'i.id=d.item_id', 'left');
        $this->db->group_by('d.order_id,d.item_id,d.id');
        return $this->db->get(TBL_ORDER.' o')->result_array();
    } 
        public function get_reports1($type = 'result')
    {
        $columns = ['id','name','title', 'type','item_qty','ordered_time'];
        $keyword = $this->input->get('search');
        $startDate = $this->input->get('startDate');
        $endDate = $this->input->get('endDate');
        
        $this->db->select('ic.id,u.name,i.title,i.type,SUM(ic.item_qty) as item_qty,o.ordered_time');
        $this->db->join(TBL_ORDER . ' as o', 'o.order_id=ic.order_id', 'left');
        $this->db->join(TBL_ITEMS . ' as i', 'i.id=ic.item_id', 'left');
        // $this->db->join(TBL_CATEGORIES . ' as ca', 'i.category_id=ca.id', 'left');
        $this->db->join(TBL_USERS . ' as u', 'o.staff_id=u.id', 'left');
        if (!empty($keyword['value'])) {
            $this->db->where('(i.title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.type LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ic.item_qty LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')');
        }
        if(!empty($startDate) && !empty($endDate))
        {
            $this->db->where('o.ordered_time >= ',date('Y-m-d', strtotime($startDate))); 
            $this->db->where('o.ordered_time <=',date('Y-m-d', strtotime($endDate)));
        }

        $this->db->where(['o.restaurant_id' => $this->session->userdata('login_user')['id']]);
       // $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        $this->db->group_by('ic.item_id,i.id');
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ORDER_DETAILS.' ic');
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_ORDER_DETAILS.' ic');
            return $query->num_rows();
        }
    } 
    public function get_reports($type = 'result')
    {
        $columns = ['id', 'name', 'title', 'item_qty','ordered_time','category'];
        $keyword = $this->input->get('search');
        $startDate = $this->input->get('startDate');
        $endDate = $this->input->get('endDate');
        
        $this->db->select('ic.id,u.name,i.title,i.type,SUM(ic.item_qty) as item_qty,o.ordered_time,ca.title as category');
        $this->db->join(TBL_ORDER . ' as o', 'o.order_id=ic.order_id', 'left');
        $this->db->join(TBL_ITEMS . ' as i', 'i.id=ic.item_id', 'left');
        $this->db->join(TBL_CATEGORIES . ' as ca', 'ic.category_id=ca.id', 'left');
        $this->db->join(TBL_USERS . ' as u', 'o.staff_id=u.id', 'left');
        if (!empty($keyword['value'])) {
            $this->db->where('(i.title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') .   ' OR ic.item_qty LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') .' OR ic.ordered_time LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')');
        }
        if(!empty($startDate) && !empty($endDate))
        {
            $this->db->where('date(o.ordered_time) >= ',date('Y-m-d', strtotime($startDate))); 
            $this->db->where('date(o.ordered_time) <=',date('Y-m-d', strtotime($endDate)));
        }

        $this->db->where(['o.restaurant_id' => $this->session->userdata('login_user')['id'],'o.is_deleted' => 0]);
       // $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        $this->db->order_by('DATE(o.ordered_time) DESC');
        $this->db->group_by('ic.item_id,ic.id,ic.order_id');
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ORDER_DETAILS.' ic');
              return $query->result_array();
        } else {
            $query = $this->db->get(TBL_ORDER_DETAILS.' ic');
            return $query->num_rows();
        }
    }  
}
