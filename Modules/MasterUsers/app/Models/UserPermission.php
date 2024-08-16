<?php

namespace Modules\MasterUsers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\MasterUsers\Database\Factories\UserPermissionFactory;

class UserPermission extends Model
{
    use HasFactory;

    protected $table = 'users_permissions';
    protected $primaryKey = ['user_id', 'permission_id'];
    protected $fillable = ['user_id', 'permission_id', 'created_at', 'updated_at'];
    protected $keyType = 'array';
    
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null; // Assuming you don't need an updated_at field

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    // Override getKeyName to return the primary keys
    public function getKeyName()
    {
        return $this->primaryKey;
    }

    // Override getIncrementing and getKeyType
    public function getIncrementing()
    {
        return $this->incrementing;
    }

    public function getKeyType()
    {
        return $this->keyType;
    }
}
