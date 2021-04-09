<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DataTables;
use File;

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
                ->filter(function ($query) {
                    if (request()->has('tags') && !empty(request()->tags)) {
                        $tagIds = Tag::whereIn('nama_tag', request()->tags)->pluck('id');
                        $query->whereHas('tags_list', function($q) use($tagIds) {
                            $q->whereIn('id', $tagIds);
                        });
                    }
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<form onsubmit="Notiflix.Loading.Dots(\'Deleting...\');" action="'.route('dokumen.destroy',$row->id).'" method="POST">';
                    if($row->file != "-"){
                        $actionBtn = $actionBtn.'<a class="dt-button dt-btn-sm buttons-pdf buttons-html5" tabindex="0" aria-controls="download" data-file="/medias/'.$row->file.'" type="button" title="'.$row->file.'"><span><i class="fa fa-file-pdf-o"></i></span></a>';
                    }
                    $actionBtn = $actionBtn.'<a class="dt-button dt-btn-sm" href="'.route('dokumen.edit',$row->id).'" title="Edit '.$row->file.'"><span><i class="fa fa-edit"></i></span></a>';
                    $actionBtn = $actionBtn.'<a class="dt-button dt-btn-sm" onclick="if(confirm(\'Apakah Anda yakin ingin menghapus data ini?\')){$(this).closest(\'form\').submit();}" title="Hapus '.$row->file.'"><span><i class="fa fa-trash"></i></span></a>';
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
            if(app('App\Http\Controllers\SolariumController')->add($dokumen) == 0){
                $dokumen->solr = true;
                $dokumen->save();
            }
            return redirect()->route('dokumen.edit', $dokumen->id)->with('messages', 'Data Dokumen telah disimpan');
        }catch(\Exception $e){
            return redirect()->route('dokumen.create')->with('messages', 'Data Dokumen gagal disimpan. Error: <br />'.Str::limit($e->getMessage(), 150));
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
        $request->validate([
            'tahun' => ['required'],
            'nomor' => ['required', 'max:200'],
        ]);
        
        try{
            // main information
            if($request->hasFile('file')){
                $local_media_file = public_path('medias/'.$dokumen->file);
                if(File::exists($local_media_file)){
                    File::delete($local_media_file);
                }
                $request->validate([
                  'file' => 'required|file|mimes:jpg,jpeg,bmp,png,pdf',
                ]);
                $fileName = time().' - '.$request->file->getClientOriginalName();
                $request->file->move(public_path('medias'), $fileName);
                $dokumen->file = $fileName;
            }
            $dokumen->tahun = $request->tahun;
            $dokumen->nomor = $request->nomor;
            $dokumen->perihal = $request->perihal;
            $dokumen->save();
            // other information
            // Tags 
            $dokumen->tags_list()->detach();
            if ($request->has('tag_list')) {
                foreach($request->tag_list as $tagName){
                    $tag = Tag::firstOrNew(['nama_tag' => strtoupper($tagName)]);
                    $tag->keterangan = $tagName;
                    $tag->save();
                }
                $tags = Tag::whereIn('nama_tag', $request->tag_list)->orderBy('nama_tag', 'ASC')->pluck('id');
                $dokumen->tags_list()->sync($tags);
                $dokumen->tags = join(",", $request->tag_list); 
                $dokumen->save();  
            }


            return redirect()->route('dokumen.index')->with('messages', 'Data Dokumen telah disimpan');
        }catch(\Exception $e){
            return redirect()->route('dokumen.edit', $dokumen->id)->with('messages', 'Data dokumen gagal disimpan. Error: <br />'.Str::limit($e->getMessage(), 200));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dokumen  $dokumen
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dokumen $dokumen)
    {
        try{
            $local_media_file = public_path('medias/'.$dokumen->file);
            if(File::exists($local_media_file)){
                File::delete($local_media_file);
            }
            $dokumen->delete();
            return redirect()->route('dokumen.index')
                        ->with('messages', 'Data dokumen berhasil dihapus.');
        }catch(\Exception $e){
            return redirect()->route('dokumen.index')->with('messages', 'Data dokumen gagal dihapus. Error: <br />'.Str::limit($e->getMessage(), 150));
        }
    }
}
