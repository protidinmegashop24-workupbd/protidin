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

<div class="row justify-content-center mt-2">
    <div class="col-md-6 col-lg-6 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">New Advertisement</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('user.advertisement-store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="">
                        <div class="form-group">
                            <label for="title" class="form-label">Ads Title <span class="text-red">*</span></label>
                            <input class="form-control" type="text" name="title" placeholder="Title" required>
                        </div>
                        <div class="form-group">
                            <label for="link" class="form-label">Target Destination</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-link"></i></span>
                                </div>
                                <input class="form-control" type="text" name="link" value="" placeholder="https://demo.com">
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <input class="form-control" type="hidden" name="amount" id="amount" value="" required placeholder="days">
                            <input class="form-control" type="hidden" name="days" id="days" value="" required placeholder="days">
                            <div class="row m-0 p-0">
                                
                                @foreach(paid_ads_rate() as $ads_rate)
                                    <div class="col-6 p-1">
                                        <div class="c-pointer ads-rate-area" id="ads-rate-area-{{$ads_rate->duration}}" onclick="selectDuration('{{$ads_rate->duration}}','{{$ads_rate->rate}}')">
                                            <span><i class="fas fa-ad"></i> {{$ads_rate->duration}} Day</span><span>${{$ads_rate->rate}}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="image" class="form-label">*Image resolution max- width (500px) & max- height (250px) ( allow only jpg , png )</label>
                            <input class="form-control" type="file" name="image" required>
                        </div>
                    </div>
                    <div class="text-right">
                         <button type="submit" class="btn btn-primary mt-4 mb-0">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
    <script>
        
        function selectDuration(duration, rate){
            $('#days').val(duration);
            $('#amount').val(rate);
            $('.ads-rate-area').removeClass('ads-rate-area-active');
            $('#ads-rate-area-'+duration).addClass('ads-rate-area-active');
        }
        
        $(document).ready(function () {
            // Triggered when the file input changes
            $('#imageInput').on('change', function () {
                readURL(this);
            });
        });

        // Function to read and display the selected image
        function readURL(input) {
            var fileInput = $(input);
            var previewImg = $('#imagePreview');

            var file = fileInput.get(0).files[0];

            if (file) {
                var reader = new FileReader();

                reader.onload = function () {
                    previewImg.show();
                    previewImg.attr("src", reader.result);
                };

                reader.readAsDataURL(file);
            } else {
                // Hide the image preview if no file is selected
                previewImg.hide();
            }
        }
    </script>
@endsection
