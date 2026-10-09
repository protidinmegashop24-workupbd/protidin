@extends('user.layouts.master')
@section('css')
    <link href="{{ asset('frontend/user/assets/css/nucleo.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/fontawesome-all.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/argon.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/myapp.css') }}" id="app-style" rel="stylesheet" type="text/css">
    {{-- select2 --}}
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .container-fluid {
            padding-right: 0px;
            padding-left: 0px;
        }
        
        .ads-rate-area{
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            display: flex;
            justify-content: space-between;
        }
        .ads-rate-area-active{
            border: 1px solid #31bd21 !important;
        }
        
        .package-area img{
            width: 100%;
            height: 165px;
        }
        
        .cost-area{
            background: #4285f4;
            padding: 6px 8px;
            border-radius: 3px;
            color: #ffffff;
        }
        
        .cost_currency{
            position: relative;
            bottom: 6px;
            font-size: 12px;
            margin-left: 5px;
        }
    </style>
@endsection
@section('user-content')

    <div class="card card-body mt-2 p-3" style="background-color: rgb(248, 249, 254);height: auto !important;">
        <div class="card-header row">
            <div class="card-title col-md-6 col-12 text-center" style="font-weight: 500; color: green; font-size: 20px;">Investment Packages</div>
            <div class="col-md-6 col-12 p-0">
                <div class="input-group" style="height: 70px;">
                  <input type="text" class="form-control" placeholder="Service name/title" aria-label="Service name/title" id="filter_item" onkeyup="filterItem()" onchange="filterItem()" onpaste="filterItem()" style="height: 70px;
    font-size: 20px;
    border-color: #04633a;
    padding: 6px;">
                  <div class="input-group-append" style="">
                    <span class="input-group-text" id="basic-addon2" style="    background: #07643c;
    border-color: #07643c;
    color: #fff;"><i class="fas fa-search"></i>&nbsp;Search</span>
                  </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            @foreach($web_scripts as $package)
                <div class="col-md-4 data-area">
                    <div class="card">
                        <div class="card-header bg-primary text-white text-center">
                            <h2 class="text-white">{{$package->title}}</h2>
                        </div>
                        <div class="card-body Promoter Award_body">
                            <div class="package-area mb-2">
                                <a href="{{route('user.web-script-details',$package->slug)}}" target="_blank">
                                    <img src="{{URL::to($package->image)}}" width="100%">
                                </a>
                            </div>
                            <span>
                                <p class="m-0 mt-1" style="color: #3498DB;    font-size: 25px;"><a href="{{route('user.web-script-details',$package->slug)}}" target="_blank">Details</a></p>
                                <p class="m-0 mt-1 text-center"><span class="cost-area"><i class="fas fa-shopping-cart"></i> <apan class="cost_currency">$</apan>{{$package->price}}</span></p>
                                <!--<p class="m-0">-->
                                <!--    <a class="btn btn-info" href="{{route('user.web-script-details',$package->slug)}}" target="_blank"><i class="fa fa-eye"></i> Details</a>-->
                                <!--</p>-->
                                <div class="text-center">
                                    <hr>
                                    <a class="btn btn-info" href="{{$package->live_preview_url}}" target="_blank"><i class="fa fa-eye"></i> Preview</a>
                                    <button class="btn btn-primary" onclick="buySelect('{{$package->id}}','{{$package->title}}','{{$package->price}}')"><i class="fa fa-cart-plus"></i> Buy </button>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <div class="modal fade" id="modalBuy" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <h3>Are you confirm to Investment?</h3>
                            <br>
                            <input type="hidden" id="investment_package_id" value="">
                            <label for="balanceType">Select Balance</label>
                            <select class="form-control" id="balanceType">
                                <option value="deposit">Deposit Balance</option>
                                <option value="earning">Earning Balance</option>
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
        </div>
    </div>

@endsection
@section('js')
    <!-- Select2 -->
    <script src="{{ asset('backend/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {
            $('.select2').select2();
        });
        function filterItem(){
            var value = $('#filter_item').val().toLowerCase();
            $(".data-area").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }
        
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
                url: "{{ route('user.web-script-buy-confirm') }}",
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
