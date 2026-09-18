<div class="clients-area bg-gray default-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="site-heading text-center">
                    <h2>Clients</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="brand-carousel owl-carousel">
                @isset($clients)
                    @foreach ($clients as $client)
                        <div class="single-logo">
                            <img src="{{ URL::to($client->image) }}" alt="">
                        </div>
                    @endforeach
                @endisset
            </div>
        </div>
        @if ($clients->count() > 0)
            <section class="customer-logos slider">
                @foreach ($clients as $client)
                    <div class="slide">
                        <img src="{{ URL::to($client->image) }}" alt="Client Logo">
                    </div>
                @endforeach
                {{-- <div class="slide"><img src="https://image.freepik.com/free-vector/3d-box-logo_1103-876.jpg"></div>
                <div class="slide"><img src="https://image.freepik.com/free-vector/blue-tech-logo_1103-822.jpg"></div>
                <div class="slide"><img src="https://image.freepik.com/free-vector/colors-curl-logo-template_23-2147536125.jpg"></div>
                <div class="slide"><img src="https://image.freepik.com/free-vector/abstract-cross-logo_23-2147536124.jpg"></div>
                <div class="slide"><img src="https://image.freepik.com/free-vector/football-logo-background_1195-244.jpg"></div>
                <div class="slide"><img src="https://image.freepik.com/free-vector/background-of-spots-halftone_1035-3847.jpg"></div>
                <div class="slide"><img src="https://image.freepik.com/free-vector/retro-label-on-rustic-background_82147503374.jpg"></div> --}}
            </section>
        @endif
    </div>
</div>
