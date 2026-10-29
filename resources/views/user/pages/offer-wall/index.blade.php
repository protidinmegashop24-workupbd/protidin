@extends('user.layouts.master')
@section('css')
    <style>
        .ow-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px 18px;
            margin-bottom: 14px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }
        .ow-card .ow-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .ow-dollar {
            width: 48px;
            height: 48px;
            min-width: 48px;
            border-radius: 50%;
            background: #16a34a;
            color: #fff;
            font-size: 22px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .ow-title {
            font-size: 17px;
            font-weight: bold;
            color: #1e293b;
        }
        .ow-sub {
            font-size: 13px;
            color: #64748b;
        }
        .ow-btn {
            background: #16a34a;
            color: #fff;
            border-radius: 8px;
            padding: 10px 18px;
            font-weight: bold;
            white-space: nowrap;
        }
        .ow-btn:hover { background: #15803d; color: #fff; }
    </style>
@endsection

@section('user-content')
<div class="container-fluid mt-2">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-md-10 col-12">

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">💵 স্পেশাল অফার জব</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" style="font-size:14px;">
                        <strong>এগুলো সাধারণ জব থেকে বেশি টাকার অফার।</strong> নিচের যেকোনো একটায় ক্লিক করলে <strong>নতুন ট্যাবে</strong> কিছু অফার (যেমন: অ্যাপ ইন্সটল, সার্ভে, সাইন-আপ) দেখাবে।
                        একটা অফারে ঢুকে তাদের নির্দেশনা (বেশিরভাগ ইংরেজিতে) অনুযায়ী কাজ শেষ করলে এখানে বোনাস যোগ হবে। কাজ শেষ করার পর টাকা যোগ হতে কিছুক্ষণ সময় লাগতে পারে।
                    </div>

                    @forelse($providers as $provider)
                        <a href="{{ route('offer-wall.start', $provider->slug) }}" target="_blank" rel="noopener" class="ow-card">
                            <div class="ow-left">
                                <div class="ow-dollar">$</div>
                                <div>
                                    <div class="ow-title">{{ $provider->name }} অফার</div>
                                    <div class="ow-sub">নতুন ট্যাবে খুলবে — অফার শেষ করলে বোনাস পাবেন</div>
                                </div>
                            </div>
                            <span class="ow-btn">শুরু করুন</span>
                        </a>
                    @empty
                        <div class="text-center text-muted py-4">এখন কোনো স্পেশাল অফার চালু নেই। পরে আবার চেক করুন।</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
