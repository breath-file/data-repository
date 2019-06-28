<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

namespace App\DataSource;

use App\Core\JsonRestGatewayClient;
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
    public function getMetrics(LocationEntity $location): MeasureCollection
    {
        return $this->loadPollen($location);
//            ->merge($this->loadPollution($location))
//            ->merge($this->loadWeather($location));
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

        foreach ($data->data->types as $type=>$values) {

            if (! $values->data_available) {
                continue;
            }
            $measures[] = $this->factoryMeasureEntity(
                MeasureCategory::POLLEN(),
                $location,
                sprintf('type_%s', $type),
                $values->index->value,
                (new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC'))
            );
//            $measures[] = (new MeasureEntity())
//                ->setName(sprintf('%s_pollen_type_%s', $this->prefix, $type))
//                ->setLocation($location)
//                ->setValue($values->index->value)
//                ->setDatetimeUtc((new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC')));
        }

//        foreach ($data->data->plants as $plant=>$values) {
//
//            if (! $values->data_available) {
//                continue;
//            }
//
//            $measures[] = (new MeasureEntity())
//                ->setName(sprintf('%s_pollen_plant_%s', $this->prefix, $plant))
//                ->setLocation($location)
//                ->setValue($values->index->value)
//                ->setDatetimeUtc((new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC')));
//        }

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

        foreach ($data->data->pollutants as $key=>$pollutant) {
            $measures[] = (new MeasureEntity())
                ->setName(sprintf('%s_pollution_%s_%s', $this->prefix, $key, $pollutant->concentration->units))
                ->setLocation($location)
                ->setValue($pollutant->concentration->value)
                ->setDatetimeUtc((new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC')));
            $measures[] = (new MeasureEntity())
                ->setName(sprintf('%s_pollution_%s_aqi', $this->prefix, $key))
                ->setLocation($location)
                ->setValue($pollutant->aqi_information->baqi->aqi)
                ->setDatetimeUtc((new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC')));
        }

        $measures[] = (new MeasureEntity())
            ->setLocation($location)
            ->setName(sprintf('%s_pollution_aqi', $this->prefix))
            ->setValue($data->data->indexes->baqi->aqi)
            ->setDatetimeUtc((new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC')));

        if (property_exists($data->data->indexes, 'fra_atmo')) {
            $measures[] = (new MeasureEntity())
                ->setLocation($location)
                ->setName(sprintf('%s_pollution_fra_atmo', $this->prefix))
                ->setValue($data->data->indexes->fra_atmo->aqi)
                ->setDatetimeUtc((new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC')));
        }
        if (property_exists($data->data->indexes, 'usa_epa')) {
            $measures[] = (new MeasureEntity())
                ->setLocation($location)
                ->setName(sprintf('%s_pollution_usa_epa', $this->prefix))
                ->setValue($data->data->indexes->usa_epa->aqi)
                ->setDatetimeUtc((new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC')));
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

        // Temperature
        $measures[] = (new MeasureEntity())
            ->setLocation($location)
            ->setName($this->generateMeasureName('weather', 'temp'))
            ->setValue($data->data->temperature->value)
            ->setDatetimeUtc((new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC')));

        // Humidity
        $measures[] = (new MeasureEntity())
            ->setLocation($location)
            ->setName($this->generateMeasureName('weather', 'humidity'))
            ->setValue($data->data->relative_humidity)
            ->setDatetimeUtc((new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC')));

        // Pressure
        $measures[] = (new MeasureEntity())
            ->setLocation($location)
            ->setName($this->generateMeasureName('weather', 'pressure'))
            ->setValue($data->data->pressure->value)
            ->setDatetimeUtc((new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC')));

        // Precipitation Probability
        $measures[] = (new MeasureEntity())
            ->setLocation($location)
            ->setName($this->generateMeasureName('weather', 'precipitation_probability'))
            ->setValue($data->data->precipitation->precipitation_probability)
            ->setDatetimeUtc((new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC')));

        // Total precipitation
        $measures[] = (new MeasureEntity())
            ->setLocation($location)
            ->setName($this->generateMeasureName('weather', 'precipitation', 'mm', 'total'))
            ->setValue($data->data->precipitation->total_precipitation->value)
            ->setDatetimeUtc((new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC')));

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
