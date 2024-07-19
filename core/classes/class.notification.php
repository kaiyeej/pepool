<?php

class Notifications extends Connection {
    public function pushNotif($title, $message, $device_id)
    {
        //API URL of FCM
        $url = 'https://fcm.googleapis.com/fcm/send';

        /*api_key available in:
        Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/
        $api_key = 'AAAAMMyIlI0:APA91bEq8wlsqm_gffhxQJvfT4vG0Oo-NV7xGwCtpbgYuzszSJOAnLms8dBB-xj6t1Tf9FyohA_5Gkqdl3AKKKL-e6ffHXlJXirAO0afXXX2tRsHFWXPJ0ZsOVdu0MVWY4urlvZpu0eL';

        $fields = array(
            'registration_ids' => array(
                $device_id
            ),
            /*'data' => array (
                "message" => $message,
                "body" => "Test",
                "title" => "Title"
        )*/
            'notification' => array(
                "body" => $message,
                "title" => $title
            ),
            'data' => array(
                'priority' => 10,
                "message" => $message,
                "body" => $message,
                "title" => $title
            )
        );

        //header includes Content type and api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $api_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
}

?>