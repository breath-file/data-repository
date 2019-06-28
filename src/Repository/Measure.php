<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

namespace App\Repository;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Measure
 * @package App\Repository
 *
 * @property int measure_id
 * @property int location_id
 * @property int data_source_id
 * @property int measure_category_id
 * @property string name
 * @property float value
 * @property Carbon measured_at
 */
class Measure extends Model
{
    /**
     * @var string
     */
    protected $table = 'measure';

    /**
     * @var string
     */
    protected $primaryKey = 'measure_id';

    /**
     * @return array
     */
    public function getDates(): array
    {
        return ['measured_at', 'created_at', 'updated_at'];
    }

    /**
     * @return BelongsTo
     */
    public function dataSource(): BelongsTo
    {
        return $this->belongsTo(DataSource::class, 'data_source_id', 'data_source_id');
    }

    /**
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id', 'location_id');
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(MeasureCategory::class, 'measure_category_id', 'measure_category_id');
    }
}
