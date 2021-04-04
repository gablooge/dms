<?php

namespace App\Http\Controllers;

use App\Models\JenisDokumen;
use App\Models\KategoriJenisDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DataTables;

class JenisDokumenController extends Controller
{
    // parameter kategori di redirect doesn't work
    private function get_kategori($request)
    {
        $kategori = $request->query('kategori', "0");
        $kategori_selected = KategoriJenisDokumen::find($kategori);
        if (!$kategori_selected) {
            $kategori_selected = KategoriJenisDokumen::first();
            if (!$kategori_selected) {
                return redirect()->route('kategori.index')->with('messages', 'Tidak ditemukan kategori, minimal harus ada 1 kategori.');
            }
            return redirect()->route('jenis.index', ['kategori' => $kategori_selected->id])->with('messages', 'Kategori yang anda pilih tidak ditemukan, dialihkan ke kategori '.$kategori_selected->nama_kategori.'.');
        }
        
        return $kategori_selected;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        // $kategori_selected = $this->get_kategori($request);
        $kategori = $request->query('kategori', "0");
        $kategori_selected = KategoriJenisDokumen::find($kategori);
        if (!$kategori_selected) {
            $kategori_selected = KategoriJenisDokumen::first();
            if (!$kategori_selected) {
                return redirect()->route('kategori.index')->with('messages', 'Tidak ditemukan kategori, minimal harus ada 1 kategori.');
            }
            return redirect()->route('jenis.index', ['kategori' => $kategori_selected->id])->with('messages', 'Kategori yang anda pilih tidak ditemukan, dialihkan ke ketegori '.$kategori_selected->nama_kategori.'.');
        }
        $kategori_list = KategoriJenisDokumen::orderBy('nama_kategori')->get();
        return view('jenis.index', compact('kategori_list', 'kategori_selected'));
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
                ->filter(function ($query) {
                    if (request()->has('kategori_id')) {
                        $query->where('kategori_jenis_dokumen_id', request('kategori_id'));
                    }
                })
                ->order(function ($query) {
                    $query->orderBy('id', 'asc');
                })
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<form action="'.route('kategori.destroy',$row->id).'" method="POST">';
                    $actionBtn = $actionBtn.'<a class="dt-button dt-btn-sm" href="'.route('kategori.edit',$row->id).'" title="Edit '.$row->nama_jenis.'"><span><i class="fa fa-edit"></i></span></a>';
                    $actionBtn = $actionBtn.'<a class="dt-button dt-btn-sm" onclick="if(confirm(\'Apakah Anda yakin ingin menghapus data ini?\')){$(this).closest(\'form\').submit();}" title="Hapus '.$row->nama_jenis.'"><span><i class="fa fa-trash"></i></span></a>';
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
    public function create(Request $request)
    {
        // $kategori_selected = $this->get_kategori($request);
        $kategori = $request->query('kategori', "0");
        $kategori_selected = KategoriJenisDokumen::find($kategori);
        if (!$kategori_selected) {
            $kategori_selected = KategoriJenisDokumen::first();
            if (!$kategori_selected) {
                return redirect()->route('kategori.index')->with('messages', 'Tidak ditemukan kategori, minimal harus ada 1 kategori.');
            }
            return redirect()->route('jenis.index', ['kategori' => $kategori_selected->id])->with('messages', 'Kategori yang anda pilih tidak ditemukan, dialihkan ke ketegori '.$kategori_selected->nama_kategori.'.');
        }
        $kategori_list = KategoriJenisDokumen::orderBy('nama_kategori')->get();
        return view('jenis.create', compact('kategori_list', 'kategori_selected'));
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
            'nama_jenis' => ['required', 'max:200'],
            'keterangan' => ['max:255'],
            'kategori_jenis_dokumen_id' => ['required'],
        ]);
 
        try{
            JenisDokumen::create($request->all());
            return redirect()->route('jenis.index', ["kategori" => $request->kategori_jenis_dokumen_id])->with('messages', 'Data Jenis Dokumen telah disimpan');
        }catch(\Exception $e){
            return redirect()->route('jenis.create', ["kategori" => $request->kategori_jenis_dokumen_id])->with('messages', 'Data Jenis Dokumen gagal disimpan. Error: <br />'.Str::limit($e->getMessage(), 150));
        }
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
