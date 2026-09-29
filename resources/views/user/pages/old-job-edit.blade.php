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
            <form action="{{ route('user.job-update',$job->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="job_fee" value="{{ $job_fee->fee }}">
                <div class="row">

                    <div class="form-group col-md-6 col-lg-6 col-12">
                        <label class="form-label">Worker Need <span class="text-red">*</span></label>
                        <input type="number" class="form-control" name="worker_need" id="worker_need" value="{{ $job->worker_need }}" min="1" onchange="chnageWorkerNeed()" onkeyup="chnageWorkerNeed()">
                        <input type="hidden" class="form-control" readonly name="each_worker_earn" id="each_worker_earn"  value="{{ $job->each_worker_earn }}" step="0.0001" min="0" onchange="chnageWorkerEarn()" onkeyup="chnageWorkerEarn()">
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
                            <input type="number" readonly class="form-control" name="budget" id="budget"  value="{{ $job->budget }}" min="0.131">
                        </div>
                    </div>

                    <div class="form-group col-md-12 col-lg-12 col-12 text-center">
                        <button type="submit" class="btn btn-primary btn-lg mt-4 mb-0">Submit</button>
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
            var total_cost = parseFloat(each_worker_earn * worker_need);
            var fee = parseFloat((parseFloat(total_cost) * parseFloat(job_fee)) / 100);
            var total = (total_cost + fee).toFixed(4);
            $('#budget').val(total);
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

        function deleteCompleteArea(step){
            $('#another_area_'+step).remove();
        }


        function chnageWorker(){
            var worrker_need = $('#worrker_need').val();
            if(worrker_need == '' || !$.isNumeric(worrker_need)){
                worrker_need = parseInt('0');
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
