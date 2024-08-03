<?php
/**
 * Manage users table related database operation
 * @author sm
 */
class UsersModel extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return user detail
     * @param string/array $where
     * @param string/array $select
     * @return array
     */
    public function get_user_detail($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get(TBL_USERS)->row_array();
    }
    public function get_user_id($where, $select = 'id')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get(TBL_USERS)->row();
    }


    public function run_manual_query($query)
    {
     return $this->db->query($query)->result_array();
    }

    public function home_graph($start_date, $end_date)
    {
        //$this->db->select($select);
        //$this->db->where($where);
        return $this->db->query("select SUM(no_of_clicks) as sum, DATE_FORMAT(created_at, '%Y-%m-%d') as date FROM item_clicks WHERE is_deleted = 0 AND restaurant_id = '".$this->session->userdata('login_user')['id']."' AND created_at >= '". date('Y-m-d', strtotime($start_date))."' AND created_at <= '". date('Y-m-d', strtotime($end_date))."' GROUP BY date")->result_array();
    }
    public function home_graph_cat($start_date, $end_date)
    {
        //$this->db->select($select);
        //$this->db->where($where);
        return $this->db->query("SELECT SUM(no_of_clicks) as sum, DATE_FORMAT(created_at, '%Y-%m-%d') as date FROM category_clicks WHERE is_deleted = 0 AND restaurant_id = '".$this->session->userdata('login_user')['id']."' AND created_at >= '". date('Y-m-d', strtotime($start_date))."' AND created_at <= '". date('Y-m-d', strtotime($end_date))."' GROUP BY date")->result_array();
    }
    /**
     * Set cookie with passed email id
     * @param string $email
     * @return boolean
     */
    public function activate_remember_me($email)
    {
        $encoded_email = $this->encrypt->encode($email);
        set_cookie(REMEMBER_ME_COOKIE_NAME, $encoded_email, time() + (3600 * 24 * 360));
        return true;
    }

    /**
     * Check verification code exists or not in users table
     * @param string $verification_code
     * @return array
     */
    public function check_verification_code($verification_code)
    {
        $this->db->where('verification_code', $verification_code);
        // $this->db->where('is_deleted', 0);
        $query = $this->db->get(TBL_USERS);
        return $query->row_array();
    }

    /**
     * Get users for datatable
     * @param string $type - Either result or count
     * @return array for result or int for count
     */
    public function get_users($type = 'result')
    {
        
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $this->db->where('(name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR email LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR role LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')');
        }

        if($this->session->userdata('login_user')['role'] === ADMIN)
        {
            $columns = ['id', 'image', 'name', 'email', 'is_active', 'created_at', 'is_deleted','role',];
            $this->db->where(['id!=' => $this->session->userdata('login_user')['id'], 'role' => RESTAURANT,'is_deleted' => 0]);
        }
        else
        {
            $columns = ['id', 'image', 'name', 'email', 'is_active', 'created_at', 'is_deleted'];
            $this->db->where(['id!=' => $this->session->userdata('login_user')['id'],'restaurant_id' => $this->session->userdata('login_user')['id'], 'is_deleted' => 0]);
        }
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'result') {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_USERS);
            return $query->result_array();
        } else {
            $query = $this->db->get(TBL_USERS);
            return $query->num_rows();
        }
    }

    /**
     * Check email exist or not for unique email
     * @param string $email
     * @return array
     */
    public function check_unique_email($email,$id = null)
    {
        $this->db->where('email', $email);
        $this->db->where('is_deleted', 0);
        if(!empty($id))
        {
           $this->db->where('id !=', $id);
        }
        $query = $this->db->get(TBL_USERS);
        return $query->row_array();
    }

      /**
     * Check count total users of restaurant
     * @return array
     */
    public function count_users($id = null)
    {
        $this->db->where('email', $email);
        if(!empty($id))
        {
            $this->db->where('id !=', $id);
        }
        $this->db->where('is_deleted', 0);
        $query = $this->db->get(TBL_USERS);
        return $query->row_array();
    }

     /**
     * Check count total staffs of restaurant
     * @return array
     */
    public function count_staffs($id = null)
    {
        $this->db->where('email', $email);
        if(!empty($id))
        {
            $this->db->where('id !=', $id);
        }
        $this->db->where('is_deleted', 0);
        $query = $this->db->get(TBL_USERS);
        return $query->row_array();
    }

    /**
     * Check count total waiters of restaurant
     * @return array
     */
    public function count_waiters($id = null)
    {
        $this->db->where('email', $email);
        if(!empty($id))
        {
            $this->db->where('id !=', $id);
        }
        $this->db->where('is_deleted', 0);
        $query = $this->db->get(TBL_USERS);
        return $query->row_array();
    }
  /*   public function getitemsync($timestamp,$restaurant_id)
    {
        $check = $this->db->query("SELECT * FROM `menus` WHERE (`created_at` > '".$timestamp."' || `updated_at` > '".$timestamp."') and `restaurant_id` = '".$restaurant_id."'");
       //return $this->db->last_query();
        return $check->num_rows();
    }
      public function getstaffsync($timestamp,$restaurant_id)
    {
        $check = $this->db->query("SELECT * FROM `users` WHERE `role` = 'staff' and (`created_at` > '".$timestamp."' || `updated_at` > '".$timestamp."') and `restaurant_id` = '".$restaurant_id."'");
       
        return $check->num_rows();
    }
      public function getbackgroundsync($timestamp,$restaurant_id)
    {
        $check = $this->db->query("SELECT * FROM `settings` WHERE (`created_at` > '".$timestamp."' || `updated_at` > '".$timestamp."') and `user_id` = '".$restaurant_id."'");
       //return $this->db->last_query();
        return $check->num_rows();
    }*/
    
}
