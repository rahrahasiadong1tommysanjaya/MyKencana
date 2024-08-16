<?php

namespace Modules\MasterUsers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\MasterUsers\Database\Factories\PermissionFactory;

class Permission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'permissions';
    protected $fillable = ['name', 'guard_name', 'menu_id'];
}
