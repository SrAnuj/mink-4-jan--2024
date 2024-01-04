<?php


class NotificationModel extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function createNotification($data) {
        $this->db->insert('notifications', $data);
        return $this->db->insert_id();
    }

    public function markAsRead($notificationId) {
        $this->db->where('id', $notificationId)
                 ->update('notifications', ['read_at' => date('Y-m-d H:i:s')]);
    }

    public function getUnreadNotifications($userId) {
        return $this->db->where('user_id', $userId)
                       ->where('read_at', null)
                       ->order_by('created_at', 'desc')
                       ->get('notifications')
                       ->result();
    }
    public function getreadNotifications($userId) {
        return $this->db->where('user_id', $userId)
                       ->where('read_at IS NOT NULL', null, false)
                       ->order_by('created_at', 'desc')
                       ->get('notifications')
                       ->result();
    }
    public function delete_notification($notification_id)
    {
       $this->db->where('id', $notification_id);
        $this->db->delete('notifications');
    }
}


?>