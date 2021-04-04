<?php

namespace App\Http\Controllers;

use App\Models\KategoriJenisDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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

    /**
     * Display a listing of the resource as json.
     *
     * @return Datatables
     */
    public function datatables(Request $request)
    {

        if ($request->ajax()) {
            $data = KategoriJenisDokumen::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<form onsubmit="Notiflix.Loading.Dots(\'Deleting...\');" action="'.route('kategori.destroy',$row->id).'" method="POST">';
                    $actionBtn = $actionBtn.'<a class="dt-button dt-btn-sm" href="'.route('kategori.edit',$row->id).'" title="Edit '.$row->nama_kategori.'"><span><i class="fa fa-edit"></i></span></a>';
                    $actionBtn = $actionBtn.'<a class="dt-button dt-btn-sm" onclick="if(confirm(\'Apakah Anda yakin ingin menghapus data ini?\')){$(this).closest(\'form\').submit();}" title="Hapus '.$row->nama_kategori.'"><span><i class="fa fa-trash"></i></span></a>';
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
        return view('kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => ['required', 'unique:kategori_jenis_dokumen', 'max:200'],
            'keterangan' => ['max:255'],
        ]);
 
        try{
            // $kategori = new KategoriJenisDokumen;
            // $kategori->nama_kategori = $request->nama_kategori;
            // $kategori->keterangan = $request->keterangan;
            // $kategori->save();
            KategoriJenisDokumen::create($request->all());
            return redirect()->route('kategori.index')->with('messages', 'Data Kategori telah disimpan');
        }catch(\Exception $e){
            return redirect()->route('kategori.create')->with('messages', 'Data Kategori gagal disimpan. Error: <br />'.Str::limit($e->getMessage(), 150));
        }
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
        return view('kategori.edit', compact('kategoriJenisDokumen'));
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
        $request->validate([
            'nama_kategori' => ['required', Rule::unique('kategori_jenis_dokumen')->ignore($kategoriJenisDokumen->id), 'max:200'],
            'keterangan' => ['max:255'],
        ]);
        
        try{
            
            $kategoriJenisDokumen->update($request->all());
            
            return redirect()->route('kategori.index')->with('messages', 'Data Kategori telah disimpan');
        }catch(\Exception $e){
            return redirect()->route('kategori.edit', $kategoriJenisDokumen->id)->with('messages', 'Data Kategori gagal disimpan. Error: <br />'.Str::limit($e->getMessage(), 150));
        }
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
        try{
            $kategoriJenisDokumen->delete();
            return redirect()->route('kategori.index')
                        ->with('messages', 'Data kategori berhasil dihapus.');
        }catch(\Exception $e){
            return redirect()->route('kategori.index')->with('messages', 'Data Kategori gagal dihapus. Error: <br />'.Str::limit($e->getMessage(), 150));
        }
    }
}
