<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 24/06/19
 */

namespace App\Domain\Entity;

use App\Domain\ValueObject\DataSource;
use App\Domain\ValueObject\MeasureCategory;
use App\Domain\ValueObject\MeasureMetric;
use App\Domain\ValueObject\MeasureUnit;
use DateTimeInterface;
use UnexpectedValueException;

/**
 * Class MeasureEntity
 * @package App\Entity
 */
class MeasureEntity
{

    /** @var DataSource */
    protected $dataSource;

    /** @var LocationEntity */
    protected $location;

    /** @var MeasureCategory */
    protected $category;

    /** @var MeasureMetric|string */
    protected $metric;

    /** @var MeasureUnit */
    protected $unit;

    /** @var float */
    protected $value;

    /** @var DateTimeInterface */
    protected $measured_date;

    /**
     * @return DataSource
     */
    public function getDataSource(): DataSource
    {
        return $this->dataSource;
    }

    /**
     * @param DataSource $dataSource
     * @return MeasureEntity
     */
    public function setDataSource(DataSource $dataSource): MeasureEntity
    {
        $this->dataSource = $dataSource;
        return $this;
    }

    /**
     * @return LocationEntity
     */
    public function getLocation(): LocationEntity
    {
        return $this->location;
    }

    /**
     * @param LocationEntity $location
     * @return MeasureEntity
     */
    public function setLocation(LocationEntity $location): MeasureEntity
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return MeasureCategory
     */
    public function getCategory(): MeasureCategory
    {
        return $this->category;
    }

    /**
     * @param MeasureCategory $category
     * @return MeasureEntity
     */
    public function setCategory(MeasureCategory $category): MeasureEntity
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return MeasureMetric|string
     */
    public function getMetric()
    {
        return $this->metric;
    }

    /**
     * @param MeasureMetric|string $metric
     * @return MeasureEntity
     */
    public function setMetric($metric): MeasureEntity
    {
        if (! (is_string($metric) || $metric instanceof MeasureMetric) ) {
            throw new UnexpectedValueException('Invalid type for Metric parameter');
        }
        $this->metric = $metric;
        return $this;
    }

    /**
     * @return MeasureUnit
     */
    public function getUnit(): MeasureUnit
    {
        return $this->unit;
    }

    /**
     * @param MeasureUnit $unit
     * @return MeasureEntity
     */
    public function setUnit(MeasureUnit $unit): MeasureEntity
    {
        $this->unit = $unit;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return implode('_', array_filter([
            (string) $this->dataSource,
            (string) $this->category,
            (string) $this->metric,
            (string) $this->unit
        ]));
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param float $value
     * @return MeasureEntity
     */
    public function setValue(float $value): MeasureEntity
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getMeasuredDate(): DateTimeInterface
    {
        return $this->measured_date;
    }

    /**
     * @param DateTimeInterface $measured_date
     * @return MeasureEntity
     */
    public function setMeasuredDate(DateTimeInterface $measured_date): MeasureEntity
    {
        $this->measured_date = $measured_date;
        return $this;
    }
}
