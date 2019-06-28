<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 28/06/19
 */

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Task
 * @package App\Repository
 *
 * @property int task_id
 * @property string command
 * @property string description
 * @property string schedule
 */
class Task extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'task';

    /**
     * @var string
     */
    protected $primaryKey = 'task_id';

    /**
     * Disable auto timestamps (created_at, updated_at)
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(TaskHistory::class, 'task_id', 'task_id');
    }
}
