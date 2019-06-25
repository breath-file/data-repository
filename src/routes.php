<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

use App\Controller\MetricController;

$app->get('/metrics', MetricController::class.':getMetrics');
