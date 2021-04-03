@extends('layouts.datatable')

@section('title')
Kategori Jenis Dokumen
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
                'url':"{{ route('dokumen.list') }}",
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
                {data: 'nama_kategori', name: 'nama_kategori'},
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
                        alert( 'Button activated' );
                    }
                }
            ]
        });

        table.buttons(0, null).container().appendTo($('#documentDatatableId_filter'));

    });
    </script>
@endsection

@section('contents')
    @parent
    <h2 class="mb-4">Kategori Dokumen</h2>
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