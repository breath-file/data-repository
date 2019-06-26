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
use App\Domain\Entity\MetricCollection;
use App\Domain\Repository\DataSourceInterface;
use DateTimeImmutable;
use DateTimeZone;
use Exception;

/**
 * Class OpenWeatherMap
 * @package App\DataSource
 */
class OpenWeatherMap extends DataSourceAbstract implements DataSourceInterface
{
    protected $config = [
        'metrics' => [
            'temp'     => ['format' => '%0.2f'],
            'humidity' => ['format' => '%d'],
            'pressure' => ['format' => '%d']
        ]
    ];

    protected $prefix = 'openweathermap';

    /**
     * @var JsonRestGatewayClient
     */
    protected $client;

    /**
     * WeatherExport constructor.
     * @param JsonRestGatewayClient $client
     */
    public function __construct(JsonRestGatewayClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param LocationEntity $location
     * @return MetricCollection
     * @throws Exception
     */
    public function getMetrics(LocationEntity $location): MetricCollection
    {
        $metrics = new MetricCollection();

        $data = $this->client->sendGet(
            '/openweathermap/weather',
            [
                'lat' => $location->getLatitude(),
                'lon' => $location->getLongitude(),
                'units' => 'metric'
            ]);

//        $route = sprintf(
//            'https://api.jeckel-lab.fr/weather?lat=%s&lon=%s&api-key=%s&units=metric',
//            $location->getLatitude(),
//            $location->getLongitude(),
//            getenv('API_KEY')
//        );
//        $data = json_decode(file_get_contents($route), false);

        foreach ($this->config['metrics'] as $metric=>$metricConfig) {
            $metric = (new MeasureEntity())
                ->setName($this->generateMeasureName('weather', $metric))
                ->setLocation($location)
                ->setDatetimeUtc((new DateTimeImmutable())->setTimestamp($data->dt)->setTimezone(new DateTimeZone('UTC')))
                //->setValue((float) sprintf($metricConfig['format'], $data->main->$metric));
                ->setValue((float) $data->main->$metric);

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
