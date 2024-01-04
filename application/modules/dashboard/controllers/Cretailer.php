<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cretailer extends MX_Controller
{

    public $retailer_id;

    function __construct()
    {
        parent::__construct();
        $this->load->database();

        $this->load->library('auth');
        $this->load->library('dashboard/lretailer');
        $this->load->library('session');
        $this->load->model('dashboard/Retailers');
        $this->auth->check_user_auth();
    }

    public function index()
    {
        $this->permission->check_label('add_retailer')->create()->redirect();

        $content = $this->lretailer->retailer_add_form();
        $this->template_lib->full_admin_html_view($content);
    }

    //Supplier Search Item
    // public function supplier_search_item()
    // {
    //     $supplier_id = $this->input->post('supplier_id', TRUE);
    //     $content = $this->lsupplier->supplier_search_item($supplier_id);

    //     $this->template_lib->full_admin_html_view($content);
    // }

    //Product Add Form
    public function manage_retailer()
    {
        $this->permission->check_label('manage_retailer')->read()->redirect();
        $content = $this->lretailer->retailer_list();
        $this->template_lib->full_admin_html_view($content);
    }

    //Insert Product and uload
    public function insert_retailer()
    {


        $this->permission->check_label('add_retailer')->create()->redirect();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('company_name', display('company_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('adress', display('adress'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('phone', display('phone'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->session->set_userdata(array('error_message' => 'fields_must_not_be_empty'));
            $this->index();
        } else {

            $retailer_id  = $this->auth->generator(20);
            $data = array(
                'retailer_id'  => $retailer_id,
                'company_name' => $this->input->post('company_name', TRUE),
                'adress'      => $this->input->post('adress', TRUE),
                'email'        => $this->input->post('email', TRUE),
				'password' 	=> md5("gef" . $this->input->post('password', TRUE)),
                'phone'       => $this->input->post('phone', TRUE),
                'city'        => $this->input->post('city', TRUE),
                'country'       => $this->input->post('country', TRUE),
                'website' => $this->input->post('website', TRUE),
                'bussiness_field' => $this->input->post('bussiness_field', TRUE),
                // 'sell_products' => $this->input->post('sell_products', TRUE),
                // 'status'       => 1,
            );
           
            $retailer = $this->Retailers->retailer_entry($data);
            // var_dump($retailer);
            // die();  

            
        } 
        if ($retailer == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_added')));
            if (isset($_POST['add-retailer'])) {
                redirect(base_url('dashboard/Cretailer/manage_retailer'));
                exit;
            } elseif (isset($_POST['add-retailer-another'])) {
                redirect(base_url('dashboard/Cretailer'));
                exit;
            }
        } else {
            $this->session->set_userdata(array('error_message' => display('already_exists')));
            if (isset($_POST['add-retailer'])) {
                redirect(base_url('dashboard/Cretailer/manage_retailer'));
                exit;
            } elseif (isset($_POST['add-retailer-another'])) {
                redirect(base_url('dashboard/Cretailer'));
                exit;
            }
        }
    }

    //Supplier Update Form
    public function retailer_update_form($retailer_id)
    {
		$string = urldecode($retailer_id);
		$string = str_replace(' ', '', $string);
        $this->permission->check_label('manage_retailer')->update()->redirect();
        $content = $this->lretailer->retailer_edit_data($string);
        $this->template_lib->full_admin_html_view($content);
    }

    // Supplier Update
    public function retailer_update()
    {
        $this->permission->check_label('manage_retailer')->update()->redirect();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('company_name', display('company_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('adress', display('adress'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('phone', display('phone'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->session->set_userdata(array('error_message' => 'fields_must_not_be_empty'));
            $retailer_id = $this->input->post('retailer_id', TRUE);
            $this->retailer_update_form($retailer_id);
        } else {
            $retailer_id = $this->input->post('retailer_id', TRUE);
            $data = array(
                'company_name' => $this->input->post('company_name', TRUE),
                'adress'      => $this->input->post('adress', TRUE),
                'email'        => $this->input->post('email', TRUE),
				'password' 	=> md5("gef" . $this->input->post('password', TRUE)),
                'phone'       => $this->input->post('phone', TRUE),
                'city'        => $this->input->post('city', TRUE),
                'country'       => $this->input->post('country', TRUE),
                'website' => $this->input->post('website', TRUE),
                'bussiness_field' => $this->input->post('bussiness_field', TRUE),
                // 'sell_products' => $this->input->post('sell_products', TRUE),
                // 'status'       => 1,
            );
            $this->Retailers->update_retailer($data, $retailer_id);
            $this->session->set_userdata(array('message' => display('successfully_updated')));
            redirect(base_url('dashboard/Cretailer/manage_retailer'));
            exit;
        }
    }

    // \Retailer Delete from System
    public function retailer_delete($retailer_id)
    {
        $this->permission->check_label('manage_retailer')->delete()->redirect();

        $result = $this->Retailers->delete_retailer($retailer_id);
        if ($result) {
            $this->session->set_userdata(array('message' => display('successfully_delete')));
            redirect(base_url('dashboard/Cretailer/manage_retailer'));
        }
    }

    // Supplier details findings
    public function supplier_details($supplier_id)
    {
        $this->permission->check_label('manage_supplier')->read()->redirect();

        $content = $this->lsupplier->supplier_detail_data($supplier_id);
        $this->supplier_id = $supplier_id;
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