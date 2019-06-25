<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 24/06/19
 */

namespace App\Formatter;

use App\Domain\Entity\MeasureEntity;

/**
 * Interface FormatterInterface
 * @package App\Formatter
 */
interface FormatterInterface
{
    public function format(MeasureEntity $metric): string;
}
