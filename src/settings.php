<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

use App\DataSource\OpenWeatherMap;
use App\Domain\Repository\LocationRepositoryInterface;
use App\Domain\Repository\MeasureRepositoryInterface;
use App\Repository\DataSourceRepository;
use App\Repository\LocationRepository;
use App\Repository\MeasureCategoryRepository;
use App\Repository\MeasureRepository;
use App\Task\ImportBreezometerTask;
use App\Task\ImportOpenWeatherMapTask;
use App\Task\TaskRunner;
use Monolog\Logger;

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        // Renderer settings
//        'renderer' => [
//            'template_path' => __DIR__ . '/../templates/',
//        ],
        // Monolog settings
        'logger' => [
            'name' => 'Omeglast',
            'path' => 'php://stdout',
            'level' => Logger::DEBUG,
        ],
        'db' => [
            'driver' => getenv('DB_DRIVER'),
            'host' => getenv('DB_HOST'),
            'database' => getenv('DB_DATABASE'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
        'api_gateway' => [
            'host' => getenv('GATEWAY_HOST') ?: 'https://api.jeckel-lab.fr',
            'api_key' => getenv('GATEWAY_API_KEY') ?: die('GATEWAY_API_KEY is not defined')
        ],
    ],
    'service_manager' => [
        'instantiables' => [
            DataSourceRepository::class => DataSourceRepository::class,
            LocationRepositoryInterface::class => LocationRepository::class,
            MeasureCategoryRepository::class => MeasureCategoryRepository::class,
            MeasureRepositoryInterface::class => MeasureRepository::class,
            OpenWeatherMap::class => OpenWeatherMap::class
        ],
        'factories' => [
        ],
        'invokables' => [
        ],
        'initializers' => [
        ]
    ],
    'commands' => [
        'OpenWeatherMap' => ImportOpenWeatherMapTask::class,
        'Breezometer'    => ImportBreezometerTask::class,
        'TaskRunner'     => TaskRunner::class
    ],
];
