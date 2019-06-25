<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

namespace App\DataSource;

use App\Domain\Entity\LocationEntity;
use App\Domain\Entity\MeasureEntity;
use App\Domain\Entity\MetricCollection;
use App\Domain\Repository\DataSourceInterface;
use DateTimeImmutable;
use DateTimeZone;

/**
 * Class Breezometer
 * @package App\DataSource
 */
class Breezometer implements DataSourceInterface
{
    public function getMetrics(LocationEntity $location): MetricCollection
    {
        $metrics = new MetricCollection();
        $route = sprintf(
            'https://api.jeckel-lab.fr/breezometer/airquality?api-key=%s&lat=%s&lon=%s&features=breezometer_aqi,pollutants_concentrations',
            getenv('API_KEY'),
            $location->getLatitude(),
            $location->getLongitude()
        );

        var_dump($route);

        $data = json_decode(file_get_contents($route), false);

        foreach ($data->data->pollutants as $key=>$pollutant) {
            $metric = (new MeasureEntity())
                ->setName(sprintf('%s_%s', $key, $pollutant->concentration->units))
                ->setLocation($location)
                ->setValue($pollutant->concentration->value)
                ->setDatetimeUtc((new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC')));
            $metrics[] = $metric;
        }

        $metrics[] = (new MeasureEntity())
            ->setLocation($location)
            ->setName('aqi')
            ->setValue($data->data->indexes->baqi->aqi)
            ->setDatetimeUtc((new DateTimeImmutable($data->data->datetime))->setTimezone(new DateTimeZone('UTC')));
        return $metrics;
    }

}
