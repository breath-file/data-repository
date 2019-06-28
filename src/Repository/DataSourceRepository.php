<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 28/06/19
 */

namespace App\Repository;

use RuntimeException;

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
     * @throws RuntimeException
     */
    public function findOneByCode(string $code): DataSource
    {
        if (! isset($this->loadedDataSources[$code])) {
            $result = DataSource::where('code', '=', $code)->get();
            if (count($result) !== 1) {
                throw new RuntimeException('Error');
            }
            $this->loadedDataSources[$code] = $result[0];
        }
        return $this->loadedDataSources[$code];
    }
}
