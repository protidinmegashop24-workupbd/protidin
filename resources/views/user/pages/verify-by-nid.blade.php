@extends('user.layouts.master')
@section('css')
    <!-- Include any external CSS libraries if necessary -->
    <link href="{{ asset('frontend/user/assets/css/custom.css') }}" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        /* Card Styling */
        .custom-card {
            border: none;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.5s;
            margin-top: 50px;
        }

        .custom-card:hover {
            transform: translateY(-10px);
        }

        .custom-card-body {
            padding: 40px;
        }

        .custom-card-body h6 {
            font-size: 22px;
            color: #333;
            font-weight: 600;
            margin-bottom: 30px;
        }

        /* Button Styling */
        .btn-verify {
            background: linear-gradient(45deg, #f093fb, #f5576c);
            color: #fff !important;
            padding: 12px 25px;
            font-size: 18px;
            border-radius: 50px;
            transition: background 0.3s ease-in-out;
            border: none;
        }

        .btn-verify:hover {
            background: linear-gradient(45deg, #f5576c, #f093fb);
            color: #fff;
        }

        /* Form Styling */
        .form-group label {
            font-weight: 500;
            font-size: 16px;
            color: #555;
        }

        .form-control {
            border-radius: 30px;
            padding: 12px 20px;
            font-size: 16px;
        }

        /* Alert Styling */
        .alert-success {
            background: #28a745;
            color: #fff;
            font-size: 18px;
            font-weight: 500;
            border-radius: 30px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .alert-success i {
            font-size: 24px;
            margin-right: 10px;
        }

        /* Paragraph Styling */
        .info-text {
            font-size: 16px;
            color: #333;
            background: #ffe9e9;
            border-left: 5px solid #ff6b6b;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            .custom-card-body {
                padding: 30px;
            }

            .btn-verify {
                font-size: 16px;
                padding: 10px 20px;
            }

            .custom-card-body h6 {
                font-size: 20px;
            }
        }
    </style>
@endsection
@section('user-content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body custom-card-body">                       
                        
                        @if(Auth::user()->is_verified)
                            <!-- যদি একাউন্ট ভেরিফাইড হয়ে থাকে -->                            
                            <h6 class="text-center">Congratulations! Your account has verified. Now Enjoy all the features.</h6>
                            <img src="https://static.vecteezy.com/system/resources/thumbnails/047/309/918/small_2x/verified-badge-profile-icon-png.png" alt="Verified Badge" class="img-fluid d-block mx-auto" style="width: 100px; margin-top: 10px; margin-bottom: 10px;">
                                                        
                            <div class="alert alert-success text-center" role="alert" style="color: white;">
                                <a href="{{ route('user.profile') }}" style="color: white;">
                                    <span><i class="fas fa-check-circle"></i> Go To your Account</span>
                                </a>
                            </div>
                        @else
                            @if(Auth::user()->kyc_status == 'pending')
                                <div class="info-text text-center">
                                    <h2>আপনার তথ্য ভেরিফাই এর জন্য পেন্ডিং অবস্থায় আছে,একটি NID Card দিয়ে একবারই ভেরিফাই করতে পারবেন , ধন্যবাদ</h2>
                                </div>
                            @elseif(Auth::user()->kyc_status == 'approve')
                                <div class="info-text text-center">
                                    <h2>আপনার অ্যাকাউন্টটি সফলভাবে ভেরিফাই হয়েছে। একটি NID Card দিয়ে একবারই ভেরিফাই করতে পারবেন , ধন্যবাদ </h2>
                                </div>
                            @elseif(Auth::user()->kyc_status == 'unapprove')
                                <div class="info-text text-center">
                                    <h2>আপনার একাউন্টটি গ্রহণ করা হয়নি, অনুগ্রহ করে সঠিকভাবে তথ্য প্রদান করুন</h2>
                                    <p>{{Auth::user()->kyc_notice}}</p>
                                </div>
                                <form action="{{route('user.account-instant-verify-by-nid-store')}}" method="post" enctype="multipart/form-data">
                                    @csrf                                    
                                    <input type="hidden" name="kyc_id" value="{{Auth::user()->id}}">
                                    <style>
                                        .col-right{
                                            text-align:right;
                                        }
                                    </style>
                                    <div class="form-group">
                                        <label for="kyc_name" class="form-label">আপনার সম্পূর্ণ নাম (কার্ডের সাথে ম্যাচ রাখবেন) <span class="text-red">*</span></label>
                                        <input class="form-control" type="text" name="kyc_name" placeholder="আপনার সম্পূর্ণ নাম (কার্ডের সাথে ম্যাচ রাখবেন)" required value="{{Auth::user()->kyc_name}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="kyc_nid_number" class="form-label">আপনার NID কার্ড নম্বরটি (ইংরেজিতে লিখতে হবে) <span class="text-red">*</span></label>
                                        <input class="form-control" type="number" name="kyc_nid_number" placeholder="আপনার NID কার্ড নম্বরটি (ইংরেজিতে লিখতে হবে)" required value="{{Auth::user()->kyc_nid_number}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="kyc_address" class="form-label">আপনার সম্পূর্ণ ঠিকানা (কার্ডের সাথে ম্যাচ রাখবেন) <span class="text-red">*</span></label>
                                        <input class="form-control" type="text" name="kyc_address" placeholder="আপনার সম্পূর্ণ ঠিকানা (কার্ডের সাথে ম্যাচ রাখবেন)" required value="{{Auth::user()->kyc_address}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="kyc_birthday" class="form-label">কার্ডের সাথে ম্যাচ রেখে  জন্ম তারিখ (মাস-তারিখ-সাল)<span class="text-red">*</span></label>
                                        <input class="form-control" type="date" name="kyc_birthday" placeholder="জন্ম তারিখ (কার্ডের সাথে ম্যাচ রাখবেন)" required value="{{Auth::user()->kyc_birthday}}">
                                    </div>
                                    
                                    <div class="form-group">  
                                        <label for="kyc_card_type">কোনটি দিয়ে এপ্লাই করছে ?</label>                            
                                        <select name="kyc_card_type" class="form-control" id="kyc_card_type" disabled>
                                            <option value="nid" {{Auth::user()->kyc_card_type == 'nid' ? 'selected' : '' }}>এনআইডি কার্ড </option>    
                                            <option value="birth" {{Auth::user()->kyc_card_type == 'birth' ? 'selected' : '' }}>জন্ম নিবন্ধন</option>    
                                        </select>                                 
                                    </div>
                                    <hr style="height: 2px;background-color:blue;width:100%;opacity:1;">
                                    <div class="form-group" style="display: flex;justify-content:space-between;">
                                        <div class="col-left">
                                            <label for="kyc_userimg" class="form-label">আপনার নিজের ছবি প্রদান করুন 500px X 500px</label>                                    
                                            <input id="kyc_userimg" class="form-control" type="file" name="kyc_userimg" accept="image/*">
                                        </div>
                                        <div class="col-right">
                                            <img src="{{custom_path(Auth::user()->kyc_userimg)}}" width="100%">
                                        </div>
                                    </div>
                                    <hr style="height: 2px;background-color:blue;width:100%;opacity:1;">
                                    <div class="form-group" style="display: flex;justify-content:space-between;">
                                        <div class="col-left">
                                            <label for="kyc_frontpart" class="form-label card_demo_image">NID কার্ডের সামনের পাশের ক্লিয়ার ছবি প্রদান করুন</label>
                                            <label for="kyc_frontpart" class="form-label card_demo_image2">জন্ম নিবন্ধনের সামনের পাশের ক্লিয়ার ছবি প্রদান করুন</label>
                                            <input class="form-control" type="file" name="kyc_frontpart" accept="image/*">
                                        </div>
                                        <div class="col-right">
                                            <img class="card_demo_image" src="{{custom_path(Auth::user()->kyc_frontpart)}}" width="60%">
                                            <img class="card_demo_image2" src="{{custom_path(Auth::user()->kyc_frontpart)}}" width="60%">
                                        </div>
                                    </div>
                                    <hr style="height: 2px;background-color:blue;width:100%;opacity:1;">
                                    <div class="form-group" style="display: flex;justify-content:space-between;">
                                        <div class="col-left">
                                            <label for="kyc_backpart" class="form-label card_demo_image">NID কার্ডের পিছনের পাশের ক্লিয়ার ছবি প্রদান করুন</label>
                                            <label for="kyc_backpart" class="form-label card_demo_image2">জন্ম নিবন্ধনের পিছনের পাশ ফাঁকা থাকলে সামনেরটা আপলোড করুন</label>
                                            <input id="kyc_backpart" class="form-control" type="file" name="kyc_backpart" accept="image/*">
                                        </div>
                                        <div class="col-right">
                                            <img class="card_demo_image" src="{{custom_path(Auth::user()->kyc_backpart)}}" width="60%">
                                            <img class="card_demo_image2" src="{{custom_path(Auth::user()->kyc_backpart)}}" width="60%">
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-top:25px;">
                                        <button class="btn btn-info" type="submit" style="width:100%;">Submit</button>
                                    </div> 
                                    
                                </form>
                            @else
                                <div class="info-text text-center">
                                    <h2>নিচের ফর্মটি সঠিক তথ্য দিয়ে পূরণ করুন</h2>
                                </div>
                                <form action="{{route('user.account-instant-verify-by-nid-store')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <style>
                                        .col-right{
                                            text-align:right;
                                        }
                                    </style>
                                    <div class="form-group">
                                        <label for="kyc_name" class="form-label">আপনার সম্পূর্ণ নাম (কার্ডের সাথে ম্যাচ রাখবেন) <span class="text-red">*</span></label>
                                        <input class="form-control" type="text" name="kyc_name" placeholder="আপনার সম্পূর্ণ নাম (কার্ডের সাথে ম্যাচ রাখবেন)" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="kyc_nid_number" class="form-label">আপনার NID কার্ড নম্বরটি (ইংরেজিতে লিখতে হবে) <span class="text-red">*</span></label>
                                        <input class="form-control" type="number" name="kyc_nid_number" placeholder="আপনার NID কার্ড নম্বরটি (ইংরেজিতে লিখতে হবে)" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="kyc_address" class="form-label">আপনার সম্পূর্ণ ঠিকানা (কার্ডের সাথে ম্যাচ রাখবেন) <span class="text-red">*</span></label>
                                        <input class="form-control" type="text" name="kyc_address" placeholder="আপনার সম্পূর্ণ ঠিকানা (কার্ডের সাথে ম্যাচ রাখবেন)" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="kyc_birthday" class="form-label">কার্ডের সাথে ম্যাচ রেখে  জন্ম তারিখ (মাস-তারিখ-সাল)<span class="text-red">*</span></label>
                                        <input class="form-control" type="date" name="kyc_birthday" placeholder="জন্ম তারিখ (কার্ডের সাথে ম্যাচ রাখবেন)" required>
                                    </div>
                                    
                                    <div class="form-group">  
                                        <label for="kyc_card_type">কোনটি দিয়ে এপ্লাই করছে ?</label>                            
                                        <select name="kyc_card_type" class="form-control" id="kyc_card_type">
                                            <option value="nid" selected>এনআইডি কার্ড </option>    
                                            <option value="birth">জন্ম নিবন্ধন</option>    
                                        </select>                                 
                                    </div>
                                    <hr style="height: 2px;background-color:blue;width:100%;opacity:1;">
                                    <div class="form-group" style="display: flex;justify-content:space-between;">
                                        <div class="col-left">
                                            <label for="kyc_userimg" class="form-label">আপনার নিজের ছবি প্রদান করুন 500px X 500px</label>                                    
                                            <input id="kyc_userimg" class="form-control" type="file" name="kyc_userimg" accept="image/*">
                                        </div>
                                        <div class="col-right">
                                            <img src="{{asset('/images/kyc_userimg.png')}}" width="100%">
                                        </div>
                                    </div>
                                    <hr style="height: 2px;background-color:blue;width:100%;opacity:1;">
                                    <div class="form-group" style="display: flex;justify-content:space-between;">
                                        <div class="col-left">
                                            <label for="kyc_frontpart" class="form-label card_demo_image">NID কার্ডের সামনের পাশের ক্লিয়ার ছবি প্রদান করুন</label>
                                            <label for="kyc_frontpart" class="form-label card_demo_image2">জন্ম নিবন্ধনের সামনের পাশের ক্লিয়ার ছবি প্রদান করুন</label>
                                            <input class="form-control" type="file" name="kyc_frontpart" accept="image/*">
                                        </div>
                                        <div class="col-right">
                                            <img class="card_demo_image" src="{{asset('/public/images/kyc_frontpart.png')}}" width="60%">
                                            <img class="card_demo_image2" src="{{asset('/public/images/kyc_birth_front.png')}}" width="60%">
                                        </div>
                                    </div>
                                    <hr style="height: 2px;background-color:blue;width:100%;opacity:1;">
                                    <div class="form-group" style="display: flex;justify-content:space-between;">
                                        <div class="col-left">
                                            <label for="kyc_backpart" class="form-label card_demo_image">NID কার্ডের পিছনের পাশের ক্লিয়ার ছবি প্রদান করুন</label>
                                            <label for="kyc_backpart" class="form-label card_demo_image2">জন্ম নিবন্ধনের পিছনের পাশ ফাঁকা থাকলে সামনেরটা আপলোড করুন</label>
                                            <input id="kyc_backpart" class="form-control" type="file" name="kyc_backpart" accept="image/*">
                                        </div>
                                        <div class="col-right">
                                            <img class="card_demo_image" src="{{asset('/public/images/kyc_backpart.png')}}" width="60%">
                                            <img class="card_demo_image2" src="{{asset('/public/images/kyc_birth_back.png')}}" width="60%">
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-top:25px;">
                                        <button class="btn btn-info" type="submit" style="width:100%;">Submit</button>
                                    </div> 
                                    
                                </form>
                            @endif


                        @endif 
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <!-- Include any external JS libraries if necessary -->
    <script src="{{ asset('frontend/user/assets/js/my_custom.js') }}"></script>
    {{-- NID or Birth  --}}
    <script>
        const updateVisibility = (selectedValue) => {
            const allCard1 = document.querySelectorAll('.card_demo_image');
            const allCard2 = document.querySelectorAll('.card_demo_image2');
    
            if (selectedValue === 'nid') {
                allCard1.forEach(img => img.style.display = 'inline-block'); // Show all .card_demo_image
                allCard2.forEach(img => img.style.display = 'none');  // Hide all .card_demo_image2
            } else if (selectedValue === 'birth') {
                allCard1.forEach(img => img.style.display = 'none');  // Hide all .card_demo_image
                allCard2.forEach(img => img.style.display = 'inline-block'); // Show all .card_demo_image2
            }
        };
    
        document.addEventListener('DOMContentLoaded', () => {
            const dropdown = document.getElementById('kyc_card_type');
            if (!dropdown) return;
    
            // Default visibility on page load
            updateVisibility(dropdown.value);
    
            // Update visibility on dropdown change
            dropdown.addEventListener('change', function () {
                updateVisibility(this.value);
            });
        });
    </script>
    
    {{-- Notification  --}}
    <script>
        @if(session('success'))
            toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true
            }
            toastr.success({{ session('success') }});
        @elseif(session('error'))
            toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true
            }
            toastr.error({{ session('error') }});
        @endif
    </script>
    

@endsection
