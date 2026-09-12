@extends('user.layouts.master')

@section('css')
<style>
    .mp-chat-card{border:1px solid #e6edf5;border-radius:20px;box-shadow:0 12px 28px rgba(15,23,42,.06);overflow:hidden;background:#fff}
    .mp-chat-header{background:linear-gradient(135deg,#16a34a 0%,#22ab59 100%);color:#fff;padding:18px 22px}
    .mp-chat-header h5{margin:0;font-size:24px;font-weight:800}
    .mp-chat-body{padding:20px;max-height:520px;overflow-y:auto;background:#f8fbff}
    .mp-chat-bubble{max-width:82%;margin-bottom:14px;padding:14px 16px;border-radius:16px;box-shadow:0 8px 16px rgba(15,23,42,.04);word-break:break-word}
    .mp-chat-me{margin-left:auto;background:#eaf8ef;border-bottom-right-radius:6px}
    .mp-chat-other{margin-right:auto;background:#fff;border:1px solid #e7edf4;border-bottom-left-radius:6px}
    .mp-chat-role{font-size:12px;font-weight:800;color:#66758b;text-transform:uppercase;margin-bottom:5px}
    .mp-chat-footer{padding:18px;border-top:1px solid #e8eef5;background:#fff}
    .mp-chat-footer textarea{border-radius:12px;border:1px solid #d8e3ee;min-height:100px;box-shadow:none!important}
    .mp-send-btn{background:#1d4ed8;color:#fff;border:0;border-radius:10px;padding:11px 18px;font-weight:800}
    .mp-send-btn:hover{background:#173fb2;color:#fff}
    .mp-side-card{border:1px solid #e6edf5;border-radius:20px;box-shadow:0 12px 28px rgba(15,23,42,.06);overflow:hidden;background:#fff;margin-bottom:20px}
    .mp-side-card .card-header{font-weight:800}
    .mp-delivery-item{border:1px solid #e6edf5;border-radius:12px;padding:12px;margin-bottom:12px;background:#f9fcff}
    .mp-btn{border-radius:10px;font-weight:700;padding:10px 14px}
    .mp-order-thumb{width:100%;height:220px;object-fit:cover}
    .mp-file-link{display:inline-block;margin-top:8px;font-weight:700;text-decoration:none!important}
    .mp-preview-img{display:block;width:100%;max-width:260px;height:auto;border-radius:10px;border:1px solid #e2e8f0;margin-top:10px}
</style>
@endsection

@section('user-content')
<div class="row mt-4">
    <div class="col-lg-8">
        <div class="mp-chat-card">
            <div class="mp-chat-header">
                <h5>Order Chat - {{ $order->service_title }}</h5>
            </div>

            <div class="mp-chat-body">
                @forelse($messages as $msg)
                    @php
                        $ext = strtolower(pathinfo($msg->file ?? '', PATHINFO_EXTENSION));
                        $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','bmp']);
                    @endphp

                    <div class="mp-chat-bubble {{ $msg->sender_id == auth()->id() ? 'mp-chat-me' : 'mp-chat-other' }}">
                        <div class="mp-chat-role">{{ $msg->sender_id == auth()->id() ? 'You' : 'Other User' }}</div>
                        <div>{{ $msg->message }}</div>

                        @if(!empty($msg->file))
                            @if($isImage)
                                <a href="{{ route('user.marketplace.message_file', $msg->id) }}" target="_blank">
                                    <img src="{{ route('user.marketplace.message_file', $msg->id) }}" class="mp-preview-img" alt="Attachment">
                                </a>
                            @endif

                            <div class="mt-2">
                                <a href="{{ route('user.marketplace.message_file', $msg->id) }}" target="_blank" class="mp-file-link">
                                    View / Download Attachment
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="alert alert-light mb-0">No messages yet.</div>
                @endforelse
            </div>

            <div class="mp-chat-footer">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('user.marketplace.send_message', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-2">
                        <textarea name="message" class="form-control" rows="3" placeholder="Write message..." required></textarea>
                    </div>
                    <div class="mb-2">
                        <input type="file" name="file" class="form-control">
                    </div>
                    <button type="submit" class="btn mp-send-btn">Send Message</button>
                </form>
            </div>
        </div>

        @if($order->service_type == 'digital_product' && auth()->id() == $order->buyer_id && in_array($order->status, ['delivered', 'completed']))
            <div class="mp-side-card mt-4">
                <div class="card-header bg-success text-white">
                    Your Digital Product
                </div>
                <div class="card-body">
                    <p class="mb-3">Your file is ready.</p>
                    <a href="{{ route('user.marketplace.download_product', $order->id) }}" class="btn btn-success mp-btn">Download File</a>
                </div>
            </div>
        @endif

        @if(auth()->id() == $order->seller_id && in_array($order->status, ['in_progress', 'revision_requested']))
            <div class="mp-side-card mt-4">
                <div class="card-header bg-warning">
                    Submit Work Delivery
                </div>
                <div class="card-body">
                    <form action="{{ route('user.marketplace.deliver', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <textarea name="message" class="form-control" rows="4" placeholder="Write what work you completed for the buyer..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <input type="file" name="file" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success mp-btn">Submit Delivery</button>
                    </form>
                </div>
            </div>
        @endif

        @if(auth()->id() == $order->buyer_id && $order->status == 'completed' && !$hasReview)
            <div class="mp-side-card mt-4">
                <div class="card-header bg-info text-white">
                    Leave a Review
                </div>
                <div class="card-body">
                    <form action="{{ route('user.marketplace.review', $order->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <select name="rating" class="form-control" required>
                                <option value="5">5 Star</option>
                                <option value="4">4 Star</option>
                                <option value="3">3 Star</option>
                                <option value="2">2 Star</option>
                                <option value="1">1 Star</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Comment</label>
                            <textarea name="comment" class="form-control" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success mp-btn">Submit Review</button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="mp-side-card">
            <img src="{{ $order->image_url }}" class="mp-order-thumb" alt="{{ $order->service_title }}">
            <div class="card-body">
                <h5 class="mb-3">{{ $order->service_title }}</h5>
                <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                <p><strong>Price:</strong> ${{ number_format($order->price, 2) }}</p>
                <p><strong>Escrow Amount:</strong> ${{ number_format($order->escrow_amount ?? $order->price, 2) }}</p>
                <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status ?? 'held') }}</p>
                <p><strong>Seller Will Receive:</strong> ${{ number_format($order->seller_amount ?? $order->price, 2) }}</p>
                <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                <p><strong>Deadline:</strong> {{ $order->delivery_deadline }}</p>
                <p><strong>Buyer Requirements:</strong><br>{{ $order->requirements ?: 'No extra requirements provided.' }}</p>
            </div>
        </div>

        @if(count($deliveries) > 0)
            <div class="mp-side-card">
                <div class="card-header bg-light">
                    Submitted Deliveries
                </div>
                <div class="card-body">
                    @foreach($deliveries as $delivery)
                        @php
                            $ext = strtolower(pathinfo($delivery->file ?? '', PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','bmp']);
                        @endphp

                        <div class="mp-delivery-item">
                            <div>{{ $delivery->message }}</div>

                            @if(!empty($delivery->file))
                                @if($isImage)
                                    <a href="{{ route('user.marketplace.delivery_file', $delivery->id) }}" target="_blank">
                                        <img src="{{ route('user.marketplace.delivery_file', $delivery->id) }}" class="mp-preview-img" alt="Delivered File">
                                    </a>
                                @endif

                                <div class="mt-2">
                                    <a href="{{ route('user.marketplace.delivery_file', $delivery->id) }}" target="_blank" class="mp-file-link">
                                        View / Download Delivered File
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection