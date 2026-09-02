@extends('backend.layouts.master')
@section('title')
Social Earning Price setup
@endsection
@section('back-content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Social Earning Price Setup</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Community Rate</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Social Earning Price Setup Update</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form class="row" action="{{route('admin.communityRateSetupStore')}}" method="POST">
                    @csrf

                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="newPost">Post Owner For Post</label>
                        <input name="newPost" type="text" class="form-control" id="newPost" placeholder="New Post Earn"  value="{{ $prices->where('bonusKey','newPost')->first()->bonusRate}}" >
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="maxPostPerDay">Max Post Per Day (Default 1)</label>
                        <input name="maxPostPerDay" type="number" class="form-control" id="maxPostPerDay" placeholder="Max Post Per Day"  value="{{ number_format($prices->where('bonusKey','maxPostPerDay')->first()->bonusRate)}}" >
                    </div>
                    

                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="postViewerLink">Post Viewer Per Like</label>
                        <input name="postViewerLink" type="text" class="form-control" id="postViewerLink" placeholder="Post Viewer Per Like"  value="{{ $prices->where('bonusKey','postViewerLink')->first()->bonusRate}}" >
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="postViewerComment">Post Viewer Per Comment</label>
                        <input name="postViewerComment" type="text" class="form-control" id="postViewerComment" placeholder="Post Viewer Per Comment"  value="{{ $prices->where('bonusKey','postViewerComment')->first()->bonusRate}}" >
                    </div>
                    
                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="maxUserLinksPerDay">Max User Likes Per Day</label>
                        <input name="maxUserLinksPerDay" type="number" class="form-control" id="maxUserLinksPerDay" placeholder="Max Post Per Day"  value="{{ number_format($prices->where('bonusKey','maxUserLinksPerDay')->first()->bonusRate)}}" >
                    </div>
                    
                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="maxUserCommentPerDay">Max User Comment Per Day (Default 1)</label>
                        <input name="maxUserCommentPerDay" type="number" class="form-control" id="maxUserCommentPerDay" placeholder="Max Post Per Day"  value="{{ number_format($prices->where('bonusKey','maxUserCommentPerDay')->first()->bonusRate)}}" >
                    </div>
                    <div class="form-group col-12 row">
                        <div class="col-sm-6">
                            <div class="ui buttons">
                                <button type="submit" class="btn btn-success m-t-15 waves-effect">UPDATE</button>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- /.container-fluid -->
  </section>
@endsection