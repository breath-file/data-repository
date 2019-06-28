<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 28/06/19
 */

namespace App\Repository;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TaskHistory
 * @package App\Repository
 *
 * @property int task_history_id
 * @property int task_id
 * @property Carbon started_at
 * @property Carbon ended_at
 * @property int exit_code
 * @property string comment
 */
class TaskHistory extends Model
{
    /**
     * @var string
     */
    protected $table = 'task_history';

    /**
     * @var string
     */
    protected $primaryKey = 'task_history_id';

    /**
     * Disable auto timestamps (created_at, updated_at)
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return BelongsTo
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'task_id');
    }

    /**
     * @return array
     */
    public function getDates(): array
    {
        return ['started_at', 'ended_at'];
    }
}
