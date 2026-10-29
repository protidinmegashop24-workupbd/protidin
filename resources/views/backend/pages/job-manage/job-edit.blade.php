@extends('backend.layouts.master')

@section('title')
    {{ $title }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('back-content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">All {{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">All {{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.job.update',$job->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="job_fee" value="{{ $job_fee->fee }}">
                                <div class="row">

                                    <div class="form-group col-md-4 col-lg-4 col-12">
                                        <label class="form-label">Select Location</label>
                                        <select class="form-control" name="continent" id="continent">
                                            <option value="">Select One</option>
                                            @foreach ($continents as $key=>$continent)
                                                <option value="{{ $continent->id }}" @if($continent->id == $job->continent_id) selected @endif>{{ $continent->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4 col-lg-4 col-12">
                                        <label class="form-label">Category <span class="text-red">*</span></label>
                                        <select class="form-control" name="category_id" id="category_id" required onchange="chnageCategory()">
                                            <option value="">Select One</option>
                                            @foreach ($categorys as $key=>$category)
                                                <option value="{{ $category->id }}" @if($category->id == $job->category_id) selected @endif>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4 col-lg-4 col-12">
                                        <label class="form-label">Subcategory <span class="text-red">*</span></label>
                                        <select class="form-control" name="sub_category" id="sub_category">
                                            <option value="">Select One</option>
                                            @foreach ($job_sub_cats as $key=>$category)
                                                <option value="{{ $category->id }}" @if($category->id == $job->sub_category) selected @endif>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-12">
                                        <label for="title" class="form-label">Write an accurate job Title <span class="text-red">*</span></label>
                                        <input class="form-control" type="text" name="title" placeholder="Title" value="{{ $job->title }}" required>
                                    </div>

                                    <div class="form-group col-12">
                                        <label for="link" class="form-label">
                                            <strong>What specific tasks need to be Completed?</strong>
                                            <!--<button type="button" class="btn btn-sm btn-info" onclick="addTaskNeededCompleteArea()">+ Add New</button>-->
                                        </label>
                                    </div>
                                    @php
                                        $tasks = explode("|",$job->specific_task);
                                    @endphp
                                    @if(count($tasks) <= 0)
                                        <input type="hidden" id="need_to_completed_step" value="1">
                                        <div class="form-group" id="task_need_to_completed_area">
                                            <label for="link" class="form-label">Step</label>
                                            <textarea class="form-control w-100" name="specific_task[]" id="" cols="30" rows="2"></textarea>
                                        </div>
                                    @else
                                        <input type="hidden" id="need_to_completed_step" value="{{ count($tasks) }}">

                                        @foreach ($tasks as $key=>$task)
                                            @if ($key == 0)
                                                <div class="form-group" id="task_need_to_completed_area">
                                                    <label for="link" class="form-label">Step</label>
                                                    <textarea class="form-control" name="specific_task[]" id="" cols="30" rows="2">{{ $task }}</textarea>
                                                </div>
                                            @else
                                                <div class="form-group" id="another_area_{{ $key+1 }}">
                                                    <label for="link" class="form-label">Step {{ $key+1 }} [ Max 100 character ] <button type="button" class="btn btn-sm btn-danger" onclick="deleteCompleteArea({{ $key+1 }})">X</button></label>
                                                    <textarea class="form-control" name="specific_task[]" id="" cols="30" rows="1">{{ $task }}</textarea>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif

                                    <div class="form-group col-12">
                                        <label for="proof" class="form-label">Required proof the job was Completed</label>
                                        <textarea class="form-control" name="required_proof" id="proof" cols="30" rows="2">{{ $job->required_proof }}</textarea>
                                    </div>

                                    <div class="form-group col-12">
                                        <label for="proof" class="form-label">Thumbnail image(Optional)</label>
                                        <input type="file" class="form-control" name="thumbnail">
                                        <img src="{{ URL::to($job->thumbnail_image) }}" width="60px" alt="">
                                    </div>

                                    <div class="form-group col-md-6 col-lg-6 col-12">
                                        <label class="form-label">Worker Need <span class="text-red">*</span></label>
                                        <input type="number" class="form-control" name="worker_need" id="worker_need" value="{{ $job->worker_need }}" min="1" onchange="chnageWorkerNeed()" onkeyup="chnageWorkerNeed()">
                                    </div>

                                    <div class="form-group col-md-6 col-lg-6 col-12">
                                        <label class="form-label">Each worker Earn <span class="text-red">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-append">
                                                <button class="btn btn-primary" type="button">$</button>
                                            </span>
                                            <input type="number" class="form-control" name="each_worker_earn" id="each_worker_earn"  value="{{ $job->each_worker_earn }}" step="0.0001" min="0" onchange="chnageWorkerEarn()" onkeyup="chnageWorkerEarn()">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6 col-lg-6 col-12">
                                        <label class="form-label">Require Screenshots <span class="text-red">*</span></label>
                                        <input type="number" class="form-control" name="required_screenshots"  value="{{ $job->required_screenshots }}" min="0">
                                    </div>

                                    <div class="form-group col-md-6 col-lg-6 col-12">
                                        <label class="form-label">Estimated Day <span class="text-red">*</span></label>
                                        <input type="number" class="form-control" name="estimited_day"  value="{{ $job->estimited_day }}" min="1">
                                    </div>

                                    <div class="form-group col-md-12 col-lg-12 col-12">
                                        <label class="form-label">Estimated Job Cost <span class="text-red">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-append">
                                                <button class="btn btn-primary" type="button">$</button>
                                            </span>
                                            <input type="number" class="form-control" name="budget" id="budget"  value="{{ $job->budget }}" min="0" step="0.0001">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12 col-lg-12 col-12 text-center">
                                        <button type="submit" class="btn btn-primary btn-lg mt-4 mb-0">Submit</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection

@section('js')
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        function setAccount(account_id){
            $('#deposit_account').val(account_id);

            $.ajax({
                url: "{{ route('user.deposit-account-info') }}",
                type:"POST",
                data:{
                    _token: '{{csrf_token()}}',
                    account_id: account_id,
                },
                success:function(data) {
                    $('#deposit_area').show();
                    $('#deposit_account_text').html('Account No: '+data['account_no']);
                    $('#deposit_account_guideline').html(data['guideline']);
                },
            });
        }

        function chnageLocationZone(){
            var location_zone = $('#location_zone').val();
            $.ajax({
                url: "{{ route('admin.get-country') }}",
                type:"POST",
                data:{
                    _token: '{{csrf_token()}}',
                    location_zone: location_zone,
                },
                success:function(data) {
                    console.log(data);
                    $('#country').html(data);
                },
            });
        }

        function chnageCategory(){
            var category_id = $('#category_id').val();
            $.ajax({
                url: "{{ route('admin.get-sub-category') }}",
                type:"POST",
                data:{
                    _token: '{{csrf_token()}}',
                    category_id: category_id,
                },
                success:function(data) {
                    console.log(data);
                    $('#sub_category').html(data);
                },
            });
        }

        function chnageSubCategory(){
            var sub_category = $('#sub_category').val();
            var worker_need = parseFloat($('#worker_need').val());
            var job_fee = parseInt($('#job_fee').val());
            $.ajax({
                url: "{{ route('admin.get-sub-category-price') }}",
                type:"POST",
                data:{
                    _token: '{{csrf_token()}}',
                    sub_category: sub_category,
                },
                success:function(data) {
                    console.log(data);
                    var minimum_cost = parseFloat(data);
                    $('#each_worker_earn').val(minimum_cost);
                    var total_cost = minimum_cost * worker_need;
                    var fee = (parseFloat(total_cost) * parseFloat(job_fee)) / 100;
                    $('#budget').val(total_cost + fee);
                },
            });
        }

        function chnageWorkerNeed(){
            var job_fee = parseInt($('#job_fee').val());
            var worker_need = parseFloat($('#worker_need').val());
            var each_worker_earn = parseFloat($('#each_worker_earn').val());
            var total_cost = each_worker_earn * worker_need;
            var fee = (parseFloat(total_cost) * parseFloat(job_fee)) / 100;
            $('#budget').val(total_cost + fee);
        }

        function chnageWorkerEarn(){
            var job_fee = parseInt($('#job_fee').val());
            var worker_need = parseFloat($('#worker_need').val());
            var each_worker_earn = parseFloat($('#each_worker_earn').val());
            var total_cost = each_worker_earn * worker_need;
            var fee = (parseFloat(total_cost) * parseFloat(job_fee)) / 100;
            $('#budget').val(total_cost + fee);
        }

        function addTaskNeededCompleteArea(){
            var need_to_completed_step = parseInt($('#need_to_completed_step').val());
            need_to_completed_step = need_to_completed_step + 1;
            $('#need_to_completed_step').val(need_to_completed_step);
            $.ajax({
                url: "{{ route('admin.get-new-task-complete-area') }}",
                type:"POST",
                data:{
                    need_to_completed_step: need_to_completed_step,
                    _token: '{{csrf_token()}}',
                },
                success:function(data) {
                    console.log(data);
                    $('#task_need_to_completed_area').append(data);
                },
            });
        }

        function deleteCompleteArea(step){
            $('#another_area_'+step).remove();
        }


        function chnageWorker(){
            var worrker_need = $('#worrker_need').val();
            if(worrker_need == '' || !$.isNumeric(worrker_need)){
                worrker_need = parseInt('0');
            }
        }
    </script>
@endsection
