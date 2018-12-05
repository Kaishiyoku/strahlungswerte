<?php

namespace App\Libraries\Odl\Models;

class Location
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string
     */
    private $place;

    /**
     * @var int
     */
    private $measureNetNodeId;

    /**
     * @var int
     */
    private $status;

    /**
     * @var int
     */
    private $height;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float|null
     */
    private $lastMeasuredOneHourValue;

    /**
     * @var string
     */
    private $postalCode;

    /**
     * @param array $json
     * @return Location
     */
    public static function fromJson($json)
    {
        $location = new Location();
        $location->uuid = $json['kenn'];
        $location->place = $json['ort'];
        $location->measureNetNodeId = $json['kid'];
        $location->status = $json['status'];
        $location->height = $json['hoehe'];
        $location->longitude = $json['lon'];
        $location->lastMeasuredOneHourValue = array_key_exists('mw', $json) ? $json['mw'] : null;
        $location->latitude = $json['lat'];
        $location->postalCode = $json['plz'];

        return $location;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @return int
     */
    public function getMeasureNetNodeId()
    {
        return $this->measureNetNodeId;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return float|null
     */
    public function getLastMeasuredOneHourValue()
    {
        return $this->lastMeasuredOneHourValue;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }
}
