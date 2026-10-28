<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserVideo;
use App\Models\VideoTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoMakerController extends Controller
{
    public function index()
    {
        $templates = VideoTemplate::where('enabled', true)->orderBy('category')->get()->groupBy('category');
        $title = 'Video Maker';
        return view('user.pages.video-maker.index', compact('templates', 'title'));
    }

    public function create($id)
    {
        $template = VideoTemplate::where('enabled', true)->findOrFail($id);
        $title = 'Make a Video';
        return view('user.pages.video-maker.create', compact('template', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'template_id' => 'required|integer',
            'text_content' => 'required|string|max:400',
        ]);

        $template = VideoTemplate::where('enabled', true)->find($request->template_id);
        if (!$template) {
            return redirect()->back()->with('error', 'Selected template is no longer available.');
        }

        $cost = (float) $template->cost;

        if ((float) Auth::user()->earning_balance < $cost) {
            return redirect()->back()->with('error', 'You do not have enough balance to make this video.');
        }

        $user = User::find(Auth::id());
        $user->earning_balance = (float) $user->earning_balance - $cost;
        $user->save();

        $video = new UserVideo();
        $video->user_id = Auth::id();
        $video->template_id = $template->id;
        $video->text_content = $request->text_content;
        $video->cost = $cost;
        $video->status = 'pending';
        $video->save();

        return redirect()->route('user.video-maker.my-videos')->with('message', 'Your video is being created. This usually takes a few minutes -- check back here.');
    }

    public function myVideos()
    {
        $videos = UserVideo::where('user_id', Auth::id())->with('template')->latest()->paginate(12);
        $title = 'My Videos';
        return view('user.pages.video-maker.my-videos', compact('videos', 'title'));
    }

    public function download($id)
    {
        $video = UserVideo::where('user_id', Auth::id())->where('status', 'completed')->findOrFail($id);

        $fullPath = base_path($video->output_path);
        if (!file_exists($fullPath)) {
            abort(404);
        }

        return response()->download($fullPath, 'video-' . $video->id . '.mp4');
    }

    public function destroy($id)
    {
        $video = UserVideo::where('user_id', Auth::id())
            ->whereIn('status', ['completed', 'failed'])
            ->findOrFail($id);

        if ($video->output_path && file_exists(base_path($video->output_path))) {
            @unlink(base_path($video->output_path));
        }

        $video->delete();

        return redirect()->back()->with('message', 'Video deleted.');
    }
}
