@extends('layouts.frontend')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="row mb-3">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.albums.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.album.title_singular') }}
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header text-center">
                        {{ trans('cruds.album.title_singular') }} {{ trans('global.list') }}
                    </div>

                    <div class="card-body">
                        <div id="album-container">
                            @foreach ($albums as $key => $album)
                                <div class="m-2 album-card" onclick="change_album(this,'{{$album->id}}')" >
                                    <div><h5>{{ $album->name }}</h5></div>
                                </div>
                            @endforeach
                        </div> 
                        <div class="text-center mt-3">
                            {{ $albums->links() }}
                        </div>
                        <div id="album-pictures"> 
                            {{-- ajax call --}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function change_album(element,albumId){
            $('.album-card').removeClass('active-album');
            $(element).addClass('active-album');
            
            $.post('{{ route('frontend.albums.view_pictures') }}', {_token:'{{ csrf_token() }}', id:albumId}, function(data){
                $('#album-pictures').html(data);
            });
        }
    </script>
@endsection
