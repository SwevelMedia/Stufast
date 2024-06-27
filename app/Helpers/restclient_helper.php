<?php

function consume($method, $url, $data)
{
    $client = \Config\Services::curlrequest();
    // $header = $this->request->getServer('HTTP_AUTHORIZATION');

    // $token = explode(' ', $header)[1];

    $token = '';

    $headers = [
        'Authorization' => 'Bearer ' . $token
    ];

    $response = $client->request($method, $url, ['headers' => $headers, 'http_errors' => false, 'form_params' => $data]);
    return $response->getBody();
}
