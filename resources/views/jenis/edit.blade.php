@extends('layouts.base')

@section('styles')
    @parent
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
@endsection

@section('scripts')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $.fn.select2.defaults.set( "theme", "bootstrap" );
            $('.select2').select2();
            $('#batallinkid').on('click', function (e) {
                location.href = "{{ route('jenis.index') }}?kategori="+$('#kategori_jenis_dokumen_id').val();
            });
        });
    </script>
@endsection

@section('breadcrumb')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Beranda</a></li>
    <li class="breadcrumb-item"><a href="{{route('jenis.index')}}">Jenis Dokumen</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
  </ol>
</nav>
@stop

@section('contents')
<hr>
<div class="card">
    <div class="card-header text-center font-weight-bold">
        Edit Jenis Dokumen
    </div>
    <div class="card-body">
    <form name="edit-jenis-form" id="edit-jenis-form" method="post" action="{{ route('jenis.update', $jenisDokumen->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="kategori_jenis_dokumen_id">Kategori</label>
            <select class="form-control select2" id="kategori_jenis_dokumen_id" name="kategori_jenis_dokumen_id" required="">
                @foreach($kategori_list as $kategori)
                    <option value="{{ $kategori->id }}" {{ ( $kategori->id == $jenisDokumen->kategori_jenis_dokumen_id) ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="nama_jenis">Jenis Dokumen</label>
            <input type="text" id="nama_jenis" name="nama_jenis" class="form-control" value="{{ $jenisDokumen->nama_jenis }}" required="" placeholder="Nama Jenis Dokumen">
        </div>
        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <input type="text" id="keterangan" name="keterangan" class="form-control" value="{{ $jenisDokumen->keterangan }}" placeholder="Keterangan">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <div class="float-right">
            <button type="button" id="batallinkid" class="btn btn-secondary">Batal</button>
        </div>
        </form>
    </div>
</div>
@stop