@extends('user.layouts.master')
@section('css')
    <style>
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
    </style>
@endsection
@section('user-content')

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-6 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">New PTC Job Add</h4>
            </div>
            <div class="card-body">
                <form id="ad-form" action="{{ route('user.ptcAddStore') }}" method="POST">
                    @csrf
                    <div class="">
                        <div class="form-group">
                            <label for="ptc_title" class="form-label">জব টাইটেল <span class="text-red">*</span></label>
                            <input class="form-control" type="text" name="ptc_title" placeholder="Job Title" required id="ptc_title">
                            @if ($errors->has('ptc_title'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('ptc_title') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="ptc_jobLink" class="form-label">এড এর লিংক <span class="text-red">*</span></label>
                            <input class="form-control" type="text" name="ptc_jobLink" placeholder="https://www.google.com" required id="ptc_jobLink">
                            @if ($errors->has('ptc_jobLink'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('ptc_jobLink') }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <label for="package_name" class="form-label">প্রতিটা ক্লিক এর প্রদত্ত মূল্য <span class="text-red">*</span></label>

                            <select name="package_name" id="package_name" class="form-control" required>
                                <option data-price="0.00020" value="1">0.0s Per View 0.00020</option>
                                <option data-price="0.00025" value="2">0.3s Per View 0.00025</option>
                                <option data-price="0.00035" value="3">0.05s Per View 0.00035</option>
                                <option data-price="0.0005" value="4">0.10s Per View 0.0005</option>
                                <option data-price="0.0007" value="5">0.15s Per View 0.0007</option>
                                <option data-price="0.00085" value="6">0.20s Per View 0.00085</option>
                                <option data-price="0.001" value="7">0.30s Per View 0.001</option>
                            </select>
                                @if ($errors->has('package_name'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('package_name') }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <label for="ptc_worker_needed" class="form-label">কতটি ক্লিক দরকার?<span class="text-red">*</span></label>
                            <input class="form-control" type="number" name="ptc_worker_needed" required placeholder="500" id="ptc_worker_needed">
                            @if ($errors->has('ptc_worker_needed'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('ptc_worker_needed') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="total_amount" class="form-label">আপনার সম্পূর্ণ খরচ হবে + 3% Cost<label>
                            <input class="form-control" type="text" id="total_amount" readonly placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label for="ptc_expire_day" class="form-label">কত তারিখ(দিন)পর্যন্ত কাজটি মার্কেটে থাকবে?<span class="text-red">*</span></label>
                            <input class="form-control" type="date" name="ptc_expire_day" required id="ptc_expire_day" min="<?php date('Y-m-d'); ?>">
                            @if ($errors->has('ptc_expire_day'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('ptc_expire_day') }}
                                </div>
                            @endif
                        </div>                 
                        {{-- <div class="form-group">
                            <label for="priority" class="form-label">Priority <span class="text-red">*</span></label>
                            <select name="priority" class=" form-select form-control form--control" required="" id="priority">
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div> --}}
                        <div class="form-group">
                            <label for="ptc_job_details" class="form-label">Message <span class="text-red">(ঐচ্ছিক)</span></label>
                            <textarea name="ptc_job_details" id="ptc_job_details" rows="6" class="form-control form--control"></textarea>
                        </div>                        
                    </div>
                    <button type="submit" class="btn btn-primary mt-4 mb-0">আমি কনফার্ম করছি</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const packageSelect = document.getElementById('package_name');
            const workerInput = document.getElementById('ptc_worker_needed');
            const totalAmount = document.getElementById('total_amount');
    
            function calculateTotal() {
                // Get the selected package's price
                const selectedOption = packageSelect.options[packageSelect.selectedIndex];
                const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
    
                // Get the number of clicks needed
                const workerNeeded = parseInt(workerInput.value) || 0;
    
                // Calculate the total amount
                let total = price * workerNeeded;
    
                // Add 3% additional cost
                total += total * 0.03;
    
                // Display the total amount
                totalAmount.value = total.toFixed(5); // Show 5 decimal places
            }
    
            // Event listeners for changes in dropdown or input field
            packageSelect.addEventListener('change', calculateTotal);
            workerInput.addEventListener('input', calculateTotal);
        });
    </script>



@endsection