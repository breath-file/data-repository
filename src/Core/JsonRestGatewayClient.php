<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 26/06/19
 */

namespace App\Core;

use RuntimeException;
use stdClass;

/**
 * Class JsonRestGatewayClient
 * @package App\Core
 */
class JsonRestGatewayClient
{
    /**
     * @var string
     */
    protected $host;
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * JsonRestGatewayClient constructor.
     * @param string $host
     * @param string $apiKey
     */
    public function __construct(string $host, string $apiKey)
    {
        $this->host = $host;
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $route
     * @param array  $parameters
     * @return stdClass
     */
    public function sendGet(string $route, array $parameters): stdClass
    {
        $parameters['api-key'] = $this->apiKey;
        $uri = sprintf(
            '%s%s?%s',
            $this->host,
            $route,
            http_build_query($parameters)
        );

        $content = file_get_contents($uri);
        if (! $content) {
            var_dump($uri);
            throw new RuntimeException('Error on API call');
        }

        return json_decode($content, false);
    }
}
