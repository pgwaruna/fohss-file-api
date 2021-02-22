<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class File
 * 
 * @property int $id
 * @property string $file_name
 * @property string $file_path
 * @property string $access_token
 * @property int $filetype_id
 * @property int $channel_id
 * @property int $active_status
 * @property int $delete_status
 * @property Carbon $created_at
 * 
 * @property FileType $file_type
 * @property Channel $channel
 *
 * @package App\Models
 */
class File extends Model
{
	protected $table = 'files';
	public $timestamps = false;

	protected $casts = [
		'filetype_id' => 'int',
		'channel_id' => 'int',
		'active_status' => 'int',
		'delete_status' => 'int'
	];

	protected $hidden = [
		'access_token'
	];

	protected $fillable = [
		'file_name',
		'file_path',
		'access_token',
		'filetype_id',
		'channel_id',
		'active_status',
		'delete_status'
	];

	public function file_type()
	{
		return $this->belongsTo(FileType::class, 'filetype_id');
	}

	public function channel()
	{
		return $this->belongsTo(Channel::class);
	}
}
