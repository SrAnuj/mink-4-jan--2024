<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ccustomer extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->auth->check_user_auth();
        $this->load->library('dashboard/lcustomer');
        $this->load->model('dashboard/Customers');
    }

    //Default loading for Customer System.
    public function index()
    {
        // $this->permission->check_label('add_customer')->create()->redirect();

        $content = $this->lcustomer->customer_add_form();
        $this->template_lib->full_admin_html_view($content);
    }

    //customer_search_item
    public function customer_search_item()
    {

        $customer_id = $this->input->post('customer_id', TRUE);
        $content = $this->lcustomer->customer_search_item($customer_id);
        $this->template_lib->full_admin_html_view($content);
    }
// customer import csv 

  public function add_customer_csv()
    {
        $this->permission->check_label('import_customer_csv')->create()->redirect();
        $data = array(
            'title' => display('import_customer_csv')
        );
        $content = $this->parser->parse('dashboard/customer/add_customer_csv', $data, true);
        $this->template_lib->full_admin_html_view($content);
    }
 function uploadCsv()
    {
        $this->permission->check_label('import_customer_csv')->create()->redirect();
        $count = 0;
        $fp = fopen($_FILES['upload_csv_file']['tmp_name'], 'r') or die("can't open file");

        if (($handle = fopen($_FILES['upload_csv_file']['tmp_name'], 'r')) !== FALSE) {

            while ($csv_line = fgetcsv($fp, 1024)) {
                //keep this if condition if you want to remove the first row
                for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                  $insert_csv = array();
           
                    $insert_csv['customer_name'] = (!empty($csv_line[0]) ? $csv_line[0] : '');
                    $insert_csv['customer_mobile'] = (!empty($csv_line[1]) ? $csv_line[1] : '');
                    $insert_csv['customer_email'] = (!empty($csv_line[2]) ? $csv_line[2] : '');
                    $insert_csv['password'] = (!empty($csv_line[3]) ? $csv_line[3] : '');
                    $insert_csv['vat_no'] = (!empty($csv_line[4]) ? $csv_line[4] : '');
                     $insert_csv['cr_no'] = (!empty($csv_line[5]) ? $csv_line[5] : '');
                    $insert_csv['previous_balance'] = (!empty($csv_line[6]) ? $csv_line[6] : '');
                    $insert_csv['customer_short_address'] = (!empty($csv_line[7]) ? $csv_line[7] : '');
                    $insert_csv['customer_address_1'] = (!empty($csv_line[8]) ? $csv_line[8] : '');
                    $insert_csv['customer_address_2'] = (!empty($csv_line[9]) ? $csv_line[9] : '');
                    $insert_csv['city'] = (!empty($csv_line[10]) ? $csv_line[10] : '');


                    $insert_csv['state'] = (!empty($csv_line[11]) ? $csv_line[11] : '');
                    $insert_csv['country'] = (!empty($csv_line[12]) ? $csv_line[12] : '');

                    $insert_csv['zip'] = (!empty($csv_line[13]) ? $csv_line[13] : '');
                    $insert_csv['status'] = (!empty($csv_line[14]) ? $csv_line[14] : 0);
                }
                if (!empty($insert_csv['password'])) {

                    $password = md5("gef". $insert_csv['password']);
                }
                
                else {
                    $password = "12345678";
                }   

                
                
                //Data organizaation for insert to database
                    $customer_id = generator(15);
                
        //Customer  basic information adding.
                
                $data = array(
                    'customer_id' => $customer_id,
                    'customer_name' => $insert_csv['customer_name'],
                    'customer_mobile' => $insert_csv['customer_mobile'],
                    'customer_email' => $insert_csv['customer_email'],
                    'password' => $password,
                    'vat_no' => $insert_csv['vat_no'],
                    'cr_no' => $insert_csv['cr_no'],
                    'previous_balance' => $insert_csv['previous_balance'],
                    'customer_short_address' => $insert_csv['customer_short_address'],
                    'customer_address_1' => $insert_csv['customer_address_1'],
                    'customer_address_2' => $insert_csv['customer_address_2'],
                    'city' => $insert_csv['city'],
                    'state' => $insert_csv['state'],
                     'country' => $insert_csv['country'],
                    'zip' => $insert_csv['zip'],
                    'status' => $insert_csv['status']
                );
                // var_dump($data);
                // die();

                if ($count > 0) {
                    $result = $this->db->select('*')
                        ->from('customer_information')
		                ->where('customer_name',$data['customer_name'])
                        ->get()
                        ->num_rows();
    // var_dump($result);
    //             die();
                    if ($result == 0 && !empty($data['customer_name']) ) {

                     $customer =    $this->db->insert('customer_information', $data);
                        
                        	if($customer){
				if(check_module_status('accounting') == 1){
					$this->load->model('accounting/account_model');
					$this->account_model->insert_customer_head($data);



					$previous_balance = $data['previous_balance'];
					if (!empty($previous_balance)) {
						$find_active_fiscal_year=$this->db->select('id')->from('acc_fiscal_year')->where('status',1)->get()->row();
	      				if (!empty($find_active_fiscal_year)) {
							$headcode = $this->db->select('HeadCode,HeadName')->from('acc_coa')->where('customer_id',$customer_id)->get()->row();
							$dtpDate = date('Y-m-d');
					        $datecheck = $this->fiscal_date_check($dtpDate);
					        if(!$datecheck){
					            $this->session->set_userdata('error_message', 'Invalid date selection! Please select a date from active fiscal year.');
					            redirect('accounting/opening_balance');
					        }
					        $createby   = $this->session->userdata('user_id');
					        $postData = array(
					            'fy_id'          =>$find_active_fiscal_year->id,
					            'headcode'       =>$headcode->HeadCode,
					            'amount'         =>$previous_balance,
					            'adjustment_date'=>$dtpDate,
					            'created_by'     =>$createby,
					        ); 
					        if ($this->account_model->create_opening($postData)) {
						        $headcode  =$headcode->HeadCode;
						        $headname  =$this->db->select('HeadName')->from('acc_coa')->where('HeadCode',$headcode->HeadCode)->get()->row();
						        $createdate=date('Y-m-d H:i:s');
						        $date      =$createdate;

					            $opening_balance_credit = array(
					                'fy_id'     =>$find_active_fiscal_year->id,
					                'VNo'       =>'OP-'.$headcode,
					                'Vtype'     =>'Sales',
					                'VDate'     =>$date,
					                'COAID'     =>3,
					                'Narration' =>'Opening balance credired from "Owners Equity And Capital" from: '.$headname->HeadName,
					                'Debit'     =>0,
					                'Credit'    =>$previous_balance,
					                'is_opening'=>1,
					                'IsPosted'  =>1,
					                'CreateBy'  =>$receive_by,
					                'CreateDate'=>$createdate,
					                'IsAppove'  =>1
					            );
						        $this->db->insert('acc_transaction',$opening_balance_credit);
							}
					    }
					}




				}
			}
                        
                     		$this->db->select('*');
			$this->db->from('customer_information');
			$this->db->order_by('customer_name','asc');
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$json_customer[] = array('label'=>$row->customer_name. (!empty($row->customer_mobile)?' ('.$row->customer_mobile.')':''),'value'=>$row->customer_id);
			}
			$cache_file ='./my-assets/js/admin_js/json/customer.json';
			$customerList = json_encode($json_customer);
			file_put_contents($cache_file,$customerList);
                    } else {

                        $this->db->where('customer_id', $data['customer_id']);
                        $this->db->where('customer_name', $data['customer_name']);
                        $this->db->update('customer_information', $data);

                        $this->db->select('*');
                        $this->db->from('customer_information');
                        $this->db->where('status', 1);
                        $query = $this->db->get();

                      	foreach ($query->result() as $row) {
				$json_customer[] = array('label'=>$row->customer_name. (!empty($row->customer_mobile)?' ('.$row->customer_mobile.')':''),'value'=>$row->customer_id);
			}
			$cache_file ='./my-assets/js/admin_js/json/customer.json';
			$customerList = json_encode($json_customer);
			file_put_contents($cache_file,$customerList);
                    }

                }

                $count++;
            }
        }

        fclose($fp) or die("can't close file");
        $this->session->set_userdata(array('message' => display('successfully_added')));

        if (isset($_POST['add-customer'])) {
            redirect(base_url('dashboard/Ccustomer/manage_customer'));
            exit;
        } elseif (isset($_POST['add-customer-another'])) {
            redirect(base_url('dashboard/Ccustomer'));
            exit;
        }
    }

   
    //Manage customer
    public function manage_customer()
    {

        $this->load->model('dashboard/Customers');
        $content = $this->lcustomer->customer_list();
        $this->template_lib->full_admin_html_view($content);
    }

    //Insert Product and upload
    public function insert_customer()
    {
		
        $customer_id = generator(15);
		// Customer basic information adding.
		$data = array(
			'customer_id'           => $customer_id,
			'customer_name'         => $this->input->post('customer_name', TRUE),
			'customer_mobile'       => $this->input->post('mobile', TRUE),
			'customer_email'        => $this->input->post('email', TRUE),
			'password'              => md5("gef" . $this->input->post('password', TRUE)),
			'vat_no'                => $this->input->post('vat_no', TRUE),
			'cr_no'                 => $this->input->post('cr_no', TRUE),
			'previous_balance'      => $this->input->post('previous_balance', TRUE),
			'customer_short_address' => $this->input->post('address', TRUE),
			'customer_address_1'    => $this->input->post('customer_address_1', TRUE),
			'customer_address_2'    => $this->input->post('customer_address_2', TRUE),
			'city'                  => $this->input->post('city', TRUE),
			'state'                 => $this->input->post('state', TRUE),
			'country'               => $this->input->post('country', TRUE),
			'zip'                   => $this->input->post('zip', TRUE),
			'customer_zone'       => $this->input->post('customer_zone', TRUE),
			'status'                => 1
		);
		
	

	// Check the character limit for 'previous_balance'
	$maxLength = 1000;
	$previousBalance = (int) $this->input->post('previous_balance', TRUE);

	if ($previousBalance > $maxLength) {
		
		// Character limit exceeded; handle the error as needed
		$this->session->set_userdata(array('error_message' => 'Error: More than 1000 characters is not allowed for "previous_balance".'));
		redirect(base_url('dashboard/Ccustomer'));
		exit;
	} else {
		// Character limit is within the allowed range; proceed with saving the data
		$result = $this->Customers->customer_entry($data);
		if ($result == TRUE) {
			$this->session->set_userdata(array('message' => display('successfully_added')));
			if (isset($_POST['add-customer'])) {
				redirect(base_url('dashboard/Ccustomer/manage_customer'));
				exit;
			} elseif (isset($_POST['add-customer-another'])) {
				redirect(base_url('dashboard/Ccustomer'));
				exit;
			}
		} else {
			$this->session->set_userdata(array('error_message' => display('already_exists')));
			redirect(base_url('dashboard/Ccustomer'));
		}
	}

    }

    //customer Update Form
    public function customer_update_form($customer_id = null)
    {
        $content = $this->lcustomer->customer_edit_data($customer_id);
        $this->template_lib->full_admin_html_view($content);
    }

    // customer Update
    public function customer_update()
    {
		
        $customer_id = $this->input->post('customer_id', TRUE);

        //Customer  basic information adding.
        $data = array(
            'customer_name'         => $this->input->post('customer_name', TRUE),
            'customer_mobile'       => $this->input->post('mobile', TRUE),
            'customer_email'        => $this->input->post('email', TRUE),
            'password'              => md5("gef" . $this->input->post('password',TRUE)),
            'vat_no'                => $this->input->post('vat_no', TRUE),
            'cr_no'                 => $this->input->post('cr_no', TRUE),
            'customer_short_address' => $this->input->post('address', TRUE),
            'customer_address_1'    => $this->input->post('customer_address_1', TRUE),
            'customer_address_2'    => $this->input->post('customer_address_2', TRUE),
            'city'                  => $this->input->post('city', TRUE),
            'state'                 => $this->input->post('state', TRUE),
            'country'               => $this->input->post('country', TRUE),
            'zip'                   => $this->input->post('zip', TRUE),
			'previous_balance' =>   $this->input->post('previous_balance', TRUE),
            'status'                => 1
        );
// var_dump($data);
// die();
			$maxLength = 1000;
				$previousBalance = (int) $this->input->post('previous_balance', TRUE);

				if ($previousBalance > $maxLength) {
					
					// Character limit exceeded; handle the error as needed
					$this->session->set_userdata(array('error_message' => 'Error: More than 1000 characters is not allowed for "previous_balance".'));
					redirect(base_url('dashboard/Ccustomer'));
					exit;
				} 
				else {
					$this->Customers->update_customer($data, $customer_id);
					$this->session->set_userdata(array('message' => display('successfully_updated')));
					redirect('dashboard/Ccustomer/manage_customer');
				}
        
    }

    //Select city by country id
    public function select_city_country_id()
    {
        $country_id = $this->input->post('country_id', TRUE);
        $states = $this->Customers->select_city_country_id($country_id);

        $html = "";
        if ($states) {
            $html .= "<select class=\"form-control select2 width_100p\" id=\"country\" name=\"country\">
					<option value=\"\">" . display('select_one') . "</option>";
            foreach ($states as $state) {
                $html .= "<option value='" . $state->name . "'>" . $state->name . "</option>";
            }
            $html .= "</select>";
        }
        echo $html;
    }

    //Credit Customer Form
    public function credit_customer()
    {
        $this->load->model('dashboard/Customers');

        $content = $this->lcustomer->credit_customer_list();
        $this->template_lib->full_admin_html_view($content);;
    }

    //Paid Customer list. The customer who will pay 100%.
    public function paid_customer()
    {
        $this->load->model('dashboard/Customers');
        $content = $this->lcustomer->paid_customer_list();
        $this->template_lib->full_admin_html_view($content);;
    }

    //Customer Ledger
    public function customer_ledger($customer_id)
    {
        $content = $this->lcustomer->customer_ledger_data($customer_id);
        $this->template_lib->full_admin_html_view($content);
    }

    //Customer Ledger Report
    public function customer_ledger_report()
    {
        $this->permission->check_label('customer_ledger')->read()->redirect();
        $customer_id = $this->input->post('customer_id', TRUE);
        $from_date   = $this->input->post('from_date', TRUE);
        $to_date     = $this->input->post('to_date', TRUE);
        $content = $this->lcustomer->customer_ledger_report($customer_id, $from_date, $to_date);
        $this->template_lib->full_admin_html_view($content);
    }

    //Customer Final Ledger
    public function customerledger($customer_id)
    {
        $content = $this->lcustomer->customerledger_data($customer_id);
        $this->template_lib->full_admin_html_view($content);
    }
    //Customer Previous Balance
    public function previous_balance_form()
    {
        $content = $this->lcustomer->previous_balance_form();
        $this->template_lib->full_admin_html_view($content);
    }
    // customer delete
    public function customer_delete($customer_id)
    {
        $this->load->model('dashboard/Customers');
        $this->Customers->delete_customer($customer_id);
        $this->session->set_userdata(array('message' => display('successfully_delete')));
        redirect('dashboard/Ccustomer/manage_customer');
    }
    public function customer_balance_report()
    {
        $this->permission->check_label('customer_balance_report')->read()->redirect();

        $from_date = $this->input->post('from_date', TRUE);
        $to_date  = $this->input->post('to_date', TRUE);
        $content  = $this->lcustomer->customer_balance_report($from_date, $to_date);
        $this->template_lib->full_admin_html_view($content);
    }
}