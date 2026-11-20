@extends('user.layouts.master')
@section('css')
    <style>
        .gallery-img {
            display: flex;
            align-items: center;
            justify-content: center;
            height: calc(100% - 64px);
            padding: 15px;
            padding-top: 0;
        }
        .gallery-img img {
            width: auto;
            height: auto;
            max-height: 380px;
        }
    </style>
@endsection
@section('user-content')
    <div class="card mt-2">
        <div class="card-header">
            <h4 class="card-title">{{ $title }}</h4>
        </div>
        <div class="card-body">
            <form id="job_creat_form" action="{{ route('user.job-post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="job_fee" value="{{ $job_fee->fee }}">
                <input type="hidden" id="minimum_cost" value="{{site_info()->minimum_job_cost}}">
                <div class="row">
                    <div class="form-group col-md-4 col-lg-4 col-12">
                        <label class="form-label">Country <span class="text-danger">*</span></label>
                        <select class="form-control" name="location_zone_country" required id="country">
                            <option value="">Select One</option>
                            @foreach ($countrys as $key=>$location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4 col-lg-4 col-12">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-control" name="category_id" id="category_id" required onchange="chnageCategory()">
                            <option value="">Select One</option>
                            @foreach ($categorys as $key=>$category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4 col-lg-4 col-12">
                        <label class="form-label">Subcategory <span class="text-danger">*</span></label>
                        <select class="form-control" name="sub_category" id="sub_category" required onchange="chnageSubCategory()">
                            <option value="">Select One</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="title" class="form-label">Write an accurate job Title <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="title" placeholder="Title" required>
                    </div>

                    <div class="form-group">
                        <label for="link" class="form-label">
                            <strong>What specific tasks need to be Completed?</strong>
                            {{-- <button type="button" class="btn btn-sm btn-info" onclick="addTaskNeededCompleteArea()">+ Add New</button> --}}
                        </label>
                    </div>
                    <input type="hidden" id="need_to_completed_step" value="1">
                    <div class="form-group" id="task_need_to_completed_area">
                        <label for="link" class="form-label">Steps</label>
                        <textarea class="form-control" name="specific_task[]" id="" cols="30" rows="2"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="proof" class="form-label">Required proof the job was Completed</label>
                        <textarea class="form-control" name="required_proof" id="proof" cols="30" rows="2"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="proof" class="form-label">Thumbnail image(Optional)</label>
                        <input type="file" class="form-control" name="thumbnail" id="screen_shot_select_image" accept="image/*" onchange="readURL();">
                        <div class="gallery-img">
                            <img class="mt-2" id="screen_shot_show_image" id="photo" src="#" style="display:none;" />
                        </div>
                    </div>

                    <div class="form-group col-md-6 col-lg-6 col-12">
                        <label class="form-label">Worker Need <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="worker_need" id="worker_need" value="5" min="1" onchange="chnageWorkerNeed()" onkeyup="chnageWorkerNeed()">
                    </div>

                    <div class="form-group col-md-6 col-lg-6 col-12">
                        <label class="form-label">Each worker Earn <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-append">
                                <button class="btn btn-primary" type="button">$</button>
                            </span>
                            <input type="hidden" class="form-control" id="each_worker_min_earn" value="0">
                            <input type="number" class="form-control" name="each_worker_earn" id="each_worker_earn" step="0.0001" value="0" min="0" onchange="chnageWorkerEarn()" onkeyup="chnageWorkerEarn()">
                        </div>
                    </div>

                    <div class="form-group col-md-6 col-lg-6 col-12">
                        <label class="form-label">Require Screenshots <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="required_screenshots" id="required_screenshots" onchange="chnageScreenShoot()" onkeyup="chnageScreenShoot()" value="0" min="0" max="2">
                    </div>

                    <div class="form-group col-md-6 col-lg-6 col-12">
                        <label class="form-label">Estimated Day <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="estimited_day" value="3" min="1">
                    </div>

                    <div class="form-group col-md-12 col-lg-12 col-12">
                        <label class="form-label">Estimated Job Cost <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-append">
                                <button class="btn btn-primary" type="button">$</button>
                            </span>
                            <input type="number" class="form-control" readonly name="budget" id="budget" value="0" min="0">
                        </div>
                        <span class="text-danger" id="job_cost_alert"></span>
                    </div>

                    <div class="form-group col-md-12 col-lg-12 col-12 text-center">
                        <button type="button" class="btn btn-primary btn-lg mt-4 mb-0" onclick="submitJob()">Submit</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
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
                url: "{{ route('user.get-country') }}",
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
                url: "{{ route('user.get-sub-category') }}",
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
                url: "{{ route('user.get-sub-category-price') }}",
                type:"POST",
                data:{
                    _token: '{{csrf_token()}}',
                    sub_category: sub_category,
                },
                success:function(data) {
                    var minimum_cost = parseFloat(data);
                    // $('#minimum_cost').val(minimum_cost);
                    $('#each_worker_earn').val(minimum_cost);
                    $('#each_worker_min_earn').val(minimum_cost);
                    var total_cost = parseFloat(minimum_cost * worker_need);
                    var fee = parseFloat((parseFloat(total_cost) * parseFloat(job_fee)) / 100);
                    var total = (total_cost + fee).toFixed(4);
                    $('#budget').val(total);
                },
            });
        }

        function chnageWorkerNeed(){
            var job_fee = parseInt($('#job_fee').val());
            var worker_need = parseFloat($('#worker_need').val());
            var each_worker_earn = parseFloat($('#each_worker_earn').val());
            var total_cost = parseFloat(each_worker_earn * worker_need);
            var fee = parseFloat((parseFloat(total_cost) * parseFloat(job_fee)) / 100);
            var total = (total_cost + fee).toFixed(4);
            $('#budget').val(total);
        }

        function chnageWorkerEarn(){
            var job_fee = parseInt($('#job_fee').val());
            var worker_need = parseFloat($('#worker_need').val());
            var each_worker_earn = parseFloat($('#each_worker_earn').val());
            var each_worker_min_earn = parseFloat($('#each_worker_min_earn').val());
            if(each_worker_earn < each_worker_min_earn){
                alert('You can not decrease cost from minimum cost of per worker cost!');
                $('#each_worker_earn').val(each_worker_min_earn);
            }else{
                var total_cost = parseFloat(each_worker_earn * worker_need);
                var fee = parseFloat((parseFloat(total_cost) * parseFloat(job_fee)) / 100);
                var total = (total_cost + fee).toFixed(4);
                $('#budget').val(total);
            }
        }

        function addTaskNeededCompleteArea(){
            var need_to_completed_step = parseInt($('#need_to_completed_step').val());
            need_to_completed_step = need_to_completed_step + 1;
            $('#need_to_completed_step').val(need_to_completed_step);
            $.ajax({
                url: "{{ route('user.get-new-task-complete-area') }}",
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
        
        function submitJob(){
            var minimum_cost = parseFloat($('#minimum_cost').val());
            var budget = parseFloat($('#budget').val());
            
            if(minimum_cost > budget){
                $('#job_cost_alert').html('Minimum cost should be $'+minimum_cost);
            }else{
                $('#job_cost_alert').html('');
                $('#job_creat_form').submit();
            }
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

        function chnageScreenShoot(){
            var required_screenshots = parseInt($('#required_screenshots').val());
            if(required_screenshots > 2){
                $('#required_screenshots').val(2);
            }
        }
        
        function readURL(){
            var file = $('#screen_shot_select_image').get(0).files[0];
            console.log(file);
            if(file){
                $('#screen_shot_show_image').show();
                var reader = new FileReader();
     
                reader.onload = function(){
                    $('#screen_shot_show_image').attr("src", reader.result);
                }
     
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
