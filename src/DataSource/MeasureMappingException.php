<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 01/07/19
 */

namespace App\DataSource;

use RuntimeException;

/**
 * Class RuntimeException
 * @package App\DataSource
 */
class MeasureMappingException extends RuntimeException implements ExceptionInterface
{

}
