<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use Illuminate\Http\Request;

class AdminSurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::latest()->paginate(20);
        return view('admin.surveys.index', compact('surveys'));
    }

    public function create()
    {
        return view('admin.surveys.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'reward' => 'required|numeric|min:0.0001',
            'topic' => 'required|in:islamic,general,bangladesh_gk,sports',
            'questions_per_attempt' => 'required|integer|min:5|max:50',
        ]);

        Survey::create([
            'title' => $request->title,
            'description' => $request->description,
            'reward' => $request->reward, // ✅ USD
            'topic' => strtolower(trim($request->topic)),
            'questions_per_attempt' => (int)$request->questions_per_attempt,
            'is_active' => 1,
        ]);

        return redirect()->route('admin.surveys.index')->with('success', '✅ Survey Created (Auto Daily Questions)');
    }

    public function edit(Survey $survey)
    {
        return view('admin.surveys.edit', compact('survey'));
    }

    public function update(Request $request, Survey $survey)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'reward' => 'required|numeric|min:0.0001',
            'topic' => 'required|in:islamic,general,bangladesh_gk,sports',
            'questions_per_attempt' => 'required|integer|min:5|max:50',
        ]);

        $survey->update([
            'title' => $request->title,
            'description' => $request->description,
            'reward' => $request->reward,
            'topic' => strtolower(trim($request->topic)),
            'questions_per_attempt' => (int) $request->questions_per_attempt,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.surveys.index')->with('success', '✅ Survey Updated');
    }

    public function destroy(Survey $survey)
    {
        // submissions + daily sets optional cleanup
        \App\Models\SurveySubmission::where('survey_id', $survey->id)->delete();
        \App\Models\SurveyDailySet::where('survey_id', $survey->id)->delete();
        $survey->delete();

        return back()->with('success', '✅ Survey Deleted');
    }
}
