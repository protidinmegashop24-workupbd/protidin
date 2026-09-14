<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SurveyProvider;
use App\Models\SurveyProviderConversion;
use Illuminate\Http\Request;

class SurveyProviderController extends Controller
{
    public function index()
    {
        $providers = \Illuminate\Support\Facades\Schema::hasTable('survey_providers')
            ? SurveyProvider::orderBy('name')->get()
            : collect();

        return view('backend.pages.survey-providers.index', compact('providers'));
    }

    public function update(Request $request, $id)
    {
        $provider = SurveyProvider::findOrFail($id);

        $request->validate([
            'app_id' => 'nullable|string|max:255',
            'secret_key' => 'nullable|string|max:255',
        ]);

        $provider->update([
            'app_id' => $request->app_id,
            'secret_key' => $request->secret_key,
            'enabled' => $request->boolean('enabled'),
        ]);

        return redirect()->back()->with('success', 'Provider settings updated.');
    }

    public function conversions(Request $request)
    {
        $query = SurveyProviderConversion::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('trans_id')) {
            $query->where('trans_id', 'like', '%' . $request->trans_id . '%');
        }

        $conversions = $query->paginate(30)->withQueryString();

        $totals = [
            'approved' => SurveyProviderConversion::where('status', 'approved')->sum('amount_usd'),
            'reversed' => SurveyProviderConversion::where('status', 'reversed')->sum('amount_usd'),
            'count' => SurveyProviderConversion::count(),
        ];

        return view('backend.pages.survey-providers.conversions', compact('conversions', 'totals'));
    }
}
