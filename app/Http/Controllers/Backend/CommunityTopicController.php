<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CommunityTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CommunityTopicController extends Controller
{
    public function index()
    {
        $topics = communityTopicsEnabled()
            ? CommunityTopic::withCount('posts')->orderBy('name')->get()
            : collect();

        return view('backend.pages.community-topics.index', compact('topics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:10',
        ]);

        $slug = Str::slug($request->name);
        $original = $slug;
        $i = 2;
        while (CommunityTopic::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $i;
            $i++;
        }

        CommunityTopic::create([
            'name' => $request->name,
            'slug' => $slug,
            'icon' => $request->icon ?: '💬',
        ]);

        return redirect()->back()->with('success', 'Topic added.');
    }

    public function update(Request $request, $id)
    {
        $topic = CommunityTopic::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:10',
        ]);

        $topic->update([
            'name' => $request->name,
            'icon' => $request->icon ?: $topic->icon,
        ]);

        return redirect()->back()->with('success', 'Topic updated.');
    }

    public function destroy($id)
    {
        // No FK constraint on community_post_topics, so the pivot rows
        // pointing at this topic need clearing by hand first, otherwise
        // they'd silently keep referencing a topic_id that no longer exists.
        DB::table('community_post_topics')->where('topic_id', $id)->delete();
        CommunityTopic::where('id', $id)->delete();

        return redirect()->back()->with('success', 'Topic deleted.');
    }
}
