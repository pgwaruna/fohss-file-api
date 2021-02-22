<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Channel
 * 
 * @property int $id
 * @property string $channel_name
 * 
 * @property Collection|ApiAccount[] $api_accounts
 * @property Collection|File[] $files
 *
 * @package App\Models
 */
class Channel extends Model
{
	protected $table = 'channels';
	public $timestamps = false;

	protected $fillable = [
		'channel_name'
	];

	public function api_accounts()
	{
		return $this->hasMany(ApiAccount::class);
	}

	public function files()
	{
		return $this->hasMany(File::class);
	}
}
