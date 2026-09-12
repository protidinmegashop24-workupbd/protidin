<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveySubmission;
use App\Models\SurveyDailySet;
use App\Models\QuestionBank;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    // ✅ Daily verify limit
    private int $dailyLimit = 20;

    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $today  = now()->toDateString();

        // ✅ badge: used/left today
        $usedToday = SurveySubmission::where('user_id', $userId)
            ->whereDate('verified_at', $today)
            ->where('code_status', 'used')
            ->count();

        $leftToday = max(0, $this->dailyLimit - $usedToday);

        // ✅ vanish today: if user opened any submission today (new/used) hide that survey
        $openedTodayIds = SurveySubmission::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->pluck('survey_id')
            ->unique()
            ->toArray();

        // ✅ surveys always exist; only hide opened today
        $surveys = Survey::where('is_active', 1)
            ->whereNotIn('id', $openedTodayIds)
            ->latest()
            ->paginate(18);

        return view('surveys.index', compact('surveys','usedToday','leftToday'));
    }

    public function show(Request $request, Survey $survey)
    {
        if (!$survey->is_active) abort(404);

        $today = now()->toDateString();

        // ✅ daily question set (once/day per survey)
        $set = $this->getOrCreateDailySet($survey, $today);
        $qids = is_array($set?->question_bank_ids) ? $set->question_bank_ids : [];

        if (!$set || count($qids) < 1) {
            return view('surveys.show_error', [
                'message' => '⚠️ আজকের জন্য প্রশ্ন সেট তৈরি হয়নি। Question bank এ যথেষ্ট প্রশ্ন আছে কিনা চেক করুন।'
            ]);
        }

        // ✅ create draft submission immediately => vanish today
        $sub = SurveySubmission::firstOrCreate(
            [
                'survey_id' => $survey->id,
                'user_id'   => $request->user()->id,
                'code_status' => 'new',
            ],
            [
                'answers' => [],
                'unique_code' => $this->generateCode(),
                'verify_status' => 'pending',
                'set_date' => $today,
            ]
        );

        $questionsCount = count($qids);

        $step = (int)$request->query('sv_step', 1);
        $step = max(1, min($questionsCount, $step));

        $currentQid = (int)$qids[$step - 1];

        $q = QuestionBank::find($currentQid);
        if (!$q) {
            return view('surveys.show_error', [
                'message' => '⚠️ Question not found in bank. Please refresh tomorrow.'
            ]);
        }

        $answers = is_array($sub->answers) ? $sub->answers : [];

        return view('surveys.show', [
            'survey' => $survey,
            'step' => $step,
            'questionsCount' => $questionsCount,
            'q' => $q,
            'answers' => $answers,
        ]);
    }

    public function saveAnswer(Request $request, Survey $survey)
    {
        $request->validate([
            'question_id' => 'required|integer',
            'answer'      => 'required|string',
            'next_step'   => 'nullable|integer',
        ]);

        $today = now()->toDateString();

        $set = $this->getOrCreateDailySet($survey, $today);
        $qids = is_array($set?->question_bank_ids) ? $set->question_bank_ids : [];

        if (!$set || count($qids) < 1) {
            return back()->withErrors(['msg' => 'Question set missing.']);
        }

        if (!in_array((int)$request->question_id, $qids, true)) {
            return back()->withErrors(['msg' => 'Invalid question for today.']);
        }

        $sub = SurveySubmission::where('survey_id', $survey->id)
            ->where('user_id', $request->user()->id)
            ->where('code_status', 'new')
            ->latest()
            ->first();

        if (!$sub) {
            $sub = SurveySubmission::create([
                'survey_id' => $survey->id,
                'user_id' => $request->user()->id,
                'answers' => [],
                'unique_code' => $this->generateCode(),
                'code_status' => 'new',
                'verify_status' => 'pending',
                'set_date' => $today,
            ]);
        }

        $answers = is_array($sub->answers) ? $sub->answers : [];
        $answers['q'.$request->question_id] = $request->answer;
        $sub->answers = $answers;
        $sub->save();

        $next = (int)($request->next_step ?? 1);
        return redirect()->route('surveys.show', [$survey->id, 'sv_step' => $next]);
    }

    /**
     * ✅ Submit (এখানে last answer miss হবে না)
     * - last question এর form এই endpoint এ post করবে (question_id + answer সহ)
     * - controller আগে save করে তারপর correct/earned বের করে code show করবে
     */
    public function submit(Request $request, Survey $survey)
    {
        $today = now()->toDateString();

        $set = $this->getOrCreateDailySet($survey, $today);
        $qids = is_array($set?->question_bank_ids) ? $set->question_bank_ids : [];

        if (!$set || count($qids) < 1) {
            return back()->withErrors(['msg' => 'Question set missing.']);
        }

        $sub = SurveySubmission::where('survey_id', $survey->id)
            ->where('user_id', $request->user()->id)
            ->where('code_status', 'new')
            ->latest()
            ->first();

        if (!$sub) return back()->withErrors(['msg' => 'Submission not found.']);

        // ✅ save last answer if included
        if ($request->filled('question_id') && $request->filled('answer')) {
            $qid = (int)$request->question_id;
            if (in_array($qid, $qids, true)) {
                $answers = is_array($sub->answers) ? $sub->answers : [];
                $answers['q'.$qid] = $request->answer;
                $sub->answers = $answers;
                $sub->save();
            }
        }

        $answers = is_array($sub->answers) ? $sub->answers : [];
        $totalQuestions = count($qids);

        // ✅ must answer all
        foreach ($qids as $qid) {
            if (empty($answers['q'.$qid])) {
                return back()->withErrors(['msg' => 'সব প্রশ্নের উত্তর দিন।']);
            }
        }

        // ✅ calculate correct
        $correctCount = 0;
        foreach ($qids as $qid) {
            $qb = QuestionBank::find((int)$qid);
            if (!$qb) continue;

            $userAns = $answers['q'.$qid] ?? null;
            if ($userAns !== null && $userAns === $qb->correct_option) {
                $correctCount++;
            }
        }

        $rewardTotal = (float)$survey->reward;
        $perQuestion = $rewardTotal / max(1, $totalQuestions);
        $earned = round($perQuestion * $correctCount, 4);

        // ✅ save result for verify
        $sub->correct_count = $correctCount;
        $sub->total_questions = $totalQuestions;
        $sub->earned_usd = $earned;
        $sub->set_date = $today;
        $sub->save();

        return redirect()->route('surveys.show', [$survey->id, 'sv_step' => $totalQuestions])
            ->with('code', $sub->unique_code)
            ->with('correctCount', $correctCount)
            ->with('totalQuestions', $totalQuestions)
            ->with('earnedUsd', $earned);
    }

    private function getOrCreateDailySet(Survey $survey, string $today)
    {
        $existing = SurveyDailySet::where('survey_id', $survey->id)
            ->where('set_date', $today)
            ->first();

        if ($existing) return $existing;

        $topic = strtolower(trim($survey->topic ?: 'general'));
        $take  = (int)($survey->questions_per_attempt ?: 10);

        $bankCount = QuestionBank::where('topic', $topic)->count();
        if ($bankCount < $take) {
            return null; // ✅ no empty row insert
        }

        $ids = QuestionBank::where('topic', $topic)
            ->inRandomOrder()
            ->limit($take)
            ->pluck('id')
            ->toArray();

        return SurveyDailySet::create([
            'survey_id' => $survey->id,
            'set_date'  => $today,
            'question_bank_ids' => $ids,
        ]);
    }

    private function generateCode()
    {
        $rand = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        return 'BD-SV-' . date('Ymd') . '-' . $rand;
    }
}
