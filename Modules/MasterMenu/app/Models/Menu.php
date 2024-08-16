<?php

namespace Modules\MasterMenu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\MasterMenu\Database\Factories\MenuFactory;

class Menu extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'menu';
    protected $primaryKey = 'id';
    protected $guard_name = 'web';
}
