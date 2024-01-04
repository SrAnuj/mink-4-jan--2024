<?php
class NotificationController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->auth->check_user_auth();
         
       
    $this->load->model(array('NotificationModel'));
    }

    public function markAsRead($notificationId) {
     
        $this->NotificationModel->markAsRead($notificationId);
       $user_id = $this->session->userdata('user_type');
        redirect('notification/show_all_notification/'.$user_id);

    }
    public function showAllNotifications($userId)
    {
       //var_dump($userId);
       (int) $userId;
       
        
       $this->load->model(array('web/NotificationModel'));
       $all_notification = $this->NotificationModel->getUnreadNotifications($userId);

        $data['notifications'] = $all_notification; // Pass the notifications to the view
       $read_notification = $this->NotificationModel->getreadNotifications($userId);
       
        $data['read_notifications'] = $read_notification;
       // $this->load->view('notifications/all_notification', $data);
         $content = $this->parser->parse('notifications/all_notification',$data,true);
        $this->template_lib->full_admin_html_view($content);

    }
    
    public function removeNotification($notification_id){
       $this->NotificationModel->delete_notification($notification_id);
        $user_id = $this->session->userdata('user_type');
        redirect('notification/show_all_notification/'.$user_id);
    }
}

?>