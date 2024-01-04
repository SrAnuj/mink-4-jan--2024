
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>

<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Admin Home Start -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-world"></i>
       
        </div>
        <div class="header-title">
            <h1><?php echo display('dashboard')?></h1>
            <small><?php echo display('home')?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home')?></a></li>
                <li class="active"><?php echo display('dashboard')?></li>
            </ol>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Alert Message -->
        <?php
            $message = $this->session->userdata('message');
            if (isset($message)) {
            $this->session->unset_userdata('error_message');
        ?>
        <div class="alert alert-info alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $message ?>                    
        </div>
        <?php 
            $this->session->unset_userdata('message');
            }
            $error_message = $this->session->userdata('error_message');
            if (isset($error_message)) {
            $this->session->unset_userdata('message');
        ?>
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $error_message ?>                    
        </div>
        <?php 
            $this->session->unset_userdata('error_message');
            }
        ?>
        <!-- First Counter -->
        
        
        <?php
        $result = $this->db->select('*')
                        ->from('product_information')
                        ->where('status', 1)
                        ->get()
                        ->num_rows();
        
      $this->db->select('*');
        $this->db->from('product_information');
        $this->db->where('status', 1);
    
    
    //  $all_product_data = $this->db->select('
				// 	supplier_information.*,
				// 	product_information.*,
				// 	product_category.category_name,
				// 	unit.unit_short_name
				// ')
    //         ->from('product_information')
    //         ->join('supplier_information', 'product_information.supplier_id = supplier_information.supplier_id', 'left')
    //         ->join('product_category', 'product_category.category_id = product_information.category_id', 'left')
    //         ->join('unit', 'unit.unit_id = product_information.unit', 'left')
    //         ->order_by('product_information.product_name', 'asc')
    //         ->group_by('product_information.product_id')
    //         ->get();
        $all_product_data = $this->db->get()->result();
$this->db->select('*');
$this->db->from('product_purchase');
$this->db->order_by('invoice_no', 'desc');
$this->db->limit(1);  

$number = $this->db->get()->result();
// foreach($all_product_data as $pro)
// {
//     echo $pro->product_name;
//     die();
// }
// var_dump($all_product_data);
// var_dump($number);

// die();
        ?>
        <div class="row">
          

            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                <div class="panel panel-bd">
                    <div class="panel-body">
                        <div class="statistic-box">
                            <h2><span><?php echo html_escape($total_invoice)?></span><span class="slight"><i class="fa fa-play fa-rotate-270 text-warning"> </i></span></h2>
                            <div class="small"><?php echo display('total_invoice')?></div>
                            <div class="sparkline1 text-center"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                <div class="panel panel-bd">
                    <div class="panel-body">
					<a href="<?php echo base_url('customer/order/manage_order')?>">
                        <div class="statistic-box">
                            <h2><span><?php echo html_escape($total_order)?></span> <span class="slight"><i class="fa fa-play fa-rotate-270 text-warning"> </i></span></h2>
                            <div class="small"><?php echo display('total_order')?></div>
                            <div class="sparkline1 text-center"></div>
                        </div>
						</a>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                <div class="panel panel-bd">
                    <div class="panel-body">
                        <div class="statistic-box">
                            <h2><span class=""><?php echo html_escape($reward_list[0]['amount'])?></span> <span class="slight"><i class="fa fa-play fa-rotate-270 text-warning"> </i></span></h2>
                            <div class="small">Reward points</div>
                            <div class="sparkline1 text-center"></div>
                        </div>
                    </div>
                </div>
            </div>
			
			<?php if(!empty($c_vacations)): ?>
						<?php
						//echo $c_vacations[0]['end_date'];die;
			// Define the start and end date for leave mode
			$leaveModeStartDate = $c_vacations[0]['start_date']; // Convert to timestamp
			$leaveModeEndDate = $c_vacations[0]['end_date'];   // Convert to timestamp
			$currentDate = date('Y-m-d'); // Get the current date as a timestamp

			// Check if the current date is within the leave mode range
			if ($currentDate >= $leaveModeStartDate && $currentDate <= $leaveModeEndDate) {
				// Display the message for leave mode
				$message = "You are currently in leave mode until ".$c_vacations[0]['end_date'];
			} else {
				// Display a different message for when not in leave mode
				$message = "Your holidays will run from ".date('j F Y',strtotime($c_vacations[0]['start_date']))." to ".date('j F Y',strtotime($c_vacations[0]['end_date'])).".  Now you will have to pay ".$c_vacations[0]['total_amount']." amount";
			}
			?>
			  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
				<div class="panel panel-default">
				  
				  <div class="alert alert-danger"><?= $message; ?></div>
				  <div class="panel-body">
					<div class="table-responsive">
					<table class="table table-centered">
						
						<thead class="thead-dark">
							<tr>
							  <th scope="col">Order No.</th>
							  <th scope="col">Product Name</th>
							  <th scope="col">Start Date</th>
							  <th scope="col">End date</th>
							  <th scope="col">Due Amount</th>
							</tr>
						  </thead>
							  <tbody>
							  <?php foreach($c_vacations as $key => $vacation): ?>
								<tr>
								  <th scope="row"><?= $vacation['order_no']; ?> </th>
								  <td><?= $vacation['product_name'].' ('.$vacation['product_model'].')'; ?> </td>
								  <td><?= date('j F Y',strtotime($vacation['start_date'])); ?> </td>
								  <td><?=  date('j F Y',strtotime($vacation['end_date'])); ?> </td>
								  <td><?= $vacation['total_amount']; ?> </td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						
					</table>
					</div>
				  </div>
				</div>
			</div>
			<?php endif; ?>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
			<h3>Subcription Products</h3>
				<div class="panel panel-default">
				<?php if (isset($alert_msg)) : ?>
				<div class="alert alert-info">
					<?php echo $alert_msg; ?>
				</div>
			<?php endif; ?>
				  <div class="panel-body">
					<div class="table-responsive">
					<table class="table table-centered">
						<thead class="thead-dark">
							<tr>
							  <th scope="col">Sr no.</th>
							  <th scope="col">Product Name.</th>
							  <th scope="col">Start Date</th>
							  <th scope="col">Expired Date</th>
							  <th scope="col">Total Days</th>
							  <th scope="col">Stock Availability</th>
							  <th scope="col">Remarks</th>
							 
							</tr>
						  </thead>
							  <tbody>
							  <?php foreach($subscription_list as $key => $vacation): ?>
							  <?php 
							  $plan = $vacation['plan']; // Assuming 'plan' is the column name in your database.
								//print($plan);die;
								$durationInDays = (int) filter_var($plan, FILTER_SANITIZE_NUMBER_INT);
								  $startDate = strtotime($vacation['from_date']);
								 
								$expiryDate = strtotime($vacation['to_date']);
								//$expiryDate = strtotime('2023-11-01');
								//echo date('Y-m-d',$expiryDate);die;
								$currentTime = strtotime(date('Y-m-d')); 


								$timeRemaining = $expiryDate - $currentTime;
								
								// Define the threshold for showing the alert (e.g., 1 week in seconds).
								 $oneWeekInSeconds = 7 * 24 * 60 * 60;

								if ($timeRemaining < $oneWeekInSeconds) {
									
									// Display an alert message.
									$remarks = "Your " . $vacation['product_name'] . " subscription is expiring on " . date('j F Y', $expiryDate) . ".<br>Please renew it.";

								}
								
								$CI = &get_instance();
								$CI->load->model('dashboard/Reports');
								
								$stok_report = $CI->Reports->stock_report_bydate($vacation['product_id']);
								$sales = $CI->db->select("
								sum(quantity) as totalSalesQnty,
								quantity
								")
								->from('invoice_stock_tbl')
								->where('product_id', $vacation['product_id'])
								->get()
								->row();
								
							$stok_quantity_cartoon = ($stok_report[0]['totalPurchaseQnty'] - $sales->totalSalesQnty);
							
								//$remarks = "Hello";
							  ?>
							  
								<tr>
								  <th scope="row"><?= $key+1; ?> </th>
								  <td><?= $vacation['product_name'].' ('.$vacation['product_model'].')'; ?> </td>
								  <td><?= date('Y-m-d',strtotime($vacation['from_date'])); ?> </td>
								  <td><?= date('Y-m-d',$expiryDate); ?> </td>
								  <td><?= $plan; ?> </td>
								  <td><?php if($stok_quantity_cartoon < 0): ?><span class="badge badge-warning alert-danger" style="padding: 10px 10px;font-size:14px;"><?php echo $vacation['product_name']."stock quantity does not exist" ?></span><?php else: ?>
								  <span class="badge badge-success alert-success" style="padding: 10px 10px;font-size: 14px;">Stock Available</span>
								  <?php endif; ?></td>
								  <td><span class="badge badge-success alert-danger" style="padding: 10px 10px;font-size: 14px;"><?= $remarks ?? 'N/A'; ?></span> </td>
								  
								</tr>
								<?php endforeach; ?>
							</tbody>
						
					</table>
					</div>
				  </div>
				</div>
			</div>
            <?php if(check_module_status('loyalty_points') == 1){ ?>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="panel panel-bd">
                        <div class="panel-body">
                            <div class="statistic-box">
                                <h2><span class=""><?php echo html_escape($available_points[0]->current_points)?></span> <span class="slight"><i class="fa fa-play fa-rotate-270 text-warning"> </i></span></h2>
                                <div class="small"><?php echo display('available_points')?></div>
                                <div class="sparkline1 text-center"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section> <!-- /.content -->
</div> <!-- /.content-wrapper -->
<!-- Admin Home end -->


<script src="<?php echo MOD_URL . 'dashboard/assets/js/add_purchase_form.js'; ?>"></script>

    <script>
        function Toggle() {
            let val = document.getElementById('flexSwitchCheckChecked');
            val.checked ? true : false

            if(val.checked === true){
                alert('i am going to muree.')
            }

            console.log(val.checked);
       }
       </script>
 
