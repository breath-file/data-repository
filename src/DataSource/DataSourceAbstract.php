<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 26/06/19
 */

namespace App\DataSource;

use App\Core\JsonRestGatewayClient;
use App\Domain\Repository\DataSourceInterface;
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
     * @return string
     */
    abstract protected function getMeasurePrefix(): string;

    /**
     * @param string      $category
     * @param string      $metric
     * @param string|null $unit
     * @param string|null $group
     * @return string
     */
    protected function generateMeasureName(string $category, string $metric, string $unit = null, string $group = null): string
    {
        // @todo Cleanup unit
        return implode('_', array_filter([$this->getMeasurePrefix(), $category, $metric, $unit, $group]));
    }
}
