@extends('layouts.frontend')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        {{ trans('global.create') }} {{ trans('cruds.album.title_singular') }}
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('frontend.albums.store') }}" enctype="multipart/form-data">
                            @method('POST')
                            @csrf
                            <div class="form-group">
                                <label class="required" for="name">{{ trans('cruds.album.fields.name') }}</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ old('name', '') }}" required>
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('name') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ trans('cruds.album.fields.name_helper') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="pictures">{{ trans('cruds.album.fields.pictures') }}</label>
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
                                <button class="btn btn-success" type="submit" name="save">
                                    Save
                                </button>
                                <button class="btn btn-info" type="submit" name="save_edit">
                                    Save & Edit pictures name
                                </button>
                            </div>
                        </form>
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
            init: function() {
                @if (isset($album) && $album->pictures)
                    var files = {!! json_encode($album->pictures) !!}
                    for (var i in files) {
                        var file = files[i]
                        this.options.addedfile.call(this, file)
                        this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                        file.previewElement.classList.add('dz-complete')
                        $('form').append('<input type="hidden" name="pictures[]" value="' + file.file_name + '">')
                    }
                @endif
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
