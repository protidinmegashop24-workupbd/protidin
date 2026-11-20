@extends('user.layouts.master')
@section('css')
<style>
    /* Custom styling for the page */
    .container-fluid {
        background-color: #f4f7fa;
        padding: 20px;
    }
    .marquee-container {
        border-radius: 8px;
        padding: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #ffc107;
    }
    .marquee a {
        font-size: 18px;
        font-weight: bold;
        text-decoration: none;
        color: #343a40;
        transition: color 0.3s ease;
    }
    .marquee a:hover {
        color: #007bff;
    }
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }
    .card-header {
        background-color: #343a40;
        color: #fff;
        text-align: center;
        padding: 20px;
        border-radius: 12px 12px 0 0;
    }
    .card-title {
        font-size: 28px;
        font-weight: bold;
    }
    .form-control {
        border-radius: 8px;
        font-size: 16px;
        padding: 12px;
    }
    .form-label {
        font-weight: bold;
        color: #343a40;
    }
    .btn-primary {
        background-color: #007bff;
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        font-size: 18px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .input-group {
        display: flex;
        align-items: center;
    }
    .input-group button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 8px 0 0 8px;
    }
    .input-group input {
        border-radius: 0 8px 8px 0;
        padding: 12px;
        font-size: 16px;
    }
    .text-center {
        text-align: center;
    }
    .form-group {
        margin-bottom: 20px;
    }
</style>
@endsection

@section('user-content')
    <div class="container-fluid mt-2">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-8 col-12">
                <div class="marquee-container">
                    <marquee scrollamount="6">
                        @foreach ($headlines as $key=>$headline)
                            <a href="{{ $headline->link }}" class="text-black"><i class="fe fe-link me-2" aria-hidden="true"></i>{{ $headline->title }}</a>
                        @endforeach
                    </marquee>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h4 class="card-title">{{ $title }}</h4>
        </div>
        <div class="card-body">
            <pre>
            @php
                // Group services by category for easier manipulation
                $groupedServices = [];
                foreach (smm_get_services() as $service) {
                    $groupedServices[$service['category']][] = $service;
                }
            @endphp
            </pre>
            <form action="{{ route('user.boost-post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-md-6 col-lg-6 col-12">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-control" name="category_id" id="category_id" required onchange="changeCategory()">
                            <option value="">Select One</option>
                            @foreach ($groupedServices as $category => $services)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
            
                    <div class="form-group col-md-6 col-lg-6 col-12">
                        <label class="form-label">Service<span class="text-danger">*</span></label>
                        <select class="form-control" name="service_id" id="sub_category" required onchange="changeSubCategory()">
                            <option value="">Select One</option>
                        </select>
                    </div>
            
                    <div class="form-group" style="display:none;">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="description" id="description" readonly cols="30" rows="4" maxlength="200" placeholder="Description"></textarea>
                    </div>
            
                    <div class="form-group col-md-4 col-lg-4 col-12">
                        <label for="link" class="form-label">Link <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="link" placeholder="Link" required>
                    </div>
            
                    <div class="form-group col-md-4 col-lg-4 col-12">
                        <label class="form-label">
                            Quantity - Min: <span class="minClass">0</span> - Max: <span class="maxClass">0</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" name="work_need" id="work_need" value="1" min="1" onchange="changeWorkNeed()" onkeyup="changeWorkNeed()">
                        <input type="hidden" class="form-control" name="base_cost" id="base_cost" step="0.0001" value="0" min="0" readonly>
                        <input type="hidden" class="form-control" name="unit_cost" id="unit_cost" step="0.0001" value="0" min="0" readonly>
                    </div>
                                
                    <div class="form-group col-md-4 col-lg-4 col-12">
                        <label class="form-label">Charge<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <button class="btn">$</button>
                            <input type="number" class="form-control" readonly name="cost" id="cost" value="0" min="0">
                        </div>
                    </div>
            
                    <div class="form-group col-md-12 col-lg-12 col-12 text-center">
                        <button type="submit" class="btn btn-primary btn-lg mt-4 mb-0">Submit</button>
                    </div>
                </div>
            </form>

            
            
            
            
            {{-- Old Script
            <form action="{{ route('user.boost-post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-md-6 col-lg-6 col-12">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-control" name="category_id" id="category_id" required onchange="chnageCategory()">
                            <option value="">Select One</option>
                            @foreach ($categorys as $key=>$category)
                                <option value="{{ $category->id }}" style="background-image:url({{ URL::to($category->image) }});"> {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-6 col-lg-6 col-12">
                        <label class="form-label">Service <span class="text-danger">*</span></label>
                        <select class="form-control" name="sub_category" id="sub_category" required onchange="chnageSubCategory()">
                            <option value="">Select One</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="description" id="description" readonly cols="30" rows="4" maxlength="200" placeholder="Description" required></textarea>
                    </div>

                    <div class="form-group col-md-4 col-lg-4 col-12">
                        <label for="link" class="form-label">Link <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="link" placeholder="Link" required>
                    </div>

                    <div class="form-group col-md-4 col-lg-4 col-12">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="work_need" id="work_need" value="100" min="1" onchange="chnageWorkNeed()" onkeyup="chnageWorkNeed()">
                        <input type="hidden" class="form-control" name="base_cost" id="base_cost" step="0.0001" value="0" min="0" readonly>
                        <input type="hidden" class="form-control" name="unit_cost" id="unit_cost" step="0.0001" value="0" min="0" readonly>
                    </div>

                    <div class="form-group col-md-4 col-lg-4 col-12">
                        <label class="form-label">Charge <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <button class="btn">$</button>
                            <input type="number" class="form-control" readonly name="cost" id="cost" value="0" min="0">
                        </div>
                    </div>

                    <div class="form-group col-md-12 col-lg-12 col-12 text-center">
                        <button type="submit" class="btn btn-primary btn-lg mt-4 mb-0">Submit</button>
                    </div>
                </div>
            </form> 
            --}}
        </div>
    </div>
@endsection

@section('js')
<script>
    const services = @json($groupedServices);

    function changeCategory() {
        const category = document.getElementById('category_id').value;
        const subCategoryDropdown = document.getElementById('sub_category');
        subCategoryDropdown.innerHTML = '<option value="">Select One</option>';

        if (services[category]) {
            services[category].forEach(service => {
                const option = document.createElement('option');
                option.value = service.service;
                option.textContent = service.name;
                option.setAttribute('data-cost', service.rate);
                option.setAttribute('data-description', service.description || '');
                option.setAttribute('data-min', service.min);
                option.setAttribute('data-max', service.max);
                subCategoryDropdown.appendChild(option);
            });
        }
    }

    function changeSubCategory() {
        const selectedService = document.getElementById('sub_category').selectedOptions[0];
        const workNeedInput = document.getElementById('work_need');
        const minClass = document.querySelector('.minClass');
        const maxClass = document.querySelector('.maxClass');

        const min = parseInt(selectedService.getAttribute('data-min')) || 1;
        const max = parseInt(selectedService.getAttribute('data-max')) || 100;

        // Update min and max spans
        minClass.textContent = min;
        maxClass.textContent = max;

        // Set input field attributes
        workNeedInput.min = min;
        workNeedInput.max = max;
        workNeedInput.value = min;

        const baseCost = parseFloat(selectedService.getAttribute('data-cost')) || 0;
        document.getElementById('base_cost').value = baseCost;
        const unitCost = (baseCost / 1000).toFixed(4);
        document.getElementById('unit_cost').value = unitCost;

        changeWorkNeed(); // Update cost based on the default min value
    }

    function changeWorkNeed() {
        const workNeedInput = document.getElementById('work_need');
        const min = parseInt(workNeedInput.min) || 1;
        const max = parseInt(workNeedInput.max) || 100;
    
        // Ensure the value stays within the range
        if (workNeedInput.value < min) {
            workNeedInput.value = min;
        } else if (workNeedInput.value > max) {
            workNeedInput.value = max;
        }
    
        const unitCost = parseFloat(document.getElementById('unit_cost').value) || 0;
        const baseTotalCost = (unitCost * parseFloat(workNeedInput.value)).toFixed(4);
        const additionalCharge = (baseTotalCost * 0.03); // Calculate 3% charge
        const totalCostWithCharge = (parseFloat(baseTotalCost) + parseFloat(additionalCharge)).toFixed(4);
    
        // Update the cost field with the new total including the 3% charge
        document.getElementById('cost').value = totalCostWithCharge;
    }

</script>

{{-- 
Old Script
<script>
    function chnageCategory() {
        var category_id = $('#category_id').val();
        $.ajax({
            url: "{{ route('user.get-boost-sub-category') }}",
            type:"POST",
            data: {
                _token: '{{csrf_token()}}',
                category_id: category_id,
            },
            success:function(data) {
                $('#sub_category').html(data);
            },
        });
    }

    function chnageSubCategory() {
        var sub_category = $('#sub_category').val();
        var work_need = parseFloat($('#work_need').val());
        $.ajax({
            url: "{{ route('user.get-boost-sub-category-price') }}",
            type:"POST",
            data: {
                _token: '{{csrf_token()}}',
                sub_category: sub_category,
            },
            success:function(data) {
                var cost = parseFloat(data['cost']);
                $('#base_cost').val(data['cost']);
                var unit_cost = (data['cost'] / 1000).toFixed(4);
                $('#unit_cost').val(unit_cost);
                var total_cost = (unit_cost * work_need).toFixed(4);
                $('#cost').val(total_cost);
                $('#description').val(data['notice']);
            },
        });
    }

    function chnageWorkNeed() {
        var work_need = parseFloat($('#work_need').val());
        var unit_cost = $('#unit_cost').val();
        var total_cost = (unit_cost * work_need).toFixed(4);
        $('#cost').val(total_cost);
    }
</script>
--}}
@endsection
