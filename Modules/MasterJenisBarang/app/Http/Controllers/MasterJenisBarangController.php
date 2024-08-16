<?php

namespace Modules\MasterJenisBarang\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DataTables;

class MasterJenisBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('masterjenisbarang::index');
    }

    /**
     * Mengambil semua data dari database.
     */
    public function show(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(DB::select("CALL MasterJenisBarang_select()"))->toJson();
        }

        return abort(404);
    }

    /**
     * menyimpan data dari form ke database.
     */
    public function store(Request $request)
    {
        $result = Collect(DB::select("CALL MasterJenisBarang_insert(?,?)", [
            $request->input('acc'),
            $request->input('ket')
        ]))->first();

        return response()->json($result);
    }

    /**
     * update data dari form ke database.
     */
    public function update(Request $request)
    {
        $result = Collect(DB::select("CALL MasterJenisBarang_edit(?,?,?)", [
            $request->input('id'),
            $request->input('acc'),
            $request->input('ket')
        ]))->first();

        return response()->json($result);
    }

    /**
     * destroy data dari form ke database.
     */
    public function destroy($id)
    {
        $result = Collect(DB::select("CALL MasterJenisBarang_delete(?)", [
            $id
        ]))->first();

        return response()->json($result);
    }
}
