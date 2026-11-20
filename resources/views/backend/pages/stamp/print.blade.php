<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>প্রিন্ট</title>

        <link rel="stylesheet" href="{{ asset('backend/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{ asset('backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
        <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
        <style>
            body {
                margin: 0;
                font-family: 'SolaimanLipi', Arial, sans-serif !important;
                font-size: 1rem;
                font-weight: 400;
                line-height: 1.5;
                color: #212529;
                text-align: left;
                background-color: rgb(204,204,204);
            }
            hr {
                box-sizing: content-box;
                height: 0;
                overflow: visible;
            }
            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                margin-top: 0;
                margin-bottom: 0rem;
            }
            p {
                margin-top: 0;
                margin-bottom: 1rem;
            }
            page {
                background: white;
                display: block;
                margin: 0 auto;
                margin-bottom: 0.5cm;
                /* box-shadow: 0 0 0.5cm rgba(0,0,0,0.5); */
                /* border: solid 1px #000; */
            }
            page[size="A4"] {
                width: 22cm;
                height: 37.67cm;
                display: flex;
                justify-content: center;
            }
            @media print {
                body, page {
                    margin: 0;
                    box-shadow: 0;
                }
            }
            .print-area{
                width: 365px;
                height: 160px;
                padding: 20px 10px 60px 10px;
                /* border: solid 1px #000; */
                margin-top: 50px;
            }
            .under_border_serial{
                border-bottom: dotted 1px;
                padding-left: 5px;
                width: 70%;
                 height: 19px;
                /* text-align: center; */
                font-weight: 800;
                line-height: 16px;
            }
            .under_border_taka{
                border-bottom: dotted 1px;
                padding-left: 5px;
                width: 82%;
                 height: 19px;
                /* text-align: center; */
                font-weight: 800;
                line-height: 16px;
            }
            .under_border_date{
                border-bottom: dotted 1px;
                padding-left: 5px;
                width: 77%;
                 height: 19px;
                /* text-align: center; */
                font-weight: 800;
                line-height: 16px;
            }
            .under_border{
                border-bottom: dotted 1px;
                padding-left: 5px;
                width: 84%;
                height: 19px;
                font-weight: 800;
                font-size: 17px;
                line-height: 16px;
            }
            .under_border_full{
                border-bottom: dotted 1px;
                padding-left: 5px;
                width: 100%;
                height: 19px;
                line-height: 16px;
                font-weight: 800;
                font-size: 17px;
            }
            .heading{
                width: 53px;
            }
            .row{
                display: -ms-flexbox;
                display: flex;
                -ms-flex-wrap: wrap;
                flex-wrap: wrap;
            }
            .col-4{
                width: 33.33%;
                display: flex;
            }
            .col-6{
                width: 50%;
                display: flex;
            }
            .col-12{
                width: 100%;
                display: flex;
            }
            .vender_info{
                display: flex;
                /* justify-content: center; */
            }
            .vender_signature{
                display: flex;
                justify-content: flex-end;
            }
            .signature{
                margin-left: 13px;
                border-bottom: solid 1px;
            }
            .font_size{
                font-size: 12px;
                font-weight: 800;
            }
            p {
                margin-top: 0;
                margin-bottom: 0rem;
            }
            .sig_img{
                position: relative;
                bottom: 60px;
                left: 10px;
            }
            .mb-5{
                margin-bottom: 5px;
            }
            .mb-7{
                margin-bottom: 7px;
            }
            .vendern_name{
                margin-bottom: 0 !important;
                line-height: 15px;
                padding-left: 10px;
            }
            .vender_info_font{
                font-size: 0.81rem;
            }
            .vender_address_info_font{
                font-size: 0.73rem;
            }
            .pl-28{
                padding-left: 28px;
            }
        </style>
    </head>
    <body>
        @include('backend.layouts.partials.eng-to-ban')
        @foreach ($sale_details as $item=>$sale_detail)
            @php
                $serial = App\Models\Admin\Stamp::find($sale_detail->stamp_id);
                $category = DB::table('categories')->where('slug', $serial->cat_slug)->first();
            @endphp
            <page size="A4">
                <div class="print-area row">
                    <div class="col-6 mb-7"><p class="font_size">তারিখঃ</p> <p class="under_border_date">{{ Converter::en2bn(\Carbon\Carbon::parse($sale->date)->format('d/m/Y')) }}</p></div>
                    <div class="col-6 mb-7"><p class="font_size">ক্রমিক নংঃ</p> <p class="under_border_serial">{{ Converter::en2bn($sale_detail->serial) }}</p></div>
                    <div class="col-6 mb-7"><p class="font_size">মূল্যঃ</p> <p class="under_border_taka">{{ Converter::en2bn($category->name) }} টাকা</p></div>
                    <div class="col-6 mb-7"><p class="font_size">সিরিয়ালঃ</p> <p class="under_border_full">{{ Converter::en2bn($serial->final_serial ) }}</p></div>
                    <div class="col-12 mb-7"><p class="font_size">নামঃ</p> <p class="under_border_full">{{ $sale->name }}</p></div>
                    <div class="col-12 mb-7"><p class="font_size">পিতাঃ</p> <p class="under_border_full">{{ $sale->father_name }}</p></div>
                    <div class="col-12 mb-7"><p class="font_size">গ্রামঃ</p> <p class="under_border_full">{{ $sale->village }}</p></div>
                    <div class="col-6 mb-7"><p class="font_size">থানাঃ</p> <p class="under_border">{{ $sale->thana }}</p></div>
                    <div class="col-6 mb-7"><p class="font_size">জেলাঃ</p> <p class="under_border">{{ $sale->district }}</p></div>
                    <div class="col-12 mb-7 vender_info vendern_name">
                        <p class="vender_info_font"><strong>{{ $website->vender_name }}, ষ্ট্যাম্প ভেন্ডার, লাইসেন্স নংঃ - {{ Converter::en2bn($website->license) }}</strong></p>
                    </div>
                    <div class="col-12 vender_info pl-28">
                        <p class="vender_address_info_font">{{ $website->address }} - {{ Converter::en2bn($website->phone) }} । <span class="signature">স্বাক্ষর</span></p>
                    </div>
                    <div class="col-12 vender_signature">
                        <img class="sig_img" src="{{ URL::to($website->signature) }}" alt="" height="60px" width="70px">
                    </div>
                </div>
            </page>
        @endforeach

        <script type="text/javascript">
            window.onload = function() { window.print(); }
       </script>
    </body>
</html>
