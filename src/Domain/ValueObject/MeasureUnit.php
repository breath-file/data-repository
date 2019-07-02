<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 01/07/19
 */

namespace App\Domain\ValueObject;

use MyCLabs\Enum\Enum;

/**
 * Class MeasureUnit
 * @package App\Domain\ValueObject
 *
 * @method static CELSIUS(): MeasureUnit
 * @method static HECTOPASCAL(): MeasureUnit
 * @method static INDEX(): MeasureUnit
 * @method static MICROGRAMS_M3(): MeasureUnit
 * @method static MILLIMETER(): MeasureUnit
 * @method static PERCENT(): MeasureUnit
 */
class MeasureUnit extends Enum
{
    public const CELSIUS       = 'C';
    public const HECTOPASCAL   = 'hPa';
    public const INDEX         = 'index';
    public const MICROGRAMS_M3 = 'ugm3';
    public const MILLIMETER    = 'mm';
    public const PERCENT       = 'percent';
}
