<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 24/06/19
 */

namespace App\Domain\Entity;

use ArrayObject;
use InvalidArgumentException;

/**
 * Class MeasureCollection
 * @package App\Entity
 */
class MeasureCollection extends ArrayObject
{
    /**
     * Implementation of method declared in \ArrayAccess
     * Used for direct setting of values
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        if (!$this->validateValue($value)) {
            throw new InvalidArgumentException('Must be a MeasureEntity');
        }
        parent::offsetSet($offset, $value);
    }

    /**
     * @param MeasureCollection $collection
     * @return MeasureCollection
     */
    public function merge(MeasureCollection $collection): MeasureCollection
    {
        /** @var MeasureEntity $metric */
        foreach ($collection as $metric) {
            $this->append($metric);
        }
        return $this;
    }

    /**
     * @param $value
     * @return bool
     */
    protected function validateValue($value): bool
    {
        return $value instanceof MeasureEntity;
    }
}
