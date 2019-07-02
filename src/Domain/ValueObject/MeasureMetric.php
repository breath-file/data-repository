<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 01/07/19
 */

namespace App\Domain\ValueObject;

use MyCLabs\Enum\Enum;

/**
 * Class MeasureMetric
 * @package App\Domain\ValueObject
 *
 * @method static CARBON_MONOXIDE(): MeasureMetric
 * @method static HUMIDITY(): MeasureMetric
 * @method static NITROGEN_DIOXIDE(): MeasureMetric
 * @method static OZONE(): MeasureMetric
 * @method static PM10_PARTICLES(): MeasureMetric
 * @method static PM2_5_PARTICLES(): MeasureMetric
 * @method static POLLEN_GRAMINALES(): MeasureMetric
 * @method static POLLEN_GRASS(): MeasureMetric
 * @method static POLLEN_RAGWEED(): MeasureMetric
 * @method static POLLEN_TREE(): MeasureMetric
 * @method static POLLEN_WEED(): MeasureMetric
 * @method static PRESSURE(): MeasureMetric
 * @method static SULPHUR_DIOXIDE(): MeasureMetric
 * @method static TEMPERATURE(): MeasureMetric
 */
class MeasureMetric extends Enum
{
    public const CARBON_MONOXIDE  = 'CO';
    public const HUMIDITY         = 'Humidity';
    public const NITROGEN_DIOXIDE = 'NO2';
    public const OZONE            = 'O3';
    public const PM10_PARTICLES   = 'PM10';
    public const PM2_5_PARTICLES  = 'PM2.5';
    public const POLLEN_GRAMINALES = 'Plant_Graminales';
    public const POLLEN_GRASS     = 'Type_Grass';
    public const POLLEN_RAGWEED   = 'Plant_Ragweed';
    public const POLLEN_TREE      = 'Type_Tree';
    public const POLLEN_WEED      = 'Type_Weed';
    public const PRECIPITATION    = 'Precipitation';
    public const PRESSURE         = 'Pressure';
    public const SULPHUR_DIOXIDE  = 'SO2';
    public const TEMPERATURE      = 'Temperature';
}
