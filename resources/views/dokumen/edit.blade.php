@extends('layouts.base')

@section('styles')
    @parent
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
    
    <style>
        .modal-header {
            display: block;
        }
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
        /* btn group */
        .input-group{
            display: table;
        }
        label.input-group-btn{
            display: table-cell;
            width: 1%;
            vertical-align: middle;
        }
        .input-group .form-control {
            width: 100%;
            display: table-cell;
        }
        .input-group .form-control:first-child, .input-group-addon:first-child, .input-group-btn:first-child > .btn, .input-group-btn:first-child > .btn-group > .btn, .input-group-btn:first-child > .dropdown-toggle, .input-group-btn:last-child > .btn:not(:last-child):not(.dropdown-toggle), .input-group-btn:last-child > .btn-group:not(:last-child) > .btn {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .input-group-btn {
            position: relative;
            font-size: 0;
            white-space: nowrap;
        }
        .btn-pdf {
            margin-left: -1px;
        }
        .input-group .form-control:last-child, .input-group-addon:last-child, .input-group-btn:last-child > .btn, .input-group-btn:last-child > .btn-group > .btn, .input-group-btn:last-child > .dropdown-toggle, .input-group-btn:first-child > .btn:not(:first-child), .input-group-btn:first-child > .btn-group:not(:first-child) > .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
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

        $("#removeFile").on("click", function () {
            $("#fileinputlabelid").val("");
            $("#file").val("");
            ShowBtn();
        });

        function ShowBtn() {
            var fileName = $("input[name=FileName]").val();
            if (fileName == "") {
                $("#removeFile").addClass("d-none");
                $(".btn-pdf").addClass("d-none");
            } else {
                $("#removeFile").removeClass("d-none");
                $(".btn-pdf").removeClass("d-none");
            }
        }
        var filepdf;
        $("#file").change(function(e) {
            var input = $(this), numFiles = input.get(0).files ? input.get(0).files.length : 1, label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);
            if (this.files && this.files[0]) {
                Notiflix.Loading.Dots('Uploading...');
                var reader = new FileReader();
                reader.readAsDataURL(this.files[0]);
                filepdf = this.files[0];
                reader.onload = function (e1) {
                    Notiflix.Loading.Remove();
                    $("#preview").removeClass("d-none");
                }

            } else {
                $("#preview").addClass("d-none");
            }
        });

        $('#file').on('fileselect', function (event, numFiles, label) {

            var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;

            if (input.length) {
                input.val(log);
            } else {
                if (log) alert(log);
            }
        });
        var pdffile_url = "";
        $("#previewpdf").on("hide.bs.modal", function(e) {
            var pdf = $("#pdfbox")[0];
            pdf.data = "";
            URL.revokeObjectURL(pdffile_url);

        });
        $("#previewpdf").on("shown.bs.modal", function(e) {
            var $file = $("#file")[0];
            if ($file && $file.files && $file.files[0]) {
                pdffile_url = URL.createObjectURL($file.files[0]);
                var pdf = $("#pdfbox")[0];
                pdf.data = pdffile_url;
                var filename = $("#fileinputlabelid");
                $("#previewpdf").find("div > div > div.modal-header > span").html("<i class=\"fa fa-file-pdf-o\"></i> " + filename.val());
                $("#previewpdf").find("h4.modal-title").html("Upload File Dokumen Baru");
            } else {
                //alert("no data to show");
                var filename = $("#fileinputlabelid");
                var modalpdf = $(this);
                var url = "/medias/"+filename.val();
                var html = '<object id="pdfbox" type="application/pdf" data="' + url + '"></object>';
                modalpdf.find(".modal-body").html(html);
                modalpdf.find("div > div > div.modal-header > span").html("<i class=\"fa fa-file-pdf-o\"></i> " + filename.val());
                // modalpdf.find("h4.modal-title").html(filename.val());
            }
        });
    });
    </script>
@endsection
@section('breadcrumb')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Beranda</a></li>
    <li class="breadcrumb-item"><a href="{{route('dokumen.index')}}">DMS</a></li>
    <li class="breadcrumb-item active" aria-current="page"> Edit </li>
  </ol>
</nav>
@stop

@section('contents')
<hr>
<div class="card">
    <div class="card-header text-center font-weight-bold">
        Edit {{ $dokumen->file }}
    </div>
    <div class="card-body">
        <form onsubmit="Notiflix.Loading.Dots('Uploading...');" name="edit-dokumen-form" id="edit-dokumen-form" method="post" action="{{ route('dokumen.update', $dokumen->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="file">File</label>
            <div class="input-group">
                <label class="input-group-btn">
                    <span class="btn btn-info">
                        Browse&hellip;<input id="file" class="form-control" name="file" accept="application/pdf" type="file" style="display: none;" multiple>
                    </span>
                </label>
                <input id="fileinputlabelid" data-validate="true" name="FileName" data-title-pdf="Dokumen" type="text" class="form-control" value="{{ $dokumen->file }}" data-error="Pilih file PDF" data-required="true" readonly required>
                <label id="preview" class="input-group-btn {{ ($dokumen->file) ? '' : 'd-none' }}">
                    <span class="btn btn-secondary btn-pdf" data-toggle="modal" data-target="#previewpdf" title='View PDF'>
                        <i class='fa fa-file-pdf-o' style='color: #fff'></i>
                    </span>
                    <span class="btn btn-danger" id="removeFile" title='Remove PDF' style="">
                        <i class='fa fa-trash-o'></i>
                    </span>
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="nomor">Nomor</label>
            <input type="text" id="nomor" name="nomor" class="form-control" value="{{ $dokumen->nomor }}" placeholder="Nomor Dokumen"  required="">
        </div>
        <div class="form-group">
            <label for="tahun">Tahun</label>
            {!! Form::selectYear('tahun', 1945, now()->year, $dokumen->tahun, ['class' => 'form-control select2']) !!}
        </div>
        <div class="form-group">
            <label for="perihal">Perihal</label>
            <textarea class="form-control" id="perihal" name="perihal" rows="3">{{ $dokumen->perihal }}</textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Simpan</button>
        <div class="float-right">
            <a class="btn btn-secondary" href="{{ route('dokumen.index') }}"> Batal</a>
        </div>
        </form>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="previewpdf">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Preview PDF</h4>
                <span></span>
            </div>
            <div class="modal-body">
                <object id="pdfbox" type="application/pdf" data=""></object>
            </div>
        </div>
    </div>
</div>
@stop