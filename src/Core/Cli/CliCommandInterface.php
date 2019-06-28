<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 28/06/19
 */

namespace App\Core\Cli;
/**
 * Interface CliCommandInterface
 * @package App\Core\Cli
 */
interface CliCommandInterface
{
    /**
     * @param array $args
     * @return string
     */
    public function command(array $args): string;
}
