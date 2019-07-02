<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 24/06/19
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
use DateTimeZone;
use Exception;
use Psr\Container\ContainerInterface;

/**
 * Class OpenWeatherMap
 * @package App\DataSource
 */
class OpenWeatherMap extends DataSourceAbstract
{
    /** @const array */
    protected const METRIC_KEYS = ['temp', 'humidity', 'pressure'];

    /** @var DataSource */
    protected $dataSource;

    /**
     * OpenWeatherMap constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->metricMap = [
            'temp'     => MeasureMetric::TEMPERATURE(),
            'humidity' => MeasureMetric::HUMIDITY(),
            'pressure' => MeasureMetric::PRESSURE()
        ];

        $this->unitMap = [
            'temp'     => MeasureUnit::CELSIUS(),
            'humidity' => MeasureUnit::PERCENT(),
            'pressure' => MeasureUnit::HECTOPASCAL()
        ];

        $this->dataSource = DataSource::OPEN_WEATHER_MAP();
    }

    /**
     * @param LocationEntity       $location
     * @param MeasureCategory|null $category
     * @return MeasureCollection
     * @throws Exception
     */
    public function getMeasures(LocationEntity $location, MeasureCategory $category = null): MeasureCollection
    {
        if ($category === null) {
            $category = MeasureCategory::WEATHER();
        }

        $metrics = new MeasureCollection();
        $data = $this->client->sendGet(
            '/openweathermap/weather',
            [
                'lat' => $location->getLatitude(),
                'lon' => $location->getLongitude(),
                'units' => 'metric'
            ]);

        $measuredDate = (new DateTimeImmutable())->setTimestamp($data->dt)->setTimezone(new DateTimeZone('UTC'));

        foreach (self::METRIC_KEYS as $metric) {
            try {
                $this->dispatch(new AddMeasureCommand(
                    $this->dataSource,
                    $location,
                    $category,
                    $this->normalizeMetric($metric),
                    $this->normalizeUnit($metric),
                    (float)$data->main->$metric,
                    $measuredDate
                ));
            } catch(MeasureMappingException $e) {
                $this->logger->error($e->getMessage());
            }
        }
        return $metrics;
    }
}
