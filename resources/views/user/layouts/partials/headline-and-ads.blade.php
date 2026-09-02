   
   <head>
       <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali&display=swap" rel="stylesheet">

   </head>
   
    @if(Route::is('user.dashboard') || Route::is('user.find-job'))
        <div class="container-fluid mt-2 mb-2">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="notice-box" style="text-align: center; padding: 5px 5px 0px 5px;">
                        <marquee bgcolor="green" style="border: 1px solid blue; background-color: white; padding: 2px; overflow: hidden; white-space: nowrap; border-radius: 1px; display: inline-block; height: 45px;" behavior="scroll" direction="left" scrollamount="5">
                            @foreach (headlines() as $key=>$headline)
<p class="notice-text" style="color: green; margin: 0; font-size: 25px; margin-top: -3px; font-family: 'Noto Serif Bengali', serif;">{{ $headline->title }}</p>
                            @endforeach
                        </marquee>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-6 col-10">
                    <div class="card">
                        <div class="card-body">
        
                            <h4 class="card-title text-center">Click Now</h4>
        
                            <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel" data-bs-interval="20000">
                                <div class="carousel-inner" role="listbox">
                                    @foreach (ad_banner() as $key=>$ads)
                                        <div class="carousel-item @if($key==0) active @endif">
                                            <a href="{{ $ads->link }}">
                                                <img class="d-block ads-img" src="{{ URL::to($ads->image) }}" alt="Ad banner">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
    @endif
    
    
