<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') | DMS BAPENDA</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ asset('assets/notiflix/notiflix-2.7.0.min.css') }}" rel="stylesheet">
    <style>
        .card{
            display: block;
        }
    </style>
    @yield('styles')

</head>
<body>
    
    <div class="container mt-5">
        @if(session('messages'))
            <div class="alert alert-success alert-dismissible fade show">
                {!! session('messages') !!}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div id="navbar">
            @yield('breadcrumb')
            <div id="menu-atas" class="dropleft show pull-right" style="top: -55px; right: 10px;">
                <a class="btn btn-secondary dropdown-toggle btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="fa fa-cog"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="{{route('tag.index')}}">Tag</a>
                    <a class="dropdown-item" href="{{route('kategori.index')}}">Kategori Dokumen</a>
                    <a class="dropdown-item" href="{{route('jenis.index')}}">Jenis Dokumen</a>
                </div>
            </div>
        </div>
        <div class="card border-dark">
            <div class="card-header">
                <h3 class="card-title" style="margin-bottom: 0;">@yield('title') @yield('additional-cog')</h3>
            </div>
            <div class="card-body collapse">
            
            </div>
        </div>
        <br />
        @yield('contents')
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/notiflix/notiflix-2.7.0.min.js') }}"></script>
    @yield('scripts')

</body>
</html>