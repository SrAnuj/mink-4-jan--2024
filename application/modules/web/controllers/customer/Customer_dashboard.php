<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Customer_dashboard extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard/Soft_settings');
        $this->load->model('dashboard/Customer_dashboards');
        $this->load->model('web/customer/Invoices');
        $this->load->model('web/customer/Orders');
        $this->load->model('dashboard/Wishlists');
        $this->load->model('dashboard/soft_settings');
        $this->user_auth->check_customer_auth();
    }

    //Default customer index load.
    public function index()
    {

        if (!$this->user_auth->is_logged()) {
            $this->output->set_header("Location: " . base_url('login'), TRUE, 302);
        }
        $customer_id   = $this->session->userdata('customer_id');
        $total_invoice = $this->Invoices->total_customer_invoice($customer_id);
        $total_order   = $this->Orders->total_customer_order($customer_id);
		//print($total_order);die;
        $reward_list   = $this->Orders->reward_list($customer_id);
// var_dump($reward_list[0]['amount']);
// die();
        $currency_details = $this->Soft_settings->retrieve_currency_info();
		$c_vacations = $this->db
						->select('subscription.*,product_information.product_name,product_information.product_model')
						->from('subscription')
						->join('product_information', 'subscription.product_id = product_information.product_id', 'inner')
						->where('subscription.customer_id', $customer_id)
						->where('subscription.status', 0)
						->where('subscription.end_date > DATE(NOW())') // Add this condition
						->get()
						->result_array();
					
			$subscriptionData = $this->Orders->getSubscriptionData($customer_id);
			//$res = array();
				if ($subscriptionData) {
			// Get the plan duration and calculate the expiration date.
			$plan = $subscriptionData[0]['plan']; // Assuming 'plan' is the column name in your database.
			//print($plan);die;
			$durationInDays = (int) filter_var($plan, FILTER_SANITIZE_NUMBER_INT);
			  $startDate = strtotime($subscriptionData[0]['created_at']);
			 
			$expiryDate = strtotime("+{$durationInDays} days", $startDate);
			//$expiryDate = strtotime('2023-11-01');
			//echo date('Y-m-d',$expiryDate);die;
			$currentTime = time(); 


			$timeRemaining = $expiryDate - $currentTime;
			
			// Define the threshold for showing the alert (e.g., 1 week in seconds).
			 $oneWeekInSeconds = 7 * 24 * 60 * 60;

			if ($timeRemaining < $oneWeekInSeconds) {
				
				// Display an alert message.
				$res['alert_message'] = "Your ".$subscriptionData[0]['product_name']." subscription is expiring on ! ".date('j F Y',$expiryDate)." Please renew it.";
			}
		}
			//print_r($subscriptionData);die;
		//print_r($this->db->last_query());die;
        if(check_module_status('loyalty_points') == 1){
            $this->load->model('loyalty_points/loyalty_points_model');
            $available_points = $this->loyalty_points_model->available_points($customer_id);
            
            $data = array( 
                'title'           => display('dashboard'),
                'total_invoice'   => $total_invoice,
                'total_order'     => $total_order,
                'reward_list'     => $reward_list,

                'available_points'=> $available_points,
                'currency'        => $currency_details[0]['currency_icon'],
                'position'        => $currency_details[0]['currency_position'],
				'c_vacations' => $c_vacations,
				'alert_msg' => $res['alert_message'],
				'subscription_list' => $subscriptionData,
            );
        }else{
            $data = array(
                'title'        => display('dashboard'),
                'total_invoice'=> $total_invoice,
                'reward_list'  => $reward_list,
                 'total_order'     => $total_order,
                'currency'     => $currency_details[0]['currency_icon'],
                'position'     => $currency_details[0]['currency_position'],
				'c_vacations' => $c_vacations,
				'alert_msg' => $res['alert_message'],
				'subscription_list' => $subscriptionData,
            );
        }
        $content = $this->parser->parse('web/customer/include/customer_home', $data, true);
        $this->template_lib->full_customer_html_view($content);
    }

    #========Logout=======#
    public function logout()
    {
        if ($this->user_auth->logout())
            $this->output->set_header("Location: " . base_url(), TRUE, 302);
    }

    //Update user profile from
    public function edit_profile()
    {
        $this->load->model('dashboard/Customers');
        $edit_data = $this->Customer_dashboards->profile_edit_data();
        $state_list = $this->Customers->select_city_country_id($edit_data->country);
        $country_list = $this->Customers->country_list();

        $data = array(
            'title' => display('update_profile'),
            'first_name' => $edit_data->first_name,
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
            'state_list' => $state_list,
            'country_list' => $country_list,
        );
        
        $data['Soft_settings'] = $this->soft_settings->retrieve_setting_editdata();
        $data['module'] = "web";
        $data['page'] = "customer/edit_profile";

        $this->parser->parse('customer/customer_html_template', $data);
    }

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