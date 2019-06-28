<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 28/06/19
 */

namespace App\Repository;

/**
 * Class MeasureCategoryRepository
 * @package App\Repository
 */
class MeasureCategoryRepository
{
    /**
     * @var array
     */
    protected $loadedCategories = [];

    /**
     * @param string $code
     * @return MeasureCategory
     */
    public function findOneByCode(string $code): MeasureCategory
    {
        if (! isset($this->loadedCategories[$code])) {
            $this->loadedCategories[$code] = MeasureCategory::where('code', '=', $code)->findOrFail(1);
        }
        return $this->loadedCategories[$code];
    }
}
