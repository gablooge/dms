@extends('layouts.datatable')

@section('title')
Dokumen
@endsection

@section('styles')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" />
    <style>
        /** PDF Box */
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
        
        /** loading input **/
        .icon-container {
            position: absolute;
            right: 10px;
            top: calc(50% - 10px);
        }
        .loader {
            position: relative;
            height: 20px;
            width: 20px;
            display: inline-block;
            animation: around 5.4s infinite;
        }

        @keyframes around {
        0% {
            transform: rotate(0deg)
        }
        100% {
            transform: rotate(360deg)
            }
        }

        .loader::after, .loader::before {
            content: "";
            background: white;
            position: absolute;
            display: inline-block;
            width: 100%;
            height: 100%;
            border-width: 2px;
            border-color: #333 #333 transparent transparent;
            border-style: solid;
            border-radius: 20px;
            box-sizing: border-box;
            top: 0;
            left: 0;
            animation: around 0.7s ease-in-out infinite;
        }

        .loader::after {
            animation: around 0.7s ease-in-out 0.1s infinite;
            background: transparent;
        }
        /** end loading input **/
    </style>
@endsection

@section('scripts')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.proto.min.js" integrity="sha512-jVHjpoNvP6ZKjpsZxTFVEDexeLNdWtBLVcbc7y3fNPLHnldVylGNRFYOc7uc+pfS+8W6Vo2DDdCHdDG/Uv460Q==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript">
    $(function () {
        Notiflix.Report.Init({ plainText: false });

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
                // 'url':"{{ route('dokumen.datatables') }}",
                'url':"{{ route('dokumen.solr') }}",
                "type": "POST",
                'data': function (d) {
                    d._token = "{{ csrf_token() }}";
                    d.tags = $("#tag_list").val();
                    d.match_tag_list = $("input[type=radio][name=match_tag_list]:checked").val();
                    Notiflix.Block.Circle('.yajra-datatable','Loading...');
                },
                'complete': function(data){
                    Notiflix.Block.Remove('.yajra-datatable');
                    respon = JSON.parse(data.responseText);
                    if(respon.success == false){
                        Notiflix.Report.Failure( 'Load Data Gagal', respon.message, 'Tutup' ); 
                    }
                }
            },
            "stateSaveParams": function (settings, data) {
                //save state
                // data.tags = $("#tag_list").val();
            },
            "stateLoadParams": function (settings, data) {
                //exit is expired
                if (data.time + (settings.iStateDuration * 1000) < Date.now())
                    return;
                // $("#tag_list").val(data.tags);
            },
            "drawCallback": function(settings) {
                
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
                {data: 'tags', name: 'tags'},
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
            // console.log(processing);
            if (!processing) {
                Notiflix.Block.Remove('.yajra-datatable');
            }
        });
        new $.fn.dataTable.Buttons(table, {
            "buttons":
            [
                {
                    text: '<i class="fa fa-plus"></i>',
                    titleAttr: 'Tambah Dokumen',
                    action: function ( e, dt, node, config ) {
                        location.href = "{{ route('dokumen.create') }}";
                    }
                },{
                    text: '<i class="fa fa-filter"></i>',
                    titleAttr: 'Filter Lanjutan',
                    action: function ( e, dt, node, config ) {
                        $("#title-card-id .card-body").collapse('toggle');
                    }
                }
            ]
        });

        table.buttons(0, null).container().appendTo($('#documentDatatableId_filter'));

        $("#tag_list").chosen({
            width: "200px"
        });
        $('#tag_list').on('chosen:ready', function(evt, params) {
            $("#tag_list_chosen").css("min-width","200px");
            $("#tag_list_chosen").css("width","auto");
        });
        $('#filter-panel-form-id select, #filter-panel-form-id input').on('change', function(evt, params) {
            table.draw();
        });
        var counter = 0;
        $('#filter-panel-form-id .chosen-search-input').autocomplete({
            source: function( request, response ) {
                if(request.term.length>1){
                    $.ajax({
                        url: "{{route('tag.select')}}",
                        data: {
                            q: request.term
                            
                        },
                        dataType: "json",
                        beforeSend: function() {
                            counter++;
                            $(".icon-container i").addClass("loader");
                        },
                        success: function( data ) {
                            // clear unselected option
                            if(data.length > 0){
                                $('#tag_list').find('option').not(':selected').remove();
                                
                                response( $.map( data, function( item ) {
                                    $('#tag_list').append('<option value="'+item.id+'">' + item.text + '</option>');
                                }));
                                $("#tag_list").trigger("chosen:updated");
                            }
                        },
                        complete: function() {
                            counter--;
                            if (counter <= 0) {
                                $(".icon-container i").removeClass('loader');
                            }
                        }
                    });
                }
            }
        });
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

@section('additional-content-filters')
    <div id="filter-panel" class="filter-panel" style="height: auto;">
        <div class="panel panel-default">
            <div class="panel-body">
            <form id="filter-panel-form-id">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="tag_list">Tag</label>
                    <div class="col-sm-10" style="position: relative; padding-top: calc(.375rem + 1px);">
                        <select data-placeholder="Pilih Tag..." multiple="multiple" class="form-control" id="tag_list" name="tag_list[]" style="min-width: 200px;">
                            <option></option>
                        </select>
                        <div class="form-check form-check-inline ml-2">
                            <label class="form-check-label">Match: </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="match_tag_list" id="match_tag_list_all" value="AND">
                            <label class="form-check-label" for="match_tag_list_all">All</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="match_tag_list" id="match_tag_list_any" value="OR" checked>
                            <label class="form-check-label" for="match_tag_list_any">Any</label>
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
@stop

@section('additional-cog')
    <div class="pull-right">
        <a class="btn btn-sm btn-outline-success" href="{{ route('dokumen.db') }}" role="button"><i class="fa fa-database" title="Database Oracle"></i></a>
    </div>
@stop

@section('contents')
    @parent
    
    <table id="documentDatatableId" class="table table-bordered yajra-datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>File</th>
                <th>Nomor</th>
                <th>Tahun</th>
                <th>Tag</th>
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