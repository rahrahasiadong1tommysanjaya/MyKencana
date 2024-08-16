<?php

namespace Modules\MasterUsers\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Models\SetupMenu;
use Modules\MasterUsers\Models\MasterUsers;
use Modules\MasterUsers\Models\UserPermission;
use Modules\MasterUsers\Models\Permission;
use Modules\MasterMenu\Models\Menu;
use App\Models\User;
use DataTables;

class MasterUsersController extends Controller 
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Master Users';
        return view('masterusers::index', $data);
    }

    /**
     * Mengambil semua data dari database.
     */
    public function show(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterUsers::select(['id', 'username', 'name'])->get();
            return DataTables::of($data)->make(true);
        }

        return abort(404);
    }

    /**
     * Simpan data form input ke database.
     */
    public function store(Request $request)
    {
        // Create user
        $user = MasterUsers::create([
            'username' => $request->input('username'),
            'name' => $request->input('name'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json(['msg' => 'Berhasil Simpan', 'sts' => 0]);
    }

    /**
     * Mengambil data user dari database by id.
     */

    public function edit($userId)
    {
        $result = MasterUsers::select('id', 'username', 'name')
        ->where('id', $userId)
        ->first();

        return response()->json($result);
    }

    /**
     * Update data form input ke database.
    */
    public function update(Request $request)
    {
    $id = $request->input('id');
    $username = $request->input('username');
    $name = $request->input('name');
    $password = $request->input('password');

    // Check for duplicate username
    $CekDuplicate = MasterUsers::where('username', $username)
                            ->where('id', '<>', $id)
                            ->count();

    if ($CekDuplicate == 0) {
        $user = User::find($id);
        if ($user) {
            $user->username = $username;
            $user->name = $name;
            if (!empty($password)) {
                $user->password = Hash::make($password);
            }
            $user->updated_at = now();
            $user->save();

            return response()->json(['msg' => 'Berhasil Update', 'sts' => 0]);
        } else {
            return response()->json(['msg' => 'User not found', 'sts' => 1]);
        }
    } else {
        return response()->json(['msg' => 'Gagal (Username sudah terdaftar)', 'sts' => 1]);
    }
    }

    /**
     * hapus user
     */
    public function destroy($userId)
    {
        // Delete from the users table
        MasterUsers::where('id', $userId)->delete();

        // Delete from the smn table
        SetupMenu::where('user_id', $userId)->delete();

        // Delete from the users_permissions table
        UserPermission::where('user_id', $userId)->delete();

        // private untuk Sync permission cache spatie plugin 
        $this->syncPermissionUsers($userId);

        return response()->json(['msg' => 'Berhasil Hapus', 'sts' => 0]);
    }

    /**
     * Direct ke halaman menu berdasarkan user id
     */
    public function menu($id)
    {
        $user = MasterUsers::find($id);

        $data['title'] = 'List Menu User ' . $user->name;
        $data['user'] =  $user;

        return view('masterusers::pages.menu', $data);
    }

    /**
     * get data menu dari database
     */
    public function getListMenu($userId)
    {
        $menuUser = SetupMenu::select('setup_menu.id as id', 'menu.id as menu_id', 'menu.menu as text', 'menu.icon', 'menu.url', 'menu.slug', 'setup_menu.parent_id', 'setup_menu.urutan', 'setup_menu.user_id', 'users.name')
        ->join('menu', 'setup_menu.menu_id', '=', 'menu.id')
        ->join('users', 'setup_menu.user_id', '=', 'users.id')
        ->where('setup_menu.user_id', $userId) // Memfilter berdasarkan id user yang sedang login
        ->where('menu.status', 1) // Memfilter berdasarkan status
        ->orderBy('setup_menu.urutan')
        ->get();

        $resultMenu = $this->buildMenuUser($menuUser);

        return response()->json($resultMenu);
    }

    /**
     * Update urutan menu user by id
     */
    public function updateSortMenu(Request $request)
    {
        $userId = $request->input('user_id');
        $data = json_decode($request->input('serialized_data'), true);

        foreach ($data as $index => $item) {
            // mendapatkan id yang disini ada amn atau kode menu
            $id = $item['id'];
            $parent = ($item['parent'] == '#') ? NULL : $item['parent'];
            $sortNumber = $index + 1; // set urutan dari 1

            try {
                // Update database
                SetupMenu::where(['id' => $id, 'user_id' => $userId])
                    ->update(['parent_id' => $parent, 'urutan' => $sortNumber]);
            } catch (\Exception $e) {
                // Handle update gagal
                return response()->json(['msg' => 'Gagal update urutan menu', 'sts' => false]);
            }
        }

        // Create file json menus by user id
        $this->createAndStoreMenuJson($userId);

        return response()->json(['msg' => 'Berhasil update urutan menu...:)', 'sts' => true]);
    }

    /**
     * Ambil semua data permission by menu id
     */
    public function getListPermission($menuId)
    {
        $permissions = Permission::select('id', 'name')
        ->where('menu_id', $menuId)
        ->get();

        $response = array();
        foreach ($permissions as $permission) {
            $response[] = array(
                "id" => $permission->id,
                "text" => $permission->name
            );
        }

        return response()->json($response);
    }

    /**
     * get data edit permisson menu by user id
     */
    public function getListPermissionUser($menuId, $userId)
    {
        $results = UserPermission::select(
            'users_permissions.user_id',
            'users.name',
            'users_permissions.permission_id',
            'permissions.name',
            'permissions.menu_id'
        )
        ->leftJoin('permissions', 'users_permissions.permission_id', '=', 'permissions.id')
        ->leftJoin('users', 'users_permissions.user_id', '=', 'users.id')
        ->where('users_permissions.user_id', $userId)
        ->where('permissions.menu_id', $menuId)
        ->get();

        $permission = [];
        foreach ($results  as $val) {
            $permission[] = array(
                $val->permission_id,
                $val->name,
            );
        }

        return response()->json($permission);
    }

    /**
     * update permission menu user ke database berdasarkan user
     */
    public function updateMenuPermission(Request $request, $userId)
    {
        $permissions = $request->input('permissionMenuUser');
        $menuId = $request->input('menuId');
        
        // Validasi input
        $validator = Validator::make(
            [
                'user_id' => $userId,
                'menu_id' => $menuId
            ],
            [
                'user_id' => 'required|integer',
                'menu_id' => 'required|integer'
            ]
        );

        if ($validator->fails()) {
            // Tangani validasi gagal
            return response()->json(['error' => 'Invalid input'], 400);
        }

        try {
            // Get existing user permissions with menu_id
            $existingPermissions = UserPermission::join('permissions', 'users_permissions.permission_id', '=', 'permissions.id')
                ->where('users_permissions.user_id', $userId)
                ->where('permissions.menu_id', $menuId)
                ->pluck('permissions.id') // This should be a string field name
                ->toArray();

            // Permissions yang akan ditambahkan
            $permissionsToAdd = array_diff($permissions, $existingPermissions);

            // Permissions yang akan dihapus
            $permissionsToRemove = array_diff($existingPermissions, $permissions);

            // Add new permissions
            foreach ($permissionsToAdd as $permissionId) {
                UserPermission::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'permission_id' => $permissionId
                    ],
                    []
                );
            }

            // // Remove permissions
            UserPermission::join('permissions', 'users_permissions.permission_id', '=', 'permissions.id')
                ->where('users_permissions.user_id', $userId)
                ->where('permissions.menu_id', $menuId)
                ->whereIn('users_permissions.permission_id', $permissionsToRemove)
                ->delete();

            // // private untuk Sync permission cache spatie plugin 
            $this->syncPermissionUsers($userId);
            return response()->json([
                'status' => true,
                'message' => 'Berhasil Update Permissions'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Permissions',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Menghapus menu berdasarkan pengguna dan id menu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destrouMenuByUser($menuId, $userId)
    {
        // Panggil method dari model Setup Menu untuk menghapus menu by user
        $result = SetupMenu::destrouMenuByUser($userId, $menuId);

        // Create file json menus by user id
        $this->createAndStoreMenuJson($userId);

        // private function untuk Sync permission cache spatie plugin 
        $this->syncPermissionUsers($userId);

        // Response JSON sesuai dengan hasil penghapusan
        return response()->json($result);
    }

    /**
     * Ambil semua data menu by user id
     */
    public function getListmenuUser($user_id)
    {
        $menus = Menu::leftJoin('setup_menu', function ($join) use ($user_id)  {
            $join->on('menu.id', '=', 'setup_menu.menu_id')
            ->where('setup_menu.user_id', '=', $user_id);
        })
        ->whereNull('setup_menu.menu_id')
        ->select('menu.id', 'menu.menu')
        ->get();

        $response = array();
        foreach ($menus as $menu) {
            $response[] = array(
                "id" => $menu->id,
                "text" => $menu->menu
            );
        }

        return response()->json($response);
    }

    /**
     * simpan menu dan permission ke database berdasarkan user
     */
    public function storeMenuPermission(Request $request)
    {
       // Validate the request input
        $validator = Validator::make($request->all(), [
            'userId' => 'required|integer',
            // 'permission' => 'required|array',
            // 'permission.*' => 'integer', // Each item in the permission array must be an integer
        ]);

        if ($validator->fails()) {
            return response()->json([
                'msg' => 'Validation Failed',
                'sts' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        SetupMenu::create([
            'user_id' => $request->input('userId'),
            'menu_id' => $request->input('menu'),
            'created_at' => now()
        ]);

        // Insert permission by user id 
        $userId = $request->input('userId');
        $permission = $request->input('permission');

        if ($permission != null) {
            $data = [];

            foreach ($permission as $value) {
                $data[] = [
                    'user_id' =>  $userId,
                    'permission_id' => $value
                ];
            }

            UserPermission::insert($data);

            // private function untuk Sync permission cache spatie plugin 
            $this->syncPermissionUsers($userId);
        }

        return response()->json([
            'message' => 'Berhasil Simpan',
            'status' => true
        ]);
    }

    /**
     * Private function untuk build array layout menu
     */
    private function syncPermissionUsers($userId)
    {
        // Validasi input
        $validator = Validator::make(
            [
                'user_id' => $userId,
            ],
            [
                'user_id' => 'required|integer',
            ]
        );

        if ($validator->fails()) {
            // Tangani validasi gagal
            return response()->json(['error' => 'Invalid input'], 400);
        }

        //remove cache of permission and menu spatie
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = UserPermission::select(
            'users_permissions.user_id',
            'users.name as nma',
            'users_permissions.permission_id',
            'permissions.name',
            'permissions.menu_id'
        )
        ->leftJoin('permissions', 'users_permissions.permission_id', '=', 'permissions.id')
        ->leftJoin('users', 'users_permissions.user_id', '=', 'users.id')
        ->where('users_permissions.user_id', $userId)
        ->get();

        $permissionName = [];

        foreach ($permissions as $value) {
            $permissionName[] = $value->name;
        }

        $user = User::find($userId);

        if (!empty($permissionName)) {
            $user->syncPermissions($permissionName);
        }
    }

    /**
     * Private function untuk build array layout menu
     */
    private function buildMenuUser($menus, $parent_id = null)
    {
        $menuUser = [];

        foreach ($menus as $menu) {
            if ($menu->parent_id == $parent_id) {
                $menu->children = $this->buildMenuUser($menus, $menu->id);
                $menuUser[] = $menu;
            }
        }

        return $menuUser;
    }

    private function transformMenu($data, $isSubmenu = false)
    {
        $result = [];

        foreach ($data as $item) {
            $menuEntry = [];

            $menuEntry['id'] = $item->id;
            $menuEntry['menu'] = $item->menu; // Pastikan kolom 'text' ada di model Menu atau SetupMenu
            $menuEntry['slug'] = $item->slug; // Pastikan kolom 'slg' ada di model Menu atau SetupMenu
            $menuEntry['parent_id'] = $item->parent_id;

            // Set url to empty string if not present
            $menuEntry['url'] = isset($item->url) ? $item->url : '';

            // Exclude 'icon' in submenu
            if (!$isSubmenu && isset($item->icon)) {
                $menuEntry['icon'] = $item->icon;
            }

            // Check if the current item has children
            if (isset($item->children) && is_array($item->children)) {
                // Recursively call transformMenu for children (passing true for $isSubmenu)
                $submenu = $this->transformMenu($item->children, true);

                // Only add 'submenu' key if it's not empty
                if (!empty($submenu)) {
                    $menuEntry['submenu'] = $submenu;
                }
            }

            // Add the menu entry to the result
            $result[] = $menuEntry;
        }

        return $result;
    }

    private function createAndStoreMenuJson($userId)
    {
        // Fetch data from the database
        $setupMenus = SetupMenu::whereHas('user', function ($query) use ($userId) {
            $query->where('id', $userId);
        })
        ->with('menu')
        ->orderBy('urutan')
        ->get();

        // Extract menu items along with their parent_id from SetupMenu
        $menuItems = $setupMenus->map(function ($setupMenu) {
            $menu = $setupMenu->menu;
            $menu->parent_id = $setupMenu->parent_id; // Include parent_id from SetupMenu
            return $menu;
        });

        $menuStructure = $this->buildMenuUser($menuItems);
        $result = $this->transformMenu($menuStructure);
        $menuData = ['menu' => $result];

        // Convert the PHP array to a JSON string
        $jsonContent = json_encode([$menuData], JSON_PRETTY_PRINT);

        // Specify the path to the menu directory within the storage directory
        $menuDirectoryPath = base_path('resources/menu');

        // Check if the directory exists, if not, create it
        if (!File::exists($menuDirectoryPath)) {
            File::makeDirectory($menuDirectoryPath);
        }

        // Define the file path with the user ID
        $filePath = $menuDirectoryPath . "/menuUser_{$userId}.json";

        // Check if the file exists, if yes, delete it
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // Store the new JSON content into the file using Laravel's Storage facade
        File::put($filePath, $jsonContent);
    }
}
