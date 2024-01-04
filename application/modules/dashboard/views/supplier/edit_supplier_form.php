<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Edit supplier page start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('supplier_edit') ?></h1>
            <small><?php echo display('supplier_edit') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('supplier') ?></a></li>
                <li class="active"><?php echo display('supplier_edit') ?></li>
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
                    <?php if($this->permission->check_label('manage_supplier')->read()->access()){ ?>
                    <a href="<?php echo base_url('dashboard/Csupplier/manage_supplier')?>"
                        class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"></i>
                        <?php echo display('manage_supplier')?></a>

                    <?php }if($this->permission->check_label('supplier_ledger')->read()->access()){ ?>
                    <a href="<?php echo base_url('dashboard/Csupplier/supplier_ledger_report')?>"
                        class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"> </i>
                        <?php echo display('supplier_ledger')?></a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- New supplier -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('supplier_edit') ?> </h4>
                        </div>
                    </div>
                    <?php echo form_open_multipart('dashboard/Csupplier/supplier_update',array( 'id' => 'validate'))?>
                    <div class="panel-body">

                        <div class="form-group row">
                            <label for="supplier_name"
                                class="col-sm-3 col-form-label"><?php echo display('supplier_name') ?> <i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input class="form-control" name="supplier_name" id="supplier_name" type="text"
                                    value="{supplier_name}" placeholder="<?php echo display('supplier_name') ?>"
                                    required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="mobile" class="col-sm-3 col-form-label"><?php echo display('supplier_mobile') ?>
                                <i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="mobile" id="mobile" type="number"
                                    placeholder="<?php echo display('supplier_mobile') ?>" value="{mobile}" required
                                    min="0">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="supplier_email"
                                class="col-sm-3 col-form-label"><?php echo display('suppler_email') ?> <i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input class="form-control" name="email" id="supplier_email" type="email"
                                    placeholder="<?php echo display('suppler_email') ?>" value="{email}" min="0">
                            </div>
                        </div>
						<div class="form-group row">
                            <label for="address " class="col-sm-3 col-form-label">
                                <?php echo display('supplier_address') ?> <i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="address" id="address" required rows="3"
                                    placeholder="<?php echo display('supplier_address') ?>">{address}</textarea>
                            </div>
                        </div>
						<div class="form-group row">
                                <label for="bank_account" class="col-sm-3 col-form-label">Bank Account  Number<i
                                    class="text-danger">*</i></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="bank_account" id="bank_account" type="text"
                                        placeholder="bank_account" value="{bank_account}">
                                </div>
                            </div>
							<div class="form-group row">
                                <label for="bank_name" class="col-sm-3 col-form-label">Bank Name <i
                                    class="text-danger">*</i></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="bank_name" id="bank_name" type="text"
                                        placeholder="bank_name" value="{bank_name}">
                                </div>
                            </div>
                               <div class="form-group row">
                                <label for="branch" class="col-sm-3 col-form-label">Branch <i
                                    class="text-danger">*</i></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="branch" id="branch" type="text"
                                        placeholder="branch" value="{branch}">
                                </div>
                            </div>
							 <div class="form-group row">
                                <label for="IFSC" class="col-sm-3 col-form-label">IFSC <i
                                    class="text-danger">*</i></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="ifsc" id="ifsc" type="text"
                                        placeholder="IFSC" value="{ifsc}">
                                </div>
                            </div>
                        <div class="form-group row">
                            <label for="vat_no" class="col-sm-3 col-form-label"><?php echo display('vat_no') ?> </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="vat_no" id="vat_no" type="text" value="{vat_no}"
                                    placeholder="<?php echo display('vat_no') ?>">
                            </div>
                        </div>
 <div class="form-group row">
                                <label for="cin" class="col-sm-3 col-form-label">CIN <i
                                    class="text-danger">*</i> </label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="cin" id="cin" type="text"
                                        placeholder="CIN"  required=""  value="{cin}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="pan" class="col-sm-3 col-form-label">PAN </label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="pan" id="pan" type="text" value="{pan}"
                                        placeholder="PAN">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="CFSSAI" class="col-sm-3 col-form-label">CFSSAI </label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="cfssai" id="cfssai" type="text" value="{cfssai}"
                                        placeholder="CFSSAI">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="SFSSAI" class="col-sm-3 col-form-label">SFSSAI </label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="sfssai" id="sfssai" type="text" value=" {sfssai}"
                                        placeholder="SFSSAI">
                                </div>
                            </div>

                        

							
                        <div class="form-group row">
                            <label for="details"
                                class="col-sm-3 col-form-label"><?php echo display('supplier_details') ?></label>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="details" id="details" rows="3"
                                    placeholder="<?php echo display('supplier_details') ?>">{details}</textarea>
                            </div>
                        </div>
                         
                               <div class="form-group row">
                                <label for="account_type" class="col-sm-3 col-form-label">Account Type </label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="account_type" id="account_type" type="text"
                                        placeholder="account_type" value="{account_type}">
                                </div>
                            </div>
                               
                        
                        <input type="hidden" name="supplier_id" value="{supplier_id}" />

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-6">
                                <input type="submit" id="add-supplier" class="btn btn-success btn-large"
                                    name="add-supplier" value="<?php echo display('save_changes') ?>" />
                            </div>
                        </div>
                    </div>
                    <?php echo form_close()?>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Edit supplier page end -->