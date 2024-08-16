<?php

namespace Modules\BackupDatabase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\BackupDatabase\Database\Factories\BackupDatabaseFactory;

class BackupDatabase extends Model
{
    use HasFactory;

    protected $table = 'backup_database'; // Sesuaikan dengan nama tabel Anda

    protected $fillable = [
        'name',
        'date',
        'location',
        'created_at',
    ];
}
