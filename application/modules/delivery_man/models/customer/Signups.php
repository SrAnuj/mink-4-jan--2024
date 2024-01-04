<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Signups extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    //Insert user signup
    public function user_signup($data)
    {
        $CI =& get_instance();
        $CI->load->model('dashboard/Soft_settings');
        $sms_service = $CI->Soft_settings->retrieve_setting_editdata();
        if ($sms_service[0]['sms_service'] == 1) {
            $mobile = $data['customer_mobile'];
            $this->send_sms("Registration", $mobile);
        }
        $result = $this->db->insert('customer_information', $data);
        
        if ($result) {
            $this->db->select('*');
            $this->db->from('customer_information');
            $query = $this->db->get();
            foreach ($query->result() as $row) {
                $json_customer[] = array('label' => $row->customer_name. (!empty($row->customer_mobile)?' ('.$row->customer_mobile.')':''), 'value' => $row->customer_id);
            }
            $cache_file = './my-assets/js/admin_js/json/customer.json';
            $customerList = json_encode($json_customer);
            file_put_contents($cache_file, $customerList);
            return TRUE;
        }
        return false;
    }
    
    
       public function get_assigned_deliveries($id){
        // var_dump($id);
        // die();
        $this->db->select('a.*,b.name,c.delivery_zone,d.title');
        $this->db->from('delivery_assign a');
        $this->db->join('delivery_boy b','a.delivery_boy_id = b.id');
        $this->db->join('delivery_zone c','a.delivery_zone_id = c.id');
        $this->db->join('delivery_time_slot d','a.time_slot_id = d.id');
        // $this->db->limit($per_page, $page);
        $this->db->order_by('delivery_id','desc');
        $this->db->where('a.delivery_boy_id ' , $id);
        $result = $this->db->get()->result_array();
		
        return $result;
    }
	public function update_order_amount($orderID, $amountToSubtract) {
		
    // Check if the subtracted amount is not greater than the due_amount
    $this->db->select('due_amount,paid_amount');
    $this->db->from('subscription');
    $this->db->where('order_no', $orderID);
    $query = $this->db->get();
	
    $row = $query->row();
	 

    if ($row) {
        $dueAmount = $row->due_amount;
		 $paidAmount = $row->paid_amount;
        if ($amountToSubtract <= $dueAmount && $paidAmount !== $amountToSubtract) {
            // Subtract the specified amount from due_amount
            $this->db->set('due_amount', 'due_amount - ' . $amountToSubtract, false);
			$this->db->set('total_amount', 'total_amount - ' . $amountToSubtract, false);
            $this->db->where('order_no', $orderID);
            $this->db->update('subscription');

            // Transfer the subtracted amount to paid_amount
            $this->db->set('paid_amount', 'paid_amount + ' . $amountToSubtract, false);
            $this->db->where('order_no', $orderID);
            $this->db->update('subscription');

            return true; // Success
        } else {
            return false; // Amount to subtract is greater than due_amount
        }
    }

    return false; // Order not found or other error
}
 public function update_invoice_order($delivery_id){
    $this->db->select('a.*,b.*');
    $this->db->from('delivery_assign a');
    $this->db->join('delivery_orders b', 'a.delivery_id = b.delivery_id', 'inner');
    $this->db->where('a.delivery_id', $delivery_id);
    $result = $this->db->get()->result_array();
    return $result;
}

 public function transferDueToPaidAmount($invoice_id) {
        // Get the current due_amount for the specified invoice
        $this->db->select('due_amount');
        $this->db->where('order', $invoice_id);
        $query = $this->db->get('invoice');
        
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $due_amount = $row->due_amount;
            
            // Update the paid_amount with the current due_amount
            $this->db->set('paid_amount', 'paid_amount + ' . $due_amount, FALSE);
            $this->db->set('due_amount', 0);
			$this->db->set('invoice_status', 4);
            $this->db->where('order', $invoice_id);
            $this->db->update('invoice');
        }
    }
	public function get_order_due_amount($invoice)
    {
        $this->db->select('*');
        $this->db->from('invoice');
        $this->db->where('order',$invoice);
        $result = $this->db->get()->row_array();
        return $result;
    }
 public function get_assigned_delivery_info_by_id($delivery_id)
    {
        $this->db->select('*');
        $this->db->from('delivery_assign');
        $this->db->where('delivery_id',$delivery_id);
        $result = $this->db->get()->row_array();
        return $result;
    }
    
      public function get_assigned_delivery_order_info_by_id($delivery_id)
    {
        $this->db->select('order_no');
        $this->db->from('delivery_orders');
        $this->db->where('delivery_id',$delivery_id);
        $result = $this->db->get()->result();
        return $result;
    }
	public function assigned_sub_delivery_order_info($delivery_id)
    {
        $this->db->select('delivery_orders.order_no,subscription.total_amount as total_amount,subscription.paid_amount as paid_amount,subscription.due_amount as due_amount');
		$this->db->from('delivery_orders');
		$this->db->join('subscription', 'delivery_orders.order_no = subscription.order_no', 'inner');
		$this->db->where('delivery_id', $delivery_id);
		
		$result = $this->db->get()->result();
		return $result;

    }
     public function get_active_delivery_boy(){
        $this->db->select('id,name');
        $this->db->from('delivery_boy');
        $this->db->where('status',1);
        $this->db->order_by('id','desc');
        $result = $this->db->get()->result();
        return $result;
    }
    
      public function get_active_delivery_zone(){
        $this->db->select('id,delivery_zone');
        $this->db->from('delivery_zone');
        $this->db->where('status',1);
        $this->db->order_by('id','desc');
        $result = $this->db->get()->result();
        return $result;
    }
    
      public function get_active_time_slots(){
        $this->db->select('*');
        $this->db->from('delivery_time_slot');
        $this->db->where('status',1);
        $this->db->order_by('id','desc');
        $result = $this->db->get()->result();
        return $result;
    }
    
     public function get_pending_orders(){
        $this->db->select('a.*,b.*');
        $this->db->from('order a');
        $this->db->join('invoice b','a.order_id = b.order_id','inner');
        $this->db->where_in('b.invoice_status',array('0','1','3','5'));
        $result = $this->db->get()->result();
        return $result;
    }
    
      public function assigned_delivery_delete($delivery_id){
        $this->db->where('delivery_id', $delivery_id);
        $this->db->delete('delivery_assign');
        $this->session->set_userdata(array('message' => display('successfully_delete')));
        redirect('delivery_man/customer/Delivery_dashboard/manage_assigned_delivery');
    }
    
    public 	function check_valid_delivery_man($username, $password)
	{

// var_dump($username);
// var_dump($password);
// die();
		$fullpassword = md5("gef" . $password);
		$this->db->where(array('email' => $username, 'password' => $fullpassword, 'status' => 1));
		$query = $this->db->get('delivery_boy');
		$result =  $query->result_array();

		if (count($result) == 1) {
			$user_id = $result[0]['id'];
                $this->session->set_userdata('id' , $user_id);

			return $query->result_array();
		}
		return false;
	}
	
	public function get_delivery_boy_info_by_id($delivery_boy){
		$this->db->select('*');
    	$this->db->from('delivery_boy');
    	$this->db->where('id',$delivery_boy);
    	$result = $this->db->get()->row_array();
    	return $result;
	}
	
	
public function	total_delivery_man_order($delivery_man_id)
{
    	$this->db->select('*');
    	$this->db->from('delivery_assign');
    	$this->db->where('delivery_boy_id',$delivery_man_id);
    	$result = $this->db->get()->result_array();
    	return count($result);
}
//send sms
    public function send_sms($type, $mobile)
    {
        $CI =& get_instance();
        $CI->load->model('Soft_settings');
        $gateway = $CI->Soft_settings->retrieve_active_getway();

        $sms_template = $CI->db->select('*')->from('sms_template')->where('type', $type)->get()->row();
        $sms = $CI->db->select('*')->from('sms_configuration')->where('status', 1)->get()->row();


        if (1 == $gateway->id) {
            /****************************
             * SMSRank Gateway Setup
             ****************************/

            $message = $sms_template->message;
            $url = "http://api.smsrank.com/sms/1/text/singles";
            $username = $sms->user_name;
            $password = base64_encode($sms->password);
            $message = base64_encode($message);
            $recipients = $mobile;

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, "$url?username=$username&password=$password&to=$recipients&text=$message");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
            curl_setopt($curl, CURLOPT_USERAGENT, $agent);
            $output = json_decode(curl_exec($curl), true);
            return true;
            curl_close($curl);

        }

        if (2 == $gateway->id) {
            /****************************
             * nexmo Gateway Setup
             ****************************/
            $api = $sms->user_name;
            $secret_key = $sms->password;
            $message = $sms_template->message;
            $from = $sms->sms_from;


            $data = array(
                'from' => $from,
                'text' => $message,
                'to' => $mobile
            );

            require_once APPPATH . 'libraries/nexmo/vendor/autoload.php';

            $basic = new \Nexmo\Client\Credentials\Basic($api, $secret_key);
            $client = new \Nexmo\Client($basic);

            $message = $client->message()->send($data);

            if (!$message) {
                return json_encode(array(
                    'status' => false,
                    'message' => 'Curl error: '
                ));
            } else {
                return json_encode(array(
                    'status' => true,
                    'message' => "success: "
                ));
            }


        }

        if (3 == $gateway->id) {
            /****************************
             * budgetsms Gateway Setup
             ****************************/
            $message = $sms_template->message;
            $from = $sms->sms_from;
            $userid = $sms->userid;
            $username = $sms->user_name;
            $handle = $sms->password;

            $data = array(
                'handle' => $handle,
                'username' => $username,
                'userid' => $userid,
                'from' => $from,
                'msg' => $message,
                'to' => $mobile
            );

            $url = "https://api.budgetsms.net/sendsms/?";
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($curl);

            if (curl_errno($curl)) {
                return json_encode(array(
                    'status' => false,
                    'message' => 'Curl error: ' . curl_error($curl)
                ));
            } else {
                return json_encode(array(
                    'status' => true,
                    'message' => "success: " . $response
                ));
            }

            curl_close($curl);

        }

    }


    //Patent Category List
    public function parent_category_list()
    {
        $this->db->select('*');
        $this->db->from('product_category');
        $this->db->where('cat_type', 1);
        $this->db->where('status', 1);
        $this->db->order_by('menu_pos');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    //Category list
    public function category_list()
    {
        $this->db->select('*');
        $this->db->from('product_category');
        $this->db->order_by('category_name', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //Best sales list
    public function best_sales()
    {
        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->where('best_sale', '1');
        $this->db->limit('6');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    //Footer block
    public function footer_block()
    {
        $this->db->select('*');
        $this->db->from('web_footer');
        $this->db->order_by('position');
        $this->db->limit('4');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }
}