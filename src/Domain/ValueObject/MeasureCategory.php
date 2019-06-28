<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 28/06/19
 */

namespace App\Domain\ValueObject;

use MyCLabs\Enum\Enum;

/**
 * Class MeasureCategory
 * @package App\Domain\ValueObject
 *
 * @method static WEATHER(): MeasureCategory
 * @method static POLLUTION(): MeasureCategory
 * @method static POLLEN(): MeasureCategory
 */
class MeasureCategory extends Enum
{
    public const WEATHER = 'Weather';
    public const POLLUTION = 'Pollution';
    public const POLLEN = 'Pollen';
}
