<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 24/06/19
 */

namespace App\DataSource;

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
class OpenWeatherMap implements DataSourceInterface
{
    protected $config = [
        'metrics' => [
            'temp'     => ['format' => '%0.2f'],
            'humidity' => ['format' => '%d'],
            'pressure' => ['format' => '%d']
        ]
    ];

    /**
     * WeatherExport constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = array_replace_recursive($this->config, $config);
    }

    /**
     * @param LocationEntity $location
     * @return MetricCollection
     * @throws Exception
     */
    public function getMetrics(LocationEntity $location): MetricCollection
    {
        $metrics = new MetricCollection();
        $route = sprintf(
            'https://api.jeckel-lab.fr/weather?lat=%s&lon=%s&api-key=%s&units=metric',
            $location->getLatitude(),
            $location->getLongitude(),
            getenv('API_KEY')
        );
        $data = json_decode(file_get_contents($route), false);

        foreach ($this->config['metrics'] as $metric=>$metricConfig) {
            $metric = (new MeasureEntity())
                ->setName($metric)
                ->setLocation($location)
                ->setDatetimeUtc((new DateTimeImmutable())->setTimestamp($data->dt)->setTimezone(new DateTimeZone('UTC')))
                //->setValue((float) sprintf($metricConfig['format'], $data->main->$metric));
                ->setValue((float) $data->main->$metric);

            $metrics[] = $metric;
        }
        return $metrics;
    }
}
