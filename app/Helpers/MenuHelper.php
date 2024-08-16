<?php

namespace App\Helpers;

class MenuHelper
{
    // Fungsi untuk memeriksa apakah menu aktif atau memiliki submenu yang aktif
    public static function isMenuActive($menu, $currentRouteName)
    {
        foreach ($menu as $submenu) {
            // Cek apakah slug dari submenu sama dengan bagian awal dari rute saat ini
            if (self::isRelatedRoute($currentRouteName, $submenu['slug'])) {
                return true;
            }
            // Cek apakah submenu memiliki submenus lain yang aktif
            if (isset($submenu['submenu']) && self::isMenuActive($submenu['submenu'], $currentRouteName)) {
                return true;
            }
        }
        return false;
    }

    // Fungsi untuk memeriksa apakah rute saat ini terkait dengan slug
    public static function isRelatedRoute($currentRouteName, $slug)
    {
        // Cek apakah slug adalah bagian dari rute saat ini
        return str_contains($currentRouteName, $slug);
    }

    // Fungsi untuk mendapatkan semua parent_id dari menu yang harus dibuka
    public static function getActiveParents($menu, $currentRouteName, &$activeParents = [])
    {
        foreach ($menu as $submenu) {
            // Jika submenu aktif, tambahkan id dari submenu tersebut ke array activeParents
            if (self::isMenuActive([$submenu], $currentRouteName)) {
                $activeParents[] = $submenu['id'];
                // Rekursif untuk mendapatkan parent dari submenu
                if (isset($submenu['submenu'])) {
                    self::getActiveParents($submenu['submenu'], $currentRouteName, $activeParents);
                }
            }
        }
        return $activeParents;
    }
}
