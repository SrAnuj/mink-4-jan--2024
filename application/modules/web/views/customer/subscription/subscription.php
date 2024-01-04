<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Manage order Start -->

<?php


// var_dump($subscription_list);
// die();

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
.ajax-loader {
  visibility: hidden;
  background-color: rgba(255,255,255,0.7);
  position: absolute;
  z-index: +100 !important;
  width: 100%;
  height:100%;
}

.ajax-loader img {
  position: relative;
  top:50%;
  left:50%;
}
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
                  	<a href="<?php echo base_url('customer/subscription/insert_subscription')?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo display('add_subscription')?></a>
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
										<th><?php echo display('order') ?></th>
										<th><?php echo display('date') ?></th>
										<th><?php echo display('total_amount') ?></th>
										<th><?php echo display('service_charge') ?></th>
										<th><?php echo display('paid') ?></th>
										<th><?php echo display('due') ?></th>
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
										<td><?php echo html_escape($order['order_no'])?></td>
										<td><?php echo html_escape($order['final_date'])?></td>
										<td class="text-right"><?php echo (($position==0)?$currency.' '.html_escape($order['total_amount']):html_escape($order['total_amount']).' '.$currency) ?></td><td class="text-right"><?php echo (($position==0)?$currency.' '.html_escape($order['service_charge']):html_escape($order['service_charge']).' '.$currency) ?></td>
										<td class="text-right"><?php echo (($position==0)?$currency.' '.html_escape($order['paid_amount']):html_escape($order['paid_amount']).' '.$currency) ?></td>
										<td class="text-right"><?php echo (($position==0)?$currency.' '.html_escape($order['due_amount']):html_escape($order['due_amount']).' '.$currency) ?></td>
										<td>  <div class="form-check form-switch">
										    <input type ="hidden" value="<?php echo $order['id'] ?>" id ="vacation_id">
        <input class="form-check-input vacation" type="checkbox" role="switch" id="flexSwitchCheckDefault" onclick=vacation('<?php echo $order['id'] ?>')
            class="btn btn-primary" href="#" data-toggle="modal" data-target="#loginModal">
    </div></td>
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
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Vacation mode</h1>
										
                </div>
                <div class="modal-body">
				<div class="ajax-loader">
				  <img src="<?= base_url('my-assets/image/loader.gif') ?>" class="img-responsive" />
				</div>
                        <div class="row form-group">
						<div class="col-md-4">
                            <label for="startDate">Start Date</label>
                            <br>
                            <input type="date" class="form-control" name="package" id="startDate">
                        </div>
						<div class="col-md-4">
						 <label for="endDate">End Date</label>
                            <br>
                            <input type="date" class="form-control" name="package" id="endDate">
						</div>
						<div class="col-md-4">
						 <label for="total_days">Calculate Days</label>
                            <br>
                            <input type="text" readonly class="form-control" name="total_days" id="total_days">
						</div>
						</div>
                       
                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    <button type="button" id="submit_button" class="btn btn-primary" data-bs-dismiss="" onclick="VacationalMod()">Save
                        changes</button>
                </div>
            </div>
        </div>
    </div>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
				dataType: "json",
				cache: false,
				 beforeSend: function(){
					$('.ajax-loader').css("visibility", "visible");
				  },
				success: function(res) {
					console.log(res);
						if(res.d_mail_status === 200){
							toastr.success(res.message);
						}else{
							toastr.error(res.message);
						}
						 if (res.recipient_emails.status == 200) {
							// Update the content of the message-container <div> with the message
							//$('.admin_message').html(res.recipient_emails.message);
							$.each(res.recipient_emails.data, function (index, message) {
									toastr.success(message);
								});
						}else{
							$.each(res.recipient_emails.data, function (index, errorMessage) {
								toastr.success(errorMessage);
							});
						} 
						 
							
							
						
						
					$('#loginModal').modal('hide');
					setTimeout("location.reload(true);",3000);

					
				},
				complete: function(){
				$('.ajax-loader').css("visibility", "hidden");
			  },
				error: function(jqXHR, textStatus, errorThrown) {
					$("#msg").html("<span style='color:red;'>" + textStatus + " " + errorThrown + "</span>");
				}
			});


        }

       
    </script>
  <script>
        $(document).ready(function () {
    // Get the current date in the "YYYY-MM-DD" format
			var currentDate = new Date().toISOString().split("T")[0];

			// Set the min attribute of the start date input to the current date
			$("#startDate").attr("min", currentDate);

			// Attach an event listener to the end date input to check if it's equal to the start date
			$("#endDate").on("change", function () {
				var startDate = $("#startDate").val();
				var endDate = $(this).val();

				if (startDate === endDate) {
					alert("End date cannot be equal to the start date.");
					$(this).val("");
				}
			});

			// Handle form submission (you can replace this with your actual form submission logic)
			$("#submit_button").on("click", function () {
				var startDate = $("#startDate").val();
				var endDate = $("#endDate").val();

				// Validate the start date against the current date
				if (startDate < currentDate) {
					alert("Start date cannot be less than the current date.");
					return false; // Prevent form submission
				}

				// Validate that end date is not equal to start date (this check is already done in the change event)
				
				// If all validation passes, you can proceed with form submission
				// Otherwise, you can prevent the form submission as shown above
			});
		});

	   $(document).ready(function () {
            // Add a change event listener to the "end-date" input
            $('#endDate').change(function () {
                calculateDays();
            });

            // Function to calculate days between dates
            function calculateDays() {
                // Get the values from the date inputs
                var startDateStr = $('#startDate').val();
                var endDateStr = $('#endDate').val();

                // Convert the date strings to Date objects
                var startDate = new Date(startDateStr);
                var endDate = new Date(endDateStr);

                // Calculate the difference in milliseconds
                var timeDifference = endDate - startDate;

                // Convert milliseconds to days
                var daysDifference = timeDifference / (1000 * 60 * 60 * 24);
				if(isNaN(daysDifference)){
					daysDifference = 0;
				}
                // Display the result
                $('#total_days').val('Total : ' + daysDifference + ' days');
            }

            // Calculate days initially (optional)
            calculateDays();
        });
       </script>
<!-- Manage order End -->