<?php

namespace Modules\BackupDatabase\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\BackupDatabase\Models\BackupDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use DataTables;

class BackupDatabaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backupdatabase::index');
    }

    /**
     * show data history backup database from database.
     */
    public function show(Request $request)
    {
        if ($request->ajax()) {
            $data = BackupDatabase::select(['name', 'date', 'location'])->get();
            return DataTables::of($data)->make(true);
        }

        return abort(404);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function backup()
    {
        try {
           // Mendapatkan informasi untuk backup
            $backupName = 'ksatria_' . now()->format('Y-m-d H:i:s');
            
            // Mendapatkan daftar disks dari konfigurasi backup
            $disks = Config::get('backup.backup.destination.disks',[]);

            // Cek apakah ada disks yang tersedia
            if (empty($disks)) {
                throw new \Exception('No backup disks configured.');
            }

            // Ambil disk pertama dari daftar disks
            $diskName = $disks[0];

            // Mendapatkan root path dari disk
            $diskRoot = Config::get("filesystems.disks.$diskName.root");

            // Pastikan root path tidak null
            if (is_null($diskRoot)) {
                throw new \Exception("Root path for disk '$diskName' is not configured.");
            }

            // Sesuaikan dengan lokasi backup yang sesuai
            $backupPath = $diskRoot . '/' . $backupName . '.zip';

    
            // Jalankan perintah backup database menggunakan Artisan
            Artisan::call('backup:run', ['--only-db' => true]);
    
            $output = Artisan::output();

            // Simpan informasi backup ke database (opsional)
            BackupDatabase::create([
                'name' => $backupName,
                'date' => now(),
                'location' => $backupPath,
            ]);
    
            return response()->json(['status' => 'success', 'message' => 'Berhasil Backup :)']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
