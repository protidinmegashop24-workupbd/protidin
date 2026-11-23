<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin\MainWallet;
use App\Models\Admin\Website;
use App\Models\BoostCategory;
use App\Models\BoostPackageHeadline;
use App\Models\User;
use App\Models\UserBoostPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoostPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = UserBoostPackage::where('user_id', Auth::user()->id)->latest()->paginate(25);
        $headlines = BoostPackageHeadline::all();
        $title = 'Order History';

        return view('user.pages.boost-package.index', compact('title', 'datas', 'headlines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorys = BoostCategory::orderBy('id', 'ASC')->get();
        $headlines = BoostPackageHeadline::all();
        $title = 'Add new Boost';

        return view('user.pages.boost-package.create', compact('categorys', 'title', 'headlines'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required',
            'link' => 'required',
            'work_need' => 'required|integer|min:1',
        ]);

        $service = null;
        foreach (smm_get_services() as $s) {
            if ($s['service'] === $request->service_id) {
                $service = $s;
                break;
            }
        }

        if (!$service) {
            return redirect()->back()->with('error', 'Selected service is no longer available. Please choose again.');
        }

        $quantity = (int) $request->work_need;
        $min = (int) $service['min'];
        $max = (int) $service['max'];
        if ($quantity < $min || ($max > 0 && $quantity > $max)) {
            return redirect()->back()->with('error', 'Quantity must be between ' . $min . ' and ' . $max . '.');
        }

        // Cost is always recomputed server-side from the provider's own
        // rate -- the client-submitted 'cost'/'base_cost'/'unit_cost' fields
        // are only there so the form's JS can preview the charge, never
        // trusted for the actual deduction.
        $unitCost = ((float) $service['rate']) / 1000;
        $baseCost = round($unitCost * $quantity, 4);
        $cost = round($baseCost * 1.03, 4);

        if (Auth::user()->deposit_balance < $cost) {
            return redirect()->back()->with('error', 'You have no sufficient balance for job.');
        }

        $user_balance = User::find(Auth::user()->id);
        $user_balance->deposit_balance = $user_balance->deposit_balance - $cost;
        $user_balance->save();

        $check_main_wallet = MainWallet::latest()->first();
        $main_wallet = MainWallet::find($check_main_wallet->id);
        $main_wallet->amount = $main_wallet->amount + $cost;
        $main_wallet->save();

        $last_ac = UserBoostPackage::select('id')->latest()->first();
        if (isset($last_ac)) {
            $code = sprintf('%04d', $last_ac->id + 1000001);
        } else {
            $code = sprintf('%04d', 1000001);
        }

        $boost_package = new UserBoostPackage();
        $boost_package->code = $code;
        $boost_package->description = $request->description;
        $boost_package->link = $request->link;
        $boost_package->category_id = 0;
        $boost_package->sub_category = 0;
        $boost_package->category = $service['category'];
        $boost_package->service_id = $request->service_id;
        $boost_package->name = $service['name'];
        $boost_package->base_cost = $baseCost;
        $boost_package->unit_cost = $unitCost;
        $boost_package->work_need = $quantity;
        $boost_package->order_qty = $quantity;
        $boost_package->cost = $cost;
        $boost_package->order_charge = $cost;
        $boost_package->user_id = Auth::user()->id;
        $boost_package->provider_id = $service['provider_id'];
        $boost_package->status = 0;
        $boost_package->save();

        // Auto-place the order with the real SMM provider -- this is the
        // part that makes the order actually get delivered once the admin
        // has pasted real API keys into Admin > SMM Panel Providers. If
        // this fails for any reason, the order stays as a normal pending
        // (status 0) request the admin can still see and handle by hand;
        // the user's balance was already deducted above either way.
        $provider = \App\Models\SmmProvider::find($service['provider_id']);
        if ($provider && $provider->api_url && $provider->api_key) {
            try {
                $client = new \App\Library\SmmPanel($provider->api_url, $provider->api_key);
                $result = $client->addOrder($service['native_service_id'], $request->link, $quantity);

                if (isset($result['order'])) {
                    $boost_package->provider_order_id = $result['order'];
                    $boost_package->provider_status = 'Pending';
                    $boost_package->status = 1;
                } else {
                    $boost_package->provider_message = json_encode($result);
                }
            } catch (\Throwable $e) {
                $boost_package->provider_message = $e->getMessage();
                \Illuminate\Support\Facades\Log::warning('smm-order-place-failed', [
                    'boost_package_id' => $boost_package->id,
                    'error' => $e->getMessage(),
                ]);
            }

            $boost_package->save();
        }

        return redirect()->back()->with('message','Boost Package added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $boost_package = UserBoostPackage::find($id);
        $headlines = BoostPackageHeadline::all();
        $title = 'Update Boost Package';

        return view('user.pages.boost-package.edit', compact('boost_package', 'title', 'headlines'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required',
        ]);

        $boost_package = UserBoostPackage::find($id);
        $boost_package->description = $request->description;
        $boost_package->save();

        return redirect()->back()->with('message','Boost Package updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
