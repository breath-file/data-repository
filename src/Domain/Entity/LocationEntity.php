<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 24/06/19
 */

namespace App\Domain\Entity;

/**
 * Class LocationEntity
 * @package App\Domain\Entity
 */
class LocationEntity
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var float
     */
    protected $longitude;

    /**
     * @var float
     */
    protected $latitude;

    /**
     * LocationEntity constructor.
     * @param int    $id
     * @param string $city
     * @param string $country
     * @param float  $longitude
     * @param float  $latitude
     */
    public function __construct(int $id, string $city, string $country, float $longitude, float $latitude)
    {
        $this->id = $id;
        $this->city = $city;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->country = $country;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return LocationEntity
     */
    public function setCity(string $city): LocationEntity
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return LocationEntity
     */
    public function setCountry(string $country): LocationEntity
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return LocationEntity
     */
    public function setLongitude(float $longitude): LocationEntity
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return LocationEntity
     */
    public function setLatitude(float $latitude): LocationEntity
    {
        $this->latitude = $latitude;
        return $this;
    }
}
