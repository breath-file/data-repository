<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

namespace App\Task;

use App\DataSource\Breezometer;
use App\Domain\Command\LoadMetricsCommand;

/**
 * Class ImportBreezometerTask
 * @package App\Task
 */
class ImportBreezometerTask extends TaskAbstract
{
    /**
     * @param array $args
     * @return string
     */
    public function command(array $args): string
    {
        $result = $this->dispatchCommand(new LoadMetricsCommand(new Breezometer()));
        return $result->isSuccess() ? 'Success' : 'Error';
    }
}
