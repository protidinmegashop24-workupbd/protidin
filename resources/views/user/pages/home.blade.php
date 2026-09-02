@extends('user.layouts.master')

@section('css')
    <link href="{{ asset('frontend/user/assets/css/custom.css') }}" rel="stylesheet" />
    <style>
        body {
            font-family: 'noto serif bengali', sans-serif;
        }

        .find-job-wrapper .card {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        .find-job-topbar {
            background: linear-gradient(135deg, #eef7ff 0%, #f7fcff 100%);
            border-bottom: 1px solid #e6eef5;
            padding: 18px 20px;
        }

        .find-job-topbar .btn {
            border-radius: 10px;
            font-weight: 700;
            padding: 10px 16px;
        }

        .btn-sort {
            font-weight: 700;
            color: #0f5132;
            background-color: #eaf7ef;
            border: 1px solid #22ab59;
        }

        .btn-sort:hover {
            background-color: #dff2e7;
            color: #0f5132;
        }

        .job-list-card-body {
            background: #f4fbff;
            padding: 18px;
        }

        .job-list-body {
            border-radius: 16px;
            background-color: transparent !important;
            border: 0 !important;
        }

        .job-item-link {
            display: block;
            text-decoration: none !important;
            color: inherit !important;
        }

        .job-item-card {
            background: #ffffff;
            border: 1px solid #e4edf5;
            border-radius: 16px;
            padding: 18px 18px;
            margin-bottom: 14px;
            box-shadow: 0 8px 20px rgba(17, 24, 39, 0.05);
            transition: all .22s ease;
        }

        .job-item-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 30px rgba(17, 24, 39, 0.09);
            border-color: #cfe1ef;
        }

        .job-item-inner {
            display: grid;
            grid-template-columns: minmax(0, 2.2fr) minmax(180px, 1.2fr) minmax(110px, .6fr);
            gap: 18px;
            align-items: center;
        }

        .job-title-wrap {
            min-width: 0;
        }

        .job-title {
            font-size: 24px;
            font-weight: 800;
            line-height: 1.35;
            color: #172b4d;
            margin-bottom: 8px;
            word-break: break-word;
        }

        .job-subtext {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f3f8fc;
            color: #5f6f86;
            font-size: 13px;
            font-weight: 700;
            padding: 8px 12px;
            border-radius: 999px;
        }

        .job-progress-block {
            width: 100%;
        }

        .job-progress-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 800;
            color: #344767;
        }

        .job-progress-top .small-label {
            color: #66758b;
            font-weight: 700;
        }

        .job-progress-bar-wrap {
            background: #edf3f8;
            border-radius: 999px;
            height: 10px;
            overflow: hidden;
        }

        .job-progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #22ab59 0%, #16a34a 100%);
            border-radius: 999px;
        }

        .job-price-box {
            text-align: right;
        }

        .job-price {
            font-size: 30px;
            font-weight: 800;
            color: #16a34a;
            line-height: 1.1;
        }

        .job-price small {
            font-size: 16px;
            font-weight: 800;
        }

        .job-price-note {
            color: #66758b;
            font-size: 12px;
            font-weight: 700;
            margin-top: 6px;
        }

        .load-more-wrap {
            padding: 16px 20px 22px;
            background: #fff;
            border-top: 1px solid #edf2f7;
        }

        .load-more-wrap .btn {
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 700;
        }

        .verify-alert-box {
            display: block;
            width: 100%;
            background: #fff;
            border: 2px solid #ef4444;
            color: #1d4ed8;
            padding: 12px 16px;
            border-radius: 12px;
            text-align: center;
            font-weight: 700;
            text-decoration: none !important;
        }

        @media (max-width: 991px) {
            .job-item-inner {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .job-price-box {
                text-align: left;
            }

            .job-price {
                font-size: 28px;
            }
        }

        @media (max-width: 575px) {
            .find-job-topbar {
                padding: 14px;
            }

            .job-list-card-body {
                padding: 12px;
            }

            .job-item-card {
                padding: 14px;
                border-radius: 14px;
            }

            .job-title {
                font-size: 21px;
            }

            .job-progress-top {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }
        }
    </style>
@endsection

@section('user-content')
    <div class="row justify-content-center find-job-wrapper">
        <div class="col-12">
            <div class="card">

                @if(site_info()->email_verification_active == 1)
                    @if(!Auth::user()->hasVerifiedEmail())
                        <div class="card-header text-center justify-content-center d-flex">
                            <a href="{{ route('verification.notice') }}" class="verify-alert-box">
                                জব সাবমিট করার আগে ইমেইল ভেরিফাই করে নিন এখানে ক্লিক করে!
                            </a>
                        </div>
                    @endif
                @endif

                <div class="find-job-topbar text-center justify-content-center d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-info" data-bs-target="#category_modal" data-bs-toggle="modal">
                        Select Category
                    </button>

                    <button type="button" class="btn btn-info mr-2" data-bs-target="#country_modal" data-bs-toggle="modal">
                        Select Location
                    </button>

                    <button type="button" class="btn btn-sort" data-bs-target="#sort_modal" data-bs-toggle="modal">
                        Sort <i class="fa fa-caret-down" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="card-body job-list-card-body">
                    <div class="job-list-body" id="job-content-area">

                        @if(boost_jobs()->count() > 0)
                            @foreach (boost_jobs() as $key => $boost_job)
                                @php
                                    $job = find_job($boost_job->job_id);
                                @endphp

                                @if($job && $job->worker_need > $job->worker_confirmed && $job->worker_need > complete_work_this_job($job->id) && work_by_me($job->id) == 0 && this_job_for_me($job->id) == 1 && boost_active($job->id) == 1)
                                    <a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('job-details', $job->code) }} @endif"
                                       @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! Please Contact with authority!')" @endif
                                       class="job-item-link">

                                        <div class="job-item-card">
                                            <div class="job-item-inner">
                                                <div class="job-title-wrap">
                                                    <div class="job-title">{{ $job->title }}</div>
                                                    <div class="job-subtext">
                                                        <i class="fa fa-bolt"></i>
                                                        Boosted Opportunity
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
                        @endif

                        @php
                            $last_id = '';
                        @endphp

                        @foreach ($jobs as $key => $job)
                            @if($job->worker_need > $job->worker_confirmed && $job->worker_need > complete_work_this_job($job->id) && work_by_me($job->id) == 0 && this_job_for_me($job->id) == 1 && boost_active($job->id) == 0)
                                @php
                                    $last_id = $job->id;
                                @endphp

                                <a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('job-details', $job->code) }} @endif"
                                   @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! Please Contact with authority!')" @endif
                                   class="job-item-link">

                                    <div class="job-item-card">
                                        <div class="job-item-inner">
                                            <div class="job-title-wrap">
                                                <div class="job-title">{{ $job->title }}</div>
                                                <div class="job-subtext">
                                                    <i class="fa fa-briefcase"></i>
                                                    Available Work
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
                    </div>
                </div>

                <div class="load-more-wrap text-center">
                    <input type="hidden" id="last_id" value="{{ $last_id }}">
                    <input type="hidden" id="filter_type" value="">
                    <button type="button" class="btn btn-success btn-sm" id="load_more_btn" onclick="loadMoreJob()">
                        Load More
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="country_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Select Location</h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="" id="sort_country_id">
                    @foreach ($countries as $key=>$country)
                        <button type="button" class="btn btn-default m-1 btn-country bordered" id="country_{{ $country->id }}" onclick="setCountryForSort({{ $country->id }})">{{ $country->name }}</button>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" onclick="clearSortJobs()">Clear</button>
                    <button class="btn btn-primary" type="button" onclick="applySortByCountry()">Apply</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="category_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Select Category</h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="" id="sort_category_id">
                    @foreach ($categorys as $key=>$category)
                        <button type="button" class="btn btn-default m-1 btn-category bordered" id="category_{{ $category->id }}" onclick="setCategoryForSort({{ $category->id }})">{{ $category->name }}</button>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" onclick="clearSortJobs()">Clear</button>
                    <button class="btn btn-primary" type="button" onclick="applySortByCategory()">Apply</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sort_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Sort</h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-info m-1" onclick="recentJobs()">Recent Jobs</button>
                    <button type="button" class="btn btn-info m-1" onclick="heighPayingJobs()">Height Paying Jobs</button>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('frontend/user/assets/js/my_custom.js') }}"></script>

    <script>
        function setCategoryForSort(id){
            $('#sort_category_id').val(id);
            $('.btn-category').removeClass('btn-success');
            $('.btn-category').addClass('btn-default');

            $('#category_'+id).removeClass('btn-default');
            $('#category_'+id).addClass('btn-success');
        }

        function applySortByCategory(){
            $('#load_more_btn').show();
            $('#job-content-area').empty();
            var category_id = $('#sort_category_id').val();

            if(category_id == ''){
                alert('Please select category');
            }else{
                $.ajax({
                    url: "{{ route('user.get-job-category-wise') }}",
                    type:"POST",
                    data:{
                        category_id: category_id,
                        _token: '{{csrf_token()}}',
                    },
                    success:function(data) {
                        $('#job-content-area').html(data['html']);
                        $('#last_id').val(data['last_id']);
                        $('#filter_type').val('category');
                        $('#category_modal').modal('hide');

                        if(parseInt(data['job_found']) <= 20){
                            $('#last_id').val('');
                        }
                    },
                });
            }
        }

        function clearSortJobs(){
            $.ajax({
                url: "{{ route('user.get-regular-job') }}",
                type:"POST",
                data:{
                    _token: '{{csrf_token()}}',
                },
                success:function(data) {
                    $('#job-content-area').html(data);

                    $('.btn-category').removeClass('btn-success');
                    $('.btn-category').addClass('btn-default');

                    $('.btn-country').removeClass('btn-success');
                    $('.btn-country').addClass('btn-default');
                },
            });
        }

        function setCountryForSort(id){
            $('#sort_country_id').val(id);
            $('.btn-country').removeClass('btn-success');
            $('.btn-country').addClass('btn-default');

            $('#country_'+id).removeClass('btn-default');
            $('#country_'+id).addClass('btn-success');
        }

        function applySortByCountry(){
            $('#load_more_btn').show();
            $('#job-content-area').empty();
            var country_id = $('#sort_country_id').val();

            if(country_id == ''){
                alert('Please select country');
            }else{
                $.ajax({
                    url: "{{ route('user.get-job-country-wise') }}",
                    type:"POST",
                    data:{
                        country_id: country_id,
                        _token: '{{csrf_token()}}',
                    },
                    success:function(data) {
                        $('#job-content-area').html(data['html']);
                        $('#last_id').val(data['last_id']);
                        $('#filter_type').val('country');
                        $('#country_modal').modal('hide');

                        if(parseInt(data['job_found']) <= 20){
                            $('#last_id').val('');
                        }
                    },
                });
            }
        }

        function recentJobs(){
            $.ajax({
                url: "{{ route('user.get-recent-job') }}",
                type:"POST",
                data:{
                    _token: '{{csrf_token()}}',
                },
                success:function(data) {
                    $('#job-content-area').html(data['html']);
                    $('#last_id').val(data['last_id']);
                    $('#sort_modal').modal('hide');

                    if(parseInt(data['job_found']) <= 20){
                        $('#last_id').val('');
                    }
                },
            });
        }

        function heighPayingJobs(){
            $.ajax({
                url: "{{ route('user.get-heigh-cost-job') }}",
                type:"POST",
                data:{
                    _token: '{{csrf_token()}}',
                },
                success:function(data) {
                    $('#job-content-area').html(data['html']);
                    $('#last_id').val(data['last_id']);
                    $('#sort_modal').modal('hide');

                    if(parseInt(data['job_found']) <= 20){
                        $('#last_id').val('');
                    }
                },
            });
        }

        function loadMoreJob(){
            $('#load_more_btn').show();
            var last_id = $('#last_id').val();
            if(last_id != ''){
                var filter_type = $('#filter_type').val();
                var country_id = $('#sort_country_id').val();
                var category_id = $('#sort_category_id').val();
                $.ajax({
                    url: "{{ route('user.get-load-more-job') }}",
                    type:"POST",
                    data:{
                        last_id: last_id,
                        filter_type: filter_type,
                        category_id: category_id,
                        country_id: country_id,
                        _token: '{{csrf_token()}}',
                    },
                    success:function(data) {
                        if(data['html'] == ''){
                            $('#load_more_btn').hide();
                        }else{
                            $('#job-content-area').append(data['html']);
                            $('#last_id').val(data['last_id']);

                            if(parseInt(data['job_found']) <= 20){
                                $('#last_id').val('');
                            }
                        }
                    },
                });
            }else{
                alert('No more jobs available!');
                $('#load_more_btn').hide();
            }
        }
    </script>
@endsection