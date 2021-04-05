@extends('layouts.datatable')

@section('title')
Kategori Dokumen
@endsection

@section('styles')
    @parent

@endsection

@section('scripts')
    @parent
    <script type="text/javascript">
    $(function () {

        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            ajax: {
                'url':"{{ route('kategori.datatables') }}",
                "type": "POST",
                'data': function (d) {
                    d._token = "{{ csrf_token() }}"
                    Notiflix.Block.Circle('.yajra-datatable','Loading...');
                }
            },
            columns: [
                {
                    "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false,
                    "className": "text-center",
                    "width": 25
                },
                {data: 'nama_kategori', name: 'nama_kategori'},
                {data: 'keterangan', name: 'keterangan'},
                {
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false,
                    className: "action",
                    "width": 45
                },
            ]
        });
        table.on('processing.dt', function (e, settings, processing) {
            if (!processing) {
                Notiflix.Block.Remove('.yajra-datatable');
            }
        });
        new $.fn.dataTable.Buttons(table, {
            "buttons":
            [
                {
                    text: '<i class="fa fa-plus"></i>',
                    titleAttr: 'Tambah Kategori Dokumen',
                    action: function ( e, dt, node, config ) {
                        location.href = "{{ route('kategori.create') }}"
                    }
                }
            ]
        });

        table.buttons(0, null).container().appendTo($('#documentDatatableId_filter'));

    });
    </script>
@endsection

@section('breadcrumb')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Beranda</a></li>
    <li class="breadcrumb-item active" aria-current="page">Kategori</li>
  </ol>
</nav>
@stop

@section('contents')
    @parent
    <table id="documentDatatableId" class="table table-bordered yajra-datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Keterangan</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
@endsection