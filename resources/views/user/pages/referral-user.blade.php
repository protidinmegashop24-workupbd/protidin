@extends('user.layouts.master')

@section('css')
<style>
    .rf-list-card {
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 10px 26px rgba(15,23,42,.05);
        overflow: hidden;
    }
    .rf-list-head {
        background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%);
        color: #fff;
        padding: 18px 22px;
        font-size: 22px;
        font-weight: 800;
    }
</style>
@endsection

@section('user-content')
<div class="rf-list-card mt-3">
    <div class="rf-list-head">{{ $title }}</div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Joining Date</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Deposit Commission</th>
                        <th>Earning Commission</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($datas as $data)
                        <tr>
                            <td>{{ $data->code }}</td>
                            <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y g:i A') }}</td>
                            <td>{{ $data->name }}</td>
                            <td>
                                @if($data->referral_activated == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td>${{ number_format((float)$data->deposit_commision_from_refer, 2) }}</td>
                            <td>${{ number_format((float)$data->earning_commision_from_refer, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No referred users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $datas->links() }}
        </div>
    </div>
</div>
@endsection