@extends('user.layouts.master')
@section('css')

@endsection
@section('user-content')
    <div class="container-fluid mt-2">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-8 col-12">
                <div class="alert alert-success bg-warning text-white border-0">
                    <marquee scrollamount="6">
                        @foreach ($headlines as $key=>$headline)
                            <a href="{{ $headline->link }}" class="text-black" style="font-size:20px;"><i class="fe fe-link me-2 white-text" aria-hidden="true"></i>{{ $headline->title }}</a>
                        @endforeach
                    </marquee>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-2">
        <div class="card-header">
            <h4 class="card-title">{{ $title }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('user.boost-update',$boost_package->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="form-group col-md-6 col-lg-6 col-12">
                        <label class="form-label">Category <span class="text-red">*</span></label>
                        <input class="form-control" type="hidden" name="category_id" value="{{ $boost_package->category_id }}">
                        <input class="form-control" type="text" readonly value="{{ main_boost_category($boost_package->category_id) }}">
                    </div>

                    <div class="form-group col-md-6 col-lg-6 col-12">
                        <label class="form-label">Service <span class="text-red">*</span></label>
                        <input class="form-control" type="hidden" name="sub_category" value="{{ $boost_package->sub_category }}">
                        <input class="form-control" type="text" readonly value="{{ sub_boost_category($boost_package->sub_category) }}">
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="description" id="description" readonly cols="30" rows="4" maxlength="200" placeholder="Description" required>{{ $boost_package->description }}</textarea>
                    </div>

                    <div class="form-group col-md-4 col-lg-4 col-12">
                        <label for="link" class="form-label">Link <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="link" placeholder="Link" value="{{ $boost_package->link }}" readonly>
                    </div>

                    <div class="form-group col-md-4 col-lg-4 col-12">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="hidden" class="form-control" name="base_cost" id="base_cost" step="0.0001" value="{{ $boost_package->base_cost }}" min="0" readonly>
                        <input type="hidden" class="form-control" name="unit_cost" id="unit_cost" step="0.0001" value="{{ $boost_package->unit_cost }}" min="0" readonly>
                        <input type="number" class="form-control" name="work_need" id="work_need" value="{{ $boost_package->work_need }}" min="1" readonly>
                    </div>

                    <div class="form-group col-md-4 col-lg-4 col-12">
                        <label class="form-label">Charge <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-append">
                                <button class="btn btn-primary" type="button">$</button>
                            </span>
                            <input type="number" class="form-control" readonly name="cost" id="cost" value="{{ $boost_package->cost }}" min="0">
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
    </script>
@endsection
