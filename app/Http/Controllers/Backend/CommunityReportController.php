<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CommunityReport;
use App\Models\feedpost;
use Illuminate\Http\Request;

class CommunityReportController extends Controller
{
    public function index()
    {
        $reports = CommunityReport::with(['post', 'reporter'])
            ->orderByRaw("status = 'pending' desc")
            ->latest()
            ->paginate(20);

        return view('backend.pages.community-reports.index', compact('reports'));
    }

    // Admin looked at the report and decided the post is fine -- clears it
    // off the queue without touching the post itself.
    public function dismiss($id)
    {
        $report = CommunityReport::findOrFail($id);
        $report->update(['status' => 'dismissed']);

        return redirect()->back()->with('success', 'Report dismissed.');
    }

    // Admin agreed the post is a problem -- hides it from the public feed
    // (same status field the rest of the Community feature already checks
    // for "approved"), and marks every report against it as actioned.
    public function hidePost($id)
    {
        $report = CommunityReport::findOrFail($id);
        $post = feedpost::find($report->post_id);
        if ($post) {
            $post->update(['status' => 'hidden']);
        }
        CommunityReport::where('post_id', $report->post_id)->update(['status' => 'post_hidden']);

        return redirect()->back()->with('success', 'Post hidden from the feed.');
    }
}
