<?php

namespace Modules\MasterCusSup\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DataTables;

class MasterCusSupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('mastercussup::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mastercussup::create');
    }

     /**
     * Mengambil semua data dari database.
     */
    public function show(Request $request)
    {
        if ($request->ajax()) {
            $filter = $request->input('filter', 'all');
    
            $data = DB::select("CALL MasterCusSup_select()");
    
            if ($filter !== 'all') {
                $data = array_filter($data, function ($item) use ($filter) {
                    return $item->jns === $filter;  // Assuming 'jns' is the type field
                });
            }
    
            return DataTables::of(collect($data))->toJson();
        }
    
        return abort(404);
    }

    /**
     * Ambil semua data kelurahan 
     */
    public function listKelurahan(Request $request)
    {
        $q = ($request->input('q') == null) ? '' : $request->input('q');
        $limit = 20;

        // Query dengan parameter search dan limit
        $kelurahan = DB::select("CALL MasterCusSup_combo_kelurahan(:search, :limit)", [
            'search' => $q,
            'limit' => $limit
        ]);

        $response = array();
        foreach ($kelurahan as $kelurahan) {
            $response[] = array(
                "id" => $kelurahan->id,
                "text" => $kelurahan->ket,
                "kel" => $kelurahan->kel,
                "kec" => $kelurahan->kec,
                "kab_kota" => $kelurahan->kab_kota,
                "prov" => $kelurahan->prov
            );
        }

        return response()->json($response);
    }

    /**
     * Simpan data form input ke database.
     */
    public function store(Request $request)
    {
        $result = Collect(DB::select("CALL MasterCusSup_insert(?,?,?,?,?)", [
            $request->input('acc'),
            $request->input('ket'),
            $request->input('alm'),
            $request->input('kel'),
            $request->input('jns')
        ]))->first();

        return response()->json($result);
    }

    /**
     * Ambil data berdasarkan id.
     */
    public function edit($id)
    {
        $result = Collect(DB::select("CALL MasterCusSup_select_one(?)", [
            $id
        ]))->first();

        return response()->json($result);
    }

    /**
     * Update data barang ke database.
     */
    function update(Request $request)
    {
        $users = Collect(DB::select("CALL MasterCusSup_edit(?,?,?,?,?,?)", [
            $request->input('id'),
            $request->input('acc'),
            $request->input('ket'),
            $request->input('alm'),
            $request->input('kel'),
            $request->input('jns')
        ]))->first();

        return response()->json($users);
    }

    /**
     * delete data by id.
     */
    public function destroy($id)
    {
        $result = Collect(DB::select("CALL MasterCusSup_delete('$id')"))->first();

        return response()->json($result);
    }
}
