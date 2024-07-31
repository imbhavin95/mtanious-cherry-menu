<?php
/**
 * Manage package details table related database operation
 * @author sm
 */
class Invoices_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return packages detail
     * @param string/array $where
     * @param string/array $select
     * @return array
     */
    public function get_detail($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get(TBL_PACKAGE_DETAILS)->row_array();
    }

    /**
     * Get package request for datatable
     * @param string $type - Either result or count
     * @return array for result or int for count
     */
    public function get_package_request($type = 'result')
    {
        $columns = ['id', 'user_name','total_package','total_price','total_discount'];
        $keyword = $this->input->get('search');
        $this->db->select('rd.*,u.name as user_name,COUNT(rd.restaurant_id) as total_package,SUM(pkg.price) as total_price,SUM(pkg.discount) as total_discount');
        $this->db->group_by('rd.restaurant_id');
        $this->db->join(TBL_PACKAGES . ' as pkg', 'rd.package_id=pkg.id', 'left');
        $this->db->join(TBL_USERS . ' as u', 'rd.restaurant_id=u.id', 'left');
        if (!empty($keyword['value'])) 
        {
            $this->db->where('(u.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR pkg.total_price LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR rd.total_discount LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') .  ')');
        }
        $this->db->where(['rd.is_deleted' => 0,'pkg.type !=' => 'free']);
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_PACKAGE_DETAILS.' as rd');
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_PACKAGE_DETAILS.' as rd');
            return $query->num_rows();
        }
    }
}
