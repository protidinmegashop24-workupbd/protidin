<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionBank;
use Illuminate\Http\Request;

class AdminQuestionBankController extends Controller
{
    public function index(Request $request)
    {
        $topic = $request->query('topic');

        $questions = QuestionBank::when($topic, function ($q) use ($topic) {
                $q->where('topic', $topic);
            })
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        $counts = [
            'general' => QuestionBank::where('topic', 'general')->count(),
            'islamic' => QuestionBank::where('topic', 'islamic')->count(),
        ];

        return view('admin.question-bank.index', compact('questions', 'topic', 'counts'));
    }

    public function create()
    {
        return view('admin.question-bank.create');
    }

    public function store(Request $request)
    {
        $data = $this->cleanQuestionInput($request);
        if ($data instanceof \Illuminate\Http\RedirectResponse) {
            return $data;
        }

        QuestionBank::create($data);

        return redirect()->route('admin.question-bank.index', ['topic' => $data['topic']])
            ->with('success', '✅ Question added.');
    }

    public function edit(QuestionBank $questionBank)
    {
        return view('admin.question-bank.edit', ['question' => $questionBank]);
    }

    public function update(Request $request, QuestionBank $questionBank)
    {
        $data = $this->cleanQuestionInput($request);
        if ($data instanceof \Illuminate\Http\RedirectResponse) {
            return $data;
        }

        $questionBank->update($data);

        return redirect()->route('admin.question-bank.index', ['topic' => $data['topic']])
            ->with('success', '✅ Question updated.');
    }

    public function destroy(QuestionBank $questionBank)
    {
        $questionBank->delete();

        return back()->with('success', '✅ Question deleted.');
    }

    /**
     * Validates and normalizes the question form input. Returns the clean
     * data array on success, or a RedirectResponse (with errors) on failure
     * -- the caller must check which one it got back.
     */
    private function cleanQuestionInput(Request $request)
    {
        $request->validate([
            'topic' => 'required|in:general,islamic',
            'question' => 'required|string|max:1000',
            'options' => 'required|array|min:2|max:6',
            'options.*' => 'required|string|max:255',
            'correct_option' => 'required|string|max:255',
        ]);

        $options = array_values(array_filter($request->input('options', []), fn ($o) => trim((string) $o) !== ''));

        if (count($options) < 2) {
            return back()->withErrors(['options' => 'At least 2 options are required.'])->withInput();
        }

        if (!in_array($request->correct_option, $options, true)) {
            return back()->withErrors(['correct_option' => 'The correct answer must match one of the options.'])->withInput();
        }

        return [
            'topic' => strtolower(trim($request->topic)),
            'question' => trim($request->question),
            'options' => $options,
            'correct_option' => $request->correct_option,
        ];
    }
}
