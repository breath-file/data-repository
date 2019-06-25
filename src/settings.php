<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

use App\Task\ImportBreezometerTask;
use App\Task\ImportOpenWeatherMapTask;

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        // Renderer settings
//        'renderer' => [
//            'template_path' => __DIR__ . '/../templates/',
//        ],
        // Monolog settings
//        'logger' => [
//            'name' => 'RecipeBudget-API',
//            'path' => getenv('DOCKER') ? 'php://stdout' : __DIR__ . '/../logs/app.log',
//            'level' => \Monolog\Logger::DEBUG,
//        ],
        'db' => [
            'driver' => 'mysql',
            'host' => getenv('MYSQL_HOST'),
            'database' => getenv('MYSQL_DATABASE'),
            'username' => getenv('MYSQL_USER'),
            'password' => getenv('MYSQL_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ]
    ],
    'service_manager' => [
        'factories' => [
        ],
        'invokables' => [
        ],
        'initializers' => [
        ]
    ],
    'commands' => [
        'OpenWeatherMap' => ImportOpenWeatherMapTask::class,
        'Breezometer'    => ImportBreezometerTask::class
    ],
];
