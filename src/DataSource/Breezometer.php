<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

namespace App\DataSource;

use App\Domain\Entity\LocationEntity;
use App\Domain\Entity\MeasureEntity;
use App\Domain\Entity\MeasureCollection;
use App\Domain\ValueObject\DataSource;
use App\Domain\ValueObject\MeasureCategory;
use DateTimeImmutable;
use DateTimeZone;
use Exception;

/**
 * Class Breezometer
 * @package App\DataSource
 */
class Breezometer extends DataSourceAbstract
{
    /**
     * @var string
     */
    protected $prefix = 'breezometer';

    /**
     * @param LocationEntity $location
     * @return MeasureCollection
     * @throws Exception
     */
    public function getMeasures(LocationEntity $location, MeasureCategory $measureCategory = null): MeasureCollection
    {
        $measures = new MeasureCollection();
        if ($measureCategory === null || $measureCategory == MeasureCategory::WEATHER()) {
            $measures->merge($this->loadWeather($location));
        }

        if ($measureCategory === null || $measureCategory == MeasureCategory::POLLUTION()) {
            $measures->merge($this->loadPollution($location));
        }

        if ($measureCategory === null || $measureCategory == MeasureCategory::POLLEN()) {
            $measures->merge($this->loadPollen($location));
        }

        return $measures;
    }

    /**
     * @return DataSource
     */
    protected function getDataSource(): DataSource
    {
        return DataSource::BREEZOMETER();
    }

    /**
     * @param LocationEntity $location
     * @return MeasureCollection
     * @throws Exception
     */
    public function loadPollen(LocationEntity $location): MeasureCollection
    {
        $measures = new MeasureCollection();

        if ($location->getCountry() !== 'FR') {
            return $measures;
        }

        $data = $this->client->sendGet(
            '/breezometer/pollen',
            [
                'lat' => $location->getLatitude(),
                'lon' => $location->getLongitude(),
                'features' => 'types_information,plants_information'
            ]);

        $dateMeasure = (new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC'));

        foreach ($data->data->types as $type=>$values) {
            if (! $values->data_available) {
                continue;
            }
            $measures[] = $this->factoryMeasureEntity(
                MeasureCategory::POLLEN(),
                $location,
                sprintf('type_%s', $type),
                $values->index->value,
                $dateMeasure
            );
        }

        foreach ($data->data->plants as $plant=>$values) {
            if (! $values->data_available) {
                continue;
            }
            $measures[] = $this->factoryMeasureEntity(
                MeasureCategory::POLLEN(),
                $location,
                sprintf('plant_%s', $plant),
                $values->index->value,
                $dateMeasure
            );
        }

        return $measures;
    }

    /**
     * @param LocationEntity $location
     * @return MeasureCollection
     * @throws Exception
     */
    public function loadPollution(LocationEntity $location): MeasureCollection
    {
        $measures = new MeasureCollection();
        $data = $this->client->sendGet(
            '/breezometer/airquality',
            [
                'lat' => $location->getLatitude(),
                'lon' => $location->getLongitude(),
                'features' => 'breezometer_aqi,pollutants_concentrations,pollutants_aqi_information,local_aqi'
            ]);
        $dateMeasure = (new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC'));

        foreach ($data->data->pollutants as $key=>$pollutant) {
            $measures[] = $this->factoryMeasureEntity(
                MeasureCategory::POLLUTION(),
                $location,
                $key,
                $pollutant->concentration->value,
                $dateMeasure
            );

            $measures[] = $this->factoryMeasureEntity(
                MeasureCategory::POLLUTION(),
                $location,
                sprintf('%s_aqi', $key),
                $pollutant->aqi_information->baqi->aqi,
                $dateMeasure
            );
        }

        $measures[] = $this->factoryMeasureEntity(
            MeasureCategory::POLLUTION(),
            $location,
            'aqi',
            $data->data->indexes->baqi->aqi,
            $dateMeasure
        );

        if (property_exists($data->data->indexes, 'fra_atmo')) {
            $measures[] = $this->factoryMeasureEntity(
                MeasureCategory::POLLUTION(),
                $location,
                'fra_atmo',
                $data->data->indexes->fra_atmo->aqi,
                $dateMeasure
            );
        }
        if (property_exists($data->data->indexes, 'usa_epa')) {
            $measures[] = $this->factoryMeasureEntity(
                MeasureCategory::POLLUTION(),
                $location,
                'usa_epa',
                $data->data->indexes->usa_epa->aqi,
                $dateMeasure
            );
        }

        return $measures;
    }

    /**
     * @param LocationEntity $location
     * @return MeasureCollection
     * @throws Exception
     */
    public function loadWeather(LocationEntity $location): MeasureCollection
    {
        $measures = new MeasureCollection();
        $data = $this->client->sendGet(
            '/breezometer/weather',
            [
                'lat' => $location->getLatitude(),
                'lon' => $location->getLongitude()
            ]);

        $dateMeasure = (new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC'));

        // Temperature
        $measures[] = $this->factoryMeasureEntity(
            MeasureCategory::WEATHER(),
            $location,
            'temp',
            $data->data->temperature->value,
            $dateMeasure
        );

        // Humidity
        $measures[] = $this->factoryMeasureEntity(
            MeasureCategory::WEATHER(),
            $location,
            'humidity',
            $data->data->relative_humidity,
            $dateMeasure
        );

        // Pressure
        $measures[] = $this->factoryMeasureEntity(
            MeasureCategory::WEATHER(),
            $location,
            'pressure',
            $data->data->pressure->value,
            $dateMeasure
        );

        // Precipitation Probability
        $measures[] = $this->factoryMeasureEntity(
            MeasureCategory::WEATHER(),
            $location,
            'precipitation_probability',
            $data->data->precipitation->precipitation_probability,
            $dateMeasure
        );

        // Total precipitation
        $measures[] = $this->factoryMeasureEntity(
            MeasureCategory::WEATHER(),
            $location,
            'precipitation_mm_total',
            $data->data->precipitation->total_precipitation->value,
            $dateMeasure
        );

        return $measures;
    }

    /**
     * @return string
     */
    protected function getMeasurePrefix(): string
    {
        return $this->prefix;
    }
}
