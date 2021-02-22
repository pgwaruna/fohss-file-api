<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApiAccount
 * 
 * @property int $id
 * @property int $channel_id
 * @property int $user_id
 * @property string $system_name
 * @property string $apitoken
 * @property string $contact_mob
 * @property string $contact_email
 * @property Carbon $api_expire
 * @property int $active_status
 * @property int $created_by
 * @property Carbon $created_at
 * @property int $modified_by
 * @property Carbon $modified_at
 * 
 * @property Channel $channel
 * @property Collection|ApiSetting[] $api_settings
 * @property Collection|ApiVsPermission[] $api_vs_permissions
 *
 * @package App\Models
 */
class ApiAccount extends Model
{
	protected $table = 'api_accounts';
	public $timestamps = false;

	protected $casts = [
		'channel_id' => 'int',
		'user_id' => 'int',
		'active_status' => 'int',
		'created_by' => 'int',
		'modified_by' => 'int'
	];

	protected $dates = [
		'api_expire',
		'modified_at'
	];

	protected $hidden = [
		'apitoken'
	];

	protected $fillable = [
		'channel_id',
		'user_id',
		'system_name',
		'apitoken',
		'contact_mob',
		'contact_email',
		'api_expire',
		'active_status',
		'created_by',
		'modified_by',
		'modified_at'
	];

	public function channel()
	{
		return $this->belongsTo(Channel::class);
	}

	public function api_settings()
	{
		return $this->hasMany(ApiSetting::class, 'info_api_id');
	}

	public function api_vs_permissions()
	{
		return $this->hasMany(ApiVsPermission::class, 'api_id');
	}
}
