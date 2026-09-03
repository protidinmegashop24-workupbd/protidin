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
                Video posts show "Pending" until an admin approves them; they will appear in the public feed once approved.
            </p>
        </div>
    </div>
</div>
<hr class="hr">
<div class="row g-4">
    @forelse($posts as $post)
        @php $isApproved = $post->status === 'approved'; @endphp
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
            <a href="{{ $isApproved ? route('publicPostLink', $post->id) : '#' }}"
               class="text-decoration-none text-dark {{ $isApproved ? '' : 'pe-none' }}">

                <div class="card h-100 shadow-sm border-0 post-card">

                    {{-- Media (optional) --}}
                    @if($post->video)
                        <video src="{{ asset($post->video) }}"
                               class="card-img-top"
                               style="height:200px; object-fit:cover; background:#000;"
                               muted preload="metadata"></video>
                    @elseif($post->image)
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
                            @if($isApproved)
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending Review</span>
                            @endif
                        </div>

                        {{-- Content --}}
                        <p class="card-text mb-3">
                            {!! \Illuminate\Support\Str::words(strip_tags($post->postContent), 22, '...') !!}
                        </p>

                        {{-- Footer --}}
                        <div class="mt-auto">
                            @if($isApproved)
                                <span class="text-primary fw-semibold">
                                    View Post →
                                </span>
                            @else
                                <span class="text-muted fw-semibold">
                                    Waiting for admin approval
                                </span>
                            @endif
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