<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Corder extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        //$this->user_auth->check_customer_auth();
        $this->load->library('web/customer/Lorder');
        $this->load->model([
            'web/customer/Orders',
            'dashboard/Soft_settings'
        ]);

        $this->load->library('occational');
    }

    //Index page load first
    public function index()
    {
        $content = $this->lorder->order_add_form();
        $this->template_lib->full_customer_html_view($content);
    }
  public function new_subscription()
    {     $data = array(
            'title' => display('add_subscription'),
        );

        $data['Soft_settings'] = $this->Soft_settings->retrieve_setting_editdata();
        $content = $this->lorder->subscription_add_form();
        $this->template_lib->full_customer_html_view($content);
    }
    //Add new order
    public function new_order()
    {
        $data = array(
            'title' => display('new_order'),
        );

        $data['Soft_settings'] = $this->Soft_settings->retrieve_setting_editdata();
        $data['module'] = "web";
        $data['page'] = "customer/order/add_order_form";
        $this->parser->parse('customer/customer_html_template', $data);

    }

    //Insert order
    public function insert_order()
    {
        // $customer_id = $this->input->post('customer_id',TRUE);
        $customer_id = $this->session->userdata('customerId');
        $order_id = $this->Orders->order_entry();
		//var_dump($order_id);die;
        $order = $this->db->select('*')->from('order')->where('order_id', $order_id)->get()
		->row();
        // $reward_id = $this->generator(8);
        $reward_id=$this->auth->generator(8);

        $reward_a = $order->due_amount;
        $a = $reward_a * 0.2;
        // var_dump($a);
        // die(); 
        $reward = array (
            'id'          =>$reward_id,
            'customer_id' => $customer_id,
            'amount' => $a,
            'date'   => date("Y-m-d H:i:s"),

        );

       
        $result = $this->Orders->reward_entry($reward); 
        // var_dump($result);
        // die();
        redirect(base_url('web/customer/Corder/manage_order'));
    }

// Product subscription insert 

 public function insert_subscription()
    {
        
        $customer_id = $this->session->userdata('customerId');
		
        $insert_subscription_id = $this->Orders->subscription_entry();
        // $order = $this->db->select('*')->from('order')->where('order_id', $order_id)->get()->row();
        // // $reward_id = $this->generator(8);
        // $reward_id=$this->auth->generator(8);

        // $reward_a = $order->due_amount;
        // $a = $reward_a * 0.2;
        // // var_dump($a);
        // // die(); 
        // $reward = array (
        //     'id'          =>$reward_id,
        //     'customer_id' => $customer_id,
        //     'amount' => $a,
        //     'date'   => date("Y-m-d H:i:s"),

        // );

       
        // $result = $this->Orders->reward_entry($reward); 
        // var_dump($result);
        // die();
        redirect(base_url('web/customer/Corder/manage_subscription'));
    }
    
    
     public function vacational_mode()
    {
		$session = $this->load->library('session');
	
		//$c_email = $this->session->userdata('customer_email');
		//d($c_email);die;
        $startDate = $this->input->post('startDate',TRUE);
        $endDate = $this->input->post('endDate',TRUE);
        $vacation_id = $this->input->post('vacation_id',TRUE);
		$startDateObj = new DateTime($startDate);
		$endDateObj = new DateTime($endDate);

		// Calculate the difference between the dates
		$interval  = $startDateObj->diff($endDateObj);
		$datediff = $interval->days;
		//var_dump($datediff);
		
		$leave_start = DateTime::createFromFormat('Y-m-d', $startDate);
		$leave_end = DateTime::createFromFormat('Y-m-d', $endDate);
		$diffDays = $leave_end->diff($leave_start)->format("%a");
		//var_dump($diffDays);die;
//       $no_of_days = $datediff->format("%a")+1;
        $record = $this->db->select('*')->from('subscription')->where('id', $vacation_id)->get()->row();
         //d($record);die;
		 $cgst= $record->total_cgst;
		 $sgst= $record->total_sgst;
		 $igst= $record->total_igst;
		 $plan= $record->plan;
		$total_amount = $record->total_amount;
		$t_sgst = $sgst / $plan;
        $t_cgst = $cgst / $plan;
        $t_igst = $igst / $plan;
        $t_amt = ($total_amount / $plan);
		$grand_total_amount = $total_amount - ($t_amt*$diffDays);
		$up_cgst = $cgst - ($diffDays*$t_cgst);
		$up_sgst = $sgst - ($diffDays*$t_sgst);
		$up_igst = $igst - ($diffDays*$t_igst);
		// $grand_total_amount = $updated_amt + $up_cgst + $up_sgst + $up_igst;
        //var_dump($total_amount);
        //var_dump($up_cgst);
        // var_dump($startDate);
        //var_dump($up_sgst);
        //var_dump($grand_total_amount);
        //var_dump($diffDays);
		$data = [
            'total_cgst' => $up_cgst,
            'total_sgst' => $up_sgst,
            'total_igst' => $up_igst,
            'total_amount' => $grand_total_amount,
            'due_amount' => $grand_total_amount,
			'status' => 0,
			'start_date' => $startDate,
			'end_date' => $endDate,
			'total_days' => $datediff,
			
        ];
         $this->db->where('id', $vacation_id);
        $this->db->update('subscription', $data);
		
		//Sending email notificartion to user and delivery boy,admin
        $this->load->library('email');
		$this->load->config('email');
		$admin_data = get_user_by_user_type(1);
		$this->load->model('dashboard/Customer_dashboards');
		$get_delivery_boy = $this->Customer_dashboards->get_ordered_assign_delivery_boy($record->order_no);
		
		$edit_data = $this->Customer_dashboards->profile_edit_data();
		
		$from = $this->config->item('smtp_user');
		$recipient_emails = array(
			$admin_data->username,
			$this->session->userdata('customer_email')
			
		);
		$json = array();
		$response = array();
		
		$subject = 'Notification: Entering Vocational Mode';
		$CI =& get_instance();
		$CI->load->model('dashboard/Soft_settings');
		$Soft_settings = $CI->Soft_settings->retrieve_setting_editdata();
		$data = array('admin_first_name' => $admin_data->first_name, 'admin_last_name' => $admin_data->last_name,'leave_start' => $startDate,'end_date' => $endDate,'total_days' => $datediff,'first_name' => $edit_data->first_name,
            'last_name' => $edit_data->last_name,
            'email' => $edit_data->customer_email,
            'image' => base_url() . $edit_data->image,
            'customer_short_address' => $edit_data->customer_short_address,
            'customer_address_1' => $edit_data->customer_address_1,
            'customer_address_2' => $edit_data->customer_address_2,
            'city' => $edit_data->city,
            'state' => $edit_data->state,
            'state_name' => $edit_data->state,
            'country' => $edit_data->country,
            'country_id' => $edit_data->country,
            'zip' => $edit_data->zip,
            'customer_mobile' => $edit_data->customer_mobile,
            'company' => $edit_data->company,
			'web_url'=> base_url(),
			'web_logo'=>base_url().(!empty($Soft_settings[0]['logo'])?$Soft_settings[0]['logo']:'assets/img/icons/default.jpg'),
			'order_id' => $record->order_no);
			
			$d_data = array('admin_first_name' => $get_delivery_boy[0]['name'] ?? 'Delivery', 'admin_last_name' => $get_delivery_boy[0]['mobile'] ?? 'Boy','leave_start' => $startDate,'end_date' => $endDate,'total_days' => $datediff,'first_name' => $edit_data->first_name,
            'last_name' => $edit_data->last_name,
            'email' => $edit_data->customer_email,
            'image' => base_url() . $edit_data->image,
            'customer_short_address' => $edit_data->customer_short_address,
            'customer_address_1' => $edit_data->customer_address_1,
            'customer_address_2' => $edit_data->customer_address_2,
            'city' => $edit_data->city,
            'state' => $edit_data->state,
            'state_name' => $edit_data->state,
            'country' => $edit_data->country,
            'country_id' => $edit_data->country,
            'zip' => $edit_data->zip,
            'customer_mobile' => $edit_data->customer_mobile,
            'company' => $edit_data->company,
			'web_url'=> base_url(),
			'web_logo'=>base_url().(!empty($Soft_settings[0]['logo'])?$Soft_settings[0]['logo']:'assets/img/icons/default.jpg'),
			'order_id' => $record->order_no);
			
			$message = $this->load->view('customer_vocational_mode', $data, TRUE);
			$message1 = $this->load->view('delivery_boy_vocational_mode', $d_data, TRUE);
			
			//$get_delivery_boy[0]['email'] = 'ram.anuj.169@gmail.com';
			if (empty($get_delivery_boy[0]['email'])) {
				$json['d_mail_status'] = 202;
				$json['message'] = 'Delivery E-mail address is not found, so email was not sent.';
				$json['d_data'] = 'Email address is empty.<br>';
			} else {
				$delivery_boy = $get_delivery_boy[0]['email'];
				$message1 = $this->load->view('delivery_boy_vocational_mode', $d_data, TRUE);
				$this->email->from($from);
				$this->email->to($delivery_boy);
				$this->email->subject($subject);
				// Use the same email template for each recipient
				$this->email->message($message1);
				
				// Try to send the email
				if ($this->email->send()) {
					$json['d_mail_status'] = 200;
					$json['message'] = 'E-mail sent to delivery boy';
					$json['d_data'] = 'Email sent to ' . $delivery_boy . '<br>';
				} else {
					$json['d_mail_status'] = 201;
					$json['message'] = 'E-mail failed to send to delivery boy';
					$json['d_data'] =  'Email sending failed to ' . $delivery_boy . ': ' . $this->email->print_debugger() . '<br>';
				}
			}

		foreach ($recipient_emails as $recipient_email) {
			$this->email->clear(); // Clear email settings for the next recipient
			$this->email->from($from);
			$this->email->to($recipient_email);
			$this->email->subject($subject);
			// Use the same email template for each recipient
			$this->email->message($message);
			// Send the email
			if ($this->email->send()) {
				$response['status'] = 200;
				$response['message'] = 'E-mail sent to owner and you';
				$response['data'][] = 'Email sent to ' . $recipient_email . '<br>';
			} else {
				$response['data'][] =  'Email sending failed to ' . $recipient_email . ': ' . $this->email->print_debugger() . '<br>';
			}
		}
		$json['recipient_emails'] = $response;

		// Send the merged JSON response
		echo json_encode($json);
    }
    
    
    
    //Retrive right now inserted data to cretae html
    public function order_inserted_data($order_id)
    {


        $order_detail = $this->Orders->retrieve_order_html_data($order_id);

        $subTotal_quantity = 0;
        $subTotal_cartoon = 0;
        $subTotal_discount = 0;

        if (!empty($order_detail)) {
            foreach ($order_detail as $k => $v) {
                $order_detail[$k]['final_date'] = $this->occational->dateConvert($order_detail[$k]['date']);
                $subTotal_quantity = $subTotal_quantity + $order_detail[$k]['quantity'];
            }
            $i = 0;
            foreach ($order_detail as $k => $v) {
                $i++;
                $order_detail[$k]['sl'] = $i;
            }
        }

        $currency_details = $this->Soft_settings->retrieve_currency_info();
        $company_info = $this->Orders->retrieve_company();
        $data = array(
            'title' => display('order_details'),
            'order_id' => $order_detail[0]['order_id'],
            'order_no' => $order_detail[0]['order'],
            'customer_name' => $order_detail[0]['customer_name'],
            'customer_mobile' => $order_detail[0]['customer_mobile'],
            'customer_email' => $order_detail[0]['customer_email'],
            'customer_address' => $order_detail[0]['customer_short_address'],
            'final_date' => $order_detail[0]['final_date'],
            'total_amount' => $order_detail[0]['total_amount'],
            'order_discount' => $order_detail[0]['order_discount'],
            'paid_amount' => $order_detail[0]['paid_amount'],
            'due_amount' => $order_detail[0]['due_amount'],
            'details' => $order_detail[0]['details'],
            'subTotal_quantity' => $subTotal_quantity,
            'order_all_data' => $order_detail,
            'company_info' => $company_info,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
        );


        $data['Soft_settings'] = $this->Soft_settings->retrieve_setting_editdata();
        $data['module'] = "web";
        $data['page'] = "customer/order/order_pdf";
        $chapterList = $this->parser->parse('customer/customer_html_template', $data, true);

        $this->load->library('pdfgenerator');
        $file_path = $this->pdfgenerator->generate_order($order_id, $chapterList);

        //File path save to database
        $this->db->set('file_path', base_url($file_path));
        $this->db->where('order_id', $order_id);
        $this->db->update('order');

        $send_email = '';
        if (!empty($data['customer_email'])) {
            $send_email = $this->setmail($data['customer_email'], $file_path);
        }

        if ($send_email != null) {
            return true;
        } else {
            return false;
        }


    }


    //Send Customer Email with invoice
    public function setmail($email, $file_path)
    {


        $setting_detail = $this->Soft_settings->retrieve_email_editdata();

        $subject = display("order_information");
        $message = display("order_info_details") . '<br>' . base_url();

        $config = array(
            'protocol' => $setting_detail[0]['protocol'],
            'smtp_host' => $setting_detail[0]['smtp_host'],
            'smtp_port' => $setting_detail[0]['smtp_port'],
            'smtp_user' => $setting_detail[0]['sender_email'],
            'smtp_pass' => $setting_detail[0]['password'],
            'mailtype' => $setting_detail[0]['mailtype'],
            'charset' => 'utf-8'
        );

        $this->load->library('email');
        $this->email->initialize($config);

        $this->email->set_newline("\r\n");
        $this->email->from($setting_detail[0]['sender_email']);
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->attach($file_path);

        $check_email = $this->test_input($email);
        if (filter_var($check_email, FILTER_VALIDATE_EMAIL)) {
            if ($this->email->send()) {
                $this->session->set_userdata(array('message' => display('email_send_to_customer')));
                return true;
            } else {
                $this->session->set_userdata(array('error_message' => display('email_not_send')));
                redirect(base_url('web/customer/Corder/manage_order'));
            }
        } else {
            $this->session->set_userdata(array('message' => display('successfully_added')));
            return true;
        }
    }

//Email testing for email
    public function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Retrive product data
    public function retrieve_product_data()
    {
        $product_id = $this->input->post('product_id',TRUE);
        $product_info = $this->Orders->get_total_product($product_id);
        echo json_encode($product_info);
    }

    // Get variant price and stock
    public function check_customer_2d_variant_info()
    {
        $product_id = $this->input->post('product_id',TRUE);
        $store_id = $this->input->post('store_id',TRUE);
        $variant_id = $this->input->post('variant_id',TRUE);
        $variant_color = $this->input->post('variant_color',TRUE);

        $stock = $this->Orders->check_variant_wise_stock($product_id, $store_id, $variant_id, $variant_color);

        if ($stock > 0) {
            $result[0] = "yes";
            $price = $this->Orders->check_variant_wise_price($product_id, $variant_id, $variant_color);

            $result[1] = $stock; //stock
            $result[2] = floatval($price['price']); //price
            $result[3] = 0; //discount

        } else {
            $result[0] = 'no';
        }
        echo json_encode($result);
    }


    //Stock available check
    public function available_stock()
    {

        $product_id = $this->input->post('product_id',TRUE);
        $variant_id = $this->input->post('variant_id',TRUE);
        $store_id = $this->input->post('store_id',TRUE);

        $this->db->select('SUM(a.quantity) as total_purchase');
        $this->db->from('product_purchase_details a');
        $this->db->where('a.product_id', $product_id);
        $this->db->where('a.variant_id', $variant_id);
        $this->db->where('a.store_id', $store_id);
        $total_purchase = $this->db->get()->row();

        $this->db->select('SUM(b.quantity) as total_sale');
        $this->db->from('invoice_stock_tbl b');
        $this->db->where('b.product_id', $product_id);
        $this->db->where('b.variant_id', $variant_id);
        $this->db->where('b.store_id', $store_id);
        $total_sale = $this->db->get()->row();

        echo $total_purchase->total_purchase - $total_sale->total_sale;
    }

    //Manage order
    public function manage_order()
    {
        $content = $this->lorder->order_list();
        $this->template_lib->full_customer_html_view($content);
    }
      public function manage_subscription()
    {
        $content = $this->lorder->subscription_list();
        $this->template_lib->full_customer_html_view($content);
    }

}