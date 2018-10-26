<?php

namespace App\Http\Controllers\API;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class Proxy {
    public $statusCode;
    public function attemptLogin($username, $password) {
        $resp = $this->proxy('/oauth/token', [
            'Accept' => 'application/json'
        ], [
            'grant_type' => 'password',
            'client_id' => 2,
            'client_secret' => "zODeGiaXjYdOZJmhpT3kjYSAIQMXMelMnAJiiTeN",
            'username' => $username,
            'password' => $password,
            'scope' => '*'
        ]);

        $data = json_decode((string) $resp->getBody());
        $this->statusCode = $resp->getStatusCode();
        return [
            'data' => $data
        ];
    }

    private function proxy($path, $headers=[], $formParams=[]) {
        $http = new \GuzzleHttp\Client([
            'base_uri'  =>  'http://test.suphawich.science'
        ]);
        $resp = $http->post($path, [
            'headers' => $headers,
            'form_params' => $formParams,
            'http_errors' => false
        ]);
        return $resp;
    }
}