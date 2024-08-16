<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('order::index');
    }

    /**
     * Mengambil semua data dari database.
     */
    public function show(Request $request)
    {
        if ($request->ajax()) {
            $columns = ['buk', 'tgl', 'tjt','nar', 'jrp'];

            $searchValue = ($request->input('search.value', '') == NULL) ? '' : $request->input('search.value', '');
            $startIdx = $request->input('start', 0);
            $limitRows = $request->input('length', 10);
            $sortOrder  = $columns[$request->input('order.0.column')];
            $sortDir = $request->input('order.0.dir', 'asc');

            $result = DB::select('CALL Order_header_select(?, ?, ?, ?, ?, ?, @totalData, @totalFiltered)', [
                $request->input('tgl'), $searchValue, $startIdx, $limitRows, $sortOrder, $sortDir
            ]);


            $totalData = DB::select('SELECT @totalData as totalData')[0]->totalData;
            $totalFiltered = DB::select('SELECT @totalFiltered as totalFiltered')[0]->totalFiltered;

            // Process $result and return the response
            $data = [];

            if (!empty($result)) {
                foreach ($result as $group) {
                    $nestedData['id'] = $group->id;
                    $nestedData['buk'] = $group->buk;
                    $nestedData['tgl'] = $group->tgl;
                    $nestedData['tjt'] = $group->tjt;
                    $nestedData['nar'] = $group->nar;
                    $nestedData['jrp'] = $group->jrp;

                    $data[] = $nestedData;
                }
            }

            // Return JSON response
            return response()->json([
                'success' => true,
                'data' => $data,
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'draw' => intval($request->input('draw')),
            ]);
        }
    }

    /**
     * Ambil data customers dari database
     */
    public function listCustomers(Request $request)
    {
        $search = $request->input('q');
        $customers = DB::select("CALL Order_combo_customer('$search')");

        $response = array();
        foreach ($customers as $customers) {
            $response[] = array(
                "id" => $customers->id,
                "text" => $customers->ket
            );
        }

        return response()->json($response);
    }

    /**
     * Mengambil data order dari database by uuid.
     */

    public function edit($uuid)
    {
        $result = Collect(DB::select("CALL Order_header_select_one('$uuid')"))->first();

        if (!$result) {
            return view('pages.pages-misc-error');
        }

        $data['result'] = $result;

        return view('order::pages.edit', $data);
    }

    /**
     * Simpan data header ke database.
     */
    function store(Request $request)
    {
        $result = Collect(DB::select("CALL Order_header_insert(?,?,?,?)", [
            Carbon::parse($request->input('tgl'))->format('Y-m-d'),
            $request->input('ar_id'),
            Carbon::parse($request->input('tjt'))->format('Y-m-d'),
            $request->input('ctt')
        ]))->first();

        return response()->json($result);
    }

    /**
     * Update data detail barang berdasarkan id.
     */
    function update(Request $request)
    {
        $result = Collect(DB::select("CALL Order_header_edit(?,?,?,?,?)", [
            $request->input('id'),
            Carbon::parse($request->input('tgl'))->format('Y-m-d'),
            $request->input('ar_id'),
            Carbon::parse($request->input('tjt'))->format('Y-m-d'),
            $request->input('ctt')
        ]))->first();

        return response()->json($result);
    }

    /**
     * destroy order dan semua barang berdasarkan id.
     */
    public function destroy($id)
    {
        $result = Collect(DB::select("CALL Order_header_delete(?)", [
            $id
        ]))->first();

        return response()->json($result);
    }

    /**
     * Mengambil data detail berdasarkan ok_id.
     */
    public function showDetail(Request $request)
    {
        if ($request->ajax()) {
            $columns = ['niv', 'tgk', 'qti','hst', 'jrp'];

            $searchValue = ($request->input('search.value', '') == NULL) ? '' : $request->input('search.value', '');
            $startIdx = $request->input('start', 0);
            $limitRows = $request->input('length', 10);
            $sortOrder  = $columns[$request->input('order.0.column')];
            $sortDir = $request->input('order.0.dir', 'asc');

            $result = DB::select('CALL Order_detail_select(?, ?, ?, ?, ?, ?, @totalData, @totalFiltered)', [
                $request->input('id'), $searchValue, $startIdx, $limitRows, $sortOrder, $sortDir
            ]);


            $totalData = DB::select('SELECT @totalData as totalData')[0]->totalData;
            $totalFiltered = DB::select('SELECT @totalFiltered as totalFiltered')[0]->totalFiltered;

            // Process $result and return the response
            $data = [];

            if (!empty($result)) {
                foreach ($result as $group) {
                    $nestedData['id'] = $group->id;
                    $nestedData['niv'] = $group->niv;
                    $nestedData['tgk'] = $group->tgk;
                    $nestedData['qti'] = $group->qti;
                    $nestedData['hst'] = $group->hst;
                    $nestedData['jrp'] = $group->jrp;

                    $data[] = $nestedData;
                }
            }

            // Return JSON response
            return response()->json([
                'success' => true,
                'data' => $data,
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'draw' => intval($request->input('draw')),
            ]);
        }
    }

    /**
     * Mengambil data barang.
     */
    public function listInv(Request $request)
    {
        if ($request->ajax()) {
            $columns = ['ket', 'hst', 'sat','jb'];

            $searchValue = ($request->input('search.value', '') == NULL) ? '' : $request->input('search.value', '');
            $startIdx = $request->input('start', 0);
            $limitRows = $request->input('length', 10);
            $sortOrder  = $columns[$request->input('order.0.column')];
            $sortDir = $request->input('order.0.dir', 'asc');

            $result = DB::select('CALL Order_combo_inv(?, ?, ?, ?, ?, @totalData, @totalFiltered)', [
                $searchValue, $startIdx, $limitRows, $sortOrder, $sortDir
            ]);


            $totalData = DB::select('SELECT @totalData as totalData')[0]->totalData;
            $totalFiltered = DB::select('SELECT @totalFiltered as totalFiltered')[0]->totalFiltered;

            // Process $result and return the response
            $data = [];

            if (!empty($result)) {
                foreach ($result as $group) {
                    $nestedData['id'] = $group->id;
                    $nestedData['ket'] = $group->ket;
                    $nestedData['hst'] = $group->hst;
                    $nestedData['sat'] = $group->sat;
                    $nestedData['jb'] = $group->jb;

                    $data[] = $nestedData;
                }
            }

            // Return JSON response
            return response()->json([
                'success' => true,
                'data' => $data,
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'draw' => intval($request->input('draw')),
            ]);
        }
    }

    /**
     * Simpan data detail barang ke database.
     */
    function storeDetail(Request $request)
    {
        $result = Collect(DB::select("CALL Order_detail_insert(?,?,?,?)", [
            $request->input('ok_id'),
            $request->input('id'),
            $request->input('hst'),
            $request->input('qti')
        ]))->first();

        return response()->json($result);
    }

    /**
     * Update data detail barang berdasarkan id.
     */
    function updateDetail(Request $request)
    {
        $result = Collect(DB::select("CALL Order_detail_edit(?,?,?,?)", [
            $request->input('id'),
            Carbon::parse($request->input('tgk'))->format('Y-m-d'),
            $request->input('hst'),
            $request->input('qti')
        ]))->first();

        return response()->json($result);
    }

    /**
     * destroy detail barang berdasarkan id.
     */
    public function destroyDetail($id)
    {
        $result = Collect(DB::select("CALL Order_detail_delete(?)", [
            $id
        ]))->first();

        return response()->json($result);
    }
}
