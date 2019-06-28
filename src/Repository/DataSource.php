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
 * Class DataSource
 * @package App\Repository
 *
 * @property int data_source_id
 * @property string code
 * @property string name
 */
class DataSource extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'data_source';

    /**
     * @var string
     */
    protected $primaryKey = 'data_source_id';

    /**
     * @return HasMany
     */
    public function measures(): HasMany
    {
        return $this->hasMany(Measure::class, 'data_source_id', 'data_source_id');
    }
}
