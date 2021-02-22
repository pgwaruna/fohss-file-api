<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApiActivity
 * 
 * @property int $id
 * @property int|null $uidx
 * @property string|null $user_name
 * @property string $type
 * @property string $activity
 * @property string $url
 * @property string $controller_name
 * @property string $action_name
 * @property string $parameters
 * @property int $status
 * @property string $ip_addr
 * @property Carbon $date_time
 * @property string|null $imi
 * @property string|null $dev_name
 * @property string|null $ua_browser
 * @property string|null $latitude
 * @property string|null $longitude
 * @property Carbon $modified_at
 *
 * @package App\Models
 */
class ApiActivity extends Model
{
	protected $table = 'api_activities';
	public $timestamps = false;

	protected $casts = [
		'uidx' => 'int',
		'status' => 'int'
	];

	protected $dates = [
		'date_time',
		'modified_at'
	];

	protected $fillable = [
		'uidx',
		'user_name',
		'type',
		'activity',
		'url',
		'controller_name',
		'action_name',
		'parameters',
		'status',
		'ip_addr',
		'date_time',
		'imi',
		'dev_name',
		'ua_browser',
		'latitude',
		'longitude',
		'modified_at'
	];
}
