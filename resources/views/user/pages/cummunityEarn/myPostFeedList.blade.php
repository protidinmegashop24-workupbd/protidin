@extends('user.layouts.master')
{{-- Css Start From Here For Single Page  --}}
@section('css')

@endsection 
@section('user-content')
<div class="row">
    <div class="col-12">
        <!-- Heading -->
        <div class="mb-4">
            <h4 class="fw-bold">My Posts (Last 6 Months)</h4>
            <p class="text-muted mb-0">
                Only approved posts are shown here
            </p>
        </div>
    </div>
</div>
<hr class="hr">
<div class="row g-4">
    @forelse($posts as $post)
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
            <a href="{{ route('publicPostLink', $post->id) }}"
               class="text-decoration-none text-dark">

                <div class="card h-100 shadow-sm border-0 post-card">

                    {{-- Image (optional) --}}
                    @if($post->image)
                        <img src="{{ asset($post->image) }}"
                             class="card-img-top"
                             alt="Post image"
                             style="height:200px; object-fit:cover;">
                    @endif

                    <div class="card-body d-flex flex-column">
                        {{-- Meta --}}
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted">
                                {{ $post->created_at->diffForHumans() }}
                            </small>
                            <span class="badge bg-success">
                                Approved
                            </span>
                        </div>

                        {{-- Content --}}
                        <p class="card-text mb-3">
                            {!! \Illuminate\Support\Str::words(strip_tags($post->postContent), 22, '...') !!}
                        </p>

                        {{-- Footer --}}
                        <div class="mt-auto">
                            <span class="text-primary fw-semibold">
                                View Post →
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                No posts found for the last 6 months.
            </div>
        </div>
    @endforelse
</div>
@endsection
@section('js')

@endsection