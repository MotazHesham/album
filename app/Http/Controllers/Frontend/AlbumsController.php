<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAlbumRequest;
use App\Http\Requests\StoreAlbumRequest;
use App\Http\Requests\UpdateAlbumRequest;
use App\Models\Album;
use App\Models\User; 
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class AlbumsController extends Controller
{
    use MediaUploadingTrait;  

    public function remove_picture($id) {
        $media = Media::findOrFail($id);
        $media->delete();
        return redirect()->back();
    }
    public function view_pictures(Request $request) {
        $album = Album::findOrFail($request->id);
        $albums = Album::where('id','!=',$request->id)->where('user_id',Auth::id())->get();  
        return view('frontend.albums.partials.pictures',compact('album','albums'));
    }
    public function update_pricture_name(Request $request) { 
        $media = Media::find($request->id);
        $media->setCustomProperty('picture_name', $request->name);
        $media->save(); 
        return 1;
    }
    public function update_prictures(Request $request) {
        foreach($request->picture_name as $key => $name){
            $media = Media::find($key);
            $media->setCustomProperty('picture_name', $name);
            $media->save();
        }  
        return redirect()->back();
    }

    public function index()
    { 
        $albums = Album::where('user_id',Auth::id())->with(['user', 'media'])->simplePaginate(25); 
        return view('frontend.albums.index', compact('albums'));
    }

    public function create()
    {  
        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.albums.create', compact('users'));
    }

    public function store(StoreAlbumRequest $request)
    {
        $validated_request = $request->all();
        $validated_request['user_id'] = Auth::id();
        $album = Album::create($validated_request);

        foreach ($request->input('pictures', []) as $file) {
            $album->addMedia(storage_path('tmp/uploads/' . basename($file)))
                    ->withCustomProperties(['picture_name' => substr($file,strpos($file,"_") + 1)])
                    ->toMediaCollection('pictures'); 
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $album->id]);
        }

        if($request->has('save_edit')){
            return redirect()->route('frontend.albums.edit',$album->id);
        }else{
            return redirect()->route('frontend.albums.index');
        }
    }

    public function edit(Album $album)
    {  
        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $album->load('user');

        return view('frontend.albums.edit', compact('album', 'users'));
    }

    public function update(UpdateAlbumRequest $request, Album $album)
    {
        $album->update($request->all());

        foreach ($request->input('pictures', []) as $file) {
            $album->addMedia(storage_path('tmp/uploads/' . basename($file)))
                    ->withCustomProperties(['picture_name' => substr($file,strpos($file,"_") + 1)])
                    ->toMediaCollection('pictures'); 
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $album->id]);
        }

        return redirect()->route('frontend.albums.edit',$album->id);
    }

    public function show(Album $album)
    { 

        $album->load('user');

        return view('frontend.albums.show', compact('album'));
    }

    public function destroy(Request $request, Album $album)
    {   
        if($request->has('move')){
            $album_to_move = Album::findOrfail($request->album_to_move_id);
            foreach($album->pictures as $media){ 
                $media = Media::find($media->id);
                if($media){
                    $media->model_id = $album_to_move->id;
                    $media->save();
                }
            }
        }else{ 
            if (count($album->pictures) > 0) {
                foreach ($album->pictures as $media) { 
                    $media->delete(); 
                }
            }
        }
        $album->delete();

        return redirect()->back();
    } 
}
