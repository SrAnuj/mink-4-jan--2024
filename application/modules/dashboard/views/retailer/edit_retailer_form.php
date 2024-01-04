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
                <li><a href="#">supplier</a></li>
                <li class="active">supplier_edit</li>
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
                    <a href="<?php echo base_url('dashboard/Cretailer/manage_retailer')?>"
                        class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"></i>
                        <?php echo display('manage_supplier')?></a>

                    <?php }if($this->permission->check_label('supplier_ledger')->read()->access()){ ?>
                    <a href="<?php echo base_url('dashboard/Csupplier/supplier_ledger_report')?>"
                        class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"> </i>
                      retailer_ledger</a>
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
                            <h4>supplier_edit </h4>
                        </div>
                    <!-- </div>dashboard/Cretailer/retailer _update_form/{retailer_id} -->
                    <?php echo form_open_multipart('dashboard/Cretailer/retailer_update',array( 'id' => 'validate'))?>
                    <div class="panel-body">
                    <div class="form-group row">
                            <label for="company_name"
                                class="col-sm-3 col-form-label">company_name <i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input class="form-control" name="company_name" id="company_name" type="text"
                                    placeholder="company_name" value="{company_name}" required="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">email
                                <i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="email" id="email" required type="email"
                                    placeholder="retailer_mobile "  value="{email}"  >
                            </div>
                        </div>
						<div class="form-group row">
                            <label for="password" class="col-sm-3 col-form-label"><?php echo display('password') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="password" tabindex="5" class="form-control" id="password" name="password" placeholder="<?php echo display('password') ?>"
								value="{password}" required />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone"
                                class="col-sm-3 col-form-label">phone </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="phone" id="phone" type="number"
                                    placeholder="retailer phone" required  value="{phone}" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address " class="col-sm-3 col-form-label">
                            retailer_address<i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-6">
                                <input class="form-control" required name="adress" id="adress " rows="3"
                                    placeholder="retailer address"  value="{adress}" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="details"
                                class="col-sm-3 col-form-label">city</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="city" id="city" type="text"
                                    placeholder="city"  value="{city}" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="country"
                                class="col-sm-3 col-form-label">country </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="country" id="country" type="text"
                                    placeholder="country"  value="{country}" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="website"
                                class="col-sm-3 col-form-label">website </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="website" id="website" type="text"
                                    placeholder="website"   value="{website}" >
                            </div>
                        </div> <div class="form-group row">
                            <label for="bussiness_field"
                                class="col-sm-3 col-form-label">Fields of Business Activities?
 </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="bussiness_field" id="bussiness_field" type="text"
                                    placeholder="bussiness_field"  value="{bussiness_field}" >
                            </div>
                        </div> 
                        <input type="hidden" name="retailer_id" value="{retailer_id}" />

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-6">
                                <input type="submit" id="add-retailer" class="btn btn-success btn-large"
                                    name="add-retailer" value="<?php echo display('save_changes') ?>" />
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