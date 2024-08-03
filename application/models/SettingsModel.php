<?php

/**
 * Manage users table related database operation
 * @author sm
 */
class SettingsModel extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return settings detail
     * @param string/array $where
     * @param string/array $select
     * @return array
     */
    public function get_settings_detail($where, $select = '*')
    {
        $this->db->select($select);
        $this->db->where($where);
        return $this->db->get(TBL_SETTINGS)->row_array();
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
        $columns = ['id', 'image', 'name', 'email', 'is_active', '', '', '', 'created_at', 'is_deleted'];
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $this->db->where('(name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR email LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')');
        }
        $this->db->where(['id!=' => $this->session->userdata('login_user')['id'], 'is_deleted' => 0]);
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
    public function check_unique_email($email, $id = null)
    {
        $this->db->where('email', $email);
        $this->db->where('is_deleted', 0);
        if (!empty($id)) {
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
        if (!empty($id)) {
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
        if (!empty($id)) {
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
        if (!empty($id)) {
            $this->db->where('id !=', $id);
        }
        $this->db->where('is_deleted', 0);
        $query = $this->db->get(TBL_USERS);
        return $query->row_array();
    }

}
