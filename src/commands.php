<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

use App\Core\CommandDispatcher;
use App\Domain\CommandHandler\LoadMetricsCommandHandler;
use App\Domain\Repository\LocationRepositoryInterface;
use App\Domain\Repository\MeasureRepositoryInterface;
use Psr\Container\ContainerInterface;

$container = $app->getContainer();

$container[LoadMetricsCommandHandler::class] = static function(ContainerInterface $c) {
    /** @var LocationRepositoryInterface $locationRepository */
    $locationRepository = $c->get(LocationRepositoryInterface::class);

    /** @var MeasureRepositoryInterface $measureRepository */
    $measureRepository = $c->get(MeasureRepositoryInterface::class);
    return new LoadMetricsCommandHandler($locationRepository, $measureRepository);
};


$container[CommandDispatcher::class] = static function(ContainerInterface $c) {
    $dispatcher = new CommandDispatcher($c);
    $dispatcher->map(LoadMetricsCommandHandler::getSupportedCommands(), LoadMetricsCommandHandler::class);
    return $dispatcher;
};
