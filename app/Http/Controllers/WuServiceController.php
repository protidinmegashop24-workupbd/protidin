<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class WuServiceController extends Controller
{
    private function walletColumn()
    {
        $possible = ['main_balance', 'balance', 'deposit_balance', 'amount'];

        foreach ($possible as $column) {
            if (Schema::hasColumn('users', $column)) {
                return $column;
            }
        }

        abort(500, 'No balance column found in users table.');
    }

    private function normalizeImagePath($path)
    {
        if (!$path || trim($path) === '') {
            return asset('frontend/assets/img/default-service.jpg');
        }

        $path = ltrim($path, '/');

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        return asset('frontend/assets/img/default-service.jpg');
    }

    private function containsBlockedContact($text)
    {
        if (!$text) {
            return false;
        }

        $text = strtolower($text);

        // block urls
        if (preg_match('/https?:\/\/|www\.|\.com|\.net|\.org|\.io|\.me|t\.me|wa\.me|telegram|whatsapp|imo|facebook|messenger|instagram|gmail|yahoo|outlook/i', $text)) {
            return true;
        }

        // block emails
        if (preg_match('/[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}/i', $text)) {
            return true;
        }

        // block long phone numbers
        if (preg_match('/(\+?\d[\d\-\s\(\)]{7,}\d)/', $text)) {
            return true;
        }

        // block direct contact words
        $badWords = [
            'whatsapp', 'telegram', 'imo', 'messenger', 'facebook',
            'instagram', 'gmail', 'email', 'phone', 'number', 'contact me',
            'call me', 'text me', 'reach me', 'inbox me', 'outside contact'
        ];

        foreach ($badWords as $word) {
            if (Str::contains($text, $word)) {
                return true;
            }
        }

        return false;
    }

    private function canShareExternalAfterOrder($order = null)
    {
        if (!$order) {
            return false;
        }

        return in_array($order->status, ['completed', 'delivered']);
    }

    private function marketplaceCommissionPercent()
{
    if (function_exists('website_info') && website_info() && isset(website_info()->marketplace_commission_percent)) {
        return (float) website_info()->marketplace_commission_percent;
    }

    return 20;
}

private function createEscrowLog($orderId, $buyerId, $sellerId, $amount, $type, $note = null)
{
    \Illuminate\Support\Facades\DB::table('wu_escrow_logs')->insert([
        'order_id' => $orderId,
        'buyer_id' => $buyerId,
        'seller_id' => $sellerId,
        'amount' => $amount,
        'type' => $type,
        'note' => $note,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
    /*
    |--------------------------------------------------------------------------
    | Public Marketplace
    |--------------------------------------------------------------------------
    */
    public function publicIndex()
{
    $services = DB::table('wu_services')
        ->where('status', 'active')
        ->latest()
        ->paginate(12);

    $categories = DB::table('wu_service_categories')
        ->where('status', 1)
        ->orderBy('name', 'asc')
        ->get();

    return view('frontend.wu_services.public-index', compact('services', 'categories'));
}

    public function publicCategory($slug)
{
    $category = DB::table('wu_service_categories')
        ->where('slug', $slug)
        ->where('status', 1)
        ->first();

    if (!$category) {
        abort(404);
    }

    $services = DB::table('wu_services')
        ->where('status', 'active')
        ->where('category', $category->name)
        ->latest()
        ->paginate(12);

    $categories = DB::table('wu_service_categories')
        ->where('status', 1)
        ->orderBy('name', 'asc')
        ->get();

    return view('frontend.wu_services.public-index', compact('services', 'categories', 'category'));
}

    public function serviceShow($slug)
{
    $service = DB::table('wu_services')
        ->leftJoin('users', 'wu_services.user_id', '=', 'users.id')
        ->where('wu_services.slug', $slug)
        ->where('wu_services.status', 'active')
        ->select(
            'wu_services.*',
            'users.name as seller_name',
            'users.username as seller_username',
            'users.image as seller_image',
            'users.created_at as seller_join_date',
            'users.seller_bio as seller_bio',
            'users.seller_skills as seller_skills',
            'users.seller_experience_level as seller_experience_level'
        )
        ->first();

    if (!$service) {
        abort(404);
    }

    $service->is_own = ((int)$service->user_id === (int)auth()->id());
    $service->image_url = wu_service_image($service->image);

    $sellerProfileImage = !empty($service->seller_image)
        ? url($service->seller_image)
        : asset('frontend/img/user.png');

    $sellerId = $service->user_id;

    $inquiries = DB::table('wu_service_inquiries')
        ->where('service_id', $service->id)
        ->where(function ($q) use ($sellerId) {
            $q->where(function ($sub) use ($sellerId) {
                $sub->where('sender_id', auth()->id())
                    ->where('receiver_id', $sellerId);
            })->orWhere(function ($sub) use ($sellerId) {
                $sub->where('sender_id', $sellerId)
                    ->where('receiver_id', auth()->id());
            });
        })
        ->orderBy('id', 'asc')
        ->get();

    $reviews = DB::table('wu_service_reviews')
        ->join('wu_service_orders', 'wu_service_reviews.service_order_id', '=', 'wu_service_orders.id')
        ->leftJoin('users', 'wu_service_reviews.buyer_id', '=', 'users.id')
        ->where('wu_service_orders.service_id', $service->id)
        ->select(
            'wu_service_reviews.*',
            'users.name as buyer_name',
            'users.image as buyer_image'
        )
        ->orderBy('wu_service_reviews.id', 'desc')
        ->get();

    $avgRating = $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 0;
    $totalReviews = $reviews->count();

    return view('user.pages.marketplace.service-show', compact(
        'service',
        'sellerProfileImage',
        'inquiries',
        'reviews',
        'avgRating',
        'totalReviews'
    ));
}

    public function publicShow($slug)
{
    $service = DB::table('wu_services')
        ->leftJoin('users', 'wu_services.user_id', '=', 'users.id')
        ->where('wu_services.slug', $slug)
        ->where('wu_services.status', 'active')
        ->select(
            'wu_services.*',
            'users.name as seller_name',
            'users.username as seller_username',
            'users.image as seller_image',
            'users.created_at as seller_join_date',
            'users.seller_bio',
            'users.seller_skills',
            'users.seller_experience_level'
        )
        ->first();

    if (!$service) {
        abort(404);
    }

    $service->image_url = wu_service_image($service->image);

    $sellerProfileImage = !empty($service->seller_image)
        ? url($service->seller_image)
        : asset('frontend/img/user.png');

    $reviews = DB::table('wu_service_reviews')
        ->join('wu_service_orders', 'wu_service_reviews.service_order_id', '=', 'wu_service_orders.id')
        ->leftJoin('users', 'wu_service_reviews.buyer_id', '=', 'users.id')
        ->where('wu_service_orders.service_id', $service->id)
        ->select(
            'wu_service_reviews.*',
            'users.name as buyer_name',
            'users.image as buyer_image'
        )
        ->orderBy('wu_service_reviews.id', 'desc')
        ->get();

    $avgRating = $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 0;
    $totalReviews = $reviews->count();

    return view('frontend.wu_services.public-show', compact(
        'service',
        'sellerProfileImage',
        'reviews',
        'avgRating',
        'totalReviews'
    ));
}


    /*
    |--------------------------------------------------------------------------
    | User Dashboard
    |--------------------------------------------------------------------------
    */
    public function dashboard()
    {
        $myServiceCount = DB::table('wu_services')->where('user_id', auth()->id())->count();
        $myOrderCount = DB::table('wu_service_orders')->where('buyer_id', auth()->id())->count();
        $mySalesCount = DB::table('wu_service_orders')->where('seller_id', auth()->id())->count();

        $myServices = DB::table('wu_services')
            ->where('user_id', auth()->id())
            ->latest()
            ->limit(6)
            ->get();

        foreach ($myServices as $service) {
            $service->image_url = $this->normalizeImagePath($service->image);
        }

        return view('user.pages.marketplace.index', compact(
            'myServiceCount',
            'myOrderCount',
            'mySalesCount',
            'myServices'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Seller services
    |--------------------------------------------------------------------------
    */
    public function create()
{
    $categories = DB::table('wu_service_categories')
        ->where('status', 1)
        ->orderBy('name', 'asc')
        ->get();

    return view('user.pages.marketplace.create', compact('categories'));
}

    public function store(Request $request)
{
    $type = $request->type === 'digital_product' ? 'digital_product' : 'service';

    $rules = [
        'title' => 'required|max:255',
        'category' => 'required|max:100',
        'price' => 'required|numeric|min:1',
        'short_description' => 'nullable|max:500',
        'description' => 'required',
        'image' => 'nullable|image|max:4096',
    ];

    if ($type === 'digital_product') {
        $rules['product_file'] = 'required|file|max:51200';
    } else {
        $rules['delivery_days'] = 'required|integer|min:1';
        $rules['revision_limit'] = 'nullable|integer|min:0';
    }

    $request->validate($rules);

    $slug = Str::slug($request->title) . '-' . rand(1000, 9999);
    $imagePath = null;

    if ($request->hasFile('image')) {
        if (!file_exists(public_path('uploads/wu-services'))) {
            @mkdir(public_path('uploads/wu-services'), 0777, true);
        }

        $file = $request->file('image');
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $file->move(public_path('uploads/wu-services'), $filename);
        $imagePath = 'uploads/wu-services/' . $filename;
    }

    $filePath = null;
    if ($type === 'digital_product' && $request->hasFile('product_file')) {
        $filePath = $this->storeProductFile($request->file('product_file'));
    }

    DB::table('wu_services')->insert([
        'user_id' => auth()->id(),
        'type' => $type,
        'title' => $request->title,
        'slug' => $slug,
        'category' => $request->category,
        'price' => $request->price,
        'delivery_days' => $type === 'digital_product' ? 0 : $request->delivery_days,
        'revision_limit' => $type === 'digital_product' ? 0 : ($request->revision_limit ?? 0),
        'short_description' => $request->short_description,
        'description' => $request->description,
        'image' => $imagePath,
        'file_path' => $filePath,
        'status' => 'pending',
        'featured' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $label = $type === 'digital_product' ? 'Digital product' : 'Service';
    return redirect()->route('user.marketplace.my_services')->with('success', "{$label} submitted successfully.");
}

    /**
     * Store an uploaded digital product file in a private (non-public) directory
     * so it can only be reached through the authenticated download route.
     */
    private function storeProductFile($file)
    {
        $dir = storage_path('app/private/wu-products');
        if (!file_exists($dir)) {
            @mkdir($dir, 0755, true);
        }

        $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);

        return $filename;
    }

    public function myServices()
{
    $services = DB::table('wu_services')
        ->where('user_id', auth()->id())
        ->latest()
        ->paginate(10);

    foreach ($services as $service) {
        $service->image_url = $this->normalizeImagePath($service->image);

        $service->inquiry_count = DB::table('wu_service_inquiries')
            ->where('service_id', $service->id)
            ->count();

        $service->unread_inquiry_count = DB::table('wu_service_inquiries')
            ->where('service_id', $service->id)
            ->where('receiver_id', auth()->id())
            ->where('is_seen', 0)
            ->count();
    }

    return view('user.pages.marketplace.my-services', compact('services'));
}

    public function edit($id)
{
    $service = DB::table('wu_services')
        ->where('id', $id)
        ->where('user_id', auth()->id())
        ->first();

    if (!$service) {
        abort(404);
    }

    $categories = DB::table('wu_service_categories')
        ->where('status', 1)
        ->orderBy('name', 'asc')
        ->get();

    $service->image_url = wu_service_image($service->image);

    return view('user.pages.marketplace.edit', compact('service', 'categories'));
}

    public function update(Request $request, $id)
{
    $service = DB::table('wu_services')
        ->where('id', $id)
        ->where('user_id', auth()->id())
        ->first();

    if (!$service) {
        abort(404);
    }

    $type = $service->type ?? 'service';

    $rules = [
        'title' => 'required|max:255',
        'category' => 'required|max:100',
        'price' => 'required|numeric|min:1',
        'short_description' => 'nullable|max:500',
        'description' => 'required',
        'image' => 'nullable|image|max:4096',
    ];

    if ($type === 'digital_product') {
        $rules['product_file'] = 'nullable|file|max:51200';
    } else {
        $rules['delivery_days'] = 'required|integer|min:1';
        $rules['revision_limit'] = 'nullable|integer|min:0';
    }

    $request->validate($rules);

    $imagePath = $service->image;

    if ($request->hasFile('image')) {
        if (!file_exists(public_path('uploads/wu-services'))) {
            @mkdir(public_path('uploads/wu-services'), 0777, true);
        }

        $file = $request->file('image');
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $file->move(public_path('uploads/wu-services'), $filename);
        $imagePath = 'uploads/wu-services/' . $filename;
    }

    $filePath = $service->file_path;
    if ($type === 'digital_product' && $request->hasFile('product_file')) {
        $filePath = $this->storeProductFile($request->file('product_file'));
    }

    DB::table('wu_services')
        ->where('id', $id)
        ->update([
            'title' => $request->title,
            'category' => $request->category,
            'price' => $request->price,
            'delivery_days' => $type === 'digital_product' ? 0 : $request->delivery_days,
            'revision_limit' => $type === 'digital_product' ? 0 : ($request->revision_limit ?? 0),
            'short_description' => $request->short_description,
            'description' => $request->description,
            'image' => $imagePath,
            'file_path' => $filePath,
            'status' => 'pending',
            'updated_at' => now(),
        ]);

    return redirect()->route('user.marketplace.my_services')->with('success', 'Listing updated and sent for review.');
}

    public function delete($id)
    {
        $service = DB::table('wu_services')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$service) {
            abort(404);
        }

        DB::table('wu_services')->where('id', $id)->delete();

        return back()->with('success', 'Service deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Buyer browse
    |--------------------------------------------------------------------------
    */
    public function browseServices()
{
    $services = DB::table('wu_services')
        ->where('status', 'active')
        ->where('user_id', '!=', auth()->id())
        ->latest()
        ->paginate(12);

    $categories = DB::table('wu_service_categories')
        ->where('status', 1)
        ->orderBy('name', 'asc')
        ->get();

    return view('user.pages.marketplace.services', compact('services', 'categories'));
}

    public function browseServicesByCategory($slug)
{
    $category = DB::table('wu_service_categories')
        ->where('slug', $slug)
        ->where('status', 1)
        ->first();

    if (!$category) {
        abort(404);
    }

    $services = DB::table('wu_services')
        ->where('status', 'active')
        ->where('user_id', '!=', auth()->id())
        ->where('category', $category->name)
        ->latest()
        ->paginate(12);

    $categories = DB::table('wu_service_categories')
        ->where('status', 1)
        ->orderBy('name', 'asc')
        ->get();

    return view('user.pages.marketplace.services', compact('services', 'categories', 'category'));
}

    /*
    |--------------------------------------------------------------------------
    | Pre-order inquiries
    |--------------------------------------------------------------------------
    */
    public function inquiries()
{
    $myId = auth()->id();

    $threads = DB::table('wu_service_inquiries as i')
        ->join('wu_services as s', 'i.service_id', '=', 's.id')
        ->leftJoin('users as sender', 'i.sender_id', '=', 'sender.id')
        ->leftJoin('users as receiver', 'i.receiver_id', '=', 'receiver.id')
        ->where(function ($q) use ($myId) {
            $q->where('i.sender_id', $myId)
              ->orWhere('i.receiver_id', $myId);
        })
        ->select(
            'i.service_id',
            's.title as service_title',
            's.slug as service_slug',
            's.user_id as seller_id',
            'i.sender_id',
            'i.receiver_id',
            'sender.name as sender_name',
            'receiver.name as receiver_name',
            DB::raw('MAX(i.id) as last_id')
        )
        ->groupBy(
            'i.service_id',
            's.title',
            's.slug',
            's.user_id',
            'i.sender_id',
            'i.receiver_id',
            'sender.name',
            'receiver.name'
        )
        ->orderByDesc('last_id')
        ->get();

    foreach ($threads as $thread) {
        $otherId = $thread->sender_id == $myId ? $thread->receiver_id : $thread->sender_id;

        $thread->other_user_id = $otherId;
        $thread->other_user_name = $thread->sender_id == $myId ? $thread->receiver_name : $thread->sender_name;

        $thread->unread_count = DB::table('wu_service_inquiries')
            ->where('service_id', $thread->service_id)
            ->where('sender_id', $otherId)
            ->where('receiver_id', $myId)
            ->where('is_seen', 0)
            ->count();
    }

    return view('user.pages.marketplace.inquiries', compact('threads'));
}

    public function inquiryThread($serviceId, $userId)
{
    $myId = auth()->id();

    $service = DB::table('wu_services')->where('id', $serviceId)->first();
    if (!$service) {
        abort(404);
    }

    $otherUser = DB::table('users')->where('id', $userId)->first();
    if (!$otherUser) {
        abort(404);
    }

    // must have an actual conversation between me and this user for this service
    $threadExists = DB::table('wu_service_inquiries')
        ->where('service_id', $serviceId)
        ->where(function ($q) use ($myId, $userId) {
            $q->where(function ($sub) use ($myId, $userId) {
                $sub->where('sender_id', $myId)
                    ->where('receiver_id', $userId);
            })->orWhere(function ($sub) use ($myId, $userId) {
                $sub->where('sender_id', $userId)
                    ->where('receiver_id', $myId);
            });
        })
        ->exists();

    // allow seller to open any thread on own service, or any participant to open their own thread
    $isSeller = ((int)$service->user_id === (int)$myId);

    if (!$threadExists && !$isSeller) {
        abort(403);
    }

    // mark incoming messages as seen
    DB::table('wu_service_inquiries')
        ->where('service_id', $serviceId)
        ->where('sender_id', $userId)
        ->where('receiver_id', $myId)
        ->where('is_seen', 0)
        ->update([
            'is_seen' => 1,
            'updated_at' => now(),
        ]);

    $messages = DB::table('wu_service_inquiries')
        ->where('service_id', $serviceId)
        ->where(function ($q) use ($myId, $userId) {
            $q->where(function ($sub) use ($myId, $userId) {
                $sub->where('sender_id', $myId)
                    ->where('receiver_id', $userId);
            })->orWhere(function ($sub) use ($myId, $userId) {
                $sub->where('sender_id', $userId)
                    ->where('receiver_id', $myId);
            });
        })
        ->orderBy('id', 'asc')
        ->get();

    return view('user.pages.marketplace.inquiry-thread', compact('service', 'otherUser', 'messages'));
}

    public function sendInquiry(Request $request, $serviceId, $userId)
{
    $request->validate([
        'message' => 'required|max:5000',
        'file' => 'nullable|file|max:4096',
    ]);

    if ($this->containsBlockedContact($request->message)) {
        return back()->with('error', 'External links, phone numbers, emails, or outside contact details are not allowed before placing an order.');
    }

    $service = DB::table('wu_services')->where('id', $serviceId)->first();
    if (!$service) {
        abort(404);
    }

    $myId = auth()->id();

    // receiver must be the seller or a real conversation participant on that service
    $participantExists = DB::table('wu_service_inquiries')
        ->where('service_id', $serviceId)
        ->where(function ($q) use ($myId, $userId) {
            $q->where(function ($sub) use ($myId, $userId) {
                $sub->where('sender_id', $myId)
                    ->where('receiver_id', $userId);
            })->orWhere(function ($sub) use ($myId, $userId) {
                $sub->where('sender_id', $userId)
                    ->where('receiver_id', $myId);
            });
        })
        ->exists();

    $isSeller = ((int)$service->user_id === (int)$myId);
    $receiverIsSeller = ((int)$userId === (int)$service->user_id);

    if (!$receiverIsSeller && !$participantExists && !$isSeller) {
        abort(403);
    }

    if ($request->hasFile('file')) {
        return back()->with('error', 'Attachments are not allowed before placing an order.');
    }

    DB::table('wu_service_inquiries')->insert([
        'service_id' => $serviceId,
        'sender_id' => $myId,
        'receiver_id' => $userId,
        'message' => strip_tags($request->message),
        'file' => null,
        'is_seen' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Inquiry sent successfully.');
}

public function messageFile($id)
{
    $message = \Illuminate\Support\Facades\DB::table('wu_service_messages')
        ->where('id', $id)
        ->first();

    if (!$message) {
        abort(404);
    }

    $order = \Illuminate\Support\Facades\DB::table('wu_service_orders')
        ->where('id', $message->order_id)
        ->where(function ($q) {
            $q->where('buyer_id', auth()->id())
              ->orWhere('seller_id', auth()->id());
        })
        ->first();

    if (!$order) {
        abort(403);
    }

    if (empty($message->file)) {
        abort(404);
    }

    $path = public_path($message->file);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
}

public function deliveryFile($id)
{
    $delivery = \Illuminate\Support\Facades\DB::table('wu_service_deliveries')
        ->where('id', $id)
        ->first();

    if (!$delivery) {
        abort(404);
    }

    $order = \Illuminate\Support\Facades\DB::table('wu_service_orders')
        ->where('id', $delivery->service_order_id)
        ->where(function ($q) {
            $q->where('buyer_id', auth()->id())
              ->orWhere('seller_id', auth()->id());
        })
        ->first();

    if (!$order) {
        abort(403);
    }

    if (empty($delivery->file)) {
        abort(404);
    }

    $path = public_path($delivery->file);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
}

public function downloadProduct($orderId)
{
    $order = \Illuminate\Support\Facades\DB::table('wu_service_orders')
        ->where('id', $orderId)
        ->where(function ($q) {
            $q->where('buyer_id', auth()->id())
              ->orWhere('seller_id', auth()->id());
        })
        ->first();

    if (!$order) {
        abort(403);
    }

    // Only paid orders (escrow held or already released) can download.
    if (!in_array($order->status, ['delivered', 'completed'])) {
        abort(403);
    }

    $service = \Illuminate\Support\Facades\DB::table('wu_services')
        ->where('id', $order->service_id)
        ->first();

    if (!$service || $service->type !== 'digital_product' || empty($service->file_path)) {
        abort(404);
    }

    $path = storage_path('app/private/wu-products/' . $service->file_path);

    if (!file_exists($path)) {
        abort(404);
    }

    $downloadName = \Illuminate\Support\Str::slug($service->title) . '.' . pathinfo($path, PATHINFO_EXTENSION);

    return response()->download($path, $downloadName);
}

    /*
    |--------------------------------------------------------------------------
    | Orders
    |--------------------------------------------------------------------------
    */
    public function orders()
{
    $orders = \Illuminate\Support\Facades\DB::table('wu_service_orders')
        ->join('wu_services', 'wu_service_orders.service_id', '=', 'wu_services.id')
        ->where('wu_service_orders.buyer_id', auth()->id())
        ->select(
            'wu_service_orders.*',
            'wu_services.title as service_title',
            'wu_services.image as service_image',
            'wu_services.type as service_type'
        )
        ->latest('wu_service_orders.id')
        ->paginate(12);

    foreach ($orders as $order) {
        $order->image_url = wu_service_image($order->service_image);
    }

    return view('user.pages.marketplace.orders', compact('orders'));
}

    public function order(\Illuminate\Http\Request $request, $serviceId)
{
    $request->validate([
        'requirements' => 'nullable|string|max:3000',
    ]);

    $service = \Illuminate\Support\Facades\DB::table('wu_services')
        ->where('id', $serviceId)
        ->where('status', 'active')
        ->first();

    if (!$service) {
        abort(404);
    }

    if ((int)$service->user_id === (int)auth()->id()) {
        return back()->with('error', 'You cannot order your own service.');
    }

    $buyer = \App\Models\User::find(auth()->id());
    $seller = \App\Models\User::find($service->user_id);

    if (!$buyer || !$seller) {
        return back()->with('error', 'Buyer or seller account not found.');
    }

    $price = (float)$service->price;
    $commissionPercent = $this->marketplaceCommissionPercent();
    $adminCommission = round(($price * $commissionPercent) / 100, 2);
    $sellerAmount = round($price - $adminCommission, 2);

    if ((float)$buyer->deposit_balance < $price) {
        return back()->with('error', 'Insufficient deposit balance.');
    }

    \Illuminate\Support\Facades\DB::beginTransaction();

    try {
        $buyer->deposit_balance = (float)$buyer->deposit_balance - $price;
        $buyer->save();

        $isDigitalProduct = ($service->type ?? 'service') === 'digital_product';

        $orderId = \Illuminate\Support\Facades\DB::table('wu_service_orders')->insertGetId([
            'service_id' => $service->id,
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'price' => $price,
            'escrow_amount' => $price,
            'admin_commission' => $adminCommission,
            'seller_amount' => $sellerAmount,
            'requirements' => $request->requirements,
            'status' => $isDigitalProduct ? 'delivered' : 'in_progress',
            'payment_status' => 'held',
            'delivery_deadline' => now()->addDays((int)$service->delivery_days),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->createEscrowLog(
            $orderId,
            $buyer->id,
            $seller->id,
            $price,
            'hold',
            'Order payment held in escrow.'
        );

        // Digital products are already on the server, so delivery is instant:
        // no manual seller step, the buyer just gets the download button now.
        if ($isDigitalProduct) {
            \Illuminate\Support\Facades\DB::table('wu_service_messages')->insert([
                'order_id' => $orderId,
                'sender_id' => $seller->id,
                'receiver_id' => $buyer->id,
                'message' => 'Your digital product is ready. Use the Download button on this order to get your file.',
                'is_seen' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        \Illuminate\Support\Facades\DB::commit();

        // Best-effort referral bonus hook -- must never turn a successful,
        // already-committed order into a false "Order failed" message.
        if (method_exists(\App\Http\Controllers\User\UserReferralController::class, 'processMarketplaceBonus')) {
            try {
                app(\App\Http\Controllers\User\UserReferralController::class)->processMarketplaceBonus($buyer->id);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('processMarketplaceBonus failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('user.marketplace.orders')->with('success', 'Order placed successfully. Payment is now held in escrow.');
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\DB::rollBack();
        return back()->with('error', 'Order failed: ' . $e->getMessage());
    }
}

    public function myOrders()
    {
        $orders = DB::table('wu_service_orders')
            ->join('wu_services', 'wu_service_orders.service_id', '=', 'wu_services.id')
            ->where('wu_service_orders.buyer_id', auth()->id())
            ->select(
                'wu_service_orders.*',
                'wu_services.title as service_title',
                'wu_services.slug as service_slug',
                'wu_services.image as service_image'
            )
            ->latest('wu_service_orders.id')
            ->paginate(10);

        foreach ($orders as $order) {
            $order->image_url = $this->normalizeImagePath($order->service_image);
        }

        return view('user.pages.marketplace.orders', compact('orders'));
    }

    public function sales()
{
    $orders = \Illuminate\Support\Facades\DB::table('wu_service_orders')
        ->join('wu_services', 'wu_service_orders.service_id', '=', 'wu_services.id')
        ->join('users', 'wu_service_orders.buyer_id', '=', 'users.id')
        ->where('wu_service_orders.seller_id', auth()->id())
        ->select(
            'wu_service_orders.*',
            'wu_services.title as service_title',
            'wu_services.image as service_image',
            'users.name as buyer_name'
        )
        ->latest('wu_service_orders.id')
        ->paginate(12);

    foreach ($orders as $order) {
        $order->image_url = wu_service_image($order->service_image);
    }

    return view('user.pages.marketplace.sales', compact('orders'));
}

    public function mySales()
    {
        $orders = DB::table('wu_service_orders')
            ->join('wu_services', 'wu_service_orders.service_id', '=', 'wu_services.id')
            ->join('users', 'wu_service_orders.buyer_id', '=', 'users.id')
            ->where('wu_service_orders.seller_id', auth()->id())
            ->select(
                'wu_service_orders.*',
                'wu_services.title as service_title',
                'wu_services.image as service_image',
                'users.name as buyer_name'
            )
            ->latest('wu_service_orders.id')
            ->paginate(10);

        foreach ($orders as $order) {
            $order->image_url = $this->normalizeImagePath($order->service_image);
        }

        return view('user.pages.marketplace.sales', compact('orders'));
    }

    /*
    |--------------------------------------------------------------------------
    | Order chat / delivery / complete / review
    |--------------------------------------------------------------------------
    */
    public function orderChat($id)
{
    $order = \Illuminate\Support\Facades\DB::table('wu_service_orders')
        ->join('wu_services', 'wu_service_orders.service_id', '=', 'wu_services.id')
        ->where('wu_service_orders.id', $id)
        ->where(function ($q) {
            $q->where('wu_service_orders.buyer_id', auth()->id())
              ->orWhere('wu_service_orders.seller_id', auth()->id());
        })
        ->select(
            'wu_service_orders.*',
            'wu_services.title as service_title',
            'wu_services.image as service_image',
            'wu_services.delivery_days as service_delivery_days',
            'wu_services.type as service_type'
        )
        ->first();

    if (!$order) {
        abort(404);
    }

    $otherUserId = $order->buyer_id == auth()->id() ? $order->seller_id : $order->buyer_id;

    \Illuminate\Support\Facades\DB::table('wu_service_messages')
        ->where('order_id', $id)
        ->where('sender_id', $otherUserId)
        ->where('receiver_id', auth()->id())
        ->where('is_seen', 0)
        ->update([
            'is_seen' => 1,
            'updated_at' => now(),
        ]);

    $messages = \Illuminate\Support\Facades\DB::table('wu_service_messages')
        ->where('order_id', $id)
        ->orderBy('id', 'asc')
        ->get();

    $deliveries = \Illuminate\Support\Facades\DB::table('wu_service_deliveries')
        ->where('service_order_id', $id)
        ->latest()
        ->get();

    $hasReview = \Illuminate\Support\Facades\DB::table('wu_service_reviews')
        ->where('service_order_id', $id)
        ->exists();

    $order->image_url = wu_service_image($order->service_image);

    return view('user.pages.marketplace.chat', compact('order', 'messages', 'deliveries', 'hasReview'));
}

    public function sendMessage(\Illuminate\Http\Request $request, $id)
{
    $request->validate([
        'message' => 'required|max:5000',
        'file' => 'nullable|file|max:5120',
    ]);

    $order = \Illuminate\Support\Facades\DB::table('wu_service_orders')
        ->where('id', $id)
        ->where(function ($q) {
            $q->where('buyer_id', auth()->id())
              ->orWhere('seller_id', auth()->id());
        })
        ->first();

    if (!$order) {
        abort(404);
    }

    $receiverId = $order->buyer_id == auth()->id() ? $order->seller_id : $order->buyer_id;

    $filePath = null;
    if ($request->hasFile('file')) {
        if (!file_exists(public_path('uploads/wu-service-messages'))) {
            @mkdir(public_path('uploads/wu-service-messages'), 0777, true);
        }

        $file = $request->file('file');
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $file->move(public_path('uploads/wu-service-messages'), $filename);
        $filePath = 'uploads/wu-service-messages/' . $filename;
    }

    \Illuminate\Support\Facades\DB::table('wu_service_messages')->insert([
        'order_id' => $id,
        'sender_id' => auth()->id(),
        'receiver_id' => $receiverId,
        'message' => strip_tags($request->message),
        'file' => $filePath,
        'is_seen' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Message sent successfully.');
}

    public function deliverOrder(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|max:5000',
            'file' => 'nullable|file|max:4096',
        ]);

        $order = DB::table('wu_service_orders')
            ->where('id', $id)
            ->where('seller_id', auth()->id())
            ->first();

        if (!$order) {
            abort(404);
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            if (!file_exists(public_path('uploads/wu-service-deliveries'))) {
                @mkdir(public_path('uploads/wu-service-deliveries'), 0777, true);
            }

            $file = $request->file('file');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/wu-service-deliveries'), $filename);
            $filePath = 'uploads/wu-service-deliveries/' . $filename;
        }

        DB::table('wu_service_deliveries')->insert([
            'service_order_id' => $id,
            'seller_id' => auth()->id(),
            'message' => strip_tags($request->message),
            'file' => $filePath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('wu_service_orders')
            ->where('id', $id)
            ->update([
                'status' => 'delivered',
                'updated_at' => now(),
            ]);

        DB::table('wu_service_messages')->insert([
            'order_id' => $id,
            'sender_id' => auth()->id(),
            'receiver_id' => $order->buyer_id,
            'message' => 'Order delivered. Please review the delivery.',
            'is_seen' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Order delivered successfully.');
    }
    
    public function deliver(\Illuminate\Http\Request $request, $id)
{
    $request->validate([
        'message' => 'required|max:5000',
        'file' => 'nullable|file|max:10240',
    ]);

    $order = \Illuminate\Support\Facades\DB::table('wu_service_orders')
        ->where('id', $id)
        ->where('seller_id', auth()->id())
        ->first();

    if (!$order) {
        abort(404);
    }

    if (!in_array($order->status, ['in_progress', 'revision_requested'])) {
        return back()->with('error', 'This order cannot be delivered now.');
    }

    $filePath = null;
    if ($request->hasFile('file')) {
        if (!file_exists(public_path('uploads/wu-service-deliveries'))) {
            @mkdir(public_path('uploads/wu-service-deliveries'), 0777, true);
        }

        $file = $request->file('file');
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $file->move(public_path('uploads/wu-service-deliveries'), $filename);
        $filePath = 'uploads/wu-service-deliveries/' . $filename;
    }

    \Illuminate\Support\Facades\DB::table('wu_service_deliveries')->insert([
        'service_order_id' => $id,
        'seller_id' => auth()->id(),
        'message' => strip_tags($request->message),
        'file' => $filePath,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    \Illuminate\Support\Facades\DB::table('wu_service_orders')
        ->where('id', $id)
        ->update([
            'status' => 'delivered',
            'updated_at' => now(),
        ]);

    \Illuminate\Support\Facades\DB::table('wu_service_messages')->insert([
        'order_id' => $id,
        'sender_id' => auth()->id(),
        'receiver_id' => $order->buyer_id,
        'message' => 'Order delivered: ' . strip_tags($request->message),
        'file' => $filePath,
        'is_seen' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Order delivered successfully.');
}

    public function completeOrder($id)
    {
        $order = DB::table('wu_service_orders')
            ->where('id', $id)
            ->where('buyer_id', auth()->id())
            ->first();

        if (!$order) {
            abort(404);
        }

        if ($order->status !== 'delivered') {
            return back()->with('error', 'This order is not ready to complete.');
        }

        $walletColumn = $this->walletColumn();

        DB::beginTransaction();

        try {
            DB::table('users')
                ->where('id', $order->seller_id)
                ->increment($walletColumn, $order->price);

            DB::table('wu_service_orders')
                ->where('id', $id)
                ->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                    'updated_at' => now(),
                ]);

            DB::table('wu_service_messages')->insert([
                'order_id' => $id,
                'sender_id' => auth()->id(),
                'receiver_id' => $order->seller_id,
                'message' => 'Buyer marked this order as completed.',
                'is_seen' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return back()->with('success', 'Order completed and seller has been paid.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Completion failed.');
        }
    }
    
    public function cancelOrder($id)
{
    $order = \Illuminate\Support\Facades\DB::table('wu_service_orders')
        ->where('id', $id)
        ->where(function ($q) {
            $q->where('buyer_id', auth()->id())
              ->orWhere('seller_id', auth()->id());
        })
        ->first();

    if (!$order) {
        abort(404);
    }

    if (!in_array($order->status, ['in_progress', 'revision_requested'])) {
        return back()->with('error', 'This order cannot be cancelled now.');
    }

    if (($order->payment_status ?? 'held') !== 'held') {
        return back()->with('error', 'Escrow already processed.');
    }

    $buyer = \App\Models\User::find($order->buyer_id);

    if (!$buyer) {
        return back()->with('error', 'Buyer account not found.');
    }

    \Illuminate\Support\Facades\DB::beginTransaction();

    try {
        $buyer->deposit_balance = (float)$buyer->deposit_balance + (float)$order->escrow_amount;
        $buyer->save();

        \Illuminate\Support\Facades\DB::table('wu_service_orders')
            ->where('id', $id)
            ->update([
                'status' => 'cancelled',
                'payment_status' => 'refunded',
                'updated_at' => now(),
            ]);

        $this->createEscrowLog(
            $order->id,
            $order->buyer_id,
            $order->seller_id,
            $order->escrow_amount,
            'refund',
            'Escrow refunded to buyer deposit balance.'
        );

        \Illuminate\Support\Facades\DB::commit();

        return back()->with('success', 'Order cancelled and refunded successfully.');
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\DB::rollBack();
        return back()->with('error', 'Refund failed: ' . $e->getMessage());
    }
}
    
    public function complete($id)
{
    $order = \Illuminate\Support\Facades\DB::table('wu_service_orders')
        ->where('id', $id)
        ->where('buyer_id', auth()->id())
        ->first();

    if (!$order) {
        abort(404);
    }

    if ($order->status !== 'delivered') {
        return back()->with('error', 'Only delivered orders can be completed.');
    }

    if (($order->payment_status ?? 'held') !== 'held') {
        return back()->with('error', 'Escrow is not in held state.');
    }

    $seller = \App\Models\User::find($order->seller_id);

    if (!$seller) {
        return back()->with('error', 'Seller account not found.');
    }

    \Illuminate\Support\Facades\DB::beginTransaction();

    try {
        // seller gets money in earning_balance, NOT deposit_balance
        $seller->earning_balance = (float)$seller->earning_balance + (float)$order->seller_amount;
        $seller->save();

        \Illuminate\Support\Facades\DB::table('wu_service_orders')
            ->where('id', $id)
            ->update([
                'status' => 'completed',
                'payment_status' => 'released',
                'completed_at' => now(),
                'updated_at' => now(),
            ]);

        $this->createEscrowLog(
            $order->id,
            $order->buyer_id,
            $order->seller_id,
            $order->seller_amount,
            'release',
            'Escrow released to seller earning balance.'
        );

        $this->createEscrowLog(
            $order->id,
            $order->buyer_id,
            $order->seller_id,
            $order->admin_commission,
            'commission',
            'Admin commission recorded.'
        );

        \Illuminate\Support\Facades\DB::commit();

        return back()->with('success', 'Order completed and seller payment released to earning balance.');
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\DB::rollBack();
        return back()->with('error', 'Failed to release escrow: ' . $e->getMessage());
    }
}
 
    public function submitReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|max:1000',
        ]);

        $order = DB::table('wu_service_orders')
            ->where('id', $id)
            ->where('buyer_id', auth()->id())
            ->where('status', 'completed')
            ->first();

        if (!$order) {
            abort(404);
        }

        $exists = DB::table('wu_service_reviews')
            ->where('service_order_id', $id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Review already submitted.');
        }

        DB::table('wu_service_reviews')->insert([
            'service_order_id' => $id,
            'buyer_id' => auth()->id(),
            'seller_id' => $order->seller_id,
            'rating' => $request->rating,
            'comment' => strip_tags($request->comment),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Review submitted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */
    public function adminIndex()
    {
        $services = DB::table('wu_services')
            ->leftJoin('users', 'wu_services.user_id', '=', 'users.id')
            ->select(
                'wu_services.*',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->latest('wu_services.id')
            ->paginate(20);

        foreach ($services as $service) {
            $service->image_url = $this->normalizeImagePath($service->image);
        }

        return view('admin.wu_services.index', compact('services'));
    }
    
    public function adminCategoryIndex()
{
    $categories = DB::table('wu_service_categories')
        ->latest('id')
        ->paginate(20);

    return view('admin.wu_services.categories', compact('categories'));
}

    public function adminCategoryStore(Request $request)
{
    $request->validate([
        'name' => 'required|max:191|unique:wu_service_categories,name',
    ]);

    DB::table('wu_service_categories')->insert([
        'name' => $request->name,
        'slug' => Str::slug($request->name),
        'status' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Category added successfully.');
}

    public function adminCategoryUpdate(Request $request, $id)
{
    $request->validate([
        'name' => 'required|max:191|unique:wu_service_categories,name,' . $id,
    ]);

    DB::table('wu_service_categories')
        ->where('id', $id)
        ->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'updated_at' => now(),
        ]);

    return back()->with('success', 'Category updated successfully.');
}

    public function adminCategoryDelete($id)
{
    DB::table('wu_service_categories')->where('id', $id)->delete();

    return back()->with('success', 'Category deleted successfully.');
}

    public function adminApprove($id)
    {
        DB::table('wu_services')
            ->where('id', $id)
            ->update([
                'status' => 'active',
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Service approved successfully.');
    }

    public function adminReject($id)
    {
        DB::table('wu_services')
            ->where('id', $id)
            ->update([
                'status' => 'rejected',
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Service rejected successfully.');
    }

    public function adminDelete($id)
    {
        DB::table('wu_services')->where('id', $id)->delete();

        return back()->with('success', 'Service deleted successfully.');
    }
}