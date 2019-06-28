<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 24/06/19
 */

namespace App\Domain\Entity;

use App\Domain\ValueObject\DataSource;
use App\Domain\ValueObject\MeasureCategory;
use DateTimeInterface;

/**
 * Class MeasureEntity
 * @package App\Entity
 */
class MeasureEntity
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $labels = [];

    /**
     * @var float|null
     */
    protected $value;

    /**
     * @var LocationEntity
     */
    protected $location;

    /**
     * @var DateTimeInterface
     */
    protected $datetimeUtc;

    /**
     * @var DataSource
     */
    protected $dataSource;

    /**
     * @var MeasureCategory
     */
    protected $category;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return MeasureEntity
     */
    public function setName(string $name): MeasureEntity
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    /**
     * @param array $labels
     * @return MeasureEntity
     */
    public function setLabels(array $labels): MeasureEntity
    {
        $this->labels = $labels;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getValue(): ?float
    {
        return $this->value;
    }

    /**
     * @param float|null $value
     * @return MeasureEntity
     */
    public function setValue(?float $value): MeasureEntity
    {
        $this->value = $value;
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
     * @param LocationEntity|null $location
     * @return MeasureEntity
     */
    public function setLocation(?LocationEntity $location): MeasureEntity
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getDatetimeUtc(): DateTimeInterface
    {
        return $this->datetimeUtc;
    }

    /**
     * @param DateTimeInterface $datetimeUtc
     * @return MeasureEntity
     */
    public function setDatetimeUtc(DateTimeInterface $datetimeUtc): MeasureEntity
    {
        $this->datetimeUtc = $datetimeUtc;
        return $this;
    }

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
}
