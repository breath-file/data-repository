<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

namespace App\Domain\Repository;

use App\Domain\Entity\LocationCollection;

/**
 * Interface LocationRepositoryInterface
 * @package App\Domain\Repository
 */
interface LocationRepositoryInterface
{
    /**
     * @return LocationCollection
     */
    public function list(): LocationCollection;
}
