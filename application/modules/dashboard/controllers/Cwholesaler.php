<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cwholesaler extends MX_Controller
{

    public $wholesaler_id;

    function __construct()
    {
        parent::__construct();
        $this->load->library('auth');
        $this->load->library('dashboard/lwholesaler');
        $this->load->library('session');
        $this->load->model('dashboard/Wholesalers');
        $this->auth->check_user_auth();
    }
 
    public function index()
    {
        $this->permission->check_label('add_wholesaler')->create()->redirect();

        $content = $this->lwholesaler->wholesaler_add_form();
        $this->template_lib->full_admin_html_view($content);
    }

    //Supplier Search Item
    public function supplier_search_item()
    {
        $supplier_id = $this->input->post('wholesaler_id', TRUE);
        $content = $this->lwholesaler->supplier_search_item($wholesaler_id);

        $this->template_lib->full_admin_html_view($content);
    }

    //Product Add Form
    public function manage_wholesaler()
    {
        $this->permission->check_label('manage_wholesaler')->read()->redirect();
        $content = $this->lwholesaler->wholesaler_list();
        $this->template_lib->full_admin_html_view($content);
    }

    //Insert Product and uload
    public function insert_wholesaler()
    {


        $this->permission->check_label('add_wholesaler')->create()->redirect();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('bussiness_name', display('bussiness_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', display('address'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('mobile', display('mobile'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->session->set_userdata(array('error_message' => 'fields_must_not_be_empty'));
            $this->index();
        } else {

            $wholesaler_id = $this->auth->generator(15);
            $data = array(
                'wholesaler_id'  => $wholesaler_id,
                'bussiness_name' => $this->input->post('bussiness_name', TRUE),
                'mobile'       => $this->input->post('mobile', TRUE),
                'abn'       => $this->input->post('abn', TRUE),
                'email'        => $this->input->post('email', TRUE),
				'password' 	=> md5("gef" . $this->input->post('password', TRUE)),
                'address'      => $this->input->post('address', TRUE),
                'city'      => $this->input->post('city', TRUE),
                'country' => $this->input->post('country', TRUE),
                'website'        => $this->input->post('website', TRUE),
            );
//   var_dump($data);
//             die(); 
            $wholesaler = $this->Wholesalers->wholesaler_entry($data);
//           	var_dump($data);
// 		die(); 
        } 
        if ($wholesaler == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_added')));
            if (isset($_POST['add-wholesaler'])) {
                redirect(base_url('dashboard/Cwholesaler/manage_wholesaler'));
                exit;
            } elseif (isset($_POST['add-wholesaler-another'])) {
                redirect(base_url('dashboard/Cwholesaler'));
                exit;
            }
        } else {
            $this->session->set_userdata(array('error_message' => display('already_exists')));
            if (isset($_POST['add-wholesaler'])) {
                redirect(base_url('dashboard/Cwholesaler/manage_wholesaler'));
                exit;
            } elseif (isset($_POST['add-wholesaler-another'])) {
                redirect(base_url('dashboard/Cwholesaler'));
                exit;
            }
        }
    }

    //Supplier Update Form
    public function wholesaler_update_form($supplier_id)
    {

        $this->permission->check_label('manage_wholesaler')->update()->redirect();
        $content = $this->lsupplier->wholesaler_edit_data($supplier_id);
        $this->template_lib->full_admin_html_view($content);
    }
	public function wholesaler_update_f($wholesaler_id)
    {
		//echo $wholesaler_id;die;
		$string = urldecode($wholesaler_id);
		$string = str_replace(' ', '', $string);
        $this->permission->check_label('manage_wholesaler')->update()->redirect();
        $content = $this->lwholesaler->wholesaler_edit_data($string);
        $this->template_lib->full_admin_html_view($content);
		
		// $content = $this->lretailer->retailer_edit_data($string);
        //$this->template_lib->full_admin_html_view($content);
    }
    // Supplier Update
    public function wholesaler_update()
    {
		//d($this->input->post());die;
        $this->permission->check_label('manage_wholesaler')->update()->redirect();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('bussiness_name', display('bussiness_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', display('address'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('mobile', display('mobile'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->session->set_userdata(array('error_message' => 'fields_must_not_be_empty'));
            $wholesaler_id = $this->input->post('wholesaler_id', TRUE);
            $this->supplier_update_form($supplier_id);
        } else {
            $wholesaler_id = $this->input->post('wholesaler_id', TRUE);
            $data = array(
                'bussiness_name' => $this->input->post('bussiness_name', TRUE),
                 'mobile'       => $this->input->post('mobile', TRUE),
                'abn'       => $this->input->post('abn', TRUE),
                'email'        => $this->input->post('email', TRUE),
                'address'      => $this->input->post('address', TRUE),
                'city'      => $this->input->post('city', TRUE),
                'country' => $this->input->post('country', TRUE),
                'website'        => $this->input->post('website', TRUE),
            );
			$this->Wholesalers->update_wholesaler($data, $wholesaler_id);
            //$this->Suppliers->update_supplier($data, $supplier_id);
            $this->session->set_userdata(array('message' => display('successfully_updated')));
            redirect(base_url('dashboard/Cwholesaler/manage_wholesaler'));
            exit;
        }
    }

    // Supplier Delete from System
    public function delete_wholesaler($wholesaler_id)
    {
        $this->permission->check_label('manage_wholesaler')->delete()->redirect();

        $result = $this->Wholesalers->delete_wholesaler($wholesaler_id);
        if ($result) {
            $this->session->set_userdata(array('message' => display('successfully_delete')));
            redirect(base_url('dashboard/Cwholesaler/manage_wholesaler'));
        }
    }

    // Supplier details findings
    public function supplier_details($wholesaler_id)
    {
        $this->permission->check_label('manage_wholesaler')->read()->redirect();

        $content = $this->lsupplier->supplier_detail_data($wholesaler_id);
        $this->wholesaler_id = $wholesaler_id;
        $this->template_lib->full_admin_html_view($content);
    }

    public function supplier_ledger($supplier_id)
    {
        $content = $this->lsupplier->supplier_ledger($supplier_id);
        $this->supplier_id = $supplier_id;
        $this->template_lib->full_admin_html_view($content);
    }

    //Supplier Ledger Report
    public function supplier_ledger_report()
    {
        $this->permission->check_label('supplier_ledger')->read()->redirect();

        $supplier_id = $this->input->post('supplier_id', TRUE);
        $from_date  = $this->input->post('from_date', TRUE);
        $to_date    = $this->input->post('to_date', TRUE);
        $this->supplier_id = $supplier_id;
        $content = $this->lsupplier->supplier_ledger_report($supplier_id, $from_date, $to_date);
        $this->template_lib->full_admin_html_view($content);
    }

    // Supplier wise sales report details
    public function supplier_sales_details($supplier_id)
    {

        $content = $this->lsupplier->supplier_sales_details($supplier_id);
        $this->supplier_id = $supplier_id;
        $this->template_lib->full_admin_html_view($content);
    }

    // Supplier wise sales report summary
    public function supplier_sales_summary($supplier_id)
    {
        $content = $this->lsupplier->supplier_sales_summary($supplier_id);
        $this->supplier_id = $supplier_id;
        $this->template_lib->full_admin_html_view($content);
    }

    // Actual Ledger based on sales & deposited amount
    public function sales_payment_actual($supplier_id)
    {

        $limit = 300;
        $start_record = 0;
        $links = "";
        $content = $this->lsupplier->sales_payment_actual($supplier_id, $limit, $start_record, $links);
        $this->supplier_id = $supplier_id;
        $this->template_lib->full_admin_html_view($content);
    }

    public function supplier_balance_report()
    {
        $this->permission->check_label('supplier_balance_report')->read()->redirect();

        $from_date  = $this->input->post('from_date', TRUE);
        $to_date    = $this->input->post('to_date', TRUE);
        $content = $this->lsupplier->supplier_balance_report($from_date, $to_date);
        $this->template_lib->full_admin_html_view($content);
    }
}