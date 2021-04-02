<?php

namespace App\Http\Controllers;

use App\Models\KategoriJenisDokumen;
use Illuminate\Http\Request;

use DataTables;

class KategoriJenisDokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('kategori.index');
    }
    public function getKategori(Request $request)
    {

        if ($request->ajax()) {
            $data = KategoriJenisDokumen::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '';
                    
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
            
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KategoriJenisDokumen  $kategoriJenisDokumen
     * @return \Illuminate\Http\Response
     */
    public function show(KategoriJenisDokumen $kategoriJenisDokumen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KategoriJenisDokumen  $kategoriJenisDokumen
     * @return \Illuminate\Http\Response
     */
    public function edit(KategoriJenisDokumen $kategoriJenisDokumen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KategoriJenisDokumen  $kategoriJenisDokumen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KategoriJenisDokumen $kategoriJenisDokumen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KategoriJenisDokumen  $kategoriJenisDokumen
     * @return \Illuminate\Http\Response
     */
    public function destroy(KategoriJenisDokumen $kategoriJenisDokumen)
    {
        //
    }
}
