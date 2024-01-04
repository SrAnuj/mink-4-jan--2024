<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Add new supplier start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1>add_Wholesaler</h1>
            <small>add_new_Wholesaler</small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#">Wholesaler</a></li>
                <li class="active">add_Wholesaler</li>
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
                    <a href="<?php echo base_url('dashboard/Cwholesaler')?>"
                        class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"></i>
                        add_wholesaler</a>

                    <?php }if($this->permission->check_label('supplier_ledger')->read()->access()){ ?>
                    <a href="<?php echo base_url('dashboard/Cwholesaler/manage_wholesaler')?>"
                        class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"> </i>
                        manage_wholesaler</a>
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
                            <h4>add_wholesaler</h4>
                        </div>
                    </div>
                    <?php echo form_open_multipart('dashboard/Cwholesaler/insert_wholesaler',array( 'id' => 'validate'))?>
                    <div class="panel-body">

                        <div class="form-group row">
                            <label for="bussiness_name"
                                class="col-sm-3 col-form-label">Bussiness name <i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input class="form-control" name="bussiness_name" id="bussiness_name" type="text"
                                    placeholder="bussiness_name" required="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="mobile" class="col-sm-3 col-form-label">mobile
                                <i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="mobile" id="mobile" required type="number"
                                    placeholder="mobile' " min="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="supplier_email"
                                class="col-sm-3 col-form-label">A.B.N </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="abn" id="abn" type="text"
                                    placeholder="Wholesaler a.b.n" min="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="supplier_email"
                                class="col-sm-3 col-form-label">Wholesaler_email </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="email" id="supplier_email" type="email"
                                    placeholder="Wholesaler email" min="0">
                            </div>
                        </div>
						<div class="form-group row">
                            <label for="password" class="col-sm-3 col-form-label"><?php echo display('password') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="password" tabindex="5" class="form-control" id="password" name="password" placeholder="<?php echo display('password') ?>" required />
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <label for="vat_no" class="col-sm-3 col-form-label"><?php echo display('vat_no') ?> </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="vat_no" id="vat_no" type="text"
                                    placeholder="<?php echo display('vat_no') ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cr_no" class="col-sm-3 col-form-label"><?php echo display('cr_no') ?> </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="cr_no" id="cr_no" type="text"
                                    placeholder="<?php echo display('cr_no') ?>">
                            </div>
                        </div> -->

                        <div class="form-group row">
                            <label for="address " class="col-sm-3 col-form-label">
                            address<i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-6">
                                <textarea class="form-control" required name="address" id="address " rows="3"
                                    placeholder="retailer address"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="details"
                                class="col-sm-3 col-form-label">city</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="city" id="city" type="text"
                                    placeholder="city">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="country"
                                class="col-sm-3 col-form-label">country </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="country" id="country" type="text"
                                    placeholder="country">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="website"
                                class="col-sm-3 col-form-label">website </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="website" id="website" type="text"
                                    placeholder="website">
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <label for="previous_balance"
                                class="col-sm-3 col-form-label"><?php echo display('previous_balance') ?> </label>
                            <div class="col-sm-6">
                                <input class="form-control" name="previous_balance" id="previous_balance" type="number"
                                    placeholder="<?php echo display('previous_balance') ?>">
                            </div>
                        </div> -->
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-6">
                                <input type="submit" id="add-wholesaler" class="btn btn-primary btn-large"
                                    name="add-wholesaler" value="<?php echo display('save') ?>" />
                                <input type="submit" value="<?php echo display('save_and_add_another') ?>"
                                    name="add-wholesaler-another" class="btn btn-large btn-success"
                                    id="add-wholesaler-another">
                            </div>
                        </div>
                    </div>
                    <?php echo form_close()?>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Add new supplier end -->