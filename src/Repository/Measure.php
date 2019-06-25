<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

namespace App\Repository;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Measure
 * @package App\Repository
 *
 * @property int measure_id
 * @property int location_id
 * @property string name
 * @property float value
 * @property Carbon measured_at_utc
 */
class Measure extends Model
{
    /**
     * @var string
     */
    protected $table = 'measure';

    /**
     * @return array
     */
    public function getDates(): array
    {
        return ['measured_at_utc', 'created_at', 'updated_at'];
    }
}
