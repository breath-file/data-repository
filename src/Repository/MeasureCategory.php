<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 28/06/19
 */

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MeasureCategory
 * @package App\Repository
 *
 * @property int measure_category_id
 * @property string code
 * @property string name
 */
class MeasureCategory extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'measure_category';

    /**
     * @var string
     */
    protected $primaryKey = 'measure_category_id';

}
