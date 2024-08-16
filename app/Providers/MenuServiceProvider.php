<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use App\Models\SetupMenu;

class MenuServiceProvider extends ServiceProvider
{
/**
 * Register services.
 */
public function register(): void
{
//
}

/**
 * Bootstrap services.
 */
public function boot(): void
{
    View::composer('*', function ($view) {
        if (Auth::check()) {
            $userId = Auth::id();
            $jsonFilePath = base_path('resources/menu/menuUser_' . $userId . '.json');

            // Cek apakah file JSON sudah ada
            if (file_exists($jsonFilePath)) {
                // Muat data dari file JSON jika ada
                $menuData = json_decode(file_get_contents($jsonFilePath), true);
            } else {
                // Fetch menu items using model SetupMenu
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

                $menuStructure = $this->buildMenuTree($menuItems);
                $verticalResult = $this->transformMenu($menuStructure);

                $verticalMenuData = ['menu' => $verticalResult];

                // Menyimpan data ke file JSON
                $menuData = [
                    $verticalMenuData
                ];

                file_put_contents($jsonFilePath, json_encode($menuData));
            }

            // dd($menuData);
            // Bagikan data menu ke semua tampilan
            $this->app->make('view')->share('menuData', $menuData);
        }
    });
}

// Fungsi untuk transformasi menu
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

// Fungsi untuk membangun pohon menu
private function buildMenuTree($menus, $parent_id = null)
{
    $menuTree = [];

    foreach ($menus as $menu) {
        if ($menu->parent_id == $parent_id) {
            $menu->children = $this->buildMenuTree($menus, $menu->id);
            $menuTree[] = $menu;
        }
    }

    return $menuTree;
}
}
