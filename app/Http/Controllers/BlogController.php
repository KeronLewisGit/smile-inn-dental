<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $posts = Post::published()->when($request->filled('category'), fn ($q) => $q->where('category', $request->category))->paginate(12)->withQueryString();

        return view('site.blog', ['posts' => $posts, 'featured' => $request->filled('category') || $request->page > 1 ? null : Post::published()->where('featured', true)->first(), 'categories' => Post::CATEGORIES]);
    }

    public function show(Post $post): View
    {
        abort_unless($post->isPublished() || auth()->check(), 404);

        return view('site.post', ['post' => $post, 'related' => Post::published()->where('id', '!=', $post->id)->where('category', $post->category)->take(3)->get()]);
    }
}
