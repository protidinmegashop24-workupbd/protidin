<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SmmProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SmmProviderController extends Controller
{
    public function index()
    {
        $providers = Schema::hasTable('smm_providers')
            ? SmmProvider::orderBy('id')->get()
            : collect();

        return view('backend.pages.smm-providers.index', compact('providers'));
    }

    public function update(Request $request, $id)
    {
        $provider = SmmProvider::findOrFail($id);

        $request->validate([
            'api_url' => 'nullable|string|max:500',
            'api_key' => 'nullable|string|max:255',
        ]);

        $provider->update([
            'api_url' => $request->api_url,
            'api_key' => $request->api_key,
            'enabled' => $request->boolean('enabled'),
        ]);

        // Each provider's service list is cached for 15 minutes (see
        // smm_get_services() in app/helpers.php) -- clear it now so a
        // just-pasted API key or URL shows up on the create-order page
        // immediately instead of waiting.
        Cache::forget('smm_services_provider_' . $provider->id);

        return redirect()->back()->with('success', 'Provider settings updated.');
    }
}
