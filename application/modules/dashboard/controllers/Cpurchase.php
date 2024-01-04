<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cpurchase extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->auth->check_user_auth();
        $this->load->model(array(
            'dashboard/Suppliers',
            'dashboard/Purchases',
            'dashboard/Stores',
            'dashboard/Variants',
            'dashboard/Soft_settings',
            'template/Template_model',
'dashboard/Categories', 
            'dashboard/Brands',
            'dashboard/Units',
            'dashboard/cfiltration_model'
        ));
        // $this->load->library('dashboard/lpurchase');
        $this->load->library('dashboard/occational');      
        
    }
    //Default index function loading
    public function index()
    {
        if (check_module_status('accounting') == 1) {
            $find_active_fiscal_year = $this->db->select('*')->from('acc_fiscal_year')->where('status', 1)->get()->row();
            if (!empty($find_active_fiscal_year)) {
                $this->permission->check_label('add_purchase')->create()->redirect();
                $all_supplier = $this->Purchases->select_all_supplier();
                $store_list   = $this->Stores->store_list();
                $get_def_store = $this->Stores->get_def_store();
                $variant_list = $this->Variants->variant_list();
                $bank_list    = $this->db->select('bank_id,bank_name')->from('bank_list')->get()->result();
                $batch_no     = $this->generator(7);
                $data = array(
                    'title'       => display('add_purchase'),
                    'all_supplier' => $all_supplier,
                    'store_list'  => $store_list,
                    'def_store'   => $get_def_store,
                    'variant_list' => $variant_list,
                    'batch_no'    => $batch_no,
                    'bank_list'   => $bank_list
                );
                $data['setting'] = $this->Template_model->setting();
                $data['module'] = "dashboard";
                $data['page']   = 'purchase/add_purchase_form';
                $this->parser->parse('template/layout', $data);
            } else {
                $this->session->set_userdata(array('error_message' => display('no_active_fiscal_year_found')));
                redirect(base_url('Admin_dashboard'));
            }
        } else {
            $this->permission->check_label('add_purchase')->create()->redirect();
            $all_supplier = $this->Purchases->select_all_supplier();
            $store_list   = $this->Stores->store_list();
            $get_def_store = $this->Stores->get_def_store();
            $variant_list = $this->Variants->variant_list();
            $batch_no     = $this->generator(7);
            $data = array(
                'title'       => display('add_purchase'),
                'all_supplier' => $all_supplier,
                'store_list'  => $store_list,
                'def_store'   => $get_def_store,
                'variant_list' => $variant_list,
                'batch_no'    => $batch_no,
            );
            $data['setting'] = $this->Template_model->setting();
            $data['module'] = "dashboard";
            $data['page']   = 'purchase/add_purchase_form';
            $this->parser->parse('template/layout', $data);
        }
    }
    //This function is used to Generate Key
    public function generator($lenth)
    {
        $number = array("6", "2", "9", "4", "5", "1", "8", "7", "3", "0");
        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 8);
            $rand_number = $number["$rand_value"];
            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }
    //Purchase Add Form
    public function manage_purchase()
    {
        $this->permission->check_label('manage_purchase')->read()->redirect();

        $purchases_list = $this->Purchases->purchase_list();
        if (!empty($purchases_list)) {
            $j = 0;
            foreach ($purchases_list as $k => $v) {
                    $purchases_list[$k]['final_date'] = $this->occational->dateConvert($purchases_list[$j]['purchase_date']);
                    // var_dump($purchases_list[0]['final_date']);
                    // die();
                $j++;
            }

            $i = 0;
            foreach ($purchases_list as $k => $v) {
                $i++;
                $purchases_list[$k]['sl'] = $i;
            }
        }
        $currency_details = $this->Soft_settings->retrieve_currency_info();
        $data = array(
            'title' => display('manage_purchase'),
            'purchases_list' => $purchases_list,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
        );

        $data['setting'] = $this->Template_model->setting();
        $data['module'] = "dashboard";
        $data['page'] = 'purchase/purchase';
        $this->parser->parse('template/layout', $data);
    }
    //Insert Purchase and uload
    public function insert_purchase()
    {
        if (check_module_status('accounting') == 1) {
            
            $find_active_fiscal_year = $this->db->select('*')->from('acc_fiscal_year')->where('status', 1)->get()->row();

            if (!empty($find_active_fiscal_year)) {
                            // var_dump($find_active_fiscal_year);         
                            // die();

                $this->permission->check_label('add_purchase')->create()->redirect();
                $this->Purchases->purchase_entry();
                $this->session->set_userdata(array('message' => display('successfully_added')));
                if (isset($_POST['add-purchase'])) {
                    redirect(base_url('dashboard/Cpurchase/manage_purchase'));
                } elseif (isset($_POST['add-purchase-another'])) {
                    redirect(base_url('dashboard/Cpurchase'));
                }
            }
        } else {
            
           
            $this->permission->check_label('add_purchase')->create()->redirect();
            $this->Purchases->purchase_entry();
            $this->session->set_userdata(array('message' => display('successfully_added')));
            if (isset($_POST['add-purchase'])) {
                redirect(base_url('dashboard/Cpurchase/manage_purchase'));
            } elseif (isset($_POST['add-purchase-another'])) {
                redirect(base_url('dashboard/Cpurchase'));
            }
        }
    }
    //Purchase Update Form
    public function purchase_update_form($purchase_id)
    {
        $this->permission->check_label('manage_purchase')->update()->redirect();
        $purchase_detail  = $this->Purchases->retrieve_purchase_editdata($purchase_id);
        // var_dump($purchase_detail);
        // die();
        $supplier_id      = $purchase_detail[0]['supplier_id'];
        $supplier_list    = $this->Suppliers->supplier_list();
        $supplier_selected = $this->Suppliers->supplier_search_item($supplier_id);
        $this->load->model('Wearhouses');
        $wearhouse_list   = $this->Wearhouses->wearhouse_list();
        $store_list       = $this->Stores->store_list();
        $variant_list     = $this->Variants->variant_list();
        $bank_list        = $this->db->select('bank_id,bank_name')->from('bank_list')->get()->result();
        $batch_no         = $this->generator(7);
        if (!empty($purchase_detail)) {
            $i = 0;
            foreach ($purchase_detail as $k => $v) {
                $i++;
                $purchase_detail[$k]['sl'] = $i;
            }
        }

        $proof_of_purchase_expese = $this->db->select('*')->from('proof_of_purchase_expese')->where('purchase_id', $purchase_id)->get()->result_array();
        $total_purchase_expense   = $this->Purchases->total_purchase_expense($purchase_id);
        $data = array(
            'title'                   => display('purchase_edit'),
            'purchase_id'             => $purchase_detail[0]['purchase_id'],
            'invoice_no'              => $purchase_detail[0]['invoice_no'],
            'supplier_name'           => $purchase_detail[0]['supplier_name'],
            'supplier_id'             => $purchase_detail[0]['supplier_id'],
            'sgst'             => $purchase_detail[0]['sgst'],
            'cgst'             => $purchase_detail[0]['cgst'],
            'igst'             => $purchase_detail[0]['igst'],

            'grand_total'             => $purchase_detail[0]['grand_total_amount'],
            'purchase_details'        => $purchase_detail[0]['purchase_details'],
            'purchase_date'           => $purchase_detail[0]['purchase_date'],
            'store_id'                => $purchase_detail[0]['store_id'],
            'wearhouse_id'            => $purchase_detail[0]['wearhouse_id'],
            'variant_id'              => $purchase_detail[0]['variant_id'],
            'purchase_info'           => $purchase_detail,
            'supplier_list'           => $supplier_list,
            'supplier_selected'       => $supplier_selected,
            'wearhouse_list'          => $wearhouse_list,
            'store_list'              => $store_list,
            'variant_list'            => $variant_list,
            'proof_of_purchase_expese' => $proof_of_purchase_expese,
            'total_purchase_expense'  => $total_purchase_expense,
            'batch_no'                => $batch_no,
            'bank_list'               => $bank_list
        );
        // var_dump($data);
        // die();
        $data['setting'] = $this->Template_model->setting();
        $data['module'] = "dashboard";
        $data['page']   = 'purchase/edit_purchase_form';
        $this->parser->parse('template/layout', $data);
    }
    //Purchase Update
    public function purchase_update()
    {
        $this->permission->check_label('manage_purchase')->update()->redirect();
        $this->Purchases->update_purchase();
        $this->session->set_userdata(array('message' => display('successfully_updated')));
        redirect(base_url('dashboard/Cpurchase/manage_purchase'));
    }
    // Purchase delete
    public function purchase_delete($purchase_id)
    {
        $this->permission->check_label('manage_purchase')->delete()->redirect();
        $this->Purchases->delete_purchase($purchase_id);
        $this->session->set_userdata(array('message' => display('successfully_delete')));
        redirect('dashboard/Cpurchase/manage_purchase');
    }
    //Purchase item by search
    public function purchase_item_by_search()
    {
        $supplier_id = $this->input->post('supplier_id', TRUE);
        $purchases_list = $this->Purchases->purchase_by_search($supplier_id);
        $j = 0;
        if (!empty($purchases_list)) {
            foreach ($purchases_list as $k => $v) {
                $purchases_list[$k]['final_date'] = $this->occational->dateConvert($purchases_list[$j]['purchase_date']);
                                // $purchases_list[$k]['final_date'] = $this->occational->dateConvert($purchases_list[$j]['purchase_date']);

                $j++;
            }
            $i = 0;
            foreach ($purchases_list as $k => $v) {
                $i++;
                $purchases_list[$k]['sl'] = $i;
            }
        }
        $data = array(
            'purchases_list' => $purchases_list
        );
        $data['setting'] = $this->Template_model->setting();
        $data['module'] = "dashboard";
        $data['page'] = 'purchase/purchase';
        $this->parser->parse('template/layout', $data);
    }
    //Purchase search by supplier id
    public function product_search_by_supplier()
    {
        $supplier_id = $this->input->post('supplier_id', TRUE);
        $product_name = $this->input->post('product_name', TRUE);
        $product_info = $this->Suppliers->product_search_item($supplier_id, $product_name);
        $json_product = [];
        foreach ($product_info as $value) {
            $json_product[] = array('label' => $value['product_name'] . '-(' . $value['product_model'] . ')', 'value' => $value['product_id']);
        }
        echo json_encode($json_product);
    }
    // Retrieve Purchase Data
    public function retrieve_product_data()
    {
        $product_id = $this->input->post('product_id', TRUE);
        $product_info = $this->Purchases->get_total_product($product_id);
        echo json_encode($product_info);
    }
    //Retrive right now inserted data to cretae html
    public function purchase_details_data($purchase_id)
    {
        $purchase_detail = $this->Purchases->purchase_details_data($purchase_id);
        if (!empty($purchase_detail)) {
            $i = 0;
            foreach ($purchase_detail as $k => $v) {
                $i++;
                $purchase_detail[$k]['sl'] = $i;
            }
            foreach ($purchase_detail as $k => $v) {
                $purchase_detail[$k]['convert_date'] = $this->occational->dateConvert($purchase_detail[$k]['purchase_date']);
                // var_dump(  $purchase_detail[$k]['convert_date']);
                // die();
            }
        }
        $currency_details = $this->Soft_settings->retrieve_currency_info();
        $company_info = $this->Purchases->retrieve_company();
        $data = array(
            'title'            => display('purchase_ledger'),
            'purchase_id'      => $purchase_detail[0]['purchase_id'],
            'purchase_details' => $purchase_detail[0]['purchase_details'],
            'supplier_name'    => $purchase_detail[0]['supplier_name'],
            'final_date'       => $purchase_detail[0]['convert_date'],
            'purchase_date'       => $purchase_detail[0]['purchase_date'],

            'sub_total_amount' => $purchase_detail[0]['grand_total_amount'],
            'invoice_no'       => $purchase_detail[0]['invoice_no'],
            'purchase_all_data' => $purchase_detail,
            'company_info'     => $company_info,
            'currency'         => $currency_details[0]['currency_icon'],
            'position'         => $currency_details[0]['currency_position'],
        );
        $data['setting'] = $this->Template_model->setting();
        $data['module'] = "dashboard";
        $data['page'] = 'purchase/purchase_detail';
        $this->parser->parse('template/layout', $data);
    }
    // Get variant price and stock 
    public function check_admin_2d_variant_info()
    {
        $product_id   = urldecode($this->input->post('product_id', TRUE));
        $store_id     = urldecode($this->input->post('store_id', TRUE));
        $variant_id   = urldecode($this->input->post('variant_id', TRUE));
        $variant_color = urldecode($this->input->post('variant_color', TRUE));
        $stock        = $this->Purchases->check_variant_wise_stock($product_id, $store_id, $variant_id, $variant_color);
		//var_dump($stock);die;
       /*  if ($stock > 0) { */
            $result[0] = "yes";
            $price = $this->Purchases->check_variant_wise_price($product_id, $variant_id, $variant_color);
            $batch = $this->Purchases->check_batch_wise_product($product_id, $variant_id, $variant_color, $store_id);
            // var_dump($batch);
            // die();
            $result[1] = $stock; //stock
            $result[2] = floatval($price['price']); //price
            $result[3] = 0; //discount
            if (!empty($batch)) {
                $result[4] = $batch; //discount
            } else {
                $batch2 = $this->Purchases->check_batch_wise_transfer_product($product_id, $variant_id, $variant_color, $store_id);
                $result[4] = $batch2;
            }
        /* } else {
            $result[0] = 'no';
        } */
        echo json_encode($result);
    }

    public function check_admin_batch_wise_stock_info()
    {
        $product_id    = urldecode($this->input->post('product_id', TRUE));
        $store_id      = urldecode($this->input->post('store_id', TRUE));
        $variant_id    = urldecode($this->input->post('variant_id', TRUE));
        $variant_color = urldecode($this->input->post('variant_color', TRUE));
        $batch_no      = urldecode($this->input->post('batch_no', TRUE));
        $stock         = $this->Purchases->check_batch_no_wise_stock($product_id, $store_id, $variant_id, $variant_color, $batch_no);
        if ($stock > 0) {
            $result[0] = "yes";
            $result[1] = $stock; //stock
        } else {
            $result[0] = 'no';
        }
        echo json_encode($result);
    }
    public function check_pos_batch_wise_stock_info()
    {
        $product_id = urldecode($this->input->post('product_id', TRUE));
        $store_id  = urldecode($this->input->post('store_id', TRUE));
        $batch_no  = urldecode($this->input->post('batch_no', TRUE));
        $stock     = $this->Purchases->check_pos_batch_no_wise_stock($product_id, $store_id, $batch_no);
       //var_dump($stock);die;
        if ($stock > 0) {
            $result[0] = "yes";
            $result[1] = $stock; //stock
        } else {
            $result[0] = 'no';
        }
        echo json_encode($result);
    }
    //Stock in available
    public function available_stock()
    {
        $product_id   = $this->input->post('product_id', TRUE);
        $store_id     = $this->input->post('store_id', TRUE);
        $variant_id   = $this->input->post('variant_id', TRUE);
        $variant_color = $this->input->post('variant_color', TRUE);

        $this->db->select('SUM(a.quantity) as total_purchase');
        $this->db->from('transfer a');
        $this->db->where('a.product_id', $product_id);
        $this->db->where('a.store_id', $store_id);
        $this->db->where('a.variant_id', $variant_id);
        if (!empty($variant_color)) {
            $this->db->where('a.variant_color', $variant_color);
        }
        $total_purchase = $this->db->get()->row();

        $this->db->select('b.quantity');
        $this->db->from('invoice_stock_tbl b');
        $this->db->where('b.product_id', $product_id);
        $this->db->where('b.store_id', $store_id);
        $this->db->where('b.variant_id', $variant_id);
        if (!empty($variant_color)) {
            $this->db->where('b.variant_color', $variant_color);
        }
        $total_sale = $this->db->get()->row();

        echo $total_purchase->quantity - $total_sale->total_sale;
    }


    //check stock product quantity
    public function check_product_stock()
    {

        $product_id = $this->input->post('product_id', TRUE);
        $variant_id = $this->input->post('variant_id', TRUE);
        $store_id = $this->input->post('store_id', TRUE);
        $variant_color = $this->input->post('variant_color', TRUE);

        $this->db->select('SUM(a.quantity) as total_purchase');
        $this->db->from('transfer a');
        $this->db->where('a.product_id', $product_id);
        $this->db->where('a.variant_id', $variant_id);
        if (!empty($variant_color)) {
            $this->db->where('a.variant_color', $variant_color);
        }
        $this->db->where('a.store_id', $store_id);
        $total_purchase = $this->db->get()->row();

        $this->db->select('SUM(b.quantity) as total_sale');
        $this->db->from('invoice_details b');
        $this->db->where('b.product_id', $product_id);
        $this->db->where('b.variant_id', $variant_id);
        if (!empty($variant_color)) {
            $this->db->where('b.variant_color', $variant_color);
        }
        $this->db->where('b.store_id', $store_id);
        $total_sale = $this->db->get()->row();

        echo $total_purchase->total_purchase - $total_sale->total_sale;
    }


    //Wearhouse available stock check
    public function wearhouse_available_stock()
    {

        $product_id = $this->input->post('product_id', TRUE);
        $variant_id = $this->input->post('variant_id', TRUE);
        $variant_color = $this->input->post('variant_color', TRUE);
        $store_id = $this->input->post('store_id', TRUE);
        $purchase_to = $this->input->post('purchase_to', TRUE);

        $this->db->select('SUM(a.quantity) as total_purchase');
        $this->db->from('transfer a');
        $this->db->where('a.product_id', $product_id);
        $this->db->where('a.variant_id', $variant_id);
        if (!empty($variant_color)) {
            $this->db->where('a.variant_color', $variant_color);
        }
        $this->db->where('a.store_id', $store_id);
        $total_purchase = $this->db->get()->row();

        $this->db->select('SUM(b.quantity) as total_sale');
        $this->db->from('invoice_details b');
        $this->db->where('b.product_id', $product_id);
        $this->db->where('b.variant_id', $variant_id);
        if (!empty($variant_color)) {
            $this->db->where('b.variant_color', $variant_color);
        }
        $this->db->where('b.store_id', $store_id);
        $total_sale = $this->db->get()->row();

				$sales = $this->db->select("
					sum(quantity) as totalSalesQnty,
					quantity
					")
				->from('invoice_stock_tbl')
				->where('product_id', $product_id)
				->get()
				->row();
					
		$stok_report =  $sales->totalSalesQnty;
		
        $stock = $total_purchase->total_purchase - $total_sale->total_sale;
			//var_dump($total_sale->total_sale);die;
			
		$this->db->select('*');
		$this->db->from('stock_adjustment_details sd');
		$this->db->where('sd.product_id', $product_id);
		$this->db->where('sd.variant_id', $variant_id);
		$this->db->order_by("id", "desc");
		if (!empty($variant_color)) {
			$this->db->where('sd.color_variant', $variant_color); // Change "or_where" to "where" here
		}
		$update_stock = $this->db->get()->row();
		if($update_stock->adjustment_type == 'increase')
		{
			$qty = (float) $update_stock->adjustment_quantity + (float) $update_stock->previous_quantity;
		}else{
			$qty = (float) $update_stock->adjustment_quantity - (float) $update_stock->previous_quantity;
		}
		
		$quantity  = abs($qty);
		//echo $stock.' '.$quantity;die;
        if ($stock > 0) {
            $result[0] = "yes";
            $batch = $this->Purchases->check_batch_wise_product($product_id, $variant_id, $variant_color, $store_id);
            $result['stock'] = $stock; //stock
            $result[4] = $batch; //discount

        } else {
            $result[0] = 'no';
        }
        echo json_encode($result);
    }

    // check default store is or not
    public function check_default_store()
    {
        $store_id =  $this->input->post('store_id', TRUE);
        $result = false;
        if (!empty($store_id)) {
            $this->db->where('store_id', $store_id);
            $this->db->where('default_status', 1);
            $query = $this->db->get('store_set');
            if ($query->num_rows() > 0) {
                $result = TRUE;
            }
        }
        echo $result;
    }

    // Add purchase form
    public function add_purchase_order()
    {
        $this->permission->check_label('create_purchase_order')->create()->redirect();

        $this->form_validation->set_rules('supplier_id', display('supplier'), 'trim|required');
        $this->form_validation->set_rules('purchase_order', display('purchase_order'), 'trim|required');
        $this->form_validation->set_rules('store_id', display('purchase_to'), 'trim|required');

        $purchase_id = $this->Purchases->get_next_pur_order_id();

        if ($this->form_validation->run() == TRUE) {
            $result = $this->Purchases->purchase_order_entry($purchase_id);
            if ($result) {
                $this->session->set_userdata(array('message' => display('successfully_added')));
                if (isset($_POST['add-purchase'])) {
                    redirect(base_url('dashboard/Cpurchase/purchase_order'));
                }
            } else {
                $this->session->set_userdata(array('error_message' => display('failed_try_again')));
            }
        }
        $all_supplier = $this->Purchases->select_all_supplier();
        $store_list   = $this->Stores->store_list();
        $get_def_store = $this->Stores->get_def_store();
        $variant_list = $this->Variants->variant_list();
        $batch_no     = $this->generator(7);
        $data = array(
            'title'            => display('add_purchase_order'),
            'all_supplier'     => $all_supplier,
            'store_list'       => $store_list,
            'def_store'        => $get_def_store,
            'variant_list'     => $variant_list,
            'purchase_order_no' => "PO-" . $purchase_id,
            'batch_no'         => $batch_no,
        );
        $data['setting'] = $this->Template_model->setting();
        $data['module'] = "dashboard";
        $data['page'] = 'purchase/purchase_order_add';
        $this->parser->parse('template/layout', $data);
    }

    //Purchase Order
    public function purchase_order()
    {
        $this->permission->check_label('purchase_order')->read()->redirect();

        $order_list = $this->Purchases->get_purchase_order_list();

        $currency_details = $this->Soft_settings->retrieve_currency_info();
        $data = array(
            'title' => display('create_purchase_order'),
            'order_list' => $order_list,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
        );

        $data['setting'] = $this->Template_model->setting();
        $data['module'] = "dashboard";
        $data['page'] = 'purchase/purchase_order';
        $this->parser->parse('template/layout', $data);
    }

    // Add purchase form
    public function edit_purchase_order($pur_order_id)
    {
        $this->permission->check_label('purchase_order')->update()->redirect();

        $this->form_validation->set_rules('supplier_id', display('supplier'), 'trim|required');
        $this->form_validation->set_rules('purchase_order', display('purchase_order'), 'trim|required');
        $this->form_validation->set_rules('store_id', display('purchase_to'), 'trim|required');

        $purchase_detail = $this->Purchases->get_purchase_order_by_id($pur_order_id);

        if ($this->form_validation->run() == TRUE) {

            $result = $this->Purchases->purchase_order_update($pur_order_id);
            if ($result) {
                $this->session->set_userdata(array('message' => display('successfully_added')));

                if (isset($_POST['add-purchase'])) {
                    redirect(base_url('dashboard/Cpurchase/purchase_order'));
                }
            } else {
                $this->session->set_userdata(array('error_message' => display('failed_try_again')));
            }
        }
        $all_supplier = $this->Suppliers->supplier_list();
        $store_list = $this->Stores->store_list();
        $data = array(
            'title' => display('edit_purchase_order'),
            'pur_order_id'     => $pur_order_id,
            'pur_order_no'     => $purchase_detail[0]['pur_order_no'],
            'supplier_name'    => $purchase_detail[0]['supplier_name'],
            'supplier_id'      => $purchase_detail[0]['supplier_id'],
            'grand_total'      => $purchase_detail[0]['grand_total_amount'],
            'purchase_details' => $purchase_detail[0]['purchase_details'],
            'purchase_date'    => $purchase_detail[0]['purchase_date'],
            'store_id'         => $purchase_detail[0]['store_id'],
            'variant_id'       => $purchase_detail[0]['variant_id'],
            'purchase_info'    => $purchase_detail,
            'all_supplier'     => $all_supplier,
            'store_list'       => $store_list,
        );

        $data['setting'] = $this->Template_model->setting();
        $data['module'] = "dashboard";
        $data['page'] = 'purchase/purchase_order_edit';
        $this->parser->parse('template/layout', $data);
    }

    // Purchase order delete
    public function purchase_order_delete($purchase_order_id)
    {
        $this->permission->check_label('purchase_order')->delete()->redirect();
        $result = $this->Purchases->delete_purchase_order($purchase_order_id);
        if ($result) {
            $this->session->set_userdata(array('message' => display('successfully_delete')));
        } else {
            $this->session->set_userdata(array('error_message' => display('failed_try_again')));
        }
        redirect('dashboard/Cpurchase/purchase_order');
    }

    public function purchase_order_print($pur_order_id)
    {
        $purchase_detail = $this->Purchases->get_po_shortinfo_by_id($pur_order_id);

        $po_details = $this->Purchases->get_purchase_order_details($pur_order_id);
        $currency_details = $this->Soft_settings->retrieve_currency_info();
        $company_info     = $this->Purchases->retrieve_company();
        $Soft_settings    = $this->Soft_settings->retrieve_setting_editdata();
        $data = array(
            'title'            => display('purchase_order'),
            'pur_order_id'     => $pur_order_id,
            'pur_order_no'     => $purchase_detail[0]['pur_order_no'],
            'supplier_id'      => $purchase_detail[0]['supplier_id'],
            'supplier_name'    => $purchase_detail[0]['supplier_name'],
            'supplier_mobile'  => $purchase_detail[0]['mobile'],
            'supplier_vat_no'  => $purchase_detail[0]['vat_no'],
            'supplier_cr_no'   => $purchase_detail[0]['cr_no'],
            'total_amount'     => $purchase_detail[0]['grand_total_amount'],
            'purchase_details' => $purchase_detail[0]['purchase_details'],
            'purchase_date'    => $purchase_detail[0]['purchase_date'],
            'expire_date'      => $purchase_detail[0]['expire_date'],
            'supply_date'      => $purchase_detail[0]['supply_date'],
            'store_id'         => $purchase_detail[0]['store_id'],
            'approve_status'   => $purchase_detail[0]['approve_status'],
            'receive_status'   => $purchase_detail[0]['receive_status'],
            'return_status'    => $purchase_detail[0]['return_status'],
            'purchase_vat'     => $purchase_detail[0]['purchase_vat'],
            'purchase_info'    => $purchase_detail,
            'po_details'       => $po_details,
            'company_info'     => $company_info,
            'currency'         => $currency_details[0]['currency_icon'],
            'position'         => $currency_details[0]['currency_position'],
            'Soft_settings'    => $Soft_settings,
        );


        $data['setting'] = $this->Template_model->setting();
        $data['module'] = "dashboard";
        $data['page'] = 'purchase/purchase_order_print';
        $this->parser->parse('template/layout', $data);
    }

    //Purchase Order
    public function receive_item()
    {
        $this->permission->check_label('receive_item')->read()->redirect();

        $order_list = $this->Purchases->get_purchase_order_list();

        $currency_details = $this->Soft_settings->retrieve_currency_info();
        $data = array(
            'title' => display('manage_purchase_order_receive'),
            'order_list' => $order_list,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
        );

        $data['setting'] = $this->Template_model->setting();
        $data['module'] = "dashboard";
        $data['page'] = 'purchase/pur_order_receive_list';
        $this->parser->parse('template/layout', $data);
    }

    // Manage Purchase order
    public function manage_purorder($param = 'view', $pur_order_id, $param2 = false)
    {
        $this->permission->check_label('purchase_order')->update()->redirect();

        if ($param == 'receive') {
            $this->form_validation->set_rules('purchase_order', display('purchase_order'), 'trim|required');
            $this->form_validation->set_rules('invoice_no', display('invoice_no'), 'trim|required');
            $this->form_validation->set_rules('batch_no[]', display('batch_no'), 'required');
            $purchase_detail = $this->Purchases->get_purchase_order_by_id($pur_order_id);
            if ($this->form_validation->run() == TRUE) {

                $result = $this->Purchases->purchase_order_receive($pur_order_id);
                if ($result) {
                    $this->session->set_userdata(array('message' => display('successfully_added')));
                    redirect(base_url('dashboard/Cpurchase/receive_item'));
                } else {
                    $this->session->set_userdata(array('error_message' => display('failed_try_again')));
                }
            }
            $all_supplier = $this->Suppliers->supplier_list();
            $store_list   = $this->Stores->store_list();
            $batch_no     = $this->generator(7);
            $bank_list    = $this->db->select('bank_id,bank_name')->from('bank_list')->get()->result();
            $data = array(
                'title'              => display('receive_item'),
                'pur_order_id'       => $pur_order_id,
                'pur_order_no'       => $purchase_detail[0]['pur_order_no'],
                'invoice_no'         => $purchase_detail[0]['invoice_no'],
                'supplier_name'      => $purchase_detail[0]['supplier_name'],
                'supplier_id'        => $purchase_detail[0]['supplier_id'],
                'grand_total'        => $purchase_detail[0]['grand_total_amount'],
                'purchase_vat'       => $purchase_detail[0]['purchase_vat'],
                'total_purchase_vat' => $purchase_detail[0]['total_purchase_vat'],
                'sub_total_price'    => $purchase_detail[0]['sub_total_price'],
                'purchase_details'   => $purchase_detail[0]['purchase_details'],
                'purchase_date'      => $purchase_detail[0]['purchase_date'],
                'store_id'           => $purchase_detail[0]['store_id'],
                'variant_id'         => $purchase_detail[0]['variant_id'],
                'batch_no'           => $batch_no,
                'purchase_info'      => $purchase_detail,
                'all_supplier'       => $all_supplier,
                'store_list'         => $store_list,
                'param2'             => $param2,
                'bank_list'          => $bank_list
            );
            $data['setting'] = $this->Template_model->setting();
            $data['module'] = "dashboard";
            $data['page'] = 'purchase/pur_order_receive';
            $this->parser->parse('template/layout', $data);
        } else if ($param == 'return') {

            $this->form_validation->set_rules('purchase_order', display('purchase_order'), 'trim|required');
            $this->form_validation->set_rules('invoice_no', display('invoice_no'), 'trim|required');

            $purchase_detail = $this->Purchases->get_po_shortinfo_by_id($pur_order_id);
            $po_details = $this->Purchases->get_purchase_order_details($pur_order_id);

            if ($this->form_validation->run() == TRUE) {

                $result = $this->Purchases->purchase_order_return($pur_order_id);
                if ($result) {
                    $this->session->set_userdata(array('message' => display('successfully_added')));
                    redirect(base_url('dashboard/Cpurchase/receive_item'));
                } else {
                    $this->session->set_userdata(array('error_message' => display('failed_try_again')));
                }
            }

            $all_supplier = $this->Suppliers->supplier_list();
            $store_list   = $this->Stores->store_list();
            $currency_details = $this->Soft_settings->retrieve_currency_info();
            $purchase_id  = $this->db->select('purchase_id')->from('product_purchase')->where('pur_order_no', $purchase_detail[0]['pur_order_no'])->get()->result();

            $proof_of_purchase_expese = $this->db->select('*')->from('proof_of_purchase_expese')->where('purchase_id', $purchase_id[0]->purchase_id)->get()->result_array();
            $total_purchase_expense  = $this->Purchases->total_purchase_expense($purchase_id[0]->purchase_id);
            $bank_list    = $this->db->select('bank_id,bank_name')->from('bank_list')->get()->result();
            $data = array(
                'title' => display('return_item'),
                'pur_order_id'             => $pur_order_id,
                'pur_order_no'             => $purchase_detail[0]['pur_order_no'],
                'invoice_no'               => $purchase_detail[0]['invoice_no'],
                'supplier_id'              => $purchase_detail[0]['supplier_id'],
                'grand_total'              => $purchase_detail[0]['grand_total_amount'],
                'purchase_vat'             => $purchase_detail[0]['purchase_vat'],
                'total_purchase_vat'       => $purchase_detail[0]['total_purchase_vat'],
                'sub_total_price'          => $purchase_detail[0]['sub_total_price'],
                'purchase_details'         => $purchase_detail[0]['purchase_details'],
                'purchase_date'            => $purchase_detail[0]['purchase_date'],
                'store_id'                 => $purchase_detail[0]['store_id'],
                'approve_status'           => $purchase_detail[0]['approve_status'],
                'receive_status'           => $purchase_detail[0]['receive_status'],
                'return_status'            => $purchase_detail[0]['return_status'],
                'purchase_info'            => $purchase_detail,
                'po_details'               => $po_details,
                'all_supplier'             => $all_supplier,
                'store_list'               => $store_list,
                'currency'                 => $currency_details[0]['currency_icon'],
                'position'                 => $currency_details[0]['currency_position'],
                'proof_of_purchase_expese' => $proof_of_purchase_expese,
                'total_purchase_expense'   => $total_purchase_expense,
                'bank_list'                => $bank_list,
                'purchase_id'              => $purchase_id
            );
            $data['setting'] = $this->Template_model->setting();
            $data['module'] = "dashboard";
            $data['page'] = 'purchase/pur_order_return';
            $this->parser->parse('template/layout', $data);
        } else {
            $purchase_detail = $this->Purchases->get_po_shortinfo_by_id($pur_order_id);
            $po_details      = $this->Purchases->get_purchase_order_details($pur_order_id);
            $all_supplier    = $this->Suppliers->supplier_list();
            $store_list      = $this->Stores->store_list();
            $currency_details = $this->Soft_settings->retrieve_currency_info();
            $company_info    = $this->Purchases->retrieve_company();
            $Soft_settings   = $this->Soft_settings->retrieve_setting_editdata();

            $data = array(
                'title'            => display('purchase_order'),
                'pur_order_id'     => $pur_order_id,
                'pur_order_no'     => $purchase_detail[0]['pur_order_no'],
                'supplier_id'      => $purchase_detail[0]['supplier_id'],
                'total_amount'     => $purchase_detail[0]['grand_total_amount'],
                'purchase_details' => $purchase_detail[0]['purchase_details'],
                'purchase_date'    => $purchase_detail[0]['purchase_date'],
                'store_id'         => $purchase_detail[0]['store_id'],
                'approve_status'   => $purchase_detail[0]['approve_status'],
                'receive_status'   => $purchase_detail[0]['receive_status'],
                'return_status'    => $purchase_detail[0]['return_status'],
                'purchase_info'    => $purchase_detail,
                'po_details'       => $po_details,
                'all_supplier'     => $all_supplier,
                'store_list'       => $store_list,
                'company_info'     => $company_info,
                'currency'         => $currency_details[0]['currency_icon'],
                'position'         => $currency_details[0]['currency_position'],
                'Soft_settings'    => $Soft_settings,
            );


            $data['setting'] = $this->Template_model->setting();
            $data['module'] = "dashboard";
            $data['page'] = 'purchase/pur_order_print';
            $this->parser->parse('template/layout', $data);
        }
    }

    public function purchase_inserted_data($purchase_id)
    {
        $purchase_detail = $this->Purchases->purchase_details_data($purchase_id);
        // var_dump($purchase_detail);
        // die();
        if (!empty($purchase_detail)) {
            $i = 0;
            foreach ($purchase_detail as $k => $v) {
                $i++;
                $purchase_detail[$k]['sl'] = $i;
            }
        }
        $currency_details = $this->Soft_settings->retrieve_currency_info();
        $company_info    = $this->Purchases->retrieve_company();
        $created_at = explode(" ", $purchase_detail[0]['created_at']);
        $created_date = @$created_at[0];
        $created_time = @$created_at[1];
        $purchase_expense_detail = $this->Purchases->purchase_expense_detail($purchase_id);
        $purchase_date = explode("-", $purchase_detail[0]['purchase_date']);
        $purchase_detail[0]['purchase_date'] = $purchase_date[2] . '-' . $purchase_date[0] . '-' . $purchase_date[1];
        $data = array(
            'title'                   => display('purchase_details'),
            'purchase_id'             => $purchase_detail[0]['purchase_id'],
            'invoice_no'              => $purchase_detail[0]['invoice_no'],
            'supplier_name'           => $purchase_detail[0]['supplier_name'],
            'supplier_mobile'         => $purchase_detail[0]['mobile'],
            'cgst'         => $purchase_detail[0]['cgst'],
            'sgst'         => $purchase_detail[0]['sgst'],
            'igst'         => $purchase_detail[0]['igst'],

            'supplier_email'          => $purchase_detail[0]['email'],
            'store_id'                => $purchase_detail[0]['store_id'],
            'vat_no'                  => $purchase_detail[0]['vat_no'],
            'cr_no'                   => $purchase_detail[0]['cr_no'],
            'supplier_address'        => $purchase_detail[0]['address'],
            'purchase_date'           => $purchase_detail[0]['purchase_date'],
            'created_at'              => $purchase_detail[0]['created_at'],
            'created_date'            => $created_date,
            'created_time'            => $created_time,
            'sub_total_price'         => $purchase_detail[0]['sub_total_price'],
            'purchase_vat'            => $purchase_detail[0]['purchase_vat'],
            'total_purchase_vat'      => $purchase_detail[0]['total_purchase_vat'],
            'grand_total_amount'      => $purchase_detail[0]['grand_total_amount'],
            'purchase_all_data'       => $purchase_detail,
            'purchase_expense_detail' => $purchase_expense_detail,
            'company_info'            => $company_info,
            'currency'                => $currency_details[0]['currency_icon'],
            'position'                => $currency_details[0]['currency_position'],
        );
        $data['Soft_settings'] = $this->Soft_settings->retrieve_setting_editdata();
        $chapterList = $this->parser->parse('dashboard/purchase/purchase_html', $data, true);
        $this->template_lib->full_admin_html_view($chapterList);
    }

    public function add_new_p_cost_row($count)
    {
        $row_id = mt_rand();
        $html = '';
        $bank_list = $this->db->select('bank_id,bank_name')->from('bank_list')->get()->result();
        $html .= '<tr id="row_' . $row_id . '">
                    <td class="text-left">
                        <input type="text" class="text-right form-control purchase_expences" name="purchase_expences_title_' . $count . '" placeholder ="Please Provide expense name" />
                    </td>
                    <td class="text-left">
                        <input type="text" onkeyup="calculate_add_purchase_cost(' . $count . ');"onchange="calculate_add_purchase_cost(' . $count . ');" id="purchase_expences_' . $count . '" class="text-right form-control purchase_expences" name="purchase_expences_' . $count . '" placeholder ="0.00" />
                    </td>
                    <td>
                        <div class="form-group row guifooterpanel">
                            <div class="col-sm-12">
                                <select class="form-control dont-select-me" name="bank_id[]">
                                    <option value="cash">Cash</option>';
        if ($bank_list) {
            foreach ($bank_list as $bank) {
                $html .= '<option value="' . $bank->bank_id . '">' . $bank->bank_name . '</option>';
            }
        }
        $html .=                '</select>
                            </div>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm del_more_btn" data-row_id="' . $row_id . '" ><i class="fa fa-minus"></i></button>
                    </td>
                </tr>';
        echo $html;
    }
    public function purchase_excel_import()
    {
        $this->permission->check_label('import_product_excel')->read()->redirect();
        $data = array(
            'title' => display('import_purchase_excel')
        );
        $content = $this->parser->parse('dashboard/purchase/add_purchase_excel', $data, true);
        $this->template_lib->full_admin_html_view($content);
    }
    	//This function will check the product & supplier relationship.
	public function product_supplier_check($product_id, $supplier_id)
	{
		$this->db->select('product_id','supplier_id');
		$this->db->from('product_information');
		$this->db->where('product_id', $product_id);
		$this->db->where('supplier_id', $supplier_id);
		$query = $this->db->get();
// 	
	return $query->result_array();
    
	}
    // purchase excel import
    
    
     public function purchase_excel_insert()
    {
        $upload_file = $_FILES["upload_excel_file"]["name"];
        $extension = pathinfo($upload_file, PATHINFO_EXTENSION);
        if ($extension == 'csv') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } elseif ($extension == 'xls') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $spreadsheet = $reader->load($_FILES["upload_excel_file"]["tmp_name"]);
        $sheetdata = $spreadsheet->getActiveSheet()->toArray();
        $datacount = count($sheetdata);
    $user_id			= $this->session->userdata('user_id');

        
        // var_dump($user_id);
        // die();
        if ($datacount > 1) {
            for ($i = 1; $i < $datacount; $i++) {

if (check_module_status('accounting') == 1) {
		  //  echo "if";
    // die();
			$find_active_fiscal_year = $this->db->select('*')->from('acc_fiscal_year')->where('status', 1)->get()->row();
// 			var_dump($find_active_fiscal_year);
// 			die();
			if (!empty($find_active_fiscal_year)) {

                $p_id         = $sheetdata[$i][0];
                $batch_no       = $sheetdata[$i][1];
                $expiry        = $sheetdata[$i][2];
                $supplier_id   = $sheetdata[$i][3];
                $quantity      = $sheetdata[$i][4];
                $variant_id        = $sheetdata[$i][5];
                $color_variant     = $sheetdata[$i][6];
                $discount                = $sheetdata[$i][7];
                $pur_order_no                = $sheetdata[$i][8];
                $store_id       = $sheetdata[$i][9];
                $sgst     = $sheetdata[$i][10];
                $cgst         = $sheetdata[$i][11];
                $igst            = $sheetdata[$i][12];
                $cost_sectors            = $sheetdata[$i][13];
                $expense_title       = $sheetdata[$i][14];
                $purchase_expense                = $sheetdata[$i][15];
                $invoice_no           = $sheetdata[$i][16];
                $wearhouse_id              = $sheetdata[$i][17];
                $sub_total_price        = $sheetdata[$i][18];
                $total_items     = $sheetdata[$i][19];
                $total_purchase_tax = $sheetdata[$i][20];
                $grand_total_amount              = $sheetdata[$i][21];
                $purchase_date         = $sheetdata[$i][22];
                $purchase_details                 = $sheetdata[$i][23];
                $product_rate       = $sheetdata[$i][24];
                $total_price       = $sheetdata[$i][25];

        

                $excel = array(
                    'product_id'         => $p_id,
                    'batch_no'           => $batch_no,
                    'expiry_date'        => $expiry,
                    'supplier_id'        => $supplier_id,
                    'quantity'           => $quantity,
                    'variant_id'         => $variant_id,
                    'color_variant'      => $color_variant,
                    'discount'           => $discount,
                    'pur_order_no'       => $pur_order_no,
                    'store_id'           => $store_id,
                    'sgst'               => $sgst,
                    'cgst'               => $cgst,
                    'igst'               => $igst,
                    'bank_id'                 => $cost_sectors,
                    'purchase_expences_title'        => $expense_title,
                    'purchase_expences'               => $purchase_expense,
                    'invoice_no'          => $invoice_no,
                    'wearhouse_id'             => $wearhouse_id,
                    'sub_total_price'       => $sub_total_price,
                    'total_number_of_items'    => $total_items,
                    'total_purchase_tax' => $total_purchase_tax,
                    'grand_total_amount'             => $grand_total_amount,
                    'purchase_date'        => $purchase_date,
                    'purchase_details'                => $purchase_details,
                    'product_rate'         =>$product_rate,
                    'total_price'          =>$total_price,
                    'woocom_stock'         =>0,
                );
                
              $pro_id = $excel['product_id'];
                $supp_id = $excel['supplier_id'];
              	 //   	var_dump($pro_id);
              	 //   	              	    	var_dump($supp_id);

                // die();
                
                $value=$this->product_supplier_check($pro_id,$supp_id);
// var_dump($value[0]['product_id']);
// die();
                
    //             if (!$value[0])
    //             {
               
				// 		  //  	var_dump($value);
    //     //         die();
				// 		$this->session->set_userdata(array('error_message' => display( "product_and_supplier_did_not_match")));
				// 		redirect(base_url('dashboard/Cpurchase'));
				// 	}
				// 	    	  //  	var_dump('value' ,$value);
            //     die();
				//Variant id required check
				// $result = array();
			
					
//   var_dump($excel);
//                 die();
				$purchase_id  = $this->generator(15);
                $product_details = array(
                    'purchase_id'     => $purchase_id,
                    'invoice_no'    => $excel['invoice_no'],
                    'pur_order_no'   => $excel['pur_order_no'],
                    'supplier_id'          => $excel['supplier_id'],
                    'store_id' => $excel['store_id'],
                    'wearhouse_id'   => '',
                    'sub_total_price' => $excel['sub_total_price'],
                    'total_items'           => $excel['total_number_of_items'],
                    'sgst'           => $excel['sgst'],
                    'cgst'  => $excel['cgst'],
                    'igst' => $excel['igst'],
                    'total_purchase_tax'    => $excel['total_purchase_tax'],
                    'grand_total_amount'       => $excel['grand_total_amount'],
                    'purchase_date'       => $excel['purchase_date'],
                    'purchase_details'           => $excel['purchase_details'],
                    'purchase_expences'      => $excel['purchase_expences'],
					'user_id'			=> $this->session->userdata('user_id'),
                    'status'			=> 1,
					'created_at'        => date('Y-m-d h:i:s')
                );
                
              
                $this->db->insert('product_purchase', $product_details);
                // $this->db->select('*');
                // $this->db->from('product_purchase');
                // $this->db->where('status', 1);
                // $query = $this->db->get();
                // foreach ($query->result() as $row) {
                //     $json_product[] = array('label' => $row->invoice_no . "-(" . $row->purchase_id . ")", 'value' => $row->purchase_id);
                // }
                // $cache_file = './my-assets/js/admin_js/json/product.json';
                // $productList = json_encode($json_product);
                // file_put_contents($cache_file, $productList);
                $total_price_without_discount = 0;
	            $ledger = array(
					'transaction_id' => $this->generator(15),
					'purchase_id'	=> $purchase_id,
					'invoice_no'	=> $excel['invoice_no'],
					'supplier_id'	=> $excel['supplier_id'],
					'amount'		=>$excel['grand_total_amount'],
					'date'			=> $excel['purchase_date'],
					'description'	=> $excel['purchase_details'],
					'status'		=> 1
				);
				
		
				$this->db->insert('supplier_ledger', $ledger);
				$data1 = array(
						'purchase_detail_id' => $this->generator(15),
						'purchase_id'		=> $purchase_id,
						'product_id'		=> $excel['product_id'],
						'batch_no'		    =>  $excel['batch_no'],
						'expiry_date'		=>  $excel['expiry_date'],
						'wearhouse_id'		=> '',
						'store_id'			=> $excel['store_id'],
						'quantity'			=> $excel['quantity'],
						'rate'				=> $excel['product_rate'],
						'discount'			=> $excel['discount'],
				// 		'vat_rate'			=> $excel['expiry_date'],
						'vat'			    => $excel['sgst'],
						'total_amount'		=> $excel['total_price'],
						'variant_id'		=> $excel['variant_id'],
						'variant_color'		=> (!empty($excel['color_variant']) ? $excel['color_variant'] : NULL),
						'status'			=> 1
					);
				
				
				                if (!empty($excel['quantity'])) {

						$this->db->insert('product_purchase_details', $data1);
// 									var_dump($ledger);
// 			die();
					}
					
				
						$store = array(
						'transfer_id'	=> $this->generator(15),
						'purchase_id'	=> $purchase_id,
						'store_id'		=>  $excel['store_id'],
						'product_id'	=>  $excel['product_id'],
						'variant_id'	=>  $excel['variant_id'],
						'variant_color'	=>  (!empty($excel['color_variant']) ? $excel['color_variant'] : NULL),
						'date_time'		=>  $excel['purchase_date'],
						'quantity'		=>  $excel['quantity'],
						'status'		=> 3
					);
					
					
						 if (!empty($excel['quantity'])) {
						$this->db->insert('transfer', $store);
						$check_stock = $this->Purchases->check_stock($excel['store_id'], $excel['product_id'], $excel['variant_id'], $excel['color_variant']);
    //               var_dump($check_stock);
				// 	die(); 
							if (empty($check_stock)) {
							// insert
							$stock = array(
								'store_id'     => $excel['store_id'],
								'product_id'   => $excel['product_id'],
								'variant_id'   => $excel['variant_id'],
								'variant_color' =>  (!empty($excel['color_variant']) ? $excel['color_variant'] : NULL),
								'quantity'     => $excel['quantity'],
								'warehouse_id' => '',
							);
														
					
				$this->db->insert('purchase_stock_tbl', $stock);
						
					
						
						}
						else {
							//update
							$stock = array(
								'quantity' => $check_stock->quantity + $excel['quantity']
							);
							if (!empty($excel['store_id'])) {
								$this->db->where('store_id', $excel['store_id']);
							}
							if (!empty($excel['product_id'])) {
								$this->db->where('product_id', $excel['product_id']);
							}
							if (!empty($excel['variant_id'])) {
								$this->db->where('variant_id', $excel['variant_id']);
							}
							if (!empty($excel['color_variant'])) {
								$this->db->where('variant_color', $excel['color_variant']);
							}
											
				
						
							$this->db->update('purchase_stock_tbl', $stock);
							//update
						}
						}
						
						$this->load->model('accounting/account_model');
				// $supplier_id  = $this->input->post('supplier_id', TRUE);
				// $store_id     = $this->input->post('store_id', TRUE);
				$store_head   = $this->db->select('HeadCode,HeadName')->from('acc_coa')->where('store_id',  $excel['store_id'])->get()->row();
				$supplier_head = $this->db->select('HeadCode,HeadName')->from('acc_coa')->where('supplier_id',  $excel['supplier_id'])->get()->row();
				
				if (empty($supplier_head)) {
					$PHead = $this->db->select('HeadCode,HeadName')->from('acc_coa')->where('HeadCode', '2111')->get()->row();
						
					if (!empty($PHead)) {
						$childCount = $this->db->select('MAX(HeadCode) as HeadCode')->from('acc_coa')->where('PHeadCode', '2111')->get()->row();
						if (!empty($childCount->HeadCode)) {
							$HeadCode = $childCount->HeadCode + 1;
						} else {
							$HeadCode = '21111';
						}
						$supplier_name = $this->db->select('supplier_name')->from('supplier_information')->where('supplier_id', $excel['supplier_id'])->get()->row();

						$acc_coa = array(
							'HeadCode'     => $HeadCode,
							'HeadName'     => $supplier_name->supplier_name,
							'PHeadName'    => $PHead->HeadName,
							'PHeadCode'    => $PHead->HeadCode,
							'HeadLevel'    => 4,
							'IsActive'     => 1,
							'IsTransaction' => 1,
							'IsGL'         => 0,
							'HeadType'     => 'L',
							'supplier_id'  => $excel['supplier_id'],
							'CreateBy'     => $this->session->userdata('user_id'),
							'CreateDate'   => date('Y-m-d H:i:s'),
						);
						$this->db->insert('acc_coa', $acc_coa);
						$supplier_head = $this->db->select('HeadCode,HeadName')->from('acc_coa')->where('supplier_id', $excel['supplier_id'])->get()->row();
					}
				}
						$createdate   = date('Y-m-d H:i:s');
				$receive_by   = $this->session->userdata('user_id');
				$date         = $createdate;

				$total_price_before_discount = $total_price_without_discount;
				$total_purchase_vat     = $excel['total_purchase_tax'];
				$total_price_with_vat   = $excel['grand_total_amount'];
				$total_purchase_discount = $total_price_before_discount - ($total_price_with_vat - $total_purchase_vat);
				$purchase_expence       =  $excel['purchase_expences'];	
				
				
				
					//1st Main warehouse Debit (total_price_before_discount)
				$main_warehouse_debit = array(
					'fy_id'     => $find_active_fiscal_year->id,
					'VNo'       => 'p-' . $purchase_id,
					'Vtype'     => 'Purchase',
					'VDate'     => $date,
					'COAID'     => 1141, //Main Warehouse
					'Narration' => 'Purchase total price before discount debit by Main warehouse',
					'Debit'     => $total_price_before_discount,
					'Credit'    => 0, //purchase price asbe
					'IsPosted'  => 1,
					'CreateBy'  => $receive_by,
					'CreateDate' => $createdate,
					'store_id'  => $excel['store_id'],
					'IsAppove'  => 1
				);
				
			
				
				
					//2nd (vat on input) Debit
				
				
								
				$vat = array(
					'fy_id'     => $find_active_fiscal_year->id,
					'VNo'       => 'p-' . $purchase_id,
					'Vtype'     => 'Purchase',
					'VDate'     => $date,
					'COAID'     => 116,
					'Narration' => 'Purchase vat/tax total debit by supplier id: ' . $supplier_head->HeadName . '(' . $supplier_id . ')',
					'Debit'     => $total_purchase_vat,
					'Credit'    => 0, //purchase price asbe
					'IsPosted'  => 1,
					'CreateBy'  => $receive_by,
					'CreateDate' => $createdate,
					'store_id'  => $excel['store_id'],
					'IsAppove'  => 1
				);
					
				//3rd supplier credit (total_price_with_vat or grand_total_price)
				$suppliercredit = array(
					'fy_id'     => $find_active_fiscal_year->id,
					'VNo'       => 'p-' . $purchase_id,
					'Vtype'     => 'Purchase',
					'VDate'     => $date,
					'COAID'     => $supplier_head->HeadCode,
					'Narration' => 'Purchase "total_price_with_vat" credited by supplier: ' . $supplier_head->HeadName . '(' . $supplier_id . ')',
					'Debit'     => 0,
					'Credit'    => $total_price_with_vat,
					'IsPosted'  => 1,
					'CreateBy'  => $receive_by,
					'CreateDate' => $createdate,
					'store_id'  => $excel['store_id'],
					'IsAppove'  => 1
				);

				//4th total_purchase_discount credit
				$discount = array(
					'fy_id'     => $find_active_fiscal_year->id,
					'VNo'       => 'p-' . $purchase_id,
					'Vtype'     => 'Purchase',
					'VDate'     => $date,
					'COAID'     => 521,
					'Narration' => 'Purchase total discount credit by supplier id: ' . $supplier_head->HeadName . '(' . $supplier_id . ')',
					'Debit'     => 0,
					'Credit'    => $total_purchase_discount,
					'IsPosted'  => 1,
					'CreateBy'  => $receive_by,
					'CreateDate' => $createdate,
					'store_id'  => $excel['store_id'],
					'IsAppove'  => 1
				);
				
			
				//5th proof of purchase expences Main warehouse Debit (purchase_expence)
				$debit_purchase_expences_inventory = array(
					'fy_id'     => $find_active_fiscal_year->id,
					'VNo'       => 'p-' . $purchase_id,
					'Vtype'     => 'Purchase',
					'VDate'     => $date,
					'COAID'     => 1141,
					'Narration' => 'Purchase expence proof (Main warehouse) debit by supplier id: ' . $supplier_head->HeadName . '(' . $supplier_id . ')',
					'Debit'     => $purchase_expence,
					'Credit'    => 0, //purchase price asbe
					'IsPosted'  => 1,
					'CreateBy'  => $receive_by,
					'CreateDate' => $createdate,
					'store_id'  => $excel['store_id'],
					'IsAppove'  => 1
				);
			
			
				//6th Cash in box general administration credit
				$credit_purchase_expences = array();
				$p_cost_sectors =$excel['bank_id'];
					
				if (!empty($p_cost_sectors)) {
					foreach ($p_cost_sectors as $key => $sector) {
						$ind_purchase_expence = $excel['purchase_expences_' . ($key + 1)] ;
						if (!empty($ind_purchase_expence)) {
							if ($sector == 'cash') {
								$credit_purchase_expences[] = array(
									'fy_id'     => $find_active_fiscal_year->id,
									'VNo'       => 'p-' . $purchase_id,
									'Vtype'     => 'Purchase',
									'VDate'     => $date,
									'COAID'     => 1111,
									'Narration' => 'Purchase expence proof (Cash in box general administration) credit by supplier id: ' . $supplier_head->HeadName . '(' .$excel['supplier_id']. ')',
									'Debit'     => 0,
									'Credit'    => $ind_purchase_expence,
									'IsPosted'  => 1,
									'CreateBy'  => $receive_by,
									'CreateDate' => $createdate,
									'store_id'  => $excel['store_id'],
									'IsAppove'  => 1
								);
							} else {
								$bank_id = $sector;
								$bank_head    = $this->db->select('HeadCode,HeadName')->from('acc_coa')->where('bank_id', $bank_id)->get()->row();
								if (empty($bank_head)) {
									$this->load->model('accounting/account_model');
									$bank_name = $this->db->select('bank_name')->from('bank_list')->where('bank_id', $bank_id)->get()->row();
									if ($bank_name) {
										$bank_data = array(
											'bank_id'  => $bank_id,
											'bank_name' => $bank_name->bank_name,
										);
										$this->account_model->insert_bank_head($bank_data);
									}
									$bank_head = $this->db->select('HeadCode,HeadName')->from('acc_coa')->where('bank_id', $bank_id)->get()->row();
								}
								$credit_purchase_expences[] = array(
									'fy_id'     => $find_active_fiscal_year->id,
									'VNo'       => 'p-' . $purchase_id,
									'Vtype'     => 'Purchase',
									'VDate'     => $date,
									'COAID'     => $bank_head->HeadCode,
									'Narration' => 'Purchase expence proof bank credit by supplier id: ' . $supplier_head->HeadName . '(' . $excel['supplier_id'] . ')',
									'Debit'     => 0,
									'Credit'    => $ind_purchase_expence,
									'IsPosted'  => 1,
									'CreateBy'  => $receive_by,
									'CreateDate' => $createdate,
									'store_id'  => $excel['store_id'],
									'IsAppove'  => 1
								);
							}
						}
					}

					if (!empty($credit_purchase_expences)) {
						$this->db->insert_batch('acc_transaction', $credit_purchase_expences);
					}
				} else {
					$credit_purchase_expences = array(
						'fy_id'     => $find_active_fiscal_year->id,
						'VNo'       => 'p-' . $purchase_id,
						'Vtype'     => 'Purchase',
						'VDate'     => $date,
						'COAID'     => 1111,
						'Narration' => 'Purchase expence proof (Cash in box general administration) credit by supplier id: ' . $supplier_head->HeadName . '(' . $excel['supplier_id'] . ')',
						'Debit'     => 0,
						'Credit'    => $purchase_expence,
						'IsPosted'  => 1,
						'CreateBy'  => $receive_by,
						'CreateDate' => $createdate,
						'store_id'  => $excel['store_id'],
						'IsAppove'  => 1
					);
					
					$this->db->insert('acc_transaction', $credit_purchase_expences);
				
				}
				
					$this->db->insert('acc_transaction', $main_warehouse_debit);
				$this->db->insert('acc_transaction', $suppliercredit);
				$this->db->insert('acc_transaction', $vat);
				$this->db->insert('acc_transaction', $discount);
				$this->db->insert('acc_transaction', $debit_purchase_expences_inventory);
				
					$woocom_stock =$excel['woocom_stock'];
				// 	var_dump($woocom_stock);
				// die();
				if (check_module_status('woocommerce') && ($woocom_stock == '1')) {
				    

					$this->load->library('woocommerce/woolib/woo_lib');
					$this->load->model('woocommerce/woo_model');
					$this->woo_lib->connection();
					$def_store = $this->woo_model->get_def_store();

					$woo_stock = [];
					for ($i = 0, $n = count($p_id); $i < $n; $i++) {
						$product_quantity = $quantity[$i];
						$product_id = $p_id[$i];
						$variant = $variant_id[$i];
						$fulldata = $woo_data = [];
						$product_stock = 0;


						$prodinfo = $this->woo_model->get_product_sync_by_local_id($product_id);

						if (!empty($prodinfo)) {
							if ($prodinfo->woo_product_type == 'variable') {

								$varinfo = $this->woo_model->get_variant_sync_by_local($product_id, $variant);

								if (!empty($varinfo->woo_product_id) && !empty($varinfo->woo_variant_id)) {

									$product_stock = $this->woo_model->get_product_stock($def_store->store_id, $product_id, $variant);

									$woo_data[] = array(
										'id' => $varinfo->woo_variant_id,
										'manage_stock' => TRUE,
										'stock_quantity' => $product_stock,
										'stock_status' => (intval($product_stock) > 0 ? 'instock' : 'outofstock')
									);

									if (!empty($woo_data)) {
										$fulldata['update'] = $woo_data;
										$woovarinfo = $this->woo_lib->post_request(array('param' => 'products/' . $varinfo->woo_product_id . '/variations/batch'), $fulldata);
									}
								}
							} else {

								$pdef_info = $this->woo_model->get_product_variant_info($product_id);

								if (!empty($pdef_info)) {

									$product_stock = $this->woo_model->get_product_stock($def_store->store_id, $product_id, $pdef_info->default_variant);

									$woo_stock[] = array(
										'id' => $prodinfo->woo_product_id,
										'manage_stock' => TRUE,
										'stock_quantity' => $product_stock,
										'stock_status' => (intval($product_stock) > 0 ? 'instock' : 'outofstock')
									);
								}
							}
						}
					}
					if (!empty($woo_stock)) { //update global stock
						$this->woo_lib->post_request(array('param' => 'products/batch'), array('update' => $woo_stock));
					}
				}
		
            //   	return true;

            }
            
            else {
				$this->session->set_userdata(array('error_message' => display('no_active_fiscal_year_found')));
				redirect('dashboard/Cpurchase');
			}
// 					echo "inside if";
// die();
                        //  	return true;
 
}

else{
    
    
    $p_id         = $sheetdata[$i][0];
                $batch_no       = $sheetdata[$i][1];
                $expiry        = $sheetdata[$i][2];
                $supplier_id   = $sheetdata[$i][3];
                $quantity      = $sheetdata[$i][4];
                $variant_id        = $sheetdata[$i][5];
                $color_variant     = $sheetdata[$i][6];
                $discount                = $sheetdata[$i][7];
                $pur_order_no                = $sheetdata[$i][8];
                $store_id       = $sheetdata[$i][9];
                $sgst     = $sheetdata[$i][10];
                $cgst         = $sheetdata[$i][11];
                $igst            = $sheetdata[$i][12];
                $cost_sectors            = $sheetdata[$i][13];
                $expense_title       = $sheetdata[$i][14];
                $purchase_expense                = $sheetdata[$i][15];
                $invoice_no           = $sheetdata[$i][16];
                $wearhouse_id              = $sheetdata[$i][17];
                $sub_total_price        = $sheetdata[$i][18];
                $total_items     = $sheetdata[$i][19];
                $total_purchase_tax = $sheetdata[$i][20];
                $grand_total_amount              = $sheetdata[$i][21];
                $purchase_date         = $sheetdata[$i][22];
                $purchase_details                 = $sheetdata[$i][23];
                $product_rate       = $sheetdata[$i][24];
                $total_price       = $sheetdata[$i][25];

        

                $excel = array(
                    'product_id'         => $p_id,
                    'batch_no'           => $batch_no,
                    'expiry_date'        => $expiry,
                    'supplier_id'        => $supplier_id,
                    'quantity'           => $quantity,
                    'variant_id'         => $variant_id,
                    'color_variant'      => $color_variant,
                    'discount'           => $discount,
                    'pur_order_no'       => $pur_order_no,
                    'store_id'           => $store_id,
                    'sgst'               => $sgst,
                    'cgst'               => $cgst,
                    'igst'               => $igst,
                    'bank_id'                 => $cost_sectors,
                    'purchase_expences_title'        => $expense_title,
                    'purchase_expences'               => $purchase_expense,
                    'invoice_no'          => $invoice_no,
                    'wearhouse_id'             => $wearhouse_id,
                    'sub_total_price'       => $sub_total_price,
                    'total_number_of_items'    => $total_items,
                    'total_purchase_tax' => $total_purchase_tax,
                    'grand_total_amount'             => $grand_total_amount,
                    'purchase_date'        => $purchase_date,
                    'purchase_details'                => $purchase_details,
                    'product_rate'         =>$product_rate,
                    'total_price'          =>$total_price,
                    'woocom_stock'         => 0,
                );
                
    //          	$value 	   = $this->product_supplier_check($excel['product_id'], $excel['supplier_id']);
				// 	if ($value == 0) {
				// 		$this->session->set_userdata(array('error_message' => display("product_and_supplier_did_not_match")));
				// 		redirect(base_url('dashboard/Cpurchase'));
				// 	}
				// 					$result = array();

    //          	if (empty($excel['variant_id'])) {
				// 		$this->session->set_userdata(array('error_message' => display('variant_is_required')));
				// 		redirect('dashboard/Cpurchase');
				// 	}
				$purchase_id  = $this->generator(15);
                $product_details = array(
                    'purchase_id'     => $purchase_id,
                    'invoice_no'    => $excel['invoice_no'],
                    'pur_order_no'   => $excel['pur_order_no'],
                    'supplier_id'          => $excel['supplier_id'],
                    'store_id' => $excel['store_id'],
                    'wearhouse_id'   => '',
                    'sub_total_price' => $excel['sub_total_price'],
                    'total_items'           => $excel['total_number_of_items'],
                    'sgst'           => $excel['sgst'],
                    'cgst'  => $excel['cgst'],
                    'igst' => $excel['igst'],
                    'total_purchase_tax'    => $excel['total_purchase_tax'],
                    'grand_total_amount'       => $excel['grand_total_amount'],
                    'purchase_date'       => $excel['purchase_date'],
                    'purchase_details'           => $excel['purchase_details'],
                    'purchase_expences'      => $excel['purchase_expences'],
					'user_id'			=> $this->session->userdata('user_id'),
                    'status'			=> 1,
					'created_at'        => date('Y-m-d h:i:s')
                );
                $this->db->insert('product_purchase', $product_details);
                $this->db->select('*');
                $this->db->from('product_purchase');
                $this->db->where('status', 1);
                $query = $this->db->get();
                foreach ($query->result() as $row) {
                    $json_product[] = array('label' => $row->invoice_no . "-(" . $row->purchase_id . ")", 'value' => $row->purchase_id);
                }
                $cache_file = './my-assets/js/admin_js/json/product.json';
                $productList = json_encode($json_product);
                file_put_contents($cache_file, $productList);
                $total_price_without_discount = 0;
	            $ledger = array(
					'transaction_id' => $this->generator(15),
					'purchase_id'	=> $purchase_id,
					'invoice_no'	=> $excel['invoice_no'],
					'supplier_id'	=> $excel['supplier_id'],
					'amount'		=>$excel['grand_total_amount'],
					'date'			=> $excel['purchase_date'],
					'description'	=> $excel['purchase_details'],
					'status'		=> 1
				);
				

				$this->db->insert('supplier_ledger', $ledger);
				
					$data1 = array(
						'purchase_detail_id' => $this->generator(15),
						'purchase_id'		=> $purchase_id,
						'product_id'		=> $excel['product_id'],
						'batch_no'		    =>  $excel['batch_no'],
						'expiry_date'		=>  $excel['expiry_date'],
						'wearhouse_id'		=> '',
						'store_id'			=> $excel['store_id'],
						'quantity'			=> $excel['quantity'],
						'rate'				=> $excel['product_rate'],
						'discount'			=> $excel['discount'],
				// 		'vat_rate'			=> $excel['expiry_date'],
						'sgst'			    => $excel['sgst'],
						'total_amount'		=> $excel['total_price'],
						'variant_id'		=> $excel['variant_id'],
						'variant_color'		=> (!empty($excel['color_variant']) ? $excel['color_variant'] : NULL),
						'status'			=> 1
					);
					if (!empty($excel['quantity'])) {
						$this->db->insert('product_purchase_details', $data1);
					}
					
					
						$store = array(
						'transfer_id'	=> $this->generator(15),
						'purchase_id'	=> $purchase_id,
						'store_id'		=>  $excel['store_id'],
						'product_id'	=>  $excel['product_id'],
						'variant_id'	=>  $excel['variant_id'],
						'variant_color'	=>  (!empty($excel['color_variant']) ? $excel['color_variant'] : NULL),
						'date_time'		=>  $excel['purchase_date'],
						'quantity'		=>  $excel['quantity'],
						'status'		=> 3
					);
					
					
						if (!empty($quantity)) {
						$this->db->insert('transfer', $store);
						$check_stock = $this->check_stock($excel['store_id'], $excel['product_id'], $excel['variant_id'], $excel['color_variant']);

							if (empty($check_stock)) {
							// insert
							$stock = array(
								'store_id'     => $excel['store_id'],
								'product_id'   => $excel['product_id'],
								'variant_id'   => $excel['variant_id'],
								'variant_color' =>  (!empty($excel['color_variant']) ? $excel['color_variant'] : NULL),
								'quantity'     => $excel['quantity'],
								'warehouse_id' => '',
							);
							
							
					
							$this->db->insert('purchase_stock_tbl', $stock);
						
						
						
						}
						else {
							//update
							$stock = array(
								'quantity' => $check_stock->quantity + $excel['quantity']
							);
							if (!empty($excel['store_id'])) {
								$this->db->where('store_id', $excel['store_id']);
							}
							if (!empty($excel['product_id'])) {
								$this->db->where('product_id', $excel['product_id']);
							}
							if (!empty($excel['variant_id'])) {
								$this->db->where('variant_id', $excel['variant_id']);
							}
							if (!empty($excel['color_variant'])) {
								$this->db->where('variant_color', $excel['color_variant']);
							}
							$this->db->update('purchase_stock_tbl', $stock);
							//update
						}
						}
						
						
		$woocom_stock = $excel['woocom_stock'];
			if (check_module_status('woocommerce') && ($woocom_stock == '1')) {

				$this->load->library('woocommerce/woolib/woo_lib');
				$this->load->model('woocommerce/woo_model');
				$this->woo_lib->connection();
				$def_store = $this->woo_model->get_def_store();

				$woo_stock = [];
				for ($i = 0, $n = count($p_id); $i < $n; $i++) {
					$product_quantity = $quantity[$i];
					$product_id = $p_id[$i];
					$variant = $variant_id[$i];
					$fulldata = $woo_data = [];
					$product_stock = 0;

					$prodinfo = $this->woo_model->get_product_sync_by_local_id($product_id);

					if (!empty($prodinfo)) {
						if ($prodinfo->woo_product_type == 'variable') {
							$varinfo = $this->woo_model->get_variant_sync_by_local($product_id, $variant);
							if (!empty($varinfo->woo_product_id) && !empty($varinfo->woo_variant_id)) {
								$product_stock = $this->woo_model->get_product_stock($def_store->store_id, $product_id, $variant);
								$woo_data[] = array(
									'id' => $varinfo->woo_variant_id,
									'manage_stock' => TRUE,
									'stock_quantity' => $product_stock,
									'stock_status' => (intval($product_stock) > 0 ? 'instock' : 'outofstock')
								);
								if (!empty($woo_data)) {
									$fulldata['update'] = $woo_data;
									$woovarinfo = $this->woo_lib->post_request(array('param' => 'products/' . $varinfo->woo_product_id . '/variations/batch'), $fulldata);
								}
							}
						} else {
							$pdef_info = $this->woo_model->get_product_variant_info($product_id);
							if (!empty($pdef_info)) {
								$product_stock = $this->woo_model->get_product_stock($def_store->store_id, $product_id, $pdef_info->default_variant);
								$woo_stock[] = array(
									'id' => $prodinfo->woo_product_id,
									'manage_stock' => TRUE,
									'stock_quantity' => $product_stock,
									'stock_status' => (intval($product_stock) > 0 ? 'instock' : 'outofstock')
								);
							}
						}
					}
				}
				if (!empty($woo_stock)) { //update global stock
					$this->woo_lib->post_request(array('param' => 'products/batch'), array('update' => $woo_stock));
				}
			}
			
			
			
// 			return true;
    
    
}


}

            $this->session->set_userdata(array('message' => display('successfully_added')));
            redirect('dashboard/Cpurchase/manage_purchase');

        }
    }
}