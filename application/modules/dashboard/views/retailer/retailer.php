<?php defined('BASEPATH') OR exit('No direct script access allowed');   
?>  

<!-- Manage Supplier Start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1>manage_retailer</h1>
	        <small>manage_your_retailer</small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#">retailer</a></li>
	            <li class="active">manage_retailer</li>
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
                	<?php if($this->permission->check_label('add_retailer')->create()->access()){ ?>
                  	<a href="<?php echo base_url('dashboard/Cretailer')?>" class="btn btn-success m-b-5 m-r-2">
                  		<i class="ti-plus"> </i> add_retailer</a>
                 <!-- 	<?php }if($this->permission->check_label('supplier_ledger')->read()->access()){ ?>-->
                 <!--  	<a href="<?php echo base_url('dashboard/Csupplier/supplier_ledger_report')?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('supplier_ledger')?></a>-->
               		<!--<?php }?>-->
                </div>
            </div>
        </div>

		<!-- Manage Supplier -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('manage_retailer') ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
		                <div class="table-responsive">
		                    <table id="dataTableExample2" class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
									<th>Retailer ID#</th>
								<th>Company name </th>
								<th>Address </th>
								<th>Email</th>
								<th>Mobile </th>
								<th>bussiness_field</th>
								<th>Action</th>
									</tr>
								</thead>
								<tbody>
								<?php
								
								
									if ($retailers_list) {

										// var_dump($retailers_list);
								// die();
								?>
								
								{retailers_list}

									<tr>
										<td>{retailer_id}</td>
										<td width="15%">
											<a href="<?php echo base_url().'dashboard/Cretailer/manage_retailer/{retailer_id}'; ?>">
                                                 {company_name} <i class="fa fa-user pull-right" aria-hidden="true"></i>
											</a>
										</td>
										<td>{adress}</td>
										<td>{email}</td>
										<td>{phone}</td>
										<td>{bussiness_field}</td>
										<td>
											<center>
											<?php echo form_open()?>
												<?php if($this->permission->check_label('manage_supplier')->update()->access()){ ?>
												<a href="<?php echo base_url().'dashboard/Cretailer/retailer_update_form/{retailer_id}'; ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('update') ?>">
													<i class="fa fa-pencil" aria-hidden="true"></i>
												</a>
												<?php }if($this->permission->check_label('manage_supplier')->delete()->access()){?>
												<a href="<?php echo base_url().'dashboard/Cretailer/retailer_delete/{retailer_id}'; ?>" class="btn btn-danger btn-sm" onclick="return confirm('<?php echo display('are_you_sure_want_to_delete')?>');" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?php echo display('delete') ?> ">
													<i class="fa fa-trash-o" aria-hidden="true"></i>
												</a>
												<?php } ?>
											<?php echo form_close()?>
											</center>
										</td>
									</tr>
								{/retailers_list}
								<?php
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
<!-- Manage Product End --> 