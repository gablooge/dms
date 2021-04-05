@extends('layouts.datatable')

@section('title')
Jenis Dokumen
@endsection

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
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            ajax: {
                'url':"{{ route('jenis.datatables') }}",
                "type": "POST",
                'data': function (d) {
                    d._token = "{{ csrf_token() }}";
                    d.kategori_id = $("#kategori_jenis_dokumen_id").val();
                    Notiflix.Block.Circle('.yajra-datatable','Loading...');
                }
            },
            "stateSaveParams": function (settings, data) {
                //save state
                data.kategori_id = $("#kategori_jenis_dokumen_id").val();
            },
            "stateLoadParams": function (settings, data) {
                //exit is expired
                if (data.time + (settings.iStateDuration * 1000) < Date.now())
                    return;
                $("#kategori_jenis_dokumen_id").val(data.kategori_id);
            },
            columns: [
                {
                    "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false,
                    "className": "text-center",
                    "width": 25
                },
                {data: 'nama_jenis', name: 'nama_jenis'},
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
                    titleAttr: 'Tambah Jenis Dokumen',
                    action: function ( e, dt, node, config ) {
                        location.href = "{{ route('jenis.create') }}?kategori="+$('#kategori_jenis_dokumen_id').val();
                    }
                }
            ]
        });

        table.buttons(0, null).container().appendTo($('#documentDatatableId_filter'));

        $('#kategori_jenis_dokumen_id').on('change', function (e) {
            table.draw();
            // Update URL state
            params = "?"
            q_mark = location.href.indexOf("?")
            if( q_mark> 0){
                params = location.href.slice(q_mark);
            }
            urlParams = new URLSearchParams(params)
            urlParams.set("kategori", $('#kategori_jenis_dokumen_id').val())
            if( q_mark> 0){
                url_ = location.href.slice(0, q_mark)+"?"+urlParams.toString();
            }else{
                url_ = location.href+"?"+urlParams.toString();
            }
            window.history.replaceState('', '', url_);
        });
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
@section('additional-cog')
    <div class="pull-right">
        <select onchange="" class="form-control select2" id="kategori_jenis_dokumen_id" name="kategori_jenis_dokumen_id" required="">
            @foreach($kategori_list as $kategori)
                <option value="{{ $kategori->id }}" {{ ( $kategori == $kategori_selected) ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
            @endforeach
        </select>
    </div>
@stop
@section('contents')
    @parent
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