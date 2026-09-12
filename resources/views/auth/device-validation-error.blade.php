@extends('frontend.layouts.master')
@section('css')
    <style>
        .home-title {
            margin-bottom: 20px;
        }

    </style>
    @php
        $website = DB::table('websites')
            ->latest()
            ->first();
        $p_categorys = DB::table('categories')
            ->latest()
            ->get();
    @endphp
@endsection
@section('front-content')
    <div class="contact_inner_section position-relative">
        <div class="container">
            <div class="row pt-sm-3 pt-lg-5 justify-content-center">
                <div class="col-12 col-md-6">
                    <div class="details_inner d-flex">
                        <div class="details_info text-danger">
                            This device already registered for another account. One device only for one account. Please try with new device;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
