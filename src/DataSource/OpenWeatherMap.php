<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 24/06/19
 */

namespace App\DataSource;

use App\Domain\Entity\LocationEntity;
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
    protected $config = [
        'metrics' => [
            'temp'     => ['format' => '%0.2f'],
            'humidity' => ['format' => '%d'],
            'pressure' => ['format' => '%d']
        ]
    ];

    /**
     * @param LocationEntity       $location
     * @param MeasureCategory|null $category
     * @return MeasureCollection
     * @throws Exception
     */
    public function getMeasures(LocationEntity $location, MeasureCategory $category = null): MeasureCollection
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
            $metrics[] = $this->factoryMeasureEntity(
                MeasureCategory::WEATHER(),
                $location,
                $metric,
                (float) $data->main->$metric,
                (new DateTimeImmutable())->setTimestamp($data->dt)->setTimezone(new DateTimeZone('UTC'))
            );
        }
        return $metrics;
    }

    /**
     * @return DataSource
     */
    protected function getDataSource(): DataSource
    {
        return DataSource::OPEN_WEATHER_MAP();
    }
}
