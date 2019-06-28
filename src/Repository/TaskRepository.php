<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 28/06/19
 */

namespace App\Repository;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class TaskRepository
 * @package App\Repository
 */
class TaskRepository
{
    /**
     * @return Task[]|Collection
     */
    public function getAll(): Collection
    {
        return Task::all();
    }
}
