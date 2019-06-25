<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

namespace App\Domain\Repository;

use App\Domain\Entity\MeasureEntity;

/**
 * Interface MeasureRepositoryInterface
 * @package App\Domain\Repository
 */
interface MeasureRepositoryInterface
{
    /**
     * @param MeasureEntity $measure
     * @return bool
     */
    public function save(MeasureEntity $measure): bool;
}
