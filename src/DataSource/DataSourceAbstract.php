<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 26/06/19
 */

namespace App\DataSource;

use App\Core\CommandDispatcher;
use App\Core\JsonRestGatewayClient;
use App\Domain\Command\AddMeasureCommand;
use App\Domain\CommandHandler\CommandResult;
use App\Domain\Repository\DataSourceInterface;
use App\Domain\ValueObject\MeasureMetric;
use App\Domain\ValueObject\MeasureUnit;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Class DataSourceAbstract
 * @package App\DataSource
 */
abstract class DataSourceAbstract implements DataSourceInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var ContainerInterface */
    protected $container;

    /** @var JsonRestGatewayClient */
    protected $client;

    /** @var CommandDispatcher */
    protected $commandDispatcher;

    /** @var array */
    protected $metricMap = [];

    /** @var array */
    protected $unitMap = [];

    /**
     * DataSourceAbstract constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->client = $container->get(JsonRestGatewayClient::class);
        $this->commandDispatcher = $container->get(CommandDispatcher::class);
        $this->logger = $container->get('logger');
    }

    /**
     * @param AddMeasureCommand $command
     * @return CommandResult
     */
    public function dispatch(AddMeasureCommand $command): CommandResult
    {
        return $this->commandDispatcher->dispatch($command);
    }

    /**
     * @param string $metric
     * @return MeasureMetric
     */
    protected function normalizeMetric(string $metric): MeasureMetric
    {
        if (! isset($this->metricMap[$metric])) {
            throw new MeasureMappingException(sprintf('Unknown metric %s', $metric));
        }
        return $this->metricMap[$metric];
    }

    /**
     * @param string $metric
     * @return MeasureUnit
     */
    protected function normalizeUnit(string $metric): MeasureUnit
    {
        if (! isset($this->unitMap[$metric])) {
            throw new MeasureMappingException(sprintf('Unknown metric %s', $metric));
        }
        return $this->unitMap[$metric];
    }
}
