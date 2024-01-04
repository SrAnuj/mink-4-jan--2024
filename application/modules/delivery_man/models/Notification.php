<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends CI_Model
{
   
	public function sentEmailNotification()
	{
		$this->db->select("db.*, s.*, ps.*,cs.*");
		$this->db->from('delivery_boy db');
		$this->db->join('delivery_orders do', 'do.delivery_id = db.id', 'inner');
		$this->db->join('subscription s', 's.order_no = do.order_no', 'inner');
		$this->db->join('customer_information cs', 'cs.customer_id = s.customer_id', 'inner');
		$this->db->join('product_information ps', 'ps.product_id = s.product_id', 'inner'); // Add this join


		// You can add WHERE conditions as needed
		// $this->db->where('do.order_status', 'delivered');

		$this->db->order_by('s.created_at', 'asc');

		$query = $this->db->get();
		//print_r($this->db->last_query());    
			
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}else{
			return false;
		}
		
		

	}
	public function getCustomerByEmail($customerEmail) {
    $this->db->where('customer_email', $customerEmail);
    $query = $this->db->get('customer_information');

    if ($query->num_rows() > 0) {
        return $query->row_array(); // Assuming you want to return the first matching row
    } else {
        return false; // No matching records found
    }
	
}

    
}