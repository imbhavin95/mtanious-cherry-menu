<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ItemsModel');
        $this->load->model('UsersModel');
        $this->load->model('SettingsModel');
    }

    public function expiring_subscription()
    {
        $date = strtotime("+7 day");
        $dateToday = date('Y-m-d H:i:s');
        $dateCheck = date('Y-m-d H:i:s', $date);
        $expiringRestaurants = "SELECT * FROM `package_details` where  status='activate' and end_date >= '".$dateToday."' AND end_date <= '".$dateCheck."' and flag=1";
        $result = $this->UsersModel->run_manual_query($expiringRestaurants);

        foreach ($result as $row) {
            $expiringRestaurants = "SELECT `name`, `email` FROM `users` where  id='".$row['restaurant_id']."'";
            $resultUser = $this->UsersModel->run_manual_query($expiringRestaurants);

            $email_data = ['subject' => 'Your subscription expiring soon!', 'name' => $resultUser[0]['name'], 'url' => base_url('login'), 'expiring_date' => date('d M', strtotime($row['end_date']))];

            send_email(trim($resultUser[0]['email']), 'expiring_subscription', $email_data);
            // send_email(trim('imbhavin95@gmail.com'), 'expiring_subscription', $email_data);
        }
    }

    public function admin_expiring_subscription()
    {
        $date = strtotime("+7 day");
        $dateToday = date('Y-m-d H:i:s');
        $dateCheck = date('Y-m-d H:i:s', $date);
        $expiringRestaurants = "SELECT * FROM `package_details` where  status='activate' and package_id!='3' and end_date >= '".$dateToday."' AND end_date <= '".$dateCheck."' and flag=1";
        $result = $this->UsersModel->run_manual_query($expiringRestaurants);

        $users = [];
        foreach ($result as $row) {
            $expiringRestaurants = "SELECT `name`, `email` FROM `users` where  id='".$row['restaurant_id']."'";
            $resultUser = $this->UsersModel->run_manual_query($expiringRestaurants);

            foreach ($resultUser as $user){
                $users[] = $user;
            }
        }

        $email_data = ['subject' => 'Below subscription expiring soon!', 'name' => $resultUser[0]['name'], 'url' => base_url('login'), 'expiring_date' => date('d M', strtotime($row['end_date'])), 'users' => $users];
        send_email('info@cherrymenu.com', 'admin_expiring_subscription', $email_data);
        // send_email(trim('imbhavin95@gmail.com'), 'admin_expiring_subscription', $email_data);
        exit;
    }

    public function admin_expired_subscription()
    {
        $date = strtotime("+7 day");
        $dateToday = date('Y-m-d H:i:s');
        $expiringRestaurants = "SELECT * FROM `package_details` where  status='activate' and package_id!='3' and end_date <= '".$dateToday."' and flag=1";
        $result = $this->UsersModel->run_manual_query($expiringRestaurants);

        $users = [];
        foreach ($result as $row) {
            $expiringRestaurants = "SELECT `name`, `email` FROM `users` where  id='".$row['restaurant_id']."'";
            $resultUser = $this->UsersModel->run_manual_query($expiringRestaurants);

            foreach ($resultUser as $user){
                $users[] = $user;
            }
        }

        $email_data = ['subject' => 'Below subscriptions are expired!', 'name' => $resultUser[0]['name'], 'url' => base_url('login'), 'expiring_date' => date('d M', strtotime($row['end_date'])), 'users' => $users];
        send_email('info@cherrymenu.com', 'admin_expired_subscription', $email_data);
        // send_email(trim('imbhavin95@gmail.com'), 'admin_expired_subscription', $email_data);
        exit;
    }
}
