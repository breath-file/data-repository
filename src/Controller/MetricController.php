<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

namespace App\Controller;

use App\Domain\Entity\MeasureEntity;
use App\Formatter\PrometheusFormatter;
use DateTimeImmutable;
use Exception;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Facades\DB;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class MetricController
 * @package App\Controller
 */
class MetricController extends ControllerAbstract
{

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     * @throws Exception
     */
    public function getMetrics(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'text/plain');

        /** @var Manager $db */
        $db = $this->container->get('db');


        $results = $db->getConnection()->select('SELECT o.location_id, o.name, o.value, o.measured_at
            FROM measure AS o
            LEFT JOIN measure AS b
              ON o.location_id = b.location_id
                AND o.name = b.name
                AND o.measured_at < b.measured_at
            WHERE b.name IS NULL;');

        foreach($results as $row) {
            $response->getBody()->write(
                sprintf(
                    "%s_%s %f %d\n",
                    'Omeglast',
                    $row->name,
                    $row->value,
                    (new DateTimeImmutable($row->measured_at))->getTimestamp()
                )
            );
        }
        //var_dump($results);

//        /** @var PrometheusFormatter $formatter */
//        $formatter = $this->container->get(PrometheusFormatter::class);
//        /** @var MeasureEntity $metric */
//        foreach ($result->getResult() as $metric) {
//            $response->getBody()->write($formatter->format($metric). "\n");
//        }

//        $response->getBody()->write("Foobar\n");
        return $response;
    }
}
