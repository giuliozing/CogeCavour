<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all posts
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // create a post
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:posts,slug'],
        ]);
        return Post::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // get a single post
        $post = Post::find($id);
        if ($post) {
            return $post;
        }
        return ['error' => true, 'message' => 'Post not found'];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // update a post
        $post = Post::find($id);
        if ($post) {
            $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'string', 'max:255', 'unique:posts,slug,'.$id],
            ]);
            $post->update($request->all());
            return $post;
        }
        return ['error' => true, 'message' => 'Post not found'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete a post
        $result = Post::destroy($id);
        if ($result) {
            return ['ok' => true, 'message' => 'Post deleted'];
        }
        return ['error' => true, 'message' => 'Post not found'];
    }

    /**
     * Increment like counter of the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function putLikes($id)
    {
        // increment likes in a post
        $post = Post::find($id);
        if ($post) {
            $post->likes++;
            $post->update();
            return ['id'=> $post->id, 'likes' => $post->likes];
        }
        return ['error' => true, 'message' => 'Post not found'];
    }

    /**
     * Increment like counter of the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getLikes($id)
    {
        // increment likes in a post
        $post = Post::find($id);
        if ($post) {
            return ['id'=> $post->id, 'likes' => $post->likes];
        }
        return ['error' => true, 'message' => 'Post not found'];
    }
}
