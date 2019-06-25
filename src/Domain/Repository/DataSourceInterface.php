<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 24/06/19
 */

namespace App\Domain\Repository;

use App\Domain\Entity\LocationEntity;
use App\Domain\Entity\MetricCollection;
use Exception;

/**
 * Interface DataSourceInterface
 * @package App\DataSource
 */
interface DataSourceInterface
{
    /**
     * @param LocationEntity $location
     * @return MetricCollection
     * @throws Exception
     */
    public function getMetrics(LocationEntity $location): MetricCollection;
}
