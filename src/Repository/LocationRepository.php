<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

namespace App\Repository;

use App\Domain\Entity\LocationEntity;
use App\Domain\Entity\LocationCollection;
use App\Domain\Repository\LocationRepositoryInterface;
use PDO;

/**
 * Class LocationRepository
 * @package App\Repository
 */
class LocationRepository implements LocationRepositoryInterface
{
    /**
     * @return LocationCollection
     */
    public function list(): LocationCollection
    {
        $locations = new LocationCollection();
        foreach (Location::all() as $item) {
            $locations[] = $this->dtoToModelTranslator($item);
        }
        return $locations;
    }

    /**
     * @param Location $item
     * @return LocationEntity
     */
    protected function dtoToModelTranslator(Location $item): LocationEntity
    {
        return new LocationEntity(
            $item->location_id,
            $item->city,
            $item->country,
            (float) $item->longitude,
            (float) $item->latitude
        );
    }
}
