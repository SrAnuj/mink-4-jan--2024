<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Edit Wholesaler page start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('Wholesaler Edit') ?></h1>
            <small><?php echo display('Wholesaler Edit') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#">Wholesaler</a></li>
                <li class="active">Wholesaler Edit</li>
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
                    <?php if($this->permission->check_label('manage_Wholesaler')->read()->access()){ ?>
                    <a href="<?php echo base_url('dashboard/Cretailer/manage_retailer')?>"
                        class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"></i>
                        <?php echo display('manage_Wholesaler')?></a>

                    <?php }if($this->permission->check_label('Wholesaler_ledger')->read()->access()){ ?>
                    <a href="<?php echo base_url('dashboard/CWholesaler/Wholesaler_ledger_report')?>"
                        class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"> </i>
                      retailer_ledger</a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- New Wholesaler -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>Wholesaler Edit </h4>
                        </div>

                    <!-- </div>dashboard/Cretailer/retailer _update_form/{retailer_id} -->
                   <?php echo form_open_multipart('dashboard/Cwholesaler/wholesaler_update',array( 'id' => 'validate'))?>
                    <div class="panel-body">
                    <div class="form-group row">
                            <label for="bussiness_name"
                                class="col-sm-3 col-form-label">Bussiness Name <i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input class="form-control" name="bussiness_name" id="bussiness_name" type="text"
                                    placeholder="bussiness_name" value="{bussiness_name}" required="">
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
                            <label for="mobile"
                                class="col-sm-3 col-form-label">mobile </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="mobile" id="mobile" type="number"
                                    placeholder="retailer mobile" required  value="{mobile}" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address " class="col-sm-3 col-form-label">
                            Wholesaler Address<i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-6">
                                <input class="form-control" required name="address" id="address " rows="3"
                                    placeholder="retailer address"  value="{address}" >
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
                                    placeholder="abn"  value="{abn}" >
                            </div>
                        </div> 
                        <input type="hidden" name="wholesaler_id" value="{wholesaler_id}" />

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
<!-- Edit Wholesaler page end -->