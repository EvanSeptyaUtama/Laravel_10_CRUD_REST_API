<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//import Resource "PostResource"
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(5);
        //true = status
        //'List Data Post' = message
        //$post = resource
        return new PostResource(true, 'List Data Post', $posts);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,giv,svg|max:3048',
            'title' => 'required',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('storage/Posts'), $filename);
            $data['image'] = $filename;
        }
        $post = Post::create([
            'image' => $filename,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return new PostResource(true, 'Data post berhasil ditambahkan', $post);
    }

    public function show($id)
    {
        $post = Post::find($id);
        return new PostResource(true, 'Detail Data Post', $post);
    }
    public function update(Request $request, $id)
    {
        //Membuat validasi atau aturan
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,giv,svg|max:3048',
            'title' => 'required',
            'content' => 'required'
        ]);

        //Dicek jika pada saat validasi data tidak sesuai aturan maka akan
        // dikembalikan ke error 422 (entitas tidak diproses)
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //Mencari Id
        $post = Post::find($id);
        //check if image is not empty

        if ($request->hasFile('image')) {
            if ($request->file('image')) {
                $file = $request->file('image');
                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('storage/Posts'), $filename);
                $data['image'] = $filename;
            }
            Storage::delete('public/posts/' . basename($post->image));

            //update post with new image
            $post->update([
                'image'     => $filename,
                'title'     => $request->title,
                'content'   => $request->content,
            ]);
        } else {

            //update post without image
            $post->update([
                'title'     => $request->title,
                'content'   => $request->content,
            ]);
        }
        return new PostResource(true, 'Data Post Berhasil Diubah!', $post);
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        Storage::delete('public/posts/' . basename($post->image));
        $post->delete();
        return new PostResource(true, 'Data Post Berhasil Dihapus!', $post);
    }
}
