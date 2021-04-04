@extends('layouts.base')

@section('breadcrumb')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Beranda</a></li>
    <li class="breadcrumb-item"><a href="{{route('kategori.index')}}">Kategori</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
  </ol>
</nav>
@stop

@section('contents')
<hr>
<div class="card">
    <div class="card-header text-center font-weight-bold">
        Edit Kategori
    </div>
    <div class="card-body">
        <form onsubmit="Notiflix.Loading.Dots('Uploading...');" name="edit-kategori-form" id="edit-kategori-form" method="post" action="{{ route('kategori.update', $kategoriJenisDokumen->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nama_kategori">Nama Kategori</label>
            <input type="text" id="nama_kategori" name="nama_kategori" value="{{ $kategoriJenisDokumen->nama_kategori }}" class="form-control" required="" placeholder="Nama Kategori">
        </div>
        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <input type="text" id="keterangan" name="keterangan" value="{{ $kategoriJenisDokumen->keterangan }}" class="form-control" placeholder="Keterangan">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <div class="float-right">
            <a class="btn btn-secondary" href="{{ route('kategori.index') }}"> Batal</a>
        </div>
        </form>
    </div>
</div>
@stop