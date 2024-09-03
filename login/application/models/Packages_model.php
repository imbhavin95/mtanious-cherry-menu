<?php
/**
 * Manage packages table related database operation
 * @author sm
 */
class Packages_model extends MY_Model
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
    public function get_package_detail($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get(TBL_PACKAGES)->row_array();
    }

    public function get_package_detail1($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        $this->db->where('package_id != 3');
        return $this->db->get(TBL_PACKAGE_DETAILS)->row_array();
    }

    /**
     * Get packages for datatable
     * @param string $type - Either result or count
     * @return array for result or int for count
     */
    public function get_packages($type = 'result')
    {
        if (is_admin()) {
            $columns = ['id', 'name', 'price','description', 'type', 'is_active', 'created_at', 'is_deleted'];
        }else{
            $columns = ['id', 'name', 'price', 'users', 'menus', 'categories', 'items','is_deleted'];
        }
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $this->db->where('(name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR price LIKE ' . $this->db->escape('%' . $keyword['value'] . '%')  . ' OR description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%')  . ' OR type LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') .  ')');
        }
        $this->db->where(['is_deleted' => 0]);
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_PACKAGES);
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_PACKAGES);
            return $query->num_rows();
        }
    }
}
