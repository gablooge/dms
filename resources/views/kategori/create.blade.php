@extends('layouts.base')

@section('title')
Kategori Dokumen
@endsection

@section('breadcrumb')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Beranda</a></li>
    <li class="breadcrumb-item"><a href="{{route('kategori.index')}}">Kategori</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
  </ol>
</nav>
@stop

@section('contents')
<hr>
<div class="card">
    <div class="card-header text-center font-weight-bold">
        Tambah Kategori
    </div>
    <div class="card-body">
        <form onsubmit="Notiflix.Loading.Dots('Uploading...');" name="add-kategori-form" id="add-kategori-form" method="post" action="{{route('kategori.store')}}">
        @csrf
        <div class="form-group">
            <label for="nama_kategori">Nama Kategori</label>
            <input type="text" id="nama_kategori" name="nama_kategori" class="form-control" required="" placeholder="Nama Kategori">
        </div>
        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <input type="text" id="keterangan" name="keterangan" class="form-control" placeholder="Keterangan">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <div class="float-right">
            <a class="btn btn-secondary" href="{{ route('kategori.index') }}"> Batal</a>
        </div>
        </form>
    </div>
</div>
@stop