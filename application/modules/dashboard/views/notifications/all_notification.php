<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Manage order Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1>Manage Notifications</h1>
            <small><?php echo display('Manage Notifications') ?></small>
            <ol class="breadcrumb">
                <li>
                    <a href="#">
                        <i class="pe-7s-home"></i>
                        <?php echo display('home') ?>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <?php echo display('Notifications') ?>
                    </a>
                </li>
                <li class="active">
                    <?php echo display('Manage Notifications') ?>
                </li>
            </ol>
        </div>
    </section>

    <section class="content">
       

        <!-- Manage order report -->
        <div class="row">
            <div class="col-sm-12">
                 <?php if(!empty($notifications)) : ?>
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('Manage Notifications') ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                    <th><?php echo display('sl') ?></th>
                                    <th><?php echo display('order_no') ?></th>
                                    <th>Content</th>
                                    <th><?php echo "Marking as Read"; ?></th>
                                    <th><?php echo "Remove"; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $ctr = 1;
                                    foreach ($notifications as $key => $notification): ?>
                                        <?php 
                                        $data = json_decode($notification->data); 
                                        //var_dump($data);
                                        ?>
                                    <tr>
                                        <td><?php echo $ctr; ?></td>
                                        <td><a
                                                href="<?php echo base_url() . 'dashboard/Corder/order_details_data/' . $data->order_id ?>"><?php echo $data->order_id; ?></a></td>
                                        <td><?php echo $data->content; ?><br/>
                                            <span class="badge badeg-success">
                                            <?php echo date('F j, Y', strtotime($notification->created_at));  ?>
                                        </span></td>
                                        <td><a href="<?= base_url('notification/mark_as_read/' . $notification->id) ?>" class="btn btn-primary btn-sm">Mark as Read</a>
                                         <a href="<?= base_url('notification/remove_notification/'.$notification->id) ?>" class="btn btn-danger btn-sm">Remove</a></td>
                                    </tr>
                                    <?php $ctr++; endforeach; ?>
                                </tbody>
                            </table>
                           
                        </div>
                        <div class="text-right">
                            <?php echo htmlspecialchars_decode($links); ?>
                        </div>
                    </div>

                </div>
                <?php else: ?>
                    <div class="alert alert-danger">
                        No Unread Notification found
                    </div>
                <?php endif; ?>

            </div>
        </div>
        
       
        <div class="row">
            <div class="col-sm-12">
                 <?php if(!empty($read_notifications)) : ?>
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('Manage Read Notifications') ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><?php echo display('sl') ?></th>
                                        <th><?php echo display('order_no') ?></th>
                                        <th>Content</th>
                                        <th><?php echo "Marking as Read"; ?></th>
                                        <th><?php echo "Remove"; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $ctr = 1;
                                    foreach ($read_notifications as $key => $notification): ?>
                                        <?php 
                                        $data = json_decode($notification->data); 
                                        //var_dump($data);
                                        ?>
                                    <tr>
                                        <td><?php echo $ctr; ?></td>
                                        <td><a
                                                href="<?php echo base_url() . 'dashboard/Corder/order_details_data/' . $data->order_id ?>"><?php echo $data->order_id; ?></a></td>
                                        <td><?php echo $data->content; ?><br/>
                                            <span class="badge badeg-success">
                                            <?php echo date('F j, Y', strtotime($notification->created_at));  ?>
                                        </span></td>
                                        <td><a href="<?= base_url('notification/mark_as_read/' . $notification->id) ?>" class="btn btn-primary btn-sm">Mark as Read</a>
                                         <td><a href="<?= base_url('notification/remove_notification/'.$notification->id) ?>" class="btn btn-danger btn-sm">Remove</a></td>
                                    </tr>
                                    <?php $ctr++; endforeach; ?>
                                </tbody>
                            </table>
                           
                        </div>
                        <div class="text-right">
                            <?php echo htmlspecialchars_decode($links); ?>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                    <div class="alert alert-danger">
                        No Read Notification found
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
    </section>
</div>
<!-- Manage order End -->