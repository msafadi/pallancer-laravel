<?php
namespace App\Channels;

use Exception;

class HotSms
{
    public function send($notifiable, $notification)
    {
        $config = config('services.hotsms');

        $url = 'http://www.hostsms.ps/sendbuklksms.php'
             . '?user_name=%s&user_pass=%s&sender=%s&mobile=%s&type=1&text=%s';

        $url = sprintf($url
            , $config['username']
            , $config['password']
            , $config['sender']
            , $notifiable->routeNotificationForNexmo($notification)
            , $notification->toHotsms($notifiable));

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        $response = curl_exec($ch);
        if ($response == 1001) {
            return true;
        }

        throw new Exception('HotSms Error: ' . $response);
    }
}