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

    public function __construct($latitude, $longitude)
    {
        $this->api_key = config('services.google.api_key');
        $this->endpoint = config('services.google.maps_enpoint');
        $this->latitude = $latitude;
        $this->longitude = $longitude;

    }

    public function fetch()
    {
        $client = new Client();
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
}
