<!DOCTYPE html>
<html>
<head>
    <title>Kategori Jenis Dokumen | DMS BAPENDA</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        div.dataTables_wrapper div.dataTables_processing {
            height: unset !important;
            background-color: darkseagreen;
            animation: pulse 2s infinite;
            position: fixed;
        }
                
        button.dt-button {
            padding: .2em .5em;
        }
        
        td.action {
            vertical-align: middle;
            text-align: center;
        }
        
        #documentDatatableId_length select[name=documentDatatableId_length]{
            width: 50px;
        }
        table.dataTable thead .sorting:before, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_desc_disabled:before, table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:after, table.dataTable thead .sorting_desc_disabled:after {
            display: none;
        }
        #documentDatatableId_filter input[type="search"] {
            width: 178px;
            transition: all .5s;
        }
        #documentDatatableId_filter input[type="search"]:focus {
            width: 350px;
        }
    </style>
</head>
<body>
    
    <div class="container mt-5">
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
    </div>
    
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

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
    
  });
</script>
</html>