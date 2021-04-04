<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use DataTables;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tag.index');
    }

    /**
     * Display a listing of the resource as json.
     *
     * @return Datatables
     */
    public function datatables(Request $request)
    {

        if ($request->ajax()) {
            $data = Tag::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<form action="'.route('tag.destroy',$row->id).'" method="POST">';
                    $actionBtn = $actionBtn.'<a class="dt-button dt-btn-sm" href="'.route('tag.edit',$row->id).'" title="Edit '.$row->nama_tag.'"><span><i class="fa fa-edit"></i></span></a>';
                    $actionBtn = $actionBtn.'<a class="dt-button dt-btn-sm" onclick="if(confirm(\'Apakah Anda yakin ingin menghapus data ini?\')){$(this).closest(\'form\').submit();}" title="Hapus '.$row->nama_tag.'"><span><i class="fa fa-trash"></i></span></a>';
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
        return view('tag.create');
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
            'nama_tag' => ['required', 'unique:tag', 'max:200'],
            'keterangan' => ['max:255'],
        ]);
 
        try{
            Tag::create($request->all());
            return redirect()->route('tag.index')->with('messages', 'Data Tag telah disimpan');
        }catch(\Exception $e){
            return redirect()->route('tag.create')->with('messages', 'Data Tag gagal disimpan. Error: <br />'.Str::limit($e->getMessage(), 150));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        return view('tag.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'nama_tag' => ['required', Rule::unique('tag')->ignore($tag->id), 'max:200'],
            'keterangan' => ['max:255'],
        ]);
        
        try{
            
            $tag->update($request->all());
            
            return redirect()->route('tag.index')->with('messages', 'Data Tag telah disimpan');
        }catch(\Exception $e){
            return redirect()->route('tag.edit', $tag->id)->with('messages', 'Data Tag gagal disimpan. Error: <br />'.Str::limit($e->getMessage(), 150));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        try{
            $tag->delete();
            return redirect()->route('tag.index')
                        ->with('messages', 'Data Tag berhasil dihapus.');
        }catch(\Exception $e){
            return redirect()->route('tag.index')->with('messages', 'Data Tag gagal dihapus. Error: <br />'.Str::limit($e->getMessage(), 150));
        }
    }
}
