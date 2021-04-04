@extends('layouts.base')

@section('breadcrumb')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Beranda</a></li>
    <li class="breadcrumb-item"><a href="{{route('tag.index')}}">Tag</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
  </ol>
</nav>
@stop

@section('contents')
<hr>
<div class="card">
    <div class="card-header text-center font-weight-bold">
        Tambah Tag
    </div>
    <div class="card-body">
        <form name="add-tag-form" id="add-tag-form" method="post" action="{{route('tag.store')}}">
        @csrf
        <div class="form-group">
            <label for="nama_tag">Nama Tag</label>
            <input type="text" id="nama_tag" name="nama_tag" class="form-control" required="" placeholder="Nama Tag">
        </div>
        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <input type="text" id="keterangan" name="keterangan" class="form-control" placeholder="Keterangan">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <div class="float-right">
            <a class="btn btn-secondary" href="{{ route('tag.index') }}"> Batal</a>
        </div>
        </form>
    </div>
</div>
@stop