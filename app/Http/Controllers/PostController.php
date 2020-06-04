<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{
    //
    public function index(){
        $posts = Post::all();
        return view('admin.posts.index', ['posts' => $posts]);
    }
    public function show(Post $post){
        return view('blog-post', ['post' => $post]);
    }
    public function create(){
        return view('admin.posts.create');
    }
    public function store(){
        $inputs = request()->validate([
            'title' => 'required|min:8|max:255',
            'post_image' => 'mimes:jpeg,png,gif',
            'body' => 'required'
        ]);
        if (request('post_image')){
            $inputs['post_image'] = request('post_image')->store('images');
        }
        auth()->user()->posts()->create($inputs);
        Session::flash('message', 'Post Created Successfully');
        return redirect()->route('post.index');
    }
    public function destroy(Post $post ,Request $request){
        $post->delete();

//        Session::flash('message', 'Post deleted Successfully');
//        OR
        $request->session()->flash('message', 'Post deleted Successfully');

        return back();
    }
    public function edit(Post $post){
        return view('admin.posts.edit', ['post' => $post]);
    }
    public function update(Post $post){
        $inputs = request()->validate([
            'title' => 'required|min:8|max:255',
            'post_image' => 'mimes:jpeg,png,gif',
            'body' => 'required'
        ]);
        if (request('post_image')){
            $inputs['post_image'] = request('post_image')->store('images');
            $post->post_image = $inputs['post_image'];
        }
        $post->title = $inputs['title'];
        $post->body = $inputs['body'];

//        auth()->user()->posts()->save($post); Saves and set the publiser to user

        $post->update();

        Session::flash('message', 'Post Updated Successfully');

        return redirect()->route('post.index');
    }
}
