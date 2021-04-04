<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Http\Request;

use DataTables;

class DokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('dokumen.index');
    }

    /**
     * Display a listing of the resource as json.
     *
     * @return Datatables
     */
    public function datatables(Request $request)
    {

        if ($request->ajax()) {
            $data = Dokumen::query();
            return Datatables::of($data)
                ->addIndexColumn()
                // ->addColumn('jenis', function (Dokumen $docs){
                //     return "-";
                // })
                ->addColumn('action', function($row){
                    $actionBtn = '<form action="'.route('kategori.destroy',$row->id).'" method="POST">';
                    if($row->file != "-"){
                        $actionBtn = $actionBtn.'<a class="dt-button dt-btn-sm buttons-pdf buttons-html5" tabindex="0" aria-controls="download" data-file="/medias/'.$row->file.'" type="button" title="'.$row->file.'"><span><i class="fa fa-file-pdf-o"></i></span></a>';
                    }
                    $actionBtn = $actionBtn.'<a class="dt-button dt-btn-sm" href="'.route('dokumen.edit',$row->id).'" title="Edit '.$row->file.'"><span><i class="fa fa-edit"></i></span></a>';
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
        return view('dokumen.create');
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
            'file' => 'required|file|mimes:jpg,jpeg,bmp,png,pdf',
            'tahun' => ['required'],
            'nomor' => ['required', 'max:200'],
        ]);
          
        try{
            $fileName = time().' - '.$request->file->getClientOriginalName();
            $request->file->move(public_path('medias'), $fileName);

            $dokumen = Dokumen::create($request->all());
            $dokumen->file = $fileName;
            $dokumen->save();
            return redirect()->route('dokumen.index')->with('messages', 'Data Tag telah disimpan');
        }catch(\Exception $e){
            return redirect()->route('dokumen.create')->with('messages', 'Data Tag gagal disimpan. Error: <br />'.Str::limit($e->getMessage(), 150));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dokumen  $dokumen
     * @return \Illuminate\Http\Response
     */
    public function show(Dokumen $dokumen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dokumen  $dokumen
     * @return \Illuminate\Http\Response
     */
    public function edit(Dokumen $dokumen)
    {
        return view('dokumen.edit', compact('dokumen'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dokumen  $dokumen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dokumen $dokumen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dokumen  $dokumen
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dokumen $dokumen)
    {
        //
    }
}
