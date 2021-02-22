<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 * 
 * @property int $id
 * @property string $permission_key
 * 
 * @property Collection|ApiVsPermission[] $api_vs_permissions
 *
 * @package App\Models
 */
class Permission extends Model
{
	protected $table = 'permission';
	public $timestamps = false;

	protected $fillable = [
		'permission_key'
	];

	public function api_vs_permissions()
	{
		return $this->hasMany(ApiVsPermission::class, 'permission_key', 'permission_key');
	}
}
