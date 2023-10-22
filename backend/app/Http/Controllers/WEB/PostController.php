<?php

namespace App\Http\Controllers\WEB;

use App\Data\PostData;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class PostController extends Controller
{
    /**
     * Show the application posts index.
     */
    public function index()
    {
        $posts = Post::with('category', 'media')->latest()->paginate(10);
        return view('posts-index', [
            'posts' => $posts
        ]);
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        return view('posts-create', [
            'categories' => Category::all(),
            'tags' => Tag::all()
        ]);
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(PostRequest $postRequest)
    {
        $post = Post::create($postRequest->validated());

        $post->tags()->sync($postRequest->input('tags'));

        try {
            $file = request()->file('thumbnail');
            $fileName = "{$post->slug}.{$file->getClientOriginalExtension()}";

            $post->addMedia($file)
                ->usingFileName($fileName)
                ->toMediaCollection('thumbnail');
        } catch (\Exception $e) {
            dd($e);
        }

        return redirect()->route('posts.index')->with('success', __('posts.created'));
    }

    public function show(Post $post)
    {
    }

    /**
     * Display the specified resource edit form.
     */
    public function edit(Post $post)
    {
        $post->load('tags');
        return view('posts-edit', [
            'post' => $post,
            'categories' => Category::all(),
            'tags' => Tag::all()
        ]);
    }

    public function update(PostRequest $request, Post $post)
    {
        $post->update($request->validated());

        $post->tags()->sync($request->input('tags'));

        if ($request->hasFile('thumbnail')) {
            try {
                $file = request()->file('thumbnail');
                $fileName = "{$post->slug}.{$file->getClientOriginalExtension()}";

                $post->addMedia($file)
                    ->usingFileName($fileName)
                    ->toMediaCollection('thumbnail');
            } catch (\Exception $e) {
                dd($e);
            }
        }

        return redirect()
            ->route('posts.index')
            ->with('success', __('posts.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->media()->delete();
        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', __('posts.deleted'));
    }
}
