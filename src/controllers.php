<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

use App\Controller\MetricController;
use Psr\Container\ContainerInterface;

$container = $app->getContainer();

$container[MetricController::class] = static function(ContainerInterface $c) { return new MetricController($c); };
