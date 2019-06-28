<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 28/06/19
 */

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class TaskHistoryRepository
 * @package App\Repository
 */
class TaskHistoryRepository
{
    /**
     * @param Task $task
     * @return TaskHistory|Model|HasMany|object|null
     */
    public function lastTaskSuccess(Task $task)
    {
        return $task->history()
            ->where('exit_code', '=', 0)
            ->orderBy('started_at', 'desc')
            ->first();
    }
}
