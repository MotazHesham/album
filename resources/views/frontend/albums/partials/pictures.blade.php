<div class="card m-5 text-center">
    <div class="p-3" style="display: flex;justify-content:space-between">
        <div><b>{{ $album->name }}</b></div>
        <div>
            <a href="{{ route('frontend.albums.edit', $album->id) }}" class="edit-album" >
                <i class="fa-solid fa-pen-to-square"></i>
            </a>

            <a href="#ex{{ $album->id }}" class="delete-album" rel="modal:open">
                <i class="fa-solid fa-trash"></i>
            </a>

            <div id="ex{{ $album->id }}" class="modal text-center">

                @if($album->pictures->count() > 0)
                    <p>Album <b>{{ $album->name }}</b> has Pictures !!! </p>
                @else 
                    <p> Are You Sure To Delete <b>{{ $album->name }}</b> !!! </p>
                @endif

                <form action="{{ route('frontend.albums.destroy', $album->id) }}" method="POST" style="display: inline-block;">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @if($album->pictures->count() > 0) 
                        <input type="submit" class="btn btn-danger" name="delete" onclick="deleteall()" value="Delete all Picture">
                    
                        <input class="btn btn-info" type="button" value="Move To Another Album" onclick="move_to_another_album()">

                        <div class="mt-2" style="display: none" id="album-to-move">  
                            <select name="album_to_move_id" class="form-control" id="album_to_move_id">
                                <option value="" selected disabled> Choose The album to Move</option>
                                @foreach ($albums as $album0)
                                    @if ($album0->id != $album->id)
                                        <option value="{{ $album0->id }}">{{ $album0->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>

                            <input type="submit" class="btn btn-success" name="move" value="Move">
                        </div>
                    @else 
                        <input type="submit" class="btn btn-danger" name="delete" value="Yes, Delete"> 
                        <a href="#close-modal" rel="modal:close" class="btn btn-secondary">Close</a>
                    @endif
                </form>
            </div>


        </div>
    </div>
    <div class="scrollable-container" id="scrollable-container">
        @forelse ($album->pictures as $key => $media)
            @if($media->hasGeneratedConversion('preview2')) 
                <div class="hover-image" title="{{ $media->getCustomProperty('picture_name') }}">
                    <img src="{{ $media->getUrl('preview2') }}"  alt="" style=" width: 100%;height: 100%;object-fit: cover;transition: .5s ease; "> 
                    <div class="overlay-img">
                        <small data-id="{{ $media->id }}">{{ $media->getCustomProperty('picture_name') }}</small>
                        <br>
                        <a title="View" target="_blanc" href="{{$media->getUrl()}}" class="picture-view">
                            <i class="fa-solid fa-magnifying-glass-plus" ></i> 
                        </a> 
                        <a  title="Delete" class="picture-delete" href="{{ route('frontend.albums.remove_picture',$media->id) }}" onclick="return confirm('{{ trans('global.areYouSure') }}');">
                            <i class="fa-solid fa-trash"></i>
                        </a> 
                    </div>
                </div>
            @endif
        @empty 
            <div class="empty-album"> 
                <b>No Pictures Added Yet! </b>
                <br>
                <a class="btn btn-info" href="{{ route('frontend.albums.edit', $album->id) }}">Add Pictures +</a>
            </div>
        @endforelse
    </div>

</div>

<script>
    $(document).ready(function() {
        // ------------- Scrolling wheels horizontally ------------
        var scrollableContainer = document.getElementById("scrollable-container");
        scrollableContainer.addEventListener("wheel", function(e) {
            if (e.deltaY > 0) scrollableContainer.scrollLeft += 100;
            else scrollableContainer.scrollLeft -= 100;
        });
    });
</script>
