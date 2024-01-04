<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Csupplier extends MX_Controller
{

    public $supplier_id;

    function __construct()
    {
        parent::__construct();
        $this->load->library('auth');
        $this->load->library('dashboard/lsupplier');
        $this->load->library('session');
        $this->load->model('dashboard/Suppliers');
        $this->auth->check_user_auth();
    }

    public function index()
    {
        $this->permission->check_label('add_supplier')->create()->redirect();

        $content = $this->lsupplier->supplier_add_form();
        $this->template_lib->full_admin_html_view($content);
    }

    //Supplier Search Item
    public function supplier_search_item()
    {
        $supplier_id = $this->input->post('supplier_id', TRUE);
        $content = $this->lsupplier->supplier_search_item($supplier_id);

        $this->template_lib->full_admin_html_view($content);
    }

    //Product Add Form
    public function manage_supplier()
    {
        $this->permission->check_label('manage_supplier')->read()->redirect();
        $content = $this->lsupplier->supplier_list();
        $this->template_lib->full_admin_html_view($content);
    }

    //Insert Product and uload
    public function insert_supplier()
    {


        $this->permission->check_label('add_supplier')->create()->redirect();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('supplier_name', display('supplier_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', display('address'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('mobile', display('mobile'), 'trim|required|xss_clean');
		
		//$this->form_validation->set_rules('account_no', display('account_no'), 'required');
        if ($this->form_validation->run() == false) {
            $this->session->set_userdata(array('error_message' => 'fields_must_not_be_empty'));
            $this->index();
        } else {
			$data = array(
            'mobile'       => $this->input->post('mobile', TRUE),
            'bank_account' => $this->input->post('bank_account', TRUE),
        );

        // Check if the supplier already exists
        if ($this->isSupplierExist($data)) {
            // If it exists, set an error message and redirect
            $errorMessage = 'Supplier already exists with the following details: ';
            $errorMessage .= 'Mobile: ' . $data['mobile'] . ', ';
            $errorMessage .= 'Bank Account: ' . $data['bank_account'];

            $this->session->set_userdata('error_message', $errorMessage);
            redirect(base_url('dashboard/Csupplier/manage_supplier'));
            exit;
        }
            $supplier_id = $this->auth->generator(20);
            $data = array(
                'supplier_id'  => $supplier_id,
                'supplier_name' => $this->input->post('supplier_name', TRUE),
                'address'      => $this->input->post('address', TRUE),
                'email'        => $this->input->post('email', TRUE),
                'vat_no'       => $this->input->post('vat_no', TRUE),
                'cin'        => $this->input->post('cin', TRUE),
                'pan'        => $this->input->post('pan', TRUE),
                'cfssai'        => $this->input->post('cfssai', TRUE),
                'sfssai'        => $this->input->post('sfssai', TRUE),
                'mobile'       => $this->input->post('mobile', TRUE),
                'details'      => $this->input->post('details', TRUE),
                'previous_balance' => $this->input->post('previous_balance', TRUE),
                'bank_account'        => $this->input->post('bank_account', TRUE),
                'account_no'        => $this->input->post('account_no', TRUE),
                'ifsc'        => $this->input->post('ifsc', TRUE),
                'account_type'        => $this->input->post('account_type', TRUE),
                'bank_name'        => $this->input->post('bank_name', TRUE),
                'branch'        => $this->input->post('branch', TRUE),
                'status'       => 1
            );
            $supplier = $this->Suppliers->supplier_entry($data);
        }
		
        if ($supplier == TRUE) {
			
            $this->session->set_userdata(array('message' => display('successfully_added')));
            if (isset($_POST['add-supplier'])) {
                redirect(base_url('dashboard/Csupplier/manage_supplier'));
                exit;
            } elseif (isset($_POST['add-supplier-another'])) {
                redirect(base_url('dashboard/Csupplier'));
                exit;
            }
        } else {
			
            $this->session->set_userdata(array('error_message' => display('already_exists')));
            if (isset($_POST['add-supplier'])) {
                redirect(base_url('dashboard/Csupplier/manage_supplier'));
                exit;
            } elseif (isset($_POST['add-supplier-another'])) {
                redirect(base_url('dashboard/Csupplier'));
                exit;
            }
        }
    }
private function isSupplierExist($data)
{
    $this->db->select('*');
    $this->db->from('supplier_information');
    $this->db->where('mobile', $data['mobile']);
    $this->db->or_where('bank_account', $data['bank_account']);

    $query = $this->db->get();
    return ($query->num_rows() > 0);
}
    //Supplier Update Form
    public function supplier_update_form($supplier_id)
    {
        $this->permission->check_label('manage_supplier')->update()->redirect();
        $content = $this->lsupplier->supplier_edit_data($supplier_id);
        $this->template_lib->full_admin_html_view($content);
    }

    // Supplier Update
    public function supplier_update()
    {
        $this->permission->check_label('manage_supplier')->update()->redirect();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('supplier_name', display('supplier_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', display('address'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('mobile', display('mobile'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->session->set_userdata(array('error_message' => 'fields_must_not_be_empty'));
            $supplier_id = $this->input->post('supplier_id', TRUE);
            $this->supplier_update_form($supplier_id);
        } else {
            $supplier_id = $this->input->post('supplier_id', TRUE);
            $data = array(
                'supplier_name' => $this->input->post('supplier_name', TRUE),
                'address'      => $this->input->post('address', TRUE),
                'email'        => $this->input->post('email', TRUE),
                'vat_no'       => $this->input->post('vat_no', TRUE),
                'cin'        => $this->input->post('cin', TRUE),
                'pan'        => $this->input->post('pan', TRUE),
                'cfssai'        => $this->input->post('cfssai', TRUE),
                'sfssai'        => $this->input->post('sfssai', TRUE),
                'mobile'       => $this->input->post('mobile', TRUE),
                'details'      => $this->input->post('details', TRUE),
                 'bank_account'        => $this->input->post('bank_account', TRUE),
                'account_no'        => $this->input->post('account_no', TRUE),
                'ifsc'        => $this->input->post('ifsc', TRUE),
                'account_type'        => $this->input->post('account_type', TRUE),
                'bank_name'        => $this->input->post('bank_name', TRUE),
                'branch'        => $this->input->post('branch', TRUE),
            );
            

            $this->Suppliers->update_supplier($data, $supplier_id);
            $this->session->set_userdata(array('message' => display('successfully_updated')));
            redirect(base_url('dashboard/Csupplier/manage_supplier'));
            exit;
        }
    }

    // Supplier Delete from System
    public function supplier_delete($supplier_id)
    {
        $this->permission->check_label('manage_supplier')->delete()->redirect();

        $result = $this->Suppliers->delete_supplier($supplier_id);
        if ($result) {
            $this->session->set_userdata(array('message' => display('successfully_delete')));
            redirect(base_url('dashboard/Csupplier/manage_supplier'));
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
        // var_dump($content);
        // die();
        $this->template_lib->full_admin_html_view($content);
    }
      public function supplier_tax_report()
    {
        $this->permission->check_label('supplier_tax_report')->read()->redirect();

       
        $content = $this->lsupplier->supplier_tax_report();
        $this->template_lib->full_admin_html_view($content);
    }
    
     public function add_supplier_csv()
    {
        $this->permission->check_label('import_supplier_csv')->create()->redirect();
        $data = array(
            'title' => display('import_supplier_csv')
        );
        $content = $this->parser->parse('dashboard/supplier/add_supplier_csv', $data, true);
        $this->template_lib->full_admin_html_view($content);
    }

    function uploadCsv()
    {
        $this->permission->check_label('import_supplier_csv')->create()->redirect();
        $count = 0;
        $fp = fopen($_FILES['upload_csv_file']['tmp_name'], 'r') or die("can't open file");

        if (($handle = fopen($_FILES['upload_csv_file']['tmp_name'], 'r')) !== FALSE) {

            while ($csv_line = fgetcsv($fp, 1024)) {
                //keep this if condition if you want to remove the first row
                for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                    $insert_csv = array();
                    $insert_csv['supplier_name'] = (!empty($csv_line[0]) ? $csv_line[0] : '');
                    $insert_csv['mobile'] = (!empty($csv_line[1]) ? $csv_line[1] : '');
                    $insert_csv['email'] = (!empty($csv_line[2]) ? $csv_line[2] : '');
                    $insert_csv['vat_no'] = (!empty($csv_line[3]) ? $csv_line[3] : '');
                    $insert_csv['cin'] = (!empty($csv_line[4]) ? $csv_line[4] : '');
                    $insert_csv['pan'] = (!empty($csv_line[5]) ? $csv_line[5] : '');
                    $insert_csv['cfssai'] = (!empty($csv_line[6]) ? $csv_line[6] : '');
                    $insert_csv['sfssai'] = (!empty($csv_line[7]) ? $csv_line[7] : '');
                    $insert_csv['address'] = (!empty($csv_line[8]) ? $csv_line[8] : '');
                    $insert_csv['details'] = (!empty($csv_line[9]) ? $csv_line[9] : '');
                    $insert_csv['previous_balance'] = (!empty($csv_line[10]) ? $csv_line[10] : '');
                    $insert_csv['bank_account'] = (!empty($csv_line[11]) ? $csv_line[11] : '');
                    $insert_csv['account_no'] = (!empty($csv_line[12]) ? $csv_line[12] : '');
                    $insert_csv['ifsc'] = (!empty($csv_line[13]) ? $csv_line[13] : '');
                    $insert_csv['account_type'] = (!empty($csv_line[14]) ? $csv_line[14] : '');
                    $insert_csv['bank_name'] = (!empty($csv_line[15]) ? $csv_line[15] : '');
                    $insert_csv['branch'] = (!empty($csv_line[16]) ? $csv_line[16] : '');
                    $insert_csv['status'] = (!empty($csv_line[17]) ? $csv_line[17] : 0);
                }
           
                //Data organizaation for insert to database
            $supplier_id = $this->auth->generator(20);
                $data = array(
                    'supplier_id' => $supplier_id,
                    'supplier_name' => $insert_csv['supplier_name'],
                    'mobile' => $insert_csv['mobile'],
                    'email' => $insert_csv['email'],
                    'vat_no' => $insert_csv['vat_no'],
                    'cin' => $insert_csv['cin'],
                    'pan' => $insert_csv['pan'],
                    'cfssai' => $insert_csv['cfssai'],
                    'sfssai' => $insert_csv['sfssai'],
                    'address' =>  $insert_csv['address'],
                    'details' => $insert_csv['details'],
                    'previous_balance' => $insert_csv['previous_balance'],
                    'bank_account' => $insert_csv['bank_account'],
                    'account_no' => $insert_csv['account_no'],
                    'ifsc' => $insert_csv['ifsc'],
                    'account_type' => $insert_csv['account_type'],
                    'bank_name' => $insert_csv['bank_name'],
                    'branch' => $insert_csv['branch'],
                    'status' => $insert_csv['status']
                );
//  var_dump( $data['supplier_id']);
//             var_dump( $data['supplier_name']);
//             var_dump( $data);
//             die();

                if ($count > 0) {
                    // echo '1';
                    // die();
                    $result = $this->db->select('*')
                        ->from('supplier_information')
                        ->where('supplier_name', $data['supplier_name'])
                        ->get()
                        ->num_rows();

                    if ($result == 0 && !empty($data['supplier_name']) && !empty($data['mobile']) && !empty($data['cin']) && !empty($data['address'])) {
//  echo '2';
//                     die();
                        $this->db->insert('supplier_information', $data);

                        $this->db->select('*');
                        $this->db->from('supplier_information');
                        $this->db->where('status', 1);
                        $query = $this->db->get();
                    } else {

                        $this->db->where('supplier_name', $data['supplier_name']);
                        // $this->db->where('product_model', $data['product_model']);
                        $this->db->update('supplier_information', $data);

                        $this->db->select('*');
                        $this->db->from('supplier_information');
                        $this->db->where('status', 1);
                        $query = $this->db->get();

                    }

                }

                $count++;
            }
        }

        fclose($fp) or die("can't close file");
        $this->session->set_userdata(array('message' => display('successfully_added')));

        if (isset($_POST['add-supplier'])) {
            redirect(base_url('dashboard/Csupplier/manage_supplier'));
            exit;
        } elseif (isset($_POST['add-supplier-another'])) {
            redirect(base_url('dashboard/Csupplier'));
            exit;
        }
    }
    
}