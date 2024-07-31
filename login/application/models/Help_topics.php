<?php
/**
 * Manage users table related database operation
 * @author sm
 */
class Help_topics extends MY_Model
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
    public function get_help_topics_detail($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get(TBL_HELP_TOPICS)->row_array();
    }

    /**
     * Get help topics for datatable
     * @param string $type - Either result or count
     * @return array for result or int for count
     */
    public function get_help_topics($type = 'result')
    {
        $columns = ['id', 'title', 'description', 'is_active', 'created_at', 'is_deleted'];
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $this->db->where('(title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')');
        }
        $this->db->where(['is_deleted' => 0]);
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_HELP_TOPICS);
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_HELP_TOPICS);
            return $query->num_rows();
        }
    }
}
