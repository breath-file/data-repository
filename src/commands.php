<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

use App\Core\CommandDispatcher;
use App\DataSource\Breezometer;
use App\DataSource\OpenWeatherMap;
use App\Domain\Command\LoadMetricsCommand;
use App\Domain\ValueObject\MeasureCategory;
use Psr\Container\ContainerInterface;

$container = $app->getContainer();

// Register CommandDispatcher and CommandHandlers
$container[CommandDispatcher::class] = static function(ContainerInterface $c) {
    $dispatcher = new CommandDispatcher($c);
    foreach($c['service_manager']['commandHandlers'] as $handlerClassname) {
        $c[$handlerClassname] = static function (ContainerInterface $c) use ($handlerClassname) { return new $handlerClassname($c); };
        $dispatcher->map($handlerClassname::getSupportedCommands(), $handlerClassname);
    }

    return $dispatcher;
};


// Configure Cron predefined commands
$container['Cron-OpenWeatherMap'] = static function(ContainerInterface $c) {
    return new LoadMetricsCommand($c->get(OpenWeatherMap::class));
};
$container['Cron-Breezometer-Pollen'] = static function(ContainerInterface $c) {
    return new LoadMetricsCommand($c->get(Breezometer::class), MeasureCategory::POLLEN());
};
$container['Cron-Breezometer-Pollution'] = static function(ContainerInterface $c) {
    return new LoadMetricsCommand($c->get(Breezometer::class), MeasureCategory::POLLUTION());
};
$container['Cron-Breezometer-Weather'] = static function(ContainerInterface $c) {
    return new LoadMetricsCommand($c->get(Breezometer::class), MeasureCategory::WEATHER());
};
