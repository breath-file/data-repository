<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 24/06/19
 */
namespace App\Formatter;


use App\Domain\Entity\MeasureEntity;

/**
 * Class PrometheusFormatter
 * @package App\Formatter
 */
class PrometheusFormatter implements FormatterInterface
{
    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * PrometheusFormatter constructor.
     * @param string $prefix
     */
    public function __construct(string $prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @param MeasureEntity $metric
     * @return string
     */
    public function format(MeasureEntity $metric): string
    {
        $labels = $metric->getLabels();
        if (($location = $metric->getLocation()) !== null) {
            $labels['location'] = sprintf('%s,%s', $location->getCity(), $location->getCountry());
            $labels['latitude'] = $location->getLatitude();
            $labels['longitude'] = $location->getLongitude();
        }

        $toReturn = sprintf(
            '%s%s%s %s',
            $this->prefix,
            $metric->getName(),
            $this->getLabelsAsString($labels),
            $metric->getValue()
        );

        if (($date = $metric->getDatetime()) !== null) {
            $toReturn .= ' ' . $date->getTimestamp();
        }

        return $toReturn;
    }

    /**
     * @param array $labels
     * @return string
     */
    protected function getLabelsAsString(array $labels = []): string
    {
        if (empty($labels)) {
            return '';
        }
        return sprintf('{%s}',
            implode(
                ',',
                array_map(
                    static function ($k, $v) { return sprintf('%s="%s"', $k, $v); },
                    array_keys($labels),
                    $labels
                )
            )
        );
    }
}
