@extends('frontend.layouts.master')
@section('css')
    <style>
        .home-title{
            margin-bottom: 20px;
        }
    </style>
@endsection
@section('front-content')
<section class="constructo-page-title-area parallaxsection">
    <div class="parallax-windowf" data-parallax="scroll" data-image-src="{{ URL::to($slider->image) }}"></div>
        <div class="display-cell">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 text-left">
                        <div class="constructo-page-title">
                            <h1>CAREER BEC</h1>
                            <h1 class="titlecolor">busanenc</h1>
                            <ol class="breadcrumb">
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li class="active"><a href="{{ route('career') }}">Career</a></li>
                            </ol>
                            <img src="frontend/img/linew.png" alt="theconstructo.com">
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<section class="constructo-aboutUs-area section-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="section-title">
                    <h2>CAREER</h2>
                    <p>A brief story about how this process works, keep an eye till the end.</p>
                    <div class="line">
                        <img src="frontend/img/line.png" alt="theconstructo.com">
                    </div>
                </div>
            </div>
        </div>
        <div class="row padding-top">
            <div class="col-sm-12 col-md-5 col-md-offset-1">
                <div class="constructo-aboutUs-content">
                    {!! $career->details !!}
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="aboutUs-img">
                    <img src="{{ URL::to($career->image) }}" alt="theconstructo.com">
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')

@endsection
