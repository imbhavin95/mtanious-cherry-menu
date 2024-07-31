<?php
/**
 * Manage active devices table related database operation
 * @author sm
 */
class Active_devices extends MY_Model
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

    public function get_androidversion()
    {
        $this->db->select('*');
        $this->db->where('id',1);
        return $this->db->get(TBL_ANDROIDVERSION)->row_array();
    }

    /**
     * Get items for datatable
     * @param string $type - Either result or count
     * @return array for result or int for count
     */
    public function get_devices($type = 'result')
    {
        if(!is_restaurant())
        {
            $columns = ['id', 'name', 'restaurant_name', 'version', 'is_login', 'created_at', 'is_deleted'];
        }
        else
        {
            $columns = ['id', 'name', 'version', 'is_login', 'created_at', 'is_deleted'];
        }
        $keyword = $this->input->get('search');
        $this->db->select('ad.*,ru.name as restaurant_name');
        $this->db->join(TBL_USERS . ' as ru', 'ad.restaurant_id=ru.id', 'left');

        if (!empty($keyword['value'])) {
            if(!is_restaurant())
            {
                $this->db->where('(ad.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ad.version LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ru.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%'). ')');
            }
            else
            {
                $this->db->where('(ad.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ad.version LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')');
            }
        }
        if(is_restaurant())
        {
            $this->db->where(['ad.restaurant_id' => $this->session->userdata('login_user')['id']]);
        }
        $this->db->where(['ad.is_login' => 1,'ad.is_deleted' => 0]);
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ACTIVE_DEVICES.' ad');
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_ACTIVE_DEVICES.' ad');
            return $query->num_rows();
        }
    }  
}
