<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Customer js php -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/customer.js.php"></script>
<!-- Product invoice js -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/product_invoice.js.php"></script>

<!-- invoice js -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/invoice.js" type="text/javascript"></script>
<!-- page css -->
<link rel="stylesheet" href="<?php echo MOD_URL.'dashboard/assets/css/order/add_order_form.css' ?>">
<style>
span.select2.select2-container.select2-container--default{width:unset !important;display:unset !important;}
</style>
<!-- Add new order start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('order') ?></h1>
            <small><?php echo display('new_order') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('order') ?></a></li>
                <li class="active"><?php echo display('new_order') ?></li>
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
                    <?php  if($this->permission->check_label('manage_order')->read()->access()){ ?>
                    <a href="<?php echo base_url('dashboard/Corder/manage_order') ?>"
                       class="btn btn-info color4 color5 m-b-5 m-r-2"><i
                                class="ti-align-justify"> </i> <?php echo display('manage_order') ?></a>
                    <?php } if($this->permission->check_label('pos_sale')->read()->access()){ ?>
                    <a href="<?php echo base_url('dashboard/Cinvoice/pos_invoice') ?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('pos_invoice') ?></a>
                <?php } ?>
                </div>
            </div>
        </div>

        <!--Add order -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('order') ?></h4>
                        </div>
                    </div>
                    <?php echo form_open_multipart('dashboard/Corder/insert_order', array('class' => 'form-vertical', 'id' => 'validate')) ?>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-8" id="payment_from_1">
                                <div class="form-group row">
                                    <label for="customer_name"
                                           class="col-sm-3 col-form-label"><?php echo display('name') ?> <i
                                                class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                        <input type="text"  name="customer_name" class="customerSelection form-control"
                                        placeholder='<?php echo display('customer_name_or_phone') ?>' id="customer_name" autocomplete="off"
                                        />
                                        <input id="SchoolHiddenId" class="customer_hidden_value" type="hidden"
                                               name="customer_id">
                                    </div>
                                    <div class=" col-sm-3">
                                        <input id="myRadioButton_1" type="button"
                                               onClick="active_customer('payment_from_1')" id="myRadioButton_1"
                                               class="btn btn-success checkbox_account" name="customer_confirm"
                                               checked="checked" value="<?php echo display('new_customer') ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-8" id="payment_from_2">
                                <div class="form-group row">
                                    <label for="customer_name_others"
                                           class="col-sm-3 col-form-label"><?php echo display('name') ?> <i
                                                class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                        <input autofill="off" type="text" name="customer_name_others"
                                               placeholder='<?php echo display('name') ?>'
                                               id="customer_name_others" class="form-control"/>
                                    </div>

                                    <div class="col-sm-3">
                                        <input onClick="active_customer('payment_from_2')" type="button"
                                               id="myRadioButton_2" class="checkbox_account btn btn-success"
                                               name="customer_confirm_others"
                                               value="<?php echo display('old_customer') ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="customer_name_others_address"
                                           class="col-sm-3 col-form-label"><?php echo display('address') ?> </label>
                                    <div class="col-sm-6">
                                        <input type="text" name="customer_name_others_address" class=" form-control"
                                               placeholder='<?php echo display('address') ?>'
                                               id="customer_name_others_address"/>
                                    </div>
                                </div>
								<div class="form-group row">
                                    <label for="customer_name_others_address"
                                        class="col-sm-3 col-form-label"><?php echo display('email') ?> </label>
                                    <div class="col-sm-6">
                                        <input type="email" size="100" name="customer_email" class=" form-control"
                                            placeholder='<?php echo display('email') ?>' id="email" />
                                    </div>
                                </div>
								<div class="form-group row">
                                    <label for="customer_name_others_address"
                                        class="col-sm-3 col-form-label"><?php echo display('password') ?> </label>
                                    <div class="col-sm-6">
                                        <input type="password" size="100" name="password" class=" form-control"
                                            placeholder='<?php echo display('password') ?>' id="password" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="customer_name_others_address"
                                        class="col-sm-3 col-form-label"><?php echo display('mobile') ?> </label>
                                    <div class="col-sm-6">
                                        <input type="number" size="100" name="customer_mobile_no" class=" form-control"
                                            placeholder='<?php echo display('mobile') ?>' id="customer_mobile_no" />
                                    </div>
                                </div>
                                <div class="form-group row">
                            <label for="customer_zone" class="col-sm-3 col-form-label">Zone</label>
                            <div class="col-sm-6">
                                <select class="form-control select2 width_100p" id="customer_zone" name="customer_zone">
								<option value="">Select Zone</option>
								<?php if ($zone_list) { ?>
									<?php foreach ($zone_list as $zone) { ?>
										<option value="<?php echo $zone['id']; ?>">
											<?php echo $zone['delivery_zone']; ?>
										</option>
									<?php } ?>
								<?php } ?>
							</select>

                            </div>
                        </div>
								<div class="form-group row">
                            <label for="city " class="col-sm-3 col-form-label"><?php echo display('city') ?></label>
                            <div class="col-sm-6">
                                <input class="form-control" name="city" id="city" type="text"
                                    placeholder="<?php echo display('city') ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="country "
                                class="col-sm-3 col-form-label"><?php echo display('country') ?></label>
                            <div class="col-sm-6">
                                <select class="form-control select2 width_100p" id="country" name="country">
                                    <option value=""><?php echo display('select_one') ?></option>
                                    <?php foreach ($country_list as $country) { ?>
                                   
                                    <option value="<?php echo $country['id'] ?>"><?php echo $country['name'] ?></option>
                           
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="state" class="col-sm-3 col-form-label"><?php echo display('state') ?></label>
                            <div class="col-sm-6">
                                <select class="form-control select2 width_100p" id="state" name="state">
                                    <option value=""><?php echo display('select_one') ?></option>
                                </select>
                            </div>
                        </div>
						<div class="form-group row">
                                    <label for="zip"
                                        class="col-sm-3 col-form-label"><?php echo display('zip') ?> </label>
                                    <div class="col-sm-6">
                                        <input type="number" size="100" name="zip"
                                            class=" form-control" placeholder='<?php echo display('zip') ?>'
                                            id="zip" />
                                    </div>
                                </div>
								<div class="form-group row">
                                    <label for="previous_balance"
                                        class="col-sm-3 col-form-label"><?php echo display('previous_balance') ?> </label>
                                    <div class="col-sm-6">
                                        <input type="text" size="100" name="previous_balance"
                                            class=" form-control" placeholder='<?php echo display('previous_balance') ?>'
                                            id="previous_balance" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="store_id" class="col-sm-4 col-form-label"><?php echo display('store') ?>
                                        <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="store_id" required="" name="store_id">
                                            <option value=""></option>
                                            <?php foreach($store_list as $store){ ?>
                                            
                                            <option value="<?php echo html_escape($store['store_id']); ?>" <?php if($store['default_status'] == 1){ echo "selected";} ?> ><?php echo html_escape($store['store_name']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="date" class="col-sm-4 col-form-label"><?php echo display('date') ?> <i
                                                class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <?php 
                                        date_default_timezone_set(DEF_TIMEZONE);
                                        $date = date('m-d-Y'); ?>
                                        <input class="form-control datepicker" type="text" size="50" name="invoice_date"
                                               id="date" required value="<?php echo html_escape($date); ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive mt_10">
                            <table class="table table-bordered table-hover" id="normalinvoice">
                                <thead>
                                <tr>
                                    <th class="text-center"><?php echo display('item_information') ?> <i
                                                class="text-danger">*</i></th>
                                    <th class="text-center" width="130"><?php echo display('variant') ?> <i
                                                class="text-danger">*</i></th>
                                    <th class="text-center"><?php echo display('available_quantity') ?></th>
                                    <th class="text-center"><?php echo display('unit') ?></th>
									<!-- <th class="text-center">Unit Price</th> -->
                                    <th class="text-center"><?php echo display('quantity') ?> <i
                                                class="text-danger">*</i></th>
                                    <th class="text-center"><?php echo display('rate');
									$CI =& get_instance();
		$CI->load->library('session');
		$user_type = $CI->session->userdata('user_type'); if ($user_type == 7 || $user_type == 6) {echo '(Unit * Unit Price)';}?> <i class="text-danger">*</i>
                                    </th>
                                    <th class="text-center"><?php echo display('discount') ?> </th>
                                    <th class="text-center"><?php echo display('total');$CI->load->library('session');
		$user_type = $CI->session->userdata('user_type'); if ($user_type == 7 || $user_type == 6) {echo '(Unit * Unit Price)';} ?> <i class="text-danger">*</i>
                                    </th>
                                    <th class="text-center"><?php echo display('action') ?></th>
                                </tr>
                                </thead>
                                <tbody id="addinvoiceItem">
                                <tr>
                                    <td>
                                        <input type="text" name="product_name" onkeyup="invoice_productList(1);"
                                               class="form-control productSelection"
                                               placeholder='<?php echo display('product_name') ?>' required=""
                                               id="product_name_1">

                                        <input type="hidden" class="autocomplete_hidden_value product_id_1"
                                               name="product_id[]"/>

                                        <input type="hidden" class="sl" value="1">

                                        <input type="hidden" class="baseUrl" value="<?php echo base_url(); ?>"/>
                                    </td>
                                    <td class="text-center">
                                        <div class="variant_id_div">
                                            <select name="variant_id[]" id="variant_id_1" class="form-control variant_id width_100p" required="">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                        <div>
                                            <select name="color_variant[]" id="variant_color_id_1" class="form-control color_variant width_100p">
                                                <option value=""></option>
                                            </select>
                                        </div>

                                    </td>
                                    <td>
                                        <input type="text" name="available_quantity[]"
                                               class="form-control text-right available_quantity_1" id="avl_qntt_1"
                                               placeholder="0" readonly=""/>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control text-right unit_1" placeholder="None"
                                               readonly=""/>
                                    </td>
									<!-- <td>
                                        <input type="text" class="form-control text-right amount_item1" placeholder="None"
                                               readonly=""/>
                                    </td>-->
                                    <td>
                                        <input type="number" name="product_quantity[]" onkeyup="quantity_calculate(1);"
                                               onchange="quantity_calculate(1);" id="total_qntt_1"
                                               class="form-control text-right" value="1" min="1" required=""/>
                                    </td>
                                    <td>
                                        <input type="number" name="product_rate[]" onkeyup="quantity_calculate(1);"
                                               onchange="quantity_calculate(1);" placeholder="0.00" id="price_item_1"
                                               class="price_item1 form-control text-right" required="" min="0"/>
                                    </td>
                                    <td>
                                        <input type="number" name="discount[]" onkeyup="quantity_calculate(1);"
                                               onchange="quantity_calculate(1);" id="discount_1"
                                               class="form-control text-right" placeholder="0.00" min="0"/>
                                    </td>

                                    <td>
                                        <input class="total_price form-control text-right" type="text"
                                               name="total_price[]" id="total_price_1" placeholder="0.00"
                                               readonly="readonly"/>
                                    </td>

                                    <td>
                                        <?php
                                        //Tax basic info
                                        $this->db->select('*');
                                        $this->db->from('tax');
                                        $this->db->order_by('tax_name', 'asc');
                                        $tax_information = $this->db->get()->result();

                                        if (!empty($tax_information)) {
                                            foreach ($tax_information as $k => $v) {
                                                if ($v->tax_id == '52C2SKCKGQY6Q9J') {
                                                    $tax['cgst_name'] = $v->tax_name;
                                                    $tax['cgst_id'] = $v->tax_id;
                                                    $tax['cgst_status'] = $v->status;
                                                } elseif ($v->tax_id == 'H5MQN4NXJBSDX4L') {
                                                    $tax['sgst_name'] = $v->tax_name;
                                                    $tax['sgst_id'] = $v->tax_id;
                                                    $tax['sgst_status'] = $v->status;
                                                } elseif ($v->tax_id == '5SN9PRWPN131T4V') {
                                                    $tax['igst_name'] = $v->tax_name;
                                                    $tax['igst_id'] = $v->tax_id;
                                                    $tax['igst_status'] = $v->status;
                                                }
                                            }
                                        }
                                        ?>

                                        <!-- Tax calculate start-->
                                        <?php if ($tax['cgst_status'] == 1) { ?>
                                            <input type="hidden" id="cgst_1" class="cgst"/>
                                            <input type="hidden" id="total_cgst_1" class="total_cgst" name="cgst[]"/>
                                            <input type="hidden" name="cgst_id[]" id="cgst_id_1">
                                        <?php }
                                        if ($tax['sgst_status'] == 1) { ?>
                                            <input type="hidden" id="sgst_1" class="sgst"/>
                                            <input type="hidden" id="total_sgst_1" class="total_sgst" name="sgst[]"/>
                                            <input type="hidden" name="sgst_id[]" id="sgst_id_1">
                                        <?php }
                                        if ($tax['igst_status'] == 1) { ?>
                                            <input type="hidden" id="igst_1" class="igst"/>
                                            <input type="hidden" id="total_igst_1" class="total_igst" name="igst[]"/>
                                            <input type="hidden" name="igst_id[]" id="igst_id_1">
                                        <?php } ?>
                                        <!-- Tax calculate end -->

                                        <!-- Discount calculate start-->
                                        <input type="hidden" id="total_discount_1" class=""/>
                                        <input type="hidden" id="all_discount_1" class="total_discount"/>
                                        <!-- Discount calculate end -->

                                        <button class="btn btn-danger text-right" type="button" value="<?php echo display('delete') ?>" onclick="deleteRow(this)"><?php echo display('delete') ?>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>
                                    <tr>

                                        <td class="text-right" colspan="7"><b><?php echo html_escape($tax['cgst_name']) ?>
                                                :</b></td>
                                        <td class="text-right">
                                            <input type="text" id="total_cgst" class="form-control text-right"
                                                   name="total_cgst" placeholder="0.00" readonly="readonly"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="7"><b><?php echo html_escape($tax['sgst_name']) ?>
                                                :</b></td>
                                        <td class="text-right">
                                            <input type="text" id="total_sgst" class="form-control text-right"
                                                   name="total_sgst" placeholder="0.00" readonly="readonly"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="7"><b><?php echo html_escape($tax['igst_name']) ?>
                                                :</b></td>
                                        <td class="text-right">
                                            <input type="text" id="total_igst" class="form-control text-right"
                                                   name="total_igst" placeholder="0.00" readonly="readonly"/>
                                        </td>
                                    </tr>
                                <tr>
                                    <td colspan="5" rowspan="4">
                                        <label for="details" class=""><?php echo display('details') ?></label>
                                        <textarea class="form-control" name="details" id="details" rows="6"
                                                  placeholder="<?php echo display('details') ?>"></textarea>
                                    </td>

                                    <td class="text-right" colspan="2">
                                        <b><?php echo display('product_discount') ?>:</b></td>
                                    <td class="text-right">
                                        <input type="text" id="total_discount_ammount" class="form-control text-right"
                                               name="total_discount" placeholder="0.00" readonly="readonly"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="2">
                                        <b><?php echo display('invoice_discount') ?>:</b></td>
                                    <td class="text-right">
                                        <input type="text" id="invoice_discount" class="form-control text-right"
                                               name="invoice_discount" placeholder="0.00"
                                               onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-right" colspan="2"><b><?php echo display('service_charge') ?>
                                            :</b></td>
                                    <td class="text-right">
                                        <input type="text" id="service_charge" class="form-control text-right"
                                               name="service_charge" placeholder="0.00" onkeyup="calculateSum();"
                                               onchange="calculateSum();"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-right"><b><?php echo display('grand_total') ?>
                                            :</b></td>
                                    <td class="text-right">
                                        <input type="text" id="grandTotal" class="form-control text-right"
                                               name="grand_total_price" placeholder="0.00" readonly="readonly"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <input type="button" id="add-invoice-item" class="btn -btn-info color4 color5"
                                               name="add-invoice-item" onClick="addInputField('addinvoiceItem');"
                                               value="<?php echo display('add_new_item') ?>"/>
                                        <input type="hidden" name="baseUrl" class="baseUrl"
                                               value="<?php echo base_url(); ?>"/>

                                        <input class="btn btn-warning" value="<?php echo display('full_paid') ?>"
                                               tabindex="15" onclick="full_paid();" type="button">
                                    </td>
                                    <td class="text-right" colspan="6"><b><?php echo display('paid_ammount') ?>
                                            :</b></td>
                                    <td class="text-right">
                                        <input type="text" id="paidAmount"
                                               onkeyup="invoice_paidamount();" class="form-control text-right"
                                               name="paid_amount" placeholder="0.00"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" class="width_220">
                                        <input type="submit" id="add-invoice" class="btn btn-success" name="add-order"
                                               value="<?php echo display('submit') ?>"/>
                                    </td>

                                    <td class="text-right" colspan="6"><b><?php echo display('due') ?>:</b></td>
                                    <td class="text-right">
                                        <input type="text" id="dueAmmount" class="form-control text-right"
                                               name="due_amount" placeholder="0.00" readonly="readonly"/>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Add Order End -->
<!--<input type="hidden" id="cgst_status" value="<?php echo html_escape($tax['cgst_status']); ?>">-->
<!--<input type="hidden" id="sgst_status" value="<?php echo html_escape($tax['sgst_status']); ?>">-->
<!--<input type="hidden" id="igst_status" value="<?php echo html_escape($tax['igst_status']); ?>">-->
<script src="<?php echo MOD_URL.'dashboard/assets/js/add_order_form.js'; ?>"></script>
<script src="<?php echo MOD_URL.'dashboard/assets/js/add_customer_form.js'; ?>"></script>