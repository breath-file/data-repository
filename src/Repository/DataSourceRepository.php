<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 28/06/19
 */

namespace App\Repository;

/**
 * Class DataSourceRepository
 * @package App\Repository
 */
class DataSourceRepository
{
    /**
     * @var array
     */
    protected $loadedDataSources = [];

    /**
     * @param string $code
     * @return DataSource
     */
    public function findOneByCode(string $code): DataSource
    {
        if (! isset($this->loadedDataSources[$code])) {
            $this->loadedDataSources[$code] = DataSource::where('code', '=', $code)->findOrFail(1);
        }
        return $this->loadedDataSources[$code];
    }
}
