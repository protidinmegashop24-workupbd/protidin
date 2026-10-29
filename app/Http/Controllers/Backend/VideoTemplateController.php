<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Library\VideoRenderer;
use App\Models\VideoMakerSetting;
use App\Models\VideoTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VideoTemplateController extends Controller
{
    public function index()
    {
        $templates = VideoTemplate::orderByDesc('id')->get();
        $ttsSetting = VideoMakerSetting::first();
        return view('backend.pages.video-templates.index', compact('templates', 'ttsSetting'));
    }

    public function saveTtsSettings(Request $request)
    {
        $request->validate([
            'google_tts_api_key' => 'nullable|string|max:255',
        ]);

        $setting = VideoMakerSetting::first() ?: new VideoMakerSetting();
        $setting->google_tts_api_key = $request->google_tts_api_key ?: null;
        $setting->save();

        return redirect()->back()->with('success', 'Voice settings saved.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'background' => 'required|mimes:mp4,mov,webm|max:51200',
            'duration_seconds' => 'required|integer|min:3|max:60',
            'font_size' => 'required|integer|min:20|max:120',
            'text_color' => 'required|string|max:20',
            'cost' => 'required|numeric|min:0',
        ]);

        $uploadDir = 'uploads/video-templates/';
        if (!is_dir(base_path($uploadDir))) {
            mkdir(base_path($uploadDir), 0755, true);
        }

        $file = $request->file('background');
        $rawFilename = Str::random(20) . '_raw.' . strtolower($file->getClientOriginalExtension());
        $file->move(base_path($uploadDir), $rawFilename);
        $rawFullPath = base_path($uploadDir . $rawFilename);

        // Re-encode into a known-clean baseline right away -- whatever odd
        // frame rate/pixel format the uploaded file carries gets fixed
        // once here instead of breaking every future render that uses
        // this template (see VideoRenderer::normalizeBackground()).
        $cleanFilename = Str::random(20) . '.mp4';
        $cleanFullPath = base_path($uploadDir . $cleanFilename);
        try {
            (new VideoRenderer())->normalizeBackground($rawFullPath, $cleanFullPath);
        } catch (\Throwable $e) {
            @unlink($rawFullPath);
            return redirect()->back()->withInput()->with('error', 'Could not process this video file -- it may be corrupted or in an unsupported format. ' . $e->getMessage());
        }
        @unlink($rawFullPath);

        $template = new VideoTemplate();
        $template->name = $request->name;
        $template->category = $request->category;
        $template->background_path = $uploadDir . $cleanFilename;
        $template->duration_seconds = $request->duration_seconds;
        $template->font_size = $request->font_size;
        $template->text_color = $request->text_color;
        $template->cost = $request->cost;
        $template->enabled = $request->boolean('enabled');
        $template->save();

        // Best-effort thumbnail -- the admin list/user gallery still works
        // fine without one (falls back to a plain card), so a failure here
        // never blocks saving the template itself.
        try {
            $thumbName = pathinfo($cleanFilename, PATHINFO_FILENAME) . '.jpg';
            $thumbFull = base_path($uploadDir . $thumbName);
            if ((new VideoRenderer())->generateThumbnail(base_path($template->background_path), $thumbFull)) {
                $template->thumbnail_path = $uploadDir . $thumbName;
                $template->save();
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('video-template-thumbnail-failed', ['error' => $e->getMessage()]);
        }

        return redirect()->back()->with('success', 'Video template added.');
    }

    public function update(Request $request, $id)
    {
        $template = VideoTemplate::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'duration_seconds' => 'required|integer|min:3|max:60',
            'font_size' => 'required|integer|min:20|max:120',
            'text_color' => 'required|string|max:20',
            'cost' => 'required|numeric|min:0',
        ]);

        $template->name = $request->name;
        $template->category = $request->category;
        $template->duration_seconds = $request->duration_seconds;
        $template->font_size = $request->font_size;
        $template->text_color = $request->text_color;
        $template->cost = $request->cost;
        $template->enabled = $request->boolean('enabled');
        $template->save();

        return redirect()->back()->with('success', 'Video template updated.');
    }

    public function destroy($id)
    {
        $template = VideoTemplate::findOrFail($id);

        if ($template->background_path && file_exists(base_path($template->background_path))) {
            @unlink(base_path($template->background_path));
        }
        if ($template->thumbnail_path && file_exists(base_path($template->thumbnail_path))) {
            @unlink(base_path($template->thumbnail_path));
        }

        $template->delete();

        return redirect()->back()->with('success', 'Video template deleted.');
    }
}
