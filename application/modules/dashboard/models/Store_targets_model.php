<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Store_targets_model extends CI_Model
{
	
public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function Store_target_entry($data)
    {
    	
    	if (!empty($data))
	   {
	      $this->db->insert('store_targets', $data);
	   }
    }
    public function retrieve_store_editdata($store_id)
	{
		$this->db->select('*');
		$this->db->from('store_targets');
		$this->db->where('store_id', $store_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}
	
	public function delete_product_attribute($attr_id) {
        // Define the table name where your product attributes are stored
        $table_name = 'store_targets';

        // Perform the deletion query
        $this->db->where('id', $attr_id);
        $this->db->delete($table_name);

        // Check if the deletion was successful
        if ($this->db->affected_rows() > 0) {
            return true; // Deletion successful
        } else {
            return false; // Deletion failed
        }
    }
    public function delete_store_target($store_id) {
        // Define the table name where your product attributes are stored
        $table_name = 'store_targets';

        // Perform the deletion query
        $this->db->where('id', $store_id);
        $this->db->delete($table_name);

        // Check if the deletion was successful
        if ($this->db->affected_rows() > 0) {
            return true; // Deletion successful
        } else {
            return false; // Deletion failed
        }
    }
// Model method to get data for all stores
// Model method to get data for all stores with grouping by store_id
public function getAllStores()
{
   $this->db->select('s.store_id, s.store_name, 
    st.target_value as total_targets,
    st.target_achieved as total_completed_targets,
    (st.target_value - st.target_achieved) as total_pending_targets,
    ((st.target_achieved / st.target_value) * 100) as target_percentage');
$this->db->from('store_set s');
$this->db->join('store_targets st', 's.store_id = st.store_id', 'inner');
$this->db->join('daily_target_achievements dta', 'dta.store_id = s.store_id', 'inner');
//$this->db->where('st.status', 1); // Filter for completed targets
$this->db->group_by('s.store_id'); // Group by store_id

 $query = $this->db->get()->result();

return $query;
}
public function getStoreTargetReport()
{
   $this->db->select('s.store_id, s.store_name, st.target_value, dta.daily_target_value,dta.target_date,dta.occupied_targets,dta.daily_target_value');
$this->db->from('store_set s');
$this->db->join('store_targets st', 's.store_id = st.store_id', 'inner');
$this->db->join('daily_target_achievements dta', 'dta.store_id = s.store_id', 'inner');
$this->db->where('dta.target_date', date('Y-m-d')); // Filter for completed targets
$this->db->select('(COALESCE(dta.daily_target_value, 0) - COALESCE(dta.occupied_targets, 0)) AS target_pending', FALSE);

// Calculate target achieved percentage
$this->db->select('(COALESCE(dta.occupied_targets, 0) / COALESCE(dta.daily_target_value, 1)) * 100 AS target_achieved_percentage', FALSE);

return $this->db->get()->result();




}


 public function update_target($store_id, $data) {
        // Update the target data in the database
        $this->db->where('store_id', $store_id);
        $this->db->update('store_targets', $data);
       
    }
}
?>