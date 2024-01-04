
<!-- SlimScroll -->
<script src="<?php echo base_url('assets/plugins/jQuery-slimScroll/jquery.slimscroll.min.js') ?>" type="text/javascript"></script>
<!-- FastClick -->
<script src="<?php echo base_url() ?>assets/plugins/fastclick/fastclick.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/fastclick/frame.js') ?>" type="text/javascript"></script>
<!-- Sparkline js -->
<script src="<?php echo base_url() ?>assets/plugins/sparkline/sparkline.min.js" type="text/javascript"></script>
<!-- Counter js -->
<script src="<?php echo base_url() ?>assets/plugins/counterup/waypoints.min.js?v=0" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
<!-- iCheck js -->
<script src="<?php echo base_url() ?>assets/plugins/icheck/icheck.min.js" type="text/javascript"></script>
<!-- dataTables js -->
<script src="<?php echo base_url('assets/datatables/js/dataTables.min.js') ?>" type="text/javascript"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.js">
</script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap.min.js"></script>

<script src="<?php echo base_url() ?>assets/plugins/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
<!-- Dashboard js -->
<script src="<?php echo base_url() ?>assets/dist/js/dashboard.min.js?v=0" type="text/javascript"></script>
<!-- Select2 -->
<script src="<?php echo base_url('assets/js/select2.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/plugins/modals/classie.js" type="text/javascript"></script>
<!-- Summernote js -->
<script src="<?php echo base_url() ?>assets/plugins/summernote/summernote.min.js" type="text/javascript"></script>
<!-- Modal js -->
<script src="<?php echo base_url() ?>assets/plugins/modals/modalEffects.js" type="text/javascript"></script>
<!-- Bootstrap tag inputs js -->
<script src="<?php echo base_url() ?>assets/js/bootstrap-tagsinput.js" type="text/javascript"></script>
<!-- Toastr js -->
<script src="<?php echo base_url() ?>assets/plugins/toastr/toastr.min.js" type="text/javascript"></script>
<!-- Custom js -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/custom.js" type="text/javascript"></script>
<!-- lobipanel -->
<script src="<?php echo base_url('assets/js/lobipanel.min.js') ?>" type="text/javascript"></script>
<!-- Pace js -->
<script src="<?php echo base_url('assets/js/pace.min.js') ?>" type="text/javascript"></script>
<!-- FastClick -->
<script src="<?php echo base_url('assets/plugins/fastclick/fastclick.min.js') ?>" type="text/javascript"></script>
<!-- bootstrap timepicker -->
<script src="<?php echo base_url('assets/js/jquery-ui-sliderAccess.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/jquery-ui-timepicker-addon.min.js') ?>" type="text/javascript"></script>
<!-- tinymce js -->
<script src="<?php echo base_url('assets/tinymce/tinymce.min.js') ?>" type="text/javascript"></script>
<!-- End Core Plugins -->
<!-- Dashboard js -->
<script src="<?php echo base_url('assets/js/dashboard.js') ?>" type="text/javascript"></script>

<script>
  $(document).ready(function() {
    // Initialize DataTables for tables with the class 'table-bordered' except those with specific IDs
    $('table.table-bordered:not(#dataTableExample2, #dataTableExample3, #dataTableExample4)').each(function() {
      if (!$.fn.DataTable.isDataTable(this)) {
        $(this).DataTable();
      }
    });
  });

</script>
<!-- End Theme label Script-->
<!-- Include module style -->
<?php
    $path = 'application/modules/';
    $map  = directory_map($path);
    if (is_array($map) && sizeof($map) > 0){
      $segment1 = $this->uri->segment(1);
      foreach ($map as $key => $value) {
        $keyval = preg_replace('/[^A-Za-z0-9\-]/', '', $key);
        if($segment1 == $keyval){
          $jsfile  = str_replace("\\", '/', $path.$key.'assets/js/script.js'); 
            if (file_exists($jsfile)) {
            	echo '<script src="'.base_url($jsfile).'" type="text/javascript"></script>'; 
            }
          } 
      }
    }   
?>