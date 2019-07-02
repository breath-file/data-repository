<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 30/06/19
 */

namespace App\Domain\Command;

use App\Domain\Entity\LocationEntity;
use App\Domain\ValueObject\DataSource;
use App\Domain\ValueObject\MeasureCategory;
use App\Domain\ValueObject\MeasureMetric;
use App\Domain\ValueObject\MeasureUnit;
use DateTimeInterface;
use UnexpectedValueException;

/**
 * Class AddMeasureCommand
 * @package App\Domain\Command
 */
class AddMeasureCommand implements CommandInterface
{
    /**
     * @var DataSource
     */
    protected $dataSource;
    /**
     * @var MeasureCategory
     */
    protected $category;
    /**
     * @var MeasureMetric
     */
    protected $metric;
    /**
     * @var MeasureUnit
     */
    protected $unit;
    /**
     * @var float
     */
    protected $value;
    /**
     * @var DateTimeInterface
     */
    protected $measuredDate;
    /**
     * @var LocationEntity
     */
    protected $location;

    /**
     * AddMeasureCommand constructor.
     * @param DataSource        $dataSource
     * @param LocationEntity    $location
     * @param MeasureCategory   $category
     * @param MeasureMetric|string     $metric
     * @param MeasureUnit       $unit
     * @param float             $value
     * @param DateTimeInterface $measuredDate
     */
    public function __construct(
        DataSource $dataSource,
        LocationEntity $location,
        MeasureCategory $category,
        $metric,
        MeasureUnit $unit,
        float $value,
        DateTimeInterface $measuredDate
    )
    {
        if (! (is_string($metric) || $metric instanceof MeasureMetric) ) {
            throw new UnexpectedValueException('Invalid type for Metric parameter');
        }

        $this->dataSource = $dataSource;
        $this->category = $category;
        $this->metric = $metric;
        $this->unit = $unit;
        $this->value = $value;
        $this->measuredDate = $measuredDate;
        $this->location = $location;
    }

    /**
     * @return DataSource
     */
    public function getDataSource(): DataSource
    {
        return $this->dataSource;
    }

    /**
     * @return MeasureCategory
     */
    public function getCategory(): MeasureCategory
    {
        return $this->category;
    }

    /**
     * @return MeasureMetric|string
     */
    public function getMetric()
    {
        return $this->metric;
    }

    /**
     * @return MeasureUnit
     */
    public function getUnit(): MeasureUnit
    {
        return $this->unit;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @return DateTimeInterface
     */
    public function getMeasuredDate(): DateTimeInterface
    {
        return $this->measuredDate;
    }

    /**
     * @return LocationEntity
     */
    public function getLocation(): LocationEntity
    {
        return $this->location;
    }
}
