@extends('frontend.layouts.master')
@section('css')
    <style>
        .home-title{
            margin-bottom: 20px;
        }
    </style>
@endsection
@section('front-content')
    <div class="services-single-area default-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="site-heading text-center">
                        <h2>Project Details</h2>
                        {{-- <p>
                            While mirth large of on front. Ye he greater related adapted proceed entered an. Through it examine express promise no. Past add size game cold girl off how old
                        </p> --}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="services-content col-md-8">
                    <img src="{{ URL::to($service->image) }}" alt="Thumb">
                    <div class="info">
                        <h2>{{ $service->name }}</h2>
                        <p>
                            {!! $service->details !!}
                        </p>
                    </div>
                </div>
                <div class="sidebar col-md-4">
                    <!-- Single Item -->
                    <div class="sidebar-item link">
                        <div class="title">
                            <h4>Other Projects</h4>
                        </div>
                        <ul>
                            @foreach ($services as $key=>$service)
                                <li><a href="{{ route('project_details',$service->slug) }}">{{ $service->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="sidebar-item address">
                        <div class="title">
                            <h4>Need Help?</h4>
                        </div>
                        <ul>
                            <li>
                                <div class="icon"><i class="fas fa-map-marked-alt"></i></div>
                                <span>{{ $website->address }}</span>
                            </li>
                            <li>
                                <div class="icon"><i class="fas fa-phone"></i></div>
                                <span>{{ $website->phone }}</span>
                            </li>
                            <li>
                                <div class="icon"><i class="fas fa-envelope-open"></i> </div>
                                <span>{{ $website->email }}</span>
                            </li>
                        </ul>
                    </div>
                    <!-- End Single Item -->
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')

@endsection
