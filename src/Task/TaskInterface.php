<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

namespace App\Task;
/**
 * Interface TaskInterface
 * @package App\Task
 */
interface TaskInterface
{
    /**
     * @param array $args
     * @return string
     */
    public function command(array $args): string;
}
