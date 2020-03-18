<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Constellation extends Model
{
    use SoftDeletes;

	protected $table = 'constellations';
	protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
		'name', 'created_user', 'updated_user', 'deleted_user',
	];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
	protected $hidden = [
		'created_user', 'updated_user', 'deleted_user',
	];
}
