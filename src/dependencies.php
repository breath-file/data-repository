<?php
/**
 * User: jeckel
 * Date: 20/08/18
 * Time: 18:06
 */
// DIC configuration
use App\Core\JsonRestGatewayClient;
use App\DataSource\Breezometer;
use App\DataSource\OpenWeatherMap;
use App\Domain\Repository\LocationRepositoryInterface;
use App\Domain\Repository\MeasureRepositoryInterface;
use App\Formatter\PrometheusFormatter;
use App\Repository\LocationRepository;
use App\Repository\MeasureRepository;
use Illuminate\Database\Capsule\Manager;
use Psr\Container\ContainerInterface;

$container = $app->getContainer();
// view renderer
//$container['renderer'] = function ($c) {
//    $settings = $c->get('settings')['renderer'];
//    return new Slim\Views\PhpRenderer($settings['template_path']);
//};

// monolog
$container['logger'] = static function (ContainerInterface $c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$capsule = new Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();
//pass the connection to global container (created in previous article)

$container['db'] = function($c) use ($capsule){
    return $capsule;
};

// Register services
/**
 * @var string $key
 * @var string $classname
 */
foreach($container['service_manager']['instantiables'] as $key=>$classname) {
    $container[$key] = static function (ContainerInterface $c) use ($classname) { return new $classname($c); };
}

$container[PrometheusFormatter::class] = static function(ContainerInterface $c) {
    return new PrometheusFormatter('omeglast_weather_');
};
$container[JsonRestGatewayClient::class] = static function(ContainerInterface $c) {
    $settings = $c->get('settings')['api_gateway'];
    return new JsonRestGatewayClient($settings['host'], $settings['api_key']);
};

require_once 'commands.php';
require_once 'controllers.php';
