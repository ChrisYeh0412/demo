<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstellationDetail extends Model
{
    use SoftDeletes;

	protected $table = 'constellations_details';
	protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
		'constellation_id', 'type', 'name', 'contents', 'date', 'created_user', 'updated_user', 'deleted_user',
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
