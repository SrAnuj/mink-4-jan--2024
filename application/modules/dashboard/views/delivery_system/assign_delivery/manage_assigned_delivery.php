<!-- Manage store Start -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css">
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('manage_assigned_delivery') ?></h1>
	        <small><?php echo display('manage_assigned_delivery') ?></small>
	        <ol class="breadcrumb">
	            <li><a href=""><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('manage_assigned_delivery') ?></a></li>
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
                <?php if($this->permission->check_label('assign_delivery')->create()->access()){ ?>
                  	<a href="<?php echo base_url('dashboard/Cdelivery_system/assign_delivery')?>" class="btn -btn-info color4 color5 m-b-5 m-r-2"><i class="ti-plus"></i><?php echo display('assign_delivery'); ?></a>
                 <?php } ?>
                </div>
            </div>
        </div>
		<div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?php echo form_open("", array('method' => 'GET','action' => "<?=  base_url('dashboard/Cdelivery_system/manage_assigned_delivery') ?>")); ?>
                        <div class="row form-group justify-content-center">
						<div class="col-md-3">
							<label for="customer_name" class="control-label">Select Date<i class="text-danger">*</i></label>
							<div id="reportrange"  class="text-center" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
								<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
								<span></span> <b class="caret"></b>
								
							</div>
							</div>
							<div class="col-md-3">
							 <label for="customer_name" class="control-label">Delivery Boy<i class="text-danger">*</i></label>
                                <select class="form-control" required name="delivery_boy_id" id="delivery_boy_id">
                                    <option value=""><?php echo display('select_delivery_boy') ?></option>
                                    <?php if ($delivery_boys) {
										foreach ($delivery_boys as $delivery_boy) { ?>
                                    <option value="<?php echo html_escape($delivery_boy->id) ?>">
                                        <?php echo html_escape($delivery_boy->name); ?></option>
                                    <?php }
									} ?>
                                </select>
							</div>
							<div class="col-md-3">
							 <label for="customer_name" class="control-label">Time zone<i class="text-danger">*</i></label>
                                <select class="form-control" required name="delivery_zone_id" id="delivery_zone_id">
                                    <option value=""><?php echo display('select_delivery_zone') ?></option>
                                    <?php if ($delivery_zones) {
										foreach ($delivery_zones as $delivery_zone) { ?>
                                    <option value="<?php echo html_escape($delivery_zone->id) ?>">
                                        <?php echo html_escape($delivery_zone->delivery_zone); ?></option>
                                    <?php }
									} ?>
                                </select>
							</div>
							<div class="col-sm-3">
                            <div class="form-group">
                                <button type="submit"
                                    class="btn btn-primary filter_btn"><?php echo display('search') ?></button>
                            </div>
                        </div>
                        </div>
                        
						<input type="hidden" id="date_ranger" name="date_ranger">


                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
		<!-- Manage store -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('manage_assigned_delivery') ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
		                <div class="table-responsive">
		                    <table id="dataTableExample3" class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th class="text-center"><?php echo display('sl') ?></th>
										<th class="text-center"><?php echo display('delivery_boy')?></th>
										<th class="text-center"><?php echo display('delivery_zone')?></th>
										<th class="text-center"><?php echo display('time_slot')?></th>
										<th class="text-center"><?php echo display('orders')?></th>
										<th class="text-center"><?php echo display('status') ?></th>
										<th class="text-center"><?php echo display('action') ?></th>
									</tr>
								</thead>
								<tbody>
								<?php
								if ($assigned_deliveries) {
									$i=$page+1;
									foreach ($assigned_deliveries as $assigned_delivery) {
								?>
									<tr>
										<td class="text-center"><?php echo $i++; ?></td>
										<td class="text-center"><?php echo html_escape($assigned_delivery['name'])?></td>
										<td class="text-center"><?php echo html_escape($assigned_delivery['delivery_zone'])?></td>
										<td class="text-center"><?php echo html_escape($assigned_delivery['title'])?></td>
										
										<td class="text-center">
										<?php
											$this->db->select('a.order_no,b.order_id');
											$this->db->from('delivery_orders a');
											$this->db->join('order b','a.order_no = b.order');
											$this->db->where('delivery_id',$assigned_delivery['delivery_id']);
											$query = $this->db->get()->result();
										 
											$result="";
											foreach($query as $item) :
											$result.='<a href="'.base_url().'dashboard/Corder/order_details_data/'.$item->order_id.'">'.$item->order_no.'</a>'.', '; 
											endforeach;
											$trimmed=rtrim($result, ', ');
											echo $trimmed;
										?>
										</td>
										<td class="text-center">
											<?php if ($assigned_delivery['status'] == '1') { ?>
											<span class="label label-success"><?php echo display('active'); ?></span>
											<?php }else{ ?> 
											<span class="label label-danger"><?php echo display('inactive'); ?></span>
											<?php } ?>
										</td>
										<td>
											<center>
												<?php if($this->permission->check_label('manage_assigned_delivery')->update()->access()){ ?>
												<a href="<?php echo base_url().'dashboard/Cdelivery_system/edit_assigned_delivery/'.$assigned_delivery['delivery_id']; ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('update') ?>">
													<i class="fa fa-pencil" aria-hidden="true"></i>
												</a>
												<?php }if($this->permission->check_label('manage_assigned_delivery')->delete()->access()){?>
												<a href="<?php echo base_url('dashboard/Cdelivery_system/assigned_delivery_delete/'.$assigned_delivery['delivery_id'])?>" class="delete_store_product btn btn-danger btn-sm" onclick="return confirm('<?php echo display('are_you_sure_want_to_delete')?>');" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo display('delete') ?> ">
													<i class="fa fa-trash-o" aria-hidden="true"></i>
												</a>
												<?php } ?>
											</center>
										</td>
									</tr>
								<?php
									}
								}
								?>
								</tbody>
		                    </table>
		                </div>
		                <div class="text-right">
		                	<?php echo htmlspecialchars_decode($links); ?>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</section>
</div>
<script>
$(function() {
    // Calculate the current date
    var currentDate = moment();

    // Initialize the start and end dates
    var initialStartDate = moment().subtract(29, 'days');
    var initialEndDate = moment();

    // Function to update the planned days
    function updatePlannedDays(startDate, endDate) {
        var plannedDays = endDate.diff(startDate, 'days') + 1;
        $('#plannedDays').val('Total : ' + plannedDays + ' days');
    }

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $('#date_ranger').val(start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        
        // Call the updatePlannedDays function with the selected date range
        updatePlannedDays(start, end);
    }

    $('#reportrange').daterangepicker({
        startDate: initialStartDate,
        endDate: initialEndDate,
        minDate: currentDate, // Set the minimum selectable date to the current date
    }, cb);

    cb(initialStartDate, initialEndDate);

    // Event handler for the "Apply" button
    $('#applyButton').click(function() {
        var selectedDateRange = $('#date_ranger').val();
        var dates = selectedDateRange.split(' to ');
        var startDate = moment(dates[0], 'YYYY-MM-DD');
        var endDate = moment(dates[1], 'YYYY-MM-DD');

        // Call the updatePlannedDays function with the selected date range
        updatePlannedDays(startDate, endDate);
    });
});
</script>
<script>
if (window.performance && window.performance.navigation.type === 1) {
    // Page was manually refreshed, you can perform actions here.
    // For example, redirect to the page without filters.
    window.location.href = "<?php echo base_url('dashboard/Cdelivery_system/manage_assigned_delivery') ?>";
}
</script>

</html>

<!-- Manage store End -->