<?php

namespace Modules\MasterBarang\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DataTables;

class MasterBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('masterbarang::index');
    }

    /**
     * Mengambil semua data dari database.
     */
    public function show(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(DB::select("CALL MasterBarang_select()"))->toJson();
        }

        return abort(404);
    }

    /**
     * Ambil data sales dari database
     */
    public function listSatuan(Request $request)
    {
        $search = $request->input('q');
        $satuan = DB::select("CALL MasterBarang_combo_satuan('$search')");

        $response = array();
        foreach ($satuan as $satuan) {
            $response[] = array(
                "id" => $satuan->id,
                "text" => $satuan->ket
            );
        }

        return response()->json($response);
    }

    /**
     * Ambil data sales dari database
     */
    public function listJenisBarang(Request $request)
    {
        $search = $request->input('q');
        $jenisBarang = DB::select("CALL MasterBarang_combo_jenis_barang('$search')");

        $response = array();
        foreach ($jenisBarang as $jenisBarang) {
            $response[] = array(
                "id" => $jenisBarang->id,
                "text" => $jenisBarang->ket
            );
        }

        return response()->json($response);
    }

    /**
     * Simpan data barang ke database.
     */
    function store(Request $request)
    {
        $users = Collect(DB::select("CALL MasterBarang_insert(?,?,?,?,?)", [
            $request->input('acc'),
            $request->input('ket'),
            (float)str_replace(',', '.', str_replace('.', '', $request->input('hst'))),
            $request->input('sat_id'),
            $request->input('jb_id')
        ]))->first();

        return response()->json($users);
    }

    /**
     * Mengambil data barang dari database by id.
     */

    public function edit($id)
    {
        $result = Collect(DB::select("CALL MasterBarang_select_one('$id')"))->first();

        return response()->json($result);
    }

      /**
     * Update data barang ke database.
     */
    function update(Request $request)
    {
        $users = Collect(DB::select("CALL MasterBarang_edit(?,?,?,?,?,?)", [
            $request->input('id'),
            $request->input('acc'),
            $request->input('ket'),
            (float)str_replace(',', '.', str_replace('.', '', $request->input('hst'))),
            $request->input('sat_id'),
            $request->input('jb_id')
        ]))->first();

        return response()->json($users);
    }

     /**
     * delete data by id.
     */
    public function destroy($id)
    {
        $result = Collect(DB::select("CALL MasterBarang_delete('$id')"))->first();

        return response()->json($result);
    }
}
