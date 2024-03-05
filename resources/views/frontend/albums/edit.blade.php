@extends('layouts.frontend')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-5"> 
                        <div class="card">
                            <div class="card-header">
                                {{ trans('global.edit') }} {{ trans('cruds.album.title_singular') }}
                            </div>

                            <div class="card-body">
                                <form method="POST" action="{{ route('frontend.albums.update', [$album->id]) }}"
                                    enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf
                                    <div class="form-group">
                                        <label class="required"
                                            for="name">{{ trans('cruds.album.fields.name') }}</label>
                                        <input class="form-control" type="text" name="name" id="name"
                                            value="{{ old('name', $album->name) }}" required>
                                        @if ($errors->has('name'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('name') }}
                                            </div>
                                        @endif
                                        <span class="help-block">{{ trans('cruds.album.fields.name_helper') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="pictures">Add More Pictures</label>
                                        <div class="needsclick dropzone" id="pictures-dropzone">
                                        </div>
                                        @if ($errors->has('pictures'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('pictures') }}
                                            </div>
                                        @endif
                                        <span class="help-block">{{ trans('cruds.album.fields.pictures_helper') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-danger" type="submit">
                                            {{ trans('global.update') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header">
                                Pictures Of Album
                            </div>

                            <div class="card-body">
                                <form action="{{ route('frontend.albums.update_prictures') }}" method="POST">
                                    @csrf 
                                    <div class="row text-center">
                                        @forelse ($album->pictures as $key => $media)
                                            <div class="col-md-4 mb-5">
                                                <a href="{{ $media->getUrl() }}" target="_blank"  style="display: inline-block">
                                                    <img class="rounded img-fluid"  src="@if($media->hasGeneratedConversion('preview')) {{ $media->getUrl('preview') }} @else {{ $media->getUrl() }}  @endif">
                                                </a>
                                                <br>
                                                <a style="color:red" href="{{ route('frontend.albums.remove_picture',$media->id) }}" onclick="return confirm('{{ trans('global.areYouSure') }}');">Delete Image</a>
                                                <input type="text" class="form-control m-2" name="picture_name[{{ $media->id }}]"
                                                    value="{{ $media->getCustomProperty('picture_name') }}">
                                            </div>
                                        @empty 
                                            <b class="m-4">No pictures added yet!</b>
                                        @endforelse
                                    </div>
                                    @if($album->pictures->count() > 0)
                                        <div class="form-group"> 
                                            <input type="submit" class="btn btn-info btn-block"  value="Update">
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var uploadedPicturesMap = {}
        Dropzone.options.picturesDropzone = {
            url: '{{ route('frontend.albums.storeMedia') }}',
            maxFilesize: 5, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 5,
                width: 4096,
                height: 4096
            },
            success: function(file, response) {
                $('form').append('<input type="hidden" name="pictures[]" value="' + response.name + '">')
                uploadedPicturesMap[file.name] = response.name
            },
            removedfile: function(file) {
                console.log(file)
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedPicturesMap[file.name]
                }
                $('form').find('input[name="pictures[]"][value="' + name + '"]').remove()
            },
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
@endsection
