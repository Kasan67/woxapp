<?php

use Phalcon\Mvc\Model;

class Cars extends Model
{
    public $id;

    public $status;

    public $color;

    public $direction;

    public $red_number;

    public $year;

    public $brand;

    public $model;

    public $currency;

    public $planting_costs;

    public $driver_id;

    public $driver_phone;

    public $costs_per_1;

    public $car_photo;

    public $location;

    public function getLocation()
    {
        return unserialize($this->location);
    }
}