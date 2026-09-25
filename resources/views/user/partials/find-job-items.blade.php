@foreach ($jobs as $job)
    @if($job->worker_need > $job->worker_confirmed && $job->worker_need > complete_work_this_job($job->id) && work_by_me($job->id) == 0 && this_job_for_me($job->id) == 1)
        <a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('job-details', $job->code) }} @endif"
           @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! Please Contact with authority!')" @endif
           class="job-item-link">

            <div class="job-item-card @if(boost_active($job->id) == 1) border border-success @endif">
                <div class="job-item-inner">
                    <div class="job-title-wrap">
                        <div class="job-title">{{ $job->title }}</div>

                        <div class="job-subtext">
                            <i class="fa @if(boost_active($job->id) == 1) fa-bolt @else fa-briefcase @endif"></i>
                            @if(boost_active($job->id) == 1)
                                Boosted Opportunity
                            @else
                                Available Work
                            @endif
                        </div>
                    </div>

                    <div class="job-progress-block">
                        <div class="job-progress-top">
                            <span>{{ complete_work_this_job($job->id) }} OF {{ $job->worker_need }}</span>
                            <span class="small-label">Progress</span>
                        </div>

                        <div class="job-progress-bar-wrap">
                            <div class="job-progress-bar-fill" style="width: {{ this_job_complet_rate($job->id) }}%"></div>
                        </div>
                    </div>

                    <div class="job-price-box">
                        <div class="job-price">{{ number_format((float)$job->each_worker_earn, 4, '.', '') }} <small>$</small></div>
                        <div class="job-price-note">Per task reward</div>
                    </div>
                </div>
            </div>
        </a>
    @endif
@endforeach