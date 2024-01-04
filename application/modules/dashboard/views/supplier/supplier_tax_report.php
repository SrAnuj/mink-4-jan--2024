<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Supplier Ledger Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('supplier_tax_report') ?></h1>
            <small><?php echo display('supplier_tax_report') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('supplier') ?></a></li>
                <li class="active"><?php echo display('supplier_tax_report') ?></li>
            </ol>
        </div>
    </section>
    <!-- Supplier information -->
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
                    <?php if ($this->permission->check_label('add_supplier')->create()->access()) { ?>
                    <a href="<?php echo base_url('dashboard/Csupplier') ?>" class="btn btn-success m-b-5 m-r-2"><i
                            class="ti-plus"> </i> <?php echo display('add_supplier') ?></a>
                    <?php }
                    if ($this->permission->check_label('manage_supplier')->read()->access()) { ?>
                    <a href="<?php echo base_url('dashboard/Csupplier/manage_supplier') ?>"
                        class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"> </i>
                        <?php echo display('manage_supplier') ?></a>
                    <?php } ?>

                </div>
            </div>
        </div>
        <!-- Supplier select -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?php echo form_open('dashboard/Csupplier/supplier_balance_report', array('class' => 'form-inline')); ?>
                        <div class="form-group">
                            <label for="from_date"><?php echo display('from_date') ?><span
                                    class="text-danger">*</span>:</label>
                            <input type="text" class="form-control datepicker" autocomplete="off"
                                placeholder="<?php echo display('from_date'); ?>" name="from_date" required>
                        </div>
                        <div class="form-group">
                            <label for="to_date"><?php echo display('to_date') ?><span
                                    class="text-danger">*</span>:</label>
                            <input type="text" class="form-control datepicker" autocomplete="off"
                                placeholder="<?php echo display('to_date'); ?>" name="to_date" required>
                        </div>
                        <button type="submit" class="btn btn-success"><?php echo display('search') ?></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Manage Supplier -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('supplier_tax_report') ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTableExample2" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="8" class="text-center">
                                            Date :
                                            <?php if (!empty($from_date)) {
                                                echo $from_date;
                                            } else {
                                                echo '(from date)';
                                            } ?>
                                            - :
                                            <?php if (!empty($from_date)) {
                                                echo $to_date;
                                            } else {
                                                echo '(to date)';
                                            } ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">NO</th>
                                        <th class="text-center">Supplier Name</th>
                                        <!--<th class="text-center">Debit</th>-->
                                        <!--<th class="text-center">Credit</th>-->
                                        <!--<th class="text-center">Balance</th>-->
                                        <th class="text-center">Mobile Number</th>
                                        <th class="text-center">Supplier CGST Tax</th>
                                        <th class="text-center">Supplier SGST Tax</th>
                                        <th class="text-center">Supplier IGST Tax</th>

                                        <th class="text-center">Supplier's Commercial Registration Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // var_dump($suppliers_transection_report);
                                    // die();
                                    
                                    if (!empty($supplier_tax_report)) {
                                        foreach ($supplier_tax_report as $key =>  $supplier_tax) {
                                            if (!empty($supplier_tax['supplier_name'])) {
                                    ?>
                                    <tr>
                                        <td><?php echo ++$key ; ?></td>
                                        <td><?php echo html_escape($supplier_tax['supplier_name']); ?></td>
                                       
                                        <td><?php echo html_escape($supplier_tax['mobile']); ?></td>
                                        <td><?php echo html_escape($supplier_tax['total_cgst']); ?></td>
                                        <td><?php echo html_escape($supplier_tax['total_sgst']); ?></td>
                                        <td><?php echo html_escape($supplier_tax['total_igst']); ?></td>

                                        <td><?php echo html_escape($supplier_tax['cr_no']); ?></td>
                                    </tr>
                                    <?php }
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Supplier Ledger End 