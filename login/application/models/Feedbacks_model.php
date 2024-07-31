<?php
/**
 * Manage Feedback table related database operation
 * @author sm
 */
class Feedbacks_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return items detail
     * @param string/array $where
     * @param string/array $select
     * @return array
     */
    public function get_feedback_detail($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get(TBL_FEEDBACKS)->row_array();
    }

    /**
     * Get feedback for datatable
     * @param string $type - Either result or count
     * @return array for result or int for count
     */
    public function get_feedback($type = 'result')
    {
        $columns = ['id','customer_name','staff_name','feedback','stars','created_at', 'is_deleted'];
        $keyword = $this->input->get('search');
        $startDate = $this->input->get('startDate');
        $endDate = $this->input->get('endDate');
        $this->db->select('f.*');
        $this->db->join(TBL_USERS . ' as ru', 'f.restaurant_id=ru.id', 'left');
        $this->db->join(TBL_USERS . ' as u', 'f.staff_id=u.id', 'left');
        if (!empty($keyword['value'])) {
            $this->db->where('(f.feedback LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR f.staff_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR f.customer_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') .  ' OR f.stars LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') .' OR f.created_at LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')');
        }
        if(!empty($startDate) && !empty($endDate))
        {
            $this->db->where('f.created_at >= ',date('Y-m-d', strtotime($startDate))); 
            $this->db->where('f.created_at <=',date('Y-m-d', strtotime($endDate)));
        }
        $this->db->where(['f.restaurant_id' => $this->session->userdata('login_user')['id'],'f.is_deleted' => 0,'ru.is_deleted' => 0,'u.is_deleted' => 0]);
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_FEEDBACKS.' f');
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_FEEDBACKS.' f');
            return $query->num_rows();
        }
    } 
}
