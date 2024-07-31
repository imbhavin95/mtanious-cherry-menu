<?php
/**
 * Manage users table related database operation
 * @author sm
 */
class Types_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return help topics detail
     * @param string/array $where
     * @param string/array $select
     * @return array
     */
    public function get_types_detail($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get(TBL_TYPES)->row_array();
    }

    /**
     * Get help topics for datatable
     * @param string $type - Either result or count
     * @return array for result or int for count
     */
    public function get_types($type = 'result')
    {
        $columns = ['id', 'type', 'is_active', 'created_at', 'is_deleted'];
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $this->db->where('(type LIKE ' . $this->db->escape('%' . $keyword['value'] . '%')  . ')');
        }
        $this->db->where(['is_deleted' => 0]);
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_TYPES);
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_TYPES);
            return $query->num_rows();
        }
    }

        /**
     * Check type exist or not for unique type
     * @param string $type
     * @return array
     */
    public function check_unique_type($type,$id = null)
    {
        $this->db->where('type', $type);
        $this->db->where('is_deleted', 0);
        if(!empty($id))
        {
           $this->db->where('id !=', $id);
        }
        $query = $this->db->get(TBL_TYPES);
        return $query->row_array();
    }
}
