<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 24/06/19
 */

namespace App\Domain\Repository;

use App\Domain\Entity\LocationEntity;
use App\Domain\Entity\MeasureCollection;
use App\Domain\ValueObject\MeasureCategory;

/**
 * Interface DataSourceInterface
 * @package App\DataSource
 */
interface DataSourceInterface
{
    /**
     * @param LocationEntity       $location
     * @param MeasureCategory|null $category
     * @return MeasureCollection
     */
    public function getMeasures(LocationEntity $location, MeasureCategory $category = null): MeasureCollection;
}
