<?php

namespace App\Http\Service;

use Illuminate\Support\Facades\Http;

class ApiService
{
    public function __construct(private ?string $token = null) {}

    private function apiCall(string $method, string $endpoint, array $body = []): array
    {
        $httpClient = Http::withHeaders([
            'platform' => 'panel'
        ]);

        if($this->token) {
            $httpClient->withToken($this->token);
        }

        $url = config('prezo.api.url') . $endpoint;

        switch ($method) {
            case 'GET':
                $response = $httpClient->get($url);
                break;
            case 'POST':
                $response = $httpClient->post($url, $body);
                break;
            default:
                throw new Exception('Invalid API method');
        }
        return $response->json();
    }

    public function getRestaurants() : array
    {
        return $this->apiCall('GET', '/restaurants');
    }

    public function createRestaurant(array $data) : array
    {
        return $this->apiCall('POST', '/restaurants', $data);
    }

    public function prezoLogin(array $data) : array
    {
        return $this->apiCall('POST', '/login', $data);
    }
}