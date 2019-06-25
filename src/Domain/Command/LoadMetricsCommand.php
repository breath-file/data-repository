<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 24/06/19
 */

namespace App\Domain\Command;

use App\Domain\Repository\DataSourceInterface;

/**
 * Class LoadMetricsCommand
 * @package App\Domain\Command
 */
class LoadMetricsCommand implements CommandInterface
{
    /**
     * @var DataSourceInterface
     */
    protected $dataSource;

    /**
     * LoadMetricsCommand constructor.
     * @param DataSourceInterface $dataSource
     */
    public function __construct(DataSourceInterface $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * @return DataSourceInterface
     */
    public function getDataSource(): DataSourceInterface
    {
        return $this->dataSource;
    }
}
