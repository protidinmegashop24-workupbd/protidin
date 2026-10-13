@extends('frontend.layouts.master')
@section('css')
    <style>
        .home-title{
            margin-bottom: 20px;
        }
    </style>
@endsection
@section('front-content')
{{-- <section class="constructo-page-title-area parallaxsection">
    <div class="parallax-windowf" data-parallax="scroll" data-image-src="{{ URL::to($slider->image) }}"></div>
        <div class="display-cell">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 text-left">
                        <div class="constructo-page-title">
                            <h1>SERVICE BEC</h1>
                            <h1 class="titlecolor">busanenc</h1>
                            <ol class="breadcrumb">
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li class="active"><a href="{{ route('service') }}">Service</a></li>
                            </ol>
                            <img src="frontend/img/linew.png" alt="theconstructo.com">
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section> --}}

{{-- Servise Section --}}
<div class="blog-area default-padding bottom-less bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="site-heading text-center">
                    <h2>Project</h2>
                    {{-- <p>
                        While mirth large of on front. Ye he greater related adapted proceed entered an. Through it examine express promise no. Past add size game cold girl off how old
                    </p> --}}
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Single Item -->
            @foreach ($projects as $item=>$service)
                <div class="col-md-4 single-item">
                    <div class="item bg-light">
                        <div class="thumb">
                            <a href="{{ route('project_details',$service->slug) }}"><img src="{{ URL::to($service->image) }}" alt="Thumb"></a>
                        </div>
                        <div class="info">
                            <h4>
                                <a href="{{ route('project_details',$service->slug) }}">{{ $service->name }}</a>
                            </h4>
                            {{-- <p>
                                {!! $service->details !!}
                            </p> --}}
                            {{-- <div class="meta">
                                <ul>
                                    <li><i class="fas fa-calendar-alt"></i> 29 Feb, 2019</li>
                                </ul>
                                <a href="{{ route('project_details',$service->slug) }}">Read More</a>
                            </div> --}}
                        </div>
                    </div>
                </div>
            @endforeach
            <!-- End Single Item -->
        </div>
        {{ $projects->links() }}
    </div>
</div>
@endsection
@section('js')

@endsection
