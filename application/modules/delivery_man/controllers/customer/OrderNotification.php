<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class OrderNotification extends CI_Controller
{
   private $CI;
    public function __construct(){
        parent::__construct();
        $this->CI   =& get_instance();
        $this->CI->load->model('delivery_man/Notification');
        $this->CI->load->model('dashboard/Soft_settings');
    }
    public function system_auto_calculate(){
		
		//$this->load->model('delivery_man/Notification');
		 $this->CI->load->library('email');
		 $this->CI->load->config('email');
        $orders = $this->CI->Notification->sentEmailNotification();
		
		$Soft_settings = $this->CI->Soft_settings->retrieve_setting_editdata();
		$from = $this->CI->config->item('smtp_user');
		$admin_data = get_user_by_user_type(1);
		$subject = 'Notification: Daily Subscription';
        if(is_array($orders)){
           foreach($orders as $key => $order){
                $this->CI->email->clear();
				$planStart = $order['from_date'];
				$planEnd = $order['to_date'];
				$currentDate = date('Y-m-d'); 
				if (strtotime($currentDate) >= strtotime($planStart) && strtotime($currentDate) <= strtotime($planEnd)){ 

				  $data = array('delivery_boy_name' => $order['name'] ?? 'Delivery', 'delivery_boy_last_name' => $order['mobile'] ?? 'Boy','start_date' => $order['from_date'],'end_date' => $order['to_date'],'total_days' => $order['plan'],'first_name' => $order['name'],'last_name' => $order['last_name'],
					'email' => $order['customer_email'],
					'image' => base_url() . $order['image'],
					'customer_name' => $order['customer_name'],
					'customer_short_address' => $order['customer_short_address'],
					'customer_address_1' => $order['customer_address_1'],
					'customer_address_2' => $order['customer_address_2'],
					'city' => $order['city'],
					'state' => $order['state'],
					'state_name' => $order['state'],
					'country' => $order['country'],
					'country_id' => $order['country'],
					'zip' => $order['zip'],
					'customer_mobile' => $order['customer_mobile'],
					'company' => $order['company'],
					'web_url'=> base_url(),
					'web_logo'=>base_url().(!empty($Soft_settings[0]['logo'])?$Soft_settings[0]['logo']:'assets/img/icons/default.jpg'),
					'order_id' => $order['order_no'],
					'product_id' => $order['product_id'],
					'image_thumb' => base_url() . $order['image_thumb'],
					'product_model' => $order['product_model'],
					'product_name' => $order['product_name'],
					'price' => $order['price'],
					'description' => $order['description'],
					'product_details' => $order['product_details'],
					'sincerly' => $admin_data->first_name.' '.$admin_data->last_name,
					
					);

					$this->CI->email->from($from);
					$this->CI->email->to('ram.anuj.169@gmail.com');
					$this->CI->email->subject($subject);
					$body   = $this->CI->load->view('order_notification',$data,TRUE);
					$this->CI->email->message($body);  
						if($this->CI->email->send()){
							 echo 'Email sent successfully';
							//set email status to send
							//$this->CI->Admin_db->reset_user_email_status($reciever);
						}
						else{
								show_error($this->email->print_debugger());
								log_message($this->email->print_debugger());

						}
					}else{
							 $customerInfo = $this->CI->Notification->getCustomerByEmail($order['customer_email']);
							 if ($customerInfo && strtotime($currentDate) > strtotime($planEnd)) {
							 $this->email->clear();

							$from = $this->config->item('smtp_user');
							$subject = 'Plan Renewal Notification';
							$data = array(
								'customer_name' => $customerInfo['customer_name'],
								'product_id' => $order['product_id'],
								'image_thumb' => base_url() . $order['image_thumb'],
								'product_model' => $order['product_model'],
								'product_name' => $order['product_name'],
								'price' => $order['price'],
								'description' => $order['description'],
								'product_details' => $order['product_details'],
								'sincerly' => $admin_data->first_name.' '.$admin_data->last_name,
								'first_name' => $customerInfo['first_name'],
								'total_days' => $order['plan'],
								'start_date' => $order['from_date'],'end_date' => $order['to_date'],
								'last_name' => $customerInfo['last_name'],
								'customer_mobile' => $order['customer_mobile'],
								'web_logo'=>base_url().(!empty($Soft_settings[0]['logo'])?$Soft_settings[0]['logo']:'assets/img/icons/default.jpg'),
							);

							$this->email->from($from);
							$this->email->to($order['customer_email']);
							$this->email->subject($subject);
							$body = $this->load->view('renewal_notification', $data, TRUE); // Create a view for renewal notification
							$this->email->message($body);

								if ($this->email->send()) {
									// Mark the plan as renewed in the database
									//$this->Customer_model->markPlanRenewed($customerInfo['customer_id']);
									echo 'Renewal notification email sent successfully.';
								} else {
									show_error($this->email->print_debugger());
									log_message($this->email->print_debugger());

								}
							 }
						}
				}	 
           }
       }
    }

















?>