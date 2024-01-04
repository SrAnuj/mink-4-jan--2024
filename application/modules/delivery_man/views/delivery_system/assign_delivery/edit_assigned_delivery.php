<!-- Manage store Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('edit_assigned_delivery') ?></h1>
            <small><?php echo display('edit_assigned_delivery') ?></small>
            <ol class="breadcrumb">
                <li><a href=""><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('edit_assigned_delivery') ?></a></li>
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
                    <?php if ($this->permission->check_label('manage_assigned_delivery')->read()->access()) { ?>
                    <a href="<?php echo base_url('dashboard/Cdelivery_system/manage_assigned_delivery') ?>"
                        class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"> </i>
                        <?php echo display('manage_assigned_delivery') ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Manage store -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('edit_assigned_delivery') ?></h4>
							<br/>
							<?php
							if(count($assigned_sub_delivery_order_info) > 0 && $assigned_sub_delivery_order_info[0]->paid_amount != $assigned_sub_delivery_order_info[0]->total_amount){
								echo '<span class="bg-primary text-white" style="padding:5px">Paid Amount :'.$assigned_sub_delivery_order_info[0]->paid_amount.'</span>';
								?> <br/><br/><?php
								echo '<span class="bg-primary text-white" style="padding:5px">Total due amount :'.$assigned_sub_delivery_order_info[0]->due_amount.'</span>';
								
							} ?>
                        </div>
                    </div>
                    <div class="panel-body">
					   
                        <?php echo form_open('delivery_man/customer/Delivery_dashboard/edit_assigned_delivery/' . $assigned_delivery_info['delivery_id'], 'enctype=multipart/form-data'); ?>
						<?php if(!empty($assigned_sub_delivery_order_info) && count($assigned_sub_delivery_order_info) > 0): ?>
							<input type="hidden" name="subscription" id="subscription" value="subscription">
						<?php endif; ?>
                        <div class="form-group row">
                            <label for="delivery_boy_id"
                                class="col-sm-3 col-form-label"><?php echo display('delivery_boy') ?><i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-6">
							<?php 
							$delivery_boy_id = (int) $assigned_delivery_info['delivery_boy_id'];
							$index = array_search($delivery_boy_id, array_column($delivery_boys, 'id'));

							if ($index !== false) {?>

								<input class="form-control" type="text" readonly value="<?php echo $delivery_boys[$index]->name; ?>">
								
								<input class="form-control" type="hidden" 
								name="delivery_boy_id" id="delivery_boy_id" value="<?php echo $delivery_boys[$index]->id; ?>">
                                    
								<?php }
							
							?>
                                <!--<select class="form-control" required name="delivery_boy_id" id="delivery_boy_id">
                                    <option value=""><?php echo display('select_delivery_boy') ?></option>
                                    <?php if ($delivery_boys) {
                                        foreach ($delivery_boys as $delivery_boy) { ?>
                                    <option value="<?php echo html_escape($delivery_boy->id) ?>"
                                        <?php echo ($delivery_boy->id == $assigned_delivery_info['delivery_boy_id']) ? 'selected' : '' ?>>
                                        <?php echo html_escape($delivery_boy->name); ?></option>
                                    <?php }
                                    } ?>
                                </select>-->
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="delivery_zone_id"
                                class="col-sm-3 col-form-label"><?php echo display('delivery_zone') ?><i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-6">
							<?php 
							$delivery_zone_id = (int) $assigned_delivery_info['delivery_zone_id'];
							
							$index_01 = array_search($delivery_zone_id, array_column($delivery_zones, 'id'));

							if ($index_01 !== false) {?>

								<input class="form-control" type="text" readonly value="<?php echo $delivery_zones[$index_01]->delivery_zone; ?>">
								
								<input class="form-control" type="hidden" 
								name="delivery_zone_id" id="delivery_zone_id" value="<?php echo $delivery_zones[$index_01]->id; ?>">
                                    
							<?php }
												
							?>
                                <!-- <select class="form-control readonly" required name="delivery_zone_id" id="delivery_zone_id">
                                    <option value=""><?php echo display('select_delivery_zone') ?></option>
                                    <?php if ($delivery_zones) {
                                        foreach ($delivery_zones as $delivery_zone) { ?>
                                    <option value="<?php echo $delivery_zone->id ?>"
                                        <?php echo ($delivery_zone->id == $assigned_delivery_info['delivery_zone_id']) ? 'selected' : '' ?>>
                                        <?php echo html_escape($delivery_zone->delivery_zone); ?></option>
                                    <?php }
                                    } ?>
                                </select> -->
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="time_slot_id"
                                class="col-sm-3 col-form-label"><?php echo display('time_slot') ?><i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-6">
							<?php 
							$time_slot_id = (int) $assigned_delivery_info['time_slot_id'];
							
							$index_02 = array_search($time_slot_id, array_column($time_slots, 'id'));

							if ($index_02 !== false) {?>

								<input class="form-control" type="text" readonly value="<?php echo $time_slots[$index_02]->title; ?>">
								
								<input class="form-control" type="hidden" 
								name="time_slot_id" id="time_slot_id" value="<?php echo $time_slots[$index_02]->id; ?>">
                                    
							<?php }
												
							?>
                                <!--<select class="form-control" required name="time_slot_id" id="time_slot_id">
                                    <option value=""><?php echo display('select_time_slot') ?></option>
                                    <?php if ($time_slots) {
                                        foreach ($time_slots as $time_slot) { ?>
                                    <option value="<?php echo $time_slot->id ?>"
                                        <?php echo ($time_slot->id == $assigned_delivery_info['time_slot_id']) ? 'selected' : '' ?>>
                                        <?php echo html_escape($time_slot->title); ?></option>
                                    <?php }
                                    } ?>
                                </select> -->
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="order_no" class="col-sm-3 col-form-label"><?php echo display('orders') ?><i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <select class="form-control" required name="order_no[]" id="order_no">
                                    <option value=""><?php echo display('select_orders') ?></option>
                                    <?php if ($pending_orders) {
                                        foreach ($pending_orders as $pending_order) { ?>
                                    <option value="<?php echo html_escape($pending_order->order) ?>"
                                        <?php echo (in_array($pending_order->order, $delivery_orders) ? 'selected' : '') ?>>
                                        <?php echo html_escape($pending_order->order) ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="completed_at"
                                class="col-sm-3 col-form-label"><?php echo display('completed_at') ?></label>
                            <div class="col-sm-6">
                                <input class="form-control" type="datetime-local"
                                    value="<?php echo html_escape($assigned_delivery_info['completed_at']) ?>"
                                    name="completed_at" id="completed_at">
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <label for="note" class="col-sm-3 col-form-label"><?php echo display('note') ?></label>
                            <div class="col-sm-6">
                                <textarea class="form-control summernote" rows="3" name="note"
                                    id="note"><?php echo html_escape($assigned_delivery_info['note']) ?></textarea>
                            </div>
                        </div>-->
                        <div class="form-group row">
                            <label for="radio_7" class="col-sm-3 col-form-label"><?php echo display('status') ?></label>
                            <label class="radio-inline">
                                <input type="radio" class="col-sm-2 col-form-label" name="status" value="Active"
                                    <?php echo (($assigned_delivery_info['status'] == 'Active') ? 'checked' : '') ?>><strong><?php echo display('active'); ?></strong>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="col-sm-2 col-form-label" name="status" value="Inactive"
                                    <?php echo (($assigned_delivery_info['status'] == 'Inactive') ? 'checked' : '') ?>>
                                <strong><?php echo display('inactive'); ?> </strong>
                            </label>
							<label class="radio-inline">
                                <input type="radio" class="col-sm-2 col-form-label" name="status" value="Completed"
                                    <?php echo (($assigned_delivery_info['status'] == 'Completed') ? 'checked' : '') ?>>
                                <strong>Complete </strong>
                            </label>
							<label class="radio-inline">
                                <input type="radio" class="col-sm-2 col-form-label" name="status" value="Incomplete"
                                    <?php echo (($assigned_delivery_info['status'] == 'InComplete') ? 'checked' : '') ?>>
                                <strong>InComplete </strong>
                            </label>
                        </div>
						<div id="showExtraFeild" style="display:none">
						<div class="form-group row">
						<label for="example-text-input" class="col-sm-3 col-form-label">Customer Amount</label>
						<div class="col-sm-6">
						<input id="amount" name="amount" class="form-control" value="">
						</div>
						</div>
						<div class="form-group row">
						<label for="example-text-input" class="col-sm-3 col-form-label">Remarks</label>
						<div class="col-sm-6">
						<textarea id="remarks" name="remarks" rows="4" cols="50" class="form-control"><?php echo $assigned_delivery_info['remarks'] ? $assigned_delivery_info['remarks'] : '' ?></textarea>
						</div>
						</div>
						<div class="form-group row">
						<label for="example-text-input" class="col-sm-3 col-form-label">Insert Image</label>
						<div class="col-sm-6">
						
						<input type="file" id="cfile" name="cfile" accept="image/*" capture="camera" value="<?php echo $assigned_delivery_info['report_file'] ? $assigned_delivery_info['report_file'] : '' ?>" class="form-control form-control-sm">
						</div>
						</div>

						<div class="form-group row">
						<img id="imagePreview" src="#" alt="Preview" style="display: none; max-width: 200px; max-height: 200px;">
						</div>
						</div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-6">
                                <input type="submit" id="update-item" class="btn btn-success btn-large"
                                    name="update-item" value="<?php echo display('update') ?>" />
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Manage store End -->
<script>
$(document).ready(function() {
	
	 const imageInput = $("#cfile");
  const imagePreview = $("#imagePreview");

  // Add an event listener to the input field for the 'change' event
  imageInput.on("change", function() {
    // Check if a file is selected
    if (this.files && this.files[0]) {
      const reader = new FileReader();

      // Define a function to run when the image is loaded
      reader.onload = function(e) {
        // Set the source of the image preview to the loaded image data
        imagePreview.attr("src", e.target.result);

        // Show the image preview
        imagePreview.css("display", "block");
      };

      // Read the selected image file as a data URL
      reader.readAsDataURL(this.files[0]);
    } else {
      // No file selected, hide the image preview
      imagePreview.css("display", "none");
    }
  });
  
   // Load an existing image when editing (replace 'existing_image_url' with the actual URL)
  const existingImageUrl = "<?= base_url($assigned_delivery_info['report_file']) ?>";
  imagePreview.attr("src", existingImageUrl);
  imagePreview.css("display", "block");
	// Get all radio buttons with the name "status"
let statusButtons = $("input[name=status]");
  let selectedValue = statusButtons.filter(":checked").val();
  console.log(selectedValue);
// Check if any of the radio buttons are checked

  // Get the value of the checked radio button
  

  // Check if the value is 'Completed'
  if (selectedValue == 'Completed') {
    $("#showExtraFeild").show();
	$("#amount").prop('required',true);
	$("#cfile").prop('required',true);
	//$("#remarks").prop('required',true);
  }else if(selectedValue == 'Incomplete'){
	  $("#showExtraFeild").show();
	  $("#amount").prop('required',false);
	  $("#cfile").prop('required',true);
	//$("#remarks").prop('required',true);
  } else {
    $("#showExtraFeild").hide();
	$("#amount").prop('required',false);
	$("#cfile").prop('required',false);
	//$("#remarks").prop('required',false);
  }
  

	
  // Add a change event listener to the radio buttons with the class "showHideRadio"
  $("input[name=status]").change(function() {
	 
    // Check if the radio button with value "specific" is checked
    if ($(this).val() === "Completed") {
      // Show the target div
      $("#showExtraFeild").show();
	  $("#amount").prop('required',true);
	  $("#cfile").prop('required',true);
	   //$("#remarks").prop('required',true);
    } else if($(this).val() === "Incomplete"){
		$("#showExtraFeild").show();
		$("#amount").prop('required',false);
		 $("#cfile").prop('required',true);
	  // $("#remarks").prop('required',true);
	}
	else {
      // Hide the target div for other values
      $("#showExtraFeild").hide();
	  $("#amount").prop('required',false);
	  $("#cfile").prop('required',false);
	  //$("#remarks").prop('required',false);
    }
  });
});
 $(document).ready(function () {
        $("#delivery_boy_id").select2({
            maximumSelectionLength: 0, // Set the maximum number of selections allowed
        });
    });
</script>