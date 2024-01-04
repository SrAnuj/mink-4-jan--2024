<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Delivery_dashboard extends MX_Controller
{

 function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard/Soft_settings');
        $this->load->model('dashboard/Customers');
        $this->load->model('dashboard/Products');
        $this->load->model('dashboard/Product_reviews');
        $this->load->model('dashboard/Categories');
        $this->load->model('dashboard/Suppliers');
        $this->load->model('dashboard/Retailers');
        $this->load->model('dashboard/Invoices');
        $this->load->model('dashboard/Purchases');
        $this->load->model('dashboard/Reports');
        $this->load->model('dashboard/Accounts');
        $this->load->model('dashboard/Users');
        $this->load->model('dashboard/Stores');
        $this->load->model('dashboard/Search_history');
        $this->load->model('dashboard/Customer_activities');
        $this->load->model('dashboard/Orders');
        $this->load->model('dashboard/Web_settings');
        $this->load->model('template/Template_model');
        $this->load->library('dashboard/lreport');
        $this->load->library('dashboard/occational');
        $this->load->library('dashboard/luser');
        $this->load->library('delivery_man/customer/lsignup');
		$this->load->model('delivery_man/customer/Signups');
		$this->lsignup->check_delivery_auth();

    }
 
    //Default index page loading
    public function index()
    {
		
  if (!$this->lsignup->is_logged()) {
            $this->output->set_header("Location: " . base_url('delivery/login'), TRUE, 302);
        }
    
        // $content = $this->parser->parse('dashboard/home/home',$data,true);
        // $this->template_lib->full_admin_html_view($content);
         $delivery_man_id    = $this->session->userdata('id');  

                $total_order   = $this->Signups->total_delivery_man_order($delivery_man_id);

         $data = array(
                'title'        => 'Delivery Man  Dashboard',
                'total_invoice'=> $total_invoice,
                'reward_list'  => $reward_list,
                'total_order'     => $total_order,

                'currency'     => $currency_details[0]['currency_icon'],
                'position'     => $currency_details[0]['currency_position'],
            );
            
  $content = $this->parser->parse('delivery_man/customer/include/customer_home', $data, true);
        $this->template_lib->full_delivery_html_view($content);
    }

  



  public function edit_assigned_delivery($delivery_id)
    {
		//d($_POST);die;
        $this->form_validation->set_rules('delivery_boy_id', display('delivery_boy_id'), 'trim|required');
        $this->form_validation->set_rules('delivery_zone_id', display('delivery_zone_id'), 'trim|required');
        $this->form_validation->set_rules('order_no[]', display('order_no[]'), 'required');
		
        if ($this->form_validation->run() == TRUE) {
			
			
			if ($_FILES['cfile']['name']) {
                //Chapter chapter add start
                $config['upload_path'] = './my-assets/image/report_file/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
                $config['max_size'] = "*";
                $config['max_width'] = "*";
                $config['max_height'] = "*";
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('cfile')) {
                    $this->session->set_userdata(array('error_message' => $this->upload->display_errors()));
                     redirect('delivery_man/customer/Delivery_auth/manage_assigned_delivery');
                } else {
                    $image = $this->upload->data();
					
                    $image_url = "my-assets/image/report_file/" . $image['file_name'];

                    //Resize image config
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $image['full_path'];
                    $config['maintain_ratio'] = FALSE;
                    $config['width'] = 400;
                    $config['height'] = 400;
                    $config['new_image'] = 'my-assets/image/report_file/thumb/' . $image['file_name'];
                    $this->upload->initialize($config);
                    $this->load->library('image_lib', $config);
                    $resize = $this->image_lib->resize();
                    //Resize image config

                    $thumb_image = $config['new_image'];
                }
            }
			$subscription = $this->input->post('subscription');
			
			//echo $subscription;die;
			if (isset($subscription)) {
			$orderIDs = $this->input->post('order_no');
			$amountToSubtract = $this->input->post('amount');
			$paid_amount = false;

			// Check if $orderIDs is an array
			if (is_array($orderIDs)) {
				// Assuming you want to process multiple orders
				foreach ($orderIDs as $orderID) {
					$paid_amount = $this->Signups->update_order_amount($orderID, $amountToSubtract);
				}
			} else {
				// If it's not an array, treat it as a single order
				$paid_amount = $this->Signups->update_order_amount($orderIDs, $amountToSubtract);
			}

			if ($paid_amount === true) {
				$this->session->set_flashdata('message', 'Thank you for the payment.');
			} else {
				$this->session->set_flashdata('error_message', 'You entered wrong amount');
			}

			redirect('delivery_man/customer/Delivery_auth/manage_assigned_delivery');
		}

			
			
			$status = $this->input->post('status', TRUE);
			
            $delivery_assign = array(
                'delivery_boy_id'  => $this->input->post('delivery_boy_id', TRUE),
                'delivery_zone_id' => $this->input->post('delivery_zone_id', TRUE),
                'time_slot_id'     => $this->input->post('time_slot_id', TRUE),
                'completed_at'     => $this->input->post('completed_at', TRUE),
                'created_by'       => $this->session->userdata('user_id'),
                'status'           => $status,
				'remarks'          => ($status == 'Completed' || $status == 'InComplete') ? $this->input->post('remarks', TRUE) : '',
				'report_file'      => ($status == 'Completed' || $status == 'InComplete') ? $image_url : '',
            );
			//var_dump($delivery_id);die;
			$new_update_invoice_order = $this->Signups->update_invoice_order($delivery_id);
			
			$orders_remarks_list = $this->Signups->get_order_due_amount($new_update_invoice_order[0]['order_no']);
			//d($orders_remarks_list['order_id']);die;
			//var_dump($this->input->post('amount'));die;
			
			$id = $this->session->userdata('id');
			
			$this->db->select('name');
			$this->db->from('delivery_boy');
			$this->db->where('id', $id);
			$result = $this->db->get()->row_array();

			if ($result) {
				$deliveryBoyName = $result['name'];

				
				$orderNo = $new_update_invoice_order[0]['order_no'];
				
				
				$notificationContent = "This order " . (string)$orderNo . " was successfully delivered by " . $deliveryBoyName . ".";

				$users = get_user_by_user_type(1);

				$notificationData = [
					'user_id' => $users->user_type,
					'type' => 'order_completed',
					'data' => json_encode([
						'order_id' => $orders_remarks_list['order_id'],
						'order_no' => $orderNo,
						'content' => $notificationContent,
					]),
				];
				$this->db->insert('notifications', $notificationData);
				
			}
			if($status == 'Completed'){
				
				//var_dump($this->input->post('amount'));die;
			
				if($orders_remarks_list['due_amount'] == $this->input->post('amount'))
				{
					//echo "Hello";die;
					$this->Signups->transferDueToPaidAmount($new_update_invoice_order[0]['order_no']);	
				}
				else
				{
					//echo "Hello";die;
					
					$this->session->set_flashdata('error_message','Insufficient amount. Your total amount is '.$orders_remarks_list['due_amount'].' please pay only this much.');
					redirect('delivery_man/customer/Delivery_auth/manage_assigned_delivery');
					exit;
				}
				
				
				
			}else{

				$update_invoice = array(
					'invoice_status'  => 6,
				   
				);
				$invoice = $this->db->update('invoice', $update_invoice, array('invoice' =>$new_update_invoice_order[0]['order_no']));
			}
			
            //var_dump($invoice);die;
            $result = $this->db->update('delivery_assign', $delivery_assign, array('delivery_id' => $delivery_id));
            // delete order history
            $this->db->delete('delivery_orders', array('delivery_id' => $delivery_id));

            $orders = $this->input->post('order_no[]', TRUE);
            foreach ($orders as $order) {
                $delivery_orders = array(
                    'delivery_id' => $delivery_id,
                    'order_no'    => $order,
                );
                $delivery_orders = $this->db->insert('delivery_orders', $delivery_orders);
            }
			//
			
            if ($result) {
				
                $this->session->set_userdata(array('message' => display('successfully_updated')));
                redirect('delivery_man/customer/Delivery_auth/manage_assigned_delivery');
            } else {
                $this->session->set_userdata(array('error_message' => display('failed_try_again')));
            }
        }
		
		
		//var_dump($orders_remarks_list);
        $assigned_delivery_info = $this->Signups->get_assigned_delivery_info_by_id($delivery_id);
        $assigned_delivery_order_info = $this->Signups->get_assigned_delivery_order_info_by_id($delivery_id);
		$assigned_sub_delivery_order_info = $this->Signups->assigned_sub_delivery_order_info($delivery_id);
		//var_dump($assigned_sub_delivery_order_info);die;
        $delivery_orders = [];
        if (!empty($assigned_delivery_order_info)) {
            $delivery_orders = array_column($assigned_delivery_order_info, 'order_no');
        }
        $delivery_boys          = $this->Signups->get_active_delivery_boy();
        $delivery_zones         = $this->Signups->get_active_delivery_zone();
        $time_slots             = $this->Signups->get_active_time_slots();
        $pending_orders         = $this->Signups->get_pending_orders();
        $data = array(
            'title'                  => display('edit_assigned_delivery'),
            'delivery_orders'        => $delivery_orders,
            'assigned_delivery_info' => $assigned_delivery_info,
            'delivery_boys'          => $delivery_boys,
            'delivery_zones'         => $delivery_zones,
            'time_slots'             => $time_slots,
            'pending_orders'         => $pending_orders,
			'assigned_sub_delivery_order_info' => $assigned_sub_delivery_order_info
        );
        $content = $this->parser->parse('delivery_man/delivery_system/assign_delivery/edit_assigned_delivery', $data, true);
        $this->template_lib->full_delivery_html_view($content);
    }

    public function assigned_delivery_delete($delivery_id)
    {

        // delete order history
        $this->db->delete('delivery_orders', array('delivery_id' => $delivery_id));
        $this->Signups->assigned_delivery_delete($delivery_id);
    }
 public function edit_profile()
    {
        
         $id    = $this->session->userdata('id');  

        $this->form_validation->set_rules('name', display('name'), 'trim|required');
        $this->form_validation->set_rules('mobile', display('mobile'), 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $image = null;
            if ($_FILES['national_id']['name']) {

                //Chapter chapter add start
                $config['upload_path']   = './my-assets/image/delivery_system/national_id/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
                $config['max_size']      = "*";
                $config['max_width']     = "*";
                $config['max_height']    = "*";
                $config['encrypt_name']  = TRUE;
                $this->upload->initialize($config);
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('national_id')) {
                    $this->session->set_userdata(array('error_message' => $this->upload->display_errors()));
                    redirect('delivery_man/customer/Delivery_dashboard/edit_profile/' . $id);
                } else {
                    $image       = $this->upload->data();
                    $image_url   = "my-assets/image/delivery_system/national_id/" . $image['file_name'];
                    $national_id = $image_url;
                    //Old image delete
                    $old_national_id = $this->input->post('old_national_id', TRUE);
                    $old_file        = substr($old_national_id, strrpos($old_national_id, '/') + 1);
                    @unlink(FCPATH . 'my-assets/image/delivery_system/national_id/' . $old_file);
                }
            }
            $image = null;
            if ($_FILES['driving_license']['name']) {
                //Chapter chapter add start
                $config['upload_path']   = './my-assets/image/delivery_system/driving_license/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
                $config['max_size']      = "*";
                $config['max_width']     = "*";
                $config['max_height']    = "*";
                $config['encrypt_name']  = TRUE;
                $this->upload->initialize($config);
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('driving_license')) {
                    $this->session->set_userdata(array('error_message' => $this->upload->display_errors()));
                    redirect('delivery_man/customer/Delivery_dashboard/edit_profile/' . $id);
                } else {
                    $image           = $this->upload->data();
                    $image_url       = "my-assets/image/delivery_system/driving_license/" . $image['file_name'];
                    $driving_license = $image_url;
                    //Old image delete
                    $old_driving_license = $this->input->post('old_driving_license', TRUE);
                    $old_file        = substr($old_driving_license, strrpos($old_driving_license, '/') + 1);
                    @unlink(FCPATH . 'my-assets/image/delivery_system/driving_license/' . $old_file);
                }
            }

            $old_driving_license = $this->input->post('old_driving_license', TRUE);
            $old_national_id     = $this->input->post('old_national_id', TRUE);
            $data = array(
                'name'            => $this->input->post('name', TRUE),
                'mobile'          => $this->input->post('mobile', TRUE),
                'address'         => $this->input->post('address', TRUE),
                'driving_license' => (!empty($driving_license) ? $driving_license : $old_driving_license),
                'national_id'     => (!empty($national_id) ? $national_id : $old_national_id),
                'birth_date'      => $this->input->post('birth_date', TRUE),
                'bank_name'       => $this->input->post('bank_name', TRUE),
                'account_no'      => $this->input->post('account_no', TRUE),
                'account_name'    => $this->input->post('account_name', TRUE),
                'status'          => $this->input->post('status', TRUE),
                'created_by'      => $this->session->userdata('user_id'),
            );
            $result = $this->db->update('delivery_boy', $data, array('id' => $id));
            if ($result) {
                $this->session->set_userdata(array('message' => display('successfully_updated')));
                redirect('delivery_man/customer/Delivery_auth/manage_assigned_delivery');
            } else {
                $this->session->set_userdata(array('error_message' => display('failed_try_again')));
            }
        }
        $delivery_boy_info = $this->Signups->get_delivery_boy_info_by_id($id);
        $data = array(
            'title' => display('edit_delivery_boy'),
            'delivery_boy_info' => $delivery_boy_info
        );
        $content = $this->parser->parse('delivery_man/delivery_system/edit_delivery_boy', $data, true);
        $this->template_lib->full_delivery_html_view($content);

    

}
    #========Logout=======#
    public function delivery_logout()
    {
        // echo "yes";
        // die();
        
        $var = $this->lsignup->logout();
       
        if ($var)
        
            $this->output->set_header("Location: " . base_url('delivery/login'), TRUE, 302);
    }

    //Update user profile from
  

    #=============Update Profile========#
    public function update_profile()
    {
        $this->Customer_dashboards->profile_update();
        $this->session->set_userdata(array('message' => display('successfully_updated')));
        redirect(base_url('web/customer/customer_dashboard/edit_profile'));
    }

    #=============Change Password Form=========#
    public function change_password_form()
    {
        $data['Soft_settings'] = $this->soft_settings->retrieve_setting_editdata();
        $data['title'] = display('change_password');
        $data['module'] = "web";
        $data['page'] = "customer/change_password";

        $this->parser->parse('customer/customer_html_template', $data);

    }

    #============Change Password===========#
    public function change_password()
    {
        $error = '';
        $email = $this->input->post('email',TRUE);
        $old_password = $this->input->post('old_password',TRUE);
        $new_password = $this->input->post('password',TRUE);
        $repassword = $this->input->post('repassword',TRUE);

        $edit_data = $this->Customer_dashboards->profile_edit_data();
        $old_email = $edit_data->customer_email;

        if ($email == '' || $old_password == '' || $new_password == '') {
            $error = display('blank_field_does_not_accept');
        } else if ($email != $old_email) {
            $error = display('you_put_wrong_email_address');
        } else if (strlen($new_password) < 6) {
            $error = display('new_password_at_least_six_character');
        } else if ($new_password != $repassword) {
            $error = display('password_and_repassword_does_not_match');
        } else if ($this->Customer_dashboards->change_password($email, $old_password, $new_password) === FALSE) {
            $error = display('you_are_not_authorised_person');
        }

        if ($error != '') {
            $this->session->set_userdata(array('error_message' => $error));
            $this->output->set_header("Location: " . base_url() . 'web/customer/customer_dashboard/change_password_form', TRUE, 302);
        } else {
            $logout = $this->user_auth->logout();
            if ($logout) {
                $this->session->set_userdata(array('message' => display('successfully_changed_password')));
                $this->output->set_header("Location: " . base_url() . 'login', TRUE, 302);
            }
        }
    }

    //Select city by country id
    public function select_city_country_id()
    {
        $this->load->model('dashboard/Customers');
        $country_id = $this->input->post('country_id',TRUE);
        $states = $this->Customers->select_city_country_id($country_id);

        $html = "";
        if ($states) {
            $html .= "<select class=\"form-control select2\" id=\"country\" name=\"country\" style=\"width: 100%\">";
            foreach ($states as $state) {
                $html .= "<option value='" . $state->name . "'>" . $state->name . "</option>";
            }
            $html .= "</select>";
        }
        echo $html;
    }

    // show wishlist

    public function wishlist()
    {
        $customer_id = $this->session->userdata('customer_id');

        $this->db->select('a.*,b.product_name,b.product_model');
        $this->db->from('wishlist a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->where('a.user_id', $customer_id);
        $this->db->order_by('wishlist_id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $wishlists = $query->result_array();
        } else {
            $wishlists = '';
        }

        $data = [
            'wishlists' => $wishlists
        ];

        $content = $this->parser->parse('web/customer/wishlist', $data, true);
        $this->template_lib->full_customer_html_view($content);
    }

    //delete wishlist
    public function wishlist_delete($wishlist_id)
    {
        $this->Wishlists->delete_wishlist($wishlist_id);
        $this->session->set_userdata(array('message' => display('successfully_delete')));
        redirect('web/customer/customer_dashboard/wishlist');
    }
}