@extends('user.layouts.master')
@section('css')
    <link href="{{ asset('frontend/user/assets/css/nucleo.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/fontawesome-all.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/argon.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/myapp.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <style>
        .container-fluid {
            padding-right: 0px;
            padding-left: 0px;
        }
        
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
    <div class="row justify-content-center mt-2">
        <div class="col-md-8 col-lg-8 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Service Payment For {{$package->title}}</h4>
                    <h6 class="card-title">Payment Amount: {{$package->price}}$</h6>
                </div>
                <div class="card-body">
                    <div>
                        <img src="{{URL::to($package->image)}}" width="100%">
                    </div>
                    <div class="mt-2">
                        <p>{!! $package->details !!}</p>
                    </div>
                    <div class="text-center mt-2">
                        <button class="btn btn-primary" onclick="buySelect('{{$package->id}}','{{$package->title}}','{{$package->price}}')"><i class="fa fa-cart-plus"></i> Buy Service</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="modal fade" id="modalBuy" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>Are you confirm to Investment?</h3>
                    <br>
                    <input type="hidden" id="investment_package_id" value="">
                    <label for="balanceType">Select Balance</label>
                    <select class="form-control form-control-sm form-control-bordered" id="balanceType">
                        <option value="earning">Earning Balance</option>
                        <option value="deposit">Deposit Balance</option>
                    </select>
                    <br>
                    <p>
                        <b>Package:</b> <span id="txt_pkg_type"></span>
                        <br>
                        <b>Price:</b> $<span id="txt_pkg_price">0</span>
                    </p>
                    <br>
                    <!--<p class="text-info">Note: You can buy one package and never change this package within one year .</p>-->
    
                    <div class="alert alert-dismissible" style="display:none;" role="alert" id="topAlertMainDeposit">
                        <span class="alert-text"><strong><span id="error-text-deposit"></span></strong></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnBuyConfirm">Yes, Confirm</button>
                    <button type="button" class="btn btn-default" onclick="closeModal()">No</button>
                </div>
            </div>
        </div>
   </div>
@endsection
@section('js')
    <script>
        var packageType="none";
        function buySelect(package_id,type,price){
            packageType=type;
            $('#topAlertMainDeposit').hide();
            $('#investment_package_id').val(package_id);
            $('#txt_pkg_type').text(type);
            $('#txt_pkg_price').text(price);
            $('#modalBuy').modal('toggle');
        }
        
        function closeModal(){
            $('#investment_package_id').val('');
            $('#modalBuy').modal('toggle');
        }
        
        $(document).on('click', '#btnBuyConfirm', function () {
            let tho = $(this);
            tho.prop('disabled', true);
            var balanceType = $('#balanceType').val();
            var package_id = $('#investment_package_id').val();

            jQuery.ajax({
                url: "{{ route('user.service-item-buy-confirm') }}",
                type: "POST",
                data: {_token: '{{csrf_token()}}',package_id: package_id,balanceType:balanceType},
                success: function (data) {
                    // console.log(data);
                    if (data.error == 1) {
                        responseMessage(0, data.msg);
                    } else {
                        responseMessage(1, data.msg);
                        setTimeout(function(){
                            location.reload();
                        }, 2000);
                    }
                    tho.prop('disabled', false);
                },
                error: function (data) {
                    tho.prop('disabled', false);
                }
            });
        });
        
        function responseMessage(type, msg) {
            const topAlertMainDeposit = $('#topAlertMainDeposit');
            const error_text_deposit = $('#error-text-deposit');
            switch (type) {
                case 1:
                    topAlertMainDeposit.removeClass('alert-danger');
                    topAlertMainDeposit.addClass('alert-success');
                    error_text_deposit.text(msg);
                    topAlertMainDeposit.fadeIn();
                    break;
                case 0:
                    topAlertMainDeposit.removeClass('alert-success');
                    topAlertMainDeposit.addClass('alert-danger');
                    error_text_deposit.text(msg);
                    topAlertMainDeposit.fadeIn();
                break;
            }
        }
    </script>
@endsection
