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
<div class="portfolio-area bg-gray inc-colum default-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="site-heading text-center">
                    <h2>Photo Gallery</h2>
                </div>
            </div>
        </div>
        <div class="portfolio-items-area text-center">
            <div class="row">
                <div class="col-md-12 portfolio-content">

                    <div class="row magnific-mix-gallery text-center masonary">
                        <div id="portfolio-grid" class="portfolio-items col-3" style="position: relative; height: 690.406px;">
                            <!-- Single Item -->
                            @foreach ($photos as $item=>$photo)
                                <div class="pf-item capital" style="position: absolute; left: 0%; top: 0px;">
                                    <div class="effect-left-swipe">
                                        <img src="{{ URL::to($photo->image) }}" alt="thumb">
                                        <a href="{{ URL::to($photo->image) }}" class="item popup-link"><i class="fa fa-plus"></i></a>
                                        <div class="icons">
                                            <h4><a href="javascript:;">{{ $photo->text }}</a></h4>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <!-- End Single Item -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')

@endsection
