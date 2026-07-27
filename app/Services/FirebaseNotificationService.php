<?php

namespace App\Services;

class FirebaseNotificationService
{
    protected $projectId;
    protected $credentials;

    public function __construct()
    {
        $this->projectId = 'smartwarehouse-45e10';
        $this->credentials = json_decode(
            file_get_contents(
                base_path('firebase_sdk.json')
            ),
            true
        );
    }

    /**
     * GENERATE ACCESS TOKEN
     */
    private function getAccessToken()
    {
        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT'
        ];

        $now = time();

        $payload = [
            'iss'   => $this->credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $now + 3600,
        ];

        $base64UrlHeader = str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode(json_encode($header))
        );

        $base64UrlPayload = str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode(json_encode($payload))
        );

        $signature = '';

        openssl_sign(
            $base64UrlHeader . "." . $base64UrlPayload,
            $signature,
            $this->credentials['private_key'],
            'SHA256'
        );

        $base64UrlSignature = str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode($signature)
        );

        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        $post = [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        return $result['access_token'] ?? null;
    }

    // send message
    public function send($token, $title, $body, $data = [])
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return [
                'success' => false,
                'message' => 'Gagal generate access token'
            ];
        }

        // $message = [
        //     'message' => [
        //         'token' => $token,
        //         'notification' => [
        //             'title' => $title,
        //             'body'  => $body
        //         ],
        //         'data' => $data
        //     ]
        // ];

        $message = [
            'message' => [
                'token' => $token,
                'data' => array_merge([
                    'title' => $title,
                    'body' => $body
                ], $data)
            ]
        ];

        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ];

        $ch = curl_init();

        curl_setopt(
            $ch,
            CURLOPT_URL,
            'https://fcm.googleapis.com/v1/projects/' .
                $this->projectId .
                '/messages:send'
        );

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        \Log::info('FCM PAYLOAD', [
            'message' => $message
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

        $response = curl_exec($ch);

        \Log::info('FCM RESPONSE', [
            'http_code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
            'response' => $response
        ]);

        if (curl_errno($ch)) {
            return [
                'success' => false,
                'curl_error' => curl_error($ch)
            ];
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return [
            'success' => $httpCode == 200,
            'http_code' => $httpCode,
            'response' => json_decode($response, true)
        ];
    }
}
