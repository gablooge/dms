<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DataTables;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Spatie\PdfToText\Pdf;

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
    public function db()
    {
        return view('dokumen.db');
    }
    public function getSolrDocumentFieldValue($document, $field)
    {
        if (isset($document->$field)){
            $value = $document->$field;
            if (is_array($value)) {
                $value = implode(', ', $value);
            }
            return $value;
        }
        return "-";
    }
    public function convertPDFToText($dokumen, $solr = true)
    {
        $response = [
            'success' => false,
            'message' => "Convert PDF to Text gagal.",
            'document' => $dokumen
        ];
        
        try{
            $local_media_file = public_path('medias/'.$dokumen->file);
            if(!File::exists($local_media_file)){
                $response = [
                    'success' => false,
                    'message' => "File PDF tidak ditemukan.",
                    'document' => $dokumen
                ];
            }else{ 
                $tmp = explode(' - ', $dokumen->file);
                $tmp = end($tmp);
                $fileName = time().' - '.$tmp;
                $process = new Process([env('BIN_OCRMYPDF', '/usr/bin/ocrmypdf'), public_path('medias/'.$dokumen->file), public_path('medias/'.$fileName)]);
                $process->run();
                // if (!$process->isSuccessful()) {
                // }
                if(File::exists(public_path('medias/'.$fileName))){
                    $temp = $dokumen->file;
                    $dokumen->file = $fileName;
                    $dokumen->solr = false;
                    $dokumen->save();
                    File::delete(public_path('medias/'.$temp));
                    $response = [
                        'success' => true,
                        'message' => "Isi PDF berhasil diubah menjadi text.",
                        'document' => $dokumen
                    ];
                    if($solr){
                        if(app('App\Http\Controllers\SolariumController')->update($dokumen) == 0){
                            $dokumen->solr = true;
                            $dokumen->save();
                        }
                    }
                }
            }
        }catch(\Exception $e){
            $response = [
                'success' => false,
                'message' => "Convert PDF to Text gagal. Error: <br />".Str::limit($e->getMessage(), 500),
                'document' => $dokumen
            ];
        }
        return $response;
    }
    public function sync(Request $request)
    {
        $params['all'] = $request->query('all', false);
        try{
            dispatch(new \App\Jobs\SyncSolrDatabaseJob($params));
            $data = [
                'success' => true,
                'message' => "Sinkronisasi berhasil dijalankan."
            ];
            return response()->json($data);
        }catch(\Exception $e){
            $data = [
                'success' => false,
                'message' => "Terjadi permasalahan saat sinkronisasi batch database ke solr. Error: <br />".Str::limit($e->getMessage(), 150)
            ];
            return response()->json($data);
        }
    }
    
    public function solr(Request $request)
    {
        try{
            $resultset = app('App\Http\Controllers\SolariumController')->select($request);
            $documentset = $resultset->getDocuments();
            $data = [
                "data" => [],
                "draw" => $request->input('draw', 1),
                "recordsFiltered" => $resultset->getNumFound(),
                "recordsTotal" => app('App\Http\Controllers\SolariumController')->total()
            ];
            $documents = [];
            $rowIndex = 1;
            foreach ($documentset as $document) {
                $doc = (object)[];
                // foreach ($document as $field => $value) {
                //     if (is_array($value)) {
                //         $value = implode(', ', $value);
                //     }
                //     $doc->$field = $value;
                // }
                $doc->DT_RowIndex = $rowIndex;
                $doc->id = $document->id;
                $doc->file = $this->getSolrDocumentFieldValue($document, "file");
                $doc->nomor = $this->getSolrDocumentFieldValue($document, "nomor");
                $doc->tahun = $this->getSolrDocumentFieldValue($document, "tahun");
                $doc->tags = $this->getSolrDocumentFieldValue($document, "tags");
                $doc->perihal = $this->getSolrDocumentFieldValue($document, "perihal");
                $actionBtn = '<form onsubmit="Notiflix.Loading.Dots(\'Deleting...\');" action="'.route('dokumen.destroy',$doc->id).'" method="POST">';
                if($doc->file != "-"){
                    $actionBtn = $actionBtn.'<a class="dt-button dt-btn-sm buttons-pdf buttons-html5" tabindex="0" aria-controls="download" data-file="/medias/'.$doc->file.'" type="button" title="'.$doc->file.'"><span><i class="fa fa-file-pdf-o"></i></span></a>';
                }
                $actionBtn = $actionBtn.'<a class="dt-button dt-btn-sm" href="'.route('dokumen.edit',$doc->id).'" title="Edit '.$doc->file.'"><span><i class="fa fa-edit"></i></span></a>';
                $actionBtn = $actionBtn.'<a class="dt-button dt-btn-sm" onclick="if(confirm(\'Apakah Anda yakin ingin menghapus data ini?\')){$(this).closest(\'form\').submit();}" title="Hapus '.$doc->file.'"><span><i class="fa fa-trash"></i></span></a>';
                $actionBtn = $actionBtn.'<input type="hidden" name="_token" value="'.csrf_token().'"><input type="hidden" name="_method" value="DELETE"></form>';
                $doc->action = $actionBtn;
                array_push($documents, $doc);
                $rowIndex = $rowIndex + 1;
            }
            $data["data"] = $documents;
            return response()->json($data);
        }catch(\Exception $e){
            $data = [
                'data' => [],
                'success' => false,
                'message' => "Terjadi permasalahan saat akses data dari solr. Error: <br />".Str::limit($e->getMessage(), 500)
            ];
            return response()->json($data);
        }
    }
    public function database(Request $request)
    {
        try{
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
                    if(request()->has('search') && !empty(request()->search['value'])){
                        $query->orWhere(DB::raw('lower(perihal)'), 'like', "%" .strtolower(request()->search['value']). "%");
                        $query->orWhere(DB::raw('lower(nomor)'), 'like', "%" .strtolower(request()->search['value']). "%");
                        $query->orWhere(DB::raw('lower(tahun)'), 'like', "%" .strtolower(request()->search['value']). "%");
                        $query->orWhere(DB::raw('lower(tags)'), 'like', "%" .strtolower(request()->search['value']). "%");
                        $query->orWhere(DB::raw('lower(isi)'), 'like', "%" .strtolower(request()->search['value']). "%");
                    }
                    if (request()->has('solr') && request()->solr != null && (request()->solr == 1 || request()->solr == 0)) {
                        $query->where('solr', '=', request()->solr);
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
        }catch(\Exception $e){
            $data = [
                'data' => [],
                'success' => false,
                'message' => "Terjadi permasalahan saat akses data dari database. Error: <br />".Str::limit($e->getMessage(), 200)
            ];
            return response()->json($data);
        }
    }
    /**
     * Display a listing of the resource as json.
     *
     * @return Datatables
     */
    public function datatables(Request $request)
    {
        $source = $request->input('source', env('DB_SEARCH', 'SOLR'));
        
        if($source == 'DB'){
            if ($request->ajax()) {
                return $this->database($request);
            }
        }else{
            return $this->solr($request);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $dokumen = new Dokumen($request->all());
        return view('dokumen.create', compact('dokumen'));
    }
    public function save_tags(Dokumen $dokumen, Request $request)
    {
        $dokumen->tags_list()->detach();
        $dokumen->tags = null;
        if ($request->has('tag_list')) {
            foreach($request->tag_list as $tagName){
                $tag = Tag::firstOrNew(['nama_tag' => strtoupper($tagName)]);
                $tag->keterangan = $tagName;
                $tag->save();
            }
            $tags = Tag::whereIn('nama_tag', $request->tag_list)->orderBy('nama_tag', 'ASC')->pluck('id');
            $dokumen->tags_list()->sync($tags);
            $dokumen->tags = join(", ", $request->tag_list); 
        }
        $dokumen->save(); 
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
        $fileName = "";
        try{
            $fileName = time().' - '.$request->file->getClientOriginalName();
            $request->file->move(public_path('medias'), $fileName);
            $dokumen = Dokumen::create($request->all());
            $dokumen->file = $fileName;

            $dokumen->save();

            $response = $this->convertPDFToText($dokumen, false);
            if($response["success"]){
                $dokumen = $response["document"];
            }
            $dokumen->isi = Pdf::getText(public_path('medias/'.$dokumen->file));

            // Job masih sering failed
            // $params['id'] = $dokumen->id;
            // dispatch(new \App\Jobs\PdfToTextJob($params));

            // other information
            // Tags 
            $this->save_tags($dokumen, $request);

            if(app('App\Http\Controllers\SolariumController')->add($dokumen) == 0){
                $dokumen->solr = true;
                $dokumen->save();
            }
            return redirect()->route('dokumen.index')->with('messages', 'Data Dokumen telah disimpan');
        }catch(\Exception $e){
            return redirect()->route('dokumen.create')->with('messages', 'Data Dokumen gagal disimpan. Error: <br />'.Str::limit($e->getMessage(), 500));
            // $dokumen = new Dokumen($request->all());
            // return view('dokumen.create', compact('dokumen'))->with('messages', 'Data Dokumen gagal disimpan. Error: <br />'.Str::limit($e->getMessage(), 200));
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
                $dokumen->save();
                $response = $this->convertPDFToText($dokumen, false);
                if($response["success"]){
                    $dokumen = $response["document"];
                }
                $dokumen->isi = Pdf::getText(public_path('medias/'.$dokumen->file));

                // Masih sering failed
                // $params['id'] = $dokumen->id;
                // dispatch(new \App\Jobs\PdfToTextJob($params));
            }
            $dokumen->tahun = $request->tahun;
            $dokumen->nomor = $request->nomor;
            $dokumen->perihal = $request->perihal;
            $dokumen->solr = false;
            $dokumen->save();
            // other information
            // Tags 
            $this->save_tags($dokumen, $request);
            // end tags

            if(app('App\Http\Controllers\SolariumController')->update($dokumen) == 0){
                $dokumen->solr = true;
                $dokumen->save();
            }
            return redirect()->route('dokumen.index')->with('messages', 'Data Dokumen telah disimpan');
        }catch(\Exception $e){
            return redirect()->route('dokumen.edit', $dokumen->id)->with('messages', 'Data dokumen gagal disimpan. Error: <br />'.Str::limit($e->getMessage(), 2000));
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
            if(app('App\Http\Controllers\SolariumController')->delete($dokumen->id) == 0){
                $local_media_file = public_path('medias/'.$dokumen->file);
                if(File::exists($local_media_file)){
                    File::delete($local_media_file);
                }
                $dokumen->delete();
                return redirect()->route('dokumen.index')
                    ->with('messages', 'Data dokumen berhasil dihapus.');
            }else{
                return redirect()->route('dokumen.index')
                    ->with('messages', 'Gagal menghapus data dari Sistem Solr.');
            }
            
        }catch(\Exception $e){
            return redirect()->route('dokumen.index')->with('messages', 'Data dokumen gagal dihapus. Error: <br />'.Str::limit($e->getMessage(), 150));
        }
    }
}
