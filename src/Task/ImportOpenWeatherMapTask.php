<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

namespace App\Task;

use App\DataSource\OpenWeatherMap;
use App\Domain\Command\LoadMetricsCommand;

/**
 * Class ImportOpenWeatherMapTask
 * @package App\Task
 */
class ImportOpenWeatherMapTask extends TaskAbstract
{
    /**
     * @param array $args
     * @return string
     */
    public function command(array $args): string
    {
        $result = $this->dispatchCommand(new LoadMetricsCommand(new OpenWeatherMap()));
        return $result->isSuccess() ? 'Success' : 'Error';
    }
}
