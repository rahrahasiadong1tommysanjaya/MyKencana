<?php

namespace Modules\MasterGg\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DataTables;

class MasterGgController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('mastergg::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mastergg::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(DB::select("CALL MasterGG_select()"))->toJson();
        }

        return abort(404);
    }


    public function showSubGg (Request $request)
    {
      $id=$request->input('id');
        if ($request->ajax()) {
            return DataTables::of(DB::select("CALL MasterGG_select_sub('$id')"))->toJson();
        }

        return abort(404);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
      if ($request->ajax()) {
        return DataTables::of(DB::select("CALL MasterGG_select('$id')"))->toJson();
    }

    return abort(404);    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
