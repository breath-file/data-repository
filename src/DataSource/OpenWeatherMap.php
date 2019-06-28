<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 24/06/19
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
 * Class OpenWeatherMap
 * @package App\DataSource
 */
class OpenWeatherMap extends DataSourceAbstract
{
    public const DATA_SOURCE_CODE = 'openweathermap';

    protected $config = [
        'metrics' => [
            'temp'     => ['format' => '%0.2f'],
            'humidity' => ['format' => '%d'],
            'pressure' => ['format' => '%d']
        ]
    ];

    protected $prefix = 'openweathermap';
//
//    /**
//     * @var JsonRestGatewayClient
//     */
//    protected $client;
//
//    /**
//     * WeatherExport constructor.
//     * @param JsonRestGatewayClient $client
//     */
//    public function __construct(JsonRestGatewayClient $client)
//    {
//        $this->client = $client;
//    }

    /**
     * @param LocationEntity $location
     * @return MeasureCollection
     * @throws Exception
     */
    public function getMetrics(LocationEntity $location): MeasureCollection
    {
        $metrics = new MeasureCollection();
        $data = $this->client->sendGet(
            '/openweathermap/weather',
            [
                'lat' => $location->getLatitude(),
                'lon' => $location->getLongitude(),
                'units' => 'metric'
            ]);

        foreach ($this->config['metrics'] as $metric=>$metricConfig) {
            $metric = (new MeasureEntity())
                ->setName($this->generateMeasureName('weather', $metric))
                ->setLocation($location)
                ->setDatetimeUtc((new DateTimeImmutable())->setTimestamp($data->dt)->setTimezone(new DateTimeZone('UTC')))
                ->setValue((float) $data->main->$metric)
                ->setDataSource(DataSource::OPEN_WEATHER_MAP())
                ->setCategory(MeasureCategory::WEATHER());

            $metrics[] = $metric;
        }
        return $metrics;
    }

    /**
     * @return string
     */
    protected function getMeasurePrefix(): string
    {
        return $this->prefix;
    }
}
