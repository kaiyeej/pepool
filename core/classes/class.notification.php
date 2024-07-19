<?php

class Notifications extends Connection {
    function getAccessToken() {
        $serviceAccount = json_decode(file_get_contents('../pepool-mobile-firebase-adminsdk-vc9s2-d72c096ce8.json'), true);

        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT'
        ];

        $now = time();
        $claimSet = [
            'iss' => $serviceAccount['client_email'],
            'scope' => 'https://www.googleapis.com/auth/cloud-platform',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now
        ];

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($header)));
        $base64UrlClaimSet = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($claimSet)));

        $signature = '';
        openssl_sign($base64UrlHeader . '.' . $base64UrlClaimSet, $signature, $serviceAccount['private_key'], 'SHA256');
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        $jwt = $base64UrlHeader . '.' . $base64UrlClaimSet . '.' . $base64UrlSignature;

        $postFields = [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

        $response = curl_exec($ch);
        curl_close($ch);

        $responseData = json_decode($response, true);
        return $responseData['access_token'];

    }

    public function push_notification($device_id, $title, $body){
        // Path to your service account JSON key file
        $serviceAccountPath = 'pepool-mobile-firebase-adminsdk-vc9s2-d72c096ce8.json';

        // Your Firebase project ID
        $projectId = 'pepool-mobile';
        // Example message payload
        $message = [
        'token' => $device_id,
        'notification' => [
            'title' => $title,
            'body' => $body,
            ],
        ];
        try {
        $accessToken = $this->getAccessToken();
        $response = $this->sendMessage($accessToken, $projectId, $message);
            //echo 'Message sent successfully: ' . print_r($response, true);
        } catch (Exception $e) {
            //echo 'Error: ' . $e->getMessage();
        }
    }

    public function sendMessage($accessToken, $projectId, $message){
        $url = 'https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send';
        $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => $message]));
        $response = curl_exec($ch);
        if ($response === false) {
        throw new Exception('Curl error: ' . curl_error($ch));
        }
        curl_close($ch);
        //return json_decode($response, true);
    }
}

?>