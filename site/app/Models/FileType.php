<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FileType
 * 
 * @property int $id
 * @property string $mime_type
 * @property string|null $type_image
 * 
 * @property Collection|File[] $files
 *
 * @package App\Models
 */
class FileType extends Model
{
	protected $table = 'file_types';
	public $timestamps = false;

	protected $fillable = [
		'mime_type',
		'type_image'
	];

	public function files()
	{
		return $this->hasMany(File::class, 'filetype_id');
	}
}
