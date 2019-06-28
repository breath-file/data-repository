<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 24/06/19
 */

namespace App\Domain\Command;

use App\Domain\Repository\DataSourceInterface;
use App\Domain\ValueObject\MeasureCategory;

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
     * @var MeasureCategory|null
     */
    protected $category;

    /**
     * LoadMetricsCommand constructor.
     * @param DataSourceInterface  $dataSource
     * @param MeasureCategory|null $category
     */
    public function __construct(DataSourceInterface $dataSource, MeasureCategory $category = null)
    {
        $this->dataSource = $dataSource;
        $this->category = $category;
    }

    /**
     * @return DataSourceInterface
     */
    public function getDataSource(): DataSourceInterface
    {
        return $this->dataSource;
    }

    /**
     * @return MeasureCategory|null
     */
    public function getCategory(): ?MeasureCategory
    {
        return $this->category;
    }
}
