@extends('layouts.base')

@section('styles')
    @parent
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
        
        .dataTables_length select.custom-select{
            width: 50px !important;
        }
        table.dataTable thead .sorting:before, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_desc_disabled:before, table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:after, table.dataTable thead .sorting_desc_disabled:after {
            display: none;
        }
        .dataTables_filter input[type="search"] {
            width: 178px !important;
            transition: all .5s;
        }
        .dataTables_filter input[type="search"]:focus {
            width: 350px !important;
        }

        div.dt-buttons {
            float: right;
            margin-left: 3px;
        }
    </style>
@endsection

@section('scripts')
    @parent
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
@endsection
