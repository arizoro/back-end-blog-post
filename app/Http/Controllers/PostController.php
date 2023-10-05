<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostDetailResource;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        // return response()->json(['data' => $posts]);
        return PostDetailResource::collection($posts->loadMissing(['author:id,username','comments:id,post_id,user_id,comments_content,created_at' ]));
    }

    public function show($id)
    {
        $post = Post::with('author:id,username')->find($id);
        return new PostDetailResource($post->loadMissing(['author:id,username','comments:id,post_id,user_id,comments_content,created_at' ]));
    }

    public function store(Request $request)
    {
        $validateData =$request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required',
            'image' => 'image|file|max:2048'
        ]);

        // if($request->file){
            //     $fileName =  $this->generateRandomString();
            //     $extension = $request->file->extension();
            
            //     Storage::putFileAs('image', $request->file, $fileName.'.'.$extension);
            // }
            
        $fileName = null;

        if($request->file){
            $request->validate([
                'image' => 'image|file|max:2048'
            ]);
            $fileName = $request->file->hashName();
            $validateData['image']= $request->file->store('image');
        }

        $validateData['image'] = $fileName;
        
        $validateData['author_id'] = Auth::user()->id ;
        $post = Post::create($validateData);

        return new PostDetailResource($post->loadMissing('author:id,username'));
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required',
        ]);

        $post = Post::find($id);
        $post->update($request->all());
        return new PostDetailResource($post->loadMissing('author:id,username'));
    }

    public function destroy($id){
        $post= Post::find($id);
        $post->delete();

        return response()->json('data deleted');
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
