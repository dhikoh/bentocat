<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $articles = Article::with('author')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%");
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.articles.index', compact('articles', 'search'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'content_json' => 'required|json',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'status' => 'required|in:DRAFT,UNDER_REVIEW,PUBLISHED',
        ]);

        $slug = Str::slug($validated['title']);
        $count = Article::where('slug', 'like', $slug . '%')->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $contentArray = json_decode($validated['content_json'], true);

        Article::create([
            'author_id' => Auth::id(),
            'title' => $validated['title'],
            'slug' => $slug,
            'summary' => $validated['summary'],
            'content_json' => $contentArray,
            'seo_title' => $validated['seo_title'] ?: $validated['title'],
            'seo_description' => $validated['seo_description'] ?: $validated['summary'],
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'PUBLISHED' ? now() : null,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil disimpan.');
    }

    public function edit(Article $article)
    {
        // Decode blocks so the editor view can read them as structured JSON
        $blocksJson = json_encode($article->content_json);
        return view('admin.articles.edit', compact('article', 'blocksJson'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'content_json' => 'required|json',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'status' => 'required|in:DRAFT,UNDER_REVIEW,PUBLISHED',
        ]);

        $slug = Str::slug($validated['title']);
        $count = Article::where('slug', 'like', $slug . '%')->where('id', '!=', $article->id)->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $contentArray = json_decode($validated['content_json'], true);

        $article->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'summary' => $validated['summary'],
            'content_json' => $contentArray,
            'seo_title' => $validated['seo_title'] ?: $validated['title'],
            'seo_description' => $validated['seo_description'] ?: $validated['summary'],
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'PUBLISHED' && !$article->published_at ? now() : $article->published_at,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);

            return response()->json([
                'success' => true,
                'url' => asset('uploads/' . $filename)
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Image upload failed.'], 400);
    }
}
