<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\SetupMenu;

class SetupMenu extends Model
{
    use HasFactory;

    protected $table = 'setup_menu';
    protected $primaryKey = 'id';
    protected $guard_name = 'web';
    
    protected $fillable = [
        'user_id', 'menu_id', 'parent_id', 'urutan'
        // Sesuaikan dengan kolom yang ingin diisi secara massal
    ];
    
    public $timestamps = true;
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null; // Assuming you don't need an updated_at field

    /**
     * Metode untuk menghapus menu berdasarkan pengguna dan id menu.
     *
     * @param int $userId
     * @param int $menuId
     * @return array
     */
    public static function destrouMenuByUser($userId, $menuId)
    {
        DB::beginTransaction();

        try {
            // Cek apakah menu terkait pengguna tidak ada
            $countMenus = SetupMenu::where('parent_id', $menuId)
                                ->where('user_id', $userId)
                                ->count();

            if ($countMenus === 0) {
                // Hapus menu dan permissions terkait
                SetupMenu::where('menu_id', $menuId)
                    ->where('user_id', $userId)
                    ->delete();

                DB::table('users_permissions')
                    ->leftJoin('permissions', 'users_permissions.permission_id', '=', 'permissions.id')
                    ->where('permissions.menu_id', $menuId)
                    ->where('users_permissions.user_id', $userId)
                    ->delete();

                DB::commit();

                return ['message' => 'Berhasil Hapus', 'status' => true];
            } else {
                // Jika menu terkait pengguna masih ada, hapus semua yang terkait
                $parentMenuId = SetupMenu::where('id', $menuId)
                                ->value('menu_ud');

                SetupMenu::where('menu_id', $menuId)
                    ->where('user_id', $userId)
                    ->delete();

                SetupMenu::where('parent_id', $parentMenuId)
                    ->where('user_id', $userId)
                    ->delete();

                DB::table('users_permissions')
                    ->leftJoin('permissions', 'users_permissions.permission_id', '=', 'permissions.id')
                    ->where('permissions.menu_id', $menuId)
                    ->where('users_permissions.user_id', $userId)
                    ->delete();

                DB::commit();

                return ['message' => 'Berhasil Hapus', 'status' => true];
            }
        } catch (\Exception $e) {
            DB::rollback();
            // Handle error, log error, throw exception, etc.
            return ['message' => $e, 'status' => false];
        }
    }

    // Relasi dengan tabel User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi dengan tabel Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
