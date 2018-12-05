<?php

namespace App\Libraries\Odl;

use App\Libraries\Odl\Models\Location;
use App\Libraries\Odl\Models\MeasurementSite;
use GuzzleHttp\Client;

class OdlFetcher
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $locations;

    /**
     * @param string $baseUrl
     * @param string $username
     * @param string $password
     */
    public function __construct($baseUrl, $username, $password)
    {
        $this->baseUrl = $baseUrl;
        $this->username = $username;
        $this->password = $password;

        $this->httpClient = new Client(['defaults' => ['verify' => false], 'auth' => [$this->username, $this->password]]);

        $this->locations = collect();
    }

    /**
     * @param string $uri
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function fetchUrl($uri)
    {
        $response = $this->httpClient->get($this->getUrlFor($uri));

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $uri
     * @return string
     */
    private function getUrlFor($uri)
    {
        return $this->baseUrl . '/json/' . $uri;
    }

    /**
     * @return \Illuminate\Support\Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchLocations()
    {
        $locations = collect();

        foreach ($this->fetchUrl('stamm.json') as $locationJson) {
            $locations->push(Location::fromJson($locationJson));
        }

        $this->locations = $locations;

        return $locations;
    }

    /**
     * @param $uuid
     * @param bool $withCosmicRate
     * @return MeasurementSite
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getMeasurementSite($uuid, $withCosmicRate = false)
    {
        $data = $this->fetchUrl($uuid . ($withCosmicRate ? 'ct' : '') . '.json');

        return MeasurementSite::fromJson($data);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * @param \Illuminate\Support\Collection $locations
     */
    public function setLocations($locations)
    {
        $this->locations = $locations;
    }
}
