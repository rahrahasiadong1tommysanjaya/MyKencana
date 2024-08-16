<?php

namespace Modules\MasterUsers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\MasterUsers\Database\Factories\MasterUsersFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MasterUsers extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $guard_name = 'web';
    protected $fillable = ['username', 'name', 'password', 'created_at'. 'updated_at'];

    // Tentukan atribut yang akan dicatat
    protected static $logAttributes = ['username', 'name'];

    // Nama log yang akan digunakan
    protected static $logName = 'MasterUsers';

    // Menentukan apakah semua perubahan atribut akan dicatat atau hanya yang ditentukan di $logAttributes
    protected static $logOnlyDirty = true;

    /**
     * Mendapatkan opsi log aktivitas untuk model ini.
     *
     * @return \Spatie\Activitylog\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['username', 'name'])
            ->useLogName('MasterUsers')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Master users {$eventName}");
    }

}
