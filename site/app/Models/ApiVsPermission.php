<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ApiVsPermission
 * 
 * @property int $id
 * @property int $api_id
 * @property string $permission_key
 * 
 * @property ApiAccount $api_account
 * @property Permission $permission
 *
 * @package App\Models
 */
class ApiVsPermission extends Model
{
	protected $table = 'api_vs_permission';
	public $timestamps = false;

	protected $casts = [
		'api_id' => 'int'
	];

	protected $fillable = [
		'api_id',
		'permission_key'
	];

	public function api_account()
	{
		return $this->belongsTo(ApiAccount::class, 'api_id');
	}

	public function permission()
	{
		return $this->belongsTo(Permission::class, 'permission_key', 'permission_key');
	}
}
