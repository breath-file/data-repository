<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Location
 * @package App\Repository
 *
 * @property int location_id
 * @property string city
 * @property string country
 * @property float latitude
 * @property float longitude
 */
class Location extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'location';
}
