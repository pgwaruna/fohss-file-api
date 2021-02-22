<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApiSetting
 * 
 * @property int $id
 * @property int $info_api_id
 * @property string $setting_key
 * @property string $setting_value
 * @property string|null $description
 * @property int $show_front
 * @property string $modified_by
 * @property Carbon $modified_at
 * 
 * @property ApiAccount $api_account
 *
 * @package App\Models
 */
class ApiSetting extends Model
{
	protected $table = 'api_settings';
	public $timestamps = false;

	protected $casts = [
		'info_api_id' => 'int',
		'show_front' => 'int'
	];

	protected $dates = [
		'modified_at'
	];

	protected $fillable = [
		'info_api_id',
		'setting_key',
		'setting_value',
		'description',
		'show_front',
		'modified_by',
		'modified_at'
	];

	public function api_account()
	{
		return $this->belongsTo(ApiAccount::class, 'info_api_id');
	}
}
