<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\OfferWallProvider;
use App\Models\OfferWallConversion;
use Illuminate\Http\Request;

class OfferWallController extends Controller
{
    public function index()
    {
        $providers = \Illuminate\Support\Facades\Schema::hasTable('offer_wall_providers')
            ? OfferWallProvider::orderBy('name')->get()
            : collect();

        return view('backend.pages.offer-wall-providers.index', compact('providers'));
    }

    public function update(Request $request, $id)
    {
        $provider = OfferWallProvider::findOrFail($id);

        $request->validate([
            'name' => 'nullable|string|max:255',
            'app_id' => 'nullable|string|max:255',
            'secret_key' => 'nullable|string|max:255',
            'widget_url_template' => 'nullable|string|max:2000',
        ]);

        $provider->update([
            'name' => $request->filled('name') ? $request->name : $provider->name,
            'app_id' => $request->app_id,
            'secret_key' => $request->secret_key,
            'widget_url_template' => $request->widget_url_template,
            'enabled' => $request->boolean('enabled'),
        ]);

        return redirect()->back()->with('success', 'Provider settings updated.');
    }

    public function conversions(Request $request)
    {
        $query = OfferWallConversion::with('user')->latest();

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
            'approved' => OfferWallConversion::where('status', 'approved')->sum('amount_usd'),
            'unverified' => OfferWallConversion::where('status', 'unverified')->count(),
            'count' => OfferWallConversion::count(),
        ];

        return view('backend.pages.offer-wall-providers.conversions', compact('conversions', 'totals'));
    }
}
