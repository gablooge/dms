@extends('layouts.datatable')

@section('title')
Dokumen
@endsection

@section('styles')
    @parent
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
    <style>
        @keyframes placeHolderShimmer {
          0% {
            background-position: 0px 0;
          }
          100% {
            background-position: 100em 0;
          }
        }
        #pdfbox {
            width: 100%;
            height: 500px;
            animation-duration: 3s;
            animation-fill-mode: forwards;
            animation-iteration-count: infinite;
            animation-name: placeHolderShimmer;
            animation-timing-function: linear;
            background: fff;
            background: linear-gradient(to right, #eeeeee 8%, #dddddd 18%, #eeeeee 33%);
            -webkit-backface-visibility: hidden;
        }
    </style>
@endsection

@section('scripts')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript">
    $(function () {
        Notiflix.Report.Init({ plainText: false });

        $.fn.select2.defaults.set( "theme", "bootstrap" );
        $('.select2').select2();

        $('#documentDatatableId').on('click', '.buttons-pdf', function () {
            var modalpdf = $('#largeModalId');
            modalpdf.find("h5.modal-title").html($(this).attr("title"));
            var html = '<object id="pdfbox" type="application/pdf" data="' + $(this).data("file") + '"></object>';
            modalpdf.find(".modal-body").html(html);
            modalpdf.modal('show');
        });

        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            ajax: {
                'url':"{{ route('dokumen.datatables') }}",
                "type": "POST",
                'data': function (d) {
                    d._token = "{{ csrf_token() }}";
                    // d.kategori_id = $("#kategori_jenis_dokumen_id").val();
                    Notiflix.Block.Circle('.yajra-datatable','Loading...');
                },
                
            },
            "stateSaveParams": function (settings, data) {
                //save state
                // data.kategori_id = $("#kategori_jenis_dokumen_id").val();
            },
            "stateLoadParams": function (settings, data) {
                //exit is expired
                if (data.time + (settings.iStateDuration * 1000) < Date.now())
                    return;
                // $("#kategori_jenis_dokumen_id").val(data.kategori_id);
            },
            columns: [
                {
                    "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false,
                    "className": "text-center",
                    "width": 25
                },
                {data: 'file', name: 'file'},
                // {data: 'jenis', name: 'jenis'},
                {data: 'nomor', name: 'nomor'},
                {
                    data: 'tahun', 
                    name: 'tahun',
                    "className": "text-center"
                },
                // {data: 'tag', name: 'tag'},
                {data: 'perihal', name: 'perihal'},
                {
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false,
                    className: "action",
                    "width": 70
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
                    action: function ( e, dt, node, config ) {
                        location.href = "{{ route('dokumen.create') }}";
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
    <li class="breadcrumb-item active" aria-current="page">Dokumen</li>
  </ol>
</nav>
@stop
@section('contents')
    @parent
    
    <table id="documentDatatableId" class="table table-bordered yajra-datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>File</th>
                <!-- <th>Jenis</th> -->
                <th>Nomor</th>
                <th>Tahun</th>
                <!-- <th>Tag</th> -->
                <th>Perihal</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    
    <div id="largeModalId" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview PDF</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    ...
                </div>
            </div>
        </div>
    </div>
@stop