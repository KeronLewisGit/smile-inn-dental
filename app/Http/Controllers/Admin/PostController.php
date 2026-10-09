<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesContent;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    use ManagesContent;

    public function index(Request $request): View
    {
        return view('admin.posts.index', ['posts' => Post::when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->q.'%'))->orderByDesc('published_at')->orderByDesc('id')->paginate(30)->withQueryString()]);
    }

    public function create(): View
    {
        return view('admin.posts.form', ['post' => new Post(['category' => 'Education', 'published_at' => now()])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['cover_image'] = $this->storeImage($request, 'cover_image', 'blog');
        $post = Post::create($data + ['author_id' => $request->user()->id]);

        return redirect()->route('admin.posts.edit', $post)->with('saved', 'Post saved.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.form', ['post' => $post]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $this->validated($request);
        $data['cover_image'] = $this->storeImage($request, 'cover_image', 'blog', $post->cover_image);
        $post->update($data);

        return redirect()->route('admin.posts.edit', $post)->with('saved', 'Post saved.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('saved', 'Post deleted.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        $data = $request->validate(['title' => ['required', 'string', 'max:200'], 'slug' => ['nullable', 'string', 'max:220', 'regex:/^[a-z0-9-]+$/'], 'category' => ['required', 'in:'.implode(',', Post::CATEGORIES)], 'excerpt' => ['nullable', 'string', 'max:500'], 'body' => ['nullable', 'string', 'max:100000'], 'cover_image' => ['nullable', 'image', 'max:5120'], 'published_at' => ['nullable', 'date']]);
        unset($data['cover_image']);
        $data['slug'] = $data['slug'] ?: null;

        return $data + ['featured' => $request->boolean('featured')];
    }
}
