<?php
class Reports_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Get items for datatable
     * @param string $type - Either result or count
     * @return array for result or int for count
     */
    public function get_reports($type = 'result')
    {
        $columns = ['id', 'name', 'title', 'type','no_of_clicks','created_at'];
        $keyword = $this->input->get('search');
        $startDate = $this->input->get('startDate');
        $endDate = $this->input->get('endDate');
        
        $this->db->select('ic.id,u.name,i.title,i.type,ic.no_of_clicks,ic.created_at');
        $this->db->join(TBL_ITEMS . ' as i', 'i.id=ic.item_id', 'left');
        // $this->db->join(TBL_CATEGORIES . ' as ca', 'i.category_id=ca.id', 'left');
        $this->db->join(TBL_USERS . ' as u', 'ic.staff_id=u.id', 'left');
        if (!empty($keyword['value'])) {
            $this->db->where('(i.title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.type LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ic.no_of_clicks LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') .' OR ic.created_at LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')');
        }
        if(!empty($startDate) && !empty($endDate))
        {
            $this->db->where('ic.created_at >= ',date('Y-m-d', strtotime($startDate))); 
            $this->db->where('ic.created_at <=',date('Y-m-d', strtotime($endDate)));
        }

        $this->db->where(['ic.restaurant_id' => $this->session->userdata('login_user')['id'],'ic.is_deleted' => 0]);
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ITEM_CLICKS.' ic');
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_ITEM_CLICKS.' ic');
            return $query->num_rows();
        }
    } 

    /**
     * Get items for datatable
     * @param string $type - Either result or count
     * @return array for result or int for count
     */
    public function get_category_reports($type = 'result')
    {
        $columns = ['id', 'name', 'title', 'no_of_clicks','created_at'];
        $keyword = $this->input->get('search');
        $startDate = $this->input->get('startDate');
        $endDate = $this->input->get('endDate');
        $this->db->select('cc.id,u.name,ca.title,cc.no_of_clicks,cc.created_at');
        $this->db->join(TBL_CATEGORIES . ' as ca', 'ca.id=cc.category_id', 'left');
        $this->db->join(TBL_USERS . ' as u', 'cc.staff_id=u.id', 'left');
        if (!empty($keyword['value'])) {
            $this->db->where('(ca.title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') .' OR m.title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR cc.no_of_clicks LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') .' OR cc.created_at LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')');
        }
        if(!empty($startDate) && !empty($endDate))
        {
            $this->db->where('cc.created_at >= ',date('Y-m-d', strtotime($startDate))); 
            $this->db->where('cc.created_at <=',date('Y-m-d', strtotime($endDate)));
        }
        $this->db->where(['cc.restaurant_id' => $this->session->userdata('login_user')['id'],'cc.is_deleted' => 0]);
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_CATEGORY_CLICKS.' cc');
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_CATEGORY_CLICKS.' cc');
            return $query->num_rows();
        }
    }
}
