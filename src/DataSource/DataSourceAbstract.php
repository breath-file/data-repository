<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 26/06/19
 */

namespace App\DataSource;

use App\Core\JsonRestGatewayClient;
use App\Domain\Entity\LocationEntity;
use App\Domain\Entity\MeasureEntity;
use App\Domain\Repository\DataSourceInterface;
use App\Domain\ValueObject\DataSource;
use App\Domain\ValueObject\MeasureCategory;
use DateTimeInterface;
use Psr\Container\ContainerInterface;

/**
 * Class DataSourceAbstract
 * @package App\DataSource
 */
abstract class DataSourceAbstract implements DataSourceInterface
{
    /** @var ContainerInterface */
    protected $container;

    /** @var JsonRestGatewayClient */
    protected $client;

    /**
     * DataSourceAbstract constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->client = $container->get(JsonRestGatewayClient::class);
    }

    /**
     * @return DataSource
     */
    abstract protected function getDataSource(): DataSource;

    /**
     * @param MeasureCategory $category
     * @param string      $metric
     * @param string|null $unit
     * @param string|null $group
     * @return string
     */
    protected function generateMeasureName(MeasureCategory $category, string $metric, string $unit = null, string $group = null): string
    {
        // @todo Cleanup unit
        return implode('_', array_filter([
            (string) $this->getDataSource(),
            (string) $category,
            $metric,
            $unit,
            $group
        ]));
    }

    /**
     * @param MeasureCategory   $category
     * @param LocationEntity    $location
     * @param string            $metric
     * @param float             $value
     * @param DateTimeInterface $measureTime
     * @return MeasureEntity
     */
    protected function factoryMeasureEntity(MeasureCategory $category, LocationEntity $location, string $metric, float $value, DateTimeInterface $measureTime): MeasureEntity
    {
        return (new MeasureEntity())
            ->setDataSource($this->getDataSource())
            ->setCategory($category)
            ->setLocation($location)
            ->setName(
                $this->generateMeasureName(
                    $category, $metric
                )
            )
            ->setValue($value)
            ->setDatetimeUtc($measureTime);
    }
}
