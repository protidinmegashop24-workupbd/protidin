<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Website;
use App\Models\BoostCharge;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BoostChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = BoostCharge::orderBy('id', 'ASC')->get();
        $website = Website::latest()->first();
        return view('backend.pages.boost-manage.boost-charge', compact('datas', 'website'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'duration' => 'required',
            'charge' => 'required',
        ]);

        $boost_charge = new BoostCharge();
        $boost_charge->duration = $request->input('duration');
        $boost_charge->charge = $request->input('charge');
        $boost_charge->save();

        return redirect()->back()->with('message','Data added Successfully');
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
        //
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
            'duration' => 'required',
            'charge' => 'required',
        ]);

        $boost_charge = BoostCharge::find($id);
        $boost_charge->duration = $request->input('duration');
        $boost_charge->charge = $request->input('charge');
        $boost_charge->save();

        return redirect()->back()->with('message','Data added Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $boost_charge = BoostCharge::find($id);
        $boost_charge->delete();

        return redirect()->back()->with('message','Data deleted Successfully');
    }
}
