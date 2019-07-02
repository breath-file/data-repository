<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

namespace App\DataSource;

use App\Domain\Command\AddMeasureCommand;
use App\Domain\Entity\LocationEntity;
use App\Domain\Entity\MeasureCollection;
use App\Domain\ValueObject\DataSource;
use App\Domain\ValueObject\MeasureCategory;
use App\Domain\ValueObject\MeasureMetric;
use App\Domain\ValueObject\MeasureUnit;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use Psr\Container\ContainerInterface;

/**
 * Class Breezometer
 * @package App\DataSource
 */
class Breezometer extends DataSourceAbstract
{
    /** @var DataSource */
    protected $dataSource;

    /** @var LocationEntity */
    protected $currentLocation;

    /** @var MeasureCategory */
    protected $currentCategory;

    /** @var DateTimeInterface */
    protected $currentDateMeasured;

    /**
     * Breezometer constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->metricMap = [
            'graminales' => MeasureMetric::POLLEN_GRAMINALES(),
            'grass'      => MeasureMetric::POLLEN_GRASS(),
            'ragweed'    => MeasureMetric::POLLEN_RAGWEED(),
            'tree'       => MeasureMetric::POLLEN_TREE(),
            'weed'       => MeasureMetric::POLLEN_WEED(),
            'co'         => MeasureMetric::CARBON_MONOXIDE(),
            'co_aqi'     => MeasureMetric::CARBON_MONOXIDE(),
            'no2'        => MeasureMetric::NITROGEN_DIOXIDE(),
            'no2_aqi'    => MeasureMetric::NITROGEN_DIOXIDE(),
            'o3'         => MeasureMetric::OZONE(),
            'o3_aqi'     => MeasureMetric::OZONE(),
            'pm10'       => MeasureMetric::PM10_PARTICLES(),
            'pm10_aqi'   => MeasureMetric::PM10_PARTICLES(),
            'pm25'       => MeasureMetric::PM2_5_PARTICLES(),
            'pm25_aqi'   => MeasureMetric::PM2_5_PARTICLES(),
            'so2'        => MeasureMetric::SULPHUR_DIOXIDE(),
            'so2_aqi'    => MeasureMetric::SULPHUR_DIOXIDE(),
            'temp'       => MeasureMetric::TEMPERATURE(),
            'humidity'   => MeasureMetric::HUMIDITY(),
            'pressure'   => MeasureMetric::PRESSURE(),
        ];

        $this->unitMap = [
            'graminales' => MeasureUnit::INDEX(),
            'grass'      => MeasureUnit::INDEX(),
            'ragweed'    => MeasureUnit::INDEX(),
            'tree'       => MeasureUnit::INDEX(),
            'weed'       => MeasureUnit::INDEX(),
            'co'         => MeasureUnit::MICROGRAMS_M3(),
            'co_aqi'     => MeasureUnit::PERCENT(),
            'no2'        => MeasureUnit::MICROGRAMS_M3(),
            'no2_aqi'    => MeasureUnit::PERCENT(),
            'o3'         => MeasureUnit::MICROGRAMS_M3(),
            'o3_aqi'     => MeasureUnit::PERCENT(),
            'pm10'       => MeasureUnit::MICROGRAMS_M3(),
            'pm10_aqi'   => MeasureUnit::PERCENT(),
            'pm25'       => MeasureUnit::MICROGRAMS_M3(),
            'pm25_aqi'   => MeasureUnit::PERCENT(),
            'so2'        => MeasureUnit::MICROGRAMS_M3(),
            'so2_aqi'    => MeasureUnit::PERCENT(),
            'temp'       => MeasureUnit::CELSIUS(),
            'humidity'   => MeasureUnit::PERCENT(),
            'pressure'   => MeasureUnit::HECTOPASCAL(),
        ];

        $this->dataSource = DataSource::BREEZOMETER();
    }

    /**
     * @param LocationEntity       $location
     * @param MeasureCategory|null $measureCategory
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

        $this->currentLocation = $location;
        $this->currentCategory = MeasureCategory::POLLEN();
        $this->currentDateMeasured =  (new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC'));;

        foreach ($data->data->types as $metric=>$values) {
            $this->processMeasureRow($metric, $values->index->value);
        }

        foreach ($data->data->plants as $metric=>$values) {
            $this->processMeasureRow($metric, $values->index->value);
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
        $this->currentLocation = $location;
        $this->currentCategory = MeasureCategory::POLLUTION();
        $this->currentDateMeasured =  (new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC'));;

        foreach ($data->data->pollutants as $pollutant=>$values) {
            $this->processMeasureRow($pollutant, $values->concentration->value);
            $this->processMeasureRow(sprintf('%s_aqi', $pollutant), $values->aqi_information->baqi->aqi);
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

        $this->currentLocation = $location;
        $this->currentCategory = MeasureCategory::WEATHER();
        $this->currentDateMeasured =  (new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC'));;

        $this->processMeasureRow('temp', $data->data->temperature->value);
        $this->processMeasureRow('humidity', $data->data->relative_humidity);
        $this->processMeasureRow('pressure', $data->data->pressure->value);

        return $measures;
    }

    /**
     * @param string     $metric
     * @param float|null $value
     * @return Breezometer
     */
    protected function processMeasureRow(string $metric, ?float $value): self
    {
        if ($value === null) {
            return $this;
        }
        try {
            $this->dispatch(new AddMeasureCommand(
                $this->dataSource,
                $this->currentLocation,
                $this->currentCategory,
                $this->normalizeMetric($metric),
                $this->normalizeUnit($metric),
                $value,
                $this->currentDateMeasured
            ));
        } catch (MeasureMappingException $e) {
            $this->logger->error($e->getMessage());
        }
        return $this;
    }
}
