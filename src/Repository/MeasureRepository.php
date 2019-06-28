<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

namespace App\Repository;

use App\Domain\Entity\MeasureEntity;
use App\Domain\Repository\MeasureRepositoryInterface;
use Psr\Container\ContainerInterface;

/**
 * Class MeasureRepository
 * @package App\Repository
 */
class MeasureRepository implements MeasureRepositoryInterface
{
    /** @var DataSourceRepository */
    protected $dataSourceRepository;

    /** @var MeasureCategoryRepository */
    protected $measureCategoryRepository;

    public function __construct(ContainerInterface $container)
    {
        $this->dataSourceRepository = $container->get(DataSourceRepository::class);
        $this->measureCategoryRepository = $container->get(MeasureCategoryRepository::class);
    }

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
        $measure->data_source_id = $this->dataSourceRepository->findOneByCode((string) $instantMeasure->getDataSource())->data_source_id;
        $measure->measure_category_id = $this->measureCategoryRepository->findOneByCode((string) $instantMeasure->getCategory())->measure_category_id;
        $measure->save();
        return true;
    }
}
