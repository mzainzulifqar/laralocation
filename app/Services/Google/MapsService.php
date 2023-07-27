<?php

namespace App\Services\Google;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class MapsService
{
    private $api_key;
    private $endpoint;
    public $latitude;
    public $longitude;
    public $client;

    public function __construct()
    {
        $this->api_key = config('services.google.api_key');
        $this->endpoint = config('services.google.maps_enpoint');
        $this->client = new Client();

    }

    public function fetch($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $client = $this->client;
        $response = $client->get($this->endpoint, [
            'query' => [
                'latlng' => $this->latitude . ',' . $this->longitude,
                'key' => $this->api_key,
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['results'][0]['formatted_address'])) {
            return $data['results'][0]['formatted_address'];
        }

        return false;
    }

    public function route($origin, $destination){

        $client = $this->client;
        $response = $client->get($this->endpoint, [
            'origin' => $origin,
            'destination' => $destination,
            'key' => $this->api_key,
        ]);

        $data = json_decode($response->getBody(), true);
        return $data;
    }
}
