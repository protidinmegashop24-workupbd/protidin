@extends('user.layouts.master')
@section('css')
    <link href="{{ asset('frontend/user/assets/css/custom.css') }}" rel="stylesheet" />
    <style>
        .job_title{
            font-weight: 600;
            font-size: 14px;
        }
        .job-list-body{
            padding: 10px !important;
            border-radius: 5px !important;
            border: 1px solid #eaeaea !important;
            background-color: #d6ebf1 !important;   
        }
        .job-area{
            background: #fff;
            margin: 0 3px;
            border-radius: 5px;
        }
        .boost-job-border{
            border-left: solid 5px;
        }
        .job-list-card-body{
            padding: 1rem 0.1rem;
        }
        .fs-12{
            font-size: 12px !important;
            color: #000000;
        }
        .fs-14{
            font-size: 14px !important;
        }
        @media screen and (min-width: 767px) {
            .job-area{
                height: 65px;
                align-items: center;
            }
            .justify-content-end {
                -webkit-box-pack: start !important;
                -ms-flex-pack: start !important;
                justify-content: flex-start !important;
            }
            .job-list-card-body{
                padding: 0px 100px;
            }
        }
    </style>
@endsection
@section('user-content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center justify-content-center d-flex gap-2" style="border: 1px solid #eaeaea !important; background-color: #d6ebf1 !important;">
                    <button type="button" class="btn btn-primary mr-2" data-bs-target="#country_modal" data-bs-toggle="modal">Country</button>
                    <button type="button" class="btn btn-info" data-bs-target="#category_modal" data-bs-toggle="modal">Category</button>
                    <button type="button" class="btn btn-success" data-bs-target="#sort_modal" data-bs-toggle="modal">Sort</button>
                </div>
                <div class="card-body job-list-body" id="job-content-area">
                    <h4 class="text-center text-success"><strong>Total Found: {{ $job_found; }}</strong></h4>
                    @php
                        $last_id = '';
                    @endphp
                    @foreach ($jobs as $key=>$job)
                        @if ($job->worker_need > $job->worker_confirmed && $job->worker_need > complete_work_this_job($job->id) && work_by_me($job->id) == 0 && work_for_me($job->id) == 1)
                            @php
                                $last_id = $job->id;
                            @endphp
                            <a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('job-details', $job->code) }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! Please Contact with authority!')" @endif>
                                <div class="border p-1 mb-2 row job-area">
                                    <div class="col-lg-4 col-md-5 col-12 text-dark fw-700 job_title">{{ $job->title }}</div>
                                    <div class="col-lg-6 col-md-5 col-9">
                                        <div class="row m-0 justify-content-end">
                                            <div class="col-lg-6 col-md-5 col-6">
                                                <p class="text-center m-0 fs-12">{{ complete_work_this_job($job->id) }} of {{ $job->worker_need }}</p>
                                                <div class="progress progress-md p-0">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-green" style="width: {{ this_job_complet_rate($job->id) }}%">{{ this_job_complet_rate($job->id) }}%</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-3 text-center text-success"><h5 class="fs-14">${{ $job->each_worker_earn }}</h5></div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
                <div class="card-footer text-center" style="border: 1px solid #eaeaea !important; background-color: #d6ebf1 !important;">
                    <input type="hidden" id="last_id" value="{{ $last_id }}">
                    <button type="button" class="btn btn-sm btn-success" id="load_more_btn" onclick="loadMoreJob()">Load More</button>
                </div>
            </div>
        </div>
    </div>

    

    <div class="modal fade" id="country_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Country</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="" id="sort_country_id">
                    @foreach ($countries as $key=>$country)
                        <button type="button" class="btn btn-info m-1" id="country_{{ $country->id }}" onclick="setCountryForSort({{ $country->id }})">{{ $country->name }}</button>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" onclick="applySortByCountry()">Apply</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="category_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Category</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="" id="sort_category_id">
                    @foreach ($categorys as $key=>$category)
                        <button type="button" class="btn btn-info m-1" id="category_{{ $category->id }}" onclick="setCategoryForSort({{ $category->id }})">{{ $category->name }}</button>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" onclick="applySortByCategory()">Apply</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sort_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Category</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
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
        }

        function applySortByCategory(){
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
                        $('#job-content-area').html(data);
                        $('#category_modal').modal('hide');
                    },
                });
            }
        }

        function setCountryForSort(id){
            $('#sort_country_id').val(id);
        }

        function applySortByCountry(){
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
                        $('#job-content-area').html(data);
                        $('#country_modal').modal('hide');
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
                    $('#job-content-area').html(data);
                    $('#sort_modal').modal('hide');
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
                    $('#job-content-area').html(data);
                    $('#sort_modal').modal('hide');
                },
            });
        }

        function loadMoreJob(){
            var last_id = $('#last_id').val();
            $.ajax({
                url: "{{ route('user.get-load-more-job') }}",
                type:"POST",
                data:{
                    last_id: last_id,
                    _token: '{{csrf_token()}}',
                },
                success:function(data) {
                    if(data['html'] == ''){
                        $('#load_more_btn').hide();
                    }else{
                        $('#job-content-area').append(data['html']);
                        $('#last_id').val(data['last_id']);
                    }
                },
            });
        }
    </script>
@endsection
