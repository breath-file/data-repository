<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

namespace App\Repository;

use App\Domain\Entity\MeasureEntity;
use App\Domain\Repository\MeasureRepositoryInterface;

/**
 * Class MeasureRepository
 * @package App\Repository
 */
class MeasureRepository implements MeasureRepositoryInterface
{
    /**
     * @param MeasureEntity $instantMeasure
     * @return bool
     */
    public function save(MeasureEntity $instantMeasure): bool
    {
        $existingRow = Measure::whereRaw(
            'location_id = ? and name = ? and measured_at = ?',
            [
                $instantMeasure->getLocation()->getId(),
                $instantMeasure->getName(),
                $instantMeasure->getDatetimeUtc()
            ]
        )->first();

        if ($existingRow !== null) {
            // Record already exists
            return true;
        }

        $measure = new Measure();
        $measure->location_id = $instantMeasure->getLocation()->getId();
        $measure->name = $instantMeasure->getName();
        $measure->value = $instantMeasure->getValue();
        $measure->measured_at = $instantMeasure->getDatetimeUtc();
        $measure->data_source = (string) $instantMeasure->getDataSource();
        $measure->category = (string) $instantMeasure->getCategory();
        $measure->save();
        return true;
    }
}
