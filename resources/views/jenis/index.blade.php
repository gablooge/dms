@extends('layouts.datatable')

@section('title')
Jenis Dokumen
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
                'url':"{{ route('jenis.datatables') }}",
                "type": "POST",
                'data': function (d) {
                    d._token = "{{ csrf_token() }}"
                }
            },
            columns: [
                {
                    "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false,
                    "width": "35px"
                },
                {data: 'jenis_dokumen', name: 'jenis_dokumen'},
                {data: 'keterangan', name: 'keterangan'},
                {
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false,
                    className: "action"
                },
            ]
        });
        
        new $.fn.dataTable.Buttons(table, {
            "buttons":
            [
                {
                    text: '<i class="fa fa-plus"></i>',
                    action: function ( e, dt, node, config ) {
                        location.href = "{{ route('jenis.create') }}"
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
    <li class="breadcrumb-item active" aria-current="page">Jenis Dokumen</li>
  </ol>
</nav>
@stop

@section('contents')
    @parent
    <h2 class="mb-4">Jenis Dokumen</h2>
    <table id="documentDatatableId" class="table table-bordered yajra-datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis Dokumen</th>
                <th>Keterangan</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
@endsection