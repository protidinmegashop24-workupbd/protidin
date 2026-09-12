@extends('user.layouts.master')
@section('css')
    <style>
    
    
    @media only screen and (max-width: 767px) {
    .card-body {
        width: 120% !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        margin-left: -33px !important;
    }
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
        /****Wizard-Main****/
        .step-container-main {
            margin: 10px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #eaeaea;
            background-color: white;
            position: relative;
        }
        
        
        .wrapper-step-bar-main {
            width: 100%;
            overflow:hidden;
            margin: 10px;
            display: inline-block;
        }
        
        .step-bar-single {
        
        }
        
        .step-bar-single li {
            list-style-type: none;
            float: left;
            width: 22%;
            position: relative;
            text-align: center;
        }
        
        .step-bar-single li:hover {
            cursor:pointer;
        
        }
        
        .step-bar-single li:before {
            content: " ";
            line-height: 40px;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            border: 1px solid #ddd;
            display: block;
            text-align: center;
            margin: 0 auto 10px;
            background-color: white;
            color: black;
            position: relative;
            z-index: 2;
        }
        
        .step-bar-single li:after {
            content: "";
            position: absolute;
            width: 100%;
            height: 4px;
            background-color: #ddd;
            top: 20px;
            left: -50%;
            z-index: 1;
        }
        
        .step-bar-single li:first-child:after {
            content: none;
        }
        
        .step-bar-single li:nth-child(1):before {
            content: "1";
        
        }
        .step-bar-single li:nth-child(2):before {
            content: "2";
        
        }
        .step-bar-single li:nth-child(3):before {
            content: "3";
        }
        .step-bar-single li:nth-child(4):before {
            content: "4";
        }
        
        .step-bar-single li.active {
            color: {{about_us()->job_create_point}};
        }
        
        .step-bar-single li.active:before {
            border-color: {{about_us()->job_create_point}};
            background-color: {{about_us()->job_create_point}};
            color: white;
        }
        
        .step-bar-single .active:after {
            background-color: {{about_us()->job_create_point}};
        }
        
        .step-container {
            display:none;
            padding: 10px;
        }
        
        .step-button {
            padding: 10px 20px 10px 20px;
            color: white;
            background: {{about_us()->job_create_next}};
            outline: none;
            border:none;
            border-radius:5px;
            display: inline-block;
        }
        
        .step-button-back {
            padding: 10px 20px 10px 20px;
            color: white;
            background: {{about_us()->job_create_back}};
            outline: none;
            border:none;
            border-radius:5px;
            display: inline-block;
        }
        .step-button + .step-button-disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .step-button-container {
            text-align: right;
            margin: 50px 10px 10px 10px;
        }
        
        /*Wizard Component*/
        /****** POST PAGE   ******/
        .myPostWizard {
            padding: 10px;
        }
        .myPostWizard input[type=radio] {
            display: none;
        }
        .myPostWizard .zone-item-div {
            margin: 10px 2px 10px 2px;
            display: inline-block;
        }
        
        .myPostWizard .zone-item {
            background-color: #fff;
            border-radius: 2px;
            border: 1px solid #eaeaea;
            padding: 1px 6px 1px 6px;
            outline: none;
            font-size: 14px;
            font-weight:bold;
            color:black;
            cursor: pointer;
        }
        
        .myPostWizard input[type=radio]:checked+label {
            background-color: #22ab59;
            color: white;
            border-radius: 3px;
            outline: none;
            border: none;
        }
        
        .myPostWizard input[type=radio][name=childCategory]:checked+label {
           background-color: #fec30f !important;
            border-radius: 15px !important;
            color: white;
            outline: none;
            border: none;
        }
        
        .myPostWizard input[type=checkbox] {
            display: none;
        }
        
        .exclude-country {
            border-radius:20px;
        }
        
        .myPostWizard .exclude-country-label {
            display: inline-block;
            background-color: #eaeaea !important;
            border-radius: 3px;
            border: 1px solid #eaeaea;
            padding: 1px 5px 1px 5px;
            outline: none;
            font-size:14px;
            cursor: pointer;
        }
        
        .myPostWizard input[type=checkbox]:checked+label {
            background-color: red !important;
            color: white;
            border-radius: 15px !important;
            outline: none;
            border: none;
        }
        
        .myPostWizard .col001 {
            display: inline-block;
            width: fit-content;
        }
        
        .myPostWizard .col022 {
            display: inline-block;
        }
        
        .myPostWizard .category-item-div {
            margin: 10px 10px 20px 10px;
            display: inline-block;
        }
        
        .myPostWizard .cchild-item {
        
        }
        
        .over-balance {
            background-color: #FCE3E5 !important;
        }
        
        .myPostWizard .block_balance_board input {
            outline: none;
            border-radius: 5px;
            border: 1px solid #eaeaea;
            padding: 10px;
        }
        
        .block_balance_board {
            box-shadow: 1px 1px 1px 1px rgba(0,0,0,0.2);
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #eaeaea;
        }
        
        #estimatedJobCost[readonly] {
        background-color: white;
        }
        
        .tab-title-header {
            font-weight: 600;
        }
        
        .tab-title-header {
            display: none;
        }
        
        .bg-softer {
            background-color: #F5F7FA;
            padding: 10px;
            border-radius: 5px;
        }
        
        .bg-softer textarea {
            border: none;
            border-radius: 5px;
            outline: none;
            padding: 4px;
            background-color: rgba(245, 247, 250, 0.85);
        }
        
        @media  only screen and (max-width: 768px) {
            /* For mobile phones: */
            .step-top-selector .tab-title {
                display: none;
            }
            .tab-title-header {
                display: block;
            }
        
            .myPostWizard .zone-item-div {
                margin: 5px;
                display: inline-block;
            }
        
            .myPostWizard .category-item-div {
                margin: 5px;
                display: inline-block;
            }
        
        }
        
        .card-title {
            margin-top: 8px;
        }
        
        
    </style>
@endsection
@section('user-content')
    <div class="card mt-2">
        <div class="card-header" style="border-radius: 0px 0px 10px 10px;">
            <h4 class="card-title text-center" style="font-weight: 500; color: green; font-size: 27px;">Post New Job</h4>
        </div>
        <div class="card-body">
            <form class="myPostWizard" id="job_creat_form" action="{{ route('user.job-post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="job_fee" value="{{ $job_fee->fee }}">
                <input type="hidden" id="minimum_cost" value="{{min_job_fee()}}">
                <input type="hidden" class="form-control" id="my_deposit_balance" value="{{ Auth::user()->deposit_balance }}">
                
                <div class="step-container-main">

                    <div style="text-align: center">
                        <div class="wrapper-step-bar-main">
                            <ul class="step-bar-single">
                                <li onclick="nextStep(1)" id="step-top-1" class="step-top-selector active"><span class="tab-title">Select Location</span></li>
                                <li onclick="nextStep(2)" id="step-top-2" class="step-top-selector"><span class="tab-title">Select Category</span></li>
                                <li id="step-top-3" class="step-top-selector"><span class="tab-title">Job Information</span></li>
                                <li id="step-top-4" class="step-top-selector"><span class="tab-title">Budget &amp; Setting</span></li>
                            </ul>
                        </div>
                    </div>

                    <br>
                    
                    <!-- STEP 1-->
                    <div id="step-1" class="step-container" style="display:block">
                        <p class="tab-title-header">Select Location</p>
                        <div class="zone_list">
                            @foreach($continents as $key=>$continent)
                                @if($key == 0)
                                    @php
                                        $cnt_id = $continent->id;
                                    @endphp
                                @endif
                                <div class="zone-item-div">
                                    <input type="radio" class="continent-input" name="continent" value="{{$continent->id}}" id="radio-int-{{ $continent->id }}" @if($key == 0) checked="" @endif>
                                    <label class="zone-item" for="radio-int" id="radio-int-label-{{ $continent->id }}" onclick="getCountry('{{ $continent->id }}')">{{$continent->name}}</label>
                                </div>
                            @endforeach
                            
                        </div>
        
                        <div class="form-group">
                            <label class="text-sm font-weight-medium text-red mb-3" style="border: 2px solid #FF9E00 !important;padding: 4px;border-radius: 5px;margin-top: 16px;"> *Select countries you want to hide from the selected zone <span class="text-blue">(optional)</span></label>
                          <p class="text-blue font-weight-bold" id="ccHideNotice" style="display:none">If you want to hide any country , than you will pay extra $0 for per country !</p>  
                          <div class="btn-checkboxes btn-checkboxes--small-gutter row exclude-countries int" style="display: block;" id="continent-country-area">
                                @if(continent_country($cnt_id)->count() > 0)
                                    @foreach(continent_country($cnt_id) as $c_key=>$c_country)
                                        <div class="col001">
                                            <div class="col022">
                                                <input type="checkbox" id="ex-int-{{$c_country->country_id}}" class="custom-control-input exclude-country" name="country_id[]" value="{{$c_country->country_id}}">
                                                <label for="ex-int-{{$c_country->country_id}}" class="exclude-country-label  bg-gray border-0">{{country($c_country->country_id)}}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="step-button-container">
                            <button type="button" onclick="nextStep(2)" class="step-button">Next</button>
                        </div>
                    </div>
        
                    <!-- STEP 2-->
                    <div id="step-2" class="step-container">
                        <p class="tab-title-header">Select Category</p>
        
                            @foreach ($categorys as $key=>$category)
                                @if($key == 0)
                                    @php
                                        $cat_id = $category->id;
                                    @endphp
                                @endif
                                <div class="category-item-div cparent-{{ $category->id }} m-0" onclick="getSubCategory('{{ $category->id }}')">
                                    <input type="radio" name="category_id" value="{{ $category->id }}" id="radio-parent-{{ $category->id }}" @if($key == 0) checked="" @endif">
                                    <label class="zone-item" for="radio-parent-{{ $category->id }}">{{ $category->name }}</label>
                                </div>
                            @endforeach
                        
        
                        <hr color="red" style="height:3px">
                        <h4 class="text-green">Select the subcategory</h4>
        
                        <div id="sub-category-area">
                            @if(sub_categorys($cnt_id)->count() > 0)
                                @foreach(sub_categorys($cnt_id) as $c_key=>$sub_category)
                                    <div class="category-item-div cchild-item {{$sub_category->id}} m-0" onclick="selectSubCategory('{{ $sub_category->id }}')">
                                        <input data-cpc="0.02" data-upload="0" type="radio" name="sub_category" value="{{$sub_category->id}}" id="radio-child-{{$sub_category->id}}"><label class="zone-item" for="radio-child-{{$sub_category->id}}">{{$sub_category->name}}</label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        
                        <div class="step-button-container">
                            <button type="button" onclick="nextStep(1)" class="step-button-back">Back</button>
                            <button type="button" onclick="nextStep(3)" id="step-button-category-next" class="step-button step-button-disabled" disabled="">Next
                        </button>
                    </div>
                </div>
        
        
                    <!-- STEP 3-->
                    <div id="step-3" class="step-container">
                        <p class="tab-title-header">Job Information</p>
                        
                        <style>
                            #count_message_title {
                                background-color: smoke;
                                margin-top: -33px;
                                margin-right: 5px;
                            }
                            .post-job-work-step:first-child .stpsCloseBtn {
                                display:none;
                            }
                            .post-job-work-step:last-child {
                                display:none;
                            }
                        </style>
            
                          
                          
                        <div class="form-group mb-5">
                            <label for="jobTitle" style="color:#0f7235">Write an accurate job Title</label>
                            <input type="text" class="form-control" id="jobTitle" name="title" placeholder="Enter your job title" maxlength="30" required="">
                            <div class="input-group-append" style="float:right">
                               <span class="label label-default text-red text-muted" id="count_message_title">0 / 30</span>
                           </div>
                        </div>
            
                        <div class="form-group">
                            <label style="color:#0f7235">What specific tasks need to be Completed</label>
                            <div class="text-label-join mb-5 bg-softer">
                                <textarea class="form-control" name="specific_task[]" id="specific_task" cols="30" rows="5" ></textarea>
                            </div>
                        </div>
            
                        <div class="form-group">
                            <label style="color:#4B0082">Required proof the job was Completed </label>
                            <div class="text-label-join mb-5 bg-softer">
                                <textarea class="form-control" name="required_proof" id="proof" cols="30" rows="5"></textarea>
                            </div>
                        </div>
            
                        <div class="form-group bg-softer">
                            <p style="color:#136e36;margin-bottom:0">Thumbnail Image(optional)</p>
                            <input type="file" class="form-control" name="thumbnail" id="screen_shot_select_image" accept="image/x-png,image/jpg,image/jpeg" onchange="readURL();">
                            <div class="gallery-img">
                                <img class="mt-2" id="screen_shot_show_image" src="#" style="display:none;" />
                            </div>
                        </div>
                
                        <div class="step-button-container">
                            <button type="button" onclick="nextStep(2)" class="step-button-back">Back</button>
                            <button type="button" onclick="nextStep(4)" id="step-button-category-next-info" class="step-button step-button-disabled" disabled="">Next</button>
                        </div>
                    </div>
            
        
                    <!-- STEP 4-->
                    <div id="step-4" class="step-container">
                        <p class="tab-title-header">Budget &amp; Setting</p>
                    
                        <div style="margin-top: 20px">
                            <!--<p id="spend1" class="font-weight-bold text-red spend1 ml-3"><i class="fas fa-exclamation-triangle"></i>-please Increase your worker!</p><p>-->
                                <!--</p><p class="font-weight-bold text-blue" id="jpySshot" style="display: none">--For Youtube Minimum 1 Screenshot Mandatory</p>-->
                    
                                <div class="row">
                                    <div class="col-md-6" style="padding: 30px; ">
                                        <div class="row">
                                            <h5 label="" class="col-sm-6" style="color:#27954f;"><b>Worker Need</b></h5>
                                            <div class="col-sm-6">
                                                <input type="number" class="form-control" name="worker_need" id="worker_need" value="5" min="1" onchange="chnageWorkerNeed()" onkeyup="chnageWorkerNeed()">
                                            </div>
                                        </div>
                    
                                        <div class="row" style="margin-top: 30px">
                                            <h5 label="" class="col-sm-6" style="color:#27954f;"><b>Each worker Earn</b></h5>
                                            <div class="col-sm-6">
                                                <input type="hidden" class="form-control" id="each_worker_min_earn" value="0">
                                                <input type="number" class="form-control" name="each_worker_earn" id="each_worker_earn" step="0.0001" value="0" min="0" onchange="chnageWorkerEarn()" onkeyup="chnageWorkerEarn()">
                                            </div>
                                        </div>
                                        
                                        <div class="row" style="margin-top: 30px">
                                            <h5 label="" class="col-sm-6" style="color:#27954f;"><b>Require Screenshots</b></h5>
                                            <div class="col-sm-6">
                                                <input type="hidden" class="form-control" id="old_required_screenshots_cost" step="0.0001" value="0" min="0" max="50">
                                                <input type="number" class="form-control" name="required_screenshots" id="required_screenshots" onchange="chnageScreenShoot('{{screenshoot_charge()}}')" onkeyup="chnageScreenShoot('{{screenshoot_charge()}}')" value="0" min="0" max="1000">
                                            </div>
                                        </div>
                    
                                        <div class="row" style="margin-top: 30px">
                                            <h5 label="" class="col-sm-6 " style="color:#27954f;"><b>Estimated Day</b></h5>
                                            <div class="col-sm-6">
                                                <input type="number" class="form-control" name="estimited_day" value="3" min="1">
                                            </div>
                                        </div>
                                    </div>
                    
                    
                    
                                <div class="col-md-6" style="text-align: center;padding: 10px">
                                    <div class="block_balance_board">
                                        <h5 style="color:#000;font-size: 13px;font-weight: bold;">Estimated Job Cost</h5>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text drk_white_theme" style="font-size:20px">$</span>
                                            </div>
                                            <input type="number" class="form-control" readonly name="budget" id="budget" value="0" min="0">
                                        </div>
                                        <p class="font-weight-bold text-red spend1" style="font-size: 14px;" id="job_cost_alert">Minimum spend ${{min_job_fee()}}</p>
                                        <p></p>
                                        <div class="text-right"><a href="{{route('user.deposit')}}" class="btn btn-success btn-sm text-dark" id="btnplsDeposit" style="background:#fec30f;border:none;display:none">Please! Deposit</a></div>
                                    </div>
                                </div>
                            </div>
                    
                            <div class="step-button-container">
                                <button type="button" onclick="nextStep(3)" class="step-button-back">Back</button>
                                <button type="button" onclick="submitJob()" class="step-button" id="postSubmitNew" style="background:#27954f;border-radius:20px;outline: none;">Submit</button>
                            </div>
                        </div>
                    
                    </div>

                </div>
                
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        function nextStep(current) {
            if (current === 1) {
                $('#step-top-4').removeClass('active');
                $('#step-top-3').removeClass('active');
                $('#step-top-2').removeClass('active');
                $('#step-top-1').addClass('active');
            } else if (current === 2) {
                $('#step-top-4').removeClass('active');
                $('#step-top-3').removeClass('active');
                $('#step-top-2').addClass('active');
                $('#step-top-1').addClass('active');
            } else if (current === 3) {
                $('#step-top-4').removeClass('active');
                $('#step-top-3').addClass('active');
                $('#step-top-2').addClass('active');
                $('#step-top-1').addClass('active');
            } else if (current === 4) {
                $('#step-top-4').addClass('active');
                $('#step-top-3').addClass('active');
                $('#step-top-2').addClass('active');
                $('#step-top-1').addClass('active');
            }
        
            if (current === 1) {
                $('.step-container').css('display', 'none');
                $('#step-' + current).css('display', 'block');
            } else if (current === 2) {
                $('.step-container').css('display', 'none');
                $('#step-' + current).css('display', 'block');
            } else if (current === 3) {
                $('.step-container').css('display', 'none');
                $('#step-' + current).css('display', 'block');
            } else if (current === 4) {
                $('.step-container').css('display', 'none');
                $('#step-' + current).css('display', 'block');
            }
        }
    
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
                    $('#country').html(data);
                },
            });
        }

        function getCountry(continet_id){
            $(".continent-input").prop('checked', false);
            $("#radio-int-"+continet_id).prop('checked', true);
            
            $.ajax({
                url: "{{ route('user.get-continent-country') }}",
                type:"POST",
                data:{
                    _token: '{{csrf_token()}}',
                    continet_id: continet_id,
                },
                success:function(data) {
                    $('#continent-country-area').html(data);
                },
            });
        }
        
        function getSubCategory(category_id){
            $.ajax({
                url: "{{ route('user.get-sub-categorys') }}",
                type:"POST",
                data:{
                    _token: '{{csrf_token()}}',
                    category_id: category_id,
                },
                success:function(data) {
                    $('#sub-category-area').html(data);
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
                    var old_required_screenshots_cost = parseFloat($('#old_required_screenshots_cost').val());
                    var minimum_cost = parseFloat(data);
                    // $('#minimum_cost').val(minimum_cost);
                    $('#each_worker_earn').val(minimum_cost);
                    $('#each_worker_min_earn').val(minimum_cost);
                    var total_cost = parseFloat(minimum_cost * worker_need);
                    var fee = parseFloat((parseFloat(total_cost) * parseFloat(job_fee)) / 100);
                    var total = (total_cost + fee + old_required_screenshots_cost).toFixed(4);
                    $('#budget').val(total);
                },
            });
        }

        function chnageWorkerNeed(){
            var my_deposit_balance = parseInt($('#my_deposit_balance').val());
            var old_required_screenshots_cost = parseFloat($('#old_required_screenshots_cost').val());
            var job_fee = parseInt($('#job_fee').val());
            var worker_need = parseFloat($('#worker_need').val());
            var each_worker_earn = parseFloat($('#each_worker_earn').val());
            var total_cost = parseFloat(each_worker_earn * worker_need);
            var fee = parseFloat((parseFloat(total_cost) * parseFloat(job_fee)) / 100);
            var total = (total_cost + fee + old_required_screenshots_cost).toFixed(4);
            $('#budget').val(total);
            
            if(my_deposit_balance < parseFloat(total)){
                $('#postSubmitNew').hide();
                $('#btnplsDeposit').show();
            }else{
                $('#postSubmitNew').show();
                $('#btnplsDeposit').hide();
            }
        }

        function chnageWorkerEarn(){
            var my_deposit_balance = parseInt($('#my_deposit_balance').val());
            var old_required_screenshots_cost = parseFloat($('#old_required_screenshots_cost').val());
            var job_fee = parseInt($('#job_fee').val());
            var worker_need = parseFloat($('#worker_need').val());
            var each_worker_earn = parseFloat($('#each_worker_earn').val());
            var each_worker_min_earn = parseFloat($('#each_worker_min_earn').val());
            
            var total_cost = parseFloat(each_worker_earn * worker_need);
            var fee = parseFloat((parseFloat(total_cost) * parseFloat(job_fee)) / 100);
            var total = (total_cost + fee + old_required_screenshots_cost).toFixed(4);
            $('#budget').val(total);
            
            if(my_deposit_balance < parseFloat(total)){
                $('#postSubmitNew').hide();
                $('#btnplsDeposit').show();
            }else{
                $('#postSubmitNew').show();
                $('#btnplsDeposit').hide();
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
                    $('#task_need_to_completed_area').append(data);
                },
            });
        }
        
        function submitJob(){
            var minimum_cost = parseFloat($('#minimum_cost').val());
            var budget = parseFloat($('#budget').val());
            
            var each_worker_earn = parseFloat($('#each_worker_earn').val());
            var each_worker_min_earn = parseFloat($('#each_worker_min_earn').val());
            
            if(minimum_cost > budget){
                $('#job_cost_alert').html('Minimum cost should be $'+minimum_cost);
                $('#job_cost_alert').addClass('text-danger');
            }else if(each_worker_earn < each_worker_min_earn){
                alert('You can not decrease cost from minimum cost of per worker cost!');
            }else{
                $('#job_cost_alert').html('');
                $('#job_cost_alert').removeClass('text-danger');
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

        function chnageScreenShoot(charge){
            var my_deposit_balance = parseInt($('#my_deposit_balance').val());
            var old_required_screenshots_cost = parseFloat($('#old_required_screenshots_cost').val());
            var required_screenshots = parseInt($('#required_screenshots').val());
            if(required_screenshots == '' || !$.isNumeric(required_screenshots)){
                required_screenshots = parseInt('0');
            }
            if(required_screenshots > 50){
                $('#required_screenshots').val(50);
            }
            var sh_charge = parseFloat(charge) * required_screenshots;
            var budget = parseFloat($('#budget').val());
            var total = ((budget - old_required_screenshots_cost) + sh_charge).toFixed(4);
            $('#budget').val(total);
            
            if(my_deposit_balance < parseFloat(total)){
                $('#postSubmitNew').hide();
                $('#btnplsDeposit').show();
            }else{
                $('#postSubmitNew').show();
                $('#btnplsDeposit').hide();
            }
            $('#old_required_screenshots_cost').val(sh_charge);
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
        
        function selectSubCategory(subcat_id){
            $('#step-button-category-next').prop('disabled', false);
            $('#step-button-category-next').removeClass('step-button-disabled');
            
            var worker_need = parseFloat($('#worker_need').val());
            var job_fee = parseInt($('#job_fee').val());
            $.ajax({
                url: "{{ route('user.get-sub-category-price') }}",
                type:"POST",
                data:{
                    _token: '{{csrf_token()}}',
                    sub_category: subcat_id,
                },
                success:function(data) {
                    var old_required_screenshots_cost = parseFloat($('#old_required_screenshots_cost').val());
                    var minimum_cost = parseFloat(data);
                    // $('#minimum_cost').val(minimum_cost);
                    $('#each_worker_earn').val(minimum_cost);
                    $('#each_worker_min_earn').val(minimum_cost);
                    var total_cost = parseFloat(minimum_cost * worker_need);
                    var fee = parseFloat((parseFloat(total_cost) * parseFloat(job_fee)) / 100);
                    var total = (total_cost + fee + old_required_screenshots_cost).toFixed(4);
                    $('#budget').val(total);
                    
                    $('#step-top-3').attr('onClick', 'nextStep(3);');
                },
            });
        }
        
        
        $(document).on('change', '#jobTitle', function () {
            titleAdded = true;
            if (titleAdded && proofAdded && workReqAdded) {
                $('#step-button-category-next-info').prop('disabled', false);
                $('#step-button-category-next-info').removeClass('step-button-disabled');
            } else {
                $('#step-button-category-next-info').prop('disabled', true);
                $('#step-button-category-next-info').addClass('step-button-disabled');
            }
        });
        
        $(document).on('change', '#work1', function () {
            if ($(this).val() !== " ") {
                workReqAdded = true;
            } else {
                workReqAdded = false;
            }
            if (titleAdded && proofAdded && workReqAdded) {
                $('#step-button-category-next-info').prop('disabled', false);
                $('#step-button-category-next-info').removeClass('step-button-disabled');
            } else {
                $('#step-button-category-next-info').prop('disabled', true);
                $('#step-button-category-next-info').addClass('step-button-disabled');
            }
        });
        
        $(document).on('change', '[name=workProve]', function () {
            if ($(this).val() !== " ") {
                proofAdded = true;
            } else {
                proofAdded = false;
            }
            if (titleAdded && proofAdded && workReqAdded) {
                $('#step-button-category-next-info').prop('disabled', false);
                $('#step-button-category-next-info').removeClass('step-button-disabled');
            } else {
                $('#step-button-category-next-info').prop('disabled', true);
                $('#step-button-category-next-info').addClass('step-button-disabled');
            }
        });
        
        
        var text_max = 30;
        $('#count_message_title').html('0 / ' + text_max );
        $('#jobTitle').keyup(function() {
            var text_length = $('#jobTitle').val().length;
            var text_remaining = text_max - text_length;
            $('#count_message_title').html(text_length + ' / ' + text_max);
        });
        
        $("#jobTitle").focusout(function(){
            var jobTitle = $('#jobTitle').val();
            var specific_task = $('#specific_task').val();
            var proof = $('#proof').val();
            if(jobTitle !== "" && specific_task !== "" && proof !== ""){
                $('#step-button-category-next-info').prop('disabled', false);
                $('#step-button-category-next-info').removeClass('step-button-disabled');
                
                $('#step-top-4').attr('onClick', 'nextStep(4);');
            }else {
                $('#step-button-category-next-info').prop('disabled', true);
                $('#step-button-category-next-info').addClass('step-button-disabled');
                
                $('#step-top-4').removeAttr('onclick');
            }
        });
        
        $("#specific_task").focusout(function(){
            var jobTitle = $('#jobTitle').val();
            var specific_task = $('#specific_task').val();
            var proof = $('#proof').val();
            if(jobTitle !== "" && specific_task !== "" && proof !== ""){
                $('#step-button-category-next-info').prop('disabled', false);
                $('#step-button-category-next-info').removeClass('step-button-disabled');
                
                $('#step-top-4').attr('onClick', 'nextStep(4);');
            }else {
                $('#step-button-category-next-info').prop('disabled', true);
                $('#step-button-category-next-info').addClass('step-button-disabled');
                
                $('#step-top-4').removeAttr('onclick');
            }
        });
        
        $("#proof").focusout(function(){
            var jobTitle = $('#jobTitle').val();
            var specific_task = $('#specific_task').val();
            var proof = $('#proof').val();
            if(jobTitle !== "" && specific_task !== "" && proof !== ""){
                $('#step-button-category-next-info').prop('disabled', false);
                $('#step-button-category-next-info').removeClass('step-button-disabled');
                
                $('#step-top-4').attr('onClick', 'nextStep(4);');
            }else {
                $('#step-button-category-next-info').prop('disabled', true);
                $('#step-button-category-next-info').addClass('step-button-disabled');
                
                $('#step-top-4').removeAttr('onclick');
            }
        });
    </script>
@endsection
