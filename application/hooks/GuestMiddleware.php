
<?php

class GuestMiddleware
{
    public function checkGuest()
    {
       $CI =& get_instance();
		$CI->load->library('session');
		$user_type = $CI->session->userdata('user_type');
		 
        // Check if the user is authenticated
        if ($user_type == 7 || $user_type == 6) {
            // Redirect authenticated users to a different page
            redirect(base_url('Admin_dashboard'));
			exit;
        }
    }
}