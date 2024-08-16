<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\BackupDatabase\Models\BackupDatabase;

class RestoreDatabase extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageConfigs = ['myLayout' => 'blank'];
        return view('content.restore-database.index', ['pageConfigs' => $pageConfigs]);
    }

    public function restoreDatabase(Request $request)
    {
        $backupPath = $request->file('file')->storeAs('backups', 'restore.sql');

        try {
            // Periksa apakah database sudah ada
            $databaseName = 'ksatria';
            $exists = $this->checkDatabaseExists($databaseName);

            if (!$exists) {
                // Buat database jika belum ada
                $this->createDatabase($databaseName);
            }

            // Lakukan restore database menggunakan exec
            $this->restoreUsingExec($backupPath, $databaseName);

            return response()->json(['status' => 'success', 'message' => 'Database berhasil direstore...']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Periksa apakah database sudah ada.
     *
     * @param string $databaseName
     * @return bool
     */
    private function checkDatabaseExists($databaseName)
    {
        try {
            $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?";
            $result = DB::select($query, [$databaseName]);
            return count($result) > 0;
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, asumsikan database tidak ada
            return false;
        }
    }

    /**
     * Buat database baru.
     *
     * @param string $databaseName
     * @return void
     */
    private function createDatabase($databaseName)
    {
        $charset = config('database.connections.mysql.charset', 'utf8mb4');
        $collation = config('database.connections.mysql.collation', 'utf8mb4_unicode_ci');

        // Buat koneksi PDO ke MySQL tanpa menghubungkan ke database tertentu
        $host = env('DB_HOST', '127.0.0.1');
        $username = env('DB_USERNAME', 'root');
        $password = env('DB_PASSWORD', '');

        try {
            $pdo = new \PDO("mysql:host=$host", $username, $password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Jalankan perintah CREATE DATABASE
            $pdo->exec("CREATE DATABASE `$databaseName` CHARACTER SET $charset COLLATE $collation");
        } catch (\PDOException $e) {
            throw new \Exception("Database creation failed: " . $e->getMessage());
        }
    }

    /**
     * Lakukan restore database menggunakan exec.
     *
     * @param string $backupPath
     * @param string $databaseName
     * @return void
     */
    private function restoreUsingExec($backupPath, $databaseName)
    {
        // Baca isi file backup
        $backupContent = file_get_contents(storage_path('app/' . $backupPath));

        // Buat koneksi PDO untuk database yang akan direstore
        $host = env('DB_HOST', '127.0.0.1');
        $username = env('DB_USERNAME', 'root');
        $password = env('DB_PASSWORD', '');

        try {
            $pdo = new \PDO("mysql:host=$host;dbname=$databaseName", $username, $password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Jalankan perintah SQL untuk restore
            $pdo->exec($backupContent);
        } catch (\PDOException $e) {
            throw new \Exception("Database restore failed: " . $e->getMessage());
        }
    }
}
