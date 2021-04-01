<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peraturan;

use DataTables;

class PeraturanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $peraturans = Peraturan::with('jenis_peraturan')->offset(0)->limit(50)->get();
        // // $peraturans = Peraturan::find(1);
        // die($peraturans);
    	return view('peraturan.index');
    }

    public function getPeraturan(Request $request)
    {

        if ($request->ajax()) {
            // $data = Peraturan::latest()->offset(0)->limit(50)->get();
            // $start = $request->input('start', '0');
            // $length = $request->input('length', '10');
            // $data = Peraturan::latest()->skip($start)->take($length)->get();
            $data = Peraturan::query();
            // $data = Peraturan::with('jenis_peraturan');
            // die($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('jenis', function (Peraturan $peraturan){
                    return $peraturan->jenis_peraturan->keterangan;
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
