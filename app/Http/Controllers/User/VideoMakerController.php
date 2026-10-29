<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Library\QuranAudioMatcher;
use App\Models\User;
use App\Models\UserVideo;
use App\Models\VideoTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoMakerController extends Controller
{
    protected const MAX_AYAHS_PER_VIDEO = 20;

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
        $reciters = QuranAudioMatcher::RECITERS;
        return view('user.pages.video-maker.create', compact('template', 'title', 'reciters'));
    }

    /** JSON list of surahs (number, names, ayah count) for the picker's Surah dropdown. */
    public function surahs()
    {
        return response()->json((new QuranAudioMatcher())->surahList());
    }

    /** JSON preview of the Arabic text for a chosen surah/ayah range, before submitting. */
    public function ayahPreview(Request $request)
    {
        $request->validate([
            'surah' => 'required|integer|min:1|max:114',
            'from' => 'required|integer|min:1',
            'to' => 'required|integer|min:1',
        ]);

        if ($request->to < $request->from) {
            return response()->json(['error' => 'Invalid ayah range.'], 422);
        }
        if (($request->to - $request->from + 1) > self::MAX_AYAHS_PER_VIDEO) {
            return response()->json(['error' => 'Please pick ' . self::MAX_AYAHS_PER_VIDEO . ' ayahs or fewer for one video.'], 422);
        }

        $text = (new QuranAudioMatcher())->ayahRangeText((int) $request->surah, (int) $request->from, (int) $request->to);
        if ($text === null) {
            return response()->json(['error' => 'Ayah data not available yet -- ask admin to run the Quran setup.'], 404);
        }

        return response()->json(['text' => $text]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'template_id' => 'required|integer',
            'source_type' => 'required|in:custom_text,quran_ayah',
        ]);

        $template = VideoTemplate::where('enabled', true)->find($request->template_id);
        if (!$template) {
            return redirect()->back()->with('error', 'Selected template is no longer available.');
        }

        $matcher = new QuranAudioMatcher();
        $quranSurah = null;
        $quranFrom = null;
        $quranTo = null;
        $reciter = null;

        if ($request->source_type === 'quran_ayah') {
            $request->validate([
                'quran_surah' => 'required|integer|min:1|max:114',
                'quran_ayah_from' => 'required|integer|min:1',
                'quran_ayah_to' => 'required|integer|min:1',
                'reciter' => 'required|in:' . implode(',', array_keys(QuranAudioMatcher::RECITERS)),
            ]);

            $quranSurah = (int) $request->quran_surah;
            $quranFrom = (int) $request->quran_ayah_from;
            $quranTo = (int) $request->quran_ayah_to;
            $reciter = $request->reciter;

            if ($quranTo < $quranFrom) {
                return redirect()->back()->withInput()->with('error', 'Invalid ayah range.');
            }
            if (($quranTo - $quranFrom + 1) > self::MAX_AYAHS_PER_VIDEO) {
                return redirect()->back()->withInput()->with('error', 'Please pick ' . self::MAX_AYAHS_PER_VIDEO . ' ayahs or fewer for one video.');
            }

            $textContent = $matcher->ayahRangeText($quranSurah, $quranFrom, $quranTo);
            if ($textContent === null) {
                return redirect()->back()->withInput()->with('error', 'That ayah range is not available. Ask admin to run the Quran setup, or check the ayah numbers.');
            }
        } else {
            $request->validate([
                'text_content' => 'required|string|max:400',
            ]);
            $textContent = $request->text_content;
        }

        $cost = (float) $template->cost;

        if ((float) Auth::user()->earning_balance < $cost) {
            return redirect()->back()->withInput()->with('error', 'You do not have enough balance to make this video.');
        }

        $user = User::find(Auth::id());
        $user->earning_balance = (float) $user->earning_balance - $cost;
        $user->save();

        $video = new UserVideo();
        $video->user_id = Auth::id();
        $video->template_id = $template->id;
        $video->text_content = $textContent;
        $video->cost = $cost;
        $video->status = 'pending';
        $video->source_type = $request->source_type;
        $video->quran_surah = $quranSurah;
        $video->quran_ayah_from = $quranFrom;
        $video->quran_ayah_to = $quranTo;
        $video->reciter = $reciter;
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
