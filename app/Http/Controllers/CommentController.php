<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

class CommentController extends Controller
{
    public function store(Request $request) 
    {

        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comments_content' => 'required',
        ]);

        $request['user_id'] = Auth::user()->id;
        $comments = Comment::create($request->all());

        return new CommentResource($comments->loadMissing('comentator:id,username')) ;
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'comments_content' => 'required',
        ]);

        $request['user_id'] = auth()->user()->id;
        $comment = Comment::find($id);
        $comment->update($request->all());

        return new CommentResource($comment->loadMissing('comentator:id,username')) ;
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        $comment->delete();
        return response()->json('deleted success');
    }
}
