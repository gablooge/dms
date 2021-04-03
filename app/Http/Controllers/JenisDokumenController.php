<?php

namespace App\Http\Controllers;

use App\Models\JenisDokumen;
use Illuminate\Http\Request;

use DataTables;

class JenisDokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('jenis.index');
    }

    /**
     * Display a listing of the resource as json.
     *
     * @return Datatables
     */
    public function datatables(Request $request)
    {

        if ($request->ajax()) {
            $data = JenisDokumen::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<form action="'.route('kategori.destroy',$row->id).'" method="POST">';
                    $actionBtn = $actionBtn.'<a class="dt-button dt-btn-sm" href="'.route('kategori.edit',$row->id).'" title="Edit '.$row->jenis_dokumen.'"><span><i class="fa fa-edit"></i></span></a>';
                    $actionBtn = $actionBtn.'<a class="dt-button dt-btn-sm" onclick="if(confirm(\'Apakah Anda yakin ingin menghapus data ini?\')){$(this).closest(\'form\').submit();}" title="Hapus '.$row->jenis_dokumen.'"><span><i class="fa fa-trash"></i></span></a>';
                    return $actionBtn.'<input type="hidden" name="_token" value="'.csrf_token().'"><input type="hidden" name="_method" value="DELETE"></form>';
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
     * @param  \App\Models\JenisDokumen  $jenisDokumen
     * @return \Illuminate\Http\Response
     */
    public function show(JenisDokumen $jenisDokumen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JenisDokumen  $jenisDokumen
     * @return \Illuminate\Http\Response
     */
    public function edit(JenisDokumen $jenisDokumen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JenisDokumen  $jenisDokumen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JenisDokumen $jenisDokumen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JenisDokumen  $jenisDokumen
     * @return \Illuminate\Http\Response
     */
    public function destroy(JenisDokumen $jenisDokumen)
    {
        //
    }
}
