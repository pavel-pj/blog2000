<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class RoleHasPermission
 * 
 * @property int $permission_id
 * @property int $role_id
 * 
 * @property Permission $permission
 * @property Role $role
 *
 * @package App\Models
 */
class RoleHasPermission extends Model
{

	use HasUuids;

	protected $table = 'role_has_permissions';
	public $incrementing = false;
	public $timestamps = false;
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

//	protected $casts = [
//		'permission_id' => 'int',
//		'role_id' => 'int'
//	];

	public function permission()
	{
		return $this->belongsTo(Permission::class);
	}

	public function role()
	{
		return $this->belongsTo(Role::class);
	}

	  
    public static function booted()
    {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
	}	
}
 