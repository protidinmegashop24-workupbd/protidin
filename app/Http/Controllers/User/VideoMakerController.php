<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Library\HadithMatcher;
use App\Library\QuranAudioMatcher;
use App\Models\User;
use App\Models\UserVideo;
use App\Models\VideoMakerSetting;
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
        $hadithImageCost = (float) (VideoMakerSetting::first()->hadith_image_cost ?? 0.01);
        return view('user.pages.video-maker.create', compact('template', 'title', 'reciters', 'hadithImageCost'));
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

    /** JSON list of hadith collections (key, Bengali name, how many hadiths) for the picker's dropdown. */
    public function hadithCollections()
    {
        return response()->json((new HadithMatcher())->collectionList());
    }

    /** JSON preview (Arabic + Bengali translation + reference) for one picked hadith, before submitting. */
    public function hadithPreview(Request $request)
    {
        $request->validate([
            'collection' => 'required|string',
            'number' => 'required|integer|min:1',
        ]);

        $hadith = (new HadithMatcher())->getHadith($request->collection, (int) $request->number);
        if ($hadith === null) {
            return response()->json(['error' => 'এই হাদিসটা পাওয়া যায়নি -- নম্বর ঠিক আছে কিনা দেখুন, অথবা অ্যাডমিনকে হাদিস সেটআপ চালাতে বলুন।'], 404);
        }

        return response()->json($hadith);
    }

    public function store(Request $request)
    {
        $request->validate([
            'template_id' => 'required|integer',
            'source_type' => 'required|in:hadith_image,quran_ayah',
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
        $arabicText = null;
        $translationText = null;
        $referenceText = null;

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

            $cost = (float) $template->cost;
        } else {
            $request->validate([
                'arabic_text' => 'required|string|max:1000',
                'translation_text' => 'nullable|string|max:1000',
                'reference_text' => 'nullable|string|max:255',
            ]);

            $arabicText = $request->arabic_text;
            $translationText = $request->translation_text;
            $referenceText = $request->reference_text;
            $textContent = trim($arabicText . "\n" . $translationText);

            $cost = (float) (VideoMakerSetting::first()->hadith_image_cost ?? 0.01);
        }

        if ((float) Auth::user()->earning_balance < $cost) {
            return redirect()->back()->withInput()->with('error', 'You do not have enough balance to make this.');
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
        $video->arabic_text = $arabicText;
        $video->translation_text = $translationText;
        $video->reference_text = $referenceText;
        $video->save();

        $message = $request->source_type === 'hadith_image'
            ? 'Your image is being created. This usually takes a minute or two -- check back here.'
            : 'Your video is being created. This usually takes a few minutes -- check back here.';

        return redirect()->route('user.video-maker.my-videos')->with('message', $message);
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

        $ext = pathinfo($video->output_path, PATHINFO_EXTENSION) ?: 'mp4';
        $label = $video->source_type === 'hadith_image' ? 'hadith-image' : 'video';

        return response()->download($fullPath, $label . '-' . $video->id . '.' . $ext);
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
