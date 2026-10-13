@extends('backend.layouts.master')

@section('title')
    Community Reports - Dashboard
@endsection

@section('back-content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Community Reports</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Community Reports</li>
          </ol>
        </div>
      </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Reported Posts</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="3%">#</th>
                            <th width="10%">Reported By</th>
                            <th width="30%">Post Content</th>
                            <th width="15%">Post Author</th>
                            <th width="15%">Reason</th>
                            <th width="10%">Status</th>
                            <th width="17%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $report->reporter->name ?? 'N/A' }}</td>
                                <td>
                                    @if($report->post)
                                        {{ Illuminate\Support\Str::limit(strip_tags($report->post->postContent), 100) }}
                                        <br>
                                        <a href="{{ route('publicPostLink', $report->post_id) }}" target="_blank">View Post</a>
                                    @else
                                        <em>Post already deleted</em>
                                    @endif
                                </td>
                                <td>{{ $report->post->user->name ?? 'N/A' }}</td>
                                <td>{{ $report->reason ?: '-' }}</td>
                                <td>
                                    @if($report->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($report->status == 'dismissed')
                                        <span class="badge badge-secondary">Dismissed</span>
                                    @else
                                        <span class="badge badge-danger">Post Hidden</span>
                                    @endif
                                </td>
                                <td>
                                    @if($report->status == 'pending')
                                        <a href="{{ route('admin.community-reports.dismiss', $report->id) }}" class="btn btn-secondary btn-sm" onclick="return confirm('Dismiss this report? The post stays visible.')">Dismiss</a>
                                        <a href="{{ route('admin.community-reports.hide-post', $report->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Hide this post from the feed?')">Hide Post</a>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No reports yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
