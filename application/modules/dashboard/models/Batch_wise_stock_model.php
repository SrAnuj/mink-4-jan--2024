<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Batch_wise_stock_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	public function batch_wise_product($filter=null)
	{
	// Query for product_purchase_details
		$this->db->select('a.*, b.product_name, b.price, b.supplier_price, a.batch_no AS stock_batch_no');
		$this->db->from('product_purchase_details a');
		$this->db->join('product_information b', 'b.product_id = a.product_id', 'left');
		if (!empty($filter['product_id'])) {
			$this->db->where('a.product_id', $filter['product_id']);
		}
		if (!empty($filter['batch_no'])) {
			$this->db->where('a.batch_no', $filter['batch_no']);
		}
		$query1 = $this->db->get();

		// Query for transfer
		$this->db->select('a.*, b.product_name, b.price, b.supplier_price, a.batch_no AS stock_batch_no');
		$this->db->from('transfer a');
		$this->db->join('product_information b', 'b.product_id = a.product_id', 'left');
		if (!empty($filter['product_id'])) {
			$this->db->where('a.product_id', $filter['product_id']);
		}
		if (!empty($filter['batch_no'])) {
			$this->db->where('a.batch_no', $filter['batch_no']);
		}
		$this->db->where('a.batch_no IS NOT NULL', NULL, FALSE);
		$this->db->group_by('a.batch_no');
		$this->db->order_by('a.batch_no', 'desc');
		$query2 = $this->db->get();

		// Merge the results
		$result1 = $query1->result_array();
		$result2 = $query2->result_array();

		// Combine the results
		$mergedResult = array_merge($result1, $result2);

		// You may want to remove duplicates based on a unique identifier
		// For example, assuming 'product_id' is unique, you can use array_column
		$uniqueMergedResult = array_column($mergedResult, null, 'product_id');

		return array_values($uniqueMergedResult);

	}
public function psbatch_wise_product002($filter=null)
	{
		$this->db->select('a.*,b.product_name,b.price,b.supplier_price');
		$this->db->from('product_purchase_details a');
		$this->db->join('purchase_stock_tbl e', 'e.product_id = a.product_id', 'left');
		if (!empty($filter['product_id'])) {
    $this->db->where('(a.product_id = ' . $filter['product_id'] . ' OR e.product_id = ' . $filter['product_id'] . ')');
}

if (!empty($filter['batch_no'])) {
    $batch_no = $this->db->escape_str($filter['batch_no']);
    $this->db->group_start();
    $this->db->where('a.batch_no', $batch_no);
    $this->db->group_end();
}
		$this->db->join('product_information b','b.product_id = a.product_id');
		$this->db->where('a.batch_no is NOT NULL', NULL, FALSE);
		$this->db->group_by('a.batch_no');
		$this->db->order_by('a.batch_no','desc');
		$product = $this->db->get();
		if($product->num_rows()){
			return $product->result_array();
		}
		return false;
	}
	public function batch_wise_invoice_details($batch_no){
		$this->db->select('quantity AS total_sale');
		$this->db->from('invoice_details');
		$this->db->where('batch_no',$batch_no);
		$invoice_details = $this->db->get();
		if ($invoice_details->num_rows()>0) {
			
			$invoice_details->result_array();
			return $invoice_details->result_array();
			
		}
		return array(array('total_sale'=>0));
	}
}