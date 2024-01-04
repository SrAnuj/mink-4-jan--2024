<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Manage order Start -->

<?php


/* d($subscription_list);
die(); */

?>


<style>
     .vacation {
            width: 96%;
            padding: 0.4rem;
            border-radius: 0.3rem;
            border: 1px solid silver;
            outline: none;
        }

        #main_div {
            display: flex;
        }

        #main_div_1 {
            width: 100%;
        }
</style>
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('manage_subscription') ?></h1>
	        <small><?php echo display('manage_subscription') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('subscription') ?></a></li>
	            <li class="active"><?php echo display('manage_subscription') ?></li>
	        </ol>
	    </div>
	</section>

	<section class="content">
		<!-- Alert Message -->
	    <?php
	        $message = $this->session->userdata('message');
	        if (isset($message)) {
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
	    ?>
	    <div class="alert alert-danger alert-dismissable">
	        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	        <?php echo $error_message ?>                    
	    </div>
	    <?php 
	        $this->session->unset_userdata('error_message');
	        }
	    ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                  	<a href="<?php echo base_url('dashboard/subscription/add_new')?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo display('add_subscription')?></a>
                </div>
            </div>
        </div>

		<!-- Manage order report -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('manage_subscription') ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
		                <div class="table-responsive">
		                    <table id="dataTableExample2" class="table table-bordered table-striped table-hover">
		                    	<thead>
									<tr>
										<th><?php echo display('sl') ?></th>
										<th><?php echo display('customer_name') ?></th>
										<th><?php echo display('product_name') ?></th>
										<th>Order Id</th>
										<th>Total Days</th>
										<th>Batch No.</th>
										<th><?php echo display('date') ?></th>
										<th><?php echo display('total_amount') ?></th>
										
										
										<th><?php echo 'Vacational Mode' ?></th>

									</tr>
								</thead>
								<tbody>
								<?php
								if ($subscription_list) {
									foreach ($subscription_list as $order) {
								?>
									<tr>
										<td><?php echo html_escape($order['sl'])?></td>
										<td><?php echo html_escape($order['customer_name'])?></td>
										<td><?php echo html_escape($order['product_name'].' ('.$order['product_model'].')')?></td>
										<td><?php echo html_escape($order['order_no'])?></td>
										<td><?php echo html_escape($order['plan'])?></td>
										<td><?php echo html_escape($order['batch_no'])?></td>
										<td><?php echo html_escape($order['final_date'])?></td>
										<td class="text-left"><strong> Payable :</strong><?php echo (($position==0)?$currency.' '.html_escape($order['total_amount']):html_escape($order['total_amount']).' '.$currency) ?><br/>
										<strong>Due :</strong>
										<?php echo (($position==0)?$currency.' '.html_escape($order['due_amount']):html_escape($order['due_amount']).' '.$currency) ?><br/><strong>Paid :</strong><?php echo (($position==0)?$currency.' '.html_escape($order['paid_amount']):html_escape($order['paid_amount']).' '.$currency) ?><br/><strong>Charge:</strong>
										<?php echo (($position==0)?$currency.' '.html_escape($order['service_charge']):html_escape($order['service_charge']).' '.$currency) ?>
										</td>
										<td class="text-center"><?php if(empty($order['start_date'])) {echo '<span class="badge alert-success btn-block">Presentable<span>';}else{echo '<span class="badge alert-danger btn-block">Absent<span>';} ?></td> 
									</tr>
								<?php
									}
								}
								?>
								</tbody>
		                    </table>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</section>
</div>
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">vacation mode</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="main_div">
                        <div id="main_div_1">
                            <label for="startDate">to</label>
                            <br>
                            <input type="date" name="package" id="startDate">
                        </div>

                        <div id="main_div_1">
                            <label for="endDate">from</label>
                            <br>
                            <input type="date" name="package" id="endDate">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="" onclick="VacationalMod()">Save
                        changes</button>
                </div>
            </div>
        </div>
    </div>
  <script>
  let a;
  function vacation(id)
        {
        //  console.log(x);
       a = id ;
        // // VacationalMod(id);
        // alert(a);
         return a;
        };
        
        
            function VacationalMod() {
            let startDate = document.getElementById('startDate').value;
            let endDate = document.getElementById('endDate').value;
            var vacation_id = a;
            var csrf_test_name=  $("#CSRF_TOKEN").val();

                        // var vacation_id = $('#vacation_id').val();

            // alert(startDate,endDate)
            // alert(endDate)
            startDate.value = null;
            endDate.value = null;

            document.getElementById('startDate').checked = true;

            console.log(startDate);
            console.log(endDate);
                        console.log(vacation_id);
                        
                        		$.ajax({
				type: "POST",
				
				url:base_url + 'web/customer/Corder/vacational_mode',
                                //url: "http://localhost/product/delete_products"
				data: {startDate: startDate,endDate:endDate,vacation_id:vacation_id,csrf_test_name:csrf_test_name},
				dataType: "html",
				cache: false,
				success: function(msg) {
				   {
				       console.log(msg);
				setTimeout("location.reload(true);",300);

			} 
				},
				error: function(jqXHR, textStatus, errorThrown) {
					$("#msg").html("<span style='color:red;'>" + textStatus + " " + errorThrown + "</span>");
				}
			});


        }

       
    </script>
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
<!-- Manage order End -->