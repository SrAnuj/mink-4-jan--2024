<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserTarget extends CI_Model {
  
    public function __construct(){
          parent::__construct();
        $this->auth->check_user_auth();
        
        date_default_timezone_set(DEF_TIMEZONE);
        $this->todays_date = date("m-d-Y");


    }

  public function getSalesManagers() {
        $this->db->select('user_login.*');
        $this->db->from('user_login');
        $this->db->join('sec_role_tbl', 'user_login.user_type = sec_role_tbl.role_id');
        $this->db->where('sec_role_tbl.role_name', 'Sales Manager');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return array(); // No users found with the specified role
        }
    }
    public function getSalesExecutive() {
       // print_r($this->db->last_query());    

        $this->db->select('user_login.*');
        $this->db->from('user_login');
        $this->db->join('sec_role_tbl', 'user_login.user_type = sec_role_tbl.role_id');
        $this->db->where('sec_role_tbl.role_name', 'sales executive');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return array(); // No users found with the specified role
        }
    }
  
}