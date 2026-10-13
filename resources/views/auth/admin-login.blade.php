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


<section id="contact" class="contact">
    <div class="container aos-init aos-animate" data-aos="fade-up">
        <div class="row gy-4 mt-4 justify-content-md-center">
            <div class="col-lg-4 pb-2 pt-2" style="border:1px solid #cecece;">
                <div class="mb-10 text-center">
                    <a href="javascript:void(0)">
                        <img src="{{ URL::to(website_logo()) }}" alt="logo" width="100" />
                    </a>
                </div>
                <form action="{{ route('login') }}" method="POST" role="form" >
                    @csrf
                    <div class="form-group mt-3">
                        <input type="text" class="form-control" placeholder="Phone/Email" name="phone" required="">
                    </div>
                    <div class="form-group mt-3">
                        <input type="password" class="form-control" placeholder="Password" name="password" required="">
                    </div>
                    <div class="text-center mt-4"><button type="submit" class="btn btn-info btn-m">Login</button></div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
