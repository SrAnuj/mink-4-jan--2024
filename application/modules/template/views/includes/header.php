<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$CI = &get_instance();
$CI->load->model('dashboard/Soft_settings');
$CI->load->model('dashboard/Reports');
$CI->load->model('dashboard/Users');
$CI->load->model('dashboard/expriy_report_model');
$Soft_settings = $CI->Soft_settings->retrieve_setting_editdata();
$users = $CI->Users->profile_edit_data();
$out_of_stock = $CI->Reports->out_of_stock();
$out_of_stock  = count($out_of_stock);
$expiry_report_count = $CI->Reports->product_list_count();   
// var_dump($out_of_stock);
// die();
$store_wise_products_count = 0;

if (!empty($this->session->userdata('language'))) {
    $language_id = $this->session->userdata('language');
} else {

    $language_id = 'english';
}

?>
 <?php
    $this->load->helper('custom_helper');
        $user_id = $this->session->userdata('user_type'); // Adjust the user_id retrieval method as needed

 $notifications = getNotifications($user_id);
$getCountNotifications = getCountNotifications($user_id);
 $subscriptionData = $CI->Users->getCustomerSubscriptionData();
$expiredCount = $CI->Users->countCustomerSubscription();
  // print($expiredCount);die;
    ?>
<input type="hidden" name="base_url" id="base_url" value="<?php echo base_url(); ?>">
<input type="hidden" name="CSRF_TOKEN" id="CSRF_TOKEN" value="<?php echo $this->security->get_csrf_hash(); ?>">
<input type="hidden" name="language_id" id="language_id" value="<?php echo html_escape($language_id) ?>">
<script src="<?php echo base_url() ?>assets/js/global_js.js" defer type="text/javascript"></script>

<a href="<?php echo base_url('Admin_dashboard') ?>" class="logo back_logo_bg">
    <!-- Logo -->
    <span class="logo-mini">
        <img src="<?php echo  base_url() . (!empty($Soft_settings[0]['favicon']) ? $Soft_settings[0]['favicon'] : 'assets/img/icons/default.jpg') ?>"
            alt="">
    </span>
    <span class="logo-lg">
        <img src="<?php echo  base_url() . (!empty($Soft_settings[0]['logo']) ? $Soft_settings[0]['logo'] : 'assets/img/icons/default.jpg') ?>"
            alt="">
    </span>
</a>
<?php
$CI =& get_instance();
		$CI->load->library('session');
		$user_type = $CI->session->userdata('user_type');
		 
 ?>
<!-- Header Navbar -->
<nav class="navbar navbar-static-top color2">
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <!-- Sidebar toggle button-->
        <span class="sr-only">Toggle navigation</span>
        <span class="pe-7s-keypad"></span>
    </a>
	
    <div class="navbar-custom-menu">
	
        <ul class="nav navbar-nav">
		
            <?php
            if ($this->session->userdata('user_type') == 4) {
                $individual_store_wise_products = $CI->Reports->individual_store_wise_product();
                $individual_store_wise_products_count = 0;
                if ($individual_store_wise_products) :
                    foreach ($individual_store_wise_products as $individual_store_wise_product) :
                        $store_product = $individual_store_wise_product['quantity'] - $individual_store_wise_product['sell'];

                        if ($store_product < 10) {
                            $individual_store_wise_products_count++;
                        }
                    endforeach;
                endif;

            ?>
            <!-- ================================================= -->
			
            <li class="dropdown notifications-menu">
                <a href="<?php echo base_url('dashboard/Store_invoice/stock_report') ?>">
                    <i class="pe-7s-culture" title="<?php echo display('stock_report_store_wise') ?>"></i>
                    <span
                        class="label label-danger"><?php echo html_escape($individual_store_wise_products_count) ?></span>
                </a>
            </li>

            <!-- settings -->
            <li class="dropdown dropdown-user">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="pe-7s-settings"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo base_url('dashboard/Admin_dashboard/edit_profile') ?>"><i
                                class="pe-7s-users"></i><?php echo display('user_profile') ?></a></li>
                    <li><a href="<?php echo base_url('dashboard/Admin_dashboard/change_password_form') ?>"><i
                                class="pe-7s-settings"></i><?php echo display('change_password') ?></a></li>
                    <li><a href="<?php echo base_url('dashboard/Admin_dashboard/logout') ?>"><i
                                class="pe-7s-key"></i><?php echo display('logout') ?></a></li>
                </ul>
            </li>

            <!-- ================================================================================== -->

            <?php } else {
            ?>
			<?php if($user_type == 1): ?>
            <li class="dropdown notifications-menu">
                <a target="_blank" href="<?php echo base_url() ?>">
                    <i class="pe-7s-home" title="<?php echo display('go_to_website') ?>"></i>

                </a>
            </li>
            <li class="dropdown notifications-menu">
                <a href="<?php echo base_url('dashboard/Creport/out_of_stock') ?>">
                    <i class="pe-7s-attention" title="<?php echo display('out_of_stock') ?>"></i>
                    <span class="label label-danger" style="width:30px"><?php echo html_escape($out_of_stock) ?></span>
                </a>
            </li>
               <li class="dropdown notifications-menu">
                <a href="<?php echo base_url('dashboard/cexpriy_report/expriy_report_index') ?>">
                    <i class="pe-7s-attention" title="<?php echo 'Expiry Date' ?>"></i>
                    <span class="label label-danger"><?php echo html_escape($expiry_report_count) ?></span>
                </a>
            </li>
			<?php endif; ?>
            <!-- settings -->
            <li class="dropdown dropdown-user">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="pe-7s-settings"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo base_url('dashboard/Admin_dashboard/edit_profile') ?>"><i
                                class="pe-7s-users"></i><?php echo display('user_profile') ?></a></li>
                    <li><a href="<?php echo base_url('dashboard/Admin_dashboard/change_password_form') ?>"><i
                                class="pe-7s-settings"></i><?php echo display('change_password') ?></a></li>
                    <li><a href="<?php echo base_url('dashboard/Admin_dashboard/logout') ?>"><i
                                class="pe-7s-key"></i><?php echo display('logout') ?></a></li>
                </ul>
            </li>
            <?php } ?>
        </ul>
    </div>
	
    <div class="panel panel-default">
  <div class="panel-body">
    <!-- Single button -->
	 <?php if ($this->session->userdata('user_type') == 1) { ?>
    <div class="btn-group pull-right top-head-dropdown">
      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Order Notification <span class="caret"></span>
        <spna class="badge badge-primary"><?=  count($getCountNotifications) ?? '0';?></spna>
      </button>
      
      <ul class="dropdown-menu dropdown-menu-right">
        <?php if (count($notifications) > 0): ?>
        <?php foreach ($notifications as $notification): ?>

            <li>
                <?php $data = json_decode($notification->data);
                //print_r($data->order_id);die;
                ?>
               
                <a href="<?= base_url('notification/mark_as_read/' . $notification->id) ?>">Mark as Read</a>

              <a href="<?= base_url('notification/mark_as_read/' . $notification->id) ?>" class="top-text-block">
                <div class="top-text-heading"><?php echo $data->content;  ?></div>
                <div class="top-text-light"><?php echo date('F j, Y', strtotime($notification->created_at));  ?></div>
              </a> 
        
            </li>
            <hr>
        <?php endforeach; ?>
       <?php endif; ?>
       <li>
               
              <a href="<?= base_url('notification/show_all_notification/'.$user_id) ?>" class="btn-link bg-primary top-text-block">Show All Notification</a> 
            </li>
       
      </ul>
       
    </div>
	<div class="btn-group pull-right top-head-dropdown">
      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Subscription Expired <span class="caret"></span>
        <spna class="badge badge-primary"><?=  $expiredCount ?? '0';?></spna>
      </button>
      
      <ul class="dropdown-menu dropdown-menu-right">
        <?php if ($expiredCount > 0): ?>
        <?php foreach ($subscriptionData as $sub): ?>
							<?php 
							  $plan = $sub['plan']; // Assuming 'plan' is the column name in your database.
								//print($plan);die;
								$durationInDays = (int) filter_var($plan, FILTER_SANITIZE_NUMBER_INT);
								  $startDate = strtotime($sub['from_date']);
								$expiryDate = strtotime($sub['to_date']);
								//$expiryDate = strtotime('2023-11-01');
								//echo date('Y-m-d',$expiryDate);die;
								$currentTime = strtotime(date('Y-m-d')); 
								$timeRemaining = $expiryDate - $currentTime;
								
								// Define the threshold for showing the alert (e.g., 1 week in seconds).
								 $oneWeekInSeconds = 7 * 24 * 60 * 60;

								if ($timeRemaining < $oneWeekInSeconds) {
									
									// Display an alert message.
									$remarks = $sub['customer_name']." ".$sub['product_name']." subscription is expiring on ! ".date('j F Y',$expiryDate)."<br>Please inform this customer.";


								}
							  ?>
							  <li>
							  <a href="#" class="top-text-block">
								<div class="top-text-heading"><?php echo $remarks;  ?></b></div>
								<div class="top-text-light"><?php echo date('F j, Y', strtotime($sub['from_date']));  ?></div>
							  </a> 
							</li>
							
  
        <?php endforeach; ?>
       <?php endif; ?>
       
       
      </ul>
       
    </div>
	<?php  } ?>
  </div>
</div>

</nav>