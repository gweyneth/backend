<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller

{
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);

        return view('pages.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('pages.posts.create');
    }

    /**
     * Menyimpan post baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:published,draft',
        ]);

        $validatedData['user_id'] = Auth::id();
        $validatedData['slug'] = Str::slug($request->title);

        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('uploads/blog_images', 'public');
        }

        Post::create($validatedData);

        return redirect()->route('posts.index')->with('success', 'Postingan baru berhasil dibuat.');
    }

    public function edit(Post $post)
    {
        return view('pages.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:published,draft',
        ]);

        if ($request->title !== $post->title) {
            $validatedData['slug'] = Str::slug($request->title);
        }

        if ($request->hasFile('image')) {
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            $validatedData['image'] = $request->file('image')->store('uploads/blog_images', 'public');
        }

        $post->update($validatedData);

        return redirect()->route('posts.index')->with('success', 'Postingan berhasil diperbarui.');
    }

    public function publish(Post $post)
    {
        try {
            $post->status = 'published';
            $post->save();
            return response()->json(['success' => 'Postingan berhasil dipublikasikan!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mempublikasikan postingan.'], 500);
        }
    }

    /**
     * Menghapus post dari database.
     */
    public function destroy(Post $post)
    {
        try {
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            $post->delete();
            return response()->json(['success' => 'Postingan berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus data.'], 500);
        }
    }
}
