@extends('layouts.base')

@section('breadcrumb')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Beranda</a></li>
    <li class="breadcrumb-item"><a href="{{route('dokumen.index')}}">DMS</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
  </ol>
</nav>
@stop

@section('contents')
<hr>
<div class="card">
    <div class="card-header text-center font-weight-bold">
        Tambah Dokumen
    </div>
    <div class="card-body">
        <form name="add-dokumen-form" id="add-dokumen-form" method="post" action="{{route('dokumen.store')}}">
        @csrf
        <div class="form-group">
            <label for="file">File</label>
            
        </div>
        <div class="form-group">
            <label for="nomor">Nomor</label>
            <input type="text" id="nomor" name="nomor" class="form-control" placeholder="Nomor Dokumen">
        </div>
        <div class="form-group">
            <label for="tahun">Tahun</label>
            <input type="text" id="tahun" name="tahun" class="form-control" placeholder="Tahun Terbit">
        </div>
        <div class="form-group">
            <label for="tag">Tags</label>
            <input type="text" id="tag" name="tag" class="form-control" placeholder="Tag Terbit">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <div class="float-right">
            <a class="btn btn-secondary" href="{{ route('dokumen.index') }}"> Batal</a>
        </div>
        </form>
    </div>
</div>
@stop