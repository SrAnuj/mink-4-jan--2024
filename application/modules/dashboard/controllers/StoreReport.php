<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class StoreReport extends MX_Controller
{

	function __construct()
    {
        parent::__construct();
        $this->auth->check_user_auth();
       $this->load->model('dashboard/Store_targets_model');
    }

    public function getStoreReports()
    {
    	  $all_stores = $this->Store_targets_model->getAllStores();
    	  
  		$data['json_data'] = json_encode($all_stores);
    	$content = $this->parser->parse('stockreports/index',$data,true);
        $this->template_lib->full_admin_html_view($content);
    }
}
?>
