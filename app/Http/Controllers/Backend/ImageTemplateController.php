<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ImageTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImageTemplateController extends Controller
{
    public function index()
    {
        $templates = ImageTemplate::orderByDesc('id')->get();
        return view('backend.pages.image-templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'background' => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $uploadDir = 'uploads/image-templates/';
        if (!is_dir(base_path($uploadDir))) {
            mkdir(base_path($uploadDir), 0755, true);
        }

        $file = $request->file('background');
        $filename = Str::random(20) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move(base_path($uploadDir), $filename);

        $template = new ImageTemplate();
        $template->name = $request->name;
        $template->background_path = $uploadDir . $filename;
        $template->enabled = $request->boolean('enabled');
        $template->save();

        return redirect()->back()->with('success', 'Image template added.');
    }

    public function update(Request $request, $id)
    {
        $template = ImageTemplate::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $template->name = $request->name;
        $template->enabled = $request->boolean('enabled');

        $file = $request->file('background');
        if ($file) {
            $request->validate(['background' => 'image|mimes:jpg,jpeg,png,webp|max:10240']);

            $uploadDir = 'uploads/image-templates/';
            if ($template->background_path && file_exists(base_path($template->background_path))) {
                @unlink(base_path($template->background_path));
            }
            $filename = Str::random(20) . '.' . strtolower($file->getClientOriginalExtension());
            $file->move(base_path($uploadDir), $filename);
            $template->background_path = $uploadDir . $filename;
        }

        $template->save();

        return redirect()->back()->with('success', 'Image template updated.');
    }

    public function destroy($id)
    {
        $template = ImageTemplate::findOrFail($id);

        if ($template->background_path && file_exists(base_path($template->background_path))) {
            @unlink(base_path($template->background_path));
        }

        $template->delete();

        return redirect()->back()->with('success', 'Image template deleted.');
    }
}
