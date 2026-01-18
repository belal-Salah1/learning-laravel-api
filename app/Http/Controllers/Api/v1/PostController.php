<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()                                                                       
    {
        $user = request()->user();
        $posts= $user->posts()->paginate();
        return PostResource::collection($posts);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request) {
        $data = $request->validated();
        $data['author_id'] = request()->user()->id; // Simulate authenticated user ID
        $post = Post::create($data);
        return response()->json(new PostResource($post),201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // $post = Post::findOrFail($id);  
        // return response()->json($post)->header('test','test headerValue');
        $user = request()->user();
            // abort_if($post->author_id !== $user->id,403,'Access forbidden');
            abort_if($post->author_id !== Auth::id(),403,'Access forbidden');
         
        return response()->json(new PostResource($post));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|min:3',
            'body'=> ['required','string','min:2']

        ]);
        $post->update($data);
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {   
        abort_if($post->author_id !== Auth::id(),403,'Access forbidden');
        $post->delete();
        return response()-> noContent();    
    }


}
