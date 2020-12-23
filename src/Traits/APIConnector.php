<?php

namespace ColoredCow\LaravelAPIConnector\Traits;

use ColoredCow\LaravelAPIConnector\Models\APIConnector as Connector;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

trait APIConnector
{
    public $grantType;
    public $clientId;
    public $clientSecret;
    public $accessToken;
    public $tokenType;
    public $expiryAt;
    public $apiConnectorData;

    function initApi()
    {
        $this->apiConnectorData = Connector::latest()->first();
        $this->accessToken = $this->apiConnectorData->access_token;
        $this->tokenType = $this->apiConnectorData->token_type;
        $this->expiryAt = $this->apiConnectorData->expiry_at;
        if ($this->accessToken && $this->hasValidToken()) {
            return true;
        } else {
            return $this->handleAccessTokenGeneration();
        }
    }

    public function hasValidToken()
    {
        return Carbon::now() < $this->expiryAt;
    }

    public function handleAccessTokenGeneration()
    {
        $data = $this->fetchNewAccessToken();
        if (!empty($data)) {
            $this->accessToken = isset($data['access_token']) ? $data['access_token'] : null;
            $this->tokenType = isset($data['token_type']) ? $data['token_type'] : null;
            $this->expiryAt = isset($data['token_type']) ? date('Y-m-d H:i:s', (time() + intval($data['expires_in'] - 60))) : null;
            return $this->apiConnectorData->update([
                'access_token' => $this->accessToken,
                'token_type' => $this->tokenType,
                'expiry_at' => $this->expiryAt,
            ]);
        }
        return false;
    }

    public function fetchNewAccessToken()
    {
        $response = Http::asForm()->post($this->apiConnectorData->auth_url, [
            'grant_type' => $this->apiConnectorData->grant_type,
            'client_id' => $this->apiConnectorData->client_id,
            'client_secret' => $this->apiConnectorData->client_secret,
            'scope' => $this->apiConnectorData->scope,
        ]);
        return $response->json();
    }

    public function makeApiCall($endPoint, $method = 'get', $data = [])
    {
        $this->initApi();
        $url = $this->apiConnectorData->base_url . '/api/' . $endPoint;
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => $this->tokenType . ' ' . $this->accessToken,
        ])->{strtolower($method)}($url, $data);
        return $response->json();
    }
}