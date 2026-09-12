@extends('layouts.app')

@section('content')
<div class="container">
  <h2 style="margin:10px 0;">Survey Approvals (Admin)</h2>

  @if(session('success'))
    <div style="padding:12px;border-radius:12px;background:rgba(22,163,74,.10);border:1px solid rgba(22,163,74,.30);font-weight:900;">
      {{ session('success') }}
    </div>
  @endif

  <div style="overflow:auto;background:#fff;border:1px solid rgba(0,0,0,.12);border-radius:14px;">
    <table style="width:100%;border-collapse:collapse;">
      <thead>
        <tr>
          <th style="text-align:left;padding:10px;border-bottom:1px solid rgba(0,0,0,.12);">User</th>
          <th style="text-align:left;padding:10px;border-bottom:1px solid rgba(0,0,0,.12);">Survey</th>
          <th style="text-align:left;padding:10px;border-bottom:1px solid rgba(0,0,0,.12);">Code</th>
          <th style="text-align:left;padding:10px;border-bottom:1px solid rgba(0,0,0,.12);">Reward</th>
          <th style="text-align:left;padding:10px;border-bottom:1px solid rgba(0,0,0,.12);">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($subs as $s)
          <tr>
            <td style="padding:10px;border-bottom:1px solid rgba(0,0,0,.08);">
              {{ $s->user?->email ?? $s->user_id }}
            </td>
            <td style="padding:10px;border-bottom:1px solid rgba(0,0,0,.08);">
              {{ $s->survey?->title ?? '-' }}
            </td>
            <td style="padding:10px;border-bottom:1px solid rgba(0,0,0,.08);">
              <b>{{ $s->unique_code }}</b>
            </td>
            <td style="padding:10px;border-bottom:1px solid rgba(0,0,0,.08);">
              ৳{{ number_format($s->survey?->reward ?? 0,2) }}
            </td>
            <td style="padding:10px;border-bottom:1px solid rgba(0,0,0,.08);">
              <form method="POST" action="{{ route('admin.approvals.approve',$s->id) }}" style="display:inline;">
                @csrf
                <button style="padding:8px 10px;border-radius:10px;border:none;background:#16a34a;color:#fff;font-weight:900;cursor:pointer;">
                  Approve
                </button>
              </form>

              <form method="POST" action="{{ route('admin.approvals.reject',$s->id) }}" style="display:inline;margin-left:6px;">
                @csrf
                <button style="padding:8px 10px;border-radius:10px;border:1px solid rgba(0,0,0,.18);background:#fff;font-weight:900;cursor:pointer;">
                  Reject
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="padding:12px;opacity:.8;">No pending approvals.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div style="margin-top:10px;">
    {{ $subs->links() }}
  </div>
</div>
@endsection
