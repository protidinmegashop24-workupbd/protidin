<!-- Start Banner
    ============================================= -->
    <div class="banner-area">
        <div id="bootcarousel" class="carousel inc-top-heading slide carousel-fade animate_text" data-ride="carousel">
            <!-- Wrapper for slides -->
            <div class="carousel-inner text-light carousel-zoom">
                @foreach ($slider as $item=>$slider)
                    <div class="item @if ($item == '0') active @endif">
                        <div class="slider-thumb bg-cover" style="background-image: url('{{ asset($slider->image)}}');"></div>
                        <div class="box-table shadow dark">
                            <div class="box-cell">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="content">
                                                <h1 data-animation="animated slideInRight">{{ $slider->text }}</h1>
                                                {{-- <h1 data-animation="animated slideInLeft">We are <span>Providing</span> Best Business Service</h1>
                                                <a data-animation="animated slideInUp" class="btn btn-light effect btn-md" href="#">View Projects</a>
                                                <a data-animation="animated slideInUp" class="btn btn-theme effect btn-md" href="#">Learn more</a> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- End Wrapper for slides -->

            <!-- Left and right controls -->
            <a class="left carousel-control shadow" href="#bootcarousel" data-slide="prev">
                <i class="fa fa-angle-left"></i>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control shadow" href="#bootcarousel" data-slide="next">
                <i class="fa fa-angle-right"></i>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <!-- End Banner -->
