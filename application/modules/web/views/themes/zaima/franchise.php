<?php
$CI =& get_instance();

$CI->load->model('dashboard/Themes');
$CI->load->model('dashboard/Companies');
$theme = $CI->Themes->get_theme();
$company_info = $CI->Companies->company_list();
?>
<section class="section-about py-5">
    <div class="container">
        <!-- Alert Message -->
        <?php
        $message = $this->session->userdata('message');
        if (isset($message)) {
            ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php echo $message ?>
            </div>
            <?php
            $this->session->unset_userdata('message');
        }
        $error_message = $this->session->userdata('error_message');
        if (isset($error_message)) {
            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php echo $error_message ?>
            </div>
            <?php
            $this->session->unset_userdata('error_message');
        }
        ?>
        <div class="row">
            <div class="col-md-10 offset-md-1 col-lg-6 offset-lg-3">
                <div class="section-title text-center mb-5">
                    <h2 class="fs-28 font-weight-normal"><?php echo 'To Own a Franchise Fill up the form' ?></h2>
                    <p class="text-black-50 mb-0 fs-16"><?php echo display('your_email_address_will_not_be_published') ?></p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-6">
                <div class="row align-items-center">
                 
                    <div class="col-sm-10 col-md-10">
                        <?php echo form_open('submit_franchise', array('class' => 'request_form')); ?>
                        <div class="comments_area">
                            <div class="form-group">
                                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="<?php echo display('first_name') ?>" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="<?php echo display('last_name') ?>" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="<?php echo display('phone') ?>" required>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" id="email" placeholder="<?php echo display('email') ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                                <input type="text" class="form-control" name="address" id="address" placeholder="<?php echo display('address') ?>" required>
                        </div>
                        <button href="#" class="btn-one btn btn-primary  color4 color46"><?php echo display('submit') ?></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.End of about section -->

<!--<div class="map-content">-->
<!--    <div id="map" class="w-100"></div>-->
<!--</div>-->


<!-- /.End of map content -->
<!--<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo html_escape($map_info[0]['map_api_key']) ?>"></script>-->
<!--<input type="hidden" id="map_latitude" value="<?php echo html_escape($map_info[0]['map_latitude'])?>">-->
<!--<input type="hidden" id="map_langitude" value="<?php echo html_escape($map_info[0]['map_langitude'])?>">-->
<!--<input type="hidden" id="company_name" value="<?php echo html_escape($company_info[0]['company_name'])?>">-->
<script src="<?php echo THEME_URL.$theme.'/assets/ajaxs/contact_us.js'; ?>"></script>


