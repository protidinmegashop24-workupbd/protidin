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
            'bangladesh_gk' => QuestionBank::where('topic', 'bangladesh_gk')->count(),
            'sports' => QuestionBank::where('topic', 'sports')->count(),
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

    public function bulkUploadForm()
    {
        return view('admin.question-bank.bulk-upload');
    }

    /**
     * CSV columns, in order, no header row needed (a header row is fine
     * too -- the first row is only skipped if it doesn't look like a
     * real question row):
     * topic, question, option1, option2, option3, option4, correct_answer
     */
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $allowedTopics = ['general', 'islamic', 'bangladesh_gk', 'sports'];

        $handle = fopen($request->file('csv_file')->getRealPath(), 'r');
        if (!$handle) {
            return back()->with('error', '❌ CSV ফাইল পড়া যায়নি।');
        }

        $added = 0;
        $skipped = [];
        $rowNum = 0;
        $first = true;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;

            // Skip a header row like "topic,question,option1,...".
            if ($first) {
                $first = false;
                if (isset($row[0]) && strtolower(trim($row[0])) === 'topic') {
                    continue;
                }
            }

            if (count(array_filter($row, fn ($c) => trim((string) $c) !== '')) === 0) {
                continue; // blank line
            }

            $topic = strtolower(trim($row[0] ?? ''));
            $question = trim($row[1] ?? '');
            $options = array_values(array_filter(
                array_map('trim', array_slice($row, 2, 4)),
                fn ($o) => $o !== ''
            ));
            $correct = trim($row[6] ?? '');

            if (!in_array($topic, $allowedTopics, true)) {
                $skipped[] = "Row {$rowNum}: অজানা topic '{$topic}'";
                continue;
            }
            if ($question === '' || count($options) < 2) {
                $skipped[] = "Row {$rowNum}: প্রশ্ন বা অপশন অনুপস্থিত";
                continue;
            }
            if (!in_array($correct, $options, true)) {
                $skipped[] = "Row {$rowNum}: সঠিক উত্তর কোনো অপশনের সাথে মিলছে না";
                continue;
            }

            QuestionBank::create([
                'topic' => $topic,
                'question' => $question,
                'options' => $options,
                'correct_option' => $correct,
            ]);
            $added++;
        }

        fclose($handle);

        return redirect()->route('admin.question-bank.index')
            ->with('success', "✅ {$added} টা প্রশ্ন যোগ হয়েছে।")
            ->with('skipped', $skipped);
    }

    /**
     * Validates and normalizes the question form input. Returns the clean
     * data array on success, or a RedirectResponse (with errors) on failure
     * -- the caller must check which one it got back.
     */
    private function cleanQuestionInput(Request $request)
    {
        $request->validate([
            'topic' => 'required|in:general,islamic,bangladesh_gk,sports',
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
