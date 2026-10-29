<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin\News;
use App\Models\Admin\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $website = Website::latest()->first();
        $blogs = News::orderBy('id', 'DESC')->get();

        return view('backend.pages.blog.index', compact('website', 'blogs'));
    }

    // Guarantees a unique slug even if two posts share the same title.
    private function uniqueSlug($title, $ignoreId = null)
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (News::where('slug', $slug)->when($ignoreId, function ($q) use ($ignoreId) {
            $q->where('id', '!=', $ignoreId);
        })->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'details' => 'required|string',
        ]);

        $blog = new News();
        $blog->title = $request->title;
        $blog->slug = $this->uniqueSlug($request->title);
        $blog->details = $request->details;
        $blog->news_date = now()->toDateString();
        $blog->news_time = now()->toTimeString();
        $blog->status = $request->has('status') ? 1 : 0;

        $image = $request->file('feature_image');
        if ($image) {
            $image_full_name = Str::random(20) . '.' . strtolower($image->getClientOriginalExtension());
            $upload_path = 'backend/img/blog/';
            $image->move($upload_path, $image_full_name);
            $blog->feature_image = $upload_path . $image_full_name;
        }

        $blog->save();

        return redirect()->back()->with('message', 'Blog post added successfully');
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
        $request->validate([
            'title'   => 'required|string|max:255',
            'details' => 'required|string',
        ]);

        $blog = News::findOrFail($id);

        if ($blog->title !== $request->title) {
            $blog->slug = $this->uniqueSlug($request->title, $blog->id);
        }

        $blog->title = $request->title;
        $blog->details = $request->details;
        $blog->status = $request->has('status') ? 1 : 0;

        $image = $request->file('feature_image');
        if ($image) {
            if ($blog->feature_image && file_exists($blog->feature_image)) {
                unlink($blog->feature_image);
            }
            $image_full_name = Str::random(20) . '.' . strtolower($image->getClientOriginalExtension());
            $upload_path = 'backend/img/blog/';
            $image->move($upload_path, $image_full_name);
            $blog->feature_image = $upload_path . $image_full_name;
        }

        $blog->save();

        return redirect()->back()->with('message', 'Blog post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blog = News::findOrFail($id);
        if ($blog->feature_image && file_exists($blog->feature_image)) {
            unlink($blog->feature_image);
        }
        $blog->delete();

        return redirect()->back()->with('message', 'Blog post deleted successfully');
    }
}
