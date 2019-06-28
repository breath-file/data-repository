<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 28/06/19
 */

namespace App\Domain\ValueObject;

use MyCLabs\Enum\Enum;

/**
 * Class DataSource
 * @package App\Domain\ValueObject
 *
 * @method static OPEN_WEATHER_MAP(): DataSource
 * @method static BREEZOMETER(): DataSource
 */
class DataSource extends Enum
{
    public const OPEN_WEATHER_MAP = 'openweathermap';
    public const BREEZOMETER      = 'breezometer';
}
