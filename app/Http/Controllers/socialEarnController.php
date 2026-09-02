<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin\Website;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\SocialEarn;
use App\Models\communityRate;
use App\Models\feedpost;
use App\Models\feedUserEarnHistory;
use App\Models\feedPostLikes;
use App\Models\feedPostComments;
use App\Models\GoogleAd;
use App\Models\Admin\UserMessage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use DB;

class socialEarnController extends Controller
{

    // admin Part
    public function communityRateSetup(){
        $website = Website::latest()->first();
        $prices = communityRate::all();
        return view('backend.pages.community.communityRateSetup',compact(['prices','website']));
    } 
    public function communityRateSetupStore(Request $req){
        // dd($req);
        $rates = [
            'newPost'               => $req->newPost,
            'postViewerLink'        => $req->postViewerLink,
            'postViewerComment'     => $req->postViewerComment,
            'maxPostPerDay'         => $req->maxPostPerDay,
            'maxUserLinksPerDay'    => $req->maxUserLinksPerDay,
            'maxUserCommentPerDay'  => $req->maxUserCommentPerDay,
        ];

        foreach ($rates as $key => $rate) {
            CommunityRate::updateOrCreate(
                ['bonusKey' => $key],
                ['bonusRate' => $rate]
            );
        }
        return redirect()->back()->with('success','Community Rate Setup Successfully');
    }
    public function cummunityPostList(Request $request){        
        $website = Website::latest()->first();
        $query = feedpost::query();
        $request->validate([
            'action'   => ['nullable', 'in:findById,findByDate'],

            // ID search
            'postId'   => ['required_if:action,findById', 'nullable', 'integer', 'exists:feedposts,id'],

            // Date inputs (both optional now)
            'fromDate' => ['nullable', 'date'],
            'toDate'   => ['nullable', 'date', 'after_or_equal:fromDate'],
        ]);

        if ($request->action === 'findById') {

            $query->where('id', $request->postId);

        } elseif ($request->action === 'findByDate') {

            // If fromDate missing → today
            $from = $request->filled('fromDate')
                ? Carbon::parse($request->fromDate)->startOfDay()
                : Carbon::today()->startOfDay();

            // If toDate missing → today
            $to = $request->filled('toDate')
                ? Carbon::parse($request->toDate)->endOfDay()
                : Carbon::today()->endOfDay();

            $query->whereBetween('created_at', [$from, $to]);
        }


        $posts = $query->latest()->paginate(30)->withQueryString();
        return view('backend.pages.community.cummunityPostList',compact(['website','posts']));
    }
    public function deleteFeedPost(Request $request){
        $id = $request->id;
        if(!$id){
            return redirect()->back()->with('error','Post Not Found');
        }
        $post = feedpost::where('id',$id)->first();
        if(!$post){
            return redirect()->route('home')->with('error','Post Not Found');
        }
        $finsHistory = feedUserEarnHistory::where('postId',$id)->get();
        foreach($finsHistory as $history){
            $user = User::where('id',$history->userId)->first();
            if(!$user){
                continue;
            }
            $user->decrement('earning_balance',$history->price); // Balance Decrese of all users

            $history->delete();
        }
        feedPostLikes::where('postId',$id)->delete();
        feedPostComments::where('postId',$id)->delete();        
        if($post->image && file_exists(public_path($post->image))){
            @unlink(public_path($post->image));
        }
        $post->delete();
        return redirect()->route('admin.cummunityPostList')->with('success','Post Deleted Successfully');
    }
    
    // User Part 
    // Fatch Start 
    public function commynityEarnFatch(Request $request) {
        $request->validate(['url' => 'required|url']);
    
        $url = $request->url;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/110.0.0.0 Safari/537.36');
        
        $html = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
    
        if ($error) {
            return response()->json(['status' => false, 'error_debug' => 'Curl Error: ' . $error]);
        }
    
        if (!$html) {
            return response()->json(['status' => false, 'error_debug' => 'Empty HTML returned']);
        }
    
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        @$doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new \DOMXPath($doc);
    
        $title = $this->queryTag($xpath, '//meta[@property="og:title"]/@content') ?? $this->queryTag($xpath, '//title');
        $image = $this->queryTag($xpath, '//meta[@property="og:image"]/@content');
        $desc = $this->queryTag($xpath, '//meta[@property="og:description"]/@content') ?? $this->queryTag($xpath, '//meta[@name="description"]/@content');
    
        return response()->json([
            'status' => true,
            'data' => [
                'title' => trim($title),
                'description' => trim($desc),
                'image' => $image,
            ]
        ]);
    }
    private function queryTag($xpath, $query) {
        $nodes = $xpath->query($query);
        return ($nodes && $nodes->length > 0) ? $nodes->item(0)->nodeValue : null;
    }
    // Fatch End 
    public function communityEarn(){

        // Production Version 
        // if (!session()->has('random_seed')) {
        //         session(['random_seed' => rand(1, 999999)]);
        //     }
        //     $seed = session('random_seed');

        //     // Fetch posts with likes/comments count for current user
        //     $posts = feedpost::withCount([
        //         'like_history as has_liked' => function($q){
        //             $q->where('userId', auth()->id());
        //         },
        //         'comment_history as has_commented' => function($q){
        //             $q->where('userId', auth()->id());
        //         }
        //     ])
        //     ->where('status', 'approved')          // only approved posts
        //     ->orderByRaw("RAND($seed)")            // random order, consistent per session
        //     ->paginate(15); 

        // Temporory Version 
        // $posts = feedpost::where('status','approved')->orderBy('id', 'DESC')->paginate(15);
        // $posts = feedpost::withCount([
        //     'like_history as has_liked' => function($q){
        //         $q->where('userId', auth()->id());
        //     },
        //     'comment_history as has_commented' => function($q){
        //         $q->where('userId', auth()->id());
        //     }
        // ])->paginate(15);
        $posts = feedpost::where('status','approved')->latest()->paginate(15);

        $website = Website::latest()->first();
        $inFeedAds = GoogleAd::where('position','In-Feed')->get();
        // dd($posts);
        return view('user.pages.cummunityEarn.communityEarn',compact('posts','website','inFeedAds'));
    }
    public function communityPostStore(Request $request) {
        $request->validate([
            'post_content' => 'required|string',
            'post_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'post_video'   => 'nullable|mimes:mp4,mov,avi,webm,mkv|max:51200',
            'fatchUrl'     => 'nullable|url',
        ]);
    
        // Max Post Limit per Day Start 
        $findPostLimit = $this->findvalueOfKey('maxPostPerDay');
        if ($findPostLimit <= 0) {
            return response()->json([
                'status'  => false,
                'message' => 'You have reached the maximum post limit for today',
            ]);
        } else {
            $today = Carbon::today();
            $todayPostCount = feedpost::where('userId', Auth::id())
                ->whereDate('created_at', $today)
                ->count();
            if ($todayPostCount >= $findPostLimit) {                
                return response()->json([
                    'status'  => false,
                    'message' => 'You have reached the maximum post limit for today',
                ]);
            }
        }
        // End Limit Max Post
    
        $imagePath = null;
        if ($request->hasFile('post_image')) {
            $image = $request->file('post_image');
            $dateFolder = Carbon::now()->format('Y-m-d');
            $uploadPath = "uploads/feedposts/{$dateFolder}";
    
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
    
            $extension = $image->getClientOriginalExtension();
            $fileName = 'feed_' . time() . '_' . Str::random(6) . '.' . $extension;
    
            // Resize using Intervention Image
            Image::make($image->getRealPath())
                ->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->save($uploadPath . '/' . $fileName, 72);
    
            $imagePath = "uploads/feedposts/{$dateFolder}/{$fileName}";
        }

        $videoPath = null;
        if ($request->hasFile('post_video')) {
            $video = $request->file('post_video');
            $dateFolder = Carbon::now()->format('Y-m-d');
            $uploadPath = "uploads/feedposts/{$dateFolder}";

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $extension = $video->getClientOriginalExtension();
            $fileName = 'feedvideo_' . time() . '_' . Str::random(6) . '.' . $extension;
            $video->move($uploadPath, $fileName);

            $videoPath = "uploads/feedposts/{$dateFolder}/{$fileName}";
        }

        // Post create with new Fetch fields
        $postAdded = feedpost::create([
            'postContent'      => $request->post_content,
            'fetchUrl'         => $request->fatchUrl,      // New
            'fetchTitle'       => $request->fetchTitle,    // New
            'fetchDescription' => $request->fetchDescription, // New
            'fetchImg'         => $request->fetchImg,      // New
            'summary'          => null,
            'aiRating'         => 0,
            'status'           => 'approved',
            'image'            => $imagePath,
            'video'            => $videoPath,
            'totalUserEarn'    => 0,
            'totalOwnerEarn'   => 0,
            'likes'            => 0,
            'commnets'         => 0,
            'userId'           => Auth::id() ?? 1,
        ]);
    
        // Earning & Referral Logic
        $findPrice = $this->findvalueOfKey('newPost');
        $this->addEarnHistory(Auth::id(), $postAdded->id, 'newPost', $findPrice);
        Auth::user()->increment('earning_balance', $findPrice);
    
        $this->addNotify(
            Auth::id(),
            'New post approved. You earned $' . number_format($findPrice, 2) .
            '<br><a href="' . route('publicPostLink', $postAdded->id) . '">View post</a>',
            'Post Approved'
        );
        
        $this->addReffEarn(Auth::id(), $postAdded->id, $findPrice, $this->findvalueOfKey('earnRef'));
    
        return response()->json([
            'status'  => true,
            'message' => 'Post submitted successfully',
        ]);
    }
    public function communityEarnRate(){
        $prices = communityRate::all();        
        $percent = $this->findvalueOfKey('earnRef');
        return view('user.pages.cummunityEarn.communityEarnRate',compact(['prices','percent']));
    }
    public function viewCommunityPP($id = null){
        if(!$id){
            return redirect()->route('home')->with('error','Post Not Found');
        }
        // $post = feedpost::where('status','approved')->where('id',$id)->first();
        $post = feedpost::where('id',$id)->withCount([
                'like_history as has_liked' => function($q){
                    $q->where('userId', auth()->id());
                },
                'comment_history as has_commented' => function($q){
                    $q->where('userId', auth()->id());
                }
            ])
            ->where('status', 'approved')           // random order, consistent per session
            ->first();
        if(!$post){
            return redirect()->route('home')->with('error','Post Not Found');
        }
        $comments = feedPostComments::where('postId',$post->id)->get();
        // dd($post);        
        return view('user.pages.cummunityEarn.privatePostLink',compact('post','comments'));
    }
    public function newComment($id, Request $request){
        if(!$id){
            return redirect()->route('home')->with('error','Post Not Found');
        }
        $findPost = feedpost::where('status','approved')->where('id',$id)->first();
        if(!$findPost){
            return redirect()->route('home')->with('error','Post Not Found');
        }
        $request->validate([
            'comment_content' => 'required|string',
        ]);
        // if ownership of the post
        if($findPost->userId == Auth::id()){
            $addComment = new feedPostComments();
            $addComment->comment = $request->comment_content;
            // if replay comment
            if($request->parentId){
                $findParent = feedPostComments::where('id',$request->parentId)->first();
                if(!$findParent){
                    return redirect()->back()->with('error','Parent Comment Not Found');
                }
                $addComment->parentId = $request->parentId ?? null;
                if($findParent->userId != Auth::id()){  
                    $this->addNotify(
                        $findParent->userId,
                        'Replying you in Comment. please check<br><a href="/public-shared/' .  $findParent->postId . '">View post</a>',
                        'New Comment Reply'
                    ); 
                }             
            }
            // End Replay Comment 
            $addComment->postId = $findPost->id;
            $addComment->userId = Auth::id();
            $addComment->save();            
            $findPost->increment('commnets');
            return redirect()->back()->with('success','Comment Added Successfully');
        }
        // End Ownership Comment of the post
 
        $addComment = new feedPostComments();
        $addComment->comment = $request->comment_content;
        $addComment->parentId = null;
        $addComment->postId = $findPost->id;
        $addComment->userId = Auth::id();
        $addComment->save();
        $findPost->increment('commnets');
        $checkEarned = $this->checkIfEarned(Auth::id(),$id,'postViewerComment');
        $todayLimit = $this->dailyMaxLimit('maxUserCommentPerDay',Auth::id());
        if(!$checkEarned && $todayLimit){
            // History add 
            $this->addEarnHistory(Auth::id(),$id,'postViewerComment',$this->findvalueOfKey('postViewerComment'));
            // Balance Update 
            Auth::user()->increment('earning_balance', $this->findvalueOfKey('postViewerComment'));
            // Notification Process             
            $this->addNotify(
                $findPost->userId,
                'You Got the New Comment<br><a href="/public-shared/' . $findPost->id . '">View post</a>',
                'New Comment'
            ); 
            $this->addNotify(
                Auth::id(),
                'You Got the ' .$this->findvalueOfKey('postViewerComment') .'$ From The Post <br><a href="/public-shared/' . $findPost->id . '">View post</a>',
                'Earn From Comment'
            ); 
            // add reffel Commision with history        
            $this->addReffEarn(Auth::id(),$findPost->id,$this->findvalueOfKey('postViewerComment'),$this->findvalueOfKey('earnRef'));
            return redirect()->back()->with('success','Comment Added Successfully');
        }else{
            $this->addNotify(
                $findPost->userId,
                'You Got the New Comment<br><a href="/public-shared/' . $findPost->id . '">View post</a>',
                'New Comment'
            );
            return redirect()->back()->with('warning','Max Limit Finished and no earn');
        }
        return redirect()->back()->with('success','Comment Added Successfully');
    }
    public function newLike(Request $request){
        $findPost = feedpost::where('status','approved')->where('id',$request->postId)->first();
        if(!$findPost){
            return response()->json([
                'status' => false,
                'message' => 'Post Not Found',                
            ]);
        }
        if($findPost->userId == Auth::id()){
            return response()->json([
                'status' => false,
                'message' => 'You Can Not Like Your Own Post',
            ]);
        }

        $checkEarned = $this->checkIfEarned(Auth::id(),$findPost->id,'postViewerLink');
        if($checkEarned){
            return response()->json([
                'status' => false,
                'message' => 'You Already Earned From This Post',
            ]);
        }
        $todayLimit = $this->dailyMaxLimit('maxUserLinksPerDay',Auth::id());
        if(!$todayLimit){
            return response()->json([
                'status' => false,
                'message' => 'Max Earn Limit hit today. no money will be add',
            ]);
        }
        $addHistory = new feedPostLikes();
        $addHistory->postId = $findPost->id;
        $addHistory->userId = Auth::id();
        $addHistory->save();
        $this->addEarnHistory(Auth::id(),$findPost->id,'postViewerLink',$this->findvalueOfKey('postViewerLink'));
        Auth::user()->increment('earning_balance', $this->findvalueOfKey('postViewerLink'));
        $this->addNotify(Auth::id(),'You Got the ' .$this->findvalueOfKey('postViewerLink') .'$ From The Post <br><a href="/public-shared/' . $findPost->id . '">View post</a>','Earn From Like');
        $findPost->increment('likes');
        $this->addReffEarn(Auth::id(),$findPost->id,$this->findvalueOfKey('postViewerLink'),$this->findvalueOfKey('earnRef'));
        return response()->json([
            'status' => true,
            'message' => 'You Like The Post',
        ]);
    }
    public function publicPostLink($id = null){
        if(!$id){
            return redirect()->route('home')->with('error','Post Not Found');
        }
        $post = feedpost::where('status','approved')->where('id',$id)->first();
        if(!$post){
            return redirect()->route('home')->with('error','Post Not Found');
        }
        if(Auth::check()){
            return redirect()->route('user.viewCommunityPP',$post->id);
        }
        // dd($post);            
        $post = feedpost::where('id',$id)->first();
        $comments = feedPostComments::with('user')->where('postId', $post->id)->orderBy('created_at', 'ASC')->get();
        return view('user.pages.cummunityEarn.publicPostLink',compact(['post','comments']));
    }
    public function postFeedDashboard(){
       $userId = Auth::id();
        $sixMonthsAgo = Carbon::now()->subMonths(6);

        $history = feedUserEarnHistory::where('userId', $userId)->whereBetween('created_at', [$sixMonthsAgo, now()]);
        $totalEarnings = (clone $history)->sum('price');
        $postEarning = (clone $history)->where('earnType', 'newPost')->sum('price');
        $likeEarning = (clone $history)->where('earnType', 'postViewerLink')->sum('price');
        $commentEarning = (clone $history)->where('earnType', 'postViewerComment')->sum('price');
        $affiliateEarning = (clone $history)->where('earnType', 'earnRef')->sum('price');
        $totalPosts = (clone $history)->where('earnType', 'newPost')->count();
        $totalLikes = (clone $history)->where('earnType', 'postViewerLink')->count();
        $totalComments = (clone $history)->where('earnType', 'postViewerComment')->count();

        return view('user.pages.cummunityEarn.postFeedDashboard', 
            compact(
                'totalEarnings',
                'totalPosts',
                'totalLikes',
                'totalComments',
                'postEarning',
                'likeEarning',
                'commentEarning',
                'affiliateEarning'
            )
        );
    }
    public function myPostFeedList(){
        $posts = feedpost::where('userId',Auth::id())->where('status','approved')->orderBy('id','DESC')->get();
        return view('user.pages.cummunityEarn.myPostFeedList',compact('posts'));
    }
    
    // Reuse Functions 
    protected function addEarnHistory($userId,$postId,$earnType,$price){
        $historyAdd = new feedUserEarnHistory();
        $historyAdd->userId = $userId;
        $historyAdd->postId = $postId;
        $historyAdd->earnType = $earnType; // community_rate Table's bonusKey and extra 1 Type : earnRef
        $historyAdd->price = $price;
        $historyAdd->save();
        return true;
    }
    protected function findvalueOfKey($key){ // if key is earnRef else find from community_rate Table's bonusKey
        if($key == 'earnRef'){
            return Website::first()->referral_earning_commission ?? 0;
        }
        return communityRate::where('bonusKey',$key)->first()->bonusRate ?? 0;
    }
    protected function dailyMaxLimit($key,$userId){
        $start = Carbon::now()->startOfDay();
        $end   = Carbon::now()->endOfDay();
        if($key == 'maxUserLinksPerDay'){
            $maxLimit = (int) $this->findvalueOfKey('maxUserLinksPerDay');
            $foundTodayPost = feedUserEarnHistory::where('userId',$userId)->where('earnType','postViewerLink')->whereBetween('created_at', [$start,$end])->count();
            return $foundTodayPost < $maxLimit;
        }elseif($key == 'maxUserCommentPerDay'){
            $maxLimit = (int) $this->findvalueOfKey('maxUserCommentPerDay');
            $foundTodayPost = feedUserEarnHistory::where('userId',$userId)->where('earnType','postViewerComment')->whereBetween('created_at', [$start,$end])->count();
            return $foundTodayPost < $maxLimit;
        }else{
            return false;
        }
    }
    protected function checkIfEarned($userId, $postId,$earnType){
        $findHistory = feedUserEarnHistory::where('userId',$userId)->where('postId',$postId)->where('earnType',$earnType)->first();
        if($findHistory){
            return true;
        }else{
            return false;
        }
    }
    protected function addNotify($userId,$message,$title = null){
        if($title == null){
            $title = 'Social Notification';
        }
        $notify = new UserMessage();
        $notify->user_id = $userId;
        $notify->message = $message;
        $notify->message_title = $title;
        $notify->seen = 0;
        $notify->save();
        return true;
    }
    protected function findReff($userId){ // Current Logged in Main Id
        $findUser = User::where('id',$userId)->first();
        if(!$findUser){
            return false;
        }
        $findparent = User::where('code',$findUser->rfered_by)->first();
        if(!$findparent){
            return false;
        }elseif($findparent == '10001'){
            return false;
        }
        return $findparent;

    }
    protected function addReffEarn($userId,$postId,$baseAmount,$percent){ // current logged-in main id, post Id and net earn amount
        $earnAmount = number_format(($baseAmount * $percent) / 100, 6, '.', '');  

        $checkParent = $this->findReff($userId);
        if($checkParent){
            $this->addEarnHistory($checkParent->id,$postId,'earnRef',$earnAmount);
            $this->addNotify($checkParent->id,'You Got the ' .$earnAmount .'$ From The Referral <br><a href="/public-shared/' . $postId . '">View post</a>','Referral Earn');
            $checkParent->increment('earning_balance',$earnAmount);
            $user = User::where('id',$userId)->first();
            $user->increment('earning_commision_from_refer',$earnAmount);
            return true;
        }
        return false;
    }
}
