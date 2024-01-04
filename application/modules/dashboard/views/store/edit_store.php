<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!--Update store start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('store_edit') ?></h1>
            <small><?php echo display('store_edit') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i><?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('store_set') ?></a></li>
                <li class="active"><?php echo display('store_edit') ?></li>
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

        <!--Edit store -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('store_edit') ?> </h4>
                        </div>
                    </div>
                  <?php echo form_open_multipart('dashboard/Cstore/store_update/{store_id}',array('class' => 'form-vertical', 'id' => 'validate'))?>
                    <div class="panel-body">

                        <div class="form-group row">
                            <label for="store_name" class="col-sm-3 col-form-label"><?php echo display('store_name') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input class="form-control" name ="store_name" id="store_name" type="text" placeholder="<?php echo display('store_name') ?>"  required="" value="{store_name}">
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <label for="store_address" class="col-sm-3 col-form-label"><?php echo display('store_address') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input class="form-control" name ="store_address" id="store_address" type="text" placeholder="<?php echo display('store_address') ?>"  required="" value="{store_address}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-3 col-form-label"><?php echo display('default_status')?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <select class="form-control" id="default_status" required="" name="default_status">
                                    <option value=""></option>
                                    <option value="1" <?php if ($default_status == 1) {echo "selected";}?>><?php echo display('yes')?></option>
                                    <option value="0" <?php if ($default_status == 0) {echo "selected";}?>><?php echo display('no')?></option>
                               </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-3 col-form-label">Target Value <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input class="form-control" id="target_value"  name="target_value" value="<?= $target_value; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-3 col-form-label" id="remarks_id">Target Remarks<i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                               <textarea class="form-control" cols="2" rows="2" id="remarks_id"  name="remarks_value"><?= $target_ramarks; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-6">
                                <input type="submit" id="update_store" class="btn btn-success btn-large" name="update_store" value="<?php echo display('save_changes') ?>" />
                            </div>
                        </div>
                    </div>
                    <?php echo form_close()?>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Update store end -->
<script type="text/javascript">
    // var attr_count = 1;
    // function add_more_attr(){
    //     attr_count++;


    //     var html = '<div class="row" id="attr_'+attr_count+'"> <div class="form-group col-md-3"> <label class="control-label" for="product_id_'+attr_count+'">Product</label> <select class="form-control" id="product_id_'+attr_count+'"  name="product_id[]" onchange="productSelectionChanged(' + attr_count + ')"><option value="">Select Product</option><?php foreach($products as $product): ?> <option value="<?= $product->id ?>"><?= $product->product_name ?></option>  <?php endforeach; ?> <!-- Add your product options here --> </select> </div><div class="form-group col-md-3"> <label class="control-label" for="product_id_'+attr_count+'">Target Value</label> <input class="form-control" id="product_id_'+attr_count+'"  name="target_value[]"> </div><div class="form-group col-md-3"> <label class="control-label" for="remarks_id_'+attr_count+'">Target Value</label> <textarea class="form-control" cols="2" rows="2" id="remarks_id_'+attr_count+'"  name="remarks_value[]"></textarea> </div> <div class="form-group col-md-3"> <label class="control-label"></label> <a class="btn btn-danger btn-block" onclick=remove_attr("'+attr_count+'") role="button">Remove</a> </div> </div>';
    //     $('#product_attr_box').append(html);
    // }
   

    // function remove_attr(attr_count){
    //     $('#attr_'+attr_count).remove();
    // }
function removeProductAttribute(attr_count,id) {
    var csrf_hash = '<?php echo $this->security->get_csrf_hash();?>'; 
    console.log(csrf_hash)
    // Send an AJAX request to the controller method for deleting the product attribute
    $.ajax({
        url: '<?= base_url('cstore/remove_store_target') ?>',
        type: 'POST',
        data: { id: id,<?php echo $this->security->get_csrf_token_name();?>: csrf_hash}, // Pass the attr_id as POST data
        dataType: 'json', // Expect a JSON response
        success: function(response) {
            if (response.success) {
                // If the delete was successful, remove the element from the page
                $('#attr_' + attr_count).remove();
            } else {
                // If there was an error, display an error message
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            // Handle AJAX errors here
            alert('AJAX request failed');
        }
    });
}

    var selectedProducts = []; // Array to store selected product IDs

    function checkDuplicateSelection(productID) {
        if (selectedProducts.includes(productID)) {
            // Display an error message or alert
            alert('Product already selected!');
            return false; // Prevent form submission
        }
        return true; // Allow form submission
    }

    function addToSelectedProducts(productID) {
        selectedProducts.push(productID); // Add the product to the selected list
    }

    function removeProductSelection(productID) {
        var index = selectedProducts.indexOf(productID);
        if (index !== -1) {
            selectedProducts.splice(index, 1); // Remove the product from the selected list
        }
    }

    // Example usage:
    function productSelectionChanged(attr_count) {
        var productDropdown = document.getElementById('product_id_' + attr_count);
        var productID = productDropdown.value;

        if (checkDuplicateSelection(productID)) {
            addToSelectedProducts(productID);
            // Continue with other actions (e.g., adding the product to the list)
        }
    }

    // function remove_attr(attr_count) {
    //     var productID = document.getElementById('product_id_' + attr_count).value;
    //     removeProductSelection(productID);
    //     // Continue with removing the product from the list
    // }
</script>


