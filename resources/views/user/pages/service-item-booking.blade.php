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
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Service Payment For {{$package->title}}</h4>
                    <h6 class="card-title">Payment Amount: {{$package->price}}$</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <a href="{{route('user.service-item-create-from-earning', $package->id)}}" class="btn btn-sm btn-info mb-2">Buy From Earning</a>
                        <a href="{{route('user.service-item-create-from-deposit', $package->id)}}" class="btn btn-sm btn-success mb-2">Buy From Deposit</a>
                    </div>
                    <form action="{{ route('user.service-item-store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="">
                            <div class="form-group d-flex">
                                @foreach ($pay_accounts as $account)
                                    <div class="wallet-1 m-1" onclick="setAccount({{ $account->id }})">
                                        <img src="{{ URL::to($account->image) }}" class="mr-2" width="70" height="70" alt="{{ $account->name }}">
                                    </div>
                                @endforeach
                            </div>

                            <input type="hidden" name="deposit_account" id="deposit_account" value="">
                            <input type="hidden" name="package_id" id="package_id" value="{{$package->id}}">
                            <div id="deposit_area" style="display: none;">
                                <div class="form-group">
                                    <h4 id="deposit_account_text"></h4>
                                    <p id="deposit_account_guideline"></p>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="link" class="form-label">Amount ($)<span class="text-red">*</span></label>
                                    <input class="form-control" readonly type="number" name="amount" value="{{$package->price}}" min="0" step="0.0001" placeholder="Deposit Amount">
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="form-label">Phone <span class="text-red">*</span></label>
                                    <input class="form-control" type="text" name="phone" required placeholder="Phone Number">
                                </div>

                                <div class="form-group">
                                    <label for="transaction_id" class="form-label">Transaction ID <span class="text-red">*</span></label>
                                    <input class="form-control" type="text" name="transaction_id" required placeholder="Transaction ID">
                                </div>

                                <div class="form-group">
                                    <label for="receipt" class="form-label">Receipt <span class="text-red">*</span></label>
                                    <input class="form-control" type="file" name="receipt" required id="screen_shot_select_image" accept="image/*" onchange="readURL();">
                                    <div class="gallery-img">
                                        <img class="mt-2" id="screen_shot_show_image" id="photo" src="#" style="display:none;" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary mt-4 mb-0">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
                    // $('#deposit_account_guideline').html(data['guideline']);
                },
            });
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
        
        function earningToDepositAlert(){
            $('#earning_to_deposit_error').show();
        }
    </script>
@endsection
