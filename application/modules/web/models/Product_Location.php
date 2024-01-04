<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Product_Location extends CI_Model
{
	
public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function Product_location_entry($data)
    {
    	
    	if (!empty($data))
	   {
	      $this->db->insert('product_locations', $data);
	   }
    }
    public function retrieve_Location_editdata($product_id)
	{
		$this->db->select('*');
		$this->db->from('product_locations');
		$this->db->where('product_id', $product_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}
	
	public function delete_locations($attr_id) {
        // Define the table name where your product attributes are stored
        $table_name = 'product_locations';

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
    public function delete_product_locations($product_id) {
        // Define the table name where your product attributes are stored
        $table_name = 'product_locations';

        // Perform the deletion query
        $this->db->where('product_id', $product_id);
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
        SUM(CASE WHEN st.status = 1 THEN st.target_value ELSE 0 END) as total_completed_targets,
        SUM(CASE WHEN st.status = 0 THEN st.target_value ELSE 0 END) as total_pending_targets,
        SUM(st.target_value) as total_targets,
        (SUM(CASE WHEN st.status = 1 THEN st.target_value ELSE 0 END) / SUM(st.target_value)) * 100 as target_percentage');
    $this->db->from('store_set s');
    $this->db->join('store_targets st', 's.store_id = st.store_id', 'inner');
    $this->db->group_by('s.store_id, s.store_name');
    return $this->db->get()->result();
}
public function isProductAvailableForZipCode($productId, $city) {
    // Define your database table names for product availability and product information
    $availabilityTableName = 'product_locations';
    $productInfoTableName = 'product_information'; // Replace with your actual product information table name

    // Perform a database query to check product availability for the given zip code and get product information
    $this->db->select('p.is_perishable');
    $this->db->from($availabilityTableName . ' as a');
    $this->db->join($productInfoTableName . ' as p', 'a.product_id = p.product_id');
    $this->db->where_in('a.product_id', $productId);
	$this->db->where('p.is_perishable', 1);
    $this->db->where('a.city', $city);
    $query = $this->db->get();
	//echo $this->db->last_query();die;
    // Check if a matching record exists
	 //var_dump($query->num_rows());
		if ($query->num_rows() > 0) {
			
		  
			 return true; 
		} else {
			// Product is not available for the specified zip code
			return false;
		}
	}
	
	// Product_Location model
public function getPerishableStatusFromDB($productId) {
    // Adjust the method to directly fetch is_perishable from your database
    $this->db->select('is_perishable');
    $this->db->where('product_id', $productId);
    $result = $this->db->get('product_information')->row_array();

    return isset($result['is_perishable']) ? (bool) $result['is_perishable'] : false;
}

}

?>