<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Library\VideoRenderer;
use App\Models\VideoTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VideoTemplateController extends Controller
{
    public function index()
    {
        $templates = VideoTemplate::orderByDesc('id')->get();
        return view('backend.pages.video-templates.index', compact('templates'));
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
        $filename = Str::random(20) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move(base_path($uploadDir), $filename);

        $template = new VideoTemplate();
        $template->name = $request->name;
        $template->category = $request->category;
        $template->background_path = $uploadDir . $filename;
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
            $thumbName = pathinfo($filename, PATHINFO_FILENAME) . '.jpg';
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
