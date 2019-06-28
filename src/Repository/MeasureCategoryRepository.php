<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 28/06/19
 */

namespace App\Repository;

use RuntimeException;

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
            $result = MeasureCategory::where('code', '=', $code)->get();
            if (count($result) !== 1) {
                throw new RuntimeException('Error');
            }
            $this->loadedCategories[$code] = $result[0];
        }
        return $this->loadedCategories[$code];
    }
}
