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
        
        .countdown {
            text-transform: uppercase;
            font-weight: bold;
        }
        
        .countdown span {
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
            font-size: 3rem;
            margin-left: 0.8rem;
        }
        
        .countdown span:first-of-type {
            margin-left: 0;
        }
        
        .countdown-circles {
            text-transform: uppercase;
            font-weight: bold;
        }
        
        .countdown-circles span {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .countdown-circles span:first-of-type {
            margin-left: 0;
        }
        
        .bg-gradient-4 {
            background: #007991;
            background: -webkit-linear-gradient(to right, #007991, #78ffd6);
            background: linear-gradient(to right, #007991, #78ffd6);
        }
        
        .rounded {
            border-radius: 1rem !important;
        }
        
        .btn-demo {
            padding: 0.5rem 2rem !important;
            border-radius: 30rem !important;
            background: rgba(255, 255, 255, 0.3);
            color: #fff;
            text-transform: uppercase;
            font-weight: bold !important;
        }
        
        .btn-demo:hover, .btn-demo:focus {
            color: #fff;
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
@endsection
@section('user-content')

    <div class="card card-body mt-2 p-3" style="background-color: rgb(248, 249, 254);height: auto !important;">
        <div class="card-header">
            <div class="card-title text-center" style="font-weight: 500; color: green; font-size: 20px;">Lottery System</div>
        </div>
        <div class="row justify-content-center mt-2">
            @foreach($datas as $data)
                <div class="col-md-6 data-area">
                    <div class="card">
                        <div class="card-body rounded bg-gradient-4 text-white shadow text-center">
                            <div id="clock-c" class="countdown py-4"></div>
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
                            <input type="hidden" id="lottery_id" value="">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.countdown/2.2.0/jquery.countdown.min.js" integrity="sha512-lteuRD+aUENrZPTXWFRPTBcDDxIGWe5uu0apPEn+3ZKYDwDaEErIK9rvR0QzUGmUQ55KFE2RqGTVoZsKctGMVw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $( document ).ready(function() {
            function get15dayFromNow() {
                console.log('dfgd');
                return new Date(new Date().valueOf() + 15 * 24 * 60 * 60 * 1000);
            }
        
            $('#clock-c').countdown(get15dayFromNow(), function(event) {
                var $this = $(this).html(event.strftime(''
                + '<span class="h1 font-weight-bold">%D</span> Day%!d'
                + '<span class="h1 font-weight-bold">%H</span> Hr'
                + '<span class="h1 font-weight-bold">%M</span> Min'
                + '<span class="h1 font-weight-bold">%S</span> Sec'));
            });
        });

    
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
            $('#lottery_id').val(package_id);
            $('#txt_pkg_type').text(type);
            $('#txt_pkg_price').text(price);
            $('#modalBuy').modal('toggle');
        }
        
        function closeModal(){
            $('#lottery_id').val('');
            $('#modalBuy').modal('toggle');
        }
        
        $(document).on('click', '#btnBuyConfirm', function () {
            let tho = $(this);
            tho.prop('disabled', true);
            var balanceType = $('#balanceType').val();
            var lottery_id = $('#lottery_id').val();

            jQuery.ajax({
                url: "{{ route('user.lottery-buy-confirm') }}",
                type: "POST",
                data: {_token: '{{csrf_token()}}',lottery_id: lottery_id,balanceType:balanceType},
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
