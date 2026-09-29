<div class="container-fluid" style="min-height: 100px;">
    <div class="row justify-content-center">
        @if(google_head_ad())
            <div class="col-12">
                {!! google_footer_ad()->code !!}
            </div>
        @endif
        @if(site_info()->ad_two_code)
            <div class="col-12 mt-2">
                {!! site_info()->ad_two_code !!}
            </div>
        @endif
    </div>
</div>


<footer class="footer pb-4">
    <div class="container-fluid">
        <div class="row justify-content-center mb-4">
            @if (system_policy()->count() > 0)
                @foreach (system_policy() as $key=>$policy)
                    <div class="col-lg-2 col-md-2 col-6">
                        <a class="fs-12px" href="{{ route('user.policy-details', $policy->slug) }}">{{ $policy->title }}</a>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="row justify-content-center mt-2">
            
            <div class="col-lg-1 col-md-1 col-2">
                <a class="btn btn-primary btn-m btn-floating facebook" href="{{site_info()->facebook_page}}" target="_blank" role="button">
                    <i class="fab fa-facebook"></i>
                </a>
            </div>
            
            <div class="col-lg-1 col-md-1 col-2">
                <a class="btn btn-primary btn-m btn-floating whatsapp" href="{{site_info()->whatsapp}}" target="_blank" role="button">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
            
            <div class="col-lg-1 col-md-1 col-2">
                <a class="btn btn-primary btn-m btn-floating instagram" href="{{site_info()->instagram}}" target="_blank" role="button">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
            
            <div class="col-lg-1 col-md-1 col-2">
                <a class="btn btn-primary btn-m btn-floating youtube" href="{{site_info()->youtube}}" target="_blank" role="button">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>
            
            <div class="col-lg-1 col-md-1 col-2">
                <a class="btn btn-primary btn-m btn-floating teligram" href="{{site_info()->teligram}}" target="_blank" role="button">
                    <i class="fab fa-telegram-plane"></i>
                </a>
            </div>
            
        </div>
        
        
        
       
        
    </div>
</footer>