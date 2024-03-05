<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    @yield('styles') 
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <a class="dropdown-item"
                                href="{{ route('frontend.profile.index') }}">{{ __('My profile') }}</a>

                            <a class="dropdown-item" href="{{ route('frontend.albums.index') }}">
                                {{ trans('cruds.album.title') }}
                            </a>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>


                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @if (session('message'))
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($errors->count() > 0)
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <ul class="list-unstyled mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<script src="{{ asset('js/main.js') }}"></script>
@yield('scripts')
<script>
    function move_to_another_album() {
        $('#album-to-move').css('display', 'flex')
        $('#album_to_move_id').prop('required', true)
    }
    function deleteall() { 
        $('#album_to_move_id').prop('required', false)
    }

    //perevent submittig multiple times
    $("body").on("submit", "form", function() {
        $(this).submit(function() {
            return false;
        });
        return true;
    });

    $(document).on('click', '.scrollable-container small', function() {

        // Get the text content of the clicked <span>
        var spanText = $(this).text();

        // Replace the <span> with an input field
        var inputField = $('<input type="text" data-id="' + $(this).data('id') +
            '" class="editInput form-control" style="display:inline;width: 100%;">').val(spanText);
        $(this).replaceWith(inputField);

        // Focus on the input field
        inputField.focus();
    });

    // Blur event on the input field
    $(document).on('blur', '.editInput', function() {
        // Get the input value
        var inputValue = $(this).val();

        // Replace the input field with a new <small> containing the input value
        var newSpan = $('<small>').text(inputValue);
        $(this).replaceWith(newSpan);

        // ajax call to save the name
        var mediaId = $(this).data('id');

        $.post('{{ route('frontend.albums.update_pricture_name') }}', {
            _token: '{{ csrf_token() }}',
            id: mediaId,
            name: inputValue
        }, function() {});
    });

    // Keydown event on the input field
    $(document).on('keydown', '.editInput', function(e) {
        // Check if the Enter key is pressed
        if (e.which === 13) {
            // Trigger the blur event to handle the replacement
            $(this).blur();
        }
    });
</script>

</html>
